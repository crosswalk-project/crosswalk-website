# Run on Android

Building a Crosswalk Android application with extensions is almost identical to building any other Crosswalk Android application, and uses the same `make_apk.py` script (see the [*Run on Android* documentation](/documentation/getting_started/run_on_android)). The main difference is that an additional `--extensions` option is required, to instruct the script where to find extension directories.

## Create the Android packages

To make Android packages for your project, do the following:

1.  Build the extension, as described [previously](/documentation/android_extensions/write_an_extension/build_the_extension). This will create the `xwalk-echo-extension/` directory with the extension files inside.

2.  After the build, the extension project directory (`xwalk-echo-extension-src/`) will contain a `lib/crosswalk-${XWALK-STABLE-ANDROID-X86}` directory, which is an unpacked Crosswalk Android distribution. You can use the scripts in here to build the Android packages.

    Ensure you have installed the Android SDK correctly ([Windows](/documentation/getting_started/windows_host_setup/Install-the-Android-SDK), [Linux](/documentation/getting_started/linux_host_setup/Install-the-Android-SDK)). Then use these commands to create the packages:

        # set an environment variable to the location of the project
        $ PROJECT_DIR=~/<my projects directory>/xwalk-echo-project

        # enter the unpacked Crosswalk Android directory
        $ cd $PROJECT_DIR/xwalk-echo-extension-src/lib/crosswalk-${XWALK-STABLE-ANDROID-X86}

        # invoke the package builder
        $ python make_apk.py --package=org.crosswalkproject.example \
            --enable-remote-debugging --fullscreen \
            --manifest=$PROJECT_DIR/xwalk-echo-app/manifest.json \
            --extensions=$PROJECT_DIR/xwalk-echo-extension-src/xwalk-echo-extension/

    The generated packages are in the `~/<my projects directory>/xwalk-echo-project/xwalk-echo-extension-src/lib/crosswalk-${XWALK-STABLE-ANDROID-X86}` directory, and called `xwalk_echo_app_x86.apk` and `xwalk_echo_app_arm.apk`.

    Note that if you are using multiple extension directories, you still have a single `--extensions` option but supply the paths as a comma-delimited string, e.g.

        --extensions=myextension1,myextension2

## Install a package on a target

Choose the correct package for your Android target's architecture and install it with a command like:

    $ adb install -r ~/<my projects directory>/xwalk-echo-project/xwalk-echo-extension-src/lib/crosswalk-${XWALK-STABLE-ANDROID-X86}/xwalk_echo_app_x86.apk

This command installs the x86 package. For more details about installing Crosswalk packages on Android, see [this page](/documentation/getting_started/run_on_android).

The icon for the application should now be in the application menu on the Android target. Tap the icon to start the application and you should see something like this:

![Crosswalk Android application with extensions on x86 ZTE Geek](assets/android-extensions-x86.png)

(The screenshot above shows the application running on an x86 ZTE Geek.)

The application should display the text "You said: Hello world", which demonstrates that the Java and JavaScript parts of the Crosswalk extension are communicating correctly.
