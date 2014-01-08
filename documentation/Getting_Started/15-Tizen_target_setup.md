# Tizen target setup

To run Crosswalk applications on Tizen, you must install the Crosswalk runtime on each target. (Unlike Crosswalk for Android, it is currently not possible to bundle the Crosswalk runtime with the application for deployment to Tizen.)

Tizen extensions for Crosswalk are also available, which enable you to make use of [Tizen APIs](https://developer.tizen.org/documentation/dev-guide/2.2.1?redirect=https%3A//developer.tizen.org/dev-guide/2.2.1/org.tizen.web.appprogramming/html/api_reference/api_reference.htm) in applications running in Crosswalk on Tizen. We don't need them for this tutorial, so we won't cover them here.

Crosswalk is available for Tizen version 2.1 or higher.

## Tizen device

At the time of writing (January 2014), there are no Tizen devices on the market, so your only option is to use an emulated Tizen device.

## Tizen emulator

To test your application on Tizen, use an emulated x86 device. This can be installed via the Tizen SDK install manager:

1.  Start the Tizen SDK Install Manager:

    *   On Windows, from a cmd shell (you'll need a different path if you didn't install it to a `tizen-sdk` directory inside your home directory):

            > %HOMEPATH%\tizen-sdk\install-manager\inst-manager.exe

    *   On Linux, from a bash shell, run the Tizen `.bin` file you originally downloaded, e.g.:

            $ ./tizen-sdk-ubuntu64-v2.2.71.bin

2.  Select *Install or update the Tizen SDK* and then the *Next* button.

3.  Select the Tizen system image and emulator from the list of components available:

        [ ] Common Tools
          [x] Emulator

        [ ] Platforms
          [ ] Tizen 2.2
            [x] Platform Image

    Then select *Install*.

4.  Once the download is complete, you can create a Tizen emulator image by running the Tizen Emulator Manager:

    *   On Windows, from a cmd shell:

            > %HOMEPATH%\tizen-sdk\tools\emulator\bin\emulator-manager.exe

    *   On Linux, from a bash shell:

            $ ~/tizen-sdk/tools/emulator/bin/emulator-manager

    Ensure that the drop-down menu in the top-left of the window has **x86-standard** selected, then select *Create New VM*.

5.  Set the name to **Tablet** and ensure that *Base Image* is set to **emulimg-2.2.x86**. Accept the defaults for the other settings:

    <img src="tizen-emulator-manager.png">

    Click *Confirm* to create the image.

6.  To run the image, stay in the Tizen Emulator Manager and click the play button at the bottom of the box representing the image. The emulator should now run your image:

    <img src="tizen-emulated-running.png">

7.  The final step is to install the Crosswalk Tizen runtime. This can be done by using `sdb` to get a root shell on the emulated device (either use bash or cmd):

        # on the host machine, from a bash shell
        sdb root on
        Switched to 'root' account mode

        # get a root shell on the emulated device
        sdb shell
        sh-4.1#

        # now we're on the emulated device (note that your prompt may
        # be slightly different from "sh-4.1#")

        # download and install the Crosswalk Tizen rpm
        sh-4.1# curl https://download.01.org/crosswalk/releases/tizen/stable/crosswalk-${XWALK-STABLE-TIZEN-X86}.i586.rpm -o tz-xwalk.rpm
        sh-4.1# rpm -ih tz-xwalk.rpm
