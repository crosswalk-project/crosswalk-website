# 为Windows平台编译Crosswalk

1. 下载并安装[Microsoft Visual Studio 2015 update 2](https://www.visualstudio.com/en-us/downloads/download-visual-studio-vs.aspx)。目前不支持Visual Studio 2015 update 3，请在使用web-installer时注意不要自动升级到update 3。 如果你在编译21版本之前的Crosswalk，则需要使用Microsoft Visual Studio 2013。

2. Windows系统语言请设置为英文以避免安装路径中包含非英文字符的错误。

3. 仅支持Windows 7 x64或以上操作系统。不支持32位的x86操作系统。

4. 安装Visual Studio 2015 Update 2或以上版本时，请确认以下子选项都已被选择安装:
   * Visual C++ 包括MFC在内的3个子项都需要安装
   * Universal Windows Apps Development Tools > Tools
   * Universal Windows Apps Development Tools > Windows 10 SDK (10.0.10586)

5. 从[win-git](http://git-scm.com/download/win)处安装Windows平台的Git，并且确保将其添加到你的PATH中。设置你的用户环境变量。在Windows的开始菜单中，检索“环境变量”。或者，点击控制面板上的系统图标；然后进入高级系统设置中，点击环境变量按钮。你应该可以看到下列对话框：

   <img src="/assets/win8.png" style="display: block; margin: 0 auto"/>

6. 通过运行下列命令从Google上克隆depot_tools：

   ```
    >  git clone https://chromium.googlesource.com/chromium/tools/depot_tools.git
   ```

   确保你已经使用上文中描述的过程将`depot_tools`目录添加到你的PATH中。

7. 在环境变量的对话框中创建新的变量（使用"新建...按钮"）并且添加：

   *  `GYP_DEFINES`设置为`target_arch=x64`，因为Crosswalk仅支持64位编译。 
   *  `GYP_GENERATORS`设置为`ninja,msvs-ninja` (如果你希望在IDE内运行针对Windows平台的Crosswalk，这个将创建Visual Studio的解决方案）
   *  `DEPOT_TOOLS_WIN_TOOLCHAIN`设置为0(这将会通知Crosswalk使用你安装的Visual Studio)

8. 进入你希望检出Crosswalk Windows的目录，创建一个目录并进入，然后拉取源代码：

   ```
   > mkdir crosswalk-src
   > cd crosswalk-src
   > gclient config --name=src/xwalk git://github.com/crosswalk-project/crosswalk.git
   > gclient sync
   ```
   
这也许需要花费一段时间，下载大小超过了3GB。

## 创建Crosswalk

目前你有两种选择：

*  通过命令行创建
*  通过Visual Studio创建

### 命令行创建
为了从命令行创建，进入Crosswalk-src/src中并调用：
```
> ninja -C out/Release_64 xwalk or ninja -C out/Debug_x64 xwalk
```

### Visual Studio创建
为了生成解决方案和项目文件，进入crosswalk-src/src中并调用：

```
> python xwalk\gyp_xwalk
```

为了在Visual Studio中创建，从`crosswalk-src/src/xwalk`中打开xwalk.sln，然后便可以开始。选择一个目标，点击Build(例如`xwalk`或者`xwalk_builder`)。请注意xwalk.sln依赖所有的Chromium代码，因此xwalk.sln包含大约600个子工程，它们需要一个用于很多RAM的强劲的机器以便能够正确地处理。我们建议使用[Funnel extension](http://vsfunnel.com/)，它允许你选取你想要加载的子项目。
