# Remote debugging

Web applications running under Crosswalk can be debugged remotely using the [Chrome dev tools](https://developer.chrome.com/devtools/index). 

Note that to debug a Crosswalk application with Chrome, you must ensure that your Chrome version is appropriate for the version of Crosswalk you are using. See [this wiki page](https://github.com/crosswalk-project/crosswalk-website/wiki/Remote-debugging-on-android), which shows the mapping between Crosswalk and Chrome versions for debugging.

To debug Crosswalk applications on Tizen, the Crosswalk service on the target must be configured to provide a debugging endpoint. You can then access this endpoint from a Chrome browser on the host.

Before you can follow these instructions, you will first need a Tizen target (with Crosswalk installed); and an application installed on that target via `xwalkctl`. See [Getting started](/documentation/getting_started.html) for details.

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

1.  Launch a Crosswalk application on the Tizen target using a console. See [these instructions](/documentation/getting_started/run_on_tizen.html#Run-the-application).

2.  Back on the host, open a Chrome browser and open the address `http://&lt;Tizen target IP&gt;:9222`. For example, for the IP address 192.168.0.19, the URL to use would be "http://192.168.0.19:9222".

    A list of all the pages available for debugging should now be displayed in the Chrome browser window:

    ![Crosswalk on Tizen IVI: remote debugging](/assets/crosswalk-tizen-remote-debug.png)

    Click on the link for the application you want to debug.

## Further information

For information about using the Chrome dev tools for debugging, see [this page](https://developer.chrome.com/devtools/index).
