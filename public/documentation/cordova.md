# Cordova

[Cordova](http://cordova.apache.org/) is a set of device APIs for accessing device capabilities (e.g. file storage, accelerometer) from JavaScript. Browsers typically prevent access to such APIs, as they could introduce serious security risks on the open web. But secure access to those capabilities can be granted to Cordova applications, which are installed directly on a device (The Crosswalk Project uses the same model).

Cordova applications are built as platform-specific packages, with a runtime "wrapper" to load the embedded web application. On Android, the wrapper is based on the [WebView](http://developer.android.com/guide/webapps/webview.html), which loads the web application files (HTML, CSS and JavaScript). Unfortunately, the stock WebView on many Android devices (pre 5.0) has [some limitations](https://developers.google.com/chrome/mobile/docs/webview/overview#does_the_new_webview_have_feature_parity_with_chrome_for_android), one of the most notable being lack of support for WebGL. This means it's not possible to run WebGL applications with Cordova on those Android devices.

The Crosswalk webview is aligned with Chrome on Android (as Crosswalk and Chrome share the same ancestor, [Chromium](http://www.chromium.org/)). This means that Crosswalk *does* support WebGL on Android, as well as the wealth of other APIs available in Chrome on Android. In addition, Crosswalk goes beyond Chrome, providing support for cutting edge features such as [SIMD](/documentation/samples/simd.html) (x86 only) and [the Presentation API](https://github.com/crosswalk-project/crosswalk-website/wiki/Presentation-api-manual). But Crosswalk on its own doesn't support the Cordova APIs.

## Cordova with the Crosswalk webview
The best solution for those using Cordova APIs is to use both Cordova and Crosswalk together. Starting with Cordova 4.0, the pluggable webview support makes bundling these two technologies simple.  For those just getting started, follow the "Cordova 4.0" link below.  
<ul>
 <li>[Cordova 4.0+](/documentation/cordova/cordova_4.html)</li>
 <li>[Cordova 3.x](/documentation/cordova/cordova_3.html)</li>
</ul>

