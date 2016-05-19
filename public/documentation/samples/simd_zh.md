# SIMD示例

<img class='sample-thumb' src='/assets/sampapp-icon-simd.png'>

这个示例展示了如何在Crosswalk应用中使用[SIMD](https://github.com/johnmccutchan/ecmascript_simd)。在这个示例中，一个Mandelbrot分形集合会在不同缩放比例下显示动画效果。

代码是由Intel的Peter Jensen和Ningxin Hu基于Mandelbrot动画演示开发而来。 

**从5.34.104.0版本以后，Crosswalk在x86架构上对SIMD有了原生支持。你将需要一个支持该版本或者更新版本的Crosswalk版本来运行这个演示；同时你也一个带有x86芯片集的设备（仿真或者真实的）。**

这个应用是[Crosswalk samples](https://github.com/crosswalk-project/crosswalk-samples)的一部分。

[开始](/documentation/getting_started_zh.html)页面中详细介绍了关于如何搭建Crosswalk环境的步骤。

## Android SIMD

一旦已经搭建好Crosswalk Android安装包之间的依赖，便可以遵循下列关于[如何在Android平台运行]的步骤(/documentation/android/run_on_android_zh.html)在Android环境下安装和运行SIMD样例。

在便捷方式下，你可以通过如下命令编译生成SIMD apk：

```sh
> <crosswalk-app-tools>/src/crosswalk-pkg --crosswalk=<crosswalk version> \
    --platforms=android <path to crosswalk-samples>/simd
```

    `<crosswalk-app-tools>` 表示你下载crosswalk-app-tools的目录。

    然后在Android平台上安装apk文件：

```sh
> adb install org.xwalk.simd*.apk
```

