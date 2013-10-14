#!/bin/bash

# The update-wiki scripts performs the following tasks:
#
# 0. Ensure all files are writable by www-data
# 1. Ensure currently on 'master'
# 2. Ensure there are no local unstaged changes
# 3. Switch to the latest live-* branch
# 4. Pull the latest changes from GitHub into the wiki/
# 5. Remove all *.html from wiki/ not in documentation/ or contribute/
# 6. Launch Gollum if it isn't running
# 7. Regenerate all markdown content to HTML
# 8. Kill Gollum if #6 launched it.
# 9. Remove all Wiki markdown content from the Live site
# 10. Commit the changes to the live-* branch
# 11. Reset local back to master branch for the website and wiki
# 12. Display message for how to push changes to staging site
#

. common.sh

dir=${PWD}

debug=1

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


# 3. Switch to the latest live-* branch
echo "Checking out branch: ${branch}"
git checkout ${branch} || {
    echo "Checking out ${branch} failed."
	cd ${dir}
    exit -1
}    
debug_msg "Branch / Checkout to ${branch} complete."


# 4. Pull the latest changes from GitHub into the wiki/
cd wiki
git pull --all || {
	echo "Updating wiki/ latest from GitHub failed. Exiting."
	cd ${dir}
	exit -1
}
debug_msg "Wiki has been pulled from GitHub."

# 5. Remove all *.html from wiki/ not in documentation/ or contribute/
find . -type f -name '*.html' -not \( \
        -path '*/documentation*/' \
        -or -path '*/contribute/*' \
    \) -and -exec rm -f {} \;
debug_msg "All Wiki *html content purged."

# 6. Launch Gollum if it isn't running
launch_gollum

# 7. Regenerate all markdown content to HTML
find . -type f -not \( \
        -path "*/contribute/*" \
        -or -path "*/documentation/*" \
        -or -path "*/.git/*" \
        -or -name "*.html" \
        -or -path "*/assets/*" \
    \) | while read file; do
	[ "${file}" == "./gfm.php" ] && continue
	[ "${file}" == "./.htaccess" ] && continue
	[ "${file}" == "./php_errors.log" ] && continue
	[ "${file}" == "./custom.js" ] && continue
    generate "${file}"
done
generate "pages.md"
generate "history.md"
debug_msg "All Wiki content generated purged."

# 8. Kill Gollum if #6 launched it.
kill_gollum

# 9. Remove all Wiki markdown content from the Live site
cd ..
find wiki \( \
        -name '*.md' \
        -or -name '*.mediawiki' \
        -or -name '*.org' \
    \) -and -not \( \
        -path "*/contribute/*" \
        -or -path "*/documentation/*" \
        -or -path "*/.git/*" \
        -or -name "*.html" \
        -or -path "*/assets/*" \
    \)
    -exec rm {} \;
debug_msg "Wiki markdown purged pre live-site commit."

# 10. Commit the changes to the live-* branch
git commit -s -a -m "Automatic static version commit for ${branch}"
debug_msg "Live-site commited to ${branch}"

# 11. Reset local back to master branch for the website and wiki
cd wiki
git clean -f
git checkout -f
git tag -f tag-${branch}
cd ..

git checkout master
git tag -f tag-${branch} 

# 12. Display message for how to push changes to staging site
cat << EOF

Changes committed to git as branch ${branch}.

Current tree set back to 'master'. Commands to run:

git push --tags origin master
git push origin ${branch}
ssh stg-sites.vlan14.01.org "cd /srv/www/stg.crosswalk-project.org/docroot ; sudo su drush -c '../update.sh'"
cd wiki
git push --tags origin master
cd ..

EOF

cd ${dir}