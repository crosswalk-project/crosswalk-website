# Embedding Mode & Native APIs

Embedding mode enables application developers to embed the Crosswalk runtime in their own iOS applications. The main interface is `XWalkView`, which is derived from `WKWebView`. You can use it as you would use `WKWebView` or `UIWebView` to load web pages or the whole web application.  It also enables the Crosswalk extension and Cordova plugin support provided by the Crosswalk runtime.

## Creating an application with embedding mode

You may refer to the implementation in AppShell, which is a standalone application with a single view controller that wraps a Crosswalk XWalkView as its main view.

## Embedding API

### XWalkView

`XWalkView` is derived from `WKWebView` and extends `WKWebView` as follows:

```swift
public func loadExtension(object: AnyObject, namespace: String)
```

`loadExtension` is used to load the extension instance object in the Crosswalk runtime, and inject the instance object under `namespace` in the Javascript world.

```swift
public func loadFileURL(URL: NSURL, allowingReadAccessToURL readAccessToURL: NSURL) -> WKNavigation?
```

`loadFileURL` enables WKWebView to load URLs with the 'file://' scheme. This is a bug of WKWebView in iOS 8.  It can be removed once this issue fixed with later versions of the iOS SDK.

### XWalkExtensionFactory

This class is responsible for creating an extension instance with its name. The extension should be packed as a framework and placed in the `Frameworks` folder of the application.

```swift
public class func createExtension(name: String) -> AnyObject?
```

```swift
public class func createExtension(name: String, initializer: Selector, arguments: [AnyObject]) -> AnyObject?
```

```swift
public class func createExtension(name: String, initializer: Selector, varargs: AnyObject...) -> AnyObject?
```

