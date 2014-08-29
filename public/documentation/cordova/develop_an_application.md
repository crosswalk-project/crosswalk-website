# Developing a Crosswalk Cordova application

These instructions were tested with crosswalk-cordova-${XWALK-STABLE-CORDOVA-X86} (for x86 architecture); both an emulated x86 and a physical x86 Android device were used as deployment targets.

The development environment used was Linux (Fedora 20), but the instructions should also work for Windows.

## Set up the host

Crosswalk Cordova for Android needs the following software to be installed on the development host:

*   Java JDK (version 1.5 or greater)
*   Apache Ant (version 1.8.0 or greater)
*   Python (version 2.6 or greater)
*   Android SDK (with Android platform version 4.0 or later installed)
*   [node](http://nodejs.org)

The host setup instructions ([Windows](/documentation/getting_started/windows_host_setup), [Linux](/documentation/getting_started/linux_host_setup)) explain how to install all of these pre-requisites, except node.

To install node, follow the [instructions on the nodejs.org website](http://nodejs.org/download/).

Additional instructions for installing the Java JDK, Apache Ant, and the Android SDK can be found in the [Cordova Android Platform Guide](http://cordova.apache.org/docs/en/3.3.0/guide_platforms_android_index.md.html#Android%20Platform%20Guide).

### Download the crosswalk-cordova-android bundle

Once you've installed the pre-requisite software, download the crosswalk-cordova-android bundle from [the downloads page](/documentation/downloads). Ensure that you get the correct bundle for your target architecture.

For example, to get the stable x86 version:

    $ wget https://download.01.org/crosswalk/releases/crosswalk/android/stable/${XWALK-STABLE-CORDOVA-X86}/x86/crosswalk-cordova-${XWALK-STABLE-CORDOVA-X86}-x86.zip

Or the ARM version:

    $ wget https://download.01.org/crosswalk/releases/crosswalk/android/stable/${XWALK-STABLE-CORDOVA-ARM}/arm/crosswalk-cordova-${XWALK-STABLE-CORDOVA-ARM}-arm.zip

If you prefer, you can download Crosswalk Cordova via a browser by following the links on the [Downloads page](/documentation/downloads).

Once you've downloaded the zip file, unzip it (WinZip, 7Zip, command line etc.).

## Set up the target

You will need an Android target to deploy the Cordova application to. You can either use a real Android device or an emulated one. Instructions for both are on the [Android target setup page](/documentation/getting_started/android_target_setup).

## Create a sample application

Create a sample application using the Crosswalk Cordova tools (inside the unpacked bundle in the `bin/` directory):

    $ crosswalk-cordova-${XWALK-STABLE-CORDOVA-X86}-x86/bin/create <project_directory> <package_name> <project_name> [<template_path>] [--shared]

The project will be generated in the directory `<project_directory>`, using the application template specified by `<template_path>` (or the default "Hello world" template if `<template_path>` is not set); `<project_name>` sets the application name in the `AndroidManifest.xml` file.

The `--shared` option prevents the content of the `crosswalk-cordova-android/framework` folder being copied into the project. Note that when you build the Android package for your application, the Cordova and Crosswalk libraries are still included: the `--shared` option just affects how much space the project takes up on your development machine.

For example, to create a "HelloWorld" app:

    $ crosswalk-cordova-${XWALK-STABLE-CORDOVA-X86}-x86/bin/create HelloWorld org.crosswalkproject.sample HelloWorld

You should use your own Java package name for your application, using [the standard Java package naming conventions](http://docs.oracle.com/javase/tutorial/java/package/namingpkgs.html).

A new project is created in the `HelloWorld` directory. The example HTML, JS and CSS files are located in `HelloWorld/assets/www` (you can replace them with your own files later).

## Build and run the application

Build HelloWorld:

    $ cd HelloWorld
    $ ./cordova/run

(Note that there is a separate `./cordova/build` command which will just build the application; but the `run` command will do a build first before installing and running the application.)

The command installs the HelloWorld package on the target and starts it. (If there are no attached Android devices, the `run` command will also try to start an emulated Android target before installation.)

On the target, you should see the "APACHE CORDOVA" page with a blinking "DEVICE IS READY" element. For example, this is HelloWorld running on an x86 ZTE Geek phone:

<img src="assets/cordova-hello-world.png">

Please refer to [Android Command-line Tools](http://cordova.apache.org/docs/en/3.3.0/guide_platforms_android_tools.md.html#Android%20Command-line%20Tools) for more command-line commands.

## Debug the application

Remote debugging works as for standard Crosswalk apps.

First, you need to build your app with the `--debug` option enabled:

    cd HelloWorld
    ./cordova/build --debug

Then run it as usual:

    ./cordova/run

You should now be able to follow the instructions on [this page](/documentation/getting_started/remote_debugging) to debug the application.

## Debug Cordova Crosswalk

You may be in the situation where the application crashes before Crosswalk itself starts (i.e. the runtime crashes before the web application can be loaded). To see what's happening in this situation, you can follow the system logs on the target:

    $ cd HelloWorld
    $ ./cordova/log

This shows the Android device log, dynamically updated as events occur on the target.

Now start your application. You should see some output as the application attempts to start, and hopefully get more information about any errors.

## Importing a project into ADT

A project generated using the command-line tools can be imported into the Android Developer Tools (ADT) (GUI-based development environment) by following these steps:

1.  Open ADT. Depending on your environment, you may already have a menu entry to start it; if not, run the `eclipse/eclipse` command from inside your Android SDK's root directory.
2.  In the *File* menu, choose *Import...*.
3.  In the *Import* dialog box, choose *Android* then *Existing Android Code into Workspace*. Click the *Next* button.
4.  A file browser window will open. Choose the directory for the generated project; for example, for the HelloWorld project, the directory is **HelloWorld**.
5.  The *Import Projects* dialog will indicate that it is ready to import three projects: **xwalk_core_library**, **HelloWorld-CordovaLib** and **HelloWorld**. Click the *Finish* button to import the projects.
6.  <p>Build each project in turn, in this order:</p>

    <ol>
    <li><strong>xwalk_core_library</strong></li>
    <li><strong>CordovaLib</strong></li>
    <li><strong>HelloWorld</strong></li>
    </ol>

    <p>You may need to turn off the automatic build option (uncheck <em>Project</em> > <em>Build Automatically</em> in Eclipse) so you can build the projects manually and in the correct order.</p>

    <p>If all builds pass, the application was imported correctly by ADT, and you are ready to continue development.</p>
