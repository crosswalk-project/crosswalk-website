# Website Design
The Crosswalk website consists of the following functional areas:

1. [Main landing page](https://crosswalk-project.org). Mostly static content. Some Javascript is used to compensate for style areas that were difficult to code in a pure CSS model. Javascript is also used for dynamically changing the version strings in the top overview section.
2. [Documentation](https://crosswalk-project.org/#documentation) and [Contribute](https://crosswalk-project.org/#contribute). Statically generated from content checked into the crosswalk-website.git project. The menu content on the left is generated via xwalk.js when the page is loaded, based on the content of menus.js. The content in the contribute/ and documentation/ directories are cached .html files generated from the markdown sources. This occurs during the 'site.sh mklive' script execution described later.
3. [Wiki](https://crosswalk-project.org/#wiki). This content is a dynamic proxy to the Wiki content hosted in the [crosswalk-website.wiki.git](https://github.com/crosswalk-project/crosswalk-website.wiki.git) on GitHub. Whenever a commit is made, the Gollum Event Webhook on GitHub invokes the regen.php page which recreates the content viewed in the Pages and History pages of the Wiki. Fetches of actual Wiki content are proxied on the server to GitHub via php (XHR on the client is blocked due to Access-Control-Allow-Origin; currently the server doesn't cache the fetched content--it should).

The wiki content is served to the client in the format output by the GitHub wiki system (Gollum.) The client side javascript (See xwalk.js content_response and generate_wiki_page) creates a DOM element with the content from the wiki, pulls out the wiki DOM item, performs some URL rewrites, and then injects the resulting content into the Crosswalk website page.

# Workflow
The general work flow is as follows:

1. Develop on local machine (see [Server Requirements: Development Website](#server-requirements-development-website))
2. Create a 'live snapshot' via the script: `./site.sh mklive`
3. Push latest 'live snapshot' to the staging server via the script: `./site.sh push`
4. Test the results on the staging server
4. Push the staging version to the live server via the script: `./site.sh push live`

## Cached dynamic content
There are several pieces of content that are generated during the site development. These include:

* xwalk.css <= generated from xwalk.scss
* markdown.css <= generated from markdown.scss
* menus.js <= generated from menus.js.php
* wiki/pages.md.html <= generated from regen.php via gfm.php
* wiki/history.md.html <= generated from regen.php via gfm.php
* contribute/\*.html <= generated from the markdown files in contribute/*.md via gfm.php
* documentation/\*.html <= generated from the markdown files in contribute/*.md via gfm.php

If running in development mode, all of the above are regenerated when the source changes (via .htaccess and gfm.php)

When you execute the mklive script, all of that content is regenerated as part of the site snapshot creation. See './site.sh --help mklive'

# Initializing the server with the crosswalk-website
To host the Crosswalk website, the following needs to be done on the server:

```
# Initialize the site content into `docroot`
git clone https://github.com/crosswalk-project/crosswalk-website.git docroot
cd docroot
git clone --bare https://github.com/crosswalk-project/crosswalk-website.wiki.git wiki.git
sudo chown wwwrun: wiki.git -R
sudo chown wwwrun: wiki -R
# Switch to the latest live branch
. scripts/common.inc
branch=$(get_remote_live_info)
echo ${branch} > REVISION
git checkout -f ${branch}
```

At this point, the site is now initialized and set to the latest live branch. The scripts/ directory is not part of the live version of the site--once you checkout ${branch}, you won't be able to run any of the scripts on the live site.

The chown lines are necessary as wiki.git and wiki are written to via regen.php, which executes as the user wwwrun.

# Server Requirements: Live Website
Running the live version requires the rewrite module in Apache2. This is used
in the wiki subsystem to map requested URLs through a PHP
script which will then perform smart matching of leaf names.

Enable the rewrite module via:

```
sudo a2enmod rewrite
sudo service apache2 restart
```

The rest of the content is served from static files that were
generated as part of the development cycle as described in the Workflow
section.

# Server Requirements: Development Website

The development version automatically generates new versions
of various files (*.css, menus.js, and the {documentation,contribute}/*.html) and requires local infrastructure to process the markdown content.

## Requirements
Running the development version of the site has several additional
software dependencies, located toward the end of this file under
Development Site Software Dependencies.

### APACHE2 AND PHP

Apache2 configured with PHP and the following:
  -MultiViews: it breaks the usage of php to create HTML from markup
  +FollowSymLinks: required for mod_rewrite

To enable the rewrite module:

```
sudo a2enmod rewrite
sudo service apache2 restart
```

### GOLLUM

The content in documentation and contribute uses gollum to create cached pages.

NOTE:
gollum requires ruby >= 1.9.2 (gollum requires nokogiri which requires
ruby >= 1.9.2) On older Ubuntu systems, this required:

```
sudo apt-get install ruby1.9.3 rubygems1.9
sudo update-alternatives --config gem
sudo update-alternatives --config ruby
```

Now we can install gollum. Any markup used in the files hosted in gollum
must be installed. Currently the Crosswalk Wiki has content in three
different markups:

* MediaWiki (*.mediawiki)
* GitHub Markdown (*.md)
* Org Mode (*.org)

```
sudo gem install gollum redcarpet org-ruby wikicloth
```

To generate the cached HTML files, gollum needs to be running. When a
wiki page is requested, a php script will perform a local connection to
gollum on port 4567 requesting that markdown file be processed. The
returned HTML is then cached. New requests are only made if the markdown
file is newer than the cached file.

Gollum should be launched with the following option:

```
gollum --live-preview ${DOCROOT} >/dev/null 2>&1 &
```

${DOCROOT} should be the root of your local install, for example /var/www/crosswalk-website.

By providing the --live-preview option you can use a live editor to edit
the documentation content locally by navigating to http://localhost:4567/.

In addition, the git tree must be checked out. When new content is ready
to be used, the following can be executed:

## CSS and SASS

sass and bourbon are used for the CSS. If a css file is requested and
there is an updated sass version available, the php script in the site
root will load the sass and cache it to the css file, which is then
returned to the caller. This is similar to running 'sass --watch'
without having to have sass running all the time (its
first-access-on-demand)

In addition to generating the CSS from the scss file, source maps are
generated. This requires sass >= 3.3.0. You can install that version
via:

```
sudo gem install sass -v ">=3.3.0alpha' --pre
sudo gem install bourbon
```

If you do not have a version greater than 3.3.0 installed, source maps
will not be generated.

NOTE:
sass files should be verified to be correct prior to committing to git!


### Development scripts

See site.sh:
```
./site.sh
```
