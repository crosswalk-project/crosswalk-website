# Remote debugging

Web applications running under Crosswalk can be debugged remotely using the [Chrome dev tools](https://developer.chrome.com/devtools/index). Depending on the platform, a connection must be established between the host (the machine where you are doing the development) and the target (the machine running the application under Crosswalk). The type of connection depends on the target's operating system, as explained below.

Note that to debug a Crosswalk application with Chrome, you must ensure that your Chrome version is appropriate for the version of Crosswalk you are using. See [this wiki page](#wiki/Remote-debugging-on-android) which shows the mapping between Crosswalk and Chrome versions for debugging.

## Android

On Android, the connection between the host and target is established using [`adb`](http://developer.android.com/tools/help/adb.html). You will need to ensure that you have installed the Android SDK (instructions for [Windows](#documentation/getting_started/windows_host_setup/Installation-for-Crosswalk-Android) and [Linux](#documentation/getting_started/linux_host_setup/Installation-for-Crosswalk-Android)) so that this tool is available.

You will also need to set up an Android target and install a packaged Crosswalk application on it. See [these instructions](#documentation/getting_started/android_target_setup).

Once these pre-requisites have been met, debug your Crosswalk application as follows:

<ol>

<li>
<p>Enable remote debugging of your app. This depends on how you are using Crosswalk:</p>

<ul>

<li>
<p><strong>Generating an Android package using <a href="#documentation/getting_started/run_on_android"><code>make_apk.py</code></a></strong></p>

<p>Enable remote debugging by passing a flag to <code>make_apk.py</code> when building the package. For example:</p>

<pre>
$ python make_apk.py --manifest=/home/me/myapp/manifest.json \
  --enable-remote-debugging
</pre>

</li>

<li>

<p><strong>Embedding Crosswalk in your application</strong> via the <a href="#documentation/embedding_crosswalk">embedding API</a></p>

<p>Modify your application's main activity to set the remote debugging preference. For example:</p>

<pre>
XWalkPreferences.setValue(XWalkPreferences.REMOTE_DEBUGGING, true);
</pre>

<p>Then build the application package the usual way (e.g. using Ant or ADT).</p>

<p>Full details are given in the <a href="#documentation/embedding_crosswalk/Debugging">Crosswalk embedding tutorial</a>.</p>

</li>

<li>

<p><strong>Using <a href="#documentation/cordova/migrate_an_application">a Cordova application migrated to Crosswalk</a></strong>

<p>When you build a Cordova application which has been migrated to Crosswalk, remote debugging is enabled by default. You shouldn't have to do anything other than the usual build:</p>

<pre>
cordova build android
</pre>

<p>Install and run the application as usual, for example:</p>

<pre>
cordova run android
</pre>

<p>Remote debugging can be disabled by <a href="http://docs.phonegap.com/en/3.3.0/guide_command-line_index.md.html">passing the <code>--release</code> option to the build</a>.</p>
</li>

<li>
<p><strong>Using <a href="#documentation/cordova/develop_an_application">an application created using Crosswalk Cordova</a></strong></p>

<p>Remote debugging is enabled by default when you build an application created with Crosswalk Cordova. For example:</p>

<pre>
./cordova/build
</pre>

<p>will build an application with remote debugging turned on.</p>

</li>

</ul>

</li>

<li>Install and run the application on the target, using your preferred tool (for example, <a href="#documentation/getting_started/run_on_android"><code>adb</code></a>).</li>

<li>
<p>On the host, open a Chrome browser and go to "chrome://inspect" in the address bar. This should show a list of attached devices, with your application listed, for example:</p>

<img src="assets/crosswalk-debug-in-chrome.png" title="Debugging a Crosswalk application in Chrome" alt="Debugging a Crosswalk application in Chrome">

<p>(The application available for debugging is highlighted with a red box in the image.) Click on the "inspect" link to open the application for debugging with the Chrome dev tools.</p>

<p>For information about using the Chrome dev tools for debugging, see <a href="https://developer.chrome.com/devtools/index">this page</a>.</p>
</li>

</ol>

### Troubleshooting

You may occasionally find that `adb` is unable to connect to the device, and remote debugging won't work. You can try unplugging the USB cable between your host and target (if using a USB connection), then reattaching it, which sometimes fixes the issue; or you could try [running `adb` as root](#documentation/getting_started/android_target_setup/Fixing-device-access-issues-on-Linux).

## Tizen

To debug Crosswalk applications on Tizen, the Crosswalk service on the target must be configured to provide a debugging endpoint. You can then access this endpoint from a Chrome browser on the host.

Before you can follow these instructions, you will first need a Tizen target (with Crosswalk installed); and an application installed on that target via `xwalkctl`. See [Getting started](#documentation/getting_started) for details.

Once you have the pre-requisites in place, turn on debugging for the Crosswalk service on the Tizen target as follows:

1.  From the host, log on to the Tizen target. For example, if the Tizen target has the IP address 192.168.0.19:

    ```
    $ ssh root@192.168.0.19
    Password:
    Welcome to Tizen
    root:~>
    ```

    (The default password is **tizen**.)

2.  On the target, add a `--remote-debugging-port=9222` option to the `ExecStart=` line in the Crosswalk service configuration file, `/usr/lib/systemd/user/xwalk.service`. For example, open the file for editing:

    ```
    root:~> vim /usr/lib/systemd/user/xwalk.service
    ```

    Then edit the `ExecStart=` line so it looks like this:

    ```
    ExecStart=/usr/lib/xwalk/xwalk --remote-debugging-port=9222 \
      --external-extensions-path=/usr/lib/tizen-extensions-crosswalk
    ```

3.  Reboot the Tizen target device.

Once the Crosswalk service is enabled for debugging, debug your applications as follows:

1.  Launch a Crosswalk application on the Tizen target using a console. See [these instructions](#documentation/getting_started/run_on_tizen/Run-the-application).

2.  Back on the host, open a Chrome browser and open the address "http://&lt;Tizen target IP&gt;:9222". For example, for the IP address 192.168.0.19, the URL to use would be "http://192.168.0.19:9222".

    A list of all the pages available for debugging should now be displayed in the Chrome browser window:

    ![Crosswalk on Tizen IVI: remote debugging](assets/crosswalk-tizen-remote-debug.png)

    Click on the link for the application you want to debug.

    For information about using the Chrome dev tools for debugging, see [this page](https://developer.chrome.com/devtools/index).
