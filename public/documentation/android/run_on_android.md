# Running a Crosswalk app on Android

Installing and running an application built with Crosswalk requires an Android target (either emulated or actual device). For assistance, see the [Android target setup](android_target_setup.html) page.

## Install the application on the target.

To install APKs created in the [build application page](build_an_application.html), do the following:

* x86 device

      > adb install -r com.abc.myapp-0.1-debug.x86.apk

* ARM device

      > adb install -r com.abc.myapp-0.1-debug.armeabi-v7a.apk

The `-r` flag stands for "reinstall". It is not required for the first installation, but useful for subsequent reinstalls of the same package.

If the installation is successful, your application icon will appear on the Android target.

<img src="/assets/xwalk-simple-on-android.png" style="display:block;margin:0 auto;">

