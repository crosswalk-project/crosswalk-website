# Tizen APIs Sample

<img class='sample-thumb' src='assets/sampapp-icon-api.png'>
A sample application leveraging a Crosswalk extension which implements 
some of the Tizen APIs. This sample demonstrates usage of the 
tizen.systeminfo API.

To use this example, you need to run the application on a Tizen device 
or in the [Tizen 
emulator](https://developer.tizen.org/help/index.jsp?topic=%2Forg.tizen.gettingstarted%2Fhtml%2Fdev_env%2Femulator.htm).

Make sure the tizen-extension-crosswalk RPM is installed on the Target 
device, as described on [Installing Crosswalk on 
Tizen](#documentation/getting_started/installing_crosswalk/tizen).

The following steps use the helper script **tizen-extension-crosswalk** 
to load the Tizen extension API into Crosswalk during load time.
```sh
sdb push samples/tizen_apis /home/developer/tizen_apis
sdb shell "tizen-extensions-crosswalk /home/developer/tizen_apis/index.html"
```
