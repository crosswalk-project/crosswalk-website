#!/bin/bash

# The update-wiki scripts performs the following tasks:
#
# 0. Ensure all files are writable by www-data
# 1. Ensure currently on 'master'
# 2. Ensure there are no local unstaged changes
# 3. Pull the latest changes from GitHub into the wiki/
#    NOTE: If no changes, abort at this point
# 4. Switch to the latest live-* branch
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

. common.sh

dir=${PWD}

debug=${debug:=0}

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
cd wiki
git pull --all || {
	echo "Updating wiki/ latest from GitHub failed. Exiting."
	cd ${dir}
	exit -1
}
git checkout -f
git diff --quiet --exit-code tag-${branch} && {
    echo "No changes to Wiki since ${branch}"
	cd ${dir}
    exit
}
debug_msg "Wiki has been pulled from GitHub."

# 4. Switch to the latest live-* branch
echo "Checking out branch for site: ${branch}"
cd ..
git checkout ${branch} || {
    echo "Checking out ${branch} failed."
	cd ${dir}
    exit -1
}
debug_msg "Branch / Checkout to ${branch} complete."

# 5. Launch Gollum if it isn't running
launch_gollum

cd wiki

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

git add wiki/pages.md.html wiki/history.md.html
debug_msg "Pages and History files added."

# 8. Kill Gollum if #6 launched it.
kill_gollum

# 11. Commit the changes to the live-* branch
git commit -s -a -m "Automatic static version commit for ${branch}"
git tag -f tag-${branch}
debug_msg "Live-site commited to ${branch}"

# 12. Reset local back to master branch for the website and wiki
git checkout master || die "Checkout failed."
git tag -f tag-${branch} 

cd wiki
git clean -f
git checkout -f
cd ..

# 13. Display message for how to push changes to staging site
cat << EOF

Changes committed to git as branch ${branch}.

Current tree set back to 'master'. Commands to run:

git push -f --tags origin master
git push origin ${branch}
ssh stg-sites.vlan14.01.org "cd /srv/www/stg.crosswalk-project.org/docroot ; sudo su drush -c '../update.sh'"
cd wiki
git push -f --tags origin master
cd ..

EOF

cd ${dir}
