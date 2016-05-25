# 共享模式 vs. 嵌入模式

默认情况下，Crosswalk运行时是被嵌入到你的应用内，并且包含在APK中。相反，“共享模式”在编译应用时不需要运行时，但是在后面运行的时候必须下载相关的运行时环境。“共享模式”允许多个Crosswalk应用共享一个运行时环境。如果设备还没有安装运行时环境，它将会从Google Play Store或者一个由开发者规定的具体地址处下载。
因为Crosswalk二进制文件不包含在应用的APK包中，所以它会明显小很多（~20MB）。

## <a class="doc-anchor" id="Pros"></a>优点

* 为Crosswalk应用生成一个明显容量更小的APK文件（大约小了20MB）。
* 在共享模式下编译生成的APK与具体的架构无关。只需上传一个包到Play Store。稍后再下载与架构相关的运行时环境。
* Crosswalk的更新与应用无关。使用最新版本时不需要更新软件。

## <a class="doc-anchor" id="Cons"></a>缺点

* 如果用户设备中没有安装Crosswalk运行时库，当应用第一次运行时它将会被下载。一个应用正常启动的过程将会被中断。
* 一个更新的Crosswalk运行时可能会引入一些与已经安装并且采用共享模式的应用之间的不兼容问题。开发者需要针对最新发布的Crosswalk，测试他们的应用。注意，我们尽力在维持向后的兼容性。

# <a class="doc-anchor" id="package"></a>用共享模式打包

## <a class="doc-anchor" id="Using-the-make_apk-packaging-tool"></a>make_apk打包工具

为了使用共享模式打包应用，请使用下列选项：

`./make_apk.py --mode=shared ...`

注意：目前crosswalk-app-tools还不支持共享模式

## <a class="doc-anchor" id="Using-Cordova"></a>编译Cordova

为了在共享模式下创建一个Cordova项目，请使用下列选项：

`./bin/create --xwalk-shared-library ...`

注意：cordova-plugin-crosswalk-webview目前还不支持共享模式

## <a class="doc-anchor" id="Using-an-IDE"></a>使用IDE

对于Eclipse ADT用户，添加库引用到指向xwalk_shared_library项目的路径。

对于Android Studi用户，引入xwalk_share_library作为依赖。

# <a class="doc-anchor" id="How-to-Configure-the-download-URL"></a>怎样配置下载链接

为了测试的目的，可能从一个特定的链接而不是Google Play Store下载Crosswalk运行时环境来配置应用。注意，无论在什么情况下，设备上只能存在一个已安装的运行时环境的拷贝，无论它是从哪里被下载。同时，只有已经得到用户许可可以通过不可信的来源进行安装，Crosswalk包才能被安装。

## <a class="doc-anchor" id="From-Packaging-Tool"></a>打包工具

在make_apk.py文件中，使用下列选项：

`./make_apk.py --mode=shared --xwalk-apk-url=http://host/XWalkRuntimeLib.apk ...`

## <a class="doc-anchor" id="From-The-Crosswalk-Manifest"></a>Crosswalk Manifest

定义键值为“xwalk_apk_url”的元素:

```
{
        "name": "XWalkApp",
            "start_url": "index.html",
                "xwalk_apk_url": "http://10.0.2.2/XWalkRuntimeLib.apk",
                    ...
}
```

## <a class="doc-anchor" id="From-The-Android-Manifest"></a>Android Manifest

在应用标签内部，定义名称为"xwalk_apk_url"的元数据：

```
<application android:name="org.xwalk.core.XWalkApplication">
    <meta-data android:name="xwalk_apk_url"
                   android:value="http://host/XWalkRuntimeLib.apk" />
                       ...
                       </application>
                       ```

# <a class="doc-anchor" id="Using-shared-mode-with-the-embedding-API"></a>在嵌入模式API中使用共享模式

使用嵌入模式API嵌入Crosswalk的开发者可以遵循下文的指南来更好地利用共享模式。

## <a class="doc-anchor" id="Permissions"></a>权限

共享模式需要下列权限的保障：

```
ACCESS_NETWORK_STATE
ACCESS_WIFI_STATE
INTERNET
WRITE_EXTERNAL_STORAGE
```

## <a class="doc-anchor" id="XWalkApplication"></a>XWalkApplication
 
 XWalkApplication对于从Crosswalk运行时环境库中获取资源很必要。你可以在Android manifest中直接使用XWalkApplication，或者让你的类继承XMwalApplication类。

 ```
 <application android:name="org.xwalk.core.XWalkApplication">
     ...
     </application>
     ```

