#!/bin/bash
desc="Site management script"
. scripts/common.inc

debug=${debug:=0}
declare names=()
declare desc=()
declare valid=()
declare max_len=0

for script in scripts/*.sh; do
    names[$i]=$(basename ${script/.sh})
    # Verify the script starts with an inclusion of common.inc
    first_line=$(grep -m 1 -v -E '^((#.*)|([[:blank:]]*))$' ${script})
    echo ${first_line}
    if [[ "${first_line}" =~ ^\..+scripts/common\.inc.*$ ]]; then
        cd scripts
        desc[$i]=$(./$(basename ${script}) --desc)
        cd ..
        valid[$i]=1
    else
        desc[$i]='Invalid script'
        valid[$i]=0
    fi
    (( ${#names[$i]}>max_len )) && max_len=${#names[$i]}
    i=$((i+1))
done

function usage () {
cat << EOF
usage: site.sh [--help] 
               <command> [<args>]

EOF

for (( j=0; j<i; j++ )); do
    printf "   %-*s   %-.*s\n" ${max_len} "${names[$j]}" $((73-max_len)) "${desc[$j]}"
done
cat << EOF

NOTE: Most commands require that there are no changed files on disk.

EOF
}

[ "$1" = "" ] && usage
i=${#names}
for (( j=0; j<i; j++ )); do
    echo ${valid[$j]}
    if [[ "${names[$j]}" = "$1" ]]; then
        if (( ${valid[$j]} == 1)); then
            shift
            . "scripts/${names[$j]}.sh" $*
        fi
        exit
    fi
done