# Running a Crosswalk app on Tizen

To run the application on a Tizen target, there is no need to package it. Instead, you just push the HTML5 project to the device and run it manually from the command line using `sdb`, as follows:

1.  Prepare the device (either connect it to the host or start it with the emulator).

2.  Put `sdb` into root mode:

        > sdb root on

3.  Make a directory on the target device to push the app to:

        > sdb shell "mkdir /home/developer/xwalk-simple"

    Note that we're using the "developer" account's home directory on the target as a convenient place to put files.

4.  Push the app to the emulated device:

        > sdb push xwalk-simple /home/developer/xwalk-simple/

5.  Run the application. On an emulated Tizen target, use this command:

        > sdb shell "xwalk /home/developer/xwalk-simple"

The application should have started on the target. Here it is running on an emulated Tizen 2.2.0 tablet, on Windows:

<img src="assets/xwalk-simple-on-tizen.png">

(NB the text is small because the emulated screen size is 720x1280px.)