## <a class="doc-anchor" id="XWalkActivity"></a>XWalkActivity

XWalkActivity可以帮助运行所有用于初始化Crosswalk环境的程序，如果需要还可以展示用于与终端用户交互的对话框。包含XWalkView对象的活动可能想要通过继承XWalkActivity来获取它的功能。对于那些活动，覆盖负责通知Crosswalk环境已经就绪的抽象方法onXWalkReady()很重要。 

在共享模式下，Crosswalk运行时库在活动已经被创建时还没有加载。所以开发者不能在onCreate()中使用嵌入模式API。所有对嵌入式API的使用通常都应该在onXWalkReady()中或者在onXWalkReady被调用后。

```
public class MyXWalkActivity extends XWalkActivity {
        @Override
            protected void onCreate(Bundle savedInstanceState) {
                        super.onCreate(savedInstanceState);

                              // Initialize the activity as usual
                                      setContentView(R.layout.activity_main);

                                              // DO NOT do more than creating an instance with embedding API
                                                      mXWalkView = (XWalkView) findViewById(R.id.xwalkview);
                                                          }

                                                              @Override
                                                                  protected void onXWalkReady() {
                                                                              // Do anything with embedding API
                                                                                      mXWalkView.load("index.html", null);
                                                                                          }
}
```

## <a class="doc-anchor" id="XWalkInitializer"></a>XWalkInitializer

XWalkInitializer可以像XWalkActivity一样，帮助初始化Crosswalk环境。但是与XWalkActivity不同的是，XWalkActivity既不提供帮助终端用户下载合适版本Crosswalk的交互界面，也不会提供提醒信息来通知在初始化过程中发生的具体错误。因为这个限制，XWalkInitializer只在嵌入模式中使用，或是在设备已安装适当版本的Crosswalk的共享模式下使用。

```
public class MyXWalkActivity extends Activity
     implements XWalkInitializer.XWalkInitListener {
    @Override
    protected void onCreate(Bundle savedInstanceState) {
    super.onCreate(savedInstanceState);
    // Invoke this method at first
    XWalkInitializer.initAsync(this, this);

    // Initialize the activity as usual
    setContentView(R.layout.activity_main);

    // DO NOT do more than creating an instance with embedding API
    mXWalkView = (XWalkView) findViewById(R.id.xwalkview);
    }

@Override
    public void onXWalkInitCompleted() {
    // Do anything with embedding API
    mXWalkView.load("index.html", null);
    }
}
     ```

## <a class="doc-anchor" id="Version-Check-Mechanism"></a>版本检查机制

除了版本名称（例如13.38.208.0），Crosswalk的每次发布都有一个独立的版本，这是为了展示嵌入式API版次的一串递增的版本号。这个版本号按下文中的格式记录在API_VERSION文件中：

API: 4
API_SDK: 2

API版本号是针对Crosswalk应用和运行时库的。MIN_API版本号只是针对运行时库，和运行时库支持的最低版本的Crosswalk应用。

当被加载时，Crosswalk应用将会检查安装在设备上的运行时库的版本。如果应用的版本号在当前版本和最低的向下兼容版本之间，则版本匹配，应用可以开启。否则，将会弹出一个对话框来提醒用户获取最新版本的运行时库。

# <a class="doc-anchor" id="Architecture-Independence"></a>架构无关性

现在，嵌入模式的Crosswalk应用会检测嵌入的Crosswalk运行时库的架构是否与设备匹配。如果架构匹配，则应用使用嵌入式运行时库。否则，它切换到共享模式。这意味着如果你仅发布了一个ARM版本的包，而你应用的用户却使用了一个x86架构的设备，那么他将被指引到Play Store上下载x86兼容版本的Crosswalk运行时环境。

# <a class="doc-anchor" id="Built-in Updater"></a>内置更新器

这个是个帮助用户获得Crosswalk运行时库的内置更新器。默认情况下，这个更新器将会跳转到Crosswalk运行时库在Google Play Store上的主页，后续操作就取决于用户了。如果一个开发者规定了一个下载链接，更新器将启动下载管理器来获取APK。

开发者可以用他们自己的方式发布和维护特定版本的Crosswalk运行时库。只要安装的运行时库的版本匹配Crosswalk应用，内置的更新器将不会被触发。
