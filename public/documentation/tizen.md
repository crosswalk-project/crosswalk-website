# Crosswalk on Tizen

This section describes how to create web and hybrid applications using the Crosswalk Project for the [Tizen operating system](http://developer.tizen.org).

For Tizen, a [canary](/documentation/downloads.html#release-channels) version is used (there are no stable or beta downloads for Tizen).

**This tutorial explains how to:**

1.  Set up the *host*: the machine where you will be developing the application.
    * [Windows host](/documentation/tizen/windows_host_setup.html)
    * [Linux host](/documentation/tizen/linux_host_setup.html)

    Note that it may be possible to develop for Crosswalk on other platforms, but only Windows and Linux are officially supported.

2.  Set up your [Tizen target](/documentation/tizen/tizen_target_setup.html): the device that will run the Crosswalk application, either physical or virtual.

3.  [Build a very simple HTML5 application](/documentation/tizen/build_an_application.html).

4.  [Run your application](/documentation/tizen/run_on_tizen.html) using Crosswalk

You will need to be comfortable using a command line to follow these steps. If you prefer to use a graphical integrated development environment (IDE), the free **Intel XDK** provides an alternative way to package applications for Crosswalk Android. See the [Intel XDK website](http://xdk-software.intel.com/) for more details.

Throughout this tutorial, commands you should run in a shell are prefixed with a `>` character. On Windows, you can use the standard Windows console; on Linux, you can use a bash shell.

**By the end of the tutorial**, you should understand the workflow for creating Crosswalk applications from your own HTML5 projects.

**This tutorial does not cover:**

*   How to write HTML5 applications. We use the simplest possible HTML5 application for this tutorial, so we can focus on the packaging and deployment aspects of Crosswalk.
*   How to use [Crosswalk-specific APIs](/documentation/apis/web_apis.html#Experimental-APIs). The code in the tutorial should run on any modern web browser, as well as on Crosswalk.
*   How to compile Crosswalk itself. This is covered in the [Contribute](/contribute) section.

