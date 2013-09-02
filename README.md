## Introduction
There are two versions of this website. The development version 
and the live version.

### Live Website
The live version consists of static content, pre-generated and 
unchanging. This version has minimal server requrements, needing
only PHP and the rewrite module. This is the version hosted on
http://crosswalk-project.org.

#### Quick Steps
To create a local copy of the live website:
```
git clone git@github.com:crosswalk-project/crosswalk-website.git -b live
```
The above will checkout the entire website, including the cached
Wiki content, and excluding binary downloads.

#### Details
The live version lives in the 'live' branch. Running the live
version requires the rewrite module in Apache2. This is used
in the wiki subsystem to map requested URLs through a PHP
script which will then perform smart matching of leaf names.

Enable the rewrite module via:

```
sudo a2enmod rewrite
sudo service apache2 restart
```

### Development Website

The development version automatically generates new versions
of various files (*.css, menus.js, and the wiki/*.html content)
as those content items are updated. To set up a local development
version:

```
git clone git@github.com:crosswalk-project/crosswalk-website
cd crosswalk-website
cd wiki
git clone git@github.com:crosswalk-project/crosswalk-website.wiki.git --bare .git
sed -i -e 's/bare = true/bare = false/' .git/config
git checkout
cd ..
```

#### Requirements
Running the development version of the site has several additional
software dependencies, located toward the end of this file under
Development Site Software Dependencies.

##### APACHE2 AND PHP

Apache2 configured with PHP and the following:
  -MultiViews: it breaks the usage of php to create HTML from markup
  +FollowSymLinks: required for mod_rewrite

To enable the rewrite module:

```
sudo a2enmod rewrite
sudo service apache2 restart
```

##### GOLLUM AND THE WIKI

The wiki subsystem uses gollum internally to create cached pages. 
The website itself does not contain the Crosswalk wiki content.
That content is managed as the wiki associated with the
crosswalk-website project.

The website is designed to have the wiki checked out in the wiki/
sub-directory, as follows:

```
cd crosswalk-website
cd wiki
git clone git@github.com:crosswalk-project/crosswalk-website.wiki.git --bare .git
sed -i -e 's/bare = true/bare = false/' .git/config
git checkout
```

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
gollum --base-path wiki ${DOCROOT}/wiki >/dev/null 2>&1 &
```

It is expected that the path above is immediately below the main site 
root and that the wiki directory contains the .git/ tree from the GitHub 
hosted gollum site where the wiki is edited.

In addition, the git tree must be checked out. When new content is ready 
to be used, the following can be executed:

```
cd ${DOCROOT}/wiki
git pull
git checkout -f
```

### CSS and SASS

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


#### Development scripts

```
./cleanup.sh
./generate.sh
```

The first deletes all old generated content. The second generates
new cached copies of all dynamic content. If gollum isn't running,
the generate script will attempt to launch it.

To update the Wiki content:
```
cd wiki
git checkout
```

After running the generate script, testing should be done locally
to ensure the site is functional. Once testing is complete, a
live version can be built:

```
git checkout live
./mklive.sh
```

That script will update the current working tree to the state
the live site will be in, including caching all dynamic 
content, removing the source files that generate the dynamic
content, and removing the dynamic scripts.

## Website Design
The Crosswalk website consists of the styles (CSS), the main page 
(index.html), and the functional logic (xwalk.js) that executes to 
adapt the DOM as necessary when the user is navigating the site.

The content is all of the information presented in the three 
sub-pages:

* Documentation
* Contribute
* Wiki

...

