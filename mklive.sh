#!/bin/bash
debug=0

function check_unstaged () {
	git diff-files --quiet --ignore-submodules || {
		echo "Can't go live with uncommitted changes in $(basename ${PWD})"
		git diff-files --name-status -r --ignore-submodules
		[ "$(basename ${PWD})" = "wiki" ] && {
			echo "If lots of files are deleted, its probably the wiki "
        	        echo "was in a partial mklive state. Try:"
	                echo "  cd wiki ; git checkout ; cd .."
		}
		exit 1
	}

	git diff-index --cached --quiet HEAD --ignore-submodules || {
		echo "Can't go live with uncommitted changes in $(basename ${PWD})"
		git diff-index --cached --name-status -r --ignore-submodules HEAD
		echo ""
		[ "$(basename ${PWD})" = "wiki" ] && {
			echo "If lots of files are deleted, its probably the wiki "
        	        echo "was in a partial mklive state. Try:"
	                echo "  cd wiki ; git checkout ; cd .."
		}
		exit 1
	}
}

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
check_unstaged
cd wiki
check_unstaged
cd ..

(( debug )) && {
echo "Check complete. Enter to continue." 
read
}

# Make new branch for live-YYYYMMDD
# -t track upstream (push/pull from github will work)
# -f force -- delete branch if it already exists
branch=live-$(date +%Y%m%d)
iter=1
while git show-ref --verify --quiet refs/heads/${branch} || 
      git show-ref --verify --quiet refs/remotes/origin/${branch}; do
	branch="${branch/.*}.${iter}"
	iter=$((iter+1))
done
echo "Using branch: ${branch}"
git branch -t ${branch}
git checkout ${branch}


(( debug )) && {
echo "Branch / Checkout to ${branch} complete. Enter to continue." 
read
}

#
# Nuke all dynamic content and regenerate it
#
./cleanup.sh
(( debug )) && {
echo "Cleanup complete. Enter to continue." 
read
}
./generate.sh
(( debug )) && {
echo "Generate complete. Enter to continue." 
read
}

#
# Turn off the PHP script override for the root directory
# by commenting out the RewriteCond and Rule 
#
sed -i \
    -e 's/^\(.*RewriteCond %{REQUEST_FILENAME}\.php -f\)$/#\1/' \
    -e 's/^\(.*RewriteRule .* %{REQUEST_URI}\$1\.php \[L\]\)$/#\1/' \
    .htaccess

#
# Modify .gitignore to track/manage the generated content
# from under the wiki/ path
#
sed -i -e 's:^wiki/\*\.html:#wiki/*.html:' \
       -e 's:^wiki/documentation:#wiki/documentation:' \
       -e 's:^wiki/contribute:#wiki/contribute:' .gitignore
for i in xwalk.css markdown.css menus.js; do
	sed -i -e s:^${i}:#${i}: .gitignore
done

#
# Adding generated Wiki content to the Live site
#
find wiki -name '*html' -exec git add {} \;
find wiki/assets -exec git add {} \;
git add xwalk.css
git add markdown.css
git add menus.js

(( debug )) && {
echo "git add complete. Enter to continue." 
read
}
#
# Remove PHP generating scripts from Live site
#
for file in xwalk markdown; do
	for extension in msg css.php scss css.map; do
		[ -e ${file}.${extension} ] && rm ${file}.${extension}
	done
done
[ -e menus.js.php ] && rm menus.js.php
#
# Remove bash scripts from the Live site
rm *.sh

#
# Remove all Wiki markdown content from Live site
#
find wiki -name '*.md' -exec rm {} \;
find wiki -name '*.mediawiki' -exec rm {} \;
find wiki -name '*.org' -exec rm {} \;
(( debug )) && {
echo "Markdown content removal complete. Enter to continue." 
read
}

git commit -s -a -m "Automatic static version commit for ${branch}"
cd wiki
git checkout -f

(( debug )) && {
echo "Wiki checkout complete. Enter to continue." 
read
}
cd ..
git checkout master
(( debug )) && {
echo "Site checkout complete. Enter to continue." 
read
}
git tag tag-${branch}
cd wiki
git tag tag-${branch}
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
