desc="Generate all content"
function run () {
  dry_run=
	check_perms

  kill_gollum

	launch_gollum

  for dir in documentation contribute; do
      find ${dir} -type f \
          -path "*.md" -or \
          -path "*.mediawiki" -or \
          -path "*.org" | while read file; do
          ${dry_run} generate "${file}"
      done
  done

	kill_gollum

	for i in menus.js xwalk.css markdown.css; do
		${dry_run}php $i.php > /dev/null
	done

  generate "wiki/pages.md"
  generate "wiki/history.md"
}
