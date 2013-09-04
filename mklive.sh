#!/bin/bash

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
	echo "mklive.sh can only be run in the 'live' branch."
	echo "$ git checkout live"
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

# Make new branch for live-YYYYMMDD
# -t track upstream (push/pull from github will work)
# -f force -- delete branch if it already exists
git branch -t -f $(date +%Y%m%d)

#
# Nuke all dynamic content and regenerate it
#
./cleanup.sh
./generate.sh

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
git add xwalk.css
git add markdown.css
git add menus.js

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
# Remove all Wiki markdown content from Live site
#
find wiki -name '*.md' -exec rm {} \;
find wiki -name '*.mediawiki' -exec rm {} \;
find wiki -name '*.org' -exec rm {} \;

git commit -s -a -m "Automatic static version commit for ${branch}"
cd wiki
git checkout -f
cd ..
git checkout master
cat < EOF

Changes committed to git as branch ${branch}.

Current tree set back to 'master'.
EOF
