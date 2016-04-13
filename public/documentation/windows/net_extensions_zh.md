# .NET Extensions

Crosswalk .NET extensions使用.NET语言编写，并且可以访问标准的.NET API。它允许在你的Crosswalk应用中将这些接口暴露给JavaScript。

## The .NET extensions 系统
Windows平台上的Crosswalk可以从指定路径加载若干个extension，因此你可以将多种.NET接口暴露给你的Crosswalk应用。针对Windows平台的Crosswalk希望每个.NET extension可以称为一个多架构的DLL文件，其中每个文件定义多个入口点，这样便可以实现Crosswalk和extension之间的通信。.NET extension在一个分离的过程中被加载（为了安全性和稳定性因素）。

当你写.NET extension时需要指导两个主要的类：

*   `XWalkExtension`只被实例化一次，并且可以被Crosswalk用来获取关于extension本身的信息：JavaScript API和extension名称（用于JavaScript前缀）。
*   `XWalkExtensionInstance` 依据浏览器环境（例如iframes）可以被多次创建。每个实例负责处理从Crosswalk传递的信息并返回给Crosswalk。

每个.NET extension需要附带一个Crosswalk提供的bridge。简单的复制并且根据你的.NET extension DLL名称加上后缀`_bridge`重新命名。例如，如果你的extension被称为`echo_extension.dll`，bridge应该称为`echo_extension_bridge.dll`。

