Crosswalk 16 for Android has entered the stable channel and adds the following features and changes.

* Update to Chromium 45, which includes new JavaScript language features, an improved audio experience on Android, and a large number of minor API improvements and deprecations. Read the [announcement](http://blog.chromium.org/2015/07/chrome-45-beta-new-es2015-features.html) for more details.
* Crosswalk can now play back Widevine DRM-protected content ([XWALK-5030](https://crosswalk-project.org/jira/browse/XWALK-5030))
* A new xwalk_view.background_color field can be added to the manifest.json, to specify the background color of the XWalkView
* When onReceivedLoadError occurs, a Toast notification is displayed to the user instead of a dialog (as the user cannot do anything to respond to the error)
* Presentation API on Android follows an updated [W3C Spec](http://www.w3.org/TR/2015/WD-presentation-api-20150701/).
* External extensions have an additional binary messaging interface for better performance when large amounts of data need to be passed to and from the extension.
* The signature of the downloaded Crosswalk APK is verified when starting the application in shared mode.
* Android support libraries (e.g. support-v4, support-v7 etc) are no longer bundled by Crosswalk and should be included explicitly if required.
