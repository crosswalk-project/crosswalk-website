# 针对Windows应用的扩展
扩展可以加强Crosswalk项目的功能，帮助应用通过原生代码充分利用平台特征。

Extensions适用于很多用例：

* 使用Crosswalk JavaScript API没有提供的设备访问能力。例如，使用特定传感器或者访问web应用沙盒外部的一部分文件系统。
* 在没有同等JavaScript库的情况下，将已存在的C/C++库同web应用集成。
* 不需要分发一个易读的JavaScript库便可以将技术或者智慧成果引入到你的项目中。
* 在达到JavaScript的性能极限后，优化你的部分项目。

Crosswalk扩展程序可以通过C或者C++实现，并且可以链接外部的函数库，潜在地允许你利用任何底层的OS接口和第三方库。另外，扩展是一种可以为Crosswalk添加原生功能的安全方法：每个扩展均在自己的进程中运行，所以它不会直接影响运行时的稳定性。

这个教程主要说明了如何为运行在64-bit的Windows平台上的Crosswalk写一个简单的"echo"扩展。其中扩展程序接受一个输入并且返回输入内容加上前缀"You said: "。它将可以被JavaScript代码（在一个HTML5应用中）调用，方法如下：

    // Synchronous call will wait until function returns
    var response = echo.echoSync('hello');
      // response == 'You said: hello'

    // Asynchronous call. Pass in the callback function as the 2nd paramter
    echo.echoAsync('hello', function (response) {
      // response == 'You said: hello'
    });

扩展的原生部分可以通过C或者C++实现。尽管我们这份简单的示例程序很普通，并且可以通过JavaScript实现。然而其目的是简化扩展本身的复杂度，以聚焦到代码结构和开发流程上。

同上方JavaScript代码对应的C代码如下：

    // echoSync() handler
    static void handle_sync_message(XW_Instance instance, const char* message) {
      char* response = build_response(message);
      g_sync_messaging->SetSyncReply(instance, response);
      free(response);
    }

    // echoAsync() handler
    static void handle_message(XW_Instance instance, const char* message) {
      char* response = build_response(message);
      g_messaging->PostMessage(instance, response);
      free(response);
    }

本小节中，我们编写，创建并打包Crosswalk扩展。其中扩展程序本身是用C语言编写并且使用Visual Studio创建。尽管在这个教程中我们同时创建了扩展和应用，实际上它们是两个分开的部分：如果需要，一个扩展可以被用来支持多个应用。

扩展包含下列三个部分：

* 一份JavaScript文件。其中定义了web应用可以调用的API。
* 一份包含一个JavaScript文件“字符串化”版本的C语言头文件。它用于定义C语言程序内部扩展的JavaScript接口。该文件在构建时生成，并且在C语言库编译之前。
* 一份实现了扩展原生部分的C文件。

同时你还需要一些支持文件来编译和打包扩展。

## 安装Visual Studio模板

下载压缩的模板文件: [Crosswalk C-C++ Extensions Template.zip](/assets/Crosswalk%20C-C++%20Extensions%20Template.zip>) (14KB)

