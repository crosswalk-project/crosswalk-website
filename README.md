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

## Adding Blog Posts

The Crosswalk blog accepts static posts written in Markdown. If your post was called “Meet Crosswalk”, create the file `meet-crosswalk.md` in `public/blog/`. Add your post’s metadata in `public/blog/_data.json`. It will probably look something like this:

```js
"meet-crosswalk": {
  "title": "Meet Crosswalk",
  "date": "2014-10-16T12:00",
  "author": "Annie Person"
},
```

If you’d like, you can also include a path to a large “Hero” image for the blog post:

```js
"meet-crosswalk": {
  "title": "Meet Crosswalk",
  "date": "2014-10-16T12:00",
  "author": "Annie Person",
  "hero": "/assets/illustrations/my-hero-image.png"
},
```

### Adding Remote Blog Posts

If you are linking to a remote post rather than a local post—for example, a post on [the Chromium blog about Crosswalk](http://blog.chromium.org/2014/09/now-with-faster-dev-workflow-and-modern.html)—you only need to edit the `public/blog/_data.json` file. The key, in this case `chrome-apps-for-mobile`, must be unique, but the `url` will be what’s used to link to the external post.

```js
"chrome-apps-for-mobile": {
  "title": "Chrome Apps for Mobile: Now with a faster dev workflow and a modern WebView",
  "date": "2014-09-22T09:00",
  "author": "Michal Mocny",
  "url": "http://blog.chromium.org/2014/09/now-with-faster-dev-workflow-and-modern.html",
  "desc": "…now you have a way to leverage the latest Chromium WebView on any device running Android versions back to Ice Cream Sandwich by bundling your Chrome App with an embeddable Chromium WebView, provided by the Crosswalk open source project."
},
```
