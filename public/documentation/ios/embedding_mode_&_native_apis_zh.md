# 嵌入式模式 & 本地API

嵌入式模式使得应用开发人员可以将Crosswalk运行时嵌入到他们自己的iOS应用中。主要接口便是`XWalkView`，它是由`WKWebView`演变而来。当你需要使用`WKWebView`或者`UIWebView`来加载页面或者整个web应用时可以使用它。它也支持Crosswalk运行时提供的Crosswalk扩展包和Cordova插件。

## 使用嵌入式模式创建一个应用

你可以参考AppShell的实现，它是一个独立应用程序，其中单视图控制器将Crosswalk XwalkView封装成它的主要视图。

## 嵌入式API

### XWalkView

`XWalkView`源自`WKWebView`并对`WKWebView`进行了如下扩展：

```swift
public func loadExtension(object: AnyObject, namespace: String)
```

`loadExtension`被用来加载Crosswalk运行时的扩展实例对象，并将实例对象注入到JavaScript下的`namespace`下。

```swift
public func loadFileURL(URL: NSURL, allowingReadAccessToURL readAccessToURL: NSURL) -> WKNavigation?
```

`loadFileURL`使得WKWebView可以加载'file://'模式的URL。这是一个存在与iOS 8中WKWebView的一个bug。一旦这个问题被后来版本的iOS SDK修复后便可以被移除。

### XWalkExtensionFactory

这个类负责创建命名的扩展实例。扩展实例应该被打包成框架并放在应用的`Frameworks`文件夹下。

```swift
public class func createExtension(name: String) -> AnyObject?
```

```swift
public class func createExtension(name: String, initializer: Selector, arguments: [AnyObject]) -> AnyObject?
```

```swift
public class func createExtension(name: String, initializer: Selector, varargs: AnyObject...) -> AnyObject?
```

