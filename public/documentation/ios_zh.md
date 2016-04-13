#  针对iOS平台的Crosswalk项目

## 简介

针对iOS平台的Crosswalk为复杂的iOS本机和混合应用提供了一个web运行时。

* 扩展的WKWebView

  针对iOS的Crosswalk项目是架于WKWebView创建的，其中WKWebView是一种在iOS8中推出的一种现代的Webkit框架。我们使用Crosswalk扩展框架来扩展WKWebView。详细信息请参见[嵌式模式和本地API](/documentation/ios/embedding_mode_&_native_apis.html)。

* Crosswalk扩展框架

  扩展机制允许你扩展Crosswalk运行时的功能。你通过使用Swift或者Objective-C创建新的功能，然后将其暴露成JavaScript函数或者对象。JavaScript的存根代码可以基于本地接口自动生成。更多信息请参见[扩展机制](/documentation/ios/extensions.html)。

* Cordova插件支持

  为了利用已经存在的Cordova插件，一个Cordova扩展包被用来模拟Cordova环境。简单地将Cordova插件的源文件放到你的工程下并显示地注册插件类。更多信息请参见[Cordova插件支持](/documentation/ios/cordova_plugin_support.html)。

## 系统需求

主开发系统：

* <a href="https://developer.apple.com/ios/">iOS SDK 8+</a>
* <a href="https://developer.apple.com/ios/">Xcode 6+</a>

目标系统：

* iOS 8+

## 开速启动

根据[快速启动指南](/documentation/ios/quick_start_guide.html)，从头开始搭建一个简单的XWalkView应用。

## 演示

以下三个演示可用:

* [样例](https://github.com/crosswalk-project/crosswalk-ios/tree/master/Demos/Sample)

  一个展示了如何嵌入XwalkView简单的app，实现了Crosswalk扩展并配置扩展和应用。

* [Cordova插件设备演示](https://github.com/crosswalk-project/ios-extensions-crosswalk/tree/master/demos/CordovaPluginDeviceDemo)

  一个展示了如何使用Crosswalk Cordova扩展支持来整合一个Cordova插件的样例，同时也展示了 `apache.cordova.device` 插件的使用。

* [Cordova插件文件展示](https://github.com/crosswalk-project/ios-extensions-crosswalk/tree/master/demos/CordovaPluginFileDemo)

  从 https://github.com/Icenium/sample-file.git 上引入的另一个Cordova插件演示，展示了 `apache.cordova.file` 插件的使用。

注意：在项目的子模块初始化以后再尝试演示，使用：

  ```bash
  git submodule update --init --recursive
  ```

## 许可证

针对iOS平台的Crosswalk项目在[BSD许可](https://github.com/crosswalk-project/crosswalk/blob/master/LICENSE)下可用.
