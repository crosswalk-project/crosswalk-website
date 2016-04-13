# Cordova插件支持

针对iOS的Crosswalk通过引入基于Crosswalk扩展机制的Cordova.framework，提供了对Cordova插件的支持。[Cordova extension](https://cocoapods.org/pods/crosswalk-extension-cordova)负责管理Cordova插件的生命循环，复制Cordova插件运行的环境，并且基于Crosswalk扩展框架提供Cordova插件需要的功能。你可以不用改变任何插件的代码，而利用已经存在的Cordova插件来扩展Crosswalk的功能。

## Cordova插件API

在将来，我们将会支持所有的Corvode插件API，目前可用的API如下：

###  被支持

* [org.apache.cordova.battery-status](https://github.com/apache/cordova-plugin-battery-status)
* [org.apache.cordova.console](https://github.com/apache/cordova-plugin-console)
* [org.apache.cordova.contacts](https://github.com/apache/cordova-plugin-contacts)
* [org.apache.cordova.device](https://github.com/apache/cordova-plugin-device)
* [org.apache.cordova.device-motion](https://github.com/apache/cordova-plugin-device-motion)
* [org.apache.cordova.device-orientation](https://github.com/apache/cordova-plugin-device-orientation)
* [org.apache.cordova.dialogs](https://github.com/apache/cordova-plugin-dialogs)
* [org.apache.cordova.file](https://github.com/apache/cordova-plugin-file)
* [org.apache.cordova.file-transfer](https://github.com/apache/cordova-plugin-file-transfer)
* [org.apache.cordova.geolocation](https://github.com/apache/cordova-plugin-geolocation)
* [org.apache.cordova.globalization](https://github.com/apache/cordova-plugin-globalization)
* [org.apache.cordova.inappbrowser](https://github.com/apache/cordova-plugin-inappbrowser)
* [org.apache.cordova.media](https://github.com/apache/cordova-plugin-media)
* [org.apache.cordova.media-capture](https://github.com/apache/cordova-plugin-media-capture)
* [org.apache.cordova.network-information](https://github.com/apache/cordova-plugin-network-information)
* [org.apache.cordova.vibration](https://github.com/apache/cordova-plugin-vibration)
* [org.apache.cordova.splashscreen](https://github.com/apache/cordova-plugin-splashscreen)
* [org.apache.cordova.statusbar](https://github.com/apache/cordova-plugin-statusbar)

### 在开发中

* [org.apache.cordova.camera](https://github.com/apache/cordova-plugin-camera)

由衷地欢迎您加入我们，为Crosswalk提供更多的Cordova插件贡献一份力量。

## 快启动

本小节，我们希望向你展示一个嵌入`org.apache.cordova.device`的简单的演示应用来展示一个使用了Cordova插件支持的应用的创建过程。

### 搭建DeviceDemo工程

#### 创建DeviceDemo工程

我们需要创建一个应用工程来当Cordova插件和web资源的宿主。

1. 创建`DeviceDemo`工程

  * 打开Xcode。使用"Single View Application"模板创建一个命名为"DeviceDemo"的iOS应用。选择"Swift"为了项目语言。

  * 为了快速演示，将自动生成的`ViewController.swift`文件替换成[crosswalk-ios/AppShell/AppShell/CordovaViewController.swift](https://github.com/crosswalk-project/crosswalk-ios/blob/master/AppShell/AppShell/CordovaViewController.swift)上相应的文件，其中它已经为你创建好了一个XWalkView实例。

  * 仅为了清除自动生成的`ViewController.swift`文件内容，将它们替换成[crosswalk-ios/AppShell/AppShell/CordovaViewController.swift](https://github.com/crosswalk-project/crosswalk-ios/blob/master/AppShell/AppShell/CordovaViewController.swift)。

2. 使用[CocoaPods](https://cocoapods.org/)将[crosswalk-ios](https://cocoapods.org/pods/crosswalk-ios)和[crosswalk-extension-cordova](https://cocoapods.org/pods/crosswalk-extension-cordova)整合到项目中。

  * 在`DeviceDemo`目录下，创建一个名为`Podfile`的文件：
  ```bash
  cd DeviceDemo
  touch Podfile
  ```

  内容如下：

  ```ruby
  platform :ios, '8.1'
  use_frameworks!
  pod 'crosswalk-extension-cordova', '~> 1.1'
  ```

  这会告诉CocoaPods部署目标是iOS 8.1+，并且将最新版本的`1.1.x`整合到crosswalk-extension-cordova`库中。记住要添加`use_frameworks!`，因为`crosswalk-ios`一部分是使用Swift语言编写，它必须被构建成一个框架而不是一个静态库。

  你可能注意到我们还没有添加`crosswalk-ios`的依赖。这个并不是必须的因为`crosswalk-extension-cordova`依赖于`crosswalk-ios`并且CocoaPods会自动处理依赖关系。

  * 安装`Pod`目标到项目。

  首先停止Xcode，然后进入`DeviceDemo`目录，使用下列命令安装pods： 

  ```bash
  pod install
  ```

  然后你将发现生成一个`DeviceDemo.xcworkspace`，并且CocoaPods会通知你从现在开始使用这个工作空间来代替`DeviceDemo.xcodeproj`。

3. 打开`DeviceDemo.xcworkspace`，尝试构建`DeviceDemo`应用程序目标，查看其是否正常工作。

#### Import `org.apache.cordova.device` Plugin Resources

下一步是从 [org.apache.cordova.device](https://github.com/apache/cordova-plugin-device) 将本地和html5资源引入到项目中。

1. 克隆org.apache.cordova.device

  ```bash
  git clone https://github.com/apache/cordova-plugin-device.git
  ```

2. 向应用中添加插件资源文件的本地部分

  * 选择`DeviceDemo`项目， 执行`File` -> `Add Files to "DeviceDemo"...`,

  * 从`cordova-plugin-device/src/ios`中将`CDVDevice.h`和`CDVDevice.m`添加到项目中。

  * 注意：如果你需要创建一个链接头部Xcode会通知你，然后创建即可。

3. 向应用中添加HTML5部分的资源

  ```bash
  cd DeviceDemo; mkdir -p www/plugins/org.apache.cordova.device/www/
  cd www; cp ../../cordova-plugin-device/www/device.js plugins/org.apache.cordova.device/www
  vim plugins/org.apache.cordova.device/www/device.js
  ```

  因为`device.js`仅仅提供了`device`对象内容的实现，我们需要在将其引入到web应用的内容前，将其包装成一个cordova模块。（我们将会提供一个脚本，实现本步自动化）。

  ```javascript
  cordova.define("org.apache.cordova.device.device", function(require, exports, module) {
    // The original content of device.js goes here.
    ...
  });
  ```

4. 创建Cordova Extension需要的配置

  我们还需要添加一个配置文件来告诉Cordova Extension怎样加载Cordova插件，为了提供跟Crodova工程相同的方式，这里我们模拟Cordova工程的做法。在`www`目录下：


  ```bash
  touch cordova_plugins.js
  vim cordova_plugins.js
  ```

  内容如下：

  ```javascript
  cordova.define('cordova/plugin_list', function(require, exports, module) {
  module.exports = [
      {
          "file": "plugins/org.apache.cordova.device/www/device.js",
          "id": "org.apache.cordova.device.device",
          "clobbers": [
              "device"
          ]
      }
  ];
  module.exports.metadata =
  // TOP OF METADATA
  {
      "org.apache.cordova.device": "0.2.12"
  }
  // BOTTOM OF METADATA
  });
  ```

#### 创建web应用

在引入设备插件以后，我们需要利用设备插件的能力来创建web应用。

1. 在`www`目录下创建`index.html`。

  ```bash
  touch index.html
  vim index.html
  ```

  内容如下：

  ```html
  <!DOCTYPE html>
  <html>
  <head>
      <meta charset="utf-8"/>
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
      <style>
          table, th, td {
              border: 1px solid black;
              border-collapse: collapse;
          }
          th, td {
              padding: 5px;
          }
      </style>
      <title>Cordova device API plugin demo</title>
  </head>
  <body>
      <p>This is the demo page for testing org.cordova.plugin.device.</p>
      <table id='table' style='width:100%'>
          <caption>Device Properties</caption>
          <thead>
              <tr>
                  <th>Name</th>
                  <th>Value</th>
              </tr>
          </thead>
      </table>
      <script>
          document.addEventListener("deviceready", onDeviceReady, false);
          function onDeviceReady() {
              var table = document.getElementById('table');
              for (var propName in device) {;
                  if (typeof(device[propName]) == 'function') {
                      continue;
                  }
                  var row = document.createElement('tr');
                  var name = document.createElement('td');
                  var value = document.createElement('td');
                  name.innerHTML = propName;
                  value.innerHTML = device[propName];
                  row.appendChild(name);
                  row.appendChild(value);
                  table.appendChild(row);
              }
          }
      </script>
  </body>
  </html>
  ```

　在这个演示中，我们将监听`deviceready`事件并切从`device`对象检索系统信息，然后将他们添加到表格中。`device`对象是由`org.apache.cordova.device`插件提供并且系统信息从本地收集。

2. 将web资源嵌入项目

  我们需要将web资源（包括插件和app资源）添加到项目中，并且打包成一个本地的iOS应用。

  * 选择`DeviceDemo`项目，然后执行`File` -> `Add Files to "DeviceDemo"...`，

  * 将`www`目录添加到项目中，同时选择`Create folder references`选项。 

3. 为项目创建`manifest.plist`文件

  `manifest.plist`描述了应用中嵌入的extension的信息，以及相关的Cordova插件信息。

  * 选择`DeviceDemo`项目，执行`File` -> `New` -> `File`，

  * 选取`iOS` -> `Resource` -> `Property List`，输入名字： `manifest`。

  然后添加：

  * String类型的`start_url`和`index.html`的值。
  * Array类型的`xwalk_extensions`并且添加：
    * `xwalk.cordova`作为记录。
  * Array类型的`cordova_plugins`并将Dictionary类型中的一项添加进去。在dictionary类型添加：
    * `class`作为key，同时`CDVDevice`作为value；
    * `name`作为key，同时`Device`作为value；

4. 在`iOS Device`设备上构建并且运行`DeviceDemo`应用，查看其是否能够正常运行。 

  现在这个演示应该可以使用了。

## 演示

在[ios-extension-crosswalk](https://github.com/crosswalk-project/ios-extensions-crosswalk)项目下的[demos](https://github.com/crosswalk-project/ios-extensions-crosswalk/tree/master/demos)的文件夹中，有两个demo可以展示Cordova插件的使用。你可以试用一下来了解它们如何工作。　　　
