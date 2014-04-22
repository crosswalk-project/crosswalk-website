# Running a Crosswalk app on Android

The Crosswalk Android download contains a Python script which can be used to make a self-contained package (`.apk` file) from an HTML5 application. See the host set up instructions ([Windows](#documentation/getting_started/Windows_host_setup/Download-the-Crosswalk-Android-app-template) / [Linux](#documentation/getting_started/Linux_host_setup/Download-the-Crosswalk-Android-app-template)) for details.

Once you have downloaded and unpacked Crosswalk Android, create the `apk` packages for your application as follows:

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

## Shared vs. embedded mode

Above, we packaged the application using the default *embedded* mode (the Crosswalk runtime is bundled with the application). This creates two apk files, one for Intel architecture devices, and a second for ARM devices.

However, it is also possible to package an application in *shared* mode. In this mode, the apk is architecture-independent; but a separate Crosswalk runtime also has to be installed on the target to run the application.

Each of these modes has its own pros and cons:

*   *Embedded*

    In embedded mode packaging, each web app is bundled with the full Crosswalk runtime. Since the Crosswalk runtime needs an architecture-dependent native library, two Android apks need to be generated for embedded mode: one for targets with Intel architecture (x86) and another for ARM targets. The `make_apk.py` script used in this tutorial generates a package for each architecture by default, as explained above.

    The advantage is that you can keep a tight dependency between Crosswalk and the application, so you can ensure that the correct Crosswalk version is used. (In shared mode, you would have to ensure that the user had the correct runtime version available.)

    The disadvantage is that the generated apk is significantly larger, as it contains the whole Crosswalk runtime inside it.

*   *Shared*

    In shared mode packaging, each web app is bundled with a thin layer of Java code which is architecture-independent. This produces a much smaller apk file. However, to run this shared mode application, a separate, architecture-dependent Crosswalk runtime also has to be installed on the target. Again, one runtime is required for each architecture (one for x86, one for ARM); but, unlike embedded mode, multiple applications can share this single runtime.

    The advantage is that one Crosswalk runtime library can support multiple shared mode applications: valuable if you are using Crosswalk to deploy multiple applications on the same Crosswalk version, as it reduces the size of each application apk.

    Another advantage is that you can upgrade the runtime for multiple applications by upgrading one shared Crosswalk runtime package. By contrast, in embedded mode, upgrading the runtime requires you to upgrade each application at the same time: so moving to a newer runtime for multiple applications means upgrading each of those applications separately.

    The disadvantage is that you must distribute apks both for your web applications and for the Crosswalk runtime.

To make a shared mode apk, pass the `--mode=shared` option to the `make_apk.py` script, for example:

    > python make_apk.py --mode=shared --package=com.intel.xwalk-simple \
        --manifest=xwalk-simple/manifest.json

Note that the `--package` option is mandatory and should be set to the Java package name for your application.

To deploy a shared mode application, you will need to install architecture-specific runtime apks on the target. These can be found in the Crosswalk Android downloads area, under the `arm/` and `x86/` directories. For example, the apks for the latest stable versions are in these files:

*   [ARM](https://download.01.org/crosswalk/releases/crosswalk/android/stable/${XWALK-STABLE-ANDROID-ARM}/arm/crosswalk-apks-${XWALK-STABLE-ANDROID-ARM}-arm.zip)
*   [Intel (x86)](https://download.01.org/crosswalk/releases/crosswalk/android/stable/${XWALK-STABLE-ANDROID-X86}/x86/crosswalk-apks-${XWALK-STABLE-ANDROID-X86}-x86.zip)
