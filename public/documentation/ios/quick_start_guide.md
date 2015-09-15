# Quick Start Guide

This guide describes how to create a Crosswalk web application, and how to create a hybrid application with Crosswalk XWalkView support.

## Create Crosswalk Web Application

### Prerequisites

1. Xcode
2. Valid Apple Developer Account
3. NPM
4. Crosswalk App Tools with iOS backend

### Creation

1. Create your demo application `org.example.foo` with command:

```
crosswalk-app create org.example.foo
```

2. Develop your application

In the directory `org.example.foo/app`, you can see the template files `icon.png`, `index.html` and `manifest.json` has already been created for you.

The `manifest.json` is the main configuration interface of your web application which is cross platform, you can refer to [iOS Manifest](manifest.html) page for more details on iOS platform.

3. Build your application with command:

```
cd org.example.foo
crosswalk-app build
```

After the build succeeds, you will get the `foo.ipa` in the directory.

4. Install your application

Open iTunes, connect your iOS device(iPhone/iPad) which should be already registered in your development group. Select `Application` page, drag the `foo.ipa` into the `Applications` list, and sync. Then the `foo.ipa` will be installed onto your iOS device.

## Create Crosswalk Hybrid Application

### Create the application project

  * Open Xcode, create an iOS application project with the "Single View Application" template in the working directory.  For this example, we use "Echo".  Use Swift for convenience.

  * We use CocoaPods to integrate the `crosswalk-ios` library and Crosswalk extensions(if needed) into our demo application. For the CocoaPods' installation and usage, please refer to: [CocoaPods](https://cocoapods.org/).

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

    Which tells CocoaPods that the deploy target is iOS 8.0 above, and we integrate library `crosswalk-ios` with the latest version of `1.0.x`. Do remember to add `use_frameworks!` because `crosswalk-ios` is partly written in Swift, it has to be built as a framework instead of a static library.

  * Install `Pods` target into your project. Quit the Xcode first, then in the `Echo` directory, use command:

  ```
  pod install
  ```

  After the installation, you will find an `Echo.xcworkspace` is generated, and CocoaPods' output will notify you that use this workspace instead of the `Echo.xcodeproj` from now on.

  Open the `Echo.xcworkspace`, you will find there are tow projects `Echo` and `Pods` in the workspace.

  * For quick test, replace the content of auto-generated `ViewController.swift` with the corresponding file in [crosswalk-ios/AppShell/AppShell/ViewController.swift](https://github.com/crosswalk-project/crosswalk-ios/blob/master/AppShell/AppShell/ViewController.swift), which has setup a `XWalkView` instance for you already.

  * Create a directory called `www` in `Echo` for the HTML5 files and resources.

    Create the `index.html` file as your entry page with the contents as follows:

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

  * Add the `www` directory into the project.

    In `File` -> `Add Files to "Echo"...`, choose the `www` directory and select `Create folder reference`.

  * Create `manifest.plist` to describe the application's configuration.

    * In `File` -> `New...` -> `File...`, choose `iOS` -> `Resource` -> `Property List`.  Create a plist file with name `manifest.plist` in `Echo` directory. This manifest file will be loaded at application startup;

    * Add an entry with the key: `start_url` and the string value: `index.html`.  This is the entry page. `XWalkView` will locate it in the `www` directory.

      ![manifest1](https://cloud.githubusercontent.com/assets/700736/7226211/36a710c0-e779-11e4-9852-000d3bab8f57.png)

  * The `Echo` demo is ready to run now. Press 'Run' button and it will be deployed and run on your iOS simulator.

  This is the first step of building the Echo demo. If you need to know how to setup a hybrid project with your own Crosswalk extension, please go to: [Extension](extensions.html) for more details.

