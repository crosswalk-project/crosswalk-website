# Quick Start Guide

Here we'd like to show you the quick demo to setup a native application with Crosswalk extension support.

## Setup The Working Directory

  * Create a working directory called `EchoDemo` in a suitable place, then `cd EchoDemo`;

  * Clone the `crosswalk-ios` project with command:

    `git clone https://github.com/crosswalk-project/crosswalk-ios.git`

  * Initialize the `crosswalk-ios` submodules with commands:

    ```bash
      cd crosswalk-ios
      git submodule update --init --recursive
      cd -
    ```

## Create The Application Project

  * Create an iOS application project called `Echo` with the "Single View Application" template in the working directory, and use language "Swift" for convenience.

  * Add `XWalkView` project into the `Echo` project.

    In `File` -> `Add Files to "Echo"...`, choose the `XWalkView.xcodeproj` from `crosswalk-ios/XWalkView/XWalkView.xcodeproj`;

    Now the project layout should look like this:

    ![projectLayout1](https://cloud.githubusercontent.com/assets/700736/8390277/c7080352-1cbe-11e5-8fe7-81f788ed6861.png)

  * Link `XWalkView` into `Echo` target.

    Select `Echo` target in `Echo` project;

    ![selectEcho](https://cloud.githubusercontent.com/assets/700736/8390270/c65d47a0-1cbe-11e5-8b8f-6dd2e48f612b.png)

    In `General` -> `Linked Frameworks and Libraries`, add `XWalkView.framework`.

    ![linkXWalkView1](https://cloud.githubusercontent.com/assets/700736/8390279/c749a460-1cbe-11e5-83b1-e5a51260e4a9.png)

    ![linkXWalkView2](https://cloud.githubusercontent.com/assets/700736/8390276/c6d22d9a-1cbe-11e5-8aa4-56d15d98b928.png)

  * For quick test, replace the auto-generated `ViewController.swift` with the corresponding file in `crosswalk-ios/AppShell/AppShell`, which has setup a WKWebView instance for you already.

    Just Remove the auto-generated `ViewController.swift` from your project, and add `crosswalk-ios/AppShell/AppShell/ViewController.swift` into it.

  * Create a directory called `www` in `Echo` to place your HTML5 files and resources.

    And create the `index.html` file in it as your entry page with the contents as follows:

    ```html
    <html>
      <head>
        <meta name='viewport' content='width=device-width' />
        <title>Echo demo of Crosswalk</title>
      </head>
      <body onload='onload()'>
        <h2>Echo Demo of Crosswalk</h2>
        <p id="content" style="font-size: 20px;" />
        <script>
          function onload() {
            xwalk.example.echo.echo('Hello World!', function(msg) {
              document.getElementById('content').innerHTML = msg;
            });
          }
        </script>
      </body>
    </html>
    ```

  * Add the `www` directory into the project.

    In `File` -> `Add Files to "Echo"...`, choose the `www` directory and select `Create folder reference`.

    Now the project layout should look like this:

    ![projectLayout2](https://cloud.githubusercontent.com/assets/700736/8390273/c687a5a4-1cbe-11e5-9260-73848b9e023f.png)

  * We need to crate a `manifest.plist` for `Echo` project to describe the application's configuration.

    * In `File` -> `New...` -> `File...`, choose `iOS` -> `Resource` -> `Property List`, create a plist file with name `manifest.plist` in `Echo` directory. This manifest file will be loaded at the application startup;

    * Add an entry with the key: `start_url` and the Stirng value: `index.html`, which indicates the file name of the entry page, `XWalkView` will locate it in the `www` directory automatically.

      ![manifest1](https://cloud.githubusercontent.com/assets/700736/7226211/36a710c0-e779-11e4-9852-000d3bab8f57.png)

  * Now your `Echo` demo can get run. As we haven't added any extension to support the functionality of the object `xwalk.example.echo`, you can only see the title: `Echo Demo of Crosswalk` on the page.

## Create The Echo Extension

  * Create a framework target called `EchoExtension` in the `Echo` project.

    In `File` -> `New...` -> `Target...`, choose `Framework & Library` -> `Cocoa Touch Framework`, with name `EchoExtension` and language type `Swift` for convenience.

    ![targets](https://cloud.githubusercontent.com/assets/700736/8390269/c6422de4-1cbe-11e5-9dd5-3e7ea021d741.png)

  * Link `XWalkView` into `EchoExtension` target.

    Select `EchoExtension` target in `Echo` project, in `General` -> `Linked Frameworks and Libraries`, add `XWalkView.framework`.

  * Create the `EchoExtension` extension class.

    Select `EchoExtension` group first, in `File` -> `New...` -> `File...`, choose `iOS` -> `Source` -> `Cocoa Touch Class`, with name `EchoExtension`, subclassing from `XWalkExtension`, and use `Swift` language type.

    ![createClass](https://cloud.githubusercontent.com/assets/700736/8390280/c76a93aa-1cbe-11e5-823a-bee32aa8f741.png)

    Now the project layout should look like this:

    ![projectLayout3](https://cloud.githubusercontent.com/assets/700736/8390274/c6be5cca-1cbe-11e5-83c3-f7dd375bc1d4.png)

  * Add the contents into `EchoExtension.swift` as follows:

  ```swift
  import Foundation
  import XWalkView

  class EchoExtension : XWalkExtension {
    func jsfunc_echo(cid: UInt32, message: String, callback: UInt32) -> Bool {
          invokeCallback(callback, key: nil, arguments: ["Echo from native: " + message])
          return true
      }
  }
  ```

  * Then we need to add the extension description section in the `Info.plist` of the `EchoExtension` target, to make the framework users aware that what kind of extensions are provided by this framework, and what are the extension names in both native and JavaScript world.

  ![projectLayout4](https://cloud.githubusercontent.com/assets/700736/8390272/c67221ca-1cbe-11e5-948d-e0f4e226f814.png)

    * Create `XWalkExtensions` section in the `Info.plist` with `Dictionary` type, then add an entry with `xwalk.example.echo` as key and `EchoExtension` as value.

      ![Info.plist](https://cloud.githubusercontent.com/assets/700736/8390278/c715238e-1cbe-11e5-9d25-eadcab37182b.png)

    This indicates that the extension `EchoExtension` will be exported with the object name: `xwalk.example.echo` in JavaScript.

## Bundle The Extension With The Application

  * Modify the `manifest.plist` to add the extension configuration to the `Echo` application.

    * Add `xwalk_extensions` section with Array type which describes the extensions that should be loaded within the `Echo` application;

    * And add `xwalk.example.echo` with String type as an entry of `xwalk_extensions`, which indicates that the extension `xwalk.example.echo` should be loaded into the JavaScript context of `Echo` project.

      ![manifest2](https://cloud.githubusercontent.com/assets/700736/7226213/3ef59a9e-e779-11e4-822f-1ef6775723ad.png)

  * Then you can build and run the application to test. If everything goes well, you can see an extra line: `Echo from native: Hello World!` displayed on the screen.

  Screenshot in iPhone6 simulator:

  ![screenshot](https://cloud.githubusercontent.com/assets/700736/8390271/c65d8bc0-1cbe-11e5-8ff5-2c537593403e.png)

