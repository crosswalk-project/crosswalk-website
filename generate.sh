#!/bin/bash
echo ''
echo -n 'Looking for files not owned by :www-data...' 
found=$(find . -not -group www-data | grep -v \.git | wc -l)
(( ${found} )) && {
  echo "${found} found."
  echo ''
  echo 'Please correct and try again.'
  echo 'Correct via: chown :www-data . -R'
  echo ''
  exit 1
}

echo 'none found. OK.'

test=$(ps -o pid= -C gollum)
[ -z ${test} ] && {
 	gollum --base-path wiki ${PWD}/wiki >/dev/null 2>&1 &
	gollum=$!
	echo -n "Launching gollum."
	x=1
	while (( $x )); do
		echo -n "."
		sleep 0.5
		netstat -na 2>&1 | grep -q ":4567 "
		x=$?
	done
	echo ""
}

function generate () {
    file=$1
	echo "Processing wiki/${file/.\//}..."
	php gfm.php ${file/.\//} > /dev/null
	html=${file/.\//}.html
	[ ! -e ${html} ] && {
		echo "Could not create ${html}."
		exit 1
	}

	find ${html} -size 0 | grep -q ${html} && {
		echo "Could not generate ${html}."
		exit 1
	}
}
    
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

