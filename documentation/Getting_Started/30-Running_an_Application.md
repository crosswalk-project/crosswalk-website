# Running a Crosswalk Application

## Running on Android
Follow the steps for [Packaging on 
Android](#documentation/getting_started/building_an_application/packaging-for-android). 
Once you have your APK, install it to your target device:
```sh
adb install -r ABC.apk
```
The application will now appear in your application list and can be 
launched by clicking on its icon.

## Running on Tizen
Launching Crosswalk applications on Tizen is currently a 
developer manual process:

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
sdb shell "xwalk --remote-debugging-port=9222 --use-gl=osmesa /home/developer/hello_world/index.html"
```

On the host, you can point your browser to http://localhost:9222/ and debug your application. As you debug and develop your application, you only need to run the '''sdb push''' command:

```sh
sdb push samples/hello_world /home/developer/hello_world
```

and then refresh the debugger in your browser via CTRL-R.

**TIP** &mdash; If you are running Tizen via the emulator, you can enable [File Sharing](https://developer.tizen.org/help/index.jsp?topic=%2Forg.tizen.gettingstarted%2Fhtml%2Fdev_env%2Femulator_file_sharing.htm) which can allow you to access your application files directly in the Tizen environment. This removes the ```sdb push``` step.

