#!/bin/bash
. ${PWD}/scripts/common.inc

check_perms

launch_gollum

cd wiki
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

for i in menus.js xwalk.css markdown.css; do
	php $i.php > /dev/null
done

[ ! -z ${gollum} ] && {
	kill -9 ${gollum}
}

