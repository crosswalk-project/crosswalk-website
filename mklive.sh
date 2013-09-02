#!/bin/bash

git diff-files --quiet --ignore-submodules || {
	echo "Can't go live with uncommitted changes."
	git diff-files --name-status -r --ignore-submodules
	exit 1
}

git diff-index --cached --quiet HEAD --ignore-submodules || {
	echo "Can't go live with uncommitted changes."
	git diff-index --cached --name-status -r --ignore-submodules HEAD
	exit 1
}

git branch | grep -q '\* live' || {
	echo ""
	echo "mklive.sh can only be run in the 'live' branch."
	echo "$ git checkout live"
	echo ""
	exit
}

require_clean_work_tree "Can't go live."

./cleanup.sh
./generate.sh

cat << EOF > .htaccess
RewriteEngine on
RewriteCond %{REQUEST_FILENAME} -f
RewriteRule ^(\..*) %{REQUEST_URI}$1 [R=404]
EOF

sed -i -e 's:^wiki/\*\.html:#wiki/*.html:' \
       -e 's:^wiki/documentation:#wiki/documentation:' \
       -e 's:^wiki/contribute:#wiki/contribute:' .gitignore
for i in xwalk.css markdown.css menus.js; do
	sed -i -e s:^${i}:#${i}: .gitignore
done

find wiki -name '*html' -exec git add {} \;
git add xwalk.css
git add markdown.css
git add menus.js

for file in xwalk markdown; do
	for extension in msg css.php scss css.map; do
		[ -e ${file}.${extension} ] && rm ${file}.${extension}
	done
done

find wiki -name '*.md' -exec rm {} \;
find wiki -name '*.mediawiki' -exec rm {} \;
find wiki -name '*.org' -exec rm {} \;
