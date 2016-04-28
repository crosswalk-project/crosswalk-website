# 部署到安卓商店

完成以上章节之后，Crosswalk应用就可以正式发布了。发布的方式取决于使用的打包方式(嵌入模式或者共享模式)。

本教程中，使用的是嵌入模式进行打包的。然而，用户也可能[使用共享模式打包](/documentation/android/run_on_android_zh.html#shared-vs-embedded-mode)。

下面将会简要介绍下如何发布两种打包方式下的APK。

## 发布嵌入模式Crosswalk应用

为了保证Crosswalk应用能运行在各种安卓机器上，建议开发者上传所有使用嵌入模式打包的应用。实际上，这需要开发者为一个嵌入模式下的web应用做下面的事情。

*   上传x86版本的Crosswalk应用到安卓商店。
*   上传ARM版本的Crosswalk应用到安卓商店。

谷歌应用商店支持同一个应用有多个APK可以上传，详情参考[文章](http://developer.android.com/google/play/publishing/multiple-apks.html)。

## 发布共享模式Crosswalk应用

对于使用共享模式打包的web应用，建议开发者：

*   上传x86版本的Crosswalk runtime APK到安卓商店。对于所有使用共享模式的应用，只需要上传一次Crosswalk runtime APK。
*   上传基于ARM的Crosswalk runtime APK到安卓商店。对于所有使用共享模式的应用，只需要上传一次Crosswalk runtime APK。
*   上传所有基于Crosswalk的应用到安卓商店。在不同平台下，这些应用会共享相应版本的runtime APK。

谷歌应用商店已经支持一个应用可以存在多个APK包（这里的Crosswalk runtime就是这样的一种"应用"，它被用来运行您的*真正的*web应用）：详情参见[文章](http://developer.android.com/google/play/publishing/multiple-apks.html)。
