# Hello World sample

<img class='sample-thumb' src='/assets/sampapp-icon-helloworld.png'>

最简单的应用 -- Hello, World！这个示例通过提供一个manifest.json文件和最少的HTML文件集来从头开始创建一个应用。

这个应用是[Crosswalk samples](https://github.com/crosswalk-project/crosswalk-samples)的一部分。

[开始](/documentation/getting_started_zh.html)页面中详细介绍了关于如何搭建Crosswalk环境的步骤。

## Hello World on Android

一旦已经搭建好Crosswalk Android安装包之间的依赖，便可以遵循下列关于[如何在Android平台运行]的步骤(/documentation/android/run_on_android_zh.html)进行。

在便捷方式下，你可以通过如下命令来编译Hello World apk：

```sh
> <crosswalk-app-tools>/src/crosswalk-pkg --crosswalk=<crosswalk version> \
    --platforms=android <path to crosswalk-samples>/hello-world
```

`<crosswalk-app-tools>` 表示你下载crosswalk-app-tools的目录。

然后在Android平台上安装apk文件：

```sh
> adb install org.xwalk.helloworld*.apk
```

