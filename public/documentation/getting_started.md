# Getting started with the Crosswalk Project

This tutorial will get you up and running with the Crosswalk Project as quickly as possible.

Note that the tutorial uses the latest stable version of Crosswalk for Android: newer versions may not work as explained below (for example, the structure of the Crosswalk Android download file may be different).

For Tizen, a [canary](/documentation/downloads.html#release-channels) version is used (there are no stable or beta downloads for Tizen).

**This tutorial explains how to:**

1.  Set up the *host*: the machine where you will be developing the application.
    * [Windows host](/documentation/getting_started/windows_host_setup.html)
    * [Linux host](/documentation/getting_started/linux_host_setup.html)

    Note that it may be possible to develop for Crosswalk on other platforms, but only Windows and Linux are officially supported.
2.  Set up *targets*: machines which will run the Crosswalk application, either physical or virtual.
    * [Android target](/documentation/getting_started/android_target_setup.html)
    * [Tizen target](/documentation/getting_started/tizen_target_setup.html)
3.  [Build a very simple HTML5 application](/documentation/getting_started/build_an_application.html).
4.  Run that application using stable releases of Crosswalk:
    *   [Run on Crosswalk Android, version ${XWALK-STABLE-ANDROID-X86}](/documentation/getting_started/run_on_android.html)
    *   [Run on Crosswalk Tizen for x86, canary version](/documentation/getting_started/run_on_tizen.html)
5.  For Android only:
    *   [Deploy the application to an Android app store](/documentation/getting_started/deploy_to_android_store.html)

You will need to be comfortable using a command line to follow these steps. If you prefer to use a graphical integrated development environment (IDE), the free **Intel XDK** provides an alternative way to package applications for Crosswalk Android. See the [Intel XDK website](http://xdk-software.intel.com/) for more details.

Throughout this tutorial, commands you should run in a shell are prefixed with a `>` character. On Windows, you can use the standard Windows console; on Linux, you can use a bash shell.

**By the end of the tutorial**, you should understand the workflow for creating Crosswalk applications from your own HTML5 projects.

**This tutorial doesn't cover:**

*   How to write HTML5 applications. We use the simplest possible HTML5 application for this tutorial, so we can focus on the packaging and deployment aspects of Crosswalk.
*   How to use Crosswalk-specific APIs. The code in the tutorial should run perfectly well on any modern web browser, as well as on Crosswalk.
*   How to write [Crosswalk extensions](https://github.com/crosswalk-project/crosswalk-website/wiki/Crosswalk-Extensions). This will be covered in other tutorials.
*   How to compile Crosswalk itself. This is covered in the [Contribute](/contribute) section.
