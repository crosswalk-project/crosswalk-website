# Run on Tizen virtual machine

You have now built the extension and application, and are at the point where you can test on the Tizen IVI virtual machine.

1.  Start the Tizen IVI virtual machine (*target*), if it's not already running.

2.  Use `scp` to push the rpms to the target. By default, the rpms you generate with `gbs` will be in `~/GBS-ROOT/local/repos/tizen3.0/i586/RPMS/`; so the commands you need should look like this:

        > scp ~/GBS-ROOT/local/repos/tizen3.0/i586/RPMS/echo-extension-0.1-1.i686.rpm \
            root@<IP address>:/root/
        > scp ~/GBS-ROOT/local/repos/tizen3.0/i586/RPMS/simple-extension-app-0.1-1.i686.rpm \
            root@<IP address>:/root/

    Replace `<IP address>` with the IP address of the virtual machine.

3.  Install the rpms on the target:

        # on the host, login to the target
        > ssh root@<IP address>

        # on the target
        root:~> rpm -ih echo-extension-0.1-1.i686.rpm
        ################################# [100%]
        Updating / installing...
        ################################# [100%]

        root:~> rpm -ih simple-extension-app-0.1-1.i686.rpm
        ################################# [100%]
        Updating / installing...
        ################################# [100%]
        Application installed/updated with path '/installed1/nejhjijinegiakjdkkdnbepefnmpdgcp'

    The rpms should install without errors. Note that when installing simple-extension-app, you get an extra line of output from the postinstall script, where the xpk file is installed via `xwalkctl` as the "app" user.

4.  Now get a shell on the target as the "app" user. This user has the correct configuration and permissions to run applications.

    The best way to get an app user shell is to click the console icon in the top-left of the virtual machine's display. If you try to login remotely to get a shell, some of the required environment variables may not be set correctly and Crosswalk may not run.

5.  Finally, run the application from the app user's shell (on the target).

    You need the application's ID to do this. During installation of the simple-extension-app rpm, you will have been notified of this after the app was installed. If you can't remember it, or missed it, you can use the `xwalkctl` command to list applications and their IDs:

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
