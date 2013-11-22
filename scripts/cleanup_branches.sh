function usage () {
cat << EOF
usage: site.sh cleanup_branches [-n]

   -n   Dry run. Do not change anything locally or remote.

EOF
}


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
                ${dry_run} git --git-dir=$i/.git --work-tree=$i push origin :${branch_name}
                ${dry_run} git --git-dir=$i/.git --work-tree=$i branch -D ${branch_name}
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
                ${dry_run} git --git-dir=$i/.git --work-tree=$i push origin :${branch_name}
                ${dry_run} git --git-dir=$i/.git --work-tree=$i branch -D ${branch_name}
            fi
        done
        
        # Remove old tags
        git --git-dir=$i/.git --work-tree=$i tag | 
            grep "tag-.*live" | while read tag; do
            tag_name=${tag}
            tag_date=${tag/*tag-live-}
            tag_date=${tag_date//.*}
            if (( tag_date <= last_week )); then
                if [[ "$tag_name" == "$current" ]]; then
                    echo "Skipping current live tag (${current})"
                    continue
                fi
                ${dry_run} git --git-dir=$i/.git --work-tree=$i push origin :refs/tags/${tag_name}
                ${dry_run} git --git-dir=$i/.git --work-tree=$i tag -d ${tag_name}
            fi
        done
    done

    ${dry_run} git remote prune origin
cat << EOF
Branches and tags have been purged. Consider running:

  git gc [-n]  Cleanup unnecessary files and optimize local repo
  
EOF
}
