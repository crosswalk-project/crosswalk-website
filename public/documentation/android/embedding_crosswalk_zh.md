# Crosswalk嵌入模式

Crosswalk Embedding API允许您将Crosswalk runtime嵌入到应用中。在嵌入的rutime中，您可以加载一个网页，用法类似于安卓[WebView](http://developer.android.com/guide/webapps/webview.html)。

当您的应用中有大量的Java代码，但是又想要使用Web技术来写UI界面时，我们才推荐您使用Embedding API。如果您仅仅希望打包您的Web应用到安卓平台，有两种方式可以选择：

*   [使用Crosswalk默认的打包工具](/documentation/android/build_an_application_zh.html)来生成安卓应用包APK文件。
*   [使用带有Crosswalk的Cordova](/documentation/cordova_zh.html)：使用这种方式，您将可以使用标准的设备APIs，同时也可以使用先进的Web APIs，使用Web技术来编译您的应用。

如果您需要使用Embedding API, 请参照下面的步骤：

## 使用embedding API创建应用

本教程中叙述了如何使用嵌入式Crosswalk Webview来创建安卓应用。

在开始教程之前，您需要对安卓开发环境熟悉。因为通常是使用[ADT](http://developer.android.com/tools/sdk/eclipse-adt.html)创建安卓应用，本教程也使用了这个工具；因此熟悉此工具将会非常有用。

教程中的所有步骤都已经在Linux (Fedora 20)平台上测试过，因此本教程也适用于其他平台和操作系统(比如Windows)。

**阅读完本教程**，您将可以开发含有嵌入式Crosswalk runtime的安卓应用。

### 配置开发主机以及目标机

在使用Embedding API之前，请确保您已经[为主机配置过安卓开发环境](/documentation/android/system_setup_zh.html)。

您也需要设置能够运行安卓应用的目标机，请参见[配置安卓目标机](/documentation/android/android_target_setup_zh.html)页面。

由于教程使用了ADT，请确保您的主机上已经[安装必要的ADT插件](http://developer.android.com/tools/sdk/eclipse-adt.html)。

### 下载Crosswalk WebView Bundle

<button onclick="location.href = '/documentation/downloads_zh.html';">下载</button>

下载Crosswalk WebView Bundle到开发机中。Webview Bundle包含一些在嵌入式Crosswalk应用中必须的库以及支持工具。注意WebView是区分CPU架构的，所以您可能需要编译出基于ARM,x64以及64位平台上的多个APK。

解压下载的`.zip`文件。

### 导入Crosswalk webview到ADT

下一步是通过导入的解压文件Crosswalk webview bundle,在ADT中创建一个工程。您可以参考下面的工程，编译属于您自己的Crosswalk嵌入模式应用。

创建工程：

1.  打开ADT。

2.  选择*File* > *New* > *Project...*, 然后*Android* > *Android Project From Existing Code*。

3.  设置*Root Directory*到解压的`crosswalk-webview/`目录下。

4.  单击*Finish*。**crosswalk-webview**工程将会在*Package Explorer*中呈现出来。

### 用ADT创建安卓应用

接下来，创建一个将会使用Crosswalk Embedding API的安卓应用。(依然在ADT中)：

<ol>
  <li>
    <p>选择<em>File</em> &gt; <em>New</em> &gt; <em>Android Application Project</em>.</p>

    <p>在<em>New Android Application</em>弹出框中填入如下的类似内容：</p>

    <ul>
      <li><em>Application Name:</em> <strong>XWalkEmbed</strong></li>
      <li><em>Project Name:</em> <strong>XWalkEmbed</strong></li>
      <li><em>Package Name:</em> <strong>org.crosswalkproject.xwalkembed</strong></li>
      <li><em>Minimum Required SDK:</em> <strong>API 14</strong> (Crosswalk支持的最低版本)</li>
      <li><em>Target SDK:</em> <strong>API 19</strong> (或者您环境中有的版本)</li>
      <li><em>Compile With:</em> <strong>API 19</strong></li>
      <li><em>Theme:</em> <strong>None</strong></li>
    </ul>

    <p>如下图所示：</p>

    <img src="/assets/embedding-api-app1.png">

    <p>点击<em>Next</em>.</p>
  </li>

  <li>
    <p>在<em>Configure Project</em> 面板中, 设置如下：</p>

    <ul>
      <li>不打钩<em>Create custom launcher icon</em>.</li>
      <li>打钩<em>Create activity</em>.</li>
      <li>不打钩 <em>Mark this project as a library</em>.</li>
      <li>打钩create the project或者选择系统中其他路径。Add to working sets可以随意。</li>
    </ul>

    <p>结果如下图所示：</p>

    <img src="/assets/embedding-api-app2.png">

    <p>点击<em>Next</em>.</p>
  </li>

  <li>
    <p>在<em>Create Activity</em>面板中, 选择<em>Create Activity</em>和<em>Blank Activity</em>:</p>

    <img src="/assets/embedding-api-app3.png">

    <p>点击 <em>Next</em>.</p>
  </li>

  <li>
    <p>在<em>Blank Activity</em>中填入如下类似内容：</p>

    <ul>
      <li><em>Activity Name:</em> <strong>MainActivity</strong></li>
      <li><em>Layout Name:</em> <strong>activity_main</strong></li>
      <li><em>Fragment Layout Name:</em> <strong>fragment_main</strong></li>
      <li><em>Navigation Type:</em> <strong>none</strong></li>
    </ul>

    <p>结果如下图所示：</p>

    <img src="/assets/embedding-api-app4.png">

    <p>点击<em>Finish</em>.</p>
  </li>
</ol>

您的工程现在已经可以工作了。

### 在工程中引用Crosswalk Webview

下一步是添加Crosswalk webview到您的应用工程中

具体如下(在ADT)：

1.  右键点击*Package Explorer*并选择*Properties*.

2.  选择*Android*。

3.  在*Library*下，点击*Add*。选择**crosswalk-webview-${XWALK-BETA-ANDROID-X86}-x86**并点击OK。

点击Ok。

您的工程就可以链接到Crosswalk webview。

### <a class="doc-anchor" id="Add-code-to-integrate-the-webview"></a>添加代码，集成webview

1.  Crosswalk在安卓上需要一些权限。打开这些权限，需要修改`AndroidManifest.xml`文件， 在`<application>`元素上面添加权限。Crosswalk WebView最少需要如下权限去取渲染页面：
    ```
    <uses-permission android:name="android.permission.ACCESS_NETWORK_STATE" />
    <uses-permission android:name="android.permission.INTERNET" />
    <uses-permission android:name="android.permission.ACCESS_WIFI_STATE" />
    ```
    
    您可以添加其他权限，这取决您的应用开发需求。不过，只添加应用真正需要的权限，是一种公认的安全做法。
    
    *获取地理位置信息*
    ```
    <uses-permission android:name="android.permission.ACCESS_FINE_LOCATION" />
    ```
    
    *获取照相机，摄像机以及麦克风*
    ```
    <uses-permission android:name="android.permission.CAMERA" />
    <uses-permission android:name="android.permission.MODIFY_AUDIO_SETTINGS" />
    <uses-permission android:name="android.permission.RECORD_AUDIO" />
    ```
    
    *写数据到SD Card*
    ```
    <uses-permission android:name="android.permission.WRITE_EXTERNAL_STORAGE" />
    ```
    
    *保持cpu运行状态*
    ```
    <uses-permission android:name="android.permission.WAKE_LOCK" />
    ```

2.  当生成application时，一些默认的布局资源会添加到工程中。用下面的内容替换主布局文件中的内容，`res/layout/activity_main.xml`：

    ```
    <org.xwalk.core.XWalkView android:id="@+id/activity_main"
      xmlns:android="http://schemas.android.com/apk/res/android"
      android:layout_width="fill_parent"
      android:layout_height="fill_parent">
    </org.xwalk.core.XWalkView>
    ```

    这将会用`XWalkView` (Crosswalk webview)资源去替换应用默认的view。

    您可以移除其他的文件，例如`res/layout/fragment_main.xml`，如果您不需要它。

3.  修改`MainActivity`类 (通过*Package Explorer*， 在`src/org.crosswalkproject.xwalkembed/MainActivity.java`中找到它)。用下面的内容替换默认内容：

    ```
    package org.crosswalkproject.xwalkembed;

    import org.xwalk.core.XWalkView;

    import android.app.Activity;
    import android.os.Bundle;

    public class MainActivity extends Activity {
      private XWalkView mXWalkView;

      @Override
      protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        mXWalkView = (XWalkView) findViewById(R.id.activity_main);
        mXWalkView.load("http://crosswalk-project.org/", null);
      }
    }
    ```

    `R.layout.activity_main`指的是您在`activity_main`里面定义的布局文件： 通过Embedding API暴漏出来的Crosswalk runtime中webview部分。Activity的主content view就被设置成了这个webview。

    上面的代码把布局模块设置成`XWalkView`，因此加载Web资源的方法就可以调用了。在这个例子中，调用了`load()`方法去加载Crosswalk官方网站并且在WebView模块中渲染该网页。请注意`loadAppFromManifest()`方法也是可以调用的，这个方法通过manifest文件加载Crosswalk应用。详情参见[Embedding API文档](/apis/embeddingapidocs/reference/org/xwalk/core/XWalkView.html)。

您现在可以运行应用了，像平常一样：右键点击*Package Explorer*中的工程，选择 *Run As* > *Android Application*。

### 在Crosswalk webview中加载页面

在之前的章节中，您在webview中加载了一个页面，证明了Android应用可以"加载"网站。然而，如果你想让Web应用作为Android应用的一部分运行，而不是在网站上运行网页，那么，您可以在Crosswalk webview中加载本地资源。

加载本地Web资源，您需要把Web文件添加到安卓应用里面作为应用包(`.apk`文件)的一部分。

参照下面步骤，添加Web资源并把它们和您的应用打包在一起：

1.  创建`assets/`目录。这是Android应用资源的标准路径，因此存放Web资源比较合适。

2.  添加具有如下内容的`index.html`文件到`assets/`目录下：

    ```
    <!DOCTYPE html>
    <html>
      <head>
        <meta name="viewport"
              content="width=device-width, initial-scale=1.0">
        <meta charset="utf-8">
        <title>simple</title>
      </head>
      <body>
        <p>hello world</p>
      </body>
    </html>
    ```

3.  修改`MainActivity`类(`src/org.crosswalkproject.xwalkembed/MainActivity.java`)：

    ```
    package org.crosswalkproject.xwalkembed;

    import org.xwalk.core.XWalkView;

    import android.app.Activity;
    import android.os.Bundle;

    public class MainActivity extends Activity {
      private XWalkView mXWalkView;

      @Override
      protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        mXWalkView = (XWalkView) findViewById(R.id.activity_main);

        // this loads a file from the assets/ directory
        mXWalkView.load("file:///android_asset/index.html", null);
      }
    }
    ```

    这里主要的不同是传到`load()`方法中的参数是一个本地资源的URL，而不是一个远程的Web URL：

    ```
    mXWalkView.load("file:///android_asset/index.html", null);
    ```

    URL是以"file:///android_asset/"为前缀，后面添加上您需要加载的文件路径，路径名字是相对于`assets/`目录。

    当首页HTML网页加载之后，网页中的其他URLs将会被当做标准的Web应用来处理，同时会把`assets/`目录作为应用的根目录。例如，如果在`assets/`目录下您有一个`page2.html`的网页，您可以用一个标准的超链接来链接到这个网页：

    ```
    <a href="page2.html">Page 2</a>
    ```

    类似地，任何CSS或者媒体文件(音频，视频)文件都可以被添加到`assets/`目录并像平常那样使用，例如：

    ```
    <!--
    index.html文件中的音频标签指向文件
    assets/audio/mytrack.ogg
    -->
    <audio src="audio/mytrack.ogg">

    /*
    index.html中的CSS声明指向图片
    assets/images/myimage.png
    */
    .myclass {
      background-image: url(images/myimage.png);
    }
    ```

### 使用Proguard压缩APK文件 (可选)
ProGuard是一个压缩、优化和混淆Java字节码文件的免费的工具。在生成您最终的APK文件时，这些选项是可选的。如果您针对您的Java代码编写了一些Proguard配置文件，那么或许您也想要添加一些Proguard规则来压缩Crosswalk库的大小： <a href="/documentation/samples/proguard-xwalk.txt">针对Crosswalk的Proguard规则</a>。

对Crosswalk使用Proguard的目的是压缩其大小而不是出于反编译的考虑。注意在Crosswalk中有很多直接把类名作为字符串处理的反射代码。因此压缩和混淆主要影响的是Chromium部分。 

<h3 id="Debugging">调试</h3>

调试运行在embedded Crosswalk webview中的Web程序，请参照下面内容修改`MainActivity.java`文件：

    package org.crosswalkproject.xwalkembed;

    import org.xwalk.core.XWalkPreferences;
    import org.xwalk.core.XWalkView;

    import android.app.Activity;
    import android.os.Bundle;

    public class MainActivity extends Activity {
      private XWalkView mXWalkView;

      @Override
      protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        mXWalkView = (XWalkView) findViewById(R.id.activity_main);

        // turn on debugging
        XWalkPreferences.setValue(XWalkPreferences.REMOTE_DEBUGGING, true);

        mXWalkView.load("file:///android_asset/index.html", null);
      }
    }

关键的一行是：

    XWalkPreferences.setValue(XWalkPreferences.REMOTE_DEBUGGING, true);

`XWalkPreferences.setValue()`是为Crosswalk设置全局参数的；在这个例子中，您打开的是调试选项。

现在您可以正常安装并运行程序。对于远程调试，请参考[这个章节](/documentation/android/android_remote_debugging_zh.html)。

## 更多信息

更多关于Embedding API的信息，请参考[Embedding API文档](/documentation/apis/embedding_api_zh.html)。
