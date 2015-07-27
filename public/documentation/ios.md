# Crosswalk Project for iOS

## Introduction

The Crosswalk Project for iOS provides a web runtime for sophisticated iOS native and hybrid applications.

* Extended WKWebView

  The Crosswalk Project for iOS is built on top of `WKWebView`, the modern WebKit framework that debuted in iOS 8. We extend the WKWebView with the Crosswalk extension framework. For detailed information, refer to [Embedding Mode & Native APIs](/documentation/ios/embedding_mode_&_native_apis.html).

* Crosswalk Extension Framework

  Extensions allow you to extend the ability of the Crosswalk runtime. You create your feature using Swift or Objective-C and expose it as a JavaScript function or object.  The JavaScript stub code can be generated automatically based on the native interface. For more information, refer to [Extensions](/documentation/ios/extensions.html).

* Cordova Plugins Support

  To leverage existing Cordova plugins, a Cordova extension is provided to simulate the Cordova environment. Simply place the source files of the Cordova plugins into your project and register the plugin classes in the manifest. For more information, refer to [Cordova plugin support](/documentation/ios/cordova_plugin_support.html).

## System Requirements

Host development system:

* <a href="https://developer.apple.com/ios/">iOS SDK 8+</a>
* <a href="https://developer.apple.com/ios/">Xcode 6+</a>

Target system:

* iOS 8+

## Quickstart

Follow the [Quick Start Guide](/documentation/ios/quick_start_guide.html) to setup a simple XWalkView based application from scratch.

## Demos

The following 3 demos are available:

* [Sample](https://github.com/crosswalk-project/crosswalk-ios/tree/master/Demos/Sample)

	A simple app that shows how to embed a XWalkView, implement Crosswalk Extensions, and configuring the extensions and application.

* [CordovaPluginDeviceDemo](https://github.com/crosswalk-project/ios-extensions-crosswalk/tree/master/demos/CordovaPluginDeviceDemo)

	An example that shows how to integrate a Cordova Plugin with the Crosswalk Cordova Extension support, and usage of the `apache.cordova.device` plugin.

* [CordovaPluginFileDemo](https://github.com/crosswalk-project/ios-extensions-crosswalk/tree/master/demos/CordovaPluginFileDemo)

	Another Cordova Plugin demo, imported from https://github.com/Icenium/sample-file.git, that demostrates the usage of the `apache.cordova.file` plugin.

NOTE: Try the demos after the project's submodules get initialized, using:

  ```bash
  git submodule update --init --recursive
  ```

## Licence

The Crosswalk Project for iOS is available under the [BSD license](https://github.com/crosswalk-project/crosswalk/blob/master/LICENSE).
