# Website Design
The Crosswalk website consists of the styles (CSS), the main page
(index.html), and the functional logic (xwalk.js) that executes to
adapt the DOM as necessary when the user is navigating the site.

There are four main parts of the website. The top landing page,
end user documentation, co-traveller contribute documentation, and
the wiki.

The content for the documentation and contribute sections is hosted 
in the crosswalk-website project under the contribute/ and documentation/
directories.

The wiki content on the website is a live view from:

https://github.com/crosswalk-project/crosswalk-website/wiki

The history and page list for the wiki are generated dynamically whenever
a commit is made to the wiki (via a GitHub webhook to the Gollum event)

# Workflow
The general work flow is as follows:

1. Develop on local machine
2. Create a live snapshot: `./site.sh mklive`
3. Push to latest 'live snapshot' to the staging server via `./site.sh push`
4. Push the stating version to the live server via `./site.sh push live`
 
Step #2 compiles all dynamic content to static versions; scss is converted to css and the markdown content in documentation/ and contribute/ are converted to html.

When the live snapshot is created, it is placed onto a branch with the name "live-YYYYMMdd".

# Initializing the server
To host the Crosswalk website, you need to perform the following on the server:

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

NOTE: `wwwrun` is the user that Apache runs under (since it needs to be able to modify those two 
directories when the wiki content is updated on GitHub.)

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
gollum --base-path wiki --live-preview ${DOCROOT} >/dev/null 2>&1 &
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
