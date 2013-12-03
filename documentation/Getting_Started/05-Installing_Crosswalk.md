# Installing Crosswalk

## Android

You will need to install the Android SDK, including [adb](http://developer.android.com/tools/help/adb.html), and use it to connect your device to your development machine or use the Android emulator.

1. Install the [Android SDK](http://developer.android.com/sdk/index.html).
1. Download the Crosswalk binary for Android from the URL in [Downloads](#documentation/downloads/files).
```sh
wget https://download.01.org/crosswalk/releases/android-x86/stable/crosswalk-${XWALK-STABLE-ANDROID-X86}.zip
```
1. Decompress the Crosswalk binary to access the various Android packages that can be installed:
```sh
unzip crosswalk-${XWALK-STABLE-ANDROID-X86}.zip
```
1. Install the XWalkRuntimeLib package:
```sh
adb install crosswalk-${XWALK-STABLE-ANDROID-X86}/apks/XWalkRuntimeLib.apk
```
On successful completion, you should see the final string *Success* displayed.

NOTE: If you have previously installed the XWalkRuntimeLib:
```sh
adb shell
pm uninstall org.xwalk.runtime.lib
```

You are now ready to install Crosswalk applications on your Android 
system. If you go to your system Settings, you should see 
**XWalkRuntimeLib** listed under the **Apps/Downloaded** list.

## Tizen
These steps assume you have the [Tizen SDK](https://developer.tizen.org/downloads/tizen-sdk) installed and correctly configured on your system. 

You can use the Tizen emulator as a target for running and developing Crosswalk applications on Tizen.

1. Install the [Tizen SDK](http://developer.tizen.org/downloads/tizen-sdk).
1. Download the Crosswalk binary for Tizen from the URL in [Downloads](#documentation/downloads/files).
```sh
wget https://download.01.org/crosswalk/releases/tizen/stable/crosswalk-${XWALK-STABLE-TIZEN-X86}-0.i586.rpm
wget https://download.01.org/crosswalk/releases/tizen/stable/crosswalk-emulator-support-${XWALK-STABLE-TIZEN-X86}-0.i586.rpm
wget https://download.01.org/crosswalk/releases/tizen/canary/tizen-extensions-crosswalk-0.11-0.i586.rpm
```
1. With the Tizen emulator started or a Tizen device connected to the computer, log into the device as root by default:
```sh
sdb root on
```
1. Push the RPMs to the device:
```sh
sdb push crosswalk-${XWALK-STABLE-TIZEN-X86}.rpm /tmp
sdb push tizen-extensions-crosswalk-0.11-0.i586.rpm /tmp
```
1. Install the RPMs on the device:
```sh
sdb shell
# While in the shell on the Tizen device
rpm -i /tmp/crosswalk-${XWALK-STABLE-TIZEN-X86}-0.i586.rpm
rpm -i /tmp/tizen-extensions-crosswalk-0.11-0.i586.rpm
```
1. Additionally, if installing Crosswalk on the Tizen Emulator, you need to install an additional package:
```sh
sdb push crosswalk-emulator-support-${XWALK-STABLE-TIZEN-X86}.rpm /tmp
sdb shell
rpm -i /tmp/crosswalk-emulator-support-${XWALK-STABLE-TIZEN-X86}-0.i586.rpm
```
Please note that installing this package on an actual device can cause performance problems.
1. While still in the shell, you can launch xwalk: 
```sh
xwalk http://www.google.com
```
1. *Work in Progress* Installing Crosswalk will install an icon on the Tizen home screen.
