# Building a Crosswalk Application
An application package is a compressed archive containing all of your application resources and a manifest file.

There are several sample applications which can be used as a seed for your project. These are listed on the [Samples](#documentation/samples) page. The steps described below can be used to package those applications and deploy them into an Android or Tizen device.

### Manifest File
The following is a minimal example for a manifest file, which should be named manifest.json and reside in your application's top level directory:
```
{
  "name": "Sample App",
  "manifest_version": 1,
  "version": "1.3.5.2",
  "app": {
    "launch":{
      "local_path": "index.html"
    }
  }
}
```
For more details on the manifest file, see the [Crosswalk Manifest](#wiki/Crosswalk-manifest) entry in the [Wiki](#wiki) section.

## The Application Structure
A typical application structure contains the manifest.json file in the root directory. The main entry point to the application is then referenced from that manifest file. In most applications this file is in the root directory as well.
```
/home/foobar/dist/manifest.json
/home/foobar/dist/index.html
/home/foobar/dist/application.js
/home/foobar/dist/assets/images.jpg
```
## Packaging for Android
The Android APK maker is included with the crosswalk-android binaries available in [Downloads](#documentation/downloads).

To package your own web application, unpack the Crosswalk app template tarball that was provided as part of the crosswalk-android ZIP archive.
```sh
tar xzvf xwalk_app_template.tar.gz
cd xwalk_app_template
```
The xwalk_app_template contains utilities and dependencies for packaging an application into an APK file, so it can be installed on an Android device.
Since Crosswalk-3, it introduces a new packaging mode - embedded mode. Such that a version of Crosswalk can be bundled with each web application without depending on XWalkRuntimeLib.

**Note**: For this script to work, you should ensure that the android command from the Android SDK is on your path. It is located in <Android SDK location>/tools/android.

For Crosswalk-3 and later:

Below you will find an example on how to package a web app. We assume that the files for the app are in /home/foobar/dist and the main entry point HTML file is /home/foobar/dist/index.html:
```sh
python make_apk.py --package=com.foo.bar --name=FooBar \
  --app-root=/home/foobar/dist --app-local-path=index.html \
    --mode=[embedded | shared]
```
The apk file is output to the same directory as the make_apk.py script, with a filename <name&gt.apk, where <name> is the name you set with the --name flag.
For embedded mode, the APK 'FooBar_[arm | x86].apk' is written to the same directory. The APKs are architecture dependent, meaning that packaged with an *arm.apk suffix works on ARM devices, and packaged with an *x86.apk suffix works on Intel based devices.
For shared mode, the APK 'FooBar.apk' is output and architecture independent.

For Crosswalk-1 and Crosswalk-2:
Only the shared mode is supported. Below is an example of how to package a local web app. We assume that the files for the app are in /home/foobar/dist and the main entry point HTML file is /home/foobar/dist/index.html:
```sh
python make_apk.py --package=com.foo.bar --name=FooBar \
  --app-root=/home/foobar/dist --app-local-path=index.html
```
Same as above shared mode, the APK 'FooBar.apk' is output and architecture independent.

For information on installing and running the application on Android,
see
 [Running on 
Android](#documentation/getting_started/running_an_application/running-on-android).

## Packaging for Tizen
There is currently no application packager for Tizen. To run your 
application in the Tizen environment, you can launch xwalk manually, 
directing it to load your application via the command line. See the 
steps in
 [Running on 
Tizen](#documentation/getting_started/running_an_application/running-on-tizen).
