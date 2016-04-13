
# 针对Windows平台的Crosswalk项目

针对Windows平台的Crosswalk项目为Windows操作系统上的web和hybrid应用提供了一个web运行时。这一节将描述如何创建并构建这些应用程序。

**这份指南解释怎样：**

 1. [搭建Windows主机](/documentation/windows/windows_host_setup.html)：你将要开发应用的机器。
在windows平台下只可能使用针对Windows的Crosswalk。
 2. [构建一个非常简单的HTML应用](/documentation/windows/build_an_application.html)。
 3. [使用一个稳定版本的Crosswalk运行你的应用](/documentation/windows/run_on_windows.html).

你将需要放松地使用命令行完成这些步骤。

在文档中，你应该运行在shell脚本中的命令应该以a > character为前缀。在Windows系统中，你可能使用标准的Windows cmd shell（从Start菜单启动"cmd"）。

在这个指导的结尾，你应该从你的HTML5项目创建Crosswalk应用的工作流。

**这份指导不包括**

*   怎样写HTML5应用。在这份指导中，我们使用了一个简单的HTML5应用，所以可以专注在Crosswalk的打包和部署方面。
*    怎样使用[Crosswalk-specific APIs](/documentation/apis/web_apis.html#Experimental-APIs)。这份指导中的代码应该可以运行在任何现代的web浏览器以及Crosswalk中。
*    怎样编译Crosswalk自身。这部分在[Contribute](/contribute)部分。
