# Linux host setup

These instructions have been tested on Fedora Linux 20, 64 bit. If you are using a different platform, you may need to modify them to suit your environment (e.g. use `apt-get` instead of `yum` if using Ubuntu, change package names where they differ from Fedora's package names).

These steps will enable you to develop Crosswalk applications to run on Android:

1.  [Install Python](#Install-Python)
2.  [Install the Oracle Java Development Kit (JDK)](#Install-the-Oracle-JDK)
3.  [Install Ant](#Install-Ant)
4.  [Configure the tools](#Configure-the-tools)
5.  [Install the Android SDK](#Install-the-Android-SDK)
6.  [Download the Crosswalk Android app template](#Download-the-Crosswalk-Android-app-template)
7.  [Verify your environment](#Verify-your-environment)

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
          [x] Android SDK Platform-tools <version>
          [x] Android SDK Build tools <version>
        [ ] Android 4.3 (API <version>)
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


Now the host is setup, you can prepare your targets.
