desc="Show difference between local and live server"

function usage () {
    echo "site site-diff [<commit>] [--] [<path>...]"
}

function run () {
    declare local
    declare remote
    declare range
    
    if [[ "$1" = "" || "$1" = "--" ]]; then
        local=$(get_local_live_name)
    else
        local=$1
        shift
    fi
    
    if [[ "$local" =~ ^.*[^.]\.\.\.?[^.].*$ ]]; then
        range=$local
    else
        if [[ "$1" = "" || "$1" = "--" ]]; then
            echo -n "Fetching active branch name from crosswalk-project.org..." >&2
            remote=$(get_remote_live_name)
            echo  "${remote}" >&2
        else
            remote=$local
            local=$1
            shift
        fi
        range="${remote}..${local}"
    fi
    

    echo "Showing diff between ${range}"
    git diff --exit-code ${range} -- $*
    if [[ $? == 0 ]]; then
        echo "No changes."
    fi
}