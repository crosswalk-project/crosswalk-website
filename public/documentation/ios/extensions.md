# Extensions

Extensions are a way to extend the ability of the Crosswalk runtime. By creating an extension, you can introduce new objects or functions to the Javascript world, and implement those functionalities based on the native codes, written in either Objective-C or Swift. An Crosswalk Extension consist of:

* Native source codes (in Objective-C or Swift):

  * An extension class derived from XWalkExtension.

* JavaScript wrapper (Optional):

  * A JavaScript file which exposes the native code to an app running on Crosswalk.

The Crosswalk for iOS runtime provides a way to auto generate JavaScript mapping codes, besides it also allows you to inject your own JavaScript codes for the extension.

## How to write an extension

In this section we are trying to show you the way to implement an extension. Here are the steps:

### Create a Cocoa Touch Framework Project

1. Open Xcode, in "File" -> "New" -> "Project...", Create a "Cocoa Touch Framework" project.

2. Select the project you've created in Navigator panel, choose "File" -> "Save As Workspace..." to create a workspace for the project;

3. Added the "XWalkView" project into this workspace;

4. In the "General" tab of the project's configuration, add "XWalkView" into the "Linked Frameworks and Libraries" to make the extension project link with XWalkView.

5. Open the **Info.plist** of this extension project, add a new row named `XWalkExtensions` with type `Dictionary`. This section defines the mapping information between app JavaScript runtime and the native extension class. The key/value defines like this:


  | Field | Type | Content | Example |
  | ------------- | ------------- |:--------------:| -----:|
  | key | String | _The exposed namespace in JavaScript runtime_ | `"xwalk.sample.echo"` |
  | value | String | _The native class name_ | `"EchoExtension"` |

### Implement The Extension

1. Create a subclass which derives from `XWalkExtension` with the name you defined in the **Info.plist**. Your subclass can be implemented with either Objective-C or Swift, and Swift is more recommended.

2. Write your own logic in this class.

Crosswalk for iOS provided a most convenient way to generate mappings between native and JavaScript, which means you don't need to write both native and JavaScript logic, then via low level postMessage way with JSON marshalling/unmarshalling support to communicate in between. With the mapping you only need to define the native properties and functions with the prefix that Crosswalk for iOS required, then the JavaScript implementation is automatically generated and inject into the JavaScript world, you can use it directly.

#### Native to JavaScript Mapping

##### Method Mapping

  * Native Method Prefix: `jsfunc_`

  * Native Definition: `func jsfunc_<functionName>(cid: UInt32, <params...>) {}`

  * JavaScript Mapping: `function <functionName>(<params...>) {}`

  * Example:


  | World | Definition |
  | ----- | ---------- |
  | Native | `func jsfunc_echo(cid: UInt32, message: String, callback: UInt32)` |
  | JavaScript | `function echo(message, callback)` |

  * Invoke from JavaScript:

    `echo.echo("Echo from native:", function(msg) {...})`

##### Property Manpping

  * Native Method Prefix: `jsprop_`

  * Native Definition: `dynamic var jsprop_<propertyName>: <Type>`

  * JavaScript Mapping: `<propertyName>`

  * Example:


  | World | Definition |
  | ----- | ---------- |
  | Native | `dynamic var jsprop_prefix: String = ""` |
  | JavaScript | `prefix` |

  * Invoke from JavaScript:

    `var prefix = echo.prefix;`
    `echo.prefix = 'HelloWorld';`

##### Constructor Mapping

  * Native Method Prefix: `none`

  * Native Definition: `initFromJavaScript`

  * JavaScript Mapping: `<Constructor>`

### Test your extension

1. Create the test app and workspace

2. embed the XWalkView framework and the extension framework into the app;

3. import or create the HTML5 resources;

4. Load the extension

## Extension API

### XWalkExtension

### XWalkDelegate

