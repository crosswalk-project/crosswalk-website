Crosswalk 15 has finally entered the Beta channel and is available from our [Downloads](/documentation/downloads) page. This is quite a bit later than the scheduled date, for what we hope is a good reason: we wanted to keep the Android 4.0 support that was removed from Chromium 44, but it took longer than the time we usually allocate for rebasing. Rather than skipping one Chromium update, we decided to delay the release. Going forward, we plan to maintain the 4.0 support for as long as the effort is manageable, while returning to the usual 6-week release cadence.

Users of the [Crosswalk Cordova WebView plugin](https://github.com/crosswalk-project/cordova-plugin-crosswalk-webview) should note that this version of Crosswalk doesn’t work with the 1.2.0 plugin currently [available from NPM](https://www.npmjs.com/package/cordova-android-crosswalk). In order to try it out you’ll need to install the plugin from the github repository until we make a new release to NPM, which should happen soon.

Below are the major features of Crosswalk 15. As usual please report bugs and issues to our [Jira](https://crosswalk-project/jira). Happy hacking!

## Chromium 44 and Android 4.0.x support

Chromium 44 includes new EC6 features and updated APIs. For more information see the [announcement](http://blog.chromium.org/2015/06/chrome-44-beta-improvements-to.html) in the Chromium Developers blog. 

Starting with this release, Chromium for Android no longer supports Android 4.0.x. However we have kept the support in our own fork of Chromium 44, which means that Crosswalk 15 still supports Android 4.0.x. 

## Preserving webview local storage data

Now that replacing the platform WebView with Crosswalk in Cordova is as easy as installing a plugin, many people have updated their existing Cordova applications to use Crosswalk. However doing so meant losing the data saved in the WebView local storage. With Crosswalk 15 this is no longer the case, and localStorage and IndexedDB data will be preserved when updating existing webview-based hybrid apps to Crosswalk.

## Support for theme-color on Android 5+

Chrome 39 introduced the [`theme-color`](https://developers.google.com/web/updates/2014/11/Support-for-theme-color-in-Chrome-39-for-Android?hl=en) meta tag to set the color of the toolbar when looking at a web application in the Android task switcher. This feature is now enabled in Crosswalk as well.

## Embedding API updates

* XWalkView now uses [`SurfaceView`](http://developer.android.com/reference/android/view/SurfaceView.html) as the default rendering backend. This solves several visual glitches, for example when opening/closing the virtual keyboard, and gives better performance, however it doesn’t support animating the view. If you need to animate the XWalkView, select [`TextureView`](http://developer.android.com/reference/android/view/TextureView.html) as the rendering backend. To change the view type to TextureView, use `XWalkPreferences.setValue(XWalkPreferences.ANIMATABLE_XWALK_VIEW, true)`
* New methods to programmatically zoom the view: [`XWalkView.zoomOut`](https://crosswalk-project.org/jira/browse/XWALK-4171),[`XWalkView.zoomIn`](https://crosswalk-project.org/jira/browse/XWALK-4170), [`XWalkView.zoomBy`](https://crosswalk-project.org/jira/browse/XWALK-4169)
* Override the default DownloadListener with [`XWalkView.setDownloadListener`](https://crosswalk-project.org/jira/browse/XWALK-3958)
* New helper class [`XWalkInitializer`](https://crosswalk-project.org/jira/browse/XWALK-3971) to initialize XWalkView asynchronously 

## Extension framework

JavaScript stub auto-generation for Java extensions on Android [[XWALK-3969](https://crosswalk-project.org/jira/browse/XWALK-3969)]

## Size optimizations

The following features were ported from the Crosswalk Lite branch to support better compression of the APK:

* Enable ProGuard for Crosswalk to compress APK size [[XWALK-3854](https://crosswalk-project.org/jira/browse/XWALK-3854)]
* LZMA support [[XWALK-3810](https://crosswalk-project.org/jira/browse/XWALK-3810)]
* Ability to customize the dialog shown when uncompressing Crosswalk [[XWALK-3475](https://crosswalk-project.org/jira/browse/XWALK-3475)]
