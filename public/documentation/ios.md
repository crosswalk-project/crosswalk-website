# Crosswalk Project for iOS

## Introduction

Crosswalk Project for iOS is a sub-project of Crosswalk, aiming to provide a web runtime to develop sophisticated iOS native or hybrid applications.

* Extended WKWebView

  Crosswalk Project for iOS is built on top of `WKWebView`, the modern WebKit framework debuted in iOS 8. We extend the WKWebView to build Crosswalk extension framework within. For the detailed information you may refer to [Embedding Mode & WKWebView](/documentation/ios/embedding_mode_&_native_apis.html).

* Crosswalk Extension Framework

  Extensions are a way to extend the ability of the Crosswalk runtime. You can write your functionalities in both Swift and Objective-C codes and expose it as a JavaScript function or object. All JavaScript stub code can be generated automatically based on the native interface. For more information please refer to [Crosswalk Extension](/documentation/ios/extensions.html).

* Cordova Plugins Support

  To leverage existing Cordova plugins, a Cordova extension is provided to simulate the Cordova environment. You only need to place source files of Cordova plugins into your project and register the classes of plugins in the manifest. For more information please refer to [Cordova Plugins Support](/documentation/ios/cordova_plugin_support.html).

## System Requirements

Development:

* iOS SDK 8+
* Xcode 6+

Deployment:

* iOS 8+

## Quickstart

Follow the [Quick Start Guide](/documentation/ios/quick_start_guide.html) to setup a simple XWalkView based application from scratch.

## Demos

There are 3 built-in demos in the project:

* [Sample](https://github.com/crosswalk-project/crosswalk-ios/tree/master/Demos/Sample)

	A simple demo which shows how to embed a XWalkView, implement Crosswalk Extensions, configuring extensions and application, etc.

* [CordovaPluginDeviceDemo](https://github.com/crosswalk-project/crosswalk-ios/tree/master/Demos/Cordova/CordovaPluginDeviceDemo)

	A demo to show the way to integrate a Cordova Plugin with the Crosswalk Cordova Extension support, and the usage of `apache.cordova.device` plugin.

* [CordovaPluginFileDemo](https://github.com/crosswalk-project/crosswalk-ios/tree/master/Demos/Cordova/CordovaPluginFileDemo)

	Another Cordova Plugin demo, which is imported from https://github.com/Icenium/sample-file.git, which demostrates the usage of `apache.cordova.file` plugin.

NOTICE: Try them after the project's submodules get initialized, using:

  ```bash
  git submodule update --init --recursive
  ```

## Licence

Crosswalk Project for iOS is available under the BSD license. See the [LICENSE](https://github.com/crosswalk-project/crosswalk/blob/master/LICENSE) file for more information.
