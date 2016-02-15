# Crosswalk App Tools

Crosswalk-app-tools is a new, NPM-based tool for creating and building Crosswalk applications. It replaces the Python-based make_apk.py script.  It is based on Node.js and therefore a cross-platform tool. Current host platforms supported are Microsoft Windows, Linux, and Apple OS X.

### Pre-requisites

* Node.js
* NPM
* Android SDK with 5.0 (target-21) or later
* Java JDK
* Apache Ant

Full setup instructions are available in the [Getting Started section](/documentation/getting_started.html).

### Installation

    > npm install -g crosswalk-app-tools

Root or adminstrative priveleges may be needed.

After installing, check dependencies:
    > crosswalk-app check android

Two executables are provided:

* crosswalk-app: implements low level helper commands
* crosswalk-pkg: main tool for creating packages

## Usage

### crosswalk-app
 Crosswalk Project application creation and build tool
 
<div class="usage">
 Usage: crosswalk-app &lt;options&gt;

   crosswalk-app check [&lt;platforms&gt;]    Check host setup
                                        Check all platforms if none given

   crosswalk-app manifest &lt;path&gt;        Initialize web manifest in &lt;path&gt;
                --package-id=&lt;pkg-id&gt;   Canonical package name e.g. com.example.foo
                --platforms=&lt;target&gt;    Optional, e.g. "windows"

   crosswalk-app create &lt;package-id&gt;    Create project &lt;package-id&gt;
                 --platforms=&lt;target&gt;   Optional, e.g. "windows"

   crosswalk-app build &lt;type&gt; [&lt;dir&gt;]   Build project to create packages
                                        type = [release|debug]
                                        Defaults to "debug" when not given
                                        Builds in current dir by default

   crosswalk-app platforms              List available target platforms

   crosswalk-app help                   Display usage information

   crosswalk-app version                Display version information

 <strong>Options for Android platform</strong>
 Usage: crosswalk-app create 'android' &lt;options&gt;

   --android-crosswalk                  Channel name (stable/beta/canary)
						                or version number (w.x.y.z)
						                or crosswalk zip
						                or xwalk_app_template dir
   --android-lite                       Use crosswalk-lite, see Crosswalk Wiki for details
   --android-shared                     Depend on shared crosswalk installation
   --android-targets                    Target ABIs to create

 Environment variables for platform 'android'

   CROSSWALK_APP_TOOLS_CACHE_DIR        Keep downloaded files in this dir

 Options for 'build' command
 Usage: crosswalk-app build &lt;options&gt;

   --android-targets                    Target ABIs to build

 <strong>Options for Windows platform</strong>

   --windows-crosswalk                  Path to crosswalk zip

</div>

### crosswalk-pkg
 Crosswalk Project application packaging tool

<div class="usage">
 Usage: crosswalk-pkg &lt;options&gt; &lt;path&gt;

  &lt;options&gt;
    -a --android=&lt;android-conf&gt;      Extra configurations for Android
    -c --crosswalk=&lt;version-spec&gt;    Specify Crosswalk version or path
    -h --help                        Print usage information
    -k --keep                        Keep build tree for debugging
    -m --manifest=&lt;package-id&gt;       Fill manifest.json with default values
    -p --platforms=&lt;android|windows&gt; Specify target platform
    -r --release                     Build release packages
    -t --targets=&lt;target-archs&gt;      Target CPU architectures
    -v --version                     Print tool version

  &lt;path&gt;
    Path to directory that contains a web app

  &lt;android-conf&gt;
    Quoted string with extra config, e.g. "shared"
      "shared"  Build APK that depends on crosswalk in the Google Play Store
      "lite"    Use crosswalk-lite, see Crosswalk Wiki

  &lt;package-id&gt;
    Canonical application name, e.g. com.example.foo, needs to
     - contain 3 or more period-separated parts
     - begin with lowecase letters

  &lt;target-archs&gt;
    List of CPU architectures for which to create packages.
    Currently supported ABIs are: armeabi-v7a, arm64-v8a, x86, x86_64
     - Prefixes will be matched, so "a","ar", or "arm" yield both ARM APKs
     - Same for "x" and "x8", but "x86" is an exact match, only x86-32 conforms
     - Short-hands "32" and "64" build ARM and x86 for the requested word size
     - Default behavior is equivalent to "32", creation of 32-bit installers
    Example: --targets="arm x86" builds both ARM plus 32-bit x86 packages

  &lt;version-spec&gt;
     - Channel name, i.e. stable/beta/canary
     - Version number, e.g. 14.43.343.25
     - Path to release, e.g. $HOME/Downloads/crosswalk-14.43.343.25.zip
     - Path to build, e.g. crosswalk/src/out/Release/xwalk_app_template
    When passing a local file or directory, only the contained ABIs can be built.
    See &lt;target-archs&gt; for details.

  Environment variables
    CROSSWALK_APP_TOOLS_CACHE_DIR=&lt;path&gt;: Keep downloaded files in this dir

</div>


## Example: Creating and packaging an application

To get started, all you need is a web manifest and an html file. The web manifest holds name and settings for your application. A minimal manifest.json looks like this:

<pre><code>{
  "name": "My first Crosswalk application",
  "start_url": "index.html",
  "xwalk_package_id": "com.example.foo"
}
</code></pre>

Then add an index.html in the same directory:

    <!DOCTYPE html>
    <html>
       <head>
          <title>My first Crosswalk application</title>
       </head>
       <body>This is my first Crosswalk application</body>
    </html>

Finally, time to create the apk package:

    > crosswalk-pkg <path>

This sets up a skeleton project, downloads and imports Crosswalk, and creates a package using the files above.

## License

The license for this project is the Apache License Version 2.0, please refer to the LICENSE-APACHE-V2 included with the package for details.
