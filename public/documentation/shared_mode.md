# Shared Mode vs. Embedded Mode

By default, the Crosswalk runtime is embedded with your application and included in the APK. In contrast, "shared mode" builds the application without the runtime, but the runtime must be downloaded later, at runtime.  "Shared mode" allows multiple Crosswalk applications to share one Crosswalk runtime. If the runtime is not already installed in the device, it will be downloaded either from the Google Play Store, or from a download location specified by the developer. Because the Crosswalk library is not included in the application's APK package, it is significantly smaller (~20MB).

## <a class="doc-anchor" id="Pros"></a>Pros

* Produces a significant smaller APK file size for Crosswalk applications (about 20MB smaller).
* APKs built in shared mode are architecture independent. Only one package needs to be submitted to the Play Store. The architecture-dependent runtime will be downloaded later.
* Crosswalk is updated independently of the application. Taking advantage of the latest improvements doesn't require an update of the application.

## <a class="doc-anchor" id="Cons"></a>Cons

* If the Crosswalk runtime library is not already installed on the user's device, it will download when the app is first run. The normal startup process of the application will be interrupted.
* An updated Crosswalk runtime may introduce incompatibility with already-installed, shared-mode apps. Developers need to test their applications against the latest Crosswalk release. Note that we do strive to maintain backwards compatibility.

# <a class="doc-anchor" id="package"></a>Packaging in shared mode

## <a class="doc-anchor" id="Using-the-make_apk-packaging-tool"></a>make_apk packaging tool

To package the application in shared mode, use the option:

`./make_apk.py --mode=shared ...`

Note: shared mode is not yet supported by crosswalk-app-tools

## <a class="doc-anchor" id="Using-Cordova"></a>Cordova build

To create a cordova project in shared mode, use the option:

`./bin/create --xwalk-shared-library ...`

Note: shared mode is not yet supported by cordova-plugin-crosswalk-webview

## <a class="doc-anchor" id="Using-an-IDE"></a>Using an IDE

For Eclipse ADT users, add library reference to the path of the xwalk_shared_library project.

For Android Studio users, import the xwalk_shared_library.aar as dependency.

# <a class="doc-anchor" id="How-to-Configure-the-download-URL"></a>How to Configure the download URL

For testing purposes, it is possible to configure the application to download the Crosswalk runtime from a specific URL rather than the Google Play Store. Note that in any case there can only be one copy of the runtime installed in the device, no matter where it was downloaded from. Also, the Crosswalk package can only be installed if the user has enabled installation from untrusted sources.

## <a class="doc-anchor" id="From-Packaging-Tool"></a>From Packaging Tool

Use the option below for make_apk.py:

`./make_apk.py --mode=shared --xwalk-apk-url=http://host/XWalkRuntimeLib.apk ...`

## <a class="doc-anchor" id="From-The-Crosswalk-Manifest"></a>From The Crosswalk Manifest

Define an element with the key “xwalk_apk_url”:

```
{
    "name": "XWalkApp",
    "start_url": "index.html",
    "xwalk_apk_url": "http://10.0.2.2/XWalkRuntimeLib.apk",
    ...
}
```

## <a class="doc-anchor" id="From-The-Android-Manifest"></a>From The Android Manifest

Define a meta-data element with the name "xwalk_apk_url" inside the application tag:
```
<application android:name="org.xwalk.core.XWalkApplication">
    <meta-data android:name="xwalk_apk_url"
               android:value="http://host/XWalkRuntimeLib.apk" />
    ...
</application>
```

# <a class="doc-anchor" id="Using-shared-mode-with-the-embedding-API"></a>Using shared mode with the embedding API

Developers that are embedding Crosswalk in their application using the Embedding API can take advantage of the shared mode following the instructions below.

## <a class="doc-anchor" id="Permissions"></a>Permissions

Shared mode requires the following permissions to be enabled:

```
ACCESS_NETWORK_STATE
ACCESS_WIFI_STATE
INTERNET
WRITE_EXTERNAL_STORAGE
```

## <a class="doc-anchor" id="XWalkApplication"></a>XWalkApplication
 
XWalkApplication is necessary to get the resources from Crosswalk runtime library. You may use XWalkApplication directly in the Android manifest, or make your own application class extend XWalkApplication class.

```
<application android:name="org.xwalk.core.XWalkApplication">
    ...
</application>
```

## <a class="doc-anchor" id="XWalkActivity"></a>XWalkActivity

XWalkActivity helps to execute all procedures for initializing the Crosswalk environment, and displays dialogs for interacting with the end-user if necessary. The activities that hold the XWalkView object might want to extend XWalkActivity to obtain this capability. For those activities, it’s important to override the abstract method onXWalkReady() that notifies the Crosswalk environment is ready.

In shared mode, the Crosswalk runtime library is not loaded yet at the moment the activity is created. So the developer can’t use embedding API in onCreate() as usual. All routines using embedding API should be inside onXWalkReady() or after onXWalkReady() is invoked.

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

XWalkInitializer is a helper to initialize the Crosswalk environment as XWalkActivity. But unlike XWalkActivity, XWalkInitializer neither provide UI interactions to help the end-user to download the appropriate Crosswalk library, nor prompt messages to notify the specific error during initializing. Due to this limitation, XWalkInitializer only works for embedded mode, or for shared mode if the appropriate Crosswalk library has already been installed on the device.

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

## <a class="doc-anchor" id="Version-Check-Mechanism"></a>Version Check Mechanism

Along with the version name (e.g. 13.38.208.0), each release of Crosswalk has an independent version which is an incremental version code to show the revision of embedding API. This version is recorded in the API_VERSION file as below:

API: 4
API_SDK: 2

The API version is for both of the Crosswalk application and runtime library. The MIN_API version is only for the runtime library, and indicates the minimal version of Crosswalk applications that the runtime library supports.

When loaded, the Crosswalk application will check the version of the runtime library installed on the device. If the version of the application is between the current version and minimal backward-compatible version of the runtime library, the versions match and the application can start. Otherwise, a dialog will pop up to to prompt the user to get the latest version of the runtime library.

# <a class="doc-anchor" id="Architecture-Independence"></a>Architecture Independence

The Crosswalk application in embedded mode now checks whether the architecture of the embedded Crosswalk runtime library matches the device. If the architecture matches, the application uses the embedded runtime library. Otherwise, it switches to shared mode. This means that if you only publish an ARM version of your package, a user of your application using an x86 device will be directed to the Play Store to download an x86-compatible version of the Crosswalk Project runtime.

# <a class="doc-anchor" id="Built-in Updater"></a>Built-in Updater

There is a built-in updater to help the user to get the APK of Crosswalk runtime library. By default, the updater will jump to the page of Crosswalk runtime library on Google Play Store, subsequent process will be up to the user. If the developer specified the download URL, the updater will launch the download manager to fetch the APK.

The developer can also distribute and maintain the specific version of Crosswalk runtime library in their own way. The built-in updater won’t be activated as long as the version of the installed runtime library matches the Crosswalk application.
