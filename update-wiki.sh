#!/bin/bash

# The update-wiki scripts performs the following tasks:
#
# 0. Ensure all files are writable by www-data
# 1. Ensure currently on 'master'
# 2. Ensure there are no local unstaged changes
# 3. Switch to the latest live-* branch
# 4. Pull the latest changes from GitHub into the wiki/
# 5. Remove all *.html from wiki/ not in documentation/ or contribute/
# 6. Regenerate all markdown content to HTML
# 7. Commit the changes to the live-* branch
# 8. Reset local back to master branch for the website and wiki
# 9. Display message for how to push changes to staging site
#

. common.sh

dir=${PWD}

debug=0

check_perms

#
# Verify this tree is on the 'master' branch
#
git branch | grep -q '\* master' || {
	echo ""
	echo "mklive.sh can only be run in the 'master' branch."
	echo "$ git checkout master"
	echo ""
	exit
}

#
# Ensure no unstaged changes in wiki/ or /
#
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

echo "Checking out branch: ${branch}"
git checkout ${branch} || {
    echo "Checking out ${branch} failed."
	cd ${dir}
    exit -1
}    

(( debug )) && {
    echo "Branch / Checkout to ${branch} complete. Enter to continue." 
    read
}

# Update the wiki
cd wiki
git pull --all || {
	echo "Updating wiki/ latest from GitHub failed. Exiting."
	cd ${dir}
	exit -1
}

# Remove all Wiki generated content
find wiki -name '*.html' -not -path '*/documentation*/ -and -not -path '*/contribute/*' -and -exec rm -f {} \;

launch_gollum

cd wiki
find . -type f -not -name '*.html' -not -path '*/documentation*/ -and -not -path '*/contribute/*' -and -exec rm -f {} \;

find . -type f -not -path "./.git/*" -and -not -name "*.html" -and -not -path '*/assets/*' | while read file; do
	[ "${file}" == "./gfm.php" ] && continue
	[ "${file}" == "./.htaccess" ] && continue
	[ "${file}" == "./php_errors.log" ] && continue
	[ "${file}" == "./custom.js" ] && continue
    generate "${file}"
done
generate "pages.md"
generate "history.md"
cd ..



./generate.sh
(( debug )) && {
echo "Generate complete. Enter to continue." 
read
}

#
# Remove all Wiki markdown content from Live site
#
find wiki -name '*.md' -exec rm {} \;
find wiki -name '*.mediawiki' -exec rm {} \;
find wiki -name '*.org' -exec rm {} \;

git commit -s -a -m "Automatic static version commit for ${branch}"

git checkout master
git tag tag-${branch}
(( debug )) && {
	echo "Site checkout complete. Enter to continue." 
	read
}

cd wiki
git checkout -f
git tag tag-${branch}
(( debug )) && {
	echo "Wiki checkout complete. Enter to continue." 
	read
}
cd ..

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
