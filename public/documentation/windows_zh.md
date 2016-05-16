
# 针对Windows平台的Crosswalk项目

针对Windows平台的Crosswalk项目为Windows操作系统上的web和hybrid应用提供了一个web运行时环境。这一节将描述如何创建并编译这些应用。

**这份指南解释如何：**

 1. [搭建Windows环境](/documentation/windows/windows_host_setup_zh.html)：你将要开发应用的机器。
在windows平台下只可能使用针对Windows的Crosswalk。
 2. [编译一个简单的HTML应用](/documentation/windows/build_an_application_zh.html)。
 3. [使用一个稳定版本的Crosswalk运行你的应用](/documentation/windows/run_on_windows_zh.html).

你将需要适应使用命令行完成这些步骤。

在文档中，你运行在shell脚本中的命令应该以 > 字符为前缀。在Windows系统中，你可以使用标准的Windows cmd shell（从Start菜单启动"cmd"）。

在这个指南的结尾，你应该理解了从你的HTML5项目创建Crosswalk应用的工作流。

**这份指南不包括**

*   怎样写HTML5应用。在这份指导中，我们使用了一个简单的HTML5应用，所以可以专注在Crosswalk的打包和部署方面。
* 怎样使用[专门针对Crosswalk的APIs](/documentation/apis/web_apis_zh.html#Experimental-APIs)。这份指导中的代码应该可以运行在任何现代的web浏览器以及Crosswalk中。
* 怎样编译Crosswalk自身。这部分在[Contribute](/contribute/index_zh)部分。
