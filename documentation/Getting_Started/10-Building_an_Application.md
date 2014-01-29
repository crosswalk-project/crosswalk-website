# Building a Crosswalk Application
An application package is a compressed archive containing all of your application resources and a manifest file.

There are several sample applications which can be used as a seed for your project. These are listed on the [Samples](#documentation/samples) page. The steps described below can be used to package those applications and deploy them into an Android or Tizen device.

### Manifest File
The following is a minimal example for a manifest file, which should be named manifest.json and reside in your application's top level directory:

```
{
  "name": "Sample App",
  "version": "1.3.5.2",
  "app": {
    "launch":{
      "local_path": "index.html"
    }
  },

  "icons": {
    "128": "icon.png"
  }
}
```

Note that the icons field is currently **required** if you intend to package your application for Android using the make_apk.py script. If you are only deploying to Tizen, it is optional.

At a minimum, the `icons` property should reference a 128 pixel square graphic to use as the icon for the application.

For more details on the manifest file, see the [Crosswalk Manifest](#wiki/Crosswalk-manifest) entry in the [Wiki](#wiki) section.

**Note**: If you package application for Android with Crosswalk-3, please add "icons" key in the manifest file:
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
  "icons": {
    "128": "icon128.png"
  }
}
```

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

The xwalk_app_template supports three kinds of web application source:
* **[Crosswalk Manifest](#wiki/Crosswalk-manifest)**.
* **[XPK package](#wiki/Crosswalk-package-management)**.
* **Command line options**. For example, '--app-url' for website, '--app-root' and '--app-local-path' for local web application.

**Note**: The manifest source and XPK source are preferred.

### Packaging from manifest source
This feature is supported for Crosswalk-3 and later.
Below is an example of how to package a web app. We assume that the files for the app are in /home/foobar/dist and the manifest file is /home/foobar/dist/manifest.json:

Both shared and embedded modes are supported.
```sh
python make_apk.py --manifest=/home/foobar/dist/manifest.json
  --mode=[embedded | shared]
```
For embedded mode, the APK 'FooBar_[arm | x86].apk' is written to the directory where you run the command. The APKs are architecture dependent, meaning that an APK with an *arm.apk suffix works on ARM devices, and an APK with an *x86.apk suffix works on x86 devices.
For shared mode, the APK 'FooBar.apk' is generated. This APK will work on both ARM and x86 devices (providing the shared runtime library is also installed).

### Packaging from XPK source
This feature is supported for Crosswalk-3 and later.
Below is an example of how to package a web app. We assume that the files for the app are archived in FooBar.xpk, which is located at /home/foobar/FooBar.xpk:
```sh
python make_apk.py --xpk=/home/foobar/FooBar.xpk \
  --mode=[embedded | shared]
```
For embedded mode, the APK 'FooBar_[arm | x86].apk' is generated. For shared mode, the APK 'FooBar.apk' is generated.

**Note**: The packaging tool depends on the third party library [pycrypto](https://pypi.python.org/pypi/pycrypto) for this feature. Please install it first.

### Packaging from command line options
For Crosswalk-3 and later:

Below you will find an example of how to package a local web app. We assume that the files for the app are in /home/foobar/dist and the main entry point HTML file is /home/foobar/dist/index.html:
```sh
python make_apk.py --package=com.foo.bar --name=FooBar \
  --app-root=/home/foobar/dist --app-local-path=index.html \
    --mode=[embedded | shared]
```
The apk file is output to the same directory as the make_apk.py script, with a filename <name&gt.apk, where <name> is the name you set with the --name flag.
For embedded mode, the APK 'FooBar_[arm | x86].apk' is generated. For shared mode, the APK 'FooBar.apk' is generated.


For Crosswalk-1 and Crosswalk-2:

Only shared mode is supported. Below is an example of how to package a local web app. We assume that the files for the app are in /home/foobar/dist and the main entry point HTML file is /home/foobar/dist/index.html:
```sh
python make_apk.py --package=com.foo.bar --name=FooBar \
  --app-root=/home/foobar/dist --app-local-path=index.html
```
The architecture-independent APK 'FooBar.apk' is generated.

For information on installing and running the application on Android,
see
 [Running on
Android](#documentation/getting_started/running_an_application/running-on-android).

## Packaging for Tizen
To run Crosswalk packages on Tizen, web applications should be packaged using the XPK
package format. To package your own web application, you should save the
[xpk_generator](#wiki/crosswalk-package-management/xpk-package-generator-bash-shell-version)
script to a local file, then call it like this:
```sh
xpk_generator /home/foobar/dist myapp.pem
```
Then, an XPK package named ```dist.xpk``` should be created under the ```/home/foobar```
directory.

Note that the 'myapp.pem' (or whatever file name you chose) file is the XPK
package identity. It's generated the first time you created the web app XPK
package, and should use the same 'myapp.pem' file when packaging this web
app, otherwise the XPK package is treated as a new app.

To run your application in the Tizen environment, you can either launch xwalk manually,
directing it to load your application via the command line; or launch an
installed XPK package from the Tizen Home Screen. See the steps in
[Running on
Tizen](#documentation/getting_started/running_an_application/running-on-tizen).
