# Using Extensions for Intel RealSense technology

Web APIs for [Intel® RealSense™ technology](http://www.intel.com/realsense) are now available for Windows applications. (Note: Support for Android will be considered once hardware is available and shipping with this capability.)

Currently, the following features are supported:

* Depth Enhanced Photography ([spec](http://crosswalk-project.github.io/realsense-extensions-crosswalk/spec/depth-enabled-photography.html))
* Scene Perception ([spec](http://crosswalk-project.github.io/realsense-extensions-crosswalk/spec/scene-perception.html))
* Face Tracking and Recognition ([spec](http://crosswalk-project.github.io/realsense-extensions-crosswalk/spec/face.html))

Developers can create 3D-aware web applications that can be run on any Windows tablet with a built-in [R200 camera](https://software.intel.com/en-us/RealSense/R200Camera), such as the [HP Spectre x2](http://store.hp.com/us/en/ContentView?storeId=10151&langId=-1&catalogId=10051&eSpotName=new-detachable), or a Windows PC with peripheral R200 camera.

## Setup

Setup the Windows host as described here: [Windows System Setup](/documentation/windows/windows_host_setup.html).

Currently you need to install a beta version of `crosswalk-app-tools` from the git repo:

    > npm install -g https://github.com/crosswalk-project/crosswalk-app-tools.git

Ensure the version of `crosswalk-app-tools` is above 0.10.0:

    > crosswalk-pkg -v
	0.10.0

Build a Crosswalk app by referring to https://crosswalk-project.org/documentation/windows/build_an_application.html

## Use RealSense Extensions

Get the Crosswalk RealSense extensions. You can either fetch the binary zip from a release or build them by referring to https://github.com/crosswalk-project/realsense-extensions-crosswalk/wiki/Dev-Instructions-for-Windows#building-extensions.

* Copy the `realsense_extensions` folder to the root directory of your app.
* Modify your application's `manifest.json` to include the realsense extensions:

  Add `"xwalk_command_line": "--use-rs-video-capture"`:
    
````
{
  "name": "My Cool RealSense App",
  "start_url": "index.html",
  "xwalk_package_id": "com.example.myapp",
  "xwalk_command_line": "--use-rs-video-capture",
  "xwalk_extensions": [
    "realsense_extensions/enhanced_photography",
    "realsense_extensions/face",
    "realsense_extensions/scene_perception"
  ]
}
````

## Sample App
A simple application has been created to demonstrate how to use the APIs.

Download Crosswalk Windows 18.48.475.0 from https://download.01.org/crosswalk/releases/crosswalk/windows/canary/18.48.475.0/crosswalk-18.48.475.0.zip.

Package app with Crosswalk Runtime and RealSense Extensions to installers.

    > crosswalk-pkg -p windows -c C:\path\to\crosswalk-18.48.475.0.zip myapp

This creates two files:

* `com.example.myapp-0.1.0.0.msi`: the MSI installer without Intel RealSense SDK (RSSDK) runtime installer.
* `com.example.myapp_with_rssdk_runtime_0.1.0.0.exe`: the WiX bootstrapper installer with the RSSDK runtime web installer. It launches the RSSDK runtime web installer to install dependencies during the application installation.

