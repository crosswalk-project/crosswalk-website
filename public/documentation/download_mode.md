# Download Mode

Download Mode provides another way to shrink the size of your APK and is similar to shared mode. Your application is still bundled with the `xwalk_shared_library` which contains only the API layer of Crosswalk.  When the application is first run on the client device, the Crosswalk core libraries and resources are downloaded in the background from a server that you specify. Compared to shared mode, the Crosswalk runtime is exclusive to your application and therefore you control the runtime lifecycle.  The Crosswalk runtime can be downloaded silently in the background without user interaction.

## Enabling Download Mode using the Embedding API

### Initialize crosswalk with XWalkInitializer/XWalkUpdater

The following code shows one example of how to use the `XWalkUpdater` class to download of the runtime during application initialization.
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
```

## Configure AndroidManifest.xml

### Specify the download URL of the Crosswalk runtime APK
```
<meta-data android:name="xwalk_apk_url" android:value="http:// [URL to your server] /XWalkRuntimeLib.apk" />
```
Please note that when the request is sent to server, the value of the device CPU will be appended ("?arch=CPU_API"). The CPU_API is the same as the value returned from `adb shell getprop ro.product.cpu_abi`. It returns `x86` for IA 32bit, `x86_64` for IA 64bit, `armeabi-v7a` for ARM 32bit and `arm64-v8a` for ARM 64bit.

### Enable download mode
```
<meta-data android:name="xwalk_enable_download_mode" android:value="enable" />
```

## Deployment
After the Crosswalk runtime APK is downloaded from the server, a signature check is done to ensure its integrity. The signature check requires that the Crosswalk runtime APK be signed with the same key used to sign your application. You can disable this signature check during development by setting `xwalk_verify` to `disable` in the AndroidManifest.xml:
```
<meta-data android:name="xwalk_verify" android:value="disable" />
```

The normal crosswalk runtime APK (`XWalkRuntimeLib.apk`) is ~28MB.  We also provde an LZMA-compressed runtime APK (`XWalkRuntimeLibLzma.apk`) which is ~18MB. While the compressed APK can save network bandwidth, when the application is first launched the APK must be decompressed (a dialog is displayed during this process).  This only happens during the first launch.


