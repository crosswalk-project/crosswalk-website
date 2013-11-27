#!/bin/bash
function die () {
	echo -ne $*
	echo -e "\nTerminating."
	exit -1
}

[ "${desc}" = "" ] && [ "$1" = "--desc" ] && {
    echo "No description provided."
    exit
}

function get_local_live_name () {
    branchname $(get_local_live_info $*)
}
    
function get_local_live_info () {
    local branch
    branch=$(git branch -a | sed -ne 's,.*[ /]\(live-.*\),\1,p' | sort -ur | head -n 1)
    info=${branch}:$(branchsha ${branch})
    echo $info
    [ "${info/*:/}" != "" ]
    return
}

function branchsha () {
    local sha=$1
    sha=${sha/*:/}
    if [[ "$sha" == "$1" ]] || [[ "$sha" == "" ]] ; then
        branch=${1/:*/}
        # Obtain the SHA for ${branch} from either remote (if available) or
        # local
        git show-ref --verify --quiet refs/remotes/origin/${branch} &&
            sha=$(git log -1 --pretty=format:%H origin/${1/:*/}) ||
            sha=$(git log -1 --pretty=format:%H ${1/:*/})
    fi
    echo $sha
    [ "${sha}" != "" ]
    return
}

function branchname () {
    local branch=$1
    echo ${branch/:*/}
}

function get_remote_live_name () {
    branchname $(get_remote_live_info $*)
}
    
function get_remote_live_info () {
    local branch
    local revision
    local url
    if [[ "$1" = "--previous" ]]; then
        revision=PRIOR-REVISION
        shift
    else
        revision=REVISION
    fi
    case "$1" in
    staging)
        url=https://stg.crosswalk-project.org
        ;;
    *)
        url=https://crosswalk-project.org
        ;;
    esac
    branch=$(wget --no-check-certificate -qO - ${url}/${revision})
    echo $branch
}

declare -i gollum_pid=0

function debug_msg () {
    (( debug )) && {
        echo -n $*
        echo " Enter to continue." 
        read
    }
}

function generate () {
    file=$1
	echo -n "Processing ${file/.\//}..."
	php gfm.php ${file/.\//} > /dev/null
	echo "done."
	html=${file/.\//}.html
	[ ! -e ${html} ] && {
		echo "Could not create ${html}."
		exit 1
	}

	find ${html} -size 0 | grep -q ${html} && {
		echo "Could not generate ${html}."
		exit 1
	}
}
    
function launch_gollum () {
    gollum_pid=0
    test=$(ps -o pid= -C gollum)
    (( test==0 )) && {
        gollum ${PWD} >/dev/null 2>&1 &
        gollum_pid=$!
        echo -n "Launching gollum."
        x=1
        while (( $x )); do
            echo -n "."
            sleep 0.5
            netstat -na 2>&1 | grep -q ":4567 "
            x=$?
        done
        echo ""
    }
}

function kill_gollum () {
    (( gollum_pid )) && {
        kill -9 ${gollum_pid}
    }
}

function check_perms () {
    return 0
    echo ''
    echo -n 'Looking for files not writable by :www-data...'
    found=$(find . -not -group www-data -or \( \
            -not -user www-data \
            -and -not -perm -g=rwX \
        \) | grep -v \.git | wc -l)
    (( ${found} )) && {
        echo "${found} found."
        echo ''
        echo 'Please correct and try again.'
        echo 'Correct via:'
        echo '  site.sh perms'
        echo ''
        exit 1
    }
    echo 'none found. OK.'
    return 0
}

function update_git () {
	git checkout master
	git pull --all || {
	        echo "Pulling from tip failed. Reverting to active branch: ${active}"
	        git checkout ${active} -f
	        exit 1
	}
}

function check_unstaged () {
    git diff --quiet --ignore-submodules --exit-code || {
		echo "Can't go live with uncommitted changes in ${dir}"
		git diff --name-status --ignore-submodules
		exit 1
	}
}