将压缩文件复制到: `C:\Users\<your user name>\Documents\Visual Studio 2015\Templates\ProjectTemplates\Visual C++ Project\` 

 (注意: Windows文件浏览器可能将"Documents"展示为"My Documents") <br><br>
 
<img src="/assets/windows-extension-dir.png" style="display: block; margin: 0 auto"/>

## 启动

打开Visual Studio并点击“New Project”，再选择“Templates” ，“Visual C++”，然后在列表中选择“Crosswalk C-C++ Extensions Template”

<img src="/assets/windows-extension-dlg.png" style="display: block; margin: 0 auto"/>

选取你代码的位置并输入你的项目名称：

<img src="/assets/windows-extension-dlg2.png" style="display: block; margin: 0 auto"/>

## JavaScript搭起了API和C语言扩展的桥梁
这个文件连接C语言接口到JavaScript，并且提供了HTML5应用和C语言代码的桥梁。

让我们看一下JavaScript接口:

    var echoListener = null;

    extension.setMessageListener(function(msg) {
      if (echoListener instanceof Function) {
        echoListener(msg);
      };
    });

    exports.echoAsync = function (msg, callback) {
      echoListener = callback;
      extension.postMessage(msg);
    };

    exports.echoSync = function (msg) {
      return extension.internal.sendSyncMessage(msg);
    };

#### 关于JavaScript接口
关于这个接口的异步部分并不适合一个真实的生产环境。
目前，当你调用`echoAsync()`方法时，你设置了一个全局的方法监听者：一个等待扩展中C语言部分返回的下一个回应的函数。然而，如果在扩展中的处理花费的时间过长，在这段时间内`echoAsync()`会被再次调用，那么这个方法将不会正常工作。

例如，考虑下列程序：

    var callback1 = function (response) {
      console.log(response + ' world');
    };

    var callback2 = function (response) {
      console.log(response + ' cruel world');
    };

    // invocation 1
    echo.echoAsync('hello', callback1);

    // invocation 2
    echo.echoAsync('goodbye', callback2);

当调用1发生时，消息的监听者被设为`callback1`。如果扩展程序花费几秒钟来响应，调用2可能已经在调用1完成之前发生；调用2将消息监听者设为`callback2`（所有的response均只有一个监听者）。结果，当调用1最终返回时，它的处理者(callback1)已经被重写；所以调用1和调用2的回应将均由（错误地）callback2处理。
实际上，这意味着你将在终端看到下列信息：

    hello cruel world
    goodbye cruel world

而不是预期的：

    hello world
    goodbye cruel world

解决方法就是从JavaScript接口传递一个token到C语言代码，然后将token作为C语言代码response的一部分返回。JavaScript接口将维护一个从token到回调的映射，所以当reponse被返回时（包含一个token），正确的处理者可以被找到并且调用。一种实现这个功能的典型方法就是在扩展中JavaScript和C语言部分之间使用JSON编码的消息，并且每个消息中包含一个token。然而，这个过程的复杂程度超过了本教程的范围。

## 针对JavaScript接口的C语言静态常量
在`extension.c`中，你应该有：

    static const char* kAPI= "var echoListener = null;"
    ""
    "extension.setMessageListener(function(msg) {"
    "  if (echoListener instanceof Function) {"
    "    echoListener(msg);"
    "  };"
    "});"
    ""
    "exports.echoAsync = function (msg, callback) {"
    "  echoListener = callback;"
    "  extension.postMessage(msg);"
    "};"
    ""
    "exports.echoSync = function (msg) {"
    "  return extension.internal.sendSyncMessage(msg);"
    "};"
    ;

## C程序代码
这实现了Crosswalk 扩展接口，并且可以访问全部的原生接口。为了专注于本教程的目的，C语言代码就简单的在消息上加上前缀"You said: "并返回。

新建一个包含下列内容的文件`extension/echo-extension.c`

    // echo extension for Crosswalk
    // adapted from
    // https://github.com/crosswalk-project/crosswalk/blob/master/extensions/test/echo_extension.c
    // Copyright (c) 2016 Intel Corporation. All rights reserved.
    // Use of this source code is governed by a BSD-style license
    // that can be found in the LICENSE file.

    #include <stdio.h>
    #include <stdlib.h>
    #include <string.h>
    #include "XW_Extension.h"
    #include "XW_Extension_SyncMessage.h"

    // load kSource_echo_api string to set JavaScript API;
    // echo-extension.h is generated by the makefile at build time
    #include "echo-extension.h"

    static const char* echo_ext_response_prefix = "You said: ";

    static XW_Extension g_extension = 0;
    static const XW_CoreInterface* g_core = NULL;
    static const XW_MessagingInterface* g_messaging = NULL;
    static const XW_Internal_SyncMessagingInterface* g_sync_messaging = NULL;

    static void instance_created(XW_Instance instance) {
        printf("Instance %d created!\n", instance);
    }

    static void instance_destroyed(XW_Instance instance) {
        printf("Instance %d destroyed!\n", instance);
    }

    // add a "You said: " prefix to message
    static char* build_response(const char* message) {
        int length = strlen(echo_ext_response_prefix) + strlen(message);
        char* response = malloc(length);
        strcpy(response, echo_ext_response_prefix);
        strcat(response, message);
        return response;
    }

    static void handle_message(XW_Instance instance, const char* message) {
        char* response = build_response(message);
        g_messaging->PostMessage(instance, response);
        free(response);
    }

    static void handle_sync_message(XW_Instance instance, const char* message) {
        char* response = build_response(message);
        g_sync_messaging->SetSyncReply(instance, response);
        free(response);
    }

    static void shutdown(XW_Extension extension) {
        printf("Shutdown\n");
    }

    // this is the only function which needs to be public
    int32_t XW_Initialize(XW_Extension extension, XW_GetInterface get_interface) {
        // set up the extension
        g_extension = extension;
        g_core = get_interface(XW_CORE_INTERFACE);
        g_core->SetExtensionName(extension, "echo");

        // kSource_echo_api comes from the echo-extension.h
        // header file
        g_core->SetJavaScriptAPI(extension, kSource_echo_api);

        g_core->RegisterInstanceCallbacks(
        extension, instance_created, instance_destroyed);
        g_core->RegisterShutdownCallback(extension, shutdown);

        g_messaging = get_interface(XW_MESSAGING_INTERFACE);
        g_messaging->Register(extension, handle_message);

        g_sync_messaging = get_interface(XW_INTERNAL_SYNC_MESSAGING_INTERFACE);
        g_sync_messaging->Register(extension, handle_sync_message);

        return XW_OK;
    }

#### 关于代码:

* `XW_Extension.h`头文件被用于定义扩展系统使用的结构。
* 唯一应该被暴露的符号是函数：
`int32_t XW_Initialize(XW_Extension extension, XW_GetInterface get_interface)`
它的参数是：

 * `extension`: 关于这个extension的标识符。这个标识符将被用于extension
 * `get_interface`: 一个名为`const void* XW_GetInterface(const char* interface_name)`的函数，用于访问提供整合原生代码到Crosswalk运行时功能的接口，它将返回指向一个结构体的指针，其中结构体中包含扩展可以调用的函数。

* 这个函数应该被实现并且暴露给共享的实体；当扩展被正确初始化时，它应该返回`XW_OK`。
* 定义这个函数时为了避免如果使用C++编译器时的方法重载，请确保使用`extern "C"`。
* `SetExtensionName()` 设定了针对你的web应用可访问的JavaScript接口的公有名称。
* `SetJavaScriptAPI()` 使用将要展示的JavaScript字符串作为API。你在`SetExtensionName()`中设置的名称应该匹配你在JavaScript接口字符串中使用的那个。在这种扩展的情况下，接口字符串将被从一个头文件中加载，它在构建中生成。
* 这个例子提供了针对相同的处理者的同步和异步两种版本。但是一个扩展不是必须要同时处理同步和异步消息：如果需要它可以仅仅处理一种类型。
* 当C语言的扩展程序执行的处理可能花费一些时间（例如，多于几毫秒；通常，这个意味着任何调用磁盘或者网络访问）时，你应该使用异步消息。当C语言代码将被快速返回（可能几十毫秒），同步消息是足够安全的。
* 然而，在大多数情况下，最安全的方法是提供一个异步的接口。为了完整性，同步的方法也可以被展示。
* 同步函数(`XW_Internal_SyncMessagingInterface->SetSyncReply()`)和异步函数(`XW_MessagingInterface->PostMessage()`)均返回一个回应“保存它们的输入”，所以一旦你已经调用它们便可以随意释放任何你传递给这些函数的指针。

## 更多关于C语言接口的细节

### 核心接口：XW_CORE_INTERFACE
* 这个接口在`XW_Extension.h`头文件中定义。
* 它是一个包含下列域的结构体`XW_CoreInterface`：
 * `SetExtensionName()`设置扩展的名称。这个是强制的。它应该仅在`XW_Initialize()`期间被调用。
 * `SetJavaScriptAPI()`输出可以供所有页面访问的JavaScript shim。JavaScript代码接口将会和扩展关联。它应该仅在`XW_Initialize()`期间被调用。
 * `RegisterInstanceCallbacks()`会通知Crosswalk运行时哪些函数需要被调用，当新的扩展实例被创建或者销毁时。实例拥有和web内容一样的生命周期。它应该仅在`XW_Initialize()`期间被调用。
 * `RegisterShutdownCallback()`登记了一个在扩展程序被卸载时将被调用的回调函数。这个函数应该仅在`XW_Initialize()`期间被调用。 
 * `SetInstanceData()`和`GetInstanceData()`均是允许任意数据与每个实例相关联的便捷函数，其中这些数据将被用于检索。这些函数可以在一个实例生命周期的任何时间被调用。

### 消息接口 XW_MESSAGING_INTERFACE
* 这个接口被定义在`XW_Extension.h`头文件中。
* 它是一个包含下列域的结构体`XW_MessagingInterface`：
 * `Register()`: 当被调用时，这个函数将会告诉Crosswalk当从JavaScript方传来消息时，哪个函数应该被调用。
 * `PostMessage()`会向实例相关的web内容发送消息。

### 消息同步接口（实验性） XW_INTERNAL_SYNC_MESSAGING_INTERFACE
* 这个接口定义在`XW_Extension_SyncMessage.h`头文件中。它被标记为内部使用，并且不能保证它对于将来的Crosswalk版本的兼容性。
* 它是一个包含下列域的结构体`XW_Internal_SyncMessagingInterface`：
 * `Register()`:当从JavaScript方传来消息时， 这个函数将会告诉Crosswalk应该调用哪个函数。
 * `SetSyncReply()` 从JavaScript方面回应一个同步（阻塞）的消息。渲染过程将会被阻塞，直到这个函数被调用.rc/xwalk/extensions/public/*.h

上文中提到的接口的名字和结构均有一个版本号后缀的名称。然而，扩展程序的作者应该使用无版本的宏指令来获取需要的接口。

## 编译项目
你仅需在Visual Studio中编译工程，它将会生成你的扩展应用的.DLL。

## 运行你的扩展
下载完针对Windows平台的Crosswalk并解压后，你便可以轻松测试你的扩展。

在你的命令行中，导航到你解压Crosswalk的路径并使用在Run中描述的方法，直接用下列参数和Crosswalk二进制文件直接启动它。

* --allow-external-extensions-for-remote-sources (它允许对本地环境的外部扩展，当打包Crosswalk应用工具时不必担心它），还有

* --external-extensions-path=/path/to/myextension/myextension.dll.

这个命令应该如下：

    > xwalk.exe --allow-external-extensions-for-remote-sources \
                --external-extensions-path=/path/to/myextension/myextension.dll \
                http://localhost:8000

你的扩展应该被加载了。
