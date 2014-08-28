# Run on Tizen virtual machine

You have now built the extension and application, and are at the point where you can test on the Tizen IVI virtual machine.

1.  Start the Tizen IVI virtual machine (*target*), if it's not already running.

2.  Use `scp` to push the extension rpm to the target. By default, the rpm you generated with `gbs` will be in `~/GBS-ROOT/local/repos/tizen3.0/i586/RPMS/`; so the command you need should look like this:

        > scp ~/GBS-ROOT/local/repos/tizen3.0/i586/RPMS/echo-extension-0.1-1.i686.rpm \
            root@<IP address>:/root/

    Replace `<IP address>` with the IP address of the virtual machine. The password is **tizen**.

3.  Install the echo extension rpm on the target. To do this, first get a root shell on the target:

        # on the host, login to the target
        > ssh root@<IP address>

    Then, in the shell on the target:

        root:~> rpm -ih echo-extension-0.1-1.i686.rpm
        ################################# [100%]
        Updating / installing...
        ################################# [100%]

    The rpm should install without errors.

4.  Use scp to push the generated `app.xpk` file to the Tizen target:

        > scp app.xpk root@<ip address>:/home/app/

5.  Install the `.xpk` file on the target using `xwalkctl`. You should do this using a shell on the target as the "app" user. This user has the correct configuration and permissions to run applications.

    The best way to get an app user shell is to click the console icon in the top-left of the virtual machine's display. If you try to login remotely to get an app user shell, some required environment variables may not be set and `xwalkctl` may not run correctly.

    To install the `.xpk` file, run this command from the app user shell:

        app:~> xwalkctl --install /home/app/app.xpk

    Make a note of the application ID (the last part of the `/installed/*` path displayed).

6.  Finally, run the application from the app user's shell (on the target).

    You need the application's ID to do this. During installation of the simple-extension-app rpm, you will have been notified of this after the app was installed (see above). If you can't remember it, or missed it, you can use the `xwalkctl` command to list applications and their IDs:

        app:~> xwalkctl
        Application ID                       Application Name
        -----------------------------------------------------
        nejhjijinegiakjdkkdnbepefnmpdgcp	simple_extension_app
        -----------------------------------------------------

    Once you know the ID, you can launch the application with:

        app:~> xwalk-launcher <app ID>

    You should see this in the emulator:

    ![Crosswalk application with extensions on Tizen IVI](assets/tizen-ivi3-emulator-echo-extension.png)

    If you don't, check for errors and warnings coming from the command. In particular, the output from the command should end with:

        Instance 1 created!

    This message is coming from the `instance_created` callback of your extension. If it's not there, the extension is not being initialized correctly.
