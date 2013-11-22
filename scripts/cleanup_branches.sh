desc="Remove all live-* branches older than one week."
function run () {
    declare current
    declare dry_run=
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
    for i in . wiki; do
        echo "Examining $i"
        # Remove remotes
        git --git-dir=$i/.git --work-tree=$i branch -a | 
            grep -E "remotes/origin/live-" | while read branch; do
            branch_name=${branch/*origin\/}
            branch_date=${branch/*live-}
            branch_date=${branch_date//.*}
            if (( branch_date <= last_week )); then
                if [[ "$branch_name" == "$current" ]]; then
                    echo "Skipping current live branch (${current})"
                    continue
                fi
                work_done=1
                ${dry_run} git push origin :${branch_name}
                ${dry_run} git branch -D ${branch_name}
            fi
        done
        # Remove remaining locals
        git --git-dir=$i/.git --work-tree=$i branch -a | 
            grep -E " live-" | while read branch; do
            branch_name=${branch/  /}
            branch_date=${branch/*live-}
            branch_date=${branch_date//.*}
            if (( branch_date <= last_week )); then
                if [[ "$branch_name" == "$current" ]]; then
                    echo "Skipping current live branch (${current})"
                    continue
                fi
                work_done=1
                ${dry_run} git push origin :${branch_name}
                ${dry_run} git branch -D ${branch_name}
            fi
        done
        
        # Remove old tags
        git --git-dir=$i/.git --work-tree=$i tag | 
            grep "tag-.*live" | while read branch; do
            branch_name=${branch}
            branch_date=${branch/*tag-live-}
            branch_date=${branch_date//.*}
            if (( branch_date <= last_week )); then
                if [[ "$branch_name" == "$current" ]]; then
                    echo "Skipping current live branch (${current})"
                    continue
                fi
                work_done=1
                ${dry_run} git tag -d ${branch_name}
            fi
        done
    done
    (( work_done )) && {
        ${dry_run} git remote prune origin
cat << EOF
Branches have been purged.
EOF
    }
}
