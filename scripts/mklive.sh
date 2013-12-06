desc="Generate live version of website"
function usage () {
cat << EOF
usage: site.sh mklive

  Generates a live version of the website, regenerating all dynamic
  content into its compiled / processed forms.
  
  Creates a branch in the form of live-YYMMDD[.REV], for example:
  
    live-20131207
    live-20131207.1
    ...
    
  The script performs the following steps:
  
  1. Ensure you are on the 'master' branch
  2. Verify there are no unstaged files (should be changed to use 
     git-stash)
  3. Create and switch to the new branch. On live branch:
  3.1. Purge all generated dynamic content (analagous of a 
       'make clean')
  3.2. Regenerate all generated dynamic content
  3.3. Rewrite the .htaccess file to turn off the development mode 
       rewrite rules
  3.4. modify the .gitignore file to only track files appropriate 
       for the live website
  3.5. Add dynamic content to GIT
  3.6. Purge all the source content from GIT
  3.7. commit the live snapshot
  4. Switch back to master.
  
  At this point, the new live-YYMMDD branch contains an exact copy
  of what would be pushed to the website. Given a branch name 
  live-YYMMDD, you can create a local archive of that version via:

  git archive --format=tar.gz --prefix=live-YYMMDD/ \
    -o live-YYMMDD.tar.gz live-YYMMDD

EOF
}


function run () {
    debug=${debug:=0}
    
    #
    # Verify this tree is on the 'master' branch
    #
    git branch | grep -q '\* master' || {
        echo ""
        echo "mklive.sh can only be run in the 'master' branch."
        echo "$ git checkout master"
        echo ""
        exit
    }
    
    [ "$1" != "-f" ] && check_unstaged
    debug_msg "Check complete." 

    # Make new branch for live-YYYYMMDD
    # -t track upstream (push/pull from github will work)
    # -f force -- delete branch if it already exists
    branch=live-$(date +%Y%m%d)
    iter=1
    while git show-ref --verify --quiet refs/heads/${branch} || 
          git show-ref --verify --quiet refs/remotes/origin/${branch}; do
        branch="${branch/.*}.${iter}"
        iter=$((iter+1))
    done
    echo "Using branch: ${branch}"
    git branch -t ${branch}
    git checkout ${branch}
    
    debug_msg "Branch / Checkout to ${branch} complete."
    
    #
    # Nuke all dynamic content and regenerate it
    #
    ./site.sh cleanup
    debug_msg "Cleanup complete."
    
    ./site.sh generate
    debug_msg "Generate complete."
    
    #
    # Turn off the PHP script override for the root directory
    # by commenting out the RewriteCond and Rule 
    #
    sed -i \
        -e 's/^\(.*RewriteCond %{REQUEST_FILENAME}\.php -f\)$/#\1/' \
        -e 's/^\(.*RewriteRule .* %{REQUEST_URI}\$1\.php \[L\]\)$/#\1/' \
        .htaccess
    
    #
    # Modify .gitignore to track/manage all generated content
    #
    sed -i -e 's:^\(.*\)\.html$:#\1.html:g' .gitignore
    for i in xwalk.css markdown.css menus.js; do
        sed -i -e s:^${i}:#${i}: .gitignore
    done
    
    #
    # Adding generated content to the Live site
    #
    find documentation -name '*html' -exec git add {} \;
    find contribute -name '*html' -exec git add {} \;
    git add xwalk.css
    git add markdown.css
    git add menus.js
    
    debug_msg "git add complete." 
    
    #
    # Remove PHP generating scripts from Live site
    #
    for file in xwalk markdown; do
        for extension in msg css.php scss css.map; do
            [ -e ${file}.${extension} ] && rm ${file}.${extension}
        done
    done
    [ -e menus.js.php ] && rm menus.js.php

    #
    # Remove bash scripts from the Live site
    rm *.sh
    
    #
    # Remove all markdown content from Live site
    #
    find . -name '*.md' -exec rm {} \;
    find . -name '*.mediawiki' -exec rm {} \;
    find . -name '*.org' -exec rm {} \;
    debug_msg "Markdown content removal complete."
    
    git commit -s -a -m "Automatic static version commit for ${branch}"
    
    git checkout master
    debug_msg "Site checkout and tag complete."
    
cat << EOF

Changes committed to git as branch ${branch}.

Current tree set back to 'master'. Steps to take:

  1. Push ${branch} to staging server:
  
     ./site.sh push
  
  2. Test the site by going to https://stg.crosswalk-project.org
     2.1 Visit each category section. Make sure they work.
         https://stg.crosswalk-project.org/#documentation
         https://stg.crosswalk-project.org/#contribute
         https://stg.crosswalk-project.org/#wiki
     2.2 Check the Wiki History and verify the newest changes exist
         https://stg.crosswalk-project.org/#wiki/history
  
  3. Resize your browser window down to a width of 320 and up to 
     full screen. Ensure responsize design still functions.

  4. After you are confident that the site is good, push the version
     from the Staging server to the Live server:
     
    ./site.sh push live
    
EOF
}
