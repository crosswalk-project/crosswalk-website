# Run on Tizen emulator

You have now built the extension and application, and are at the point where you can test on the emulator.

1.  Use the emulator manager to start the Tizen IVI target (if it's not already running).

2.  Now you can use the `sdb` tool to push the application to the running Tizen IVI target, get a shell on it, and start the application

    If your host machine is Linux, open a bash shell. On Windows, you need a cmd shell instead (as `sdb push` doesn't work in a bash shell on Windows).

3.  In the shell, turn on sdb root mode:

        sdb root on

4.  Go to the root directory of the **simple** project on the host machine.

    Make sure you've run the build for the project, so the `build` directory is populated (see [the previous section](#documentation/tizen_ivi_extensions/build_an_application)).

5.  Push the build directory of the project from the host to the target:

        sdb push build /home/app/simple

    This will create a `/home/app/simple` directory on the target.

6.  Now get a shell on the target and switch to the "app" user. This user has the correct configuration and permissions to run applications:

        # in the host shell
        sdb shell

        # now we're on the target as root
        # su - app

        # now we've switched to the app user
        app:~>

7.  Finally, run the application from the app user's shell (on the target):

        xwalk --fullscreen \
          --external-extensions-path=/home/app/simple/extension/ \
          /home/app/simple/app/

    You should see this in the emulator:

    ![Crosswalk application with extensions on Tizen IVI](assets/tizen-ivi3-emulator-echo-extension.png)

    If you don't, check for errors and warnings coming from the `xwalk` command. In particular, the output from the command should end with:

        Instance 1 created!

    This message is coming from the `instance_created` callback of your extension. If it's not there, the extension is not being initialized correctly.
