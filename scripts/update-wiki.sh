desc="Pull latest wiki, apply to current live-* branch on server"

function usage () {
cat << EOF
  usage: site.sh update-wiki [<commit>|staging|live|latest]

  Pull the latest wiki content from GitHub and apply any
  content changes against the specified target.
  
  If no target specified, or set to 'live', the active branch 
  name on crosswalk-project.org will be used as the target.
  
  If target is 'staging', stg.crosswalk-project.org will be used.
  
  If target is 'latest', the most recent local live-* branch name
  will be used.
  
  Otherwise target will be used as the target to apply the changes 
  to.
  
EOF
}

function run () {

# The update-wiki scripts performs the following tasks:
#
# 0. Ensure all files are writable by www-data
# 1. Ensure currently on 'master'
# 2. Ensure there are no local unstaged changes
# 3. Pull the latest changes from GitHub into the wiki/
#    NOTE: If no changes, abort at this point
# 4. Switch to the live-* branch currently in use
# 5. Remove all *.html from wiki/ not in documentation/ or contribute/
# 6. Generate the menu files
# 7. Commit the changes to the live-* branch
# 8. Reset local back to master branch for the website and wiki
# 9. Display message for how to push changes to staging site
#
case "$1" in
    ""|live)
        echo -n "Fetching active branch name from crosswalk-project.org..." >&2
        target=$(get_remote_live_name)
        echo "${target}" >&2
        ;;
    staging)
        echo -n "Fetching active branch name from stg.crosswalk-project.org..." >&2
        target=$(get_remote_live_name staging)
        echo "${target}" >&2
        ;;
    latest)
        target=$(get_local_live_name)
        ;;
    *)
        target="$1"
        ;;
esac

debug=${debug:=0}

WIKI_GIT="--git-dir=wiki/.git --work-tree=wiki/"

# 0. Ensure all files are writable by www-data
check_perms

# 1. Ensure currently on 'master'
git branch | grep -q '\* master' || {
	echo ""
	echo "mklive.sh can only be run in the 'master' branch."
	echo "$ git checkout master"
	echo ""
	exit
}

# 2. Ensure there are no local unstaged changes
[ "$1" != "-f" ] && check_unstaged

active=$(git branch | grep "^\*.*")
active=${active/\* }
echo "Current branch: ${active}"
sha=$(git show --oneline ${active} | head -n 1)
sha=${sha// *}

branch=$(git branch -a | grep remotes.*live | sort -r | head -n 1)
branch=${branch//  remotes\/origin\//}
echo "Latest live branch: ${branch}"
debug_msg "Branch status above."

# 3. Fetch the latest changes from GitHub into the wiki/
git ${WIKI_GIT} fetch --all || {
	echo "Updating wiki/ latest from GitHub failed. Exiting."
	exit -1
}

git ${WIKI_GIT} diff --quiet --exit-code tag-${branch} && {
    echo "No changes to Wiki since ${branch}"
    exit
}
debug_msg "Wiki has been pulled from GitHub."

# 4. Switch to the latest live-* branch
branch=$(wget -O - https://crosswalk-project.org/REVISION)
branch=${branch/:*}
echo "Checking out branch for site: ${branch}"

git checkout ${branch} || {
    echo "Checking out ${branch} failed."
    exit -1
}
debug_msg "Branch / Checkout to ${branch} complete."

# 6. Regenerate the pages and history
generate "wiki/pages.md"
generate "wiki/history.md"
    
git add wiki/pages.md.html wiki/history.md.html
debug_msg "Pages and History files added."

# 7. Commit the changes to the live-* branch
git commit -s -a -m "Automatic static version commit for ${branch}"
debug_msg "Live-site commited to ${branch}"

# 8. Reset local back to master branch for the website and wiki
git checkout master || die "Checkout failed."

git ${WIKI_GIT} tag -f tag-${branch} master

# 13. Display message for how to push changes to staging site
cat << EOF

Changes committed to git as branch ${branch}.

Current tree set back to 'master'. Steps to take:

  1. Push ${branch} to staging server:
  
     ./site.sh push ${branch}
  
  2. Test the site by going to https://stg.crosswalk-project.org
     2.1 Visit each category section. Make sure they work.
         https://stg.crosswalk-project.org/#documentation
         https://stg.crosswalk-project.org/#contribute
         https://stg.crosswalk-project.org/#wiki
     2.2 Check the Wiki History and verify the newest changes exist
         https://stg.crosswalk-project.org/#wiki/history
  
  3. Resize your browser window down to a width of 320 and up to 
     full screen. Ensure responsize design still functions.

  4. After you are confident that the site is good, push the version
     from the Staging server to the Live server:
     
    ./site.sh push live

EOF
}
