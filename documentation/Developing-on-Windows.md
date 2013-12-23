# Building Crosswalk-enabled Android applications

This tutorial goes over the steps for setting up a Windows host environment to build Crosswalk-enabled Android applications.

It will walk you through the following:

* **[Installing the git SCM tools](#Installing-the-git-SCM-tools)** - provides a command shell plus utilities like unzip, tar, and gzip.
* **[Installing the Android SDK](#Installing-the-Android-SDK)** - necessary for building Android applications. Also provides a hardware accelerated Android
emulator.
* **[Installing Python 2.7](#Installing-Python-2.7)** - necessary to build Crosswalk-enabled Android applications.
* **[Installing the Oracle JDK](#Installing-the-Oracle-JDK)** - necessary for building Android applications.
* **[Installing Apache ant](#Installing-Apache-ant)** - necessary to build Crosswalk-enabled Android applications.
* **[Downloading a Crosswalk for Android package](#Downloading-a-Crosswalk-for-Android-package)**
* **[Building a Crosswalk-enabled Android application](#Building-a-Crosswalk-enabled-Android-application)**

## Installing the git SCM tools

Bash is provided as part of the git download for Windows. In addition to bash, git for Windows also provides tar, unzip, and gzip, which are used in later steps.

Download and install git for Windows from the git-scm website:
<a href='http://git-scm.com/download/win' target='_blank'>http://git-scm.com/download/win</a>

While installing git-scm, select the following options:

<img src='wiki/assets/integrate.png'><br>

Then select Run Git from the Windows Command Prompt:

<img src='wiki/assets/path.png'><br>

Once installed, you will want to add various directories to your PATH when you enter the Git Bash session. To do this, open notepad, paste the following, and save it as **%USERPROFILE%\\.bashrc**:
<pre>
export PATH=${PATH}:${USERPROFILE}/android/sdk/platform-tools
export PATH=${PATH}:${USERPROFILE}/android/sdk/tools
export PATH=${PATH}:${USERPROFILE}/Python27
export PATH="C:/Program\ Files/Java/jdk1.7.0_40/jre/bin":${PATH}
export PATH="C:/Program\ Files/Java/jdk1.7.0_40/bin":${PATH}
export PATH=${PATH}:${USERPROFILE}/apache-ant-1.9.2/bin
</pre>

**NOTE:** If you already have the Android SDK, Python27, Apache ANT, or the JDK installed on your system, adjust the above path variables appropriately for your system configuration.

### Launching a Git Bash session

You can now open a Git Bash session by going to your Start Menu and typing in **Git Bash**. Select Git Bash:

<img src='wiki/assets/launch.png'><br>

## Installing the Android SDK

1. Download Android SDK:
<a href='http://developer.android.com/sdk/index.html#download' target='_blank'>http://developer.android.com/sdk/index.html#download</a>
1. Extract the contents into %USERPROFILE%\android
1. Android's **platform-tools** and **tools** directories were added to the Git Bash session's PATH variable while installing git for Windows.

1. Run the SDK Manager. You can do this in the bash session by running:
<pre>
cd ${USERPROFILE}/android
"SDK Manager.exe"
</pre>
1. Within the SDK Manager you need to install:
</pre>
[ ] Android 4.3 (API 18)
    [x] Intel x86 Atom System Image
...
[ ] Extras
    [x] Intel x86 Emulator Accelerator (HAXM)
</pre>

### OPTIONAL: Emulator Setup
If you do not have an x86 based Android device, you can use the hardware accelerated execution manager (HAXM) to provide an emulated Android device on your host computer.

1. Install HAXM. The Android SDK Manager will download the HAXM installer, however it does not install it. You can do this in the bash session by running:
<pre>
cd ${USERPROFILE}/android/sdk/extras/Hardware_Accelerated_Execution_Manager
IntelHaxm.exe
</pre>
**NOTE:** The path may change where it places the IntelHaxm installation program. Find this in the bash session by running:
<pre>
cd ${USERPROFILE}/android
find . -iname intelhaxm.exe
</pre>

1. Create a new emulator image using the AVD Manager.  You can do this in the bash session by running:
<pre>
cd ${USERPROFILE}/android
sdk/"AVD Manager.exe"
</pre>

For these instructions, we call the image *Tablet*. Select **Intel Atom (x86)** for CPU/ABI and **Use Host GPU**:

<img src='wiki/assets/emulator.png'><br>

1. Launch the emulator. You can do this in the bash session by running:
<pre>
emulator -avd Tablet
</pre>

1. Once the emulator has loaded, you can use adb to connect to the emulator by running the following in your bash session:
<pre>
adb devices
</pre>
This should output something similar to the following:
</pre>
List of devices attached
emulator-5554   device
</pre>

## Installing Python 2.7

Install Python 2.7.x (don't install 3.x as some of the scripts do not support the newer 3.x syntax). You can get it from http://www.python.org/getit.

This tutorial assumes Python is installed into the default location **C:\Python27\**.

## Installing the Oracle JDK

Download the Oracle JDK from
<a href='http://www.oracle.com/technetwork/java/javase/downloads/index.html' targe='_blank'>http://www.oracle.com/technetwork/java/javase/downloads/index.html</a>

Make a note of the path where you install the JDK. If you accept the defaults, it will install to **C:\Program Files\Java\jdk1.7.0_40**.

The JDK's  **jre/bin** and **bin** directories were added to the bash session's PATH variable while installing git for Windows.

## Installing Apache ant

Install ant using the following commands in your bash session:

<pre>
curl http://apache.spinellicreations.com//ant/binaries/apache-ant-1.9.2-bin.zip -o apache-ant-1.9.2-bin.zip
unzip apache-ant-1.9.2-bin -x '*/manual/*'
</pre>

These commands download the binary distribution and decompress it to ${USERPROFILE}.

The Apache ant **bin** directory was added to the bash session's PATH variable while installing git for Windows.

## Downloading a Crosswalk for Android package
Now you can download the Crosswalk for Android package, decompress that package, and install the Crosswalk runtime library on a connected Android device using **adb**. You can do this in the bash session by running:
<pre>
cd ${USERPROFILE}
curl https://download.01.org/crosswalk/releases/android-x86/stable/crosswalk-${XWALK-STABLE-ANDROID-X86}-x86.zip -o crosswalk-${XWALK-STABLE-ANDROID-X86}-x86.zip
unzip crosswalk-${XWALK-STABLE-ANDROID-X86}-x86.zip
adb install -r ${USERPROFILE}/crosswalk-${XWALK-STABLE-ANDROID-X86}/apks/XWalkRuntimeLib.apk
</pre>

**NOTE:** Passing **-r** will re-install the Crosswalk runtime (if you already have a version installed on your device).

At this point, if you go to your Android system settings, you should see *XWalkRuntimeLib* listed in the set of installed applications.

<img src='wiki/assets/android-settings.png'><br>

## Building a Crosswalk-enabled Android application

You are now ready to build a Crosswalk application!

Download the **crosswalk-samples** source package, decompress it, and then build a Crosswalk enabled
Android application hosting the WebGL sample. You can do this in the Git Bash session by running:

<pre>
curl https://download.01.org/crosswalk/releases/crosswalk-samples-0.1.tgz -o crosswalk-samples-0.1.tgz
tar xvf crosswalk-samples-0.1.tgz
cd crosswalk-${XWALK-STABLE-ANDROID-X86}
tar xvf xwalk_app_template.tgz
cd xwalk_app_template
python make_apk.py --package=com.sample.webgl --name=WebGL --app-root=../../samples/webgl --app-local-path=index.html
</pre>

You can install the WebGL sample on your device using **adb install**. You can do this in the Git Bash session by running:

<pre>
adb install WebGL.apk
</pre>

The WebGL sample will now be in your application listing:

<img src='wiki/assets/android-apps.png'><br>

After launching WebGL, you should see the following application:

<img src='wiki/assets/android-webgl.png'><br>
