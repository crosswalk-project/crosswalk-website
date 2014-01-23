# Android target setup

Crosswalk applications will run on either a hardware or emulated Android device.

Any version of Android from 4.0 or higher should work.

## Android device

To deploy a Crosswalk application to an Android device, you need a working connection between it and your host computer. The simplest way is to connect them together via a USB cable.

To test whether the device has been detected, run the following command from a bash shell:

    $ adb devices
    List of devices attached
    Medfield532DC30E	device

If the list of devices attached is empty, you may need to change the developer options on your device to enable USB debugging (*Settings* &gt; *Developer options* &gt; turn on *USB debugging*).

### Fixing device access issues on Linux

In some cases, running `adb` as a non-root user on Linux may result in your devices not being detected:

    $ adb devices
    List of devices attached

or detected with insufficient permissions:

    $ adb devices
    List of devices attached
    ????????????	no permissions

(The latter seems to happen with older Android 4.0.* versions.)

In both cases, a work-around is to run the `adb` server as root:

    # kill any existing server instances
    $ sudo ~/android-sdk/platform-tools/adb kill-server

    # start the adb server as root
    $ sudo ~/android-sdk/platform-tools/adb start-server

    # check for devices (non-root user should be ok now)
    $ adb devices
    List of devices attached
    HT23KW103989	device

## Android emulator

To test your application on Android platforms you don't own, the next best option is to use an emulated device. These can be installed via the Android SDK.

1.  Start the Android SDK Manager. If you set it up as detailed in [Install the Android SDK](#Install-the-Android-SDK), you can invoke this as follows:

    *   On Linux, from a bash shell:

            $ android

    *   On Windows, from a cmd shell (if you try to do this from bash it can fail silently due to Windows' security measures):

            > %HOMEPATH%\xwalk-tools\android-sdk\"SDK Manager.exe"

2.  In the SDK Manager window, check the following boxes in the list:

        [ ] Android 4.3 (API 18)
            [x] Intel x86 Atom System Image

    If you want to test with other Android API versions, install the corresponding x86 system images.

3.  On Windows **only**, download HAXM as well using the SDK Manager:

        [ ] Extras
            [x] Intel x86 Emulator Accelerator (HAXM)

    This provides better graphics performance for emulated x86 devices running on Windows.

    Note that the SDK Manager downloads HAXM, but does not install it. To install it, first find the installer by running this command in a bash shell:

        $ find ~/android-sdk/ -iname *haxm*

    This should output the path to the HAXM .exe file. For example, my file was:

        /c/Users/elliot/android-sdk/sdk/extras/intel/Hardware_Accelerated_Execution_Manager/IntelHaxm.exe

    Then, using Windows Explorer, navigate to the folder above the `IntelHaxm.exe` executable, right click on that `.exe` file, and select *Open* to run the installer. Answer *Yes* to any security prompts and accept the default installation settings.

4.  Once your selected packages are installed, set up an emulator image by running the AVD Manager:

    *   On Linux, from a bash shell:

            $ android avd

    *   On Windows, from a cmd shell:

            > %HOMEPATH%\xwalk-tools\android-sdk\sdk\tools\android.bat avd

    Create a new image called **Tablet**.

    Select the following options:

    *   *Target*: **Android 4.3**
    *   *CPU/ABI*: **Intel Atom (x86)** (if you only downloaded an x86 image, this will be selected automatically)
    *   **Use Host GPU** (check the box)

    The configuration should look something like this:

    <img src='assets/emulator.png'>

5.  Launch the new emulator image from a bash shell (this works on both Linux and Windows):

        $ emulator -avd Tablet

You should be able to connect to any running images using adb, as for a hardware device:

    $ adb devices
    List of devices attached
    emulator-5554	device
