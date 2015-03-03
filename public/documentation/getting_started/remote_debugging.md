# Remote debugging

Web applications running under Crosswalk can be debugged remotely using the [Chrome DevTools](https://developer.chrome.com/devtools/index). 

Depending on the platform, a connection must be established between the host (the machine where you are doing the development) and the target (the machine running the application under Crosswalk). The type of connection depends on the target's operating system, as explained below.

Note that to debug a Crosswalk application with Chrome, you must ensure that your Chrome version is appropriate for the version of Crosswalk you are using. See [this wiki page](https://github.com/crosswalk-project/crosswalk-website/wiki/Remote-debugging-on-android), which shows the mapping between Crosswalk and Chrome versions for debugging.

## Android

On Android, the connection between the host and target is established using [`adb`](http://developer.android.com/tools/help/adb.html). You will need to ensure that you have installed the Android SDK (instructions for [Windows](/documentation/getting_started/windows_host_setup.html#Installation-for-Crosswalk-Android) and [Linux](/documentation/getting_started/linux_host_setup.html#Installation-for-Crosswalk-Android)) so that this tool is available.

You will also need to set up an Android target and install a packaged Crosswalk application on it. See [these instructions](/documentation/getting_started/android_target_setup.html).

Debugging on Android can be enabled in two ways: [at build time](#Enable-debugging-at-build-time-for-Android) and [at run time](#Enable-debugging-at-run-time-for-Android). Both options are described below.

### <a id="Enable-debugging-at-build-time-for-Android"></a>Enable debugging at build time for Android

Once the [pre-requisites](#Android) have been met, debug your Crosswalk application as follows:

<ol>

<li>
<p>Enable remote debugging of your app. This depends on how you are using Crosswalk:</p>

<ul>

<li>
<p><strong>Generating an Android package using <a href="/documentation/getting_started/run_on_android.html"><code>make_apk.py</code></a></strong></p>

<p>Enable remote debugging by passing a flag to <code>make_apk.py</code> when building the package. For example:</p>

<pre><code>$ python make_apk.py --package=org.crosswalkproject.example \
  --manifest=/home/me/myapp/manifest.json --enable-remote-debugging</code></pre>

</li>

<li>

<p><strong>Embedding Crosswalk in your application</strong> via the <a href="/documentation/embedding_crosswalk.html">embedding API</a></p>

<p>Modify your application's main activity to set the remote debugging preference. For example:</p>

<pre><code>XWalkPreferences.setValue(XWalkPreferences.REMOTE_DEBUGGING, true);</code></pre>

<p>Then build the application package the usual way (e.g. using Ant or ADT).</p>

<p>Full details are given in the <a href="/documentation/embedding_crosswalk.html#Debugging">Crosswalk embedding tutorial</a>.</p>

</li>

<li>

<p><strong>Using <a href="/documentation/cordova/migrate_an_application.html">a Cordova application migrated to Crosswalk</a></strong>

<p>When you build a Cordova application which has been migrated to Crosswalk, remote debugging is enabled by default. You shouldn't have to do anything other than the usual build:</p>

<pre><code>$ cordova build android</code></pre>

<p>Install and run the application as usual, for example:</p>

<pre><code>$ cordova run android</code></pre>

<p>Remote debugging can be disabled by <a href="http://docs.phonegap.com/en/3.3.0/guide_command-line_index.md.html">passing the <code>--release</code> option to the build</a>.</p>
</li>

<li>
<p><strong>Using <a href="/documentation/cordova/develop_an_application.html">an application created using Crosswalk Cordova</a></strong></p>

<p>Remote debugging is enabled by default when you build an application created with Crosswalk Cordova. For example:</p>

<pre><code>$ ./cordova/build</code></pre>

<p>will build an application with remote debugging turned on.</p>

</li>

</ul>

</li>

<li>Install and run the application on the target, using your preferred tool (for example, <a href="/documentation/getting_started/run_on_android.html"><code>adb</code></a>).</li>

<li>
<p>On the host, open a Chrome browser and go to "chrome://inspect" in the address bar. This should show a list of attached devices, with your application listed, for example:</p>

<img src="/assets/crosswalk-debug-in-chrome.png" title="Debugging a Crosswalk application in Chrome" alt="Debugging a Crosswalk application in Chrome">

<p>(The application available for debugging is highlighted with a red box in the image.)</p>

<p>Click on the "inspect" link to open the application for debugging with the Chrome dev tools.</p>

</li>

</ol>

### <a id="Enable-debugging-at-run-time-for-Android"></a>Enable debugging at run time for Android

**Note: The instructions below only work with Crosswalk version 10 and lower.**

*Starting with Crosswalk version 11, the ability to turn on debugging at run time was disabled for security purposes.*

If you built your Android package without the debugging feature, you can still turn on the feature at run time by sending it an [intent](http://developer.android.com/guide/components/intents-filters.html). This works for a Crosswalk application built using any of the packaging methods, i.e.

1.  Packaged with `make_apk.py`.
2.  Using the Crosswalk embedding API.
3.  Migrated from Cordova.
4.  Created using Crosswalk Cordova.

Note that the intent will enable remote debugging feature for *all* Crosswalk applications which are running on the target.

First ensure that the [pre-requisites](#Android) have been met. Then follow these steps to enable remote debugging for Crosswalk applications:

1.  Install and run the application(s) on the target, using your preferred tool (for example, [`adb`](/documentation/getting_started/run_on_android.html)).

2.  From the host, use `adb` to broadcast the remote debugging intent to all Crosswalk applications on the target:

    ```
    $ adb shell am broadcast -a org.xwalk.intent -e remotedebugging true
    ```

3.  On the host, open a Chrome browser and go to "chrome://inspect" in the address bar. This should show a list of attached devices, with your application listed, for example:

    ![Debugging a Crosswalk application in Chrome](/assets/crosswalk-debug-in-chrome.png)

    (The application available for debugging is highlighted with a red box in the image.)

4.  Click on the "inspect" link to open the application for debugging with the Chrome dev tools.

Note that it is also possible to disable remote debugging using the same intent, with:

    $ adb shell am broadcast -a org.xwalk.intent -e remotedebugging false

### Troubleshooting

*   **adb can't connect to the device**

    You may occasionally find that `adb` is unable to connect to the device, and remote debugging won't work. You can try unplugging the USB cable between your host and target (if using a USB connection), then reattaching it, which sometimes fixes the issue; or you could try [running `adb` as root](/documentation/getting_started/android_target_setup.html#Fixing-device-access-issues-on-Linux).

*   **The application doesn't appear in the chrome://inspect page**

    If the application is not visible in the inspection page, use `adb` to check that remote debugging is enabled for the application:

    ```
    host$ adb shell
    shell@android$ cat /proc/net/unix |grep devtools_remote
    00000000: 00000002 00000000 00010000 0001 01 1102698 @org.crosswalkproject.app_devtools_remote
    00000000: 00000002 00000000 00010000 0001 01 1092981 @org.xwalk.core.xwview.shell_devtools_remote
    ```

    If you cannot see any entries ending with `_devtools_remote`, it's likely that remote debugging is not enabled for the application. Follow the steps above to either rebuild the application with remote debugging support, or switch remote debugging on at run time.

## Tizen

To debug Crosswalk applications on Tizen, the Crosswalk service on the target device must be configured to provide a debugging endpoint.  A properly-configured endpoint is accessible via Chrome browser on the host. 

All you need is a Tizen target (with Crosswalk installed) and an application installed on that target via `xwalkctl`. See [Getting started](/documentation/getting_started.html) for details.

Once the pre-requisites are in place, log in and turn on debugging for the Crosswalk service on the Tizen target as follows:

1.  From the host, log on to the Tizen target. For example, if the Tizen target has the IP address 192.168.0.19:

    ```
    $ ssh root@192.168.0.19
    Password:
    Welcome to Tizen
    root:~">"
    ```

    (The default password is **tizen**.)

2.  Remote debugging-mode of the xwalk service with the specified port can be opened by either of following methods (a) or (b); (a) is the recommend method:

    (a)  `xwalkctl -d PORT_NUMBER` `#enable/disable remote-debugging-mode`   

      Usage:
        ```
        xwalkctl -d 9222 #enabled remote debugging at port '9222'
        xwalkctl -d 0    #disable remote debugging.
        ```

    (b) Modify the `xwalk.service` configuration file under `/usr/lib/systemd/user/xwalk.service` on the target and restart xwalk service. 

    Add option `--remote-debugging-port=9222` to the `ExecStart=` line in the Crosswalk service configuration file.  For example, to open the file for editing with VIM:

    ```
    root:~">" vim /usr/lib/systemd/user/xwalk.service
    ```

    Then edit the `ExecStart=` line so it looks like this:

    ```
    ExecStart=/usr/lib/xwalk/xwalk --remote-debugging-port=9222 \
      --external-extensions-path=/usr/lib/tizen-extensions-crosswalk
    ```

    

3. Reboot the Tizen target device



4. Now that the Crosswalk service has been enabled for debugging, debug your application(s) as follows:

    (a) Launch a Crosswalk application on the Tizen target using a console. See [these instructions](/documentation/getting_started/run_on_tizen.html#Run-the-application).

    (b) Back on the host, open a Chrome browser and open the address `http://&lt;Tizen target IP&gt;:9222`. For example, for the IP address 192.168.0.19, the URL to use would be "http://192.168.0.19:9222".

    A list of all the pages available for debugging should now be displayed in the Chrome browser window:

    ![Crosswalk on Tizen IVI: remote debugging](/assets/crosswalk-tizen-remote-debug.png)

    Click on the link for the application you want to debug.

    After remote-debugging-mode of xwalk is opened, remote debugging of an app can be enabled with command:

    `app_launcher -s APP_ID -d`

    Note that only the target application is debugged; other applications running services are kept unaffected.  Navigate to `http://remote_device_ip:port` from your host and attach to any of the discovered Tizen applications for debugging.

    You can also use `sdb forward` to forward socket connections

    For example:  `sdb forward tcp:9222 tcp:9222`  and navigate to `http://localhost:9222`




## Further information


For more information about using Chrome DevTools for debugging, see [this page](https://developer.chrome.com/devtools/index).
