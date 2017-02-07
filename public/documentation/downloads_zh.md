# 下载

Crosswalk为多种操作系统和平台提供二进制文件。

通过Crosswalk编译web应用时，推荐使用基于NPM的[Crosswalk App Tools](/documentation/crosswalk-app-tools.html)，详细信息请参见[开始](/documentation/getting_started.html)页面。本该页面主要用于下载非稳定或者共享库版本。

推荐Cordova开发者使用[Crosswalk Webview插件程序](/documentation/cordova.html)，它将自动下载Crosswalk库。

| | 稳定版 (23.53.589.4)
| ------------ | -------------
| **Android (ARM + x86)** | [32-bit](https://download.01.org/crosswalk/releases/crosswalk/android/stable/latest/crosswalk-23.53.589.4.zip) / [64-bit](https://download.01.org/crosswalk/releases/crosswalk/android/stable/latest/crosswalk-23.53.589.4-64bit.zip)
| **Android webview (x86)** | [32-bit](https://download.01.org/crosswalk/releases/crosswalk/android/stable/latest/x86/crosswalk-webview-23.53.589.4-x86.zip) / [64-bit](https://download.01.org/crosswalk/releases/crosswalk/android/stable/latest/x86_64/crosswalk-webview-23.53.589.4-x86_64.zip)
| **Android webview (ARM)** | [32-bit](https://download.01.org/crosswalk/releases/crosswalk/android/stable/latest/arm/crosswalk-webview-23.53.589.4-arm.zip) / [64-bit](https://download.01.org/crosswalk/releases/crosswalk/android/stable/latest/arm64/crosswalk-webview-23.53.589.4-arm64.zip)

如需其他版本(包括beta或者canary版本)，请查阅https://download.01.org/crosswalk/releases/crosswalk/。

**发行说明**： [Crosswalk发行说明](https://github.com/crosswalk-project/crosswalk/blob/master/RELEASENOTES.md)
(功能，API变动，以及已知问题)。

另请参考：
* [开始](/documentation/getting_started.html)：如何使用这些下载版本。
* [质量一览表](/documentation/qa/quality_dashboard.html)：每个版本的测试覆盖率以及结果。
* [发布周期规则](https://github.com/crosswalk-project/crosswalk-website/wiki/release-methodology#version-numbers)：版本号中每个数字的意义。

## 质量总结

关于详细的质量总结，请参见页面[质量一览表](/documentation/qa/quality_dashboard.html)。

## 发布版本

Crosswalk有三个发布版本(为了增强稳定性)：

1. **稳定版**

   终端用户请使用稳定版。Crosswalk每发布一个稳定版，只有当稳定版遇到非常严重的bug或者安全问题，才会更新。

1. **测试版**

    具备下面情况的应用开发者请使用测试版：测试Crosswalk新版本中的变动对于应用的影响，或者想要在下一个Crosswalk稳定版中使用Crosswalk新的特性。新的测试版本发布是基于自动的基础验收测试(ABAT),人工测试结果和功能变动。Beta版本具备一定的稳定性；但是，它仍然只是一个Beta版，可能包含许多的bug。

1. **金丝雀版**

    金丝雀版本的发布很频繁（有时候是按天发布）。金丝雀版的发布是根据Crosswalk代码库中主分支的一些新的补丁而发布的，这些补丁通过了编译和基础验收测试。对于那些特别关心Crosswalk最新特性而不想自己编译的开发者而言，本版本是一个不错的选择。

更多的信息请参见[发布版本页面](https://github.com/crosswalk-project/crosswalk-website/wiki/Release-methodology)。

[Crosswalk版本编号页面](https://github.com/crosswalk-project/crosswalk-website/wiki/release-methodology#version-numbers)描述了Crosswalk版本号是如何分配的。
