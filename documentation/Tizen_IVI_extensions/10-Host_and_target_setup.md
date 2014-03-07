# Host and target setup for extensions

In this section, we set up the host computer for Crosswalk Tizen development: enabling IVI support in the Tizen SDK on your host, creating a Tizen IVI target, and installing Crosswalk on it.

The host should first be [set up for Crosswalk development](#documentation/getting_started/host_setup); in particular, you need to install the Tizen SDK. Then there are a couple of additional steps to enable you to compile native code for Tizen, described below.

**Note:** these steps are only necessary if you are writing Crosswalk Tizen extensions: they are not required if you are writing Crosswalk Android extensions, or just using Crosswalk as a runtime for a web app.

## Install a Tizen IVI image

First, get hold of a Tizen IVI emulator image (there are no physical Tizen IVI devices at present). To do this:

1.  [Install the Tizen SDK](#documentation/getting_started/host_setup/Optional:-installing-tools-for-Tizen-targets).

2.  Follow [these instructions](#documentation/getting_started/tizen_target_setup/Tizen-emulator) to set up the Tizen SDK emulator. Then start the Tizen SDK Install Manager.

3.  Use the Install Manager to set up the Tizen SDK for the "latest" channel, which contains the IVI image. You need to do this because the IVI image is not part of the stable SDK (as of 2014-03-03).

    To do this, open the Install Manager, then click the *Advanced* button. Follow the instructions at https://wiki.tizen.org/wiki/Tizen_IVI_SDK to enable the "latest" channel.

    Select *OK* to go back to the main Install Manager screen.

4.  Once configured, select *Next* to go to the component selection screen. Select the components you need:

        [ ] Native App Development
          [x] Toolchain
          [x] Native IDE
          [x] Command Line tools
            [x] Toolchain
            [x] Native IDE Core Resources
            [x] Native Rootstrap
        [x] Common Tools
          [x] x86 Emulator
        [ ] Platforms
          [x] IVI 3.0
            [x] x86 Platform Image
            [x] Web IDE Core Resources for IVI profile

    Even if you don't intend to use the Web IDE, the Emulator Manager will not run correctly without the *Web IDE Core Resources for IVI profile* component.

## Create a Tizen IVI virtual machine (VM)

You can now create the VM:

1.  Open the Tizen Emulator Manager. [These instructions](#documentation/getting_started/tizen_target_setup/Tizen-emulator) explain how to do this.

2.  In the *ivi-3.0* tab, select *Add New* to create a new VM.

3.  In the *Detail* panel (on the right), set *Name* to **tizen-IVI** and *Base Image* to **emulimg-3.0.x86**. Accept the defaults for the remaining fields and click on *Confirm*.

    The result should look like this:

    ![Tizen emulator manager for Tizen IVI](assets/tizen-emulator-manager-tizen-ivi.png)

4.  Start the Tizen IVI VM using the blue "play" button. It can take a while to start, so don't be alarmed if nothing seems to be happening.

5.  From a command line, you can check whether the VM has booted with this command:

        sdb devices

    If the VM is ready, you should get output like this:

        List of devices attached
        emulator-26101          device          tizen-IVI

    If it isn't ready, you will see:

        error msg: target not found

## Install Crosswalk

You can now install Crosswalk on the target VM:

1.  Once the target VM is ready, get a root shell on it with:

        sdb root on
        Switched to `root` account mode

        sdb shell

    The shell prompt should be `#` if you are root. If it isn't, exit, ensure that root mode is on, then try to get a root shell again.

2.  From the shell on the target, download the Crosswalk package:

        curl -O https://download.01.org/crosswalk/releases/tizen-ivi/canary/crosswalk-5.32.87.0-0.i586.rpm

    And install it:

        rpm -ih crosswalk-5.32.87.0-0.i586.rpm

    I suggest you use Crosswalk for Tizen, version 5.32.87.0: this will install successfully on a Tizen IVI VM. The other version I tried (5.34.94.0) wouldn't install. (Note that this is to be expected, as Crosswalk for Tizen IVI is still considered unstable, and some versions may not work.)

3.  Check the installation is working by switching to the `app` user. This user has the correct configuration to be able to run Crosswalk on the target:

        # still as the root user
        su - app

    Then test that Crosswalk can run:

        xwalk

    You should see some messages about [Ozone](https://github.com/01org/ozone-wayland), e.g.:

        [0306/093443:INFO:desktop_factory_wayland.cc(12)] Ozone: DesktopFactoryWayland
        [0306/093443:INFO:desktop_factory_wayland.cc(12)] Ozone: DesktopFactoryWayland

    These indicate that Crosswalk is running correctly on the target.

## Configure the Tizen SDK CLI tools

The Tizen SDK includes a set of command line tools for building and packaging Tizen native apps. You can use these tools to build an extension for Crosswalk. Make sure these tools are on your path by editing your `~/.bashrc` file (Windows or Linux):

    export PATH=<TIZEN_SDK_HOME>tools/mingw/bin:$PATH

## Checkout Crosswalk source code

You will need a copy of the Crosswalk source, to enable you to compile against the Crosswalk headers.

Checkout the Crosswalk github repo on the host machine (the machine where you intend to compile your extension):

    git clone https://github.com/crosswalk-project/crosswalk ~/crosswalk-source/xwalk

It's important that you clone the code to a directory called `xwalk`, as this is the path the compiler will be looking on for headers/libraries.

## Create project directories

The project name is **simple**. Set up the directories for the project from a command line as follows:

    mkdir simple
    cd simple

    # directory for the HTML5 web app
    mkdir app

    # directory for the Crosswalk extension
    mkdir extension

Now the preparation is complete, you can start building the application and associated extension.
