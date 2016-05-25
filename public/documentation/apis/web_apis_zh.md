# Web APIs

下列是Crosswalk支持的标准API。

版本关键字:
&nbsp;&nbsp;&nbsp;<a class="doc-anchor" id="a"></a><sup>[a]</sup> = 只支持Android
&nbsp;&nbsp;&nbsp;<a class="doc-anchor" id="w"></a><sup>[w]</sup> = 只支持Windows桌面
&nbsp;&nbsp;&nbsp;<a class="doc-anchor" id="v"></a><sup>[X.X.X.X]</sup> = 这个或者更新的Crosswalk版本
&nbsp;&nbsp;&nbsp;<a class="doc-anchor" id="va"></a><sup>[X.X.X.X;x86|ARM]</sup> = 针对特定架构的这个或者更新的Crosswalk版本。

## 针对Intel RealSense技术的Crosswalk的扩展 
* [深度增强摄影](http://crosswalk-project.github.io/realsense-extensions-crosswalk/spec/depth-enabled-photography.html)<sup><a href="#w">[w]</a></sup> 捕获图像的3D深度信息，通过利用3D深度信息和[XDM]格式的I/O增强图像处理过程。
* [场景感知](http://crosswalk-project.github.io/realsense-extensions-crosswalk/spec/scene-perception.html)<sup><a href="#w">[w]</a></sup> 利用3D摄像头来创建被观察环境的数字化表示并且估算相机呈现出来的实时场景。
* [人脸追踪和识别](http://crosswalk-project.github.io/realsense-extensions-crosswalk/spec/face.html)<sup><a href="#w">[w]</a></sup> 在web平台上使用3D摄像头用于人脸追踪和人脸识别。

## 多媒体 & 图像
* [CSS动画](http://www.w3.org/TR/css3-animations/) - 在时间轴上使用关键帧的动画CSS属性。
* [CSS Backgrounds and Borders Level 3](http://www.w3.org/TR/css3-background/) - 关于边框和背景的CSS 特性。
* [CSS Color Module Level 3](http://www.w3.org/TR/css3-color/) - 关于颜色值和属性的CSS特性。
* [CSS Flexible Box Layout ](http://www.w3.org/TR/css3-flexbox/) - 一个针对用户接口设计的CSS box model。
* [CSS Fonts Level 3](http://www.w3.org/TR/css3-webfonts/) - 字体属性和动态字体资源加载。
* [CSS Multi-column Layout](http://www.w3.org/TR/css3-multicol/) - CSS多列布局。
* [CSS Text Decoration Level 3](http://www.w3.org/TR/css-text-decor-3/) - CSS文本修饰特性。
* [CSS Transforms](http://www.w3.org/TR/css3-transforms/) - 在不干扰正常流的前提下，使用CSS改变内容在3D空间的位置。
* [CSS Transitions](http://www.w3.org/TR/css3-transitions/) - 在时间轴上动态改变CSS属性值。
* [HTML Canvas 2D Context](http://www.w3.org/TR/2dcontext/) - 支持2D实时绘图模型的API。
* [HTML5 Audio/Video](http://www.w3.org/TR/html5/) - 不需要插件支持的音频和视频支持。
* [Media Queries Level 3](http://www.w3.org/TR/css3-mediaqueries/) - 对于不同的输出设备和屏幕采用相同内容的CSS媒体特征。
<!-- (Bob: Waiting for official support)* [Responsive Images](http://picture.responsiveimages.org/)<sup><a href="#v">[5.34.104.5]</a></sup> - Control which image resource is presented to a user, based on media query and/or image format support. -->
* [Scalable Vector Graphics (SVG) 1.1](http://www.w3.org/TR/SVG11/) - 一种针对2D矢量图形的XML标记语言。
* [WebRTC](http://www.w3.org/TR/webrtc/)<sup><a href="#a">[a]</a></sup> - 点对点地共享视频和音频流。

## 网络 & 存储
* [File API](http://dev.w3.org/2006/webapi/FileAPI/) - 异步读取在客户端缓存的文件内容或者原始数据。
* [File API: Directories and System](http://dev.w3.org/2009/dap/file-system/file-dir-sys.html) - 向API用户暴露沙盒内部文件系统。
* [File API: Writer](http://dev.w3.org/2009/dap/file-system/file-writer.html) - 在客户端向沙盒式文件系统写入文件。
* [HTML5 Web Messaging](http://www.w3.org/TR/webmessaging/) - 一种在不同的浏览器上下文中通讯的机制。
* [Indexed DB](https://dvcs.w3.org/hg/IndexedDB/raw-file/default/Overview.html) - 一个异步的客户端的存储API，可以快速访问大量结构化数据。
* [Online State](http://www.w3.org/html/wg/drafts/html/CR/browsers.html#browser-state) - 关于网络状态在线和离线的事件。
* [Web SQL](http://www.w3.org/TR/webdatabase/) - 使用一种类似SQL的语言在客户端存储数据。
* [Web Sockets](http://www.w3.org/TR/websockets/) - 一种基于持续TCP链接的低成本的与web服务器间双向通信的协议。
* [Web Storage](http://dev.w3.org/html5/webstorage/) - 一种简单的客户端同步存储API，可以用于存储键值对。
* [XMLHttpRequest](http://www.w3.org/TR/XMLHttpRequest/) - 在HTTP协议下，可编程式地在客户端和服务器之间传输数据。

## 性能 & 优化
* [Navigation Timing](http://www.w3.org/TR/navigation-timing/) - 获取关于导航和元素相关的定时信息。
* [Page Visibility](http://www.w3.org/TR/page-visibility/)<sup><a href="#a">[a]</a></sup> - 用编程方式决定一个web应用的视觉状态。
* [Resource Timing](http://www.w3.org/TR/resource-timing/) - 获取和HTML元素相关的定时信息。
* [Selectors Level 1](http://www.w3.org/TR/selectors-api/)和[Level 2](http://www.w3.org/TR/selectors-api2/) - 使用CSS选择器从DOM中检索元素。
* [Typed Array 1.0](http://www.khronos.org/registry/typedarray/specs/latest/) - 为性能关键性任务提供的原始二进制数据类型。
* [Web Workers](http://www.w3.org/TR/workers/) - 在页面中并行运行脚本。

## 设备 & 硬件
* [Device Orientation Events](http://www.w3.org/TR/orientation-event/) - 提供设备物理定向和运行相关消息的DOM事件。
* [Fullscreen](http://fullscreen.spec.whatwg.org/) - 用编程方式控制页面上的一个元素以全屏方式显示。
* [Geolocation](http://www.w3.org/TR/geolocation-API/) - 获取主机设备相关的地理位置信息。
* <a href="http://www.w3.org/TR/html5/forms.html#date-and-time-state-(type=datetime)">HTML5 Date and Time state for input element</a><sup><a href="#a">[a]</a></sup> - 控制数据和时间输入类型的选择器。
*  <a href="http://www.w3.org/TR/html5/forms.html#telephone-state-(type=tel)">HTML5 Telephone, Email and URL state for input element</a><sup><a href="#a">[a]</a></sup> - 控制电话，邮箱和URL输入类型的选择器。
* [Media Capture and Streams](http://www.w3.org/TR/mediacapture-streams/)<sup><a href="#a">[a]</a></sup> - 提供访问本地媒体流，包括视频（摄像头）和音频（麦克风）。
* [Screen Orientation](http://www.w3.org/TR/screen-orientation/) - 提供屏幕方向锁定并提供访问屏幕方向的数据和事件。
* [Touch Events](https://dvcs.w3.org/hg/webevents/raw-file/v1/touchevents.html) - 用编程的方式处理触摸事件。
* [Vibration](http://www.w3.org/TR/vibration/) - 可编程地控制设备的震动机制。
* [Web Notifications](http://notifications.spec.whatwg.org/) - 使用设备原生的通知机制（例如，Android上的状态条）向用户展示消息。

# <a class="doc-anchor" id="Experimental-APIs"></a>实验性APIs

除了标准的API，Crosswalk提供了额外的实验性或有可能被标准化的API来更好地支持编译实验原生应用。

* [Device Capabilities](http://www.w3.org/2012/sysapps/device-capabilities/) - 访问设备信息
* [Launch Screen](/documentation/manifest/launch_screen.html) - 应用启动时展示一个静态用户界面，当应用可以运行时将其隐藏。
* [Presentation API](http://w3c.github.io/presentation-api/)<sup>[\[a\]](#a)</sup> - 在web应用中获取外部显示器。可通过该教程开始：[Presentation API Tutorial](/documentation/apis/presentation_api_tutorial_zh.html)。更多信息请参见[开发文档](https://github.com/crosswalk-project/crosswalk-website/wiki/Presentation-api-manual)。
* [Raw Sockets](http://www.w3.org/TR/raw-sockets/) - 客户端和服务器端原始的TCP和UDP套接字。
* [SIMD](http://tc39.github.io/ecmascript_simd/) - 用于获取普通CPU架构上的单指令多数据（SIMD）指令集的数据类型和操作，例如SSE(IA32/X64)和NEON (ARMv7.这个实现目前还没有完成）。白皮书：[SIMD in Javascript via C++ and Emscripten](https://www.google.com/url?q=https%3A%2F%2Fdocs.google.    com%2Fviewer%3Fa%3Dv%26pid%3Dsites%26srcid%3DZGVmYXVsdGRvbWFpbnx3cG12cDIwMTV8Z3g6NTkzYWE2OGNlNDAyMTRjOQ)。
  * [WebCL](https://www.khronos.org/registry/webcl/specs/1.0.0/)<sup>[\[a\]](#a)</sup><sup><a href="#v">[13.41.304.0]</a></sup> - 绑定Khronos OpenCL标准的JavaScript。它使得web应用能在并行处理过程中访问利用GPU和多核CPU。
  <small>本页面中标准API部分的一些内容改编自[chromestatus.com](http://www.chromestatus.com/), &copy; 2013 The Chromium Authors,在[Creative Commons Attribution 2.5 license](http://creativecommons.org/licenses/by/2.5/)许可下使用。</small>
