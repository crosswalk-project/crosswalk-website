# 快速入门指南 

这份指南描述了如何创建一个Crosswalk的Web应用，以及如何使用Crosswalk XWalView的支持创建一个混合应用。

## 创建一个Crosswalk的Web应用

### 先决条件

1. [Xcode](https://developer.apple.com/xcode/)
2. [有效的Apple开发者账号](https://developer.apple.com/programs/)
3. [NPM](https://www.npmjs.com/)
4. [Crosswalk App Tool及其iOS后端](https://github.com/crosswalk-project/crosswalk-app-tools-ios)

### 创建

1. 创建一个演示应用 `org.example.foo`

   ```
   crosswalk-app create org.example.foo
   ```

2. 开发应用

   在`org.example.foo/app`目录下，模板文件 `icon.png`, `index.html` and `manifest.json` 已经被创建。

   `manifest.json` 文件是你的Web应用的主要配置接口。它是跨平台的。请参见页面[iOS Manifest](manifest_zh.html)获取更多关于iOS平台的信息。 

3. 编译应用

   ```
   cd org.example.foo
   crosswalk-app build
   ```

   如果编译成功，则会在目录中生成`foo.ipa`文件。

4. 安装应用

   打开iTunes，连接一个已经被注册在你的开发组内的iOS设备（iPhone或者iPad)。选择 `Application` 页面， 将 `foo.ipa` 拖入 `Applications` 列表，然后同步。`'foo.ipa` 将会被安装在iOS设备上。

## 创建一个Crosswalk的混合应用

### 创建应用工程

1. 打开Xcode。在工作目录下使用 "单一视图应用" 模板创建一个iOS应用项目。对于这个例子，我们使用"Echo"作为名字。为了方便可以使用Swift。

2. 使用CocoaPods将 `crosswalk-ios` 库和Crosswalk扩展包（如果需要）整合进演示应用中。关于CocoaPods的安装和使用，请参考[CocoaPods](https://cocoapods.org/)。

    在 `Echo` 目录下,创建文件 `Podfile`:

    ```
    cd Echo;
    touch Podfile;
    ```

    内容如下:

    ```
    platform :ios, '8.1'
    use_frameworks!
    pod 'crosswalk-ios', '~> 1.2'
    ```

    这个会告诉CocoaPods部署目标是iOS 8.1+环境并用最新版本的`1.2.x`来集成`crosswalk-ios`库。记住添加 `use_frameworks!` 因为 `crosswalk-ios` 一部分是用Swift编写并且它必须被创建成一个框架而不是一个静态库。

    安装 `Pods` 目标到项目。首次退出Xcode，然后在 `Echo` 目录下，使用命令：

    ```
    pod install
    ```

    安装以后，你将会发现 `Echo.xcworkspace` 已经生成，并且CocoaPods输出将通知你从现在开始使用这个工作空间来替代 `Echo.xcodeproj`。

   打开 `Echo.xcworkspace`，将会有`Echo`和`Pods`两个工程。

  * 为了快速测试，用[crosswalk-ios/AppShell/AppShell/ViewController.swift](https://github.com/crosswalk-project/crosswalk-ios/blob/master/AppShell/AppShell/ViewController.swift)上的相应文件替换自动产生的 `ViewController.swift` 文件内容，其中该文件已经为你搭建了一个 `XWalkView` 实例。

3. 在`Echo`目录下创建一个`www`文件夹，用于存放HTML5的文件和资源。

4. 创建文件`index.html`作为你的入口页，内容如下文所示：

    ```html
    <html>
      <head>
        <meta name='viewport' content='width=device-width' />
        <title>Echo demo of Crosswalk</title>
      </head>
      <body>
        <h2>Echo Demo of Crosswalk</h2>
      </body>
    </html>
    ```

5. 将`www`目录添加到工程。

    在`File` -> `Add Files to "Echo"...`中，选取`www`目录并选择`Create folder reference`。

6. 创建`manifest.plist`文件来描述应用的配置。 

    在`File` -> `New...` -> `File...`中，选取`iOS` -> `Resource` -> `Property List`。在`Echo`目录下创建一个名为`manifest.plist`的plist文件。这个资源配置文件将会在应用启动时被加载；

    添加一对记录，其中键值为`start_url`，字符串值为`index.html`。这个就是入口页。`XWalkView`将位于`www`目录下。   

      ![manifest1](https://cloud.githubusercontent.com/assets/700736/7226211/36a710c0-e779-11e4-9852-000d3bab8f57.png)

`Echo` 示例现在已经可以运行了。按下'Run'按键，它将会被部署并在你的iOS模拟器上运行。

这是创建Echo示例的第一步。如果你需要知道如何使用你的Crosswalk扩展包来创建一个混合项目，详细信息请参见页面[Extension](/documentation/ios/extensions_zh.html)。
