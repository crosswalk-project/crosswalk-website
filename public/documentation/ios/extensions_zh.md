# Extensions

Extension机制可以帮助你扩展Crosswalk运行时的能力。通过创建一个extension，你可以向Javascript中引入新的对象或者函数，并且用本地代码实现这些功能，可以选择使用Objective-C或者Swift。一个Crosswalk Extension包括：

* 本地源代码（使用Objective-C或者Swift）：

  * 一个源自XWalkExtension的扩展类。

* JavaScript包装（可选）：

  * 暴露本地代码给一个运行Crosswalk的app的一个Javascript文件。

针对iOS运行时的Crosswalk提供了一种自动产生JavaScript映射代码的方法并且允许你注入自己的JavaScript代码。

## 如何写一个extension

本小节将展示如何实现一个extension。

### 创建一个Cocoa Touch Frame项目

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

    在项目中安装`Pods`目标。首先停止Xcode，然后进入项目目录中，使用目录：

    ```
    pod install
    ```
    
    在安装结束后，你将会发现产生了一个`<projectName>.xcworkspace`，并且CocaPods的输出会通知你从现在开始开始使用这个工作空间来代替 `<projectName>.xcodeproj`。
   
4. 创建一个`extensions.plist`文件来定义应用JavaScript运行时和本地extension类的映射信息。

    在`File` -> `New...` -> `File...`下选取`iOS` -> `Resource` -> `Property List`。在项目目录下创建一个名为`extensions.plist`的plist文件。

    新添加命名为`XWalkExtensions`的一行，并且类型为`Dictionary`。键／值对定义如下： 

<table style="table-layout: auto;">
 <tr><th>字段</th><th>类型</th><th width="100%">内容</th><th>样例</th></tr>
 <tr><td>键</td><td>String</td><td>运行时的暴露的命名空间</td><td>`"xwalk.sample.echo"`</td></tr>
 <tr><td>值</td><td>String</td><td>本地类名</td><td>`"EchoExtension"`</td></tr>
</table>

### Extension的实现

1. 根据你在**extensions.plist**中定义的名字创建一个源自`XWalkExtension`的子类。其中你的子类可以用Object-C或者Swift实现，更加推荐使用Swift。

2. 在这个类中写你自己的逻辑。

针对iOS的Crosswalk提供了一个最方便的方法来产生本地和JavaScript之间的映射，这意味着你不需要同时写本地和JavaScript逻辑，然后通过JSON的编组／解组支持的底层postMessage的方式进行通信。通过映射，你仅需要根据针对iOS的CrossWalk要求的前缀来定义本地属性和函数，然后javaScript的实现是自动产生的而且注入到JavaScript环境中，你可以直接使用它。

#### 本地到JavaScript的映射

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

  * 本地方法前缀：`jsprop_`

  * 本地定义：`dynamic var jsprop_<propertyName>: <Type>`

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

  * 本地方法前缀：`none`

  * 本地定义：`initFromJavaScript`

  * JavaScript映射：`<Constructor>`

### 测试你的extension

1. 创建一个测试app和工作空间。

2. 嵌入XWalkView框架和extension框架到app中。

3. 引入或者创建HTML5资源。

4. 加载extension。


