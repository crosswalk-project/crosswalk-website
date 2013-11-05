#!/bin/bash
. scripts/common.inc

debug=${debug:=0}
command=$(basename ${0})

function usage () {
cat << EOF
usage: ${command} <command> [<args>]

EOF

max_len=0
names=()
desc=()
for script in scripts/*.sh; do
    names[$i]=$(basename ${script/.sh})
    desc[$i]='No description'
    (( ${#names[$i]}>max_len )) && max_len=${#names[$i]}
    i=$((i+1))
done
for (( j=0; j<i; j++ )); do
    printf "   %-*s   %-.*s\n" ${max_len} "${names[$j]}" $((73-max_len)) "${desc[$j]}"
done
cat << EOF

NOTE: Most commands require that there are no changed files on disk.

EOF
}

[ "$1" = "" ] && usage