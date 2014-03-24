# Getting started with Crosswalk

This tutorial will get you up and running with Crosswalk as quickly as possible.

**This tutorial explains how to:**

1.  [Set up the tools needed to deploy Crosswalk applications](#documentation/getting_started/host_setup). This covers using both Linux and Windows as development hosts.
2.  Set up [Android](#documentation/getting_started/android_target_setup) and [Tizen](#documentation/getting_started/tizen_target_setup) devices (physical or emulated) so you can deploy Crosswalk applications to them.
3.  [Build a very simple HTML5 application](#documentation/getting_started/build_an_application).
4.  Run that application using stable releases of Crosswalk. The versions used for this tutorial are:
    *   [Crosswalk Android for x86, version ${XWALK-STABLE-ANDROID-X86}](#documentation/getting_started/run_on_android)
    *   [Crosswalk Tizen for x86, version ${XWALK-STABLE-TIZEN-X86}](#documentation/getting_started/run_on_tizen)

You will need to be comfortable using a command line to follow these steps. If you prefer to use a graphical integrated development environment (IDE), the free **Intel XDK** provides an alternative way to package applications with Crosswalk. See the [Intel XDK website](http://xdk-software.intel.com/) for more details.

**By the end of the tutorial**, you should understand the workflow for creating Crosswalk applications from your own HTML5 projects.

**This tutorial doesn't cover:**

*   How to write HTML5 applications. We use the simplest possible HTML5 application for this tutorial, so we can focus on the packaging and deployment aspects of Crosswalk.
*   How to use Crosswalk-specific APIs. The code in the tutorial should run perfectly well on any modern web browser, as well as on Crosswalk.
*   How to write [Crosswalk extensions](#wiki/Crosswalk-Extensions). This will be covered in other tutorials.
*   How to compile Crosswalk itself. This is covered in the [Contribute](#contribute) section.
