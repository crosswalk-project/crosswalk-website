#!/bin/bash
today=$(date +%Y%m%d)
last_week=$((today - 7))
work_done=0
git branch -a | grep ".*live" | while read branch; do
	branch_date=${branch/*live-}
	branch_date=${branch_date//.*}
	if (( branch_date <= last_week )); then
		work_done=1
		git push origin :${branch/*origin\/}
		git branch -D ${branch/*origin\/}
	fi
done
(( work_done )) && {
cat << EOF
Branches have been purged. Commands to run:

git gc
git push --all
EOF
}
