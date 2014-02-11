# Introduction

This repository contains the source code for the
[Crosswalk website](http://crosswalk-project.org/). The live website
is generated from this code.

Any bugs for the website should be logged on the
[Crosswalk Jira](https://crosswalk-project.org/jira/), under the
[*Website*](https://crosswalk-project.org/jira/browse/XWALK/component/10203)
component.

Pull requests for the website should be submitted via
[github](https://github.com/crosswalk-project/crosswalk-website/pulls).

Note that this document gives an overview of the site and explains how
to setup and release for staging and live servers. If you need to edit
the content of the site, the [HACKING](HACKING.md) file explains how
to set the site up locally.

# Website design

The Crosswalk website consists of the following functional areas:

1.  [Main landing page](https://crosswalk-project.org). Mostly
static content. Some Javascript is used to compensate for style
areas that were difficult to code in a pure CSS model. Javascript
is also used for dynamically changing the version strings in the top
overview section.

2.  [Documentation](https://crosswalk-project.org/#documentation)
and [Contribute](https://crosswalk-project.org/#contribute). Statically
generated from content checked into the crosswalk-website.git project.
These sections are hand-picked selections from the wiki which are
particularly useful or frequently viewed.

    The menu content on the left is generated via xwalk.js when the page
is loaded, based on the content of menus.js. The content in the
contribute/ and documentation/ directories are cached .html files
generated from the markdown sources. This occurs during the
`site.sh mklive` script execution described later.

3.  [Wiki](https://crosswalk-project.org/#wiki). This content is a
dynamic proxy to the Wiki content hosted in the
[crosswalk-website.wiki.git](https://github.com/crosswalk-project/crosswalk-website.wiki.git)
on GitHub. Whenever a commit is made to the wiki, the Gollum Event Webhook on
GitHub invokes the regen.php page on the live site; this in turn recreates
the content viewed in the Pages and History pages of the Wiki.

    Fetches of actual Wiki content are proxied on the server to GitHub
via php (XHR on the client is blocked due to Access-Control-Allow-Origin;
currently PHP doesn't cache the fetched content - it should).
The wiki content is served to the client in the format output by the
GitHub wiki system (Gollum). The client-side javascript (see xwalk.js
content_response and generate_wiki_page) creates pages using
content from the wiki: it pulls out the wiki DOM element, performs some
URL rewrites, and then injects the resulting content into the Crosswalk
website page requested by the browser.

# Content management for crosswalk-project.org

## Managing documentation for different Crosswalk versions

The content on crosswalk-project.org relates to the current stable release
of Crosswalk.

Documentation for previous versions stays on the wiki.

## Editing wiki content

The content under the `#wiki/` path on crosswalk-project.org is maintained
in the crosswalk-website wiki. This can either be edited via
[github](https://github.com/crosswalk-project/crosswalk-website/wiki) or
by cloning the wiki github repo (the latter is required if you want to
add images).

See https://crosswalk-project.org/#wiki/Editing-the-Wiki for instructions.

## Editing non-wiki content

Other content on crosswalk-project.org is managed through this
project (crosswalk-website). The process for editing this content is
as follows:

*   [Fork the crosswalk-website repo](https://help.github.com/articles/fork-a-repo).

*   Make your edits (see [HACKING.md](HACKING.md)).

*   [Make a pull request via github](https://help.github.com/articles/using-pull-requests)
asking for your changes to be merged into the master branch of the
crosswalk-website repo.

# Workflow

The general work flow for releasing a new version of the
Crosswalk website is as follows:

1.  Develop on a local machine (see [HACKING.md](HACKING.md))
2.  Create a 'live snapshot' via the command `./site.sh mklive`
3.  Push latest 'live snapshot' to the staging server via the script:
`./site.sh push`
4.  Test the results on the staging server at http://stg.crosswalk-project.org/
5.  Push the staging version to the live server via the command
`./site.sh push live`

To push to the staging or live servers, you will need to be given access
by the Crosswalk infrastructure team.

NOTE: You can determine the version of the website that is active by
fetching the file [REVISION](https://crosswalk-project.org/REVISION).
The part before the colon is the branch name, the part after the colon
is the commit-id. This file is queried by several of the functions
defined in scripts/common.inc.

## Cached dynamic content

There are several pieces of content that are generated during the site
development. These include:

*   xwalk.css <= generated from xwalk.scss
*   markdown.css <= generated from markdown.scss
*   menus.js <= generated from menus.js.php
*   wiki/pages.md.html <= generated from regen.php via gfm.php
*   wiki/history.md.html <= generated from regen.php via gfm.php
*   contribute/\*.html <= generated from the markdown files in
contribute/*.md via gfm.php
*   documentation/\*.html <= generated from the markdown files in
contribute/*.md via gfm.php

On your local development server, all of the above are regenerated
dynamically when any source file changes (via .htaccess and gfm.php).

On the staging and live servers, no content is generated on the fly:
the mklive script generates a one-off snapshot from the source files
(*.md, *.scss etc.). This snapshot is then pushed to the server as static
HTML/CSS/JS. See `./site.sh --help mklive` for details.

# Setting up a server for crosswalk-website

**This is a list of one time setup instructions for hosting crosswalk-website.
These steps have already been carried out on the live and production
servers, and do not have to be repeated to make a new release of the site.
However, they are provided for reference in case the setup needs to be
done again on a different server, or if the existing setup needs to be
modified or debugged.**

## Server software requirements

Running the live version requires the rewrite module in Apache2.
This is used in the wiki subsystem to map requested URLs through
a PHP script which will then perform smart matching of leaf names.

Enable the rewrite module via:

    sudo a2enmod rewrite
    sudo service apache2 restart

The rest of the content is served from static files that are generated
as part of the development cycle, described in the Workflow section (above).

In addition, you will need to enable the cURL extension for PHP. This is
used by the gfm.php script to perform HTTP requests for pages from the
Crosswalk wiki. Enable it by editing the `php.ini` file for the PHP
installation and adding this line (there should be several other
`extension` lines in `php.ini` already, so add it after those):

    extension=php_curl.so

## Server configuration

To host the Crosswalk website, the following needs to be done on the server:

    # Initialize the site content into the docroot directory
    git clone https://github.com/crosswalk-project/crosswalk-website.git docroot
    cd docroot

    # make a clone of the wiki content
    git clone --bare https://github.com/crosswalk-project/crosswalk-website.wiki.git wiki.git

    # make the wiki and cache directories owned by Apache
    sudo chown -R wwwrun wiki.git
    sudo chown -R wwwrun wiki
    sudo chown -R wwwrun cache

    # create a configuration file with github credentials (see below)
    sudo cp site-config.php.template site-config.php
    vi site-config.php

    # Switch to the latest live branch
    . scripts/common.inc
    branch=$(get_remote_live_info)
    echo ${branch} > REVISION
    git checkout -f ${branch}

At this point, the site is now initialized and set to the latest
live branch. The `scripts/` directory is not part of the live version
of the site - once you checkout `${branch}`, you won't be able to run
any of the scripts on the live site.

The chown command is necessary as regen.php writes to wiki.git and wiki,
which also executes as the Apache user. On Ubuntu this user is `wwwrun`,
but if you're using a different operating system, or a different Apache
distribution, the user may be someone else; for example, XAMPP uses
`nobody` as the user.

## Site configuration

The `site-config.php` file should be created on the server in the root
directory, using the `site-config.php.template` file as the template.

`site-config.php` requires credentials for accessing the github API
(see the `github.php` script for how the github API proxy is implemented).
The proxy is used by the Downloads and Channels Viewer pages, to
populate the Crosswalk version numbers, download URLs, commit SHAs etc.

The credentials required are a **Client ID** and **Client Secret**,
which can be created by [registering a new application against a github
account](https://github.com/settings/applications/new).

## github configuration for wiki

The crosswalk-project.org website needs to be set up in tandem with a
[github hook](http://developer.github.com/v3/repos/hooks/) to ensure that
any changes to the wiki are immediately reflected on the website.
The hook should invoke the `regen.php` script for any `gollum` events
triggered by changes to the [crosswalk project's
wiki](https://github.com/crosswalk-project/crosswalk-website/wiki).

However, this hook has to be manually added to the github project for
crosswalk-website: the built-in web hooks available via github
<em>Settings</em> cannot be set up to respond to gollum wiki events.

### Adding the hook to the crosswalk-website github project

The JSON data to required for configuring the hook looks like this:

    {
      "name": "web",
      "active": true,
      "events": [
        "gollum"
      ],
      "config": {
        "url": "http://crosswalk-project.org/regen.php",
        "content_type": "json"
      }
    }

Paste this into a file and post it to the github API using an HTTP client
tool; for example, if the JSON file was called `config.json` and you
were posting it as the `foo` user via `curl`, you would do:

    curl -k -u foo -d @config.json \
      https://api.github.com/repos/crosswalk-project/crosswalk-website/hooks

You should get a response from the API which indicates whether the
request was successful. From now on, any time the wiki for
crosswalk-website changes, the script at
http://crosswalk-project.org/regen.php will be invoked
with details of the pages which changed.
