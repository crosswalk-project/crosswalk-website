# Windows host setup

These instructions have been tested on Windows 7 Enterprise, 64 bit.

These steps will enable you to develop Crosswalk applications to run on Android:

1.  [Install Python](#Install-Python)
2.  [Install the Oracle Java Development Kit (JDK)](#Install-the-Oracle-JDK)
3.  [Install Ant](#Install-Ant)
4.  [Configure the tools](#Configure-the-tools)
5.  [Install the Android SDK](#Install-the-Android-SDK)
6.  [Download the Crosswalk Android app template](#Download-the-Crosswalk-Android-app-template)
7.  [Verify your environment](#Verify-your-environment)

<h3 id="Install-Python">Install Python</h3>

1. Download Python: http://www.python.org/downloads/
2. When the installer starts, choose *Install for all users*.  For this example, we set `C:\python-3.4.3` as the installation directory (any location is fine).

<h3 id="Install-the-Oracle-JDK">Install the Oracle JDK</h3>

1.  Download the Oracle JDK: http://www.oracle.com/technetwork/java/javase/downloads/  (Java 7 and 8 are known to work)
2.  Once downloaded, run the Java <code>.exe</code> installer.  For this example, we set `C:\jdk-1.8.0_40` as the installation directory (any location is fine).

<h3 id="Install-Ant">Install Ant</h3>

1.  Download the Apache Ant build tool: http://www.apache.org/dist/ant/binaries/ (Version 1.9.3 is known to work)
2.  Unzip the contents where you want them.  For this example, we put the folder in `C:\ant-1.9.4` (any location is fine).

<h3 id="Configure-the-tools">Configure the tools</h3>

The next step is to set up your environment so that binaries and scripts which were installed manually are in your `Path` (Python, Java, and Ant).

Edit environment variables for your account. In the Windows Start menu, search for "Environment variables". Alternatively, click on the *System* icon in the *Control Panel*; then go to *Advanced system settings* and click the *Environment Variables* button. You should see this dialog box:

![Windows environment variables dialog](/assets/windows-env-variables.png)

Edit the `Path` environment variable as follows:

1.  Select the *Path* environment variable (in the top select box, *User variables...*).  If it does not exist, add it.

2.  Click *Edit*.

3.  In the *Variable value* field, add the path to *the executable* for each of the installed tools, separated with ";".  For this example, we added the following at the end of the Path variable:

        ;c:\python-3.4.3;c:\ant-1.9.4\bin;c:\jdk-1.8.0_40\bin

4.  Click *OK*.

The same task can be done on the command-line (note the quotes ""):
```
> setx path "%path%;c:\python-3.4.3;c:\ant-1.9.4\bin;c:\jdk-1.8.0_40\bin"
```

To ensure that Ant is using the correct version of Java (the one you just installed), set the `JAVA_HOME` environment variable to the location of the JDK:

1.  Click the *New* button just under the top select box (*User variables...*).

2.  Set *Variable name* to **JAVA_HOME**.

3.  Set *Variable value* to <root directory of JDK>.  For our example, **c:\jdk-1.8.0_40**.

4.  Click *OK*.

<h3 id="Install-the-Android-SDK">Install the Android SDK</h3>

1.  Download Android Studio from <a href='http://developer.android.com/sdk/index.html' target='_blank'>http://developer.android.com/sdk/index.html</a>.
2.  Run the installer.  Remember the installation directory you select for the next step.

3.  Add the Android SDK directories to your `PATH` either through the Environment Variables dialog or from the command-line. 
```
> setx path "%path%;<path to Android SDK>"
> setx path "%path%;<path to Android SDK>\sdk\tools"
> setx path "%path%;<path to Android SDK>\sdk\platform-tools"
```

    The `tools` and `platform-tools` directories may be in slightly different locations, depending on how you installed the SDK. Amend the above paths appropriately.

4.  Run the *SDK Manager*, either from inside Android Studio or from the command-line:

        > "SDK Manager.exe"

5.  In the SDK Manager window, install the Platform tools, Build tools, and SDK Platform for the version(s) you are interested in:

        [ ] Tools
          [x] Android SDK Platform-tools <version>
          [x] Android SDK Build tools <version>
        [ ] Android <version> (API <version>)
          [x] SDK Platform

<h3 id="Download-the-Crosswalk-Android-app-template">Download the Crosswalk Android app template</h3>

The Crosswalk Android distribution contains an application template which can be used as a wrapper for an HTML5 application. It also includes a script which will convert a wrapped HTML5 application into an installable Android `apk` file.

To get Crosswalk Android:

1.  Download the version you want from the [Downloads page](/documentation/downloads.html). We suggest that you use the stable Android version (${XWALK-STABLE-ANDROID-X86}).

2.  Unzip it. You should now have a `crosswalk-${XWALK-STABLE-ANDROID-X86}` directory.

<h3 id="Verify-your-environment">Verify your environment</h3>

Check that you have installed the tools properly by running these commands:

```
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
```

Congratulations, your system is ready for Android development with Crosswalk.

Now the host is setup, you can prepare your targets.
