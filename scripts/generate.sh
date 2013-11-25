desc="Generate all content"
function run () {
    dry_run=
	check_perms

	launch_gollum

    for dir in documentation contribute; do
        find ${dir} -type f \
            -path "*.md" -or \
            -path "*.mediawiki" -or \
            -path "*.org" | while read file; do
            ${dry_run} generate "${file}"
        done
    done

	for i in menus.js xwalk.css markdown.css; do
		${dry_run}php $i.php > /dev/null
	done

	[ ! -z ${gollum} ] && {
		kill -9 ${gollum}
	}
    
    ./site.sh update-wiki
}
