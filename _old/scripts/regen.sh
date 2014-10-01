desc="Fetch the latest wiki and regenerate page list and history"

function usage () {
cat << EOF
  usage: site.sh regen

  Pull the latest wiki content from GitHub and regenerate
  the wiki/pages.md.html and wiki/history.md.html files.
  
  This is run as part of the 'site.sh mklive' process, as well
  as triggered on the live server whenever a commit occurs
  in the wiki on GitHub (via regen.php).
  
EOF
}

function run () {
    if [[ ! -d wiki ]]; then
        mkdir wiki || die "Unable to create wiki/ directory."
    fi
    
    if [[ ! -w wiki ]]; then
        die "Unable to write to wiki/ directory.\nTry: ./site.sh perms"
    fi

    if [[ ( -e wiki/pages.md.html && ! -w wiki/pages.md.html ) ||
          ( -e wiki/history.md.html && ! -w wiki/history.md.html ) ]]; then
        die "Unable to write to wiki/{pages,history}.md.html files.\nTry: sudo ./site.sh perms"
    fi
    
    WIKI_GIT="--git-dir=wiki.git"
    url=$(git remote show -n origin | sed -ne 's,^.*Push.*URL: \(.*\)$,\1,p')
    echo -n "Fetching latest changes from $url..."
    git ${WIKI_GIT} fetch -q origin master:master || 
        die "\nUpdating wiki.git latest from GitHub failed. Exiting."
    echo "done."

    echo "Generating content..."
    generate "wiki/pages.md"
    generate "wiki/history.md"
}
