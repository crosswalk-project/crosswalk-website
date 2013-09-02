#!/bin/bash
git branch | grep '\* live' || {
	echo ""
	echo "mklive.sh can only be run in the 'live' branch."
	echo "$ git checkout live"
	echo ""
	exit
}
git status >/dev/null 2>&1 && {
	echo ""
	echo "Untracked local changes. Can't go live."
	echo ""
	git status
	exit
}
./cleanup.sh
./generate.sh
cat << EOF > .htaccess
RewriteEngine on
RewriteCond %{REQUEST_FILENAME} -f
RewriteRule ^(\..*) %{REQUEST_URI}$1 [R=404]
EOF
