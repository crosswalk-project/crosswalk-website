desc="Remove all auto-generated content"
function run () {
	# 0) Remove all *.log files from '/' recursively
	# 1) Remove all *.html from '/wiki' sub-directory
	# 2) Remove generated files in '/'
	find . -name '*log' -exec rm -f {} \;
	find documentation -name '*.html' -exec rm -f {} \;
	find contribute -name '*.html' -exec rm -f {} \;
	find wiki -name '*.html' -exec rm -f {} \;
	for i in xwalk markdown; do
		rm -f $i.css $i.css.map $i.msg
	done
	rm -f menus.js
}
