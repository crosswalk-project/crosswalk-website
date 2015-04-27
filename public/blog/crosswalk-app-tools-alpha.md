We are happy to announce the alpha version for Crosswalk-app-tools. This is a preview milestone of our forthcoming Node.js based tools for Android app development. Deployment in a production environment is not encouraged at this time.

Crosswalk-app-tools is licensed under the Apache License Version 2.0, please refer to the file LICENSE-APACHE-V2 included in the package.

### Installation

Crosswalk-app-tools is cross-platform by virtue of being based on Node.js. However at this point most testing is done on Linux, so we expect the least number of hiccups there. In any case we have seen reports of successful runs on Apple OS X, and are looking forward to hearing about adventurous individuals giving it a spin on Microsoft Windows as well. (But be warned that there are known problems with proxy support on Windows, for starters)

The following components are required

   1. Android SDK with 5.0 (target-21) installed
   2. Java JDK and Apache Ant
   3. Node.js and NPM

The best way to check if a machine has all the required dependencies is to create and build a plain empty Android app on the system. If this does not work, then building Crosswalk apps will not succeed either.

```
android create project -a MainActivity -k com.example.foo -p com.example.foo -t android-21
cd com.example.foo
ant debug
```

In order to get the `crosswalk-app` script available everywhere, global npm installation is required. On most Linux distributions this can be achieved by using `sudo`.
```
sudo npm install -g crosswalk-app-tools
```

### Usage

```
Crosswalk Application Project and Packaging Tool

    crosswalk-app create <package-id>           Create project <package-id>

    crosswalk-app build [release|debug]         Build project to create packages
                                                Defaults to debug when not given

    crosswalk-app update <channel>|<version>    Update Crosswalk to latest in named
                                                channel, or specific version

    crosswalk-app help                          Display usage information

    crosswalk-app version                       Display version information

Options for platform 'android'

    For command 'create'
        --android-crosswalk                     Channel name (stable/beta/canary)
                                                or version number (w.x.y.z)
Environment Variables

    CROSSWALK_APP_TOOLS_CACHE_DIR               Keep downloaded files in this dir
```
#### Example: Create App
```
crosswalk-app create com.example.foo
```
This sets up a skeleton project in directory com.example.foo/, downloads and imports Crosswalk, and puts a sample "hello world" web app under com.example.foo/app/.

#### Example: Build App
```
cd com.example.foo
crosswalk-app build
```
Builds packages. The APKs can be found under pkg/ when done.

For more details, see the project's [README file](https://github.com/crosswalk-project/crosswalk-app-tools/blob/master/README.md).

## Highlights

* Straightforward creation and building of app packages (APKs) for Android. Crosswalk is downloaded and integrated into the build process without user intervention.

* Distributed via the Node Package Manager, with the Android SDK and Ant as only requirements, relying only on standard web app and Android development tools.

* Extensible architecture allows for adding operating system support and package formats over time.


## Limitations

* Tested and known to work on Linux. Support for additional host operating systems coming soon (has been found to work on OS X, although there is no official support yet).


## Upcoming features

* Support for W3C manifest.
* Support building release packages that can be round-trip tested via the Google Play Store.


## Issue tracking

Bugs are tracked and fixed as quickly as possible. Please enter those you find. Crosswalk JIRA can be found at https://crosswalk-project.org/jira/ ("App Tools" component)
