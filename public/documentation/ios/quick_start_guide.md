# Quick Start Guide

This guide describes how to create a Crosswalk web application, and how to create a hybrid application with Crosswalk XWalkView support.

## Create Crosswalk Web Application

### Prerequisites

1. Xcode
2. Valid Apple Developer Account
3. NPM
4. Crosswalk App Tools with iOS backend

### Creation

1. Create a demo application `org.example.foo`

   ```
   crosswalk-app create org.example.foo
   ```

2. Develop the application

   In the directory `org.example.foo/app` the template files `icon.png`, `index.html` and `manifest.json` have already been created.

   The `manifest.json` file is the main configuration interface for your web application. It is cross platform. Refer to the [iOS Manifest](manifest.html) page for more details on the iOS platform.

3. Build the application

   ```
   cd org.example.foo
   crosswalk-app build
   ```

   If the build succeeds the file `foo.ipa` will be in the directory.

4. Install the application

   Open iTunes. Connect an iOS device (iPhone/iPad) which is already registered in your development group. Select the `Application` page, drag `foo.ipa` into the `Applications` list, and sync. `'foo.ipa` will be installed on the iOS device.

## Create Crosswalk Hybrid Application

### Create the application project

1. Open Xcode. Create an iOS application project with the "Single View Application" template in the working directory.  For this example, we use "Echo".  Use Swift for convenience.

2. Use CocoaPods to integrate the `crosswalk-ios` library and Crosswalk extensions (if needed) into the demo application. For the CocoaPods installation and usage, please refer to: [CocoaPods](https://cocoapods.org/).

    In the `Echo` directory, create a file called `Podfile`:

    ```
    cd Echo;
    touch Podfile;
    ```

    With the contents as below:

    ```
    platform: ios, '8.0'
    use_frameworks!
    pod 'crosswalk-ios', '~> 1.1'
    ```

    This tells CocoaPods that the deploy target is iOS 8.0+ and to integrate library `crosswalk-ios` with the latest version of `1.0.x`. Remember to add `use_frameworks!` because `crosswalk-ios` is partly written in Swift and it has to be built as a framework instead of a static library.

    Install `Pods` target into the project. Quit the Xcode first, then in the `Echo` directory, use command:

    ```
    pod install
    ```

    After the installation, you will find an `Echo.xcworkspace` is generated, and CocoaPods output will notify you to use this workspace instead of the `Echo.xcodeproj` from now on.

   Open the `Echo.xcworkspace`. There will be two projects: `Echo` and `Pods`.

  * For quick test, replace the contents of the auto-generated `ViewController.swift` with the corresponding file in [crosswalk-ios/AppShell/AppShell/ViewController.swift](https://github.com/crosswalk-project/crosswalk-ios/blob/master/AppShell/AppShell/ViewController.swift), which has set up a `XWalkView` instance for you already.

3. Create a directory called `www` in `Echo` for the HTML5 files and resources.

4. Create the `index.html` file as your entry page with the contents as follows:

    ```html
    <html>
      <head>
        <meta name='viewport' content='width=device-width' />
        <title>Echo demo of Crosswalk</title>
      </head>
      <body>
        <h2>Echo Demo of Crosswalk</h2>
      </body>
    </html>
    ```

5.  Add the `www` directory into the project.

    In `File` -> `Add Files to "Echo"...`, choose the `www` directory and select `Create folder reference`.

6. Create `manifest.plist` to describe the application's configuration.

    In `File` -> `New...` -> `File...`, choose `iOS` -> `Resource` -> `Property List`.  Create a plist file with name `manifest.plist` in `Echo` directory. This manifest file will be loaded at application startup;

    Add an entry with the key: `start_url` and the string value: `index.html`.  This is the entry page. `XWalkView` will locate it in the `www` directory.

      ![manifest1](https://cloud.githubusercontent.com/assets/700736/7226211/36a710c0-e779-11e4-9852-000d3bab8f57.png)

The `Echo` demo is ready to run now. Press 'Run' button and it will be deployed and run on your iOS simulator.

This is the first step in building the Echo demo. If you need to know how to setup a hybrid project with your own Crosswalk extension, please go to: [Extension](extensions.html) for more details.

