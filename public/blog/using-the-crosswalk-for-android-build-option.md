# Using the Intel XDK “Crosswalk for Android” Build Option

With the Crosswalk runtime you can create, preview, debug, profile and package Crosswalk applications all from within the Intel XDK, without the need to install any additional software.

This article provides some of the information you need to get started with the Crosswalk runtime. Read [this overview of Crosswalk](http://software.intel.com/en-us/html5/xdkdocs#496374) for more details regarding the Crosswalk runtime and API.

## Creating a Crosswalk App

If you want to create a new application that runs on Crosswalk, you have several options:

- Use “Start with a Blank Project” and then add your own code.
- Use “Start with App Designer” or “Use App Starter” to create a new project.
- Use “Start with a Demo” and select “Crosswalk” from the drop-down “Filter by keyword” menu.

Many existing applications will run on Crosswalk, including applications that use the standard Cordova APIs and the Intel XDK APIs (those that are supported as Cordova plugins).

## Previewing on Device

_NOTE: Crosswalk applications will not work with the Test tab or when using App Preview on non-Android devices._

Use the Debug tab to preview your app. Follow the on-screen instructions for connecting your Android 4.x device via USB to your development system. If you just want to preview your Crosswalk app press the “launch” icon (the rocket ship). You can also debug your app on this tab using the “bug” icon, or use the Profile tab to profile your app's CPU usage. The Debug and Profile tabs run your app inside a special Crosswalk container on an Android device for a very accurate rendition of an actual Crosswalk application.

## Emulating Inside the Intel XDK

If you have an Android 4.x device, it is easiest, and most accurate, to preview and debug using your Android device and the Debug tab. If you do not have access to an Android 4.x device you can run your Crosswalk app in the Emulator tab. The emulator runs inside a modern Chromium web runtime, which supports many of the same APIs as Crosswalk, but not all of them. In particular, the emulator does not support the following Crosswalk APIs:

- Presentation
- Vibration
- Device Orientation and Acceleration

## Building a Crosswalk Package

The Intel XDK includes an option to package your app with the Crosswalk runtime, so that it is ready to submit to an Android store. To do this, click the Build tab and select the Crosswalk for Android build target.

Keep the following in mind when building a Crosswalk for Android app:

- The build server will generate both an x86 and an ARM architecture binary (two APKs). This is done automatically; the resulting APKs will only run on devices that match the corresponding CPU architecture (the x86 APK runs only on x86 devices and the ARM APK runs only on ARM devices). It is strongly recommended you submit both APKs when deploying to Android app stores, in order to reach the maximum audience and minimize issues associated with downloading mismatched APK images. Follow the instructions in [Submitting Multiple Crosswalk APKs to the Google Play Store*](https://software.intel.com/en-us/html5/articles/submitting-multiple-crosswalk-apk-to-google-play-store) for details about submitting multiple APKs. If you follow this procedure, the store will automatically deliver the appropriate APK to your end user's device.
- Select the plugins corresponding to the Cordova and Intel XDK APIs you are using in your application on the Details page. Specifics regarding Crosswalk support for Cordova APIs, and any exceptions, can be found in the [Crosswalk wiki](https://crosswalk-project.org/#wiki/Plugins-List-@-3.3.0-Supported-by-Crosswalk-Cordova-Android). Documentation for the standard Cordova plugins and their corresponding APIs are available on the [Cordova documentation website](http://cordova.apache.org/docs/en/3.3.0/index.html). Documentation regarding the Intel XDK plugins and their corresponding APIs supported in the Crosswalk container are provided in the [Intel XDK Name Space for Cordova Plugins](http://software.intel.com/en-us/html5/articles/intel-xdk-api-cordova-plugin-methods-properties-events) document.
- If you want to test against the latest Crosswalk code base for bug fixes or new features, you can opt to build using the [beta version of Crosswalk code base](https://crosswalk-project.org/#documentation/downloads). This option is available on the Details page under “Select Code Base.” The default option is to use the stable version. If you choose to use the beta code base, please note that this version of code base has not been completely tested, hence your application may be buggy when built using this option; this build option is not recommended for deployment to app stores.
