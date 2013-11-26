#!/bin/bash
desc="Site management script"
app=$0
cmd=$0
if [[ ! -e "$cmd" && "$cmd" =~ .*\/.* ]]; then
    cmd=$(which "$cmd")
fi
dir=$(dirname "$cmd")/scripts
. ${dir}/common.inc

debug=${debug:=0}
declare names=()
declare descs=()
declare valid=()
declare max_len=0

function scope_source () {
    . $1
    echo $desc
}

for script in "${dir}"/*.sh; do
    names[$i]=$(basename ${script/.sh})
    # Verify the script starts with an inclusion of common.inc
    first_line=$(grep -m 1 -v -E '^((#.*)|([[:blank:]]*))$' ${script})
    desc=$(scope_source "$script")
    if [[ "$desc" == "" ]]; then
        valid[$i]="0"
    else
        valid[$i]="1"
    fi
    descs[$i]=$desc
    (( ${#names[$i]}>max_len )) && max_len=${#names[$i]}
    i=$((i+1))
done

function usage () {
cat << EOF
usage: $app [--help] 
               <command> [<args>]

EOF
# Loop through all of the defined scripts and display them
for (( j=0; j<i; j++ )); do
    printf "   %-*s   %-.*s\n" ${max_len} "${names[$j]}" $((73-max_len)) "${descs[$j]}"
done
cat << EOF

NOTE: Most commands require that there are no changed files on disk.

EOF
exit
}

function execute_script () {
    local site_script=$1
    local site_cmd=$2
    shift 2
    unset -f ${site_cmd}
    . "${dir}/${site_script}.sh"
    ${site_cmd} $*
    exit
}

# If --help was passed, then show the sub-command usage
if [[ "$1" = "--help" ]]; then
    cmd="usage"
    shift
else
    cmd="run"
fi

if [[ "$1" = "" ]]; then
    usage
fi

j=0
while [[ "${names[$j]}" != "" ]]; do
    if [[ "${names[$j]}" = "$1" ]]; then
        if [[ "${valid[$j]}" == "1" ]]; then
            shift
            for v in $@; do
                if [[ "$v" == "--help" ]]; then
                    cmd="usage"
                fi
            done
            execute_script "${names[$j]}" $cmd $*
        else
            echo "$1 is an invalid script."
        fi
        exit
    fi
    j=$((j+1))
done

cat << EOF
$app: '$1' is not a site command. See '$app --help'.

EOF
exit