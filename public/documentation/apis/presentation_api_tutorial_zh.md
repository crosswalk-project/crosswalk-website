# Presentation API教程#

Presentation API允许一个web应用在一个连接在设备上的第二显示屏上展示web内容。它有如下几个用途：展示幻灯片，分享视频和图片，以及通过移动设备在一个大屏幕电视上玩游戏。

Presentation API是是针对Android 4.2版本Crosswalk的一个实验性功能，它基于兼容Miracast的wireless display支持实现。它在[当前版本](https://crosswalk-project.org/documentation/downloads.html)的Crosswalk中可以使用。这篇教程是基于[HTML5 Hub](http://html5hub.com/presentation-api-tutorial/)上由Hongbo Min发表的一篇文章，并且已经经过[Crosswalk Stable 11.40.277.7](https://crosswalk-project.org/documentation/downloads.html)的测试。

Presentation API定义如下：

```
interface Presentation :EventTarget {
           void requestShow(DOMString url, successCallback, errorCallback);
                  readonly attribute boolean displayAvailable;
                         attributeEventHandler ondisplayavailablechange;
};
```

在Crosswalk中，Presentation API作为navigator对象的一个附加属性被外界调用： 
    
    ```
    navigator.presentation.requestShow
    navigator.presentation.displayAvailable
    navigator.presentation.displayavailablechange
    ```

    注意：[Presentation API W3C spec](http://w3c.github.io/presentation-api/)还在不断地改进，并且可能为了满足新的使用场景而继续修改，例如和Chromecast设备的集成。

## 搭建无线展示环境 ##

**需求**

1. 支持Miracast协议的Android 4.2或者更新版本的设备。
2. 一台TV或者有HDMI端口的显示器。
3. 一个Miracast认证的无线显卡，例如Netgear PTV3000 (已通过测试)。
4. 将无线显卡插入显示器的HDMI端口。
5. 在Android设备上，打开“setting”应用并选取“display”。
6. 打开“wireless display”，连接到可用的无线显示器上。

**其他方法: 没有无线显示器**

如果你没有无线显卡，但是希望在你的设备上运行应用，Android模拟器可为你提供一个用于测试目的的第二显示器。这个模拟显示器会覆盖你设备的屏幕。为了使用它，你需要执行以下步骤：

1. 打开`Settings`应用并选取`Developer Options`。
2. 使用`Simulate Secondary Display`选项来选择一个用于模拟的显示器。

## 简单代码 ##

在下文的样例中存在两个HTML文件：

 * `index.html` – 一个展示主屏幕的控制页，同时还会打开第二显示器上的一个页面。
 * `content.html` – 在第二显示器上展示的页面内容

  **index.html**
  ```
  <!DOCTYPE html>
   
   <html>
   <head>
   <script>
   var presentationWindow = null;
    
    function onsuccess(w) {
          presentationWindow = w;
            w.postMessage("Hello from the primary display", "*");
    }
     
     function onerror(error) {
           alert("Failed to requestShow: " + error.name);
     }
      
      function showPresentation() {
            if (!navigator.presentation.displayAvailable) {
                    alert("No available display found.");
                        return;
                          }
                           
                             navigator.presentation.addEventListener("displayavailablechange", function() {
                                     if (!navigator.presentation.displayAvailable)
                                               presentationWindow = null;
                                                 });
                               navigator.presentation.requestShow("content.html", onsuccess, onerror);
      }
       
       window.addEventListener("message", function(event) {
             var msg = event.data;
              
                var elem = document.getElementById(“msg”);
                 
                   elem.innerHTML= ”Message from secondary display: ” + msg;
       });
       </script>
       </head>
         <body>
           <input type="button" onclick="showPresentation();" value=”Show Presentation”>
             </input>
                
                  <div id=”msg”>Error: No message from primary display</div>
                    </body>
                    </html>
                    ``` 

                    **content.html**
                    ```
                    <html>
                    <head>
                      <script>
                         window.addEventListener("message", function(event) {
                                  var msg = event.data;
                                       var elem = document.getElementById(“output”);
                                            elem.innerHTML = msg;
                                                 event.source.postMessage("Hello from the secondary display", "*");
                                                    });
                           </script>
                           </head>
                           <body>
                             Message from its opener:
                               <div id=”output”>Error: No message received from secondary dispaly</div>
                               </body>
                               </html>
                               ```

遵循[如何编译一个Crosswalk应用](/documentation/android/build_an_application_zh.html)来将上文中的两个HTML文件打包成一个Android应用；然后根据[如何在Android环境下运行](/documentation/android/run_on_android_zh.html)的指导在一个Android 4.2+的设备上启动应用。在运行应用前请确保至少有一个可用的第二显示器：参见上文中的如何搭建一个无线显示器。

一旦应用开始运行，点击"show Presentation"按钮将会在第二显示器上打开`content.html`页面。

你应该可以在主屏幕上看到如下的输入：

> Hello from the secondary display

在第二显示屏上看到如下输出:

> Hello from the primary display

如果没有可用的无线显示器，你将会收到显示如下信息的警告对话框"No available display found"。

关于一个使用第二显示屏的更加实际的HTML5应用的详细信息，请参见[ImageGallery](https://github.com/crosswalk-project/crosswalk-demos/tree/master/Gallery)。它展示了如何使用Presesntation API在一个无线显示屏上展示照片。


### 关于JavaScript API的细节 ###

####`presentation.requestShow Method`####

`requestShow`为了展示一个presentation向用户代理发送一个请求。如果第二显示屏已经可以使用，那么在第二显示屏上将会打开一个通过url规定的HTML页面。第二个窗口是在当前浏览器环境下打开的。一旦页面完成加载，则`successCallback`方法会被新页面的window对象作为输入参数调用。因此，控制窗口和展示窗口之间的通信遵循[HTML5 Web Messaging](http://www.w3.org/TR/webmessaging/)的方法。你可以调用`postMessage`发送一条消息，同时注册一个`onmessage`事件监听器来处理从另一端传来的消息。

如果发生错误，`requestShow`将失败， 并且`errorCallback`函数将被DOMError对象调用。下表描述了可能的错误：

<table>
<tr><th>Error名称</th><th>描述信息</th></tr>
<tr><td>NotFoundError</t><td>没有发现可用的第二显示屏</td></tr>
<tr><td>InvalidAccessError</td><td>在第二显示屏上已经有展示内容</td></tr>
<tr><td>NotSupportedError</td><td>平台不支持Miracast协议</td></tr>
<tr><td>InvalidParameterError</td><td>传递了一个无效的url参数</td></tr>
</table>

注意：因为在第二显示屏上没有输入通道，我们推荐：

1. 为大屏展示设计便于阅读的用户界面。
2. 避免JavaScript对话框(例如alert和confirm).
3. 不要期待点击，触屏或者键盘事件，或者和展示页面内容的交互。

####`presentation.displayAvailable属性`####

如果至少存在一个可以用来显示presentation的第二显示屏，`presentation.displayAvailable`属性便是true。否则，它是false。如果一个第二显示屏不能连接时：

1. 如果这不是唯一与设备连接的第二显示屏，那么页面的内容将会自动转向下一个可用的显示屏。
2. 如果这个唯一的第二显示屏，则页面内容将不会被显示，并且`displayAvailable`属性将被设置成false。

####`presentation.displayavailablechange事件`####

当第一个第二显示屏可用或者最近使用的一个第二显示屏被移出时，`displayavailablechange`事件会被触发。`displayAvailable`的属性值会被更新成true或者false（参见上文）。

如果第二显示屏不可用时，展示的浏览器窗口将被关闭。应用无需关闭它。


### 总结 ###

在这份指南中，为了在一个第二显示屏上呈现web内容，我们引入了一个新的W3C Presentation API。目前，它还是在Android 4.2+设备上的Crosswalk中实现的一个实验性特性。基于社区的反馈，它在将来会不断改进。


### 资源 ###

这里是一些相关的链接：

* [W3C Presentation API Draft](http://w3c.github.io/presentation-api/)
* [Second Screen Presentation Community Group](http://www.w3.org/community/webscreens/)
* [Web Screens Mail List](http://lists.w3.org/Archives/Public/public-webscreens/)
