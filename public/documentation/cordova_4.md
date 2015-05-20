# Crosswalk WebView in Cordova 4.0
## Overview
With the introduction in [Cordova Android 4.0.0](http://cordova.apache.org/announcements/2015/04/15/cordova-android-4.0.0.html) of pluggable WebView support, it is now easy to use the Crosswalk WebView with your Cordova app. By using the Crosswalk WebView plugin, developers can enjoy remote debugging capabilities, cutting edge HTML5 features such as WebGL, WebAudio and WebRTC, and significant performance enhancements on Android devices all the way down to Android 4.0 Ice Cream Sandwich (ICS).
## Prerequisites
Please refer to the [Android Platform Guide](https://cordova.apache.org/docs/en/4.0.0/guide_platforms_android_index.md.html#Android%20Platform%20Guide) to setup the Android SDK environment for Cordova application development.

Make sure you have an up-to-date version of [node.js](https://nodejs.org/) installed on your system.
## Workflow

1.  Install the Cordova Command Line Interface (CLI)

        $ npm install -g cordova

    Check that the version of Cordova CLI is >= 5.0.0:

        $ cordova -v
        5.0.0

2.  Create a Cordova example app, which can be used as a stub for creating new projects

        $ cordova create hello com.example.hello HelloWorld

3.  Navigate to the newly created project directory

        $ cd hello

    All subsequent commands must be run within the project's directory (e.g. `hello`)

4.  Add Android as the target platform

        $ cordova platform add android

    This adds the Cordova Android platform (version >= 4.0.0) into the app.

5.  Install the Crosswalk WebView plugin

        $ cordova plugin add cordova-plugin-crosswalk-webview

    This adds the [Crosswalk WebView cordova plugin](https://www.npmjs.com/package/cordova-plugin-crosswalk-webview/) into the app.

6.  Build with Crosswalk WebView engine for Android

        $ cordova build android

    This automatically fetches the stable Crosswalk WebView libraries from the Crosswalk Project download site (https://download.01.org/crosswalk/releases/crosswalk/android/) and build for both X86 and ARM architectures. For example, building a `HelloWorld` project generates:

        /path/to/hello/platforms/android/build/outputs/apk/android-x86-debug.apk
        /path/to/hello/platforms/android/build/outputs/apk/android-armv7-debug.apk

    The Crosswalk WebView library will be embedded in your app. This adds about 18MB to the APK size.

7.  Launch it in the emulator

        $ cordova emulate android

    Launching a Cordova app using the Crosswalk WebView is done the same way as Cordova app. The command will launch the emulator, install the application APK, and then launch your app. As an example, after launching the above Cordova example, you will see the following result on your screen:

    <img src="/assets/cordova-in-emulator.jpg" />

    Please refer to The [Command-Line Interface](https://cordova.apache.org/docs/en/4.0.0/guide_cli_index.md.html#The%20Command-Line%20Interface) for a full listing of commands and options.


## Remote debugging with Crosswalk

The Crosswalk WebView allows remote debugging the Cordova app, even on old Android devices (4.0+).

You can use desktop Google Chrome to debug any apps running on Crosswalk remotely. After the app launches, navigate to “chrome://inspect” to list the debuggable app on the device. For example, when inspecting the `HelloWorld` app running in the emulator with Crosswalk:

<img src="/assets/cordova-devtools-inspect.jpg" />

By clicking the “inspect” link under `Hello World`, a Chrome DevTools window will be opened. In the JavaScript console tab, you can make sure the app is running on Crosswalk by inspecting `navigator.userAgent`. For example, the app is running on Crosswalk 13 (Chromium M42):

<img src="/assets/cordova-with-devtools.jpg" />

Please refer to [Remote Debugging on Android with Chrome](https://developer.chrome.com/devtools/docs/remote-debugging) for the user manual.


## (Optional) Android Platform Only Workflow

This workflow is for developers who want to stay on the bleeding edge of development. Go to Cordova CLI workflow if you want a stable release.

[git](http://www.google.com/url?q=http%3A%2F%2Fgit-scm.com%2F&sa=D&sntz=1&usg=AFQjCNFOqwvh2KbuCJQUVsR5fW38FrTRTw) is required.

1.  Pull the cordova-android repo

        $ git clone https://github.com/apache/cordova-android.git

2.  Install plugman

        $ npm install -g plugman

    Check that the version of plugman is >= 0.22.17

        $ plugman -v
        0.23.1

    For more information about plugman, please refer to [Using Plugman to Manage Plugins](https://cordova.apache.org/docs/en/4.0.0/plugin_ref_plugman.md.html#Using%20Plugman%20to%20Manage%20Plugins).

4.  Create a Cordova example app

        $ /path/to/cordova-android/bin/create hello com.example.hello HelloWorld

5.  Navigate to the project directory

        $ cd hello

    All subsequent commands need to be run within the project's directory.

6.  Install Crosswalk WebView engine plugin

        $ plugman install --platform android \
         --plugin https://github.com/crosswalk-project/cordova-plugin-crosswalk-webview.git \
         --project .

    Note: as there are binary files in the git history, the first git cloning downloads about 100MB. This  might be slow across a low bandwidth connection.

7.  Build with the Crosswalk WebView engine

        $ ./cordova/build

    This automatically fetches the Crosswalk WebView libraries from the Crosswalk Project release site (https://download.01.org/crosswalk/releases/crosswalk/android/) and builds for both X86 and ARM architectures. For example, it generates:

        /path/to/hello/build/outputs/apk/hello-x86-debug.apk
        /path/to/hello/build/outputs/apk/hello-armv7-debug.apk

8.  Launch in emulator

        $ ./cordova/run --emulator --nobuild

    This launches the emulator, installs the application APK, and launches your app. 

For more commands of cordova-android, please refer to [cordova-android README.md](https://github.com/apache/cordova-android).
