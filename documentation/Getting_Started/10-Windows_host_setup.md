# Windows host setup

You need different tools depending on which target platforms you want to deploy your application to:

*   Deploy to Android: follow [Installation for Crosswalk Android](#documentation/getting_started/Windows_host_setup/Installation-for-Crosswalk-Android).
*   Deploy to Tizen: follow [Installation for Crosswalk Tizen](#documentation/getting_started/Windows_host_setup/Installation-for-Crosswalk-Tizen).

These instructions have been tested on Windows 7 Enterprise, 64 bit.

## Installation for Crosswalk Android

These steps will enable you to develop Crosswalk applications to run on Android:

1.  [Install Python](#documentation/getting_started/windows_host_setup/Install-Python).
2.  [Install the Oracle Java Development Kit (JDK)](#documentation/getting_started/windows_host_setup/Install-the-Oracle-JDK).
3.  [Install Ant](#documentation/getting_started/windows_host_setup/Install-Ant).
4.  [Configure the tools](#documentation/getting_started/windows_host_setup/Configure-the-tools).
5.  [Install the Android SDK](#documentation/getting_started/windows_host_setup/Install-the-Android-SDK).
6.  [Download the Crosswalk Android app template](#documentation/getting_started/windows_host_setup/Download-the-Crosswalk-Android-app-template).
7.  [Verify your environment](#documentation/getting_started/windows_host_setup/Verify-your-environment).

### Install Python

Get Python from http://www.python.org/downloads/, choosing an "MSI installer" for your architecture (32 or 64 bit).

When the installer starts, choose *Install for all users* and set **C:\python** as the installation location. You will need to manually add the Python directory to your path for it to be available in the bash shell (see [Configure your environment](#Configure-your-environment)).

### Install the Oracle JDK

The Oracle JDK has to be downloaded manually (you must accept a licence agreement):

1.  Go to the Oracle Java JDK download page in a browser:

    http://www.oracle.com/technetwork/java/javase/downloads/

    Choose the Java version to download (Java 7 is known to work).

2.  Choose a JDK download and accept the licence agreement.

3.  Once downloaded, run the Java <code>.exe</code> installer, and set <code>C:\jdk</code> as the installation directory.

### Install Ant

1.  Download Ant from: http://www.apache.org/dist/ant/binaries/

    Version 1.9.3 is known to work.

2.  Unzip it (using WinZip or similar) to your `C:` drive.

3.  Rename the unzipped directory to `ant`.

Your Ant installation should now be in `C:\ant`.

### Configure the tools

The next step is to set up your environment so that binaries and scripts which were installed manually (ant, JDK, Python) are on your `PATH`.

    > setx path "%path%;c:\python;c:\ant\bin;c:\jdk\bin"

### Install the Android SDK

1.  Download the Android SDK from <a href='http://developer.android.com/sdk/index.html' target='_blank'>http://developer.android.com/sdk/index.html</a>.

2.  Extract the files from the archive.

3.  Add the Android SDK directories to your `PATH`:

        > setx path "%path%;<path to Android SDK>"
        > setx path "%path%;<path to Android SDK>\sdk\tools
        > setx path "%path%;<path to Android SDK>\sdk\platform-tools"

    The `tools` and `platform-tools` directories may be in slightly different locations, depending on how you installed the SDK. Amend the above paths appropriately.

4.  Run the *SDK Manager*:

        > "SDK Manager.exe"

5.  In the SDK Manager window, select the following items from the list:

        [ ] Tools
          [x] Android SDK Platform-tools 19.0.1
          [x] Android SDK Build tools 18.0.1
        [ ] Android 4.3 (API 18)
          [x] SDK Platform

    Note that if you are using devices with versions of Android later than 4.3, you should install the platform tools, build tools and SDK platform for those versions too.

### Download the Crosswalk Android app template

The Crosswalk Android distribution contains an application template which can be used as a wrapper for an HTML5 application. It also includes a script which will convert a wrapped HTML5 application into an installable Android `apk` file.

To get Crosswalk Android for x86:

1.  Download the version you want from http://crosswalk-project.org/#documentation/downloads. We suggest that you use the stable Android x86 version (${XWALK-STABLE-ANDROID-X86}).

2.  Unzip it. You should now have a `C:\${XWALK-STABLE-ANDROID-X86}-x86` directory.

### Verify your environment

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

## Installation for Crosswalk Tizen

These steps will enable you to develop Crosswalk applications to run on Tizen:

<ol>

  <li>Download the Tizen SDK for your platform from <a href="https://developer.tizen.org/downloads/tizen-sdk" target="_blank">https://developer.tizen.org/downloads/tizen-sdk</a>. <strong>Note that if you intend to deploy applications to an emulated Tizen device, you must use version 2.2.0 of the Tizen SDK: see <a href="https://developer.tizen.org/downloads/sdk/advanced-configuration">the advanced configuration instructions</a> for details of how to change versions.</strong></li>

  <li>
    <p><a href="https://developer.tizen.org/downloads/sdk/installing-tizen-sdk">Follow the instructions</a> to install it.</p>

    <p>If you have a physical Tizen device, you don't need to select any of the optional components. However, if you intend to use an emulated Tizen image, you may want to install those components now. See <a href="#documentation/getting_started/tizen_target_setup">Tizen target setup</a> for details.</p>
  </li>

  <li>
    <p>Once installed, ensure that the <code>sdb</code> tool is on your <code>PATH</code>:</p>

    <pre>> setx path "%path%;<path to Tizen SDK>\tools"</pre>
  </li>

  <li>
    <p>Test <code>sdb</code>:</p>

<pre>
> sdb
Smart Development Bridge version 2.0.2
</pre>
  </li>

</ol>
