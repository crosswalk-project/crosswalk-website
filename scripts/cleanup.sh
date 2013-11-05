#!/bin/bash
. ${PWD}/scripts/common.inc
#
# 0) Remove all *.log files from '/' recursively
# 1) Remove all *.html from '/wiki' sub-directory
# 2) Remove generated files in '/'
#
find . -name '*log' -exec rm -f {} \;
find wiki -name '*.html' -exec rm -f {} \;
chown :www-data . -R
chmod g+rwX . -R
for i in xwalk markdown; do
	rm -f $i.css $i.css.map $i.msg
done
rm -f menus.js

