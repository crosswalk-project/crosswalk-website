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
# 6. Launch Gollum if it isn't running
# 7. Regenerate all markdown content to HTML
# 8. Kill Gollum if #6 launched it.
# 9. Remove all Wiki markdown content from the Live site
# 10. Add any new files (not in documentation/contribute) into the live-site
# 11. Commit the changes to the live-* branch
# 12. Reset local back to master branch for the website and wiki
# 13. Display message for how to push changes to staging site
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
echo "Using ${target} as target."
return

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
cd wiki
[ "$1" != "-f" ] && check_unstaged
cd ..

active=$(git branch | grep "^\*.*")
active=${active/\* }
echo "Current branch: ${active}"
sha=$(git show --oneline ${active} | head -n 1)
sha=${sha// *}

branch=$(git branch -a | grep remotes.*live | sort -r | head -n 1)
branch=${branch//  remotes\/origin\//}
echo "Latest live branch: ${branch}"
debug_msg "Branch status above."

# 3. Pull the latest changes from GitHub into the wiki/
git ${WIKI_GIT} pull --all || {
	echo "Updating wiki/ latest from GitHub failed. Exiting."
	exit -1
}
git ${WIKI_GIT} checkout -f
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

# 5. Launch Gollum if it isn't running
launch_gollum

cd wiki && {
    # 6. For each file edited, if it is a markdown file, 
    #    delete the .html for the file (if it exists) and then 
    #    html for it.
    git diff --name-only tag-${branch} | while read file; do
        [ -e "${file}.html" ] && rm -f "${file}.html"
        extension="${file##*.}"
        case "${extension}" in
        "md" | "mediawiki" | "org")
            generate "${file}"
            git --git-dir=../.git --work-tree=.. add "${file}".html
            ;;
        *)
            git --git-dir=../.git --work-tree=.. add "${file}"
            ;;
        esac
    done
    debug_msg "Content files added."
    
    # Since content was changed, regenerate the pages and history
    generate "pages.md"
    generate "history.md"
    cd ..
}
    
git add wiki/pages.md.html wiki/history.md.html
debug_msg "Pages and History files added."

# 8. Kill Gollum if #6 launched it.
kill_gollum

# 11. Commit the changes to the live-* branch
git commit -s -a -m "Automatic static version commit for ${branch}"
debug_msg "Live-site commited to ${branch}"

# 12. Reset local back to master branch for the website and wiki
git checkout master || die "Checkout failed."

git ${WIKI_GIT} clean -f
git ${WIKI_GIT} checkout -f
git ${WIKI_GIT} tag -f tag-${branch}

# 13. Display message for how to push changes to staging site
cat << EOF

Changes committed to git as branch ${branch}.

Current tree set back to 'master'. Commands to run:

git push origin ${branch}
git ${WIKI_GIT} push origin tag-${branch}
ssh stg-sites.vlan14.01.org "cd /srv/www/stg.crosswalk-project.org/docroot ; sudo su drush -c '../update.sh'"

EOF
}
