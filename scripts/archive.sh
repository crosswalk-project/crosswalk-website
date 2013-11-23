desc="Creates a tar-ball archive of the live site"
function usage () {
    cat << EOF
usage: site.sh archive [<commit>]

Creates a tar-ball archive of the live site based on the live-* branch
provided, or the most recent live-* branch if none specified.

The created archive is of the form branch.tar.gz, for example:

  ./site.sh archive live-20131122.1

would create

  live-20131122.1.tar.gz

EOF
}

function run () {
    declare branch
    
    if [[ "$1" = "" ]]; then
        branch=$(get_local_live_name)
    else
        branch=$1
        shift
    fi

    echo -n "Creating ${branch}.tar.gz..."    
    git archive --prefix=${branch}/ -o ${branch}.tar.gz ${branch} && {
        echo "success."
    } || {
        echo "failed."
    }

}
