# Running a Crosswalk app on Tizen

On Tizen, Crosswalk runs as a background service, only becoming active when needed (i.e. when a user session activates it). It effectively runs as a daemon with a D-Bus interface for managing applications.

To run an application on a Tizen target, first ensure you have [set up your host for Tizen] and [set up a Tizen target].

Next, follow these steps to get the application running:

???link to sections

1.  Create a Tizen configuration file (`config.xml`).
2.  Create a Tizen package (`.xpk` file) from the application code plus the `config.xml` file.
3.  Push the package to the device.
4.  Install the package.
5.  Run the application from the Tizen home screen.

These steps are explained in detail below.

## Create a Tizen configuration file

???not sure if this is necessary, but I tried with and without

    <?xml version="1.0" encoding="UTF-8"?>
    <widget xmlns="http://www.w3.org/ns/widgets" xmlns:tizen="http://tizen.org/ns/widgets" id="https://foo.bar/simple" version="2.0.0" viewmodes="fullscreen">
        <name>xwalk-simple</name>
        <icon src="icon.png"/>
        <tizen:application id="xwalksimpl.xwalksimple1" package="xwalksimpl" required_version="2.2"/>
        <content src="index.html"/>
    </widget>

## Create a Tizen package

A Tizen package file is a zip file with an `.xpk` suffix. It should contain all of the files relating to your application (HTML, CSS, JavaScript, assets), as well as any metadata (???config.xml, `manifest.json`, icons etc.).

To create a Tizen package, you can use any standard zip application, command line or GUI.

*   On Windows, you can use a zip application like WinZip or 7zip to create the archive. Once you've created it, change the `.zip` suffix to `.xpk`.

*   On Linux, use the built-in `zip` from the command line to create the package in your home directory:

        zip ~/xwalk-simple.xpk xwalk-simple/*

The important thing to remember is that the Tizen package should have the application's `manifest.json` file at its top level. For example, the structure of the package for the *xwalk-simple* application in this tutorial should look like this:

    xwalk-simple.xpk
      icon.png
      index.html
      manifest.json

## Push the package to the device

1.  Prepare the device (either connect it to the host or start it with the emulator).

2.  Put `sdb` into root mode:

        > sdb root on

3.  Push the package to the emulated device:

        > sdb push xwalk-simple.xpk /home/app/

    Note that we're using the **app** account's home directory on the target, as we'll install and run the application using this account.

## Install the package

1.  Get a root shell on the device:

        > sdb root on
        > sdb shell

2.  Prepare the environment for a non-privileged user. Rather than install and run the application as root, we will apply some manual steps so the non-privileged **app** user can do it. Still as root, do:

        # on the device
        sh-4.1# mkdir /run/user/app/dbus/
        sh-4.1# chown -R app /run/user/app/

3.  On the device, change to the non-privileged **app** user:

        sh-4.1# su - app
        app:~> whoami
        app

    Note that the prompt for the **app** user may differ between devices/Tizen versions.

4.  Set the **app** user's `XDG_RUNTIME_DIR` and `DBUS_SESSION_BUS_ADDRESS` variables:

        app:~> export XDG_RUNTIME_DIR=/run/user/5000
        app:~> export DBUS_SESSION_BUS_ADDRESS=unix:path=/run/user/5000/dbus/user_bus_socket

5.  Start Crosswalk as a service:

        app:~> xwalk --run-as-service

6.  Check the status of the Crosswalk service:

        app:~> systemctl --user status xwalk.service
        xwalk.service - Crosswalk
        Loaded: loaded (/usr/lib/systemd/user/xwalk.service; static)
        Active: inactive (dead)

7.  Now open another shell on the Tizen target from another terminal on your host:

        > sdb root on
        > sdb shell

        # on the device
        sh-4.1# su - app
        app:~> export XDG_RUNTIME_DIR=/run/user/5000

    This is the session we're going to use to install the package.

8.  From this second shell, install the `xwalk-simple.xpk` package using the `xwalkctl` command (still as the app user):

        app:~> xwalkctl --install /home/app/xwalk-simple.xpk

    The path to the xpk file must be absolute, otherwise the installer won't work.

## Run the application

???to start?

The application should have started on the target. Here it is running on an emulated Tizen ??? tablet, on Windows:

<img src="assets/xwalk-simple-on-tizen.png">

(NB the text is small because the emulated screen size is 720x1280px.)
