#!/bin/bash
find wiki -name '*html' -exec rm {} \;
chown :www-data . -R
chmod g+rwX . -R
for i in xwalk markdown; do
	rm $i.css $i.css.map $i.msg
done
rm menus.js

