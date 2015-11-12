# .NET Extensions

Crosswalk .NET extensions are written in .NET, and have access to the standard .NET APIs. It allows exposing these APIs back to JavaScript in your Crosswalk application.

## The .NET extensions system
Crosswalk for Windows can load several extensions from a given path, thus you can expose multiple .NET APIs back to your Crosswalk application. Crosswalk for Windows expects each .NET extension to be a multi-arch DLL file where several entry points are defined so communication can happen between Crosswalk and the extension. The .NET extensions are loaded in a separate process (for security and stability reasons).

There are two main classes you should know about while writing a .NET extension:

*   `XWalkExtension` is instantiated once and used by Crosswalk to get information about the extension itself: the JavaScript API and the extension name (used as the prefix in JavaScript).
*   `XWalkExtensionInstance` may be created multiple times depending on browser context (such as iframes). Each instance is responsible of handling the messages from Crosswalk and responding back to Crosswalk.

Each .NET extension is required to ship with a bridge which is provided by Crosswalk. Simply copy it and rename it according to your .NET extension DLL name with the `_bridge` suffix.  For example, if your extension is called `echo_extension.dll`, the bridge should be `echo_extension_bridge.dll`.

## <a class='doc-anchor' id='Writing-your-Net-Extension'></a>Writing your .NET Extension
1. Download and install [Visual Studio 2013](https://www.visualstudio.com/en-us/downloads/download-visual-studio-vs.aspx). (2015 has not been tested at the moment).

2. Download and install the [Crosswalk .NET Extensions Visual Studio Template](https://visualstudiogallery.msdn.microsoft.com/51e648be-c91a-40fa-9d13-8f49ec134e86). You can alternatively search for it in Visual Studio Extensions and Updates dialog.

   This template will help you to quickly create an extension in Visual Studio.

3. Open Visual Studio and click: File -> New Project

4. In the new dialog, select: “Visual C#” and scroll down to the bottom of the list where you can find the Crosswalk .NET Extensions Sample template.
    
   <a href="/assets/win5-new-project.png"><img src="/assets/win5-new-project.png" style="display: block; margin: 0 auto"/></a>

5. Enter the name of your .NET extension, set the location and give a name to the Visual Studio solution

6. Click OK and you should be all set up:

   <a href="/assets/win6-visual-studio.png"><img src="/assets/win6-visual-studio.png" style="display: block; margin: 0 auto"/></a>

In the `XWalkExtension` class there are two key methods you should implement:

*   `ExtensionName()`: This is the prefix of your extension in JavaScript and how you are expected to call the API provided by your extension. For example you can return “echo” and all your call in JavaScript will be prefixed with “echo.”
*   `ExtensionAPI()`: This is the API you’re going to expose to JavaScript. The image below is an example of how this API is used with a simple “Echo” app.

   <a href="/assets/win7-extension-config.png"><img src="/assets/win7-extension-config.png" style="display: block; margin: 0 auto"/></a>

### Writing the Extension API

*   Any properties (methods, objects, constants, etc.) you want to expose as the JavaScript API for your extension should be appended to the exports object inside the JavaScript API string. This has a similar role to the exports object in nodejs modules, defining the public face of the API. Any other variables not attached to exports are only scoped to this file and won't pollute the web application's global scope. In this simple `echo` example we have two methods exposed : `echo` and `syncEcho`. In the JavaScript side they will be called like this: `echo.echo(“test”)` and `echo.syncEcho(“test”)`
*   `extension` object is set by the .NET extension framework and contains methods to communicate with the .NET side of your extension: `extension.PostMessage()` and `extension.internal.sendSyncMessage()`. The former is simple, it sends a message from JavaScript to .NET which will be responsible to handle it. `sendSyncMessage` is similar however it is a synchronous call (and thus blocking) which means that it will not return until the .NET side gives an answer.
*   `extension.setMessageListener()` sets a function to invoke for each message returned by the .NET side of the extension. In this simple example the `echo()` function takes a callback from the caller to be called when the .NET echoed our message. In that case the listener is called when .NET replied and the callback will be invoked.

### Implementing XWalkExtensionInstance class

*   About the constructor: You should not modify its signature for the .NET extension to be loaded. It take a parameter `dynamic native` which is going to be used to communicate back to the Crosswalk extension system.
*   `HandleMessage(String message)` is going to be called for every message initiated from JavaScript, so every calls to `extension.PostMessage()` that we have from above. The message parameter contains the message passed from JavaScript.
*   `HandleSyncMessage(String message)` is equal to `HandleMessage` but receives the calls from JavaScript `sendSyncMessage()`. You’re expected to set a reply to JavaScript in that function because until you do so, the JavaScript execution is blocked.
*   Communicating back to Javascript is done by using `PostMessageToJS` and `SendSyncReply` on the native handle. `PostMessageToJS` simply sends an answer to JavaScript in this simple example will be catched by the message listener in our JavaScript API (which will call the callback). `SendSyncReply` should be called from within `HandleSyncMessage` and tell the .NET extension framework that you are done with your synchronous call and JavaScript execution can be resumed. The return value of your JavaScript sync call is set by the `SendSyncReply` method parameter.

When you’re done, click on the Build menu and select 'Build Solution' to create your extension DLL.

### Advanced JavaScript API in your .NET extension

The example in <a href="#Writing-your-Net-Extension">Writing your .NET Extension</a> above is a pretty simple and trivial API in JavaScript.

It’s very common that your JavaScript API have several methods calling `PostMessage` therefore in your .NET extension `handleMessage` can get tricky. One way to handle this complexity is to pass through your .NET extension JSON-based messages where you can pass an ID and the message. The ID is going to be used to identify from which Javascript API the message originated and then any other attributes may be the parameters passed along. Once received in .NET `handleMessage` it’s straightforward to deserialize the JSON received. Here is how it would look like on the JavaScript side:

```
// private method for building the message object and converting it
// to a JSON string for transfer to the .NET part of the extension
var messageToJson = function (method, message) {
  var obj = {
    id: '' + method,
    content: message
  };

  return JSON.stringify(obj);
};

exports.echoAsync = function (message, callback) {
  extension.PostMessage(messageToJson(“async”, message));
  ...
}

exports.echo2 = function (message, callback) { 
  extension.PostMessage(messageToJson(“echo2”, message));
  ...
}
```

On the .NET side see [https://msdn.microsoft.com/en-us/library/bb412179(v=vs.110).aspx](https://msdn.microsoft.com/en-us/library/bb412179(v=vs.110)

You may also want to implement an API based on [Promises](http://promises-aplus.github.io/promises-spec/). In the previous example we showed a callback mechanism but it has to be managed carefully: if callbacks are nested inside callbacks inside callbacks etc., it can lead to the so-called [pyramid of doom](https://github.com/survivejs/js_tricks_and_tips/blob/master/common_problems/pyramid.md).


### Packing your extension with crosswalk-app-tools
The ability to package your .MSI installer with your .NET extension is being implemented.  This section will beupdated when the ability is complete and part of the Crosswalk packaging tool.

### Testing your .NET Extension using Crosswalk for Windows binary
After downloading Crosswalk for Windows and unzipping it you can easily try your extension.

1.  Make sure you copy xwalk_extension_bridge.dll in your .NET DLL extension folder
2.  Rename `xwalk_extension_bridge.dll` so that it looks identical as your extension DLL name with the suffix `_bridge` as the only difference. Here is an example:

   <img src="/assets/win9-extension-net-bridge.png" style="display: block; margin: 0 auto"/>

3.  Inside your command line, navigate to the path where you unzipped Crosswalk and launch it using the method described in <a href="/documentation/windows/run_on_windows.html#Run-using-Crosswalk-binary-directly">Run using Crosswalk binary directly</a> with the following parameters:

    *  `--allow-external-extensions-for-remote-sources` (which allow external extension for localhost, you do not need to worry about that when packing with Crosswalk app tools), and
    *  `--external-extensions-path=/path/to/myextension`.

Your extension should be loaded.

