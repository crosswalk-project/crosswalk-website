<style>
.simple-table {
    table-layout:fixed;
    padding: 0px;
}

.simple-table td {
    height: 5px !important;
}

</style>

# Remote debugging

Web applications running under Crosswalk can be debugged remotely using the [Chrome dev tools](https://developer.chrome.com/devtools/index). 

For best resuls, use the version of Chrome that matches (or is newer) the version used in the Crosswalk release you are using. See the mapping in the dropdown below.

<select>
  <option>Crosswalk 18, use Chrome >= 47</option>
  <option>Crosswalk 17, use Chrome >= 46</option>
  <option>Crosswalk 16, use Chrome >= 45</option>
  <option>Crosswalk 15, use Chrome >= 44</option>
  <option>Crosswalk 14, use Chrome >= 43</option>
  <option>Crosswalk 13, use Chrome >= 42</option>
  <option>Crosswalk 12, use Chrome >= 41</option>
  <option>...</option>
  <option>Crosswalk &nbsp;x, use Chrome >= (x+29)</option>
</select>
On Android, the connection between the host and target is established using [`adb`](http://developer.android.com/tools/help/adb.html) which is part of the [Android SDK](/documentation/android/system_setup.html#Android). You will also need to set up an Android target and install a packaged Crosswalk application on it. See [these instructions](/documentation/android/android_target_setup.html).

Debugging on Android must be enabled during build time. 

## <a class="doc-anchor" id="Enable-debugging"></a>Enable debugging

* **crosswalk-app build**

  `crosswalk-app build` accepts "release" and "debug" as options.  Debug is the default, so you don't need to add anything to create a debug version, although using "debug" may remind you that you should change the build before releasing the app.

        > crosswalk-app build [release|debug] [<dir>] 

* **cordova build**

  `cordova build` also defaults to creating a debug version. It accepts `--debug` and `--release` parameters.
  
        > cordova build android [--release|--debug]

* **Embedding Crosswalk in your application**

  If you are embedding Crosswalk in your application using the Crosswalk embedding API (for example in a native application using the Crosswalk webview), you can enable debugging in your application code using the [embedding API](/documentation/android/embedding_crosswalk.html)

  * Modify your application's main activity to set the remote debugging preference. For example:

        XWalkPreferences.setValue(XWalkPreferences.REMOTE_DEBUGGING, true);

  * Build the application package the usual way (e.g. using Ant or ADT).

  Full details are given in the [Crosswalk embedding tutorial](/documentation/android/embedding_crosswalk.html#Debugging)


## Install and debug

* Install your application on the target:
      > adb install com.abc.myapp

* Run the application on the target by clicking on the application icon. [More details](/documentation/android/run_on_android.html)

* On the host, open a Chrome browser and go to "chrome://inspect" in the address bar. This should show a list of attached devices, with your application listed, for example:

  <img src="/assets/crosswalk-debug-in-chrome.png" title="Debugging a Crosswalk application in Chrome" style="display:block;margin:0 auto;">

* Click on the "inspect" link to open the application for debugging with the Chrome dev tools.

  <img src="/assets/crosswalk-debug-in-chrome2.png" style="display:block;margin:0 auto;">
  
## Troubleshooting

* **adb can't connect to the device**

  You may occasionally find that `adb` is unable to connect to the device, and remote debugging won't work. You can try unplugging the USB cable between your host and target (if using a USB connection), then reattaching it, which sometimes fixes the issue; or you could try [running `adb` as root](/documentation/android/android_target_setup.html#Fixing-device-access-issues-on-Linux).

* **The application doesn't appear in the chrome://inspect page**

  If the application is not visible in the inspection page, use `adb` to check that remote debugging is enabled for the application:

    ```
    host$ adb shell
    shell@android$ cat /proc/net/unix |grep devtools_remote
    00000000: 00000002 00000000 00010000 0001 01 1102698 @org.crosswalkproject.app_devtools_remote
    00000000: 00000002 00000000 00010000 0001 01 1092981 @org.xwalk.core.xwview.shell_devtools_remote
    ```

  If you cannot see any entries ending with `_devtools_remote`, it's likely that remote debugging is not enabled for the application. Follow the steps above to either rebuild the application with remote debugging support, or switch remote debugging on at run time.

## Further information

For information about using the Chrome dev tools for debugging, see [this page](https://developer.chrome.com/devtools/index).
