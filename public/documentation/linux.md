# Crosswalk Project for Linux

The Crosswalk Project for Linux enables the creation of Linux desktop applications using web technologies. It is based on the Chromium content layer and its integration with the Linux desktop UI.

As with other platforms, the Crosswalk Project for Linux supports advanced Web APIs like WebGL, WebRTC, WebAudio, ServiceWorker, SIMD and Web Manifest.

This distribution includes a .deb package of the Crosswalk runtime and a backend for the crosswalk-app-tools command line suite to package Crosswalk applications for Debian Linux. The Crosswalk package is tested on Ubuntu 14.04 and [Deepin Linux](http://www.deepin.org/) 2014.2

## Downloading and Installing Crosswalk Project for Linux

Download the Crosswalk deb package from https://download.01.org/crosswalk/releases/crosswalk/linux/deb/

Double click to open the deb file and start installing Crosswalk using the system software manager (you may need to input your administrator password).

Alternatively, you can directly use `sudo dpkg -i crosswalk_xxx.deb` to install it from the command line.
 
## Running a Crosswalk application

The simplest way to start a Crosswalk application is to use the `xwalk` command with the [application’s manifest](/documentation/manifest.html) as argument:

```
$ xwalk /path/to/manifest.json
```

Crosswalk will parse the manifest and launch the application from the entry point specified in `start_url`. Crosswalk supports both "packaged" and “hosted” applications, meaning that `start_url` can point either to a local file in the application folder or an external URL. See [here](https://crosswalk-project.org/documentation/manifest.html) for documentation about the Crosswalk manifest.

If the application was packaged in the XPK format, it can be launched directly with the xwalk command:

```
$ xwalk /path/to/app.xpk
```

Finally, if the application was packaged as a .deb package (see next section) it can be installed with `dpkg` and launched from its desktop icon or by invoking its name from the command line.

## Packaging a web application

**Package as .xpk**
To package a Crosswalk application as an XPK package, follow the instructions in https://github.com/crosswalk-project/crosswalk-website/wiki/Crosswalk-package-management#xpk-package-generator-python-version

The XPK package can be launched directly with the xwalk command (see the previous section)

**Package as .deb**
To package a Crosswalk application as a deb package, you’ll need to use the [crosswalk-app-tools](https://www.npmjs.com/package/crosswalk-app-tools/access) CLI suite and its debian backend. Follow the instructions in https://github.com/crosswalk-project/crosswalk-app-tools-deb to install the backend, and package the application with the command `crosswalk-app build`.

Note: crosswalk-app-tools does not yet support multiple backends. Once you install the .deb backend, you will only be able to create .deb packages until it is removed. You may want to keep multiple copies of crosswalk-app-tools to build for multiple platforms.

## Other notes and instructions

The “devscripts” and “debhelper” packages are needed to create debian packages with crosswalk-app-tools.

Crosswalk follows the [W3C manifest specification](http://www.w3.org/TR/appmanifest/). In particular, if no `display` member is specified in the manifest, Crosswalk will use `browser` as the default value and will display simple navigation controls with the application. To remove them, you need to explicitly specify `“display”: “standalone”` in the manifest.

When launching an application, Crosswalk will display the error

```
[0630/233246:ERROR:browser_main_loop.cc(185)] Running without the SUID sandbox! 
See https://code.google.com/p/chromium/wiki/LinuxSUIDSandboxDevelopment 
for more information on developing with the sandbox on.
```

This is because the suid sandbox is not enabled in Crosswalk (see https://crosswalk-project.org/jira/browse/XWALK-3839). It has no effect on the application, and as such the error can be safely ignored.

If you want to dig deeper into Crosswalk for Linux, the links below might prove useful:

* Building Crosswalk: https://crosswalk-project.org/contribute/building_crosswalk.html
* Contributing to Crosswalk: https://crosswalk-project.org/contribute/contributing-code.html
* Implementing Extensions: https://github.com/crosswalk-project/crosswalk-website/wiki/Crosswalk-Extensions
