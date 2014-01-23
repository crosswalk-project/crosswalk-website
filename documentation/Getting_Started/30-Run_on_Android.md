# Running a Crosswalk app on Android

The Crosswalk Android download contains a Python script which can be used to make a self-contained package (`.apk` file) from an HTML5 application. See [???]() for instructions on how to download Crosswalk Android.

Once you have downloaded and unpacked Crosswalk Android, create an `apk` file for your application as follows:

1.  Go to the `xwalk_app_template` directory inside the unpacked Crosswalk Android:

        $ cd ${XWALK-STABLE-ANDROID-X86}/xwalk_app_template

2.  Run the `make_apk.py` script with Python as follows:

        $ python make_apk.py --manifest=~/xwalk-simple/manifest.json

    This will package the application defined in the specified `manifest.json` file and produce an apk file from it, in the directory where you ran the script. The file will have the name as set in the manifest, with any filesystem-sensitive characters removed and an architecture identifier ("x86" or "arm") appended. For our example, the output file is `simple_x86.apk`.

3.  Install the application on the target:

        $ adb install -r simple_x86.apk

    The `-r` flag stands for "reinstall". It is not required for the first installation, but useful for subsequent reinstalls of the same package.

If the installation is successful, your application should now be on the Android target. Find its icon in the application list and open it. Here it is running on a ZTE Geek phone:

<img src="assets/xwalk-simple-on-android.png">
