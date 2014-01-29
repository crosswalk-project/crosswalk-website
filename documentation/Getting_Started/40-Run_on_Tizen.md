# Running a Crosswalk app on Tizen

To run the application on a Tizen target, there is no need to package it: you can just push the HTML5 project to the device and run it manually from the command line using `sdb`, as follows:

1.  Prepare the device (either connect it to the host or start it with the emulator).

2.  Start your console. Use bash on Linux or cmd on Windows.

3.  Put `sdb` into root mode:

        sdb root on

4.  Push the application to the target. Note that we use the developer account's home directory on the Tizen target as a convenient place to put files.

    On Linux:

        $ sdb push ~/xwalk-simple /home/developer/

    On Windows:

        > sdb push %HOMEPATH%\xwalk-simple /home/developer/

5.  Run the application. On an emulated Tizen target, use this command:

        sdb shell "xwalk --use-gl=osmesa /home/developer/xwalk-simple"

    The `--use-gl` flag ensures that WebGL is enabled (via Mesa).

The application should have started on the target. Here it is running on an emulated Tizen 2.2.0 tablet, on Windows:

TODO - can't show this at the moment due to xwalk being broken on emulated Tizen 2.2 - https://crosswalk-project.org/jira/browse/XWALK-614
