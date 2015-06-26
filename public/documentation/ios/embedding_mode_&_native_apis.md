# Embedding Mode & Native APIs

The embedding mode enables the application developers to embed the Crosswalk runtime in their own iOS applications. The main interface is `XWalkView`, which is derived from `WKWebView`. You can use it as the way you use a `WKWebView` or `UIWebView` to load web pages or the whole web application, meanwhile utilizing the Crosswalk extension and Cordova plugin support provided by the Crosswalk runtime.

## Creating an application with embedding mode

You may refer to the implementation in AppShell, which is a standalone application with a single view controller that wraps a Crosswalk XWalkView as its main view.

## Embedding API

### XWalkView

The `XWalkView` is derived from `WKWebView` and extends `WKWebView` as follows:

```swift
public func loadExtension(object: AnyObject, namespace: String)
```

`loadExtension` is to load the extension instance object in the Crosswalk runtime, and inject the instance object under `namespace` in the Javascript world.

```swift
public func loadFileURL(URL: NSURL, allowingReadAccessToURL readAccessToURL: NSURL) -> WKNavigation?
```

`loadFileURL` is to fix the issue that WKWebView cannot load URL with 'file://' scheme. This is a bug of WKWebView of iOS 8, and should be removed once it is fixed with the future release of iOS SDK.

### XWalkExtensionFactory

This class is responsible for creating an extension instance with its name. The extension should be packed as a framework and placed into the `Frameworks` folder in the application.

```swift
public class func createExtension(name: String) -> AnyObject?
```

```swift
public class func createExtension(name: String, initializer: Selector, arguments: [AnyObject]) -> AnyObject?
```

```swift
public class func createExtension(name: String, initializer: Selector, varargs: AnyObject...) -> AnyObject?
```

