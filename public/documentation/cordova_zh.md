# 支持Cordova 4.0的Crosswalk
## 概述
随着[Cordova Android 4.0.0](http://cordova.apache.org/announcements/2015/04/15/cordova-android-4.0.0.html)引入了对插入式webview的支持，现在你可以方便地在你的Cordova应用上使用Crosswalk的webview。通过使用Crosswalk的webview插件，开发者可以享用远程调试的功能，前沿的HTML5特性，例如WebGL, WebAudio和WebRTC，以及在包括Android 4.0 Ice Cream Sandwich(ICS)在内的Android设备上性能的显著提升。
## 先决条件
请参见[Android平台指导](https://cordova.apache.org/docs/en/4.0.0/guide_platforms_android_index.md.html#Android%20Platform%20Guide)建立起针对Cordova应用开发的Android SDK环境。

确保你的系统是否已经安装了最新版本的[node.js](https://nodejs.org/)。
## 工作流程

1.  安装Cordova命令行工具（CLI）

        $ npm install -g cordova

    检查Cordova的版本是否大于等于5.0.0:

        $ cordova -v
        5.0.0

2.  创建一个Cordova样例应用，它可以被用来作为创建新项目的模板

        $ cordova create hello com.example.hello HelloWorld

3.  进入新创建的项目目录

        $ cd hello

    所有后续命令必须在该目录下运行（例如，`hello`）

4.  添加Android作为目标平台

        $ cordova platform add android

    这样便可以将Cordova Android平台（版本大于等于4.0.0）添加到应用中。

5.  安装Crosswalk的Webview插件

        $ cordova plugin add cordova-plugin-crosswalk-webview

    这条命令实现了将[Crosswalk WebView以Cordova插件形式](https://www.npmjs.com/package/cordova-plugin-crosswalk-webview/)添加进应用。

6. 　编译

        $ cordova build android

    它会自动从Crosswalk项目的下载网站(https://download.01.org/crosswalk/releases/crosswalk/android/)上取到稳定版本的Crosswalk WebView库，并且针对X86和ARM架构分别编译。例如，编译一个`HelloWorld`项目会生成：

        /path/to/hello/platforms/android/build/outputs/apk/android-x86-debug.apk
        /path/to/hello/platforms/android/build/outputs/apk/android-armv7-debug.apk

    至此，Crosswalk WebView库将会被嵌入到你的应用中。这大概会使APK的大小增加18MB。

7.  在模拟器上启动它

        $ cordova emulate android

    使用Crosswalk WebView启动一个Cordova应用的做法和直接使用Cordova应用差不多。上述命令行将会启动模拟器，安装应用的APK，然后启动你的应用。例如，在启动上述的Cordova样例应用后，你将在你的屏幕上看到下列的结果：

    <img src="/assets/cordova-in-emulator.jpg" />

    关于详细的命令行和选项，请参见[命令行接口](https://cordova.apache.org/docs/en/4.0.0/guide_cli_index.md.html#The%20Command-Line%20Interface)。


## 通过Crosswalk远程调控

Crosswalk的WebView允许远程调试Cordova应用，即使是较旧的Android设备上(4.0+)也可以。

你可以使用Google的Chrome浏览器远程调试任何运行在Crosswalk上的应用。在应用启动之后，进入“chrome://inspect”便可以看到设备上的所有可调试的应用。例如，当观察运行在Crosswalk环境下的模拟器中的`helloWorld`应用时：

<img src="/assets/cordova-devtools-inspect.jpg" />

点击`helloWorld`下方的"inspect"链接，将会打开一个Chrome的DevTools窗口。在JavaScript控制台选项中，你可以通过查看`navigator.userAgent`来确认应用当前是否运行在Crosswalk环境中。例如，该应用正运行在Crosswalk 13 (Chromium M42)中：

<img src="/assets/cordova-with-devtools.jpg" />

关于使用手册请参见[使用Chrome远程调试Android应用](https://developer.chrome.com/devtools/docs/remote-debugging)。


## (可选) 仅针对Android平台的工作流

这个工作流主要针对那些对前沿开发技术感兴趣的开发者。如果你想要一个稳定版本，可以参考Cordova CLI工作流。

需要[git](http://www.google.com/url?q=http%3A%2F%2Fgit-scm.com%2F&sa=D&sntz=1&usg=AFQjCNFOqwvh2KbuCJQUVsR5fW38FrTRTw)。

1.  Pull Crodova-android的资源库

        $ git clone https://github.com/apache/cordova-android.git

2.  安装plugman

        $ npm install -g plugman

    检测plugman版本是否大于等于0.22.17

        $ plugman -v
        0.23.1

    关于plugman的更多信息，请参见[使用Plugman管理Plugins](https://cordova.apache.org/docs/en/4.0.0/plugin_ref_plugman.md.html#Using%20Plugman%20to%20Manage%20Plugins).

4.  创建一个Cordova的应用样例

        $ /path/to/cordova-android/bin/create hello com.example.hello HelloWorld

5.  进入项目目录

        $ cd hello

    所有后续命令都需要在该项目目录下运行。

6.  安装Crosswalk WebView引擎插件

        $ plugman install --platform android \
         --plugin https://github.com/crosswalk-project/cordova-plugin-crosswalk-webview.git \
         --project .

    注意：因为在git记录中均是二进制文件，所以第一次的git克隆下载量大约为100MB。在低宽带连接的情况下，该过程可能会比较慢。

7.  编译Crosswalk WebView引擎

        $ ./cordova/build

    这样将会自动去Crosswalk项目发布网站(https://download.01.org/crosswalk/releases/crosswalk/android/)获取Crosswalk WebView库，并且可以为X86和ARM架构编译。例如，它会产生：

        /path/to/hello/build/outputs/apk/hello-x86-debug.apk
        /path/to/hello/build/outputs/apk/hello-armv7-debug.apk

8.  在模拟器上启动它

        $ ./cordova/run --emulator --nobuild

    这条命令启动了模拟器，安装了应用的APK并启动了你的应用。

关于cordova-android的更多命令，请参见[cordova-android README.md](https://github.com/apache/cordova-android).
