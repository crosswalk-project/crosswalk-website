# 扩展

扩展机制可以帮助你扩展Crosswalk运行时环境的功能。通过创建一个扩展程序，你可以向Javascript环境中引入新的对象或者函数，并且用原生代码实现这些功能，可以选择使用Objective-C或者Swift。一个Crosswalk扩展包括：

* 原生代码（使用Objective-C或者Swift）：

  * 一个源自XWalkExtension的继承类。

* JavaScript包装（可选）：

  * 暴露原生代码给一个运行在Crosswalk中的app的Javascript文件。

针对iOS运行时的Crosswalk提供了一种自动生成JavaScript映射代码的方法并且允许你注入自己的JavaScript代码。

## 如何编写一个扩展程序

本小节将展示如何实现一个扩展程序。

### 创建一个Cocoa Touch Framework

1. 打开Xcode, 然后执行"File" -> "New" -> "Project..."。 创建一个"Cocoa Touch Framework" 项目。

2. 选择你已经在Navigator面板上创建的工程，然后选取 "File" -> "Save As Workspace..."，为该项目创建一个工作空间。

3. 使用CocoaPod整合`crosswalk-ios`库。关于CocoaPods的安装和使用，请参考：[CocoaPods](https://cocoapods.org/)。

    在项目目录中，创建一个文件命名为`Podfile`：

    ```
    touch Podfile;
    ```

    内容如下：

    ```
    platform :ios, '8.1'
    use_frameworks!
    pod 'crosswalk-ios', '~> 1.2'
    ```

    在项目中安装`Pods`目标。首先关闭Xcode，然后进入项目目录中，执行命令：

    ```
    pod install
    ```
    
    在安装结束后，你将会发现生成了一个`<projectName>.xcworkspace`，并且CocaPods的输出会通知你从现在开始使用这个工作空间来代替 `<projectName>.xcodeproj`。
   
4. 创建一个`extensions.plist`文件来定义应用JavaScript运行时和原生扩展类的映射信息。

    在`File` -> `New...` -> `File...`下选取`iOS` -> `Resource` -> `Property List`。在项目目录下创建一个名为`extensions.plist`的plist文件。

    新添加命名为`XWalkExtensions`的一行，并且类型为`Dictionary`。键／值对定义如下： 

<table style="table-layout: auto;">
 <tr><th>字段</th><th>类型</th><th width="100%">内容</th><th>样例</th></tr>
 <tr><td>键</td><td>String</td><td>JavaScript运行时的外部命名空间</td><td>`"xwalk.sample.echo"`</td></tr>
 <tr><td>值</td><td>String</td><td>原生类名</td><td>`"EchoExtension"`</td></tr>
</table>

### 扩展程序的实现

1. 根据你在**extensions.plist**中定义的名字创建一个源自`XWalkExtension`的子类。其中你的子类可以用Object-C或者Swift实现，推荐使用Swift。

2. 在这个类中写你自己的逻辑。

针对iOS平台的Crosswalk提供了一个最方便的方法来生成原生代码和JavaScript之间的映射，这意味着你不需要同时写原生代码和JavaScript逻辑，然后通过JSON序列化／反序列化支持的底层postMessage的方式进行通信。通过映射，你仅需要根据CrossWalk iOS要求的前缀来命名原生属性和方法，javaScript实现将会据此自动生成并在运行时自动注入，你可以直接使用它。

#### 原生代码到JavaScript的映射

##### 方法映射

  * 本地方法前缀：`jsfunc_`

  * 本地定义：`func jsfunc_<functionName>(cid: UInt32, <params...>) {}`

  * JavaScript映射：`function <functionName>(<params...>) {}`

  * 例如：

<table style="table-layout: auto;">
 <tr><th>World</th><th width="100%">定义</th></tr>
 <tr><td>本地</td><td>`func jsfunc_echo(cid: UInt32, message: String, callback: UInt32)`</td></tr>
 <tr><td>JavaScript</td><td>`function echo(message, callback)`</td></tr>
</table>

  * 调用JavaScript:

    `echo.echo("Echo from native:", function(msg) {...})`

##### 属性映射

  * 原生方法前缀：`jsprop_`

  * 原生定义：`dynamic var jsprop_<propertyName>: <Type>`

  * JavaScript映射： `<propertyName>`

  * 样例：

<table style="table-layout: auto;">
 <tr><th>World</th><th width="100%">定义</th></tr>
 <tr><td>本地</td><td>`dynamic var jsprop_prefix: String = ""`</td></tr>
 <tr><td>JavaScript</td><td>`prefix`</td></tr>
</table>

  * 调用JavaScript:

    `var prefix = echo.prefix;`
    `echo.prefix = 'HelloWorld';`

##### 构造器映射

  * 原生方法前缀：`none`

  * 原生定义：`initFromJavaScript`

  * JavaScript映射：`<Constructor>`

### 测试你的扩展程序

1. 创建一个测试应用和工作空间。

2. 嵌入XWalkView框架和扩展框架到应用中。

3. 引入或者创建HTML5资源。

4. 加载扩展程序。


