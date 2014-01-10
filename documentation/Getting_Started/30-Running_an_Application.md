# Running a Crosswalk Application

## Running on Android
Follow the steps for [Packaging on 
Android](#documentation/getting_started/building_an_application/packaging-for-android). 
Once you have your APK, install it to your target device:
```sh
adb install -r [FooBar.apk | FooBar_arm.apk | FooBar_x86.apk]
```
The application will now appear in your application list and can be 
launched by clicking on its icon.

## Running on Tizen
### Launching unpacked Crosswalk applications on Tizen from command line

To access the files, xwalk needs to be launched as root
```sh
sdb root on
```

Set up port forwarding from the host port 9222 to the emulator port 9222
```sh
sdb forward tcp:9222 tcp:9222
```

Sync your application contents to the device
```sh
sdb push samples/hello_world /home/developer/hello_world
```

Launch Crosswalk. NOTE: This command passes the following parameters:
```
  --use-gl=osmesa                Enable WebGL via Mesa (if running in 
                                 the emulator)
  --remote-debugging-port=9222   Listen on port 9222 for web debugging
```

The last parameter is the full path to the HTML file to load. 
Eventually you will only need to point it to the base directory and 
Crosswalk will load the manifest.json file it finds there.
```sh
sdb shell "xwalk --remote-debugging-port=9222 --use-gl=osmesa /home/developer/hello_world"
```

On the host, you can point your browser to http://localhost:9222/ and debug your application. As you debug and develop your application, you only need to run the '''sdb push''' command:

```sh
sdb push samples/hello_world /home/developer/hello_world
```

and then refresh the debugger in your browser via CTRL-R.

**TIP** &mdash; If you are running Tizen via the emulator, you can enable [File Sharing](https://developer.tizen.org/help/index.jsp?topic=%2Forg.tizen.gettingstarted%2Fhtml%2Fdev_env%2Femulator_file_sharing.htm) which can allow you to access your application files directly in Tizen environment. This removes the ```sdb push``` step.

### Launching an XPK package on Tizen
Follow the steps for [Packaging on
Tizen](#documentation/getting_started/building_an_application/packaging-for-tizen). Once
you have the XPK package, you can install and launch it on a Tizen device by
following the steps below.

* The XPK should be installed as root:
```sh
sdb root on
```

* Sync your XPK package to the device:
```sh
sdb push FooBar.xpk /tmp/
```

* Install the package:
```sh
sdb shell "xwalk --install /tmp/FooBar.xpk"
```

The new application icon should now be visible on the device's home screen.

You can refer to [XPK
package management](#wiki/Crosswalk-package-management/xpk-package-management)
for more details about how to manage an XPK package in Crosswalk.
