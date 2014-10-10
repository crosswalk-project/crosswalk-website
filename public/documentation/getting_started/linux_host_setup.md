# Linux host setup

You need different tools depending on which target platforms you want to deploy your application to:

*   Deploy to Android: follow [Installation for Crosswalk Android](/documentation/getting_started/Linux_host_setup.html#Installation-for-Crosswalk-Android).
*   Deploy to Tizen: follow [Installation for Crosswalk Tizen](/documentation/getting_started/Linux_host_setup.html#Installation-for-Crosswalk-Tizen).

These instructions have been tested on Fedora Linux 20, 64 bit. If you are using a different platform, you may need to modify them to suit your environment (e.g. use `apt-get` instead of `yum` if using Ubuntu, change package names where they differ from Fedora's package names).

<h2 id="Installation-for-Crosswalk-Android">Installation for Crosswalk Android</h2>

These steps will enable you to develop Crosswalk applications to run on Android:

1.  [Install Python](/documentation/getting_started/linux_host_setup.html#Install-Python).
2.  [Install the Oracle Java Development Kit (JDK)](/documentation/getting_started/linux_host_setup.html#Install-the-Oracle-JDK).
3.  [Install Ant](/documentation/getting_started/linux_host_setup.html#Install-Ant).
4.  [Configure the tools](/documentation/getting_started/linux_host_setup.html#Configure-the-tools).
5.  [Install the Android SDK](/documentation/getting_started/linux_host_setup.html#Install-the-Android-SDK).
6.  [Download the Crosswalk Android app template](/documentation/getting_started/linux_host_setup.html#Download-the-Crosswalk-Android-app-template).
7.  [Verify your environment](/documentation/getting_started/linux_host_setup.html#Verify-your-environment).

<h3 id="Install-Python">Install Python</h2>

Python can usually be installed using your system's package manager. For example, on Fedora:

    sudo yum install python

<h3 id="Install-the-Oracle-JDK">Install the Oracle JDK</h3>

The Oracle JDK has to be downloaded manually (you must accept a licence agreement):

1.  Go to the Oracle Java JDK download page in a browser:

    http://www.oracle.com/technetwork/java/javase/downloads/

    Choose the Java version to download (Java 7 is known to work).

2.  Choose a JDK download and accept the licence agreement.

3.  Once downloaded, unpack the archive file:

        tar zxvf <jdk file>.tar.gz

    The JDK binaries are in `<unpacked directory>/bin`. We'll add that directory to our path later.

<h3 id="Install-Ant">Install Ant</h3>

1.  Download Ant from: http://www.apache.org/dist/ant/binaries/

    Version 1.9.3 is known to work.

2.  Unzip it (using WinZip or similar).

3.  Rename the unzipped directory to `ant`.

<h3 id="Configure-the-tools">Configure the tools</h3>

The next step is to set up your environment so that binaries and scripts which were installed manually (ant, JDK) are on your `PATH`. Edit your `~/.bashrc` file, adding these lines to the end:

    export PATH=<path to ant>/bin:<path to JDK>/bin:$PATH

It's also advisable to set the `JAVA_HOME` environment variable, to ensure that Ant uses the expected version of Java (rather than other versions of Java which may be installed on your system). Also in the `~/.bashrc` file, add this line:

    export JAVA_HOME=<path to JDK>

(Note that you should not include the `bin/` directory in the path for `JAVA_HOME`.)

Then refresh your `PATH` variable in the shell:

    > source ~/.bashrc

Note that we prepend the new paths to the `PATH` variable to ensure that we use scripts and binaries from our newly-installed packages.

<h3 id="Install-the-Android-SDK">Install the Android SDK</h3>

1.  Download the Android SDK from <a href='http://developer.android.com/sdk/index.html' target='_blank'>http://developer.android.com/sdk/index.html</a>.

2.  Extract the SDK from the archive file you downloaded.

3.  Add the Android SDK directories to your `PATH` by editing `~/.bashrc`:

        export PATH=<path to Android SDK>:$PATH
        export PATH=<path to Android SDK>/tools:$PATH
        export PATH=<path to Android SDK>/platform-tools:$PATH

    Note that, depending on your how you installed the SDK, the `tools` and `platform-tools` directories may be in slightly different locations. Amend the above paths appropriately.

4.  Refresh your `PATH` variable:

        > source ~/.bashrc

5.  Run the *SDK Manager*. You can do this from a bash shell as follows:

        > android

6.  In the SDK Manager window, select the following items from the list:

        [ ] Tools
          [x] Android SDK Platform-tools 19.0.1
          [x] Android SDK Build tools 18.0.1
        [ ] Android 4.3 (API 18)
          [x] SDK Platform

    Note that if you are using devices with versions of Android later than 4.3, you should install the platform tools, build tools and SDK platform for those versions too.

<h3 id="Download-the-Crosswalk-Android-app-template">Download the Crosswalk Android app template</h3>

The Crosswalk Android distribution contains an application template which can be used as a wrapper for an HTML5 application. It also includes a script which will convert a wrapped HTML5 application into an installable Android `apk` file.

To get Crosswalk Android:

1.  Download the version you want from the [Downloads page](/documentation/downloads.html). We suggest that you use the stable Android version (${XWALK-STABLE-ANDROID-X86}).

2.  Unzip it. You should now have a `crosswalk-${XWALK-STABLE-ANDROID-X86}` directory.

<h3 id="Verify-your-environment">Verify your environment</h3>

Check that you have installed the tools properly by running these commands:

    > java -version
    java version "1.7.0_45"
    Java(TM) SE Runtime Environment (build 1.7.0_45-b18)
    Java HotSpot(TM) 64-Bit Server VM (build 24.45-b08, mixed mode)

    > ant -version
    Apache Ant(TM) version 1.9.3 compiled on December 23 2013

    > python --version
    Python 2.7.6

    > adb help
    Android Debug Bridge version 1.0.31

<h2 id="Installation-for-Crosswalk-Tizen">Installation for Crosswalk Tizen</h2>

In this tutorial, you're going to use an emulated Tizen IVI image, running under VMware. To be able to create this image and access it, you need to install a few packages on the host machine:

1.  **bash:** the script for generating Tizen packages runs under a bash shell. Usually, bash is installed by default, but just in case it's not:

        sudo yum install bash

2.  Packages containing utilities:

    <ul>
    <li><strong>openssh:</strong> this is so you can use the <code>ssh</code> command to push files to, and log in to, the virtual machine./li>
    <li><strong>openssl:</strong> this provides the <code>openssl</code> command used to create a key for signing your Tizen packages.</li>
    <li><strong>bzip2:</strong> to unpack the Tizen IVI disk image.</li>
    <li><strong>qemu-img:</strong> this is used to convert a Tizen IVI disk image into a format suitable for use with VMware.</li>
    </ul>

    You can usually install all of these with your package manager. For example, on Fedora

        sudo yum install openssh openssl bzip2 qemu-img

3.  **Kernel headers:** these are required so that VMware can compile the Linux kernel modules which enable it to run:

        sudo yum install kernel-headers

4.  **VMware Player** or **VMware Workstation**, to create and run the virtual machine. The free version of Player can be downloaded from [the VMware website](https://my.vmware.com/web/vmware/free). However, if you are using Player for commercial purposes, you will [need a licence](http://store.vmware.com/buyplayerplus).

    If you need help with installing VMware products, see [this page on the VMware website](http://kb.vmware.com/selfservice/microsites/search.do?language=en_US&cmd=displayKC&externalId=2053973).

Now the host is setup, you can prepare your targets.
