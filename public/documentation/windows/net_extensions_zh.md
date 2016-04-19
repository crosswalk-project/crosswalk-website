# .NET扩展程序

Crosswalk的.NET扩展程序是使用.NET语言编写，并且可以访问标准的.NET API。它允许在你的Crosswalk应用中将这些接口暴露给JavaScript。

## .NET扩展程序系统
Windows平台上的Crosswalk可以从指定路径加载若干个扩展，因此你可以将多个.NET接口暴露给你的Crosswalk应用。针对Windows平台的Crosswalk希望每个.NET 扩展为一个多架构的DLL文件，其中每个文件定义多个入口点，这样便可以实现Crosswalk和扩展之间的通信。.NET扩展在一个分离的过程中被加载（为了安全性和稳定性因素）。

当你写.NET extension时需要知道两个主要的类：

*   `XWalkExtension`只被实例化一次，并且可以被Crosswalk用来获取关于扩展本身的信息：JavaScript API和扩展名称（用于JavaScript前缀）。
*   `XWalkExtensionInstance` 依据浏览器环境（例如iframes）可以被多次创建。每个实例负责处理从Crosswalk传递的信息并返回给Crosswalk。

每个.NET扩展需要附带一个Crosswalk提供的bridge。仅需要复制并根据你的.NET扩展程序的 DLL名称加上后缀`_bridge`重新命名。例如，如果你的扩展被称为`echo_extension.dll`，bridge应该称为`echo_extension_bridge.dll`。

