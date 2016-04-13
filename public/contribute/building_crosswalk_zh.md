# 创建Crosswalk

## 环境

1.  通过下列指导在Chromium wiki上创建你的构建环境。

    <ul>
    <li><a href="http://www.chromium.org/developers/how-tos/build-instructions-windows">Building on Windows</a></li>
    <li><a href="http://code.google.com/p/chromium/wiki/LinuxBuildInstructionsPrerequisites">Building on Linux</a></li>
    <li><a href="https://code.google.com/p/chromium/wiki/MacBuildInstructions">Building on Mac</a></li>
    </ul>

2.  如果你将要构建Android平台的Crosswalk，你需要安装额外的必需包，参考页面[创建Android平台的Chrome](http://code.google.com/p/chromium/wiki/AndroidBuildInstructions)。

    [section below](#contribute/building_crosswalk/Building-Crosswalk-for-Android)
    总结了包含的步骤。

3. [depot_tools](http://www.chromium.org/developers/how-tos/install-depot-tools)
包含下列用于管理和构建Crosswalk的工具：

    <ul>
    <li><code>gclient</code> 管理代码和以来关系。</li>
    <li><code>Ninja</code>是一个在大多数平台上被推荐使用的用于构建Crosswalk的工具。
    它的<a href="https://chromium.googlesource.com/chromium/src/+/master/docs/ninja_build.md">网站</a>
    包含了详细的使用说明。</li>
    </ul>

## 协议创建

如果你在使用一个网络协议，你需要确保你的换将被正确地创建，并且变量设置合适。在Linux环境下，你至少需要做以下几步：

1. 设置`http_proxy`和`https_proxy`环境变量:

    ```
    export http_proxy=http://example-host:port
    export https_proxy=http://example-host:port
    ```

1. 在某个地方创建一个Boto配置文件，包含以下内容：

    ```
    [Boto]
    proxy = example-host
    proxy_port = port number
    ```

    After that, point the `NO_AUTH_BOTO_CONFIG` environment variable to the file
    you created:

    ```
    export NO_AUTH_BOTO_CONFIG=/path/to/boto-file
    ```

1. 创建你的版本控制协议设置。至少需要在`[global]`中设置`http-proxy-host`和`http-proxy-port`。在Linux环境下，文件被称为`.subversion/servers`，在Windows环境下是`C:\Users\<YOUR_USER>\AppData\Roaming\Subversion\servers`。

## 下载Crosswalk资源

### 开始之前： Android

如果你为Android平台构建Crosswalk，你首先应该设置
`XWALK_OS_ANDROID`环境变量：

    export XWALK_OS_ANDROID=1

你必须这样做，否则你的清单将不会包含某些对于Android构建必要的组件。

如果你检查源没有设置，你可以在后面设置并且再执行一次`gclient sync`，把其余的针对Android的组件包含进来。

### 获取资源

1.  创建一个资源目录：

        cd <home directory>
        mkdir crosswalk-src
        cd crosswalk-src

2.  自动生成gclient的配置文件(`.gclient`)：

        gclient config --name=src/xwalk \
          https://github.com/crosswalk-project/crosswalk.git

    你可以将`https://`替换成`git://`或者`ssh://git@`，依赖于你的代理需求和你是否想使用你的GitHub凭据。

3.  从包含`.gclient`文件的相同目录下，获取资源：

        gclient sync

### 追踪一个不同的Crosswalk分支

如果你想要追终一个不是`master`的分支（例如beta或者一个固定分支），存在两种你可以使用的方式，描述如下。

*在初次checkout之前设定分支*

如果你还没有Crosswalk的源代码，则将你需要checkout的分支的URL传递给`gclient config`调用。例如，为了追终_crosswalk-2_ branch:

    gclient config --name=src/xwalk \
      git://github.com/crosswalk-project/crosswalk.git@origin/crosswalk-2

*为一个已经存在的checkout改变分支*

如果你已经克隆了一个Crosswalk，并且想改变追终的分支，你需要开启一个新的git分支，然后编辑你的`.gclient`文件。

例如，假设你想要追终_crosswalk-2_ branch。
首先，在你的Crosswalk资源库中开启一个新的分支：

    cd /path/to/src/xwalk
    git checkout -b crosswalk-2 origin/crosswalk-2

然后，编辑你的`.gclient`文件（上面产生的）并且改变`url`记录。它看上去应该像这样：

```python
"url": "git://github.com/crosswalk-project/crosswalk.git@origin/crosswalk-2",
```
然后，再次同步你的代码：

    gclient sync

## 为桌面构建Crosswalk

这些指导覆盖到从构建Crosswalk到在桌面环境(Windows, Linux, or Mac OS)下运行。

1. `gyp`是被用于生成Crosswalk工程的工具。这些工程然后会被用作实际代码编译的基础。

   为了构建工程，进入`src`目录并运行

   在Linux, Mac操作系统：

   ```
   export GYP_GENERATORS='ninja'
   python xwalk/gyp_xwalk
   ```

   在Windows：

   ```
   set GYP_GENERATORS=ninja
   python xwalk\gyp_xwalk
   ```

   如果你想要创建一个Visual Studio，然后便可以在Visual Studio下构建／编辑，你应该设置GYP_GENERATORS如下：
   ```
   set GYP_GENERATORS=ninja,ninja-msvs
   ```
   
   注意：在Windows下，`set`在当前的cmd窗口下有效。`setx`使得在*未来的*cmd窗口下永久有效，类似于在环境变量对话框内设置变量。

2. 现在你已经使用`gyp`构建了项目，并且已经准备实际编译。为了构建Crosswalk启动器（可以运行一个web应用）:

        ninja -C out/Release xwalk

### 测试一个桌面构造

启动一个Crosswalk应用最简单的方式是使用一个应用的manifest作为参数的`xwalk`命令：
with an application’s manifest as argument:

    xwalk /path/to/manifest.json

Crosswalk将会解析manifest，并且从已经在`start_url`中定义好的入口处启动应用。

如果你没有任何的HTML应用来测试，
[Crosswalk 样例](/documentation/samples.html)包含一些你可以试试。 

## 为Android构建Crosswalk

如上面所提到的，Android平台的Crosswalk的构建过程主要基于Chrome的过程，所以需要确定你
[对其很熟悉](http://code.google.com/p/chromium/wiki/AndroidBuildInstructions)。

1.  为Android平台的Crosswalk构建安装依赖：

        ./build/install-build-deps-android.sh

    注意这个需要你的系统对`apt-get`的支持。 
    如果你的系统中没有`apt-get` (例如Fedora Linux)，下面的命令可以安装依赖：

    ```
    sudo yum install alsa-lib-devel alsa-lib-devel.i686 bison \
    cairo-devel.i686 cups-devel cups-devel.i686 dbus-devel \
    dbus-devel.i686 elfutils-libelf-devel.i686 elfutils-libelf-devel.x86_64 \
    expat-devel expat-devel.i686 fontconfig-devel.i686 freetype-devel.i686 \
    gcc-c++ gconf GConf2-devel GConf2-devel.i686 gconf-devel git \
    glib2-devel.i686 glibc-devel.i686 gperf gtk2-devel harfbuzz-devel.i686 \
    krb5-devel.i686 libcap-devel libcap-devel.i686 libcom_err-devel.i686 \
    libgcrypt-devel libgcrypt-devel.i686 libgnome-keyring-devel \
    libgpg-error-devel.i686 libgudev1-devel libpciaccess-devel \
    libstdc++.i686 libX11-devel.i686  libXcomposite-devel.i686 \
    libXcursor-devel.i686 libXdamage-devel.i686 libXext-devel.i686 \
    libXfixes-devel.i686 libXi-devel.i686 libXrandr-devel.i686 \
    libXrender-devel.i686 libXScrnSaver-devel libXtst-devel \
    libXtst-devel.i686 lighttpd nss-devel nss.i686 pango-devel.i686 \
    pciutils-devel pciutils-devel.i686 pulseaudio-libs-devel python-pexpect \
    svn systemd-devel systemd-devel.i686 xorg-x11-server-Xvfb \
    xorg-x11-utils zlib-devel.i686 zlib.i686
    ```

2.  配置 gyp。

    gyp是在Chromium中使用的构造系统生成器，用于为Ninja和其他系统生成实际的构建文件。为了生成Android的构造文件还需要一些变量。

    在包含你的`.gclient`文件（在`src/`之上）的目录下存在一个名为`chromium.gyp_env`的文件。

        echo "{ 'GYP_DEFINES': 'OS=android target_arch=ia32', }" > chromium.gyp_env

    如果你在为ARM构建，使用`target_arch=arm`代替上述的
    `target_arch=ia32`。

3.  配置你的启动来生成Crosswalk工程。

        export GYP_GENERATORS='ninja'
        python xwalk/gyp_xwalk

4.  为了通过工具和嵌入式库，构建针对Android平台的主要Crosswalk包的内容，你可以运行：

        ninja -C out/Release xwalk_core_library

    这样将会在`out/Release`下创建一个名为`xwalk_core_library`的目录，在一个项目中嵌入Crosswalk时需要使用的特定体系结构的Crosswalk库。

    为了构造Crosswalk的运行时库APK（它可以被用作使用Crosswalk共享模式构建的应用的运行时），运行：

        ninja -C out/Release xwalk_runtime_lib_apk

    这将会在`out/Release/apks`下生成一个名称为`XWalkRuntimeLib.apk`的APK。

    为了构造一个简单的web应用APK（为了快速安装／目标测试），执行：

        ninja -C out/Release xwalk_app_template_apk

    这将会在`out/Release/apks`下生成一个名称为`XWalkAppTemplate.apk`的APK。

    最终，你也可以通过使用Gradle和Maven构建AAR文件。下列命令将会通过主要的Crosswalk库创建一个特定体系结构的AAR文件，同时也会创建一个Crosswalk共享模式的系统结构独立的ARR。

        ninja -C out/Release xwalk_core_library_aar xwalk_shared_library_aar

    ARR文件都将位于`out/Release`目录下。

## 运行测试套件

如果你有兴趣运行Crosswalk的测试套件，你可以在`src`目录下构建测试套件：

    ninja -C out/Release xwalk_unittest
    ninja -C out/Release xwalk_browsertest

然后运行它们：

    ./out/Release/xwalk_unitttest
    ./out/Release/xwalk_browsertest
