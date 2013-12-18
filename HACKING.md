# Hacking on the crosswalk-website project

The below steps are **not recommended for the Crosswalk site's live production setup**.
They just enable easy setup of the crosswalk-website project so you can
work on it and make new releases to staging and live. They are not
secure or optimised in any way.

I recommend using Linux to work on the crosswalk-website project.

## Important note: developer mode scuppered by generated content

If you use any of the site scripts to pre-generate content for the site
(e.g. `./site.sh generate`), then try to look at the site in a browser,
you may experience errors, such as stylesheets not loading (the whole
site has a red background).

This is because the server will attempt to generate content dynamically,
and when it is unable to do so (because a pre-generated file produced
by one of the site scripts already exists), it will fail and return an error.

For this reason, if you intend to test in a browser, ensure that you clean
the project before you start:

    ./site.sh cleanup

This removes all the generated CSS, JS, HTML etc. files and gives you
a clean base to start from.

## Install required software

In developer mode, the website automatically generates numerous files
(*.css, menus.js, and the {documentation,contribute}/*.html) and processes
files in various markup languages (markdown etc.) on the fly.

The scripts for generating the staging and live websites also have various
dependencies.

Ensure that you have all the additional required software by following
the instructions below.

### Apache2 and PHP

You need Apache2 configured with PHP.

*The simplest way to get a working, up to date and easily to configure
Apache + PHP setup on Linux is to install
[XAMPP](http://www.apachefriends.org/en/xampp.html), rather than relying
on the Apache that's on your Linux distro. The instructions below
assume you're using XAMPP.*

In the Apache configuration, ensure that the following modules are
disabled/enabled:

*   MultiViews module: turn it off as it breaks creating HTML from markup via PHP
*   Rewrite: so the site can map URLs to the correct markdown/html files
*   Alias: so you can create a subdirectory on localhost which points
at the crosswalk-website project directory
*   Dir: so the server can serve content from directories

On XAMPP, modules are turned on/off in the httpd configuration file
`/opt/lampp/etc/httpd.conf`. You need the following lines to be
uncommented (they should all be already, by default):

    LoadModule alias_module modules/mod_alias.so
    LoadModule dir_module modules/mod_dir.so
    LoadModule rewrite_module modules/mod_rewrite.so

XAMPP has MultiViews disabled by default.

#### Put PHP on your path

The `./site.sh` script and its friends rely on php being on your path
(they call the gfm.php script to generate HTML from other markup
languages). By default, XAMPP doesn't put it on your path.

To add php to your path, edit your bash rc file (typically `~/.bashrc`)
and set the `PATH` variable to include the XAMPP `bin` directory, e.g.

    PATH=$PATH:/opt/lampp/bin

Then source your bash rc file so the changes take effect:

    $ source ~/.bashrc

Test that php is on your path by doing:

    $ php -v

### gollum

The content in the "documentation" and "contribute" directories is created
by gollum on the fly and cached.

NOTE:

gollum requires ruby >= 1.9.2 (gollum requires nokogiri which requires
ruby >= 1.9.2). On older Ubuntu systems, you may need to install a recent
version of ruby explicitly:

    sudo apt-get install ruby1.9.3 rubygems1.9
    sudo update-alternatives --config gem
    sudo update-alternatives --config ruby

Now we can install gollum. Libraries for any markup styles used in the
files hosted in gollum must be installed. Currently the Crosswalk wiki
has content in three different markup flavours:

*   MediaWiki (*.mediawiki)
*   GitHub Markdown (*.md)
*   Org Mode (*.org)

To install gollum, plus support for these wiki content formats:

    sudo gem install gollum redcarpet org-ruby wikicloth

To generate the cached HTML files, gollum needs to be running. When a
wiki page is requested, a php script will perform a local connection
to gollum on port 4567 requesting that markdown file be processed.
The returned HTML is then cached. New requests are only made if the
markdown file is newer than the cached file.

Gollum should be launched with the following option:

    gollum ${DOCROOT} >/dev/null 2>&1 &

${DOCROOT} should be the root of your local install, for example
/var/www/crosswalk-website.

#### Editing content through gollum

You can edit content through gollum directly by running it with this
command:

    gollum --ref ${BRANCH} --live-preview ${DOCROOT}

By providing the --live-preview option you can use an editor to
edit the content in "documentation" and "contribute" by navigating
to http://localhost:4567/.

${BRANCH} sets the branch of the website you want to make edits on. This
is important if you are editing the site's content in a development
branch (do not edit the content on a *live...* branch!). If you do
not set a branch, all edits will be committed to master, even if you
have a different branch checked out (which can be confusing).

### sass and bourbon

sass and bourbon are used for the CSS. If a css file is requested and
there is an updated version of the corresponding sass stylesheet available,
the php script in the site root will load the sass stylesheet, process it,
and cache the output to the css file. That css file is then returned to
the caller. This is similar to running 'sass --watch' without having to
have sass running all the time (it's first-access-on-demand).

In addition to generating the CSS from the scss file, source maps are
generated. This requires sass >= 3.3.0.

You can install the required versions of sass and bourbon via:

    sudo gem install sass -v ">=3.3.0alpha' --pre
    sudo gem install bourbon

If you do not have a version greater than 3.3.0 installed, source maps
will not be generated.

NOTE:
sass files should be verified to be correct prior to committing to git!

Note that the sass script has to be available to whatever user gollum
is running as. It's easiest to run gollum as your normal user to
simplify this, rather than trying to run it under sudo or as the
Apache user.

## Prepare the project directory

Follow these steps to clone the project and its dependencies, ready for
development. In the instructions below, `<docroot>` refers to a directory
(probably under your home directory) that you want to checkout the
crosswalk-website project to:

    # Initialize the site content into the <docroot> directory
    git clone https://github.com/crosswalk-project/crosswalk-website.git <docroot>
    cd <docroot>

    # make a clone of the wiki content
    git clone --bare https://github.com/crosswalk-project/crosswalk-website.wiki.git wiki.git

    # make <docroot> and its children world readable; note that if you're
    # doing development in your home directory, you need to ensure that the
    # whole directory path to <docroot> is readable and executable by Apache,
    # including its parent directories (i.e. if docroot is
    # /home/me/crosswalk-website, you need to make /home, /home/me and
    # /home/me/crosswalk-website readable and executable by Apache)
    chmod -R a+r <docroot>

    # make all <docroot> and its sub-directories world readable, writable and
    # executable; this is necessary as PHP needs to write to the root
    # directory and several sub-directories
    find <docroot> -type d | xargs chmod a+rwx

## Configure Apache

The steps below explain how to set up the Crosswalk website as a subfolder
of the main localhost website.

1.  Copy `site.conf` to `/opt/lampp/etc/crosswalk-website.conf`.

2.  Edit the `crosswalk-website.conf` file, replacing all occurrences
in the file of `/home/me/dev/crosswalk-website` with the absolute path
to your Crosswalk website project directory.

3.  Include your custom configuration file from the main Apache
configuration file by adding this line to the end of
`/opt/lampp/etc/httpd.conf`:

        Include /opt/lampp/etc/crosswalk-site.conf

4.  Restart Apache after changing the configuration:

        sudo /opt/lampp/lampp restartapache

After restarting Apache, the site should be available at
http://localhost/crosswalk-website.
