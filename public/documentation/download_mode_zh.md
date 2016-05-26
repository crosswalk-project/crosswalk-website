# 下载模式

下载模式为缩小APK的大小提供了另一种与共享模式相似的解决方案。你的应用仍然绑定仅包含Crosswalk API层的`xwalk_shared_library`。当应用第一次在客户端设备上运行时，后台将会从你规定好的服务器上下载Crosswalk核心库和资源。与共享模式相比，你的应用可以独享Crosswalk运行时，因此你便可以控制运行时的生命周期。Crosswalk运行时可以不需要用户交互，在后台完成下载。

## 使用嵌入式API完成下载模式

### 使用XWalkInitializer/XWalkUpdater初始化Crosswalk

下文中的代码展示了在应用初始化过程中，如何使用`XWalkUpdater`类来下载运行时。
```
public class XWalkDownloadActivity extends Activity
        implements XWalkInitializer.XWalkInitListener, 
		XWalkUpdater.XWalkBackgroundUpdateListener {

    XWalkInitializer mXWalkInitializer;
    XWalkUpdater mXWalkUpdater;
    XWalkView mXWalkView;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        mXWalkInitializer = new XWalkInitializer(this, this);
        mXWalkInitializer.initAsync();

        setContentView(R.layout.activity_xwalkview);

        mXWalkView = (XWalkView) findViewById(R.id.xwalkview);
        ....
    }

    @Override
    protected void onResume() {
        super.onResume();
        mXWalkInitializer.initAsync();
    }

    @Override
    public void onXWalkInitStarted() {
    }

    @Override
    public void onXWalkInitCancelled() {
        finish();
    }

    @Override
    public void onXWalkInitFailed() {
        // Initialization failed. Trigger the Crosswalk runtime download
        if (mXWalkUpdater == null) {
			mXWalkUpdater = new XWalkUpdater(this, this);
		}
        mXWalkUpdater.updateXWalkRuntime();
    }

    @Override
    public void onXWalkInitCompleted() {
        // Initialization successfully, ready to invoke any XWalk embedded API
        mXWalkView.load("file:///android_asset/create_window_1.html", null);
    }

    @Override
    public void onXWalkUpdateStarted() {
    }

    @Override
    public void onXWalkUpdateProgress(int percentage) {
        Log.d(TAG, "XWalkUpdate progress: " + percentage);
    }

    @Override
    public void onXWalkUpdateCancelled() {
        finish();
    }

    @Override
    public void onXWalkUpdateFailed() {
        finish();
    }

    @Override
    public void onXWalkUpdateCompleted() {
        // Crosswalk Runtime update finished, re-init again.
        mXWalkInitializer.initAsync();
    }
	...
	
```

## 配置AndroidManifest.xml

### 规定Crosswalk运行时APK的下载链接
```
<meta-data android:name="xwalk_apk_url" 
           android:value="https:// [URL of your server] /XWalkRuntimeLib.apk" />
```
注意当请求被发送到服务器端时，描述设备CPU架构的值将会被附加("?arch=&lt;cpu arch&gt; ")。"cpu arch"与`adb shell getprop ro.product.cpu_abi`的返回值相同，参见下表：
<table style="max-width: 380px; margin:0 auto">
  <tr><th>设备CPU架构</th><th>"cpu arch"的值</th></tr>
  <tr><td>IA 32-bit</td><td>`x86`</td></tr>
  <tr><td>IA 64-bit</td><td>`x86_64`</td></tr>
  <tr><td>ARM 32-bit</td><td>`armeabi-v7a`</td></tr>
  <tr><td>ARM 64-bit</td><td>`arm64-v8a`</td></tr>
</table>
<br>
### 使用下载模式
```
<meta-data android:name="xwalk_enable_download_mode" android:value="enable" />
```

## 部署
在Crosswalk运行时APK从服务器端下载完成后，需要进行一个签名检测来保证它的完整性。签名检测要求签署Crosswalk运行时APK的关键字与用来签署你的应用的关键字相同。你可以通过在AndroidManifest.xml文件中将`xwalk_verify`设置为`disable`来取消部署阶段的签名检测:
```
<meta-data android:name="xwalk_verify" android:value="disable" />
```

通常，一个Crosswalk运行时APK(`XWalkRuntimeLib.apk`)大小约为为~28MB。我们也提供一个大小约为~18MB的LZMA压缩版的运行时APK。虽然压缩后的APK可以节省带宽，但是当应用第一次启动时，APK必须被解压。仅在第一次启动时执行。

## Crosswalk运行时更新
一个使用下载模式的Crosswalk应用必须使用服务器端相同版本的Crosswalk运行时APK进行编译。特别是你的应用使用的`xwalk_shared_library`和服务器端`XWalkRuntimeLib.apk` (或者`XWalkRuntimeLibLzma.apk`)必须来自同一版本。因此，无论你何时在应用商店更新你的应用，你也必须更新你服务器的运行时APK。如果你客户端设备上的应用更新到了一个新版本，你的应用将尝试重新下载与新版本匹配的运行时，即使它已经下载过之前的版本。
