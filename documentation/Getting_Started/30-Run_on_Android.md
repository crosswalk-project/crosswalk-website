# Running a Crosswalk app on Android

The Crosswalk Android download contains a Python script which can be used to make a self-contained package (`.apk` file) from an HTML5 application. See the host set up instructions ([Windows](#documentation/getting_started/Windows_host_setup/Download-the-Crosswalk-Android-app-template) / [Linux](#documentation/getting_started/Linux_host_setup/Download-the-Crosswalk-Android-app-template)) for details.

Once you have downloaded and unpacked Crosswalk Android, create an `apk` file for your application as follows:

1.  Go to the unpacked Crosswalk Android directory:

        > cd ${XWALK-STABLE-ANDROID-X86}

2.  Run the `make_apk.py` script with Python as follows:

        > python make_apk.py --manifest=xwalk-simple/manifest.json

    This will package the application defined in the specified `manifest.json` file and produce two apk files from it, one for x86 architecture and one for ARM. The apk files will end up in the directory where you ran the script. Each file is given the name set in the manifest, with any filesystem-sensitive characters removed and an architecture identifier ("x86" or "arm") appended. For our example, the output files are `simple_x86.apk` and `simple_arm.apk`.

3.  Install the application on the target.

    If installing on an x86 device:

        > adb install -r simple_x86.apk

    If installing on an ARM device:

        > adb install -r simple_arm.apk

    The `-r` flag stands for "reinstall". It is not required for the first installation, but useful for subsequent reinstalls of the same package.

If the installation is successful, your application should now be on the Android target. Find its icon in the application list and open it. Here it is running on a ZTE Geek phone:

<img src="assets/xwalk-simple-on-android.png">
