# Running a Crosswalk app on Tizen

On Tizen, Crosswalk runs as a background service, only becoming active when needed (i.e. when a user session activates it). In technical turns, Crosswalk effectively runs as a daemon, exposing a D-Bus interface for managing applications.

To run an application on a Tizen target, first ensure you have [set up your host for Tizen] and [set up a Tizen target]. In the instructions below, we assume you are using a Tizen IVI target running under VMware, and consequently use `ssh` to push files to and get a shell on the target.

Next, follow these steps to get the application running:

1.  [Create a Tizen package](#Create-a-Tizen-package) (`.xpk` file) from the application code.
2.  [Push the package to the device](#Push-the-package-to-the-device).
3.  [Install the application package](#Install-the-application-package).
4.  [Run the application](#Run-the-application).

These steps are explained in detail below.

## Create a Tizen package

A Tizen package file is a zip file with some "magic" and an `.xpk` suffix. It will contain all of the files relating to your application (HTML, CSS, JavaScript, assets), as well as any metadata (`manifest.json`, icons etc.). See [the wiki](#wiki/Crosswalk-package-management) for detailed information about the format.

To create a zip package, you will need to have a `bash` shell and the `openssl` binary installed. On Linux, these are usually available by default. On Windows, you will need to [install git SCM](#documentation/getting_started/Windows_host_setup/Installation-for-Crosswalk-Tizen).

Then follow these steps to create the package:

1.  Copy the following script into a file called `make_xpk.sh`:

        #!/bin/bash -e
        #
        # Purpose: Pack a CrossWalk directory into xpk format
        # Modified from http://developer.chrome.com/extensions/crx.html
        if test $# -ne 2; then
          echo "Usage: `basename $0` <unpacked dir> <pem file path>"
          exit 1
        fi

        dir=$1
        key=$2
        name=$(basename "$dir")
        xpk="$name.xpk"
        pub="$name.pub"
        sig="$name.sig"
        zip="$name.zip"
        trap 'rm -f "$pub" "$sig" "$zip"' EXIT

        [ ! -f $key ] && openssl genrsa -out $key 1024

        # zip up the xpk dir
        cwd=$(pwd -P)
        (cd "$dir" && zip -qr -9 -X "$cwd/$zip" .)

        # signature
        openssl sha1 -sha1 -binary -sign "$key" < "$zip" > "$sig"

        # public key
        openssl rsa -pubout -outform DER < "$key" > "$pub" 2>/dev/null

        byte_swap () {
          # Take "abcdefgh" and return it as "ghefcdab"
          echo "${1:6:2}${1:4:2}${1:2:2}${1:0:2}"
        }

        crmagic_hex="4372 576B" # CrWk
        pub_len_hex=$(byte_swap $(printf '%08x\n' $(ls -l "$pub" | awk '{print $5}')))
        sig_len_hex=$(byte_swap $(printf '%08x\n' $(ls -l "$sig" | awk '{print $5}')))
        (
          echo "$crmagic_hex $pub_len_hex $sig_len_hex" | xxd -r -p
          cat "$pub" "$sig" "$zip"
        ) > "$xpk"
        echo "Wrote $xpk"

2.  Make the script executable:

        > chmod +x make_xpk.sh

3.  To create xpk packages, you will need a private key file. Use `openssl` to generate this for you:

        > openssl genrsa -out mykey.pem 1024

4.  Call the shell script, passing it the path to the directory containing your application and your key file:

        > ./make_xpk.sh xwalk-simple/ mykey.pem

    This will produce a file named `xwalk-simple.xpk` in the directory where you ran the script.

## Push the package to the device

1.  Prepare the device (either connect it to the host or start it with VMware player/the emulator).

2.  Use `scp` to push the package to the device:

        > scp xwalk-simple.xpk root@<ip address>:/home/app/

    Note that we're using the **app** account's home directory on the target, as we'll install and run the application using this account.

## Install the application package

1.  Get a root shell on the device:

        > ssh root@<ip address>

    The password is **tizen**.

2.  Prepare the environment for a non-privileged user. Rather than install and run the application as root, we will apply some manual steps so the non-privileged **app** user can do it. As root on the target, do:

        root:~> mkdir /run/user/app/dbus/
        root:~> chown -R app /run/user/app/

3.  Still on the target, change to the non-privileged **app** user:

        root:~> su - app
        app:~> whoami
        app

    Note that the prompt for the **app** user may differ between devices/Tizen versions.

4.  Set the **app** user's `XDG_RUNTIME_DIR` and `DBUS_SESSION_BUS_ADDRESS` variables:

        app:~> export XDG_RUNTIME_DIR=/run/user/5000
        app:~> export DBUS_SESSION_BUS_ADDRESS=unix:path=/run/user/5000/dbus/user_bus_socket

5.  Start Crosswalk as a service:

        app:~> xwalk --run-as-service

6.  Now open another shell on the Tizen target:

        # on the host
        > ssh root@<ip address>

        # on the target, in the new shell
        root:~> su - app
        app:~> export XDG_RUNTIME_DIR=/run/user/5000

    This is the session we're going to use to install the package.

7.  From this second shell, install the `xwalk-simple.xpk` package using the `xwalkctl` command (still as the app user):

        app:~> xwalkctl --install /home/app/xwalk-simple.xpk

    The path to the xpk file must be absolute, otherwise the installer won't work.

    The output from this command will look something like this:

        Application installed/updated with path '/installed1/dogabgfklbjobjkfdbokaedngjeepepj'

    The long string at the end of the installation path (*dogabgfklbjobjkfdbokaedngjeepepj* here) is the **application ID**. This is important, as you'll need it to launch the application in the next step.

    In the shell where you started `xwalk --run-as-service`, you should also see output like this:

        [0411/041919:WARNING:exported_object.cc(211)] Unknown method: message_type: MESSAGE_METHOD_CALL
        destination: :1.2
        path: /installed1
        interface: org.freedesktop.DBus.Properties
        member: GetAll
        sender: :1.3
        signature: s
        serial: 12

        string "org.crosswalkproject.Installed.Manager1"

        [0411/041920:WARNING:service_package_installer.cc(129)] 'icon' not included in manifest
        [0411/041920:INFO:service_package_installer.cc(108)] Converting manifest.json into dogabgfklbjobjkfdbokaedngjeepepj.xml for installation. [DONE]
        [0411/041922:INFO:application_service.cc(297)] Application be installed in: /home/app/.config/xwalk-service/applications/dogabgfklbjobjkfdbokaedngjeep
        epj
        [0411/041922:INFO:application_service.cc(298)] Installed application with id: dogabgfklbjobjkfdbokaedngjeepepj successfully.

    This confirms that the package has been installed successfully.

## Run the application

To start the application, you need to know the ID assigned to the application when it was installed.

Pass this ID to the `xwalk-launcher` command, in the same shell you used to install the application (see above):

    app:~> xwalk-launcher -f dogabgfklbjobjkfdbokaedngjeepepj

(The `-f` option instructs Crosswalk to run the application in fullscreen mode.)

The application should now start on the target. Here it is running on an emulated Tizen IVI device, on Fedora Linux:

<img src="assets/xwalk-simple-on-tizen-ivi.png">
