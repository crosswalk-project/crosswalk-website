# 安卓版Crosswalk

本章介绍了如何利用Crosswalk为[安卓系统](http://developer.android.com/index.html)创建web以及混合式移动应用。

<strong>注意：Cordova开发者</strong>可以利用[Crosswalk WebView in Cordova 4.0](/documentation/cordova_zh.html)教程，轻松地让您在Cordova 4.0应用中使用高级Crosswalk webview。

**本章节介绍了以下及方面内容：**

1.  [搭建*主机*环境](/documentation/android/system_setup_zh.html)：主机，是您用来开发应用的机器。Crosswalk官方支持Windows系统和Linux系统。

2.  搭建[安卓平台](/documentation/android/android_target_setup_zh.html): 安卓平台，即用来运行Crosswalk应用的物理机或者虚拟机。

3.  [编译一个简单的HTML5应用](/documentation/android/build_an_application_zh.html)。

4.  [运行Crosswalk应用](/documentation/android/run_on_android_zh.html)：通过使用一个稳定版的Crosswalk发行版本。

5.  [部署应用到安卓应用商店](/documentation/android/deploy_to_android_store_zh.html)。

遵循以上步骤，您需要能够轻松地使用命令行。如果您更喜欢图形化集成环境(IDE), 免费的**Intel XDK**提供了一种打包Crosswalk安卓应用的途径。详情参见[Intel XDK官网](http://xdk-software.intel.com/)。

在本篇教程中，命令行是以`>`字符开头。在Windows系统中，您可以使用标准的Windows控制台程序，在Linux系统中，您可以使用bash shell。

**阅读完本教程**，您应该了解从HTML5到创建Crosswalk应用的流程。

**本教程没有包含以下内容：**

*   如何写HTML5应用。本教程使用了一个简单的HTML5应用作为例子，把主要篇幅放在了打包以及部署Crosswalk上面。
*   如何使用 [特定CrosswalkAPIs](/documentation/apis/web_apis.html#Experimental-APIs)。教程中的代码可以运行在任何浏览器上面，当然也能运行在Crosswalk上面。
*   如何编译Crosswalk。编译Crosswalk的教程在[贡献](/contribute)这一章。

