# 在安卓系统上运行Crosswalk应用

在安卓系统上，安装并运行基于Crosswalk的应用(在虚拟机或者物理机中都可以)。 如需帮助，参照[安卓配置](android_target_setup_zh.html)页面。

## 安装应用

安装在[编译app页面](build_an_application_zh.html)生成的APK，请参考下面命令：

* x86设备

      > adb install -r com.abc.myapp-0.1-debug.x86.apk

* ARM设备

      > adb install -r com.abc.myapp-0.1-debug.armeabi-v7a.apk

`-r`标签代表"reinstall"。第一次安装时这个标签不是必须的，但是对于后续的重新安装是非常有帮助的。

如果安装成功，您的应用图标将会出现在设备上。

<img src="/assets/xwalk-simple-on-android.png" style="display:block;margin:0 auto;">

