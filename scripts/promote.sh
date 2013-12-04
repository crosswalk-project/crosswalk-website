#  This script will perform the following:
#  
#  1. Determine the version of the website active on the live server (eg. live-20131202)
#  2. stash any current changes to master
#  3. update the versions.js file
#  4. commit that change to master
#  5. checkout the live branch
#  6. apply the versions.js file to the live branch
#  7. commit that change to the live branch
#  8. push the live branch to GitHub
#  9. Optionally activate the branch on the staging server via
#     
#    site.sh push live-20131202
#    
#  At this point, the staging server should be tested to ensure the version update
#  works as appropriate. Once satisfied, run:
#    
#    site.sh push live
#    
#  To push the version from the staging server to the live server.


desc="Promote a release version for a given channel"

declare channel=""
declare platform=""
declare arch=""
declare version=""
declare STAGE_GIT=""

function usage () {
cat << EOF
usage: site.sh promote <channel> <platform> <architecture> <version>

  channel is either 'beta' or 'stable'
  platform is one of 'tizen' or 'android'
  arch is either 'x86' or 'arm'
  version is of the form A.B.C.D
 
Example:
  ${cmd} promote stable android x86 2.31.27.0
  ${cmd} push
  
  Visit https://stg.crosswalk-project.org and verify the site lists
  the correct version on the main page.
  
  You should also verify the links on the download page:
  
    https://stg.crosswalk-project.org/#documentation/downloads
  
  Once verified:
  
  ${cmd} push live
  
EOF
}

function query_diff () {
    branch="$1"

    while true; do 
        git diff --exit-code ${branch} -- versions.js &&
            echo "No diferences."
        echo -n "Is the above diff correct? [Yn] "
        read answer
        case $answer in
        ""|Y|y)
            true
            return
            ;;
        N|n)
            cat << EOF
            
Exiting. You will need to manually edit 'versions.js'. When done, re-run:

    ${cmd} promote --manual-edit
    
EOF
            false
            return
            ;;
        esac
    done
}    

function update_versions () {
    pattern="s,(${channel}:.*?${platform}:.*?${arch}: \")[^\"]*,\${1}${version},s"     
    cat versions.js | perl -077 -pe "${pattern}" > versions.js.tmp
    mv versions.js.tmp versions.js
}


# usage: site.sh promote <channel> <platform> <architecture> <version>
function run () {
    while [[ "$1" =~ -.* ]]; do
        case "$1" in
        "--manual-edit")
            skip_update=1
            shift
            ;;
        *)
            echo "error: unknown switch '${1}'"
            usage
            false
            return
            ;;
        esac
    done
    
    channel="${1,,}"
    platform="${2,,}"
    arch="${3,,}"
    version="$4"
    
    if [[ "${channel}" == "" || 
          "${platform}" == "" || 
          "${arch}" == "" || 
          "${version}" == "" ]]; then
        usage
        false
        return
    fi
    
    case ${channel} in
    "beta"|"stable")
        ;;
    *)
        echo "Unrecognized channel: ${channel}"
        false
        return
    esac

    case ${arch} in
    "arm"|"x86")
        ;;
    *)
        echo "Unrecognized architecture: ${arch}"
        false
        return
    esac

    case ${platform} in
    "windows"|"linux"|"android"|"tizen"|"osx")
        ;;
    *)
        echo "Unrecognized platform: ${platform}"
        false
        return
    esac

    if ! [[ "$version" =~ ^([0-9]+\.){3}[0-9]+$ ]]; then
        echo "Invalid version syntax: ${version}"
        false
        return
    fi

    git branch | grep -q '\* master' || {
        cat << EOF
    
${cmd} promote must be run in the 'master' branch. Steps:

  git stash
  git checkout master
  ${cmd} promote <arguments>
  git pop
  
EOF
        false
        return
    }

    git diff --quiet --exit-code -- versions.js || {
        cat << EOF
Uncommitted changes detected in versions.js. Run:

    git commit -s -m 'Updated versions.js' -- versions.js
    
to commit the file.
EOF
        false
        return
    }

    echo -n "Fetching staging branch name from stg.crosswalk-project.org..." >&2
    live=$(get_remote_live_name live)
    echo "done"

    git show-ref --verify --quiet refs/heads/${live} || {
        echo -n "Fetching ${live} from GitHub..."
        git fetch origin ${live}:${live} || die "Failed."
        echo "done"
    }

    git diff --quiet --ignore-submodules --exit-code || {
        git stash
        stash=1
    }

    (( $skip_update )) && {
        echo "Using versions.js from master."
    } || {
        update_versions || {
            echo "Unable to set versions.js appropriately."
            (( $stash )) && {
                echo -n "Restoring working directory..."
                git stash pop --quiet || die "git pop failed"
                echo "done"
            }
            false
            return
        }

        query_diff HEAD -- versions.js || {
            git diff HEAD -- versions.js | patch -p1 -R || 
                die "Unable to reset to previous state. 'git stash' still active."
            (( $stash )) && {
                echo -n "Restoring working directory..."
                git stash pop --quiet || die "git pop failed"
                echo "done"
            }
            false
            return
        }
        
        git commit -s -m \
            "Bumped ${platform}-${arch^^} ${channel^^} to ${version}." \
            -- versions.js
    }

    echo "Switching to ${live}..."
    git checkout ${live}
    echo "Applying versions.js from master..."
    git checkout master -- versions.js
    echo "Committing to versions.js to ${live}..."
    git commit -s -m \
        "Bumped ${platform}-${arch^^} ${channel^^} to ${version}." \
        -- versions.js
    echo "Restoring GIT tree to original state"
    git checkout master
    (( $stash )) && git stash apply

    echo "Pushing ${live} to GitHub..."
    git push origin ${live}:${live} || die "Push to server failed."
    
    while true; do 
        echo -n "Push ${live} to staging server? [Yn] "
        read answer
        case $answer in
        ""|Y|y)
            ${cmd} push ${live}
            return
            ;;
        N|n)
            false
            return
            ;;
        esac
    done
}
