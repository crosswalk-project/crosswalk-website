#!/bin/bash
today=$(date +%Y%m%d)
last_week=$((today - 7))
work_done=0
git branch | grep -v '\*.*' | while read branch; do git branch -D ; done
git branch -r | grep ".*/live" | while read branch; do
	branch_date=${branch/*origin\/live-}
	branch_date=${branch_date//.*}
	if (( branch_date <= last_week )); then
		work_done=1
		git branch -d -r ${branch}
	fi
done
(( ${work_done} )) && {
cat << EOF
Branches have been purged. Commands to run:

git gc
git push --all
EOF
}