## <a class='doc-anchor' id='Writing-your-Net-Extension'></a>编写你的.NET Extension
1. 下载并安装[Visual Studio 2013](https://www.visualstudio.com/en-us/downloads/download-visual-studio-vs.aspx)。 （2015目前还没有测试）。

2. 下载并安装[Crosswalk .NET Extensions Visual Studio Template](https://visualstudiogallery.msdn.microsoft.com/51e648be-c91a-40fa-9d13-8f49ec134e86)。也许你也可以在Visual Studio Extensions中检索它并且更新对话框。

   这个模板将会帮助你在Visual Studio中快速创建一个extension。

3. 打开Visual Studio并且点击：File -> New Project

4. 在新的对话框中，选择“Visual C#”并且将滚动条下拉到底部的列表，其中你可以找到Crosswalk .NET Extensions简单模板。
    
   <a href="/assets/win5-new-project.png"><img src="/assets/win5-new-project.png" style="display: block; margin: 0 auto"/></a>

5. 输入你的.NET extension的名称，设置位置并且传递一个名称给Visual Studio解决方案

6. 点击OK后你便完成了所有的设置：

   <a href="/assets/win6-visual-studio.png"><img src="/assets/win6-visual-studio.png" style="display: block; margin: 0 auto"/></a>
   
   请注意默认的Windows Runtime API不可用，但是你可以通过右击你的项目，然后执行Add -> References，标记Windows下的Core将其添加。

在`XWalkExtension`类中有两个你应该实现的方法：

*   `ExtensionName()`: 这个是你的JavaScript编写的extension的前缀，并且关于如何调用你的extension提供的API。例如，你可以返回“echo”，那么你的所有使用JavaScript的调用都将以“echo”为前缀。
*   `ExtensionAPI()`: 这个是你将暴露给JavaScript的API。下图是关于一个简单的"Echo"app如何使用这API的示例。

   <a href="/assets/win7-extension-config.png"><img src="/assets/win7-extension-config.png" style="display: block; margin: 0 auto"/></a>

### 编写Extension API

*   任何你想作为JavaScript暴露给你的extension的属性（方法，对象，实例等）都应该被附加到JavaScript API string内的出口对象上。这对于nodejs模式中的出口对象有相似的作用，定义API的公众形象。任何其他没有附加到出口上的变量只能被限制到本文件，并且不会扩展到web应用的全局范围。在这个简单的“echo”样例中，我们有两个暴露的方法：`echo`和`syncEcho`。在JavaScript方面，它们将会被以这种方式调用：`echo.echo(“test”)`和`echo.syncEcho(“test”)`。
*   `extension`对象是通过.NET extension框架设定并且包含可以与你的extension中的.NET方面通信的方法：`extension.PostMessage()`和`extension.internal.sendSyncMessage()`。前者较简单，它从JavaScript向将要处理它的.NET发送消息。`sendSyncMessage`是相似的，但是它是同步调用（并且因此阻塞），这意味着它将在.NET方面给出答复后才返回。
*   `extension.setMessageListener()`设置一个调用函数用于.NET方面的extension返回的每条信息。在这个简单的样例中，当.NET响应我们的消息时，`echo()`函数从将要被调用的调用者处采用一个回调。在这种情况下，当.NET回复时监听者将被调用并且调用回调函数。

### 实现XWalkExtensionInstance类

*   关于构造器：你不应该修改关于.NET extension将被加载的信号。它采用一个参数`dynamic native`，它将被用于反馈信息给Crosswalk extension系统。
*   `HandleMessage(String message)`用于每条JavaScript发起的消息，所以每次会调用`extension.PostMessage()`。消息的参数包含从JavaScript传递下来的。
*   `HandleSyncMessage(String message)`等同于`HandleMessage`，但是接收从JavaScript `sendSyncMessage()`的调用。你应该在那个函数中设置一个对JavaScript的回复因为否则javaScript执行会被阻塞。
*   对JavaScript的通信反馈是通过在本地处理中使用`PostMessageToJS`和`SendSyncReply`完成。在这个简单的样例中，`PostMessageToJS`简单的发送一个回复到JavaScript，并且被JavaScript API（它将会调用回调）消息监听器捕获。`SendSyncReply`应该在`HandleSyncMessage`内调用，并且告诉.NET extension框架你已经完成了你的同步调用，JavaScript的执行将被恢复。你的JavaScript调用的返回值是由`SendSyncReply`方法的参数设置。

当你完成这些后，点击Build菜单并且选择'Build Solution'创建你的extension DLL。

### 在你的.NET extension中的高级JavaScript API 

在上文<a href="#Writing-your-Net-Extension">Writing your .NET Extension</a>中的样例是一个JavaScript中相当简单并且普通的API。

你的JavaScript API中存在多个调用`PostMessage`的方法是很常见的，所以在你的.NET extension中， `handleMessage`变得很棘手。一种处理这个难题的方法是通过你的.NET extension中基于JSON的消息，其中你可以传递一个ID和消息。ID将被用于识别消息来自那个JavaScript API，并且任何其他的属性也可以被当做参数继续传递。一旦被.NET `handleMessage`接收，便会直接反序列化接收的JSON。下文是在JavaScript角度它将如何呈现：

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

关于.NET方面请参见[https://msdn.microsoft.com/en-us/library/bb412179(v=vs.110).aspx](https://msdn.microsoft.com/en-us/library/bb412179(v=vs.110)

你可能想要基于[Promises](http://promises-aplus.github.io/promises-spec/)实现一个API。You may also want to implement an API based on [Promises](http://promises-aplus.github.io/promises-spec/). In the previous example we showed a callback mechanism but it has to be managed carefully: if callbacks are nested inside callbacks inside callbacks etc., it can lead to the so-called [pyramid of doom](https://github.com/survivejs/js_tricks_and_tips/blob/master/common_problems/pyramid.md).


### Packing your extension with crosswalk-app-tools
The ability to package your .MSI installer with your .NET extension is being implemented.  This section will beupdated when the ability is complete and part of the Crosswalk packaging tool.

### Testing your .NET Extension using Crosswalk for Windows binary
After downloading Crosswalk for Windows and unzipping it you can easily try your extension.

1.  Make sure you copy xwalk_extension_bridge.dll in the folder where you built your .NET extension (where the .DLL is located).
2.  Rename `xwalk_extension_bridge.dll` so that it looks identical as your extension DLL name with the suffix `_bridge` as the only difference. Here is an example:

   <img src="/assets/win9-extension-net-bridge.png" style="display: block; margin: 0 auto"/>

3.  Inside your command line, navigate to the path where you unzipped Crosswalk and launch it using the method described in <a href="/documentation/windows/run_on_windows.html#Run-using-Crosswalk-binary-directly">Run using Crosswalk binary directly</a> with the following parameters:

    *  `--allow-external-extensions-for-remote-sources` (which allow external extension for localhost, you do not need to worry about that when packing with Crosswalk app tools), and
    *  `--external-extensions-path=/path/to/myextension/myextension.dll`.
  
  The command line should look like this :
   ```
    > xwalk.exe --allow-external-extensions-for-remote-sources \
                 --external-extensions-path=/path/to/myextension/myextension.dll \
                 http://localhost:8000
   ```

Your extension should be loaded.

