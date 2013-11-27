function usage () {
cat << EOF
usage: site.sh cleanup_branches [-n]

   -n   Dry run. Do not change anything locally or remote.

EOF
}

function process_branch () {
    declare git_dir=$1
    declare mode=$2
    declare current=$3
    declare branch=$4
    declare last_week=$5
    
    branch_name=${branch/  /}
    branch_date=${branch/*live-}
    branch_date=${branch_date//.*}
    if (( branch_date <= last_week )); then
        if [[ "$branch_name" == "$current" ]]; then
            echo "Skipping current live branch (${current})"
            continue
        fi
        if [[ "${mode}" == "local" ]]; then
            ${dry_run} git --git-dir=$i branch -D ${branch_name}
        else
            ${dry_run} git --git-dir=$i push origin :${branch_name}
        fi
        true
    else
        false
    fi
}

function run () {
    declare current
    if [[ "${dry_run}" == "1" ]]; then
        dry_run="echo "
    fi
    if [[ "$1" == "-n" ]]; then
        dry_run=echo
        shift
    fi
    
    echo -n "Determining current live version..."
    current=$(get_remote_live_name)
    echo "${current}"
    
    today=$(date +%Y%m%d)
    last_week=$((today - 14))
    work_done=0
    for i in .git wiki.git; do
        echo "Examining $i"
        # Remove remotes
        git --git-dir=$i remote show origin -n | while read branch rest; do
            if [[ "${branch}" =~ ^Local.* ]]; then
                break
            fi
            if [[ "${branch}" == "live-"* ]]; then
                process_branch $i remote ${current} ${branch} ${last_week}
            fi
        done
        # Remove locals
        git --git-dir=$i branch | while read branch; do
            if [[ ! "${branch}" =~ live-.* ]]; then
                continue
            fi
            process_branch $i local ${current} ${branch} ${last_week}
        done
        
        # Remove old tags
        git --git-dir=$i tag | 
            grep "tag-.*live" | while read tag; do
            tag_name=${tag}
            tag_date=${tag/*tag-live-}
            tag_date=${tag_date//.*}
            if (( tag_date <= last_week )); then
                if [[ "$tag_name" == "$current" ]]; then
                    echo "Skipping current live tag (${current})"
                    continue
                fi
                ${dry_run} git --git-dir=$i push origin :refs/tags/${tag_name}
                ${dry_run} git --git-dir=$i tag -d ${tag_name}
            fi
        done
        
        ${dry_run} git --git-dir=$i remote prune origin
    done

cat << EOF
Branches and tags have been purged. Consider running:

  git gc [-n]  Cleanup unnecessary files and optimize local repo
  
EOF
}
