# 常问问题

如果你有任何下文中没有提及的问题，可以通过crosswalk-help邮箱列表发布。或者可以通过#crosswalk IRC频道直接联系我们。详细信息请参见[社区页面](/documentation/community.html)。

### <a class="doc-anchor" id="Contents"></a>Contents

*   [项目背景](#Background-to-the-project)
*   [使用Crosswalk项目的方式](#Ways-to-use-the-Crosswalk-Project)
*   [发布Crosswalk项目的应用](#Distributing-Crosswalk-Project-applications)
*   [Canvas和WebGL支持](#Canvas-and-WebGL-support)
*   [嵌入式 API](#Embedding-API)
*   [iOS 支持](#iOS-Support)
*   [Crosswalk项目社区](#The-Crosswalk-Project-community)
*   [商业方面](#Commercial-aspects)
*   [与其他项目的关联](#Relationships-with-other-projects)
*   [常见问题](#Common-issues)

## <a class="doc-anchor" id="Background-to-the-project"></a>项目背景

### <a class="doc-anchor" id="What-is-the-Crosswalk-Project-for"></a>Crosswalk项目是什么？

如果你是一名从事web技术的发开人员，Crosswalk项目使你能够通过一个其专有运行环境来部署一个web应用。这意味着三件事情：

1.  你可以通过app商店来发布你的web应用。
2.  只要你控制着运行和它的升级周期，你的应用便不会中断任何过时的网页视图或者你的应用受众使用的浏览器。
3.  构建应用时不必过分担心运行环境差异和特例：你只需要处理一种运行换将。

### <a class="doc-anchor" id="Is-this-a-runtime-like-Java-or-Visual-Basic"></a>这是一个像Java或者Visual Basic一样的运行环境吗？

不，因为Crosswalk项目是基于W3C标准:HTML5,CSS和JavaScript。不同于之前的运行环境支持的语言，W3C标准是在多环境下，由多个公司以开源和商业形式发布。大范围的开源和商业工具、项目均支持开发过程。当你使用Crosswalk项目作为应用运行环境时，你正在加入一个成长中的生态系统。

### <a class="doc-anchor" id="If-my-apps-need-W3C-standards-why-not-target-a-browser"></a>如果我的app需要W3C标准，为什么不指定一款浏览器？

浏览器确实能够很好的支持W3C标准，但是它们却不能支持[Systems Applications Working Group](http://www.w3.org/2012/sysapps/)上的API。这是因为这些API获取平台特征，如果已知为一个网站并且结合浏览器可获取的其他数据，将会允许侵犯用户的隐私。因为Crosswalk项目的应用拥有一种不同的安全模型，其中用户可以选择给予某个应用哪些权限，同时系统应用的API*可以*被支持。同时，这样也使得Crosswalk项目可以运行那些不可能在开放的web上运行的应用。

### <a class="doc-anchor" id="Isnt-the-Crosswalk-Project-just-going-to-mean-more-fragmentation-of-the-web"></a>Crosswalk项目难道仅仅意味着网络的碎片化？

不，因为：

* Crosswalk项目并不针对web：它只是恰巧针对那些使用HTML5,CSS和JS完成的项目。
* 使用Crosswalk项目运行环境的项目了解它们被构建的环境。运行实现的微小差异（例如，一个平台可用传感器而另一个却不可以）也可以很容易地被开发者管理。
* 我们不打算复刻Blink，Chromium底层的渲染引擎。
* 我们会定期地重建Blink的新版本。
* 如果一个变化对于Chromium是有意义的，我们将会提交它。

## <a class="doc-anchor" id="Ways-to-use-the-Crosswalk-Project"></a>使用Crosswalk项目的方式

### <a class="doc-anchor" id="Can-I-use-the-Crosswalk-Project-to-appify-my-website"></a>我可以使用Crosswalk项目“app化”我的网站吗？

是的。你可以使用一种Crosswalk项目运行时包装一个网站的网址，这样它便看起来像个app（全屏，没有浏览器的chrome，home键等）。

### <a class="doc-anchor" id="Can-I-customise-the-Crosswalk-Project"></a>我可以自定义Crosswalk项目吗？

可以。Crosswalk项目自身是可以被修改的，因为代码是开源的。我们积极地估计[贡献](https://crosswalk-project.org/contribute/index.html)。

或者，你可以通过它的[扩展机制](https://github.com/crosswalk-project/crosswalk-website/wiki/Crosswalk-Extensions)为Crosswalk项目添加新的额外功能，这样便可以不必修改代码。这使得一个应用可以通过源码（Android的Java，Tizen的C/C++）访问平台的特征，并且超越了web运行时的边界。

## <a class="doc-anchor" id="Distributing-Crosswalk-Project-applications"></a>发布Crosswalk项目的应用

### <a class="doc-anchor" id="How-big-is-the-Crosswalk-Project-runtime-and-how-will-it-affect-my-applications-size"></a>Crosswalk项目运行时占用多大空间，同时它将怎样影响我的应用程序的大小？

粗略估算，[一个项目的样例应用](https://github.com/crosswalk-project/crosswalk-samples/tree/master/hello_world)中的HTML/JS/CSS文件大概占据24kb的磁盘空间。

一旦这个应用通过它的Crosswalk 10 (x86 Android)运行时打包，apk文件的大小为~20Mb。安装完成后，整个应用占据~58Mb的磁盘空间。

### <a class="doc-anchor" id="Can-one-Crosswalk-installation-be-shared-between-multiple-applications"></a>一个Crosswalk安装包可以在多个应用之间共享吗？

将运行时绑定应用（又称为“嵌入式模式”）是解决分布式的最简单的方法。但是Crosswalk应用*可以*共享一个Crosswalk运行时库（“共享模式”）；其中确保共享模式的包包含在Crosswalk中部署Android的部分。然而你，你需要自己部署这个共享的运行时。

### <a class="doc-anchor" id="How-can-I-distribute-a-Crosswalk-Android-application-across-multiple-architectures"></a>如何在多个架构上部署Crosswalk的Android应用？

Crosswalk的二进制文件是针对特征架构的。这意味着在安装x86芯片的Android设备上，你需要一版x86兼容的Crosswalk；在安装了ARM芯片的Android设备上需要一个ARM兼容的Crosswalk。

其中存在两种方法来构建兼容支持x86和ARM的应用：

*   为你的应用创建两个分离的包，一个针对x86，另一个针对ARM；然后将其上传到你的应用所在的app商店。主流的商店，例如google play，均支持[针对不同的平台上传多个包](http://developer.android.com/google/play/publishing/multiple-apks.html).

    [Crosswalk apk 生成脚本](/documentation/android/run_on_android.html) (`make_apk.py`)为了推进这种工作方式可以为两种架构生成相应的包。

*   为你的应用创建一个包，但是其中并不包含x86和ARM版本的Crosswalk。这种方法的缺点是导致包文件较大（在添加应用代码之前40Mb）。

    这种创建包的方式需要你将Crosswalk中的共享文件对象复制到两种架构中的 apk包文件的`lib/`目录下。例如，一个支持x86和ARM的apk将包含下列文件：  

        lib/armeabi-v7a/libxwalkcore.so
        lib/x86/libxwalkcore.so

    你如何做到这一点取决于你的构建过程。如果你需要一个参考，请参见[Cordova迁移说明](/documentation/cordova/migrate_an_application.html#Multi-architecture-packages)，其中解释了如何在Crosswalk Cordova的环境下做到这一点。  

### <a class="doc-anchor" id="Which-platforms-does-Crosswalk-support"></a>Crosswalk支持哪些平台？

Crosswalk官方支持[Android (版本 4.0 和以上)](/documentation/android.html), [iOS](/documentation/ios.html), [Linux 桌面 (deb)](/documentation/linux.html), and [Tizen 3.0 (通用和IVI配置文件)](/documentation/tizen.html).预编译的程序包可以用于这些平台。详见[下载页面](https://crosswalk-project.org/documentation/downloads.html)。支持Windows 10 桌面版的工作正在进行中。

### <a class="doc-anchor" id="How-to-use-Crosswalk-on-a-project-using-ProGuard"></a>怎样在使用了ProGuard的工程中使用Crosswalk？

不要使用ProGuard来混淆xwalk的核心库，因为这将会产生一个“破损的JNI链接”的错误。请将下列ProGuard规则添加到proguard-project.txt文件中：

    -keep class org.xwalk.core.** {
        *;
    }
    -keep class org.chromium.** {
	    *;
    }
    -keepattributes **

## <a class="doc-anchor" id="Canvas-and-WebGL-support"></a>Canvas和WebGL支持

### <a class="doc-anchor" id="Why-wont-WebGL-work-in-Crosswalk-on-my-device"></a>为什么在我的设备上WebGL不可以运行在Crosswalk中？

Chromium有一个关于运行WebGL时造成稳定和／或者一致性问题的GPU黑名单。如果运行设备上存在位于黑名单的GPU，Chromium则禁用WebGL。

Crosswalk使用相同的一份黑名单。所以如果Crosswalk运行在一个存在位于黑名单的GPU的设备时，默认情况下WebGL会被禁用。

关于哪些GPU以及何时被禁用的详细信息请参见 [Khronos WebGL 维基](http://www.khronos.org/webgl/wiki/BlacklistsAndWhitelists#Chrome).

### <a class="doc-anchor" id="Can-I-force-Crosswalk-to-enable-WebGL"></a>我可以在Crosswalk上强制运行WebGL吗？

如果你想在一个包含黑名单中GPU的设备上使用WebGL测试一个应用，这里存在一个可用的变通方法：将`--ignore-gpu-blacklist`命令行选项传递到`xwalk`
二进制文件。但是如果在一个应用中Crosswalk被嵌入直接作为本地库，你便不可以直接这样做（例如：使用Crosswalk Cordova, Crosswalk Android工具包，或者使用嵌入式的API。

然而，你可以通过在你的Android`apk`包的`assets/`目录下添加一个叫做`xwalk-command-line`的文件来使用一个自定义的命令行（Crosswalk 6 或者更高版本）。这个文件应该包含一行，代表`xwalk`命令行执行；这种情况下，这一行应该：

    xwalk --ignore-gpu-blacklist

（如果需要，其他命令行参数可以被添加到文件中。）

关于添加文件到你的Android包中的方法依赖于你怎么使用Crosswalk：

*   如果你使用**[Android应用的嵌入式Crosswalk](/documentation/embedding_crosswalk.html)**，文件应该被放置到工程的`assets/`目录下。

*   如果你使用**[使用Crosswalk Cordova](/documentation/cordova.html)**，文件应该被放置到工程的`assets/`目录下。

*   如果你使用**[使用`make_apk.py`脚本创建Android包](/documentation/android/run_on_android.html)**，你可以传递一个参数创建一个在Android包输出内的文件：

    ```
    $ make_apk.py --manifest=mygame/manifest.json \
      --xwalk-command-line="--ignore-gpu-blacklist"
    ```

注意，在包含黑名单内GPU的平台上启用WebGL可能会导致应用（或者整个设备）冻结或者崩溃，所以在产品应用中并不推荐这种方法。

### <a class="doc-anchor" id="Why-is-canvas-performance-poor-on-my-device"></a>为什么canvas在我的设备上表现不佳？

如果设备包含一个[黑名单GPU](#Why-won't-WebGL-work-in-Crosswalk-on-my-device?)，canvas元素没有硬件加速。这将会导致较低的性能。[强制Crosswalk忽视GPU黑名单](#Can-I-force-Crosswalk-to-enable-WebGL?)虽然可以提高性能，但是可能会导致你的应用变得不稳定。

## <a class="doc-anchor" id="Embedding-API"></a>嵌入式API

### <a class="doc-anchor" id="Webview-compatibility"></a>我过去常常使用Crosswalk内嵌的API调用WebView方法，但是从Crosswalk 9开始不能使用了。为什么？

在Crosswalk ８的嵌入式API中不幸包含一些不是为了被访问的类和方法，因此从Crosswalk 9开始被设置为私有的。

从开始，我们就选择了不与现有的Android WebView API 100%兼容。这个决定背后的原因是，我们希望避免陷入支持旧版的那些不常出现在用例中API中。它们会对性能和重构，以及项目的提升产生负面影响。

Crosswalk项目的目标是为应用开发者提供最新的web技术，同时需要高性能和现代化。使其成为一个WebView技术上的直接替代却违背了这些目标。

换句话说，我们非常欢迎反馈并扩展我们的API。

如果你有特殊的需求，你应该在Crosswalk项目的JIRE上创建一个功能需求，指定你对于API的用例和需求。

如果API阻止你升级到最新版本的Crosswalk，请解释原因然后我们会尽量调整相应的优先级。

## <a class="doc-anchor" id="iOS-Support"></a>iOS 支持

### <a class="doc-anchor" id="Why-use-iOS"></a>为什么当我创建一个iOS web应用时应该使用crosswalk？

Crosswalk对于iOS的优势包括：
<ul><li>这是基于最新的WKWebView的性能增强版的web运行时</li>
    <li>跨平台（安卓，iOS等）统一的web应用的创建和维护体验</li>
    <li>混合应用开发人员以一种更容易的方式来扩展他们自己的Web的应用程序编程接口</li>
    <li>可以使用Cordova插件的能力</li>
</ul>

### 在Android和iOS之间哪些功能是不同的？我的Android＋Crosswalk的应用可以运行在iOS上吗？

在Android环境下，Crosswalk使用的是一个基于Chromium的web引擎。iOS的存储限制阻止了我们使用Chromium引擎。因此，Crosswalk在iOS上依赖于WKWebView。两种引擎的区别会导致HTML5特征的不同，某些Crosswalk特征的不同（例如WebCL和SIMD.js）不能被iOS支持。统一Web应用程序接口的Crosswalk应该减少Android和iOS平台之间的差异，对于大部分功能，你未更改的Android Web应用应该也可以iOS上运行。

### Crosswalk支持哪些版本的iOS？	

iOS 8 或者更新版本

### 我需要一个Mac来创建关于iOS的web应用吗？我可以在相同的系统上创建Android和iOS应用吗？ 	

是的，你需要一个Mac来通过Crosswalk来创建iOS应用。你也可以通过使用Crosswalk的搭建工具来安装Android SDK并创建Android应用，虽然关于这个环境的创建还没有写入文档。

### 现在我可以使用Cordova来创建我的iOS应用。那么这个和Crosswalk有什么区别呢？我可以使用两个吗？

Crosswalk是基于WKWebView，其中WKWebView支持很多新的HTML5特征并且速度比Cordova使用的UIWebview快到10x倍。Crosswalk也支持Cordova插件机制，这样保证了应用程序可以使用大量的Cordova插件和新功能。虽然还未经测试，第三方插件应该也可以工作。

### 使用Crosswalk创建app的基本流程？

**Web应用开发人员:**

 * 使用 `crosswalk-app-tool create` 创建一个模板项目
 * 编写你的HTML5源码并将其放在正确的目录下
 * 运行 `crosswalk-app-tool build` 将它们创建成iOS应用

**混合应用开发人员:**

 * 创建你自己的iOS应用工程
 * 在你的工程中引入 `XWalkView.xcodeproj` 
 * 插入 `XWalkView` 目标作为你应用目标的框架 as a framwork 
 * 如果你有任何的扩展，将扩展框架插入到你的应用中Embed 
 * 应用本身:
  * 添加 `XWalkView` 到你的视图控制作为主要视图
  * 创建扩展并下载
  * 下载你的入口页

详细信息请参考页面 [Crosswalk项目关于iOS](/documentation/ios.html)。
	
### 关于iOS的Crosswalk项目会被发布到CocoPods上吗？	

这个是我们的计划并且希望我们可以尽快实现它。


****************************************************************





## <a class="doc-anchor" id="The-Crosswalk-Project-community"></a>Crosswalk项目社区

### <a class="doc-anchor" id="Who-is-using-the-Crosswalk-Project"></a>谁在使用Crosswalk项目？

Crosswalk虽然还是一个年轻的项目，但是发展势头很大。现在在app商店已经有超过300款应用（大部分是游戏）是基于Crosswalk创建的。 

### <a class="doc-anchor" id="How-often-is-the-Crosswalk-Project-released"></a>Crosswalk项目多久发布一次？

Crosswalk每六周会更新到最新的Chromium。实际上，这意味着在一个功能出现在Chromium和出现在Crosswalk中最大的时间间隔就是六周。

更多的详细信息，请参见[关于Crosswalk如何关联Chromium的说明](https://github.com/crosswalk-project/crosswalk-website/wiki/Downstream-Chromium).

### <a class="doc-anchor" id="Can-I-get-involved"></a>我可以参与吗？

是的，我们欢迎来自任何希望项目越来越好的人的贡献，无论是写代码，上传bug，或者添加文档。关于如何参与的具体细节请参见[Crosswalk网站](https://crosswalk-project.org/contribute/index.html).

## <a class="doc-anchor" id="Commercial-aspects"></a>商业方面

### <a class="doc-anchor" id="Do-I-have-to-pay-for-the-Crosswalk-Project"></a>我必须支付Crosswalk工程吗?

不，Crosswalk是一个发布在[github](https://github.com/crosswalk-project/crosswalk)上的开源项目，被[BSD licence](https://github.com/crosswalk-project/crosswalk/blob/master/LICENSE)认证。无论是商业或者其他目的，它均是被免费使用的。

### <a class="doc-anchor" id="If-Im-not-paying-for-the-Crosswalk-Project-who-is"></a>如果我没有支付Crosswalk项目，那么是谁在支付？

Crosswalk的开发工作大部分室友Intel赞助，但是是基于顶层的[Chromium](http://www.chromium.org/)开发。

### <a class="doc-anchor" id="Can-I-get-commercial-support-for-the-Crosswalk-Project"></a>我能获取Crosswalk项目的商业支持吗？

现在虽然还不可以，但是如果你需要我们希望收到你的消息。

## <a class="doc-anchor" id="Relationships-with-other-projects"></a>与其他项目的关联

### <a class="doc-anchor" id="How-does-Crosswalk-relate-to-the-Intel-XDK"></a>Crosswalk如何关联Intel的XDK？

[Intel XDK](http://xdk-software.intel.com/)是一个针对HTML5应用的开发环境（IDE）。当一个开发人员使用XDK来创建一个应用时，他们可以选择将他们的应用导出成一个Crosswalk的Android包（apk）。这种通过XDK方式创建的HTML5应用，捆绑自己的Crosswalk运行时。

### <a class="doc-anchor" id="Is-Crosswalk-a-replacement-for-Phonegap-Cordova"></a>Crosswalk是Phonegap或者Cordova的替代吗？

不，他们是互补的。如果你想创建一个多平台（除了Android和Tizen)，需要大量的文档和一个很成熟的组织，Cordova可能是一个更好的选择。如果你对在图形硬件加速的WebGL的支持和前言的HTML5特征有兴趣，Crosswalk可能是一个更好的选择。

话虽如此，如果你愿意，可以通过了解[从Crosswalk使用Cordova的应用程序接口](/documentation/cordova.html)获得一个两全其美的方法。

### <a class="doc-anchor" id="Does-Crosswalk-for-Android-use-the-Android-webview"></a>针对Android的Crosswalk使用的是否是Android的webview？

不，Crosswalk是一个有效的Chromium的修订版本，其中Chromium是Google的Chrome浏览器的开源基础。

### <a class="doc-anchor" id="Why-do-I-need-Crosswalk-now-that-Android-KitKat-and-later-has-a-Chrome-based-webview"></a>既然Android（KitKat和以后）已经有了基于Chrome的web试图，为什么我现在还需要使用Crosswalk呢？

Crosswalk提供了Chrome所支持的[齐全的现代web应用程序访问接口](/documentation/apis/web_apis.html)。相反，基于Chrome的Android web视图[缺少某些特征](https://developers.google.com/chrome/mobile/docs/webview/overview#does_the_new_webview_have_feature_parity_with_chrome_for_android)，但是这些特征在Android平台上的Chrome中是可用的。 

在此之上，Crosswalk还添加了一些Chrome或者Android的web视图都不能提供的新特征，例如对于[SIMD](https://01.org/blogs/tlcounts/2014/bringing-simd-javascript)的实验性支持，对于[演示API](https://github.com/crosswalk-project/crosswalk-website/wiki/presentation-api-manual)的支持等.

### <a class="doc-anchor" id="Why-use-Blink-vs-the-higher-level-Chromium-Embedded-Framework-as-a-basis-for-Crosswalk"></a>为什么使用Blink和更高层的Chromium嵌入式框架作为Crosswalk的基础？

虽然[CEF 1.0](https://code.google.com/p/chromiumembedded/)已经证明很受欢迎，但是也正在逐渐被CEF 3.0所[淘汰](http://www.magpcss.org/ceforum/viewtopic.php?f=10&t=10647&sid=510426ccd8a9650f72ba416d7b51de06)。因为我们希望在Crosswalk项目上有一致性的实现，所以必须选择一层能容纳所有用例的Chromium架构。通过从Blink开始和构建，而不是从CEF 3.0开始并移除构建，我们认为最终将会得到一个更严格，更一致的结果。

### <a class="doc-anchor" id="When-should-I-use-Chromes-new-packaged-apps-rather-than-Crosswalk"></a>我什么时候应该使用Chrome新的打包应用程序而不是Crosswalk呢？

使用Chrome的打包应用程序，你可以访问Chrome的app商店和Chrome提供的功能。

通过Crosswalk工程，你有不同的可能：

* 如果你在构建一个平台，你可以引入一个Crosswalk应用运行时作为你自己应用目录的服务器。
* 开发人员可以将应用和Crosswalk应用运行时一起打包，这样如果没有开发人员的许可，app和运行时将不允许被修改。

当然，因为Crosswalk是基于Blink和Chromium的，所以开发人员通过Crosswalk和Chrome均可以发布标准的HTML5的应用。

## <a class="doc-anchor" id="Common-issues"></a>常见问题

### <a class="doc-anchor" id="Switch-to-shared-mode"></a>我使用嵌入式的方式打包我的应用，但是当打开应用时却要求从Play商店下载Crosswalk。为什么？

最可能的是，你已经安装了一个为x86设备的ARM架构创建的APK。当这种情况发生时，Crosswalk尝试下载x86版本的运行时来启动应用。为了避免这种情况，确认你的应用的x86版本已经在Play商店发布。

