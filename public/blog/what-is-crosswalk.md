# What is Crosswalk and how you can use it with Intel <abbr>XDK</abbr>

[Crosswalk](https://crosswalk-project.org/) is a HTML5 runtime, built on open source foundations, which extends the web platform with new capabilities.

This means Android developers can now deploy your mobile HTML5 application with its own runtime without a dependence on the native `WebView` that is on your customer's device for all Android 4.0+ devices. This means

- Uniform runtime across all devices and so you don't have to deal with runtime differences on different devices running different versions of the OS. 
- Control the runtime upgrade cycle
- Better memory management

## What is Crosswalk and why should you use it?

Using Crosswalk also allows you to use [state-of-the-art W3C* recommended HTML5 APIs](https://crosswalk-project.org/#documentation/apis). Several of the standards-based web features requested by app developers are enabled in Crosswalk and can be used today. Some examples of APIs supported in Crosswalk include:

- WebGL support
- Websockets
- Reliable Web Audio API support with advanced audio effects
- WebRTC for Real Time Communications
- Raw sockets
- [Presentation API (WiDi)](https://crosswalk-project.org/#wiki/presentation-api-manual)
- [SIMD (Single Instruction Multiple Data) support for faster numeric operations](https://01.org/blogs/tlcounts/2014/bringing-simd-javascript)

## Using Intel® XDK to build a Crosswalk based Android App

You can use the Intel® XDK to develop, test, debug, profile and build your Crosswalk App. You can either start with creating a new project or play with some of our existing Crosswalk demos like the WebRTC Video, Threejs WebGL or any of the existing Cordova samples to explore using XDK with Crosswalk.

<div class="pullquote" data-pullquote="features requested by app developers are enabled in Crosswalk and can be used today"></div>

There is also a [detailed documentation](http://software.intel.com/en-us/html5/xdkdocs#508153) explaining how to use the different features of the Intel XDK for development of a Crosswalk based Android App.

Here are some important points you should keep in mind when using Crosswalk with the Intel XDK:

- We support the [Cordova 3.3 APIs](http://cordova.apache.org/docs/en/3.3.0/index.html) and plugins for Crosswalk
- We do not yet support Intel XDK APIs when building Crosswalk apps. So, if you are using Intel XDK Javascript APIs, we recommend you either use the equivalent Cordova APIs or build using the non-Crosswalk version of Android Build target.
- Use the Debug tab (and not the test tab) to test a Crosswalk based app. The test feature using App Preview uses a different runtime on your Android phone and hence will not be an accurate representation of the final apk.
- Currently Crosswalk build target builds and provides download links for two apks - one each for x86 and ARM CPUs. We strongly recommend you submit both the apks to Stores like [Google Play Store](http://developer.android.com/google/play/publishing/multiple-apks.html) to insure all of your customers can run your Crosswalk app.
- If your Crosswalk build fails, check for the following:
-- Verify that your bundle id/app name and other identifiers do not contain special characters (!$#% or even numbers at times). While we have covered most of them for you, we might have forgotten a few. You can verify this in the App Details page in the Build tab.
-- Verify that there are no files in your www directory with unicode characters in all file names. In short, try to avoid special characters in filenames. This is particularly important for non-English locations. We are hoping to add support for unicode characters in the build system, but this will take some time.
