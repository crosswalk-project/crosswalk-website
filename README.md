# Introduction

This repository contains the source code for the
[Crosswalk website](http://crosswalk-project.org/). The live website
is generated from this code.

Any bugs for the website should be logged on the
[Crosswalk Jira](https://crosswalk-project.org/jira/), under the
[*Website*](https://crosswalk-project.org/jira/browse/XWALK/component/10203)
component.

Pull requests for the website should be submitted via
[github](https://github.com/crosswalk-project/crosswalk-website-v2/pulls).

This document gives an overview of the source and how to build the project 
on your system.

## Building the project

This static site for Crosswalk is built with:

- [Node.js](http://nodejs.org)
- [Harp](http://harpjs.com), the static web server with build-in preprocessing
- [KSS](https://github.com/kss-node/kss-node), which creates the styleguide

First, [install Node.js](http://nodejs.org). Then, run the following commands:

```
# Install Harp. You may need to preface this command with `sudo`
npm install --global harp

# Clone this project from GitHub
git clone https://github.com/crosswalk-project/crosswalk-website-v2.git

# Install the project’s dependencies
cd crosswalk-website-v2/
npm install

# Serve the project
harp server

# The project is now available at http://localhost:9000
```
## Create static web content for production

Harp can be used to create static web content. This is the content that the
current website uses.

### Build the Styleguide

The styleguide should be created first. The markup and CSS modules are 
documented in a Styleguide. It’s comparable to a miniature version of the
[documentation for Bootstrap](http://getbootstrap.com/css/), where each module
has an example and the accompanying code.

To build the Styleguide, run the following commands:

```
# Install dependencies
npm install -g kss

# Build the Styleguide
npm run styleguide
```
### Compile and generate static content

This site has been built to take advantage of Harp’s niceties, so the
web server should:
 * Create clean URLs by rewriting, for example, `about.html` to `about/`
 * Allow absolute paths from`/`

```
harp compile
```
The results are placed in "www" directory and can be viewed on your local
system with apache server by simply setting
```	DocumentRoot <path to www directory> ```
in the apache configuration file.


