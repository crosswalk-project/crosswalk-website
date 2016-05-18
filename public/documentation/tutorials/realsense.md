# Using Extensions for Intel RealSense technology

Web APIs for [Intel® RealSense™ technology](http://www.intel.com/realsense) are now available for Windows applications. (Note: Support for Android will be considered once hardware is available and shipping with this capability.)

Currently, the following features are supported:

* Depth Enhanced Photography ([API documentation](http://crosswalk-project.github.io/realsense-extensions-crosswalk/spec/depth-enabled-photography.html))
* Scene Perception ([API documentation](http://crosswalk-project.github.io/realsense-extensions-crosswalk/spec/scene-perception.html))
* Face Tracking and Recognition ([API documentation](http://crosswalk-project.github.io/realsense-extensions-crosswalk/spec/face.html))

Developers can create 3D-aware web applications that can be run on any Windows tablet with a built-in [R200 camera](https://software.intel.com/en-us/RealSense/R200Camera), such as the [HP Spectre x2](http://store.hp.com/us/en/ContentView?storeId=10151&langId=-1&catalogId=10051&eSpotName=new-detachable), or a Windows PC with peripheral R200 camera.

## Setup

### Setup Windows host
Setup the Windows host as described here: [Windows System Setup](/documentation/windows/windows_host_setup.html).

Ensure the version of `crosswalk-app-tools` is 0.10.0 or newer:

```cmdline
> crosswalk-pkg -v
0.10.2
```

Build a Crosswalk app by referring to this tutorial: [Build an Application](https://crosswalk-project.org/documentation/windows/build_an_application.html).

### Install RealSense Extensions

Download and unzip the Crosswalk RealSense extensions (740K zipped, 1.7MB unzipped):

* For Crosswalk v19:  [Crosswalk RealSense Extensions v19](https://github.com/crosswalk-project/realsense-extensions-crosswalk/releases/download/v19.6.0/realsense_extensions_v19.6.0.zip) 
* For Crosswalk v18: [Crosswalk RealSenes Extensions v18](https://github.com/crosswalk-project/realsense-extensions-crosswalk/releases/download/v18.6.0/realsense_extensions_v18.6.0.zip)

(You can also build them by referring to the [instructions for building extensions](https://github.com/crosswalk-project/realsense-extensions-crosswalk/wiki/Dev-Instructions-for-Windows#building-extensions)).

## Use RealSense Extensions
To add support to your application, do the following:

1. Copy the `realsense_extensions` folder to the root directory of your app.
2. Modify your application's `manifest.json` to include the realsense extensions:

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
The [sample code here](https://github.com/crosswalk-project/realsense-extensions-crosswalk/tree/v19.6.0/sample) can used to see how some of the APIs are used. Other examples are provided in the next section.

Download [Crosswalk for Windows 19.48.498.0](https://download.01.org/crosswalk/releases/crosswalk/windows/canary/19.48.498.0/crosswalk64-19.48.498.0.zip).

Package your app with the Crosswalk Runtime and RealSense Extensions:

```cmdline
> crosswalk-pkg -p windows -c <path to crosswalk zip file> myapp
```

This creates two files:

* `com.example.myapp-0.1.0.0.msi`: the MSI installer without Intel RealSense SDK (RSSDK) runtime installer.
* `com.example.myapp_with_rssdk_runtime_0.1.0.0.exe`: the WiX bootstrapper installer with the RSSDK runtime web installer. It launches the RSSDK runtime web installer to install dependencies during the application installation.

## Examples

The following are examples from the API documentation.

### Depth Enabled Photography

* [Taking a Depth Photo](http://crosswalk-project.github.io/realsense-extensions-crosswalk/spec/depth-enabled-photography.html#taking-a-depth-photo)
* [Load a Depth Photo from File System](http://crosswalk-project.github.io/realsense-extensions-crosswalk/spec/depth-enabled-photography.html#load-a-depth-photo-from-file-system)
* [Load a Depth Photo from Network](http://crosswalk-project.github.io/realsense-extensions-crosswalk/spec/depth-enabled-photography.html#load-a-depth-photo-from-network)
* [Refocus a Depth Photo](http://crosswalk-project.github.io/realsense-extensions-crosswalk/spec/depth-enabled-photography.html#refocus-a-depth-photo)
* [Access Photo Information](http://crosswalk-project.github.io/realsense-extensions-crosswalk/spec/depth-enabled-photography.html#access-photo-information)
* [Use PhotoUtils](http://crosswalk-project.github.io/realsense-extensions-crosswalk/spec/depth-enabled-photography.html#use-photoutils)
* [Measure Within a Depth Photo](http://crosswalk-project.github.io/realsense-extensions-crosswalk/spec/depth-enabled-photography.html#measure-within-a-depth-photo)
* [Apply Motion Effect](http://crosswalk-project.github.io/realsense-extensions-crosswalk/spec/depth-enabled-photography.html#apply-motion-effect)
* [Photo Segmentation ](http://crosswalk-project.github.io/realsense-extensions-crosswalk/spec/depth-enabled-photography.html#photo-segmentation)

### Scene Perception

* [Configure and Control Scene Perception(SP) module](http://crosswalk-project.github.io/realsense-extensions-crosswalk/spec/scene-perception.html#configure-and-control-scene-perception-sp-module)
* [Handle events of SP module ](http://crosswalk-project.github.io/realsense-extensions-crosswalk/spec/scene-perception.html#handle-events-of-sp-module)

### Face Recognition

* [Start/Stop face module](http://crosswalk-project.github.io/realsense-extensions-crosswalk/spec/face.html#start-stop-face-module)
* [Face module configuration](http://crosswalk-project.github.io/realsense-extensions-crosswalk/spec/face.html#face-module-configuration.x)
