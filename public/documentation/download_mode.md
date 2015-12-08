# Using the Crosswalk Project Runtime in Download Mode

Download Mode provides another way to shrink the application size just like what shared mode does. Your application is still bundled with xwalk_shared_library which contains only the API layer of crosswalk, while the crosswalk core libraries/resources will be downloaded in the background at the first launch of your application from your specified server. Compared to shared mode, the crosswalk runtime is exclusive to your application therefor you are on control of the crosswalk runtime full lifecycle, and the crosswalk runtime download in the background could be done silently without any end user interaction.

# How to enable Download Mode with Embedding API

## Initialize crosswalk with XWalkInitializer/XWalkUpdater

```
public class XWalkDownloadActivity extends Activity
        implements XWalkInitializer.XWalkInitListener, XWalkUpdater.XWalkBackgroundUpdateListener {

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
        // Initialization failed then to trigger the crosswalk runtime download
        if (mXWalkUpdater == null) mXWalkUpdater = new XWalkUpdater(this, this);
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

### Specify the URL from where to download crosswalk runtime APK
```
<meta-data android:name="xwalk_apk_url" android:value="http://10.0.2.2/XWalkRuntimeLibLzma.apk" />
```
Please note that when the HTTP request is sent to server, the URL will be appended with "?arch=CPU_API" to indicate that on which CPU architecture it's currently running.
The CPU_API is the same as the value returned from "adb shell getprop ro.product.cpu_abi", e.g. x86 for IA 32bit, x86_64 for IA 64bit, armeabi-v7a for ARM 32bit and arm64-v8a for ARM 64bit.

### Enable download mode
```
<meta-data android:name="xwalk_enable_download_mode" android:value="enable" />
```

## Deployment
After the crosswalk runtime APK was downloaded from the server, we will do a signature check to ensure the integrity of the downloaded crosswalk runtime APK. And the signature check requires that the crosswalk runtime APK must be signed with the same key used in your application signing. You could also disable the signature check, for example, in development phase by inserting a meta-data to AndroidManifest.xml.
```
<meta-data android:name="xwalk_verify" android:value="disable" />
```
Besides the normal crosswalk runtime APK (named XWalkRuntimeLib.apk), we also provde a LZMA compressed runtime APK (named XWalkRuntimeLibLzma.apk) which has smaller size, for example, the size of XWalkRuntimeLib.apk for x86 is ~28M while the size of XWalkRuntimeLibLzma.apk is ~18M. The compressed one could save your users' network bandwidth in download but it will take a little more time in decompressing at the first launch of your application. You could choose which one to deploy based on your need.

## Crosswalk Runtime auto update
Basically, it requires that the build version of the xwalk_shared_library which you used to bundle with your application must be the same as that of crosswalk runtime APK, otherwise the initialization will fail and then it will trigger an update to try to download a new crosswalk runtime APK from the server. This will guarantee that the crosswalk runtime is always up to date in user's device after you upgrade your application with a new crosswalk library. 