## <a class='doc-anchor' id='Writing-your-Net-Extension_zh'></a>编写你的.NET扩展程序
1. 下载并安装[Visual Studio 2013](https://www.visualstudio.com/en-us/downloads/download-visual-studio-vs.aspx)。 （2015目前还没有测试）。

2. 下载并安装[Crosswalk .NET扩展程序Visual Studio模板](https://visualstudiogallery.msdn.microsoft.com/51e648be-c91a-40fa-9d13-8f49ec134e86)。也许你也可以在Visual Studio的扩展和更新对话框中检索它。

   这个模板将会帮助你在Visual Studio中快速创建一个扩展。

3. 打开Visual Studio并且点击：File -> New Project

4. 在新的对话框中，选择“Visual C#”并且将滚动条下拉到底部的列表，其中你可以找到Crosswalk .NET扩展程序的简单模板。
    
   <a href="/assets/win5-new-project.png"><img src="/assets/win5-new-project.png" style="display: block; margin: 0 auto"/></a>

5. 输入你的.NET extension的名称，设置位置并且传递一个名称给Visual Studio解决方案

6. 点击OK后你便完成了所有的设置：

   <a href="/assets/win6-visual-studio.png"><img src="/assets/win6-visual-studio.png" style="display: block; margin: 0 auto"/></a>
   
   请注意默认的Windows运行时API不可用，但是你可以通过右击你的项目，然后执行Add -> References，标记Windows下的Core并将其添加。

在`XWalkExtension`类中有两个你应该实现的方法：

*   `ExtensionName()`: 这个是你用JavaScript编写的扩展程序的前缀，并且关于如何调用你的扩展程序提供的API。例如，你可以返回“echo”，那么你所有使用JavaScript的调用都将以“echo”为前缀。
*   `ExtensionAPI()`: 这个是你将暴露给JavaScript的API。下图是关于一个简单的"Echo"应用如何使用这API的示例。

   <a href="/assets/win7-extension-config.png"><img src="/assets/win7-extension-config.png" style="display: block; margin: 0 auto"/></a>

### 编写扩展API

*   任何你想作为JavaScript API暴露给扩展程序的属性（方法，对象，实例等）都应该被附加到JavaScript API文件内暴露的对象上。这和nodejs中暴露对象的职责有相似的作用，定义API的公众接口。任何其他没有附加到exports上的变量只能被限制到文件作用域内，并不会扩展web应用的全局命名空间。在这个简单的“echo”样例中，我们有两个暴露的方法：`echo`和`syncEcho`。在JavaScript方面，它们将会被以这种方式调用：`echo.echo(“test”)`和`echo.syncEcho(“test”)`。
*   `extension`对象是通过.NET扩展Framework设定并包含与你的扩展程序的.Net方交流的方法：`extension.PostMessage()`和`extension.internal.sendSyncMessage()`。前者较简单，它从JavaScript向将要处理它的.NET部分发送消息。`sendSyncMessage`是相似的，但是它是同步调用（并且因此阻塞），这意味着它将在.NET部分发出response以后才会返回。
*   `extension.setMessageListener()`设置一个调用函数用于.NET方面的extension返回的每条信息。在这个简单的样例中，当.NET响应我们的消息时，`echo()`函数从将要被调用的调用者处采用一个回调。在这种情况下，当.NET回复时监听者将被调用并且调用回调函数。

### 实现XWalkExtensionInstance类

*   关于构造器：你不应该修改将被加载的.NET扩展的签名。它采用一个参数`dynamic native`，该参数将被用于反馈信息给Crosswalk扩展系统。
*   `HandleMessage(String message)`用于处理每条由JavaScript发起的消息，所以对上文中提及的`extension.PostMessage()`的每次调用均会发出改函数的调用。消息的参数包含从JavaScript传递下来的信息。
*   `HandleSyncMessage(String message)`等同于`HandleMessage`，但是接收JavaScript 中对于`sendSyncMessage()`的调用。你应该在那个函数中设置一个对JavaScript的reply，否则javaScript执行会被阻塞。
*   对JavaScript的通信反馈是通过在本地处理中使用`PostMessageToJS`和`SendSyncReply`完成。在这个简单的样例中，`PostMessageToJS`简单的发送一个回复到JavaScript，并且被JavaScript API（它将会调用回调）消息监听器捕获。`SendSyncReply`应该在`HandleSyncMessage`内调用，并且告诉.NET 扩展框架你已经完成了你的同步调用，JavaScript的执行将被恢复。你的JavaScript调用的返回值是由`SendSyncReply`方法的参数设置。

当你完成这些后，点击编译菜单并且选择'Build Solution'创建你的扩展程序DLL。

### .NET扩展中的高级JavaScript API 

在上文<a href="#Writing-your-Net-Extension_zh">编写你的.NET扩展程序</a>中的样例是一个JavaScript中相当简单并且普通的API。

你的JavaScript API中存在多个调用`PostMessage`的方法是很常见的，所以你的.NET扩展程序中的 `handleMessage`变得很棘手。一种处理这个难题的方法是，在JavaScript和.NET扩展之间传递基于JSON的消息，在消息中可以携带ID和消息内容。ID将被用于识别消息来自哪个JavaScript API，并且任何其他的属性也可以被当做参数继续传递。一旦被.NET `handleMessage`接收，便会直接反序列化接收到的JSON。以下展示了在JavaScript端代码的编写方式：

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

你可能想要基于[Promises](http://promises-aplus.github.io/promises-spec/)实现一个API。在前面的样例中，我们展示了一种回调机制但是必须谨慎使用：如果回调是包含在回调中的回调等，它可能导致所谓的[金字塔效应](https://github.com/survivejs/js_tricks_and_tips/blob/master/common_problems/pyramid.md)。


### 使用crosswalk-app-tools打包你的扩展
通过你的.NET扩展打包你的.MSI安装程序的功能正在被实现。当功能被完成并且成为Crosswalk打包工具的一部分时，本小节将被更新。

### 使用针对Windows二进制文件的Crosswalk测试你的.NET扩展。
下载完针对Windows平台的Crosswalk并且解压后，你可以轻松地测试你的扩展程序。

如果你没有使用Visual Studio的模板，你需要做接下来的两步。开启2.0版本的模板，bridge将会被自动添加。
1.  确保在你创建.NET扩展的文件夹（.DLL位于的地方）中复制了xwalk_extension_bridge.dll文件。
2.  重命名`xwalk_extension_bridge.dll`，除了后缀`_bridge`唯一不同，其余部分和你的扩展 DLL命名完全相同。例如：

   <img src="/assets/win9-extension-net-bridge.png" style="display: block; margin: 0 auto"/>

3.  在命令行内，导航到你解压Crosswalk项目的路径并且使用<a href="/documentation/windows/run_on_windows_zh.html#Run-using-Crosswalk-binary-directly_zh">直接使用Crosswalk二进制文件运行</a>中描述的方法运行它，具体参数如下：

    *  `--allow-external-extensions-for-remote-sources` (这个允许对本地环境的外部扩展，你不需要担心什么时候打包Crosswalk应用工具包），
    *  `--external-extensions-path=/path/to/myextension/myextension.dll`.
  
  命令行应该如下：
   ```
    > xwalk.exe --allow-external-extensions-for-remote-sources \
                 --external-extensions-path=/path/to/myextension/myextension.dll \
                 http://localhost:8000
   ```

你的扩展应该被加载。请注意你可以在同一个目录下添加多个扩展（和它们各自的bridge），Crosswalk将会加载它们。

