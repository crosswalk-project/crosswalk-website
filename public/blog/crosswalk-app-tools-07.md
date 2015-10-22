We are happy to announce a new release of [Crosswalk App Tools](https://www.npmjs.com/package/crosswalk-app-tools/), our standalone packaging tool based on node.js. This release has everything you need to package your web application with Crosswalk for Android in the simplest way.

Many of our users build Crosswalk applications using of one of the several frameworks that integrate Crosswalk, but with App Tools we want to provide a separate tool for developers to quickly package their web apps with Crosswalk and take advantage of all the Crosswalk features, like extensions and [Web Manifest](https://w3c.github.io/manifest/). 

We encourage users of the make_apk script to move to the Crosswalk App Tools as we will be deprecating make_apk by the end of the year. 

## Release Highlights

* A new tool that makes packaging as easy as typing `crosswalk-pkg <path>`
* Support of Chrome Devtools for debug-build packages through
chrome://inspect/ enables real-world production use.
* Vastly improved support for configuring web apps using the [W3C Web Manifest](https://w3c.github.io/manifest/) through manifest.json. For details please refer to our [documentation](https://github.com/crosswalk-project/crosswalk-app-tools/blob/0.7/manifest.md).

## Installation and Usage

* Microsoft Windows: `npm install -g crosswalk-app-tools`
* Apple OS X and Linux: `sudo npm install -g crosswalk-app-tools`

Complete instructions can be found online:

https://github.com/crosswalk-project/crosswalk-app-tools/blob/0.7/README.md#installation

For a command reference, please refer to our documentation, or the
tool’s own help output:

https://github.com/crosswalk-project/crosswalk-app-tools/blob/0.7/README.md#usage

## Additional Target Platforms

Crosswalk-app-tools supports additional target platforms through
installable modules. We have forthcoming implementations for

* Crosswalk on iOS:
   https://github.com/crosswalk-project/crosswalk-app-tools-ios
* Crosswalk on Debian Linux
   https://github.com/crosswalk-project/crosswalk-app-tools-deb
* Crosswalk on Windows will be enabled in the next release.

## How to Contribute

We welcome contributions! The source code is available on Github under an Apache 2.0 license: https://github.com/crosswalk-project/crosswalk-app-tools

If you don't know where to start have a look at our [Jira](https://crosswalk-project.org/jira) under the "App Tools" component. 