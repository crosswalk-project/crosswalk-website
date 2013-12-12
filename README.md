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
currently the server doesn't cache the fetched content - it should).
The wiki content is served to the client in the format output by the
GitHub wiki system (Gollum). The client-side javascript (See xwalk.js
content_response and generate_wiki_page) creates a DOM element with
the content from the wiki, pulls out the wiki DOM element, performs some
URL rewrites, and then injects the resulting content into the Crosswalk
website page being displayed in the browser.

# Workflow

The general work flow is as follows:

1.  Develop on local machine (see [HACKING.md](HACKING.md))
2.  Create a 'live snapshot' via the script: `./site.sh mklive`
3.  Push latest 'live snapshot' to the staging server via the script:
`./site.sh push`
4.  Test the results on the staging server
5.  Push the staging version to the live server via the script:
`./site.sh push live`

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

If running in development mode, all of the above are regenerated when
the source changes (via .htaccess and gfm.php).

When you execute the mklive script, all of that content is regenerated
as part of the site snapshot creation. See `./site.sh --help mklive`.

# Initializing the server with the crosswalk-website

To host the Crosswalk website, the following needs to be done on the server:

    # Initialize the site content into the docroot directory
    git clone https://github.com/crosswalk-project/crosswalk-website.git docroot
    cd docroot

    # make a clone of the wiki content
    git clone --bare https://github.com/crosswalk-project/crosswalk-website.wiki.git wiki.git

    # make the wiki directories owned by Apache
    sudo chown -R wwwrun wiki.git
    sudo chown -R wwwrun wiki

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

# Server requirements for the live website

Running the live version requires the rewrite module in Apache2.
This is used in the wiki subsystem to map requested URLs through
a PHP script which will then perform smart matching of leaf names.

Enable the rewrite module via:

    sudo a2enmod rewrite
    sudo service apache2 restart

The rest of the content is served from static files that were generated
as part of the development cycle as described in the Workflow section.
