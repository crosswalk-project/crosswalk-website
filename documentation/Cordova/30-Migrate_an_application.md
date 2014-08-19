# Migrate an existing Cordova application to Crosswalk

A project which was built with Cordova can be modified so it uses Crosswalk as the webview, rather than the Cordova default (Android webview). The [introduction](#documentation/cordova) explains the advantages of doing this.

*   If you don't have a Cordova application you want to migrate, or would rather experiment with someone else's project, follow the steps in the [Create a Cordova application](#create-a-cordova-application) section to create a test application.
*   If you already have a Cordova application to migrate, skip to the [Migrate](#migrate) section.

## Create a Cordova application

In this section, you will create a Cordova Android application which will be migrated to Crosswalk Cordova for Android later in the tutorial.

**Note:** If you already have a Cordova application you want to migrate, you don't need to follow the steps in this section.

### Set up the Cordova command line tools

You'll be using the command line version of Cordova to create the project, so you need to install pre-requisites as described in [Cordova command line](http://cordova.apache.org/docs/en/3.5.0/guide_cli_index.md.html).

Once you have the pre-requisites, you can install the Cordova command line tools with `npm`:

    $ npm install -g cordova@3.5

(You're installing Cordova 3.5.*, as this is the version supported by the current stable Crosswalk.)

This installs the tools globally so they are available from any shell.

### Create the Cordova project

The Cordova project you'll create in this section will just have support for the Android platform; but you could obviously add other Cordova-supported platforms if desired. However, Crosswalk will only be used for the runtime on Android, regardless of how many platforms you add to the project.

To create the project for the application:

1.  Create the base Cordova project:

        $ cordova create kitchensink com.crosswalkproject.sample KitchenSink

    This creates the KitchenSink project in the `kitchensink/` directory.

2.  Add support for the Android platform:

        $ cd kitchensink
        $ cordova platform add android

3.  Import source code. Here you're importing an existing public "kitchen sink" project, [html5-kitchensink-cordova-xdk-af](https://github.com/krisrak/html5-kitchensink-cordova-xdk-af.git), as the web part of the application, replacing the default `www/` directory.

    This project exercises most of the Cordova APIs, as well as APIs provided by the [Intel XDK](http://xdk-software.intel.com/). It also uses UI elements from Intel's [appframework](http://app-framework-software.intel.com/).

    Run these commands inside the `kitchensink/` directory to clone the kitchen sink project:

        $ rm -Rf www
        $ git clone https://github.com/krisrak/html5-kitchensink-cordova-xdk-af.git www

4.  Add the required plugins for this application by running these commands inside the `kitchensink/` directory:

        cordova plugin add https://git-wip-us.apache.org/repos/asf/cordova-plugin-battery-status.git#r0.2.8
        cordova plugin add https://git-wip-us.apache.org/repos/asf/cordova-plugin-camera.git#r0.2.9
        cordova plugin add https://git-wip-us.apache.org/repos/asf/cordova-plugin-contacts.git#r0.2.10
        cordova plugin add https://git-wip-us.apache.org/repos/asf/cordova-plugin-device.git#r0.2.9
        cordova plugin add https://git-wip-us.apache.org/repos/asf/cordova-plugin-device-motion.git#r0.2.7
        cordova plugin add https://git-wip-us.apache.org/repos/asf/cordova-plugin-device-orientation.git#r0.3.6
        cordova plugin add https://git-wip-us.apache.org/repos/asf/cordova-plugin-dialogs.git#r0.2.7
        cordova plugin add https://git-wip-us.apache.org/repos/asf/cordova-plugin-file.git#r1.1.0
        cordova plugin add https://git-wip-us.apache.org/repos/asf/cordova-plugin-file-transfer.git#r0.4.3
        cordova plugin add https://git-wip-us.apache.org/repos/asf/cordova-plugin-geolocation.git#r0.3.7
        cordova plugin add https://git-wip-us.apache.org/repos/asf/cordova-plugin-globalization.git#r0.2.7
        cordova plugin add https://git-wip-us.apache.org/repos/asf/cordova-plugin-inappbrowser.git#r0.4.0
        cordova plugin add https://git-wip-us.apache.org/repos/asf/cordova-plugin-media.git#r0.2.10
        cordova plugin add https://git-wip-us.apache.org/repos/asf/cordova-plugin-media-capture.git#r0.3.0
        cordova plugin add https://git-wip-us.apache.org/repos/asf/cordova-plugin-network-information.git#r0.2.8
        cordova plugin add https://git-wip-us.apache.org/repos/asf/cordova-plugin-splashscreen.git#r0.3.0
        cordova plugin add https://git-wip-us.apache.org/repos/asf/cordova-plugin-vibration.git#r0.3.8

    Note that the kitchen sink application uses all the Cordova plugins. Also note that the `cordova` command wraps the `plugman` tool, described in the [Add plugins](#documentation/cordova/add_plugins) page. You can get more details about how plugins are installed from that page.

5.  Build the Android package:

        $ cordova build android

6.  Install the package on the target and run the application:

        $ cordova run android

If the above steps work correctly, the kitchen sink application should be running on the target. For example, here it is on an x86 ZTE Geek:

<img src="assets/cordova-kitchensink.png">

## Migrate to Crosswalk

**Before you can migrate your application to Crosswalk, you need to download and unpack a crosswalk-cordova-android bundle. See [these instructions](#documentation/cordova/develop_an_application/Download-the-crosswalk-cordova-android-bundle).**

You can migrate a Cordova application to Crosswalk in two ways:

*   [Migrate using command line tools](#migrate-using-command-line-tools)
*   [Migrate using ADT](#migrate-using-adt)

The following sections explain these two approaches. In these sections, the **kitchensink** application is referred to (i.e. it's assumed that you created the test application to migrate, as explained above). If you are migrating your own application, replace **kitchensink** with the name of your project.

### Migrate using command line tools

Once you have the application working with standard Cordova, you can move on to migrating the application to use Crosswalk as its webview:

1.  Enter the project directory:

        $ cd kitchensink

2.  Remove the contents of the `platforms/android/CordovaLib` directory (containing the Cordova libraries which adapt the Android webview):

        $ rm -Rf platforms/android/CordovaLib/*

    These will be replaced with the Crosswalk-enabled equivalent.

3.  Copy the `framework/` directory from the crosswalk-cordova-android bundle you unpacked earlier:

        $ cp -a <path_to_unpacked_bundle>/framework/* \
            platforms/android/CordovaLib/

    Note that the content of the `framework/` directory contains some architecture-specific library files. Make sure that the crosswalk-cordova-android bundle you downloaded matches the architecture of the Android target you're deploying to.

4.  Copy the `VERSION` file from the unpacked bundle into the Android platform directory:

        $ cp -a <path_to_bundle>/VERSION platforms/android/

5.  Crosswalk requires an extra permission which is not automatically added by the Cordova application generator. Add this manually be editing `platforms/android/AndroidManifest.xml`, adding this line just after the existing `<uses-permission>` elements:

        <uses-permission android:name="android.permission.ACCESS_WIFI_STATE" />

6.  <a name="build-project"></a>Build the projects in this order:

    <ol>
    <li><strong>xwalk_core_library</strong></li>
    <li><strong>CordovaLib</strong></li>
    <li><strong>kitchensink</strong></li>
    </ol>

    The sequence of commands you need is as follows (from the top-level project directory):

        # this finds the root directory of the Android SDK installed
        # on your system, assuming the android command is on your
        # path; this variable is required for the manual builds below
        $ export ANDROID_HOME=$(dirname $(dirname $(which android)))

        $ cd platforms/android/CordovaLib/

        # this updates the CordovaLib project and the
        # xwalk_core_library subproject and target Android 4.4.2 (API
        # level 19)
        $ android update project --subprojects --path . \
            --target "android-19"

        # build both the CordovaLib and xwalk_core_library projects
        $ ant debug

        # build the Android apk file
        $ cd ../../..
        $ cordova build android

    The `android update` command used above takes a `--target` option, specifying which Android API level you want to target. If you only have one platform versions installed in your Android SDK, this option is not required; but if you have multiple platform versions installed, you need to specify which one to use.

    To get a list of the targets available in your Android SDK, run this command:

        $ android list targets

    Here's a sample of the output when Android 4.3 (API level 18) and Android 4.4.2 (API level 19) are available:

        ----------
        id: 1 or "android-18"
             Name: Android 4.3
             Type: Platform
             API level: 18
             Revision: 2
             Skins: QVGA, WXGA720, WQVGA432, HVGA, WVGA800 (default), WQVGA400, WXGA800, WXGA800-7in, WVGA854, WSVGA
         Tag/ABIs : no ABIs.
        ----------
        id: 2 or "android-19"
             Name: Android 4.4.2
             Type: Platform
             API level: 19
             Revision: 3
             Skins: QVGA, WXGA720, WQVGA432, HVGA, WVGA800 (default), WQVGA400, WXGA800, WXGA800-7in, WVGA854, WSVGA
         Tag/ABIs : default/armeabi-v7a, default/x86

    The `id` lines show the integer or string you need to pass to the `--target` option.

    By default, Cordova will build a debug package for your application. You can build a release version instead using:

        $ cordova build --release android

    Once you've built a release package, you will need to [sign and align it](http://developer.android.com/tools/publishing/app-signing.html) if you intend to install it to an Android device or distribute it via an app store.

7.  The output Android `.apk` files are in the `kitchensink/platforms/android/bin/` directory. You can either install them from there with `adb`:

        $ cd kitchensink
        $ adb install platforms/android/bin/KitchenSink-debug.apk

    then manually start them from the menu on the Android target.

    Or you can use `cordova` from the top-level directory of the project to install and run the application:

        $ cd kitchensink/
        $ cordova run android

    The application should now be running on the target.

8.   The application should look identical to how it does on standard Cordova. If you are using a debug build (i.e. you didn't apply the `--release` option), you should also be able to [debug your application using Chrome](#documentation/cordova/develop_an_application/Debug-the-application).

    You can also use the dev tools to confirm your application is using Crosswalk. Open "chrome://inspect" in a Chrome browser and select the "inspect" link for the *Intel XDK Kitchen Sink* application (or for your own application).

    In the Chrome dev tools, you should now have a JavaScript console. You can verify that your application is using Crosswalk as its webview by echoing the `navigator.userAgent` property to the console:

        > navigator.userAgent
        "Mozilla/5.0 (Linux; Android 4.2.2; ZTE V975 Build/JDQ39) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.116 Mobile Crosswalk/7.36.154.12 Mobile Safari/537.36"

    Note the presence of the "Crosswalk/N.N.N.N" string, which indicates that the application is using Crosswalk.

### Multi-architecture packages

One down-side of the current Crosswalk Cordova is that it is not architecture-agnostic: by default, the method for building a Crosswalk Cordova application (above) produces a package for a single architecture.

For the application to run on both x86 and ARM targets, you need to incorporate the native libraries for both architectures in the package. Eventually, this should be done seamlessly for you by the Crosswalk Cordova tools; for now, it requires some manual labour.

If you followed the steps above, you already have an application with support for x86 architecture. The key file providing this support is `libxwalkcore.so` (in the `platforms/android/CordovaLib/xwalk_core_library/libs/x86` directory). This is a shared object library, loaded by the Android system, which enables the Crosswalk runtime.

To add support for ARM, you need a similar `libxwalkcore.so` file, but compiled for ARM architecture and in the correct directory. Once the package contains both `.so` libraries, the Android system and Crosswalk will choose either the x86 or ARM `.so` library as appropriate for the architecture. Fortunately, the file you need is readily available in the crosswalk-cordova-android bundle.

To incorporate the `.so` file for the other architecture, follow these steps:

1.  Download the crosswalk-cordova-android bundle for the architecture you want to add (x86 or ARM). Unzip it.

2.  Copy the directory containing the `.so` file to the correct location:

        $ cd kitchensink
        $ cp -a <path_to_unpacked_bundle>/framework/xwalk_core_library/libs/* \
            platforms/android/CordovaLib/xwalk_core_library/libs/

    For example, if you're copying from an ARM version of crosswalk-cordova-android, this will result in the following file being added to the project:

        armeabi-v7a/libxwalkcore.so

3.  Rebuild the package: see [the earlier instructions](#build-project).

4.  Make sure the Android target is running and visible to the host (via `adb`).

5.  Install the package and run it:

        $ ./cordova/run

The only issue with this approach is that the `.apk` file you end up with is quite large (mine was 36Mb). This might be a problem if the app store you're hoping to deploy to has a limit on the size of packages.

### Migrate using ADT

These steps assume that you created your project using the Cordova command line tools.

<ol>
  <li>Open ADT. Depending on your environment, you may already have a menu entry to start it; if not, run the `eclipse/eclipse` command from inside your Android SDK's root directory.</li>

  <li>

    <p>Import the crosswalk-cordova-android bundle libraries into ADT:</p>

    <ol>

      <li>In the <em>File</em> menu, choose <em>Import...</em>.</li>

      <li>In the <em>Import</em> dialog box, choose <em>Android</em> then <em>Existing Android Code into Workspace</em>. Click the <em>Next</em> button.</li>

      <li>

        <p>Set the <em>Root Directory</em> by clicking the <em>Browse</em> button. Browse to the <code>framework/</code> directory inside the crosswalk-cordova-android bundle you unpacked earlier. Under <em>Projects to Import</em>, two projects should be displayed:</p>

        <ol>
          <li><strong>Cordova</strong></li>
          <li><strong>xwalk_core_library</strong></li>
        </ol>

        <p>Click <em>Finish</em> to import both. The projects should now be visible in the <em>Package Explorer</em>.</p>

      </li>

    </ol>

  </li>

  <li>

    <p>Import the directory containing your existing Cordova application into ADT.</p>

    <ol>

      <li>Select <em>File</em> > <em>Import...</em>; then <em>Android</em> > <em>Existing Android Code into Workspace</em>. Click <em>Next</em>.</li>

      <li>

        <p>Click <em>Browse</em> to browse to the root directory of the project you want to import. Under <em>Projects to Import</em>, two projects should be displayed:</p>

        <ol>
          <li><strong>Your application project</strong>, located in <code>platforms/android/</code>.</li>
          <li><strong>CordovaLib</strong>, located in `platforms/android/CordovaLib`.</li>
        </ol>

        <p>Uncheck the CordovaLib project then click <em>Finish</em> to just import your application project. The project should now be visible in the <em>Package Explorer</em>.</p>

      </li>

    </ol>

    <p>Note that if you have set up ADT with the web development tools, you can also import the `www/` directory containing your web application code into ADT (not covered in these steps).</p>

  </li>

  <li>Configure the app to depend on the libraries in the crosswalk-cordova-android bundle. Right-click on the project name in the <em>Package Explorer</em>; then, in the context menu, choose <em>Properties</em>.</li>

  <li>In the <em>Properties</em> dialog, choose the <em>Android</em> section. Under <em>Library</em>, you'll notice that there is a reference to a <strong>CordovaLib</strong> project with a red cross next to it (the reference is broken because you didn't import the CordovaLib project). Select this reference then click on <em>Remove</em> to remove it.</li>

  <li>

    <p>Now configure your project to use a reference to the CordovaLib project inside the crosswalk-cordova-android bundle. Click <em>Add...</em> to display the <em>Project Selection</em> dialog. First add the <strong>CordovaLib</strong> project, then the <strong>xwalk_core_library</strong> project.</p>

    <p>The final result should resemble this:</p>

    <img src="assets/cordova-project-refs-adt.png">

  </li>

  <li>
    <p>Build each project in turn, in this order:</p>

      <ol>
        <li><strong>xwalk_core_library</strong></li>
        <li><strong>CordovaLib</strong></li>
        <li><strong>HelloWorld</strong></li>
      </ol>

    <p>You may need to turn off the automatic build option (uncheck *Project* > *Build Automatically* in Eclipse) so you can build the projects manually and in the correct order.</p>

    <p>If all builds pass, the application was imported and built correctly by ADT, and you are ready to continue development.</p>
  </li>

  <li>

    <p>To test the application on a target, right-click on the application project in the <em>Package Explorer</em>. Choose <em>Run As...</em> > <em>Android Application</em>. A list of available targets should be displayed, for example:</p>

    <img src="assets/cordova-adt-target-select.png">

    <p>Select the target you want and click <em>OK</em> (remember the architecture must be aligned with the architecture of the crosswalk-cordova-android bundle). ADT will package the project, install it on the target, and run it.</p>

  </li>

</ol>

### Migrating with Crosswalk 5

The above instructions work for applications built with Cordova 3.3, 3.4 and 3.5. However, if you are using Crosswalk 5, you will need to add an additional permission for your application, otherwise the package will build and install, but the application won't run.

The additional permission should be added to your Cordova project's `platforms/android/AndroidManifest.xml` file, just after any existing `<uses-permission>` elements:

    <uses-permission android:name="android.permission.ACCESS_NETWORK_STATE" />

## Adding plugins to a migrated project

To add plugins to a project you've migrated to Crosswalk, use the standard Cordova `cordova plugin` command. For example, to add the device motion plugin, from the top-level project directory call:

    cordova plugin add https://git-wip-us.apache.org/repos/asf/cordova-plugin-device-motion.git#r0.2.4

This will put the required files in the `platforms/android` directory and register the plugin with the project.

## Acknowledgements

The [kitchen sink application](https://github.com/krisrak/html5-kitchensink-cordova-xdk-af) used in this tutorial is maintained by Intel's [Rakshith Krishnappa](https://github.com/krisrak). It is released under an [MIT licence](https://github.com/krisrak/html5-kitchensink-cordova-xdk-af/blob/master/LICENSE).
