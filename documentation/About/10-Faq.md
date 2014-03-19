# Frequently-asked questions

If you have any questions that are not answered below, the crosswalk-help mailing list is a good place to ask them. Alternatively, contact us directly via the #crosswalk IRC channel on Freenode. See the [Community page](#documentation/community) for more details.

### What is Crosswalk for?

If you are a developer working with web technologies, Crosswalk enables you to deploy a web application with its own dedicated runtime. This means three things:

1.  You can distribute your web application via app stores.
2.  Your application won't break in whatever ancient webviews or browsers your audience is using, as you control the runtime and its upgrade cycle.
3.  You can build applications without worrying so much about runtime differences and quirks: you only have one runtime to deal with.

### How big is the Crosswalk runtime, and how will it affect my application's size?

To give a rough idea, the HTML/JS/CSS for [one of the project's test applications](https://github.com/crosswalk-project/crosswalk-apk-generator/tree/master/test/functional/demo-app) takes up 4Mb of disk space.

Once this application is packaged with its own Crosswalk (x86 Android) runtime, the apk file size is 24Mb.

Unpacked, the files take up 63Mb of disk space, 59Mb of which is occupied by the Crosswalk runtime and its supporting files.

### Can one Crosswalk installation be shared between multiple applications?

Bundling the runtime with the application (aka "embedded mode") is the simplest approach for distribution purposes. But Crosswalk applications *can* share a single Crosswalk runtime library (in "shared mode"); and a package which enables shared mode is part of the Crosswalk for Android distribution. However, you would have to distribute this shared runtime package yourself.

### Can I use Crosswalk to "appify" my website?

Yes. You can wrap a website URL with a Crosswalk runtime so it behaves like an app (fullscreen, no browser chrome, home screen icon etc.).

### Can I customise Crosswalk?

Yes. Crosswalk itself can be modified, as the code is open source. We actively encourage [contributions](https://crosswalk-project.org/#contribute/overview).

Alternatively, you can add extra capabilities to Crosswalk through its [extension mechanism](#wiki/Crosswalk-Extensions) without having to modify the core code. This enables an application to access platform features via native code (Java on Android, C/C++ on Tizen) and go beyond the boundaries of the web runtime.

### Who is using Crosswalk?

Crosswalk is still a young project. However, the last couple of months have seen some impressive take-up, with dozens of Crosswalk-based applications (mostly games) making it into app stores.

### How does Crosswalk relate to the Intel XDK?

The [Intel XDK](http://xdk-software.intel.com/) is a development environment (IDE) for HTML5 applications. When a developer builds an application using the XDK, they have a choice of exporting their application to a Crosswalk Android package (apk). Packages built this way by the XDK consist of the HTML5 application, bundled with its own Crosswalk runtime.

### Is Crosswalk a replacement for Phonegap/Cordova?

No, they are complementary. If you intend to build for multiple platforms (beyond Android and Tizen), need extensive documentation and a very mature community, Cordova may be a better choice. If you are interested in hardware-accelerated WebGL support and bleeding edge HTML5 features, Crosswalk may be a better choice.

Having said this, you can get the best of both worlds by [using Cordova APIs from Crosswalk](https://crosswalk-project.org/#wiki/Crosswalk-cordova-android) if you wish.

### Does Crosswalk for Android use the Android webview?

No. Crosswalk is effectively a modified version of Chromium, the open source basis of the Google Chrome browser.

### Why do I need Crosswalk now that Android (KitKat and later) has a Chrome-based webview?

Crosswalk provides access to the [full range of modern web APIs](https://crosswalk-project.org/#documentation/apis) supported by Chrome. By contrast, the Android Chrome-based web view [lacks some features](https://developers.google.com/chrome/mobile/docs/webview/overview#does_the_new_webview_have_feature_parity_with_chrome_for_android) which are available in Chrome on Android.

On top of this, Crosswalk adds extra features which are *not* available in either Chrome or the Android webview, such as experimental support for [SIMD](https://01.org/blogs/tlcounts/2014/bringing-simd-javascript) and support for the [Presentation API](https://crosswalk-project.org/#wiki/presentation-api-manual).

### How often is Crosswalk released?

Crosswalk is updated to the latest Chromium once every six weeks. In practice, this means that the longest gap between a feature appearing in Chromium and the same feature appearing in Crosswalk is six weeks.

For more details, see [this explanation of how Crosswalk relates to Chromium](https://crosswalk-project.org/#wiki/Downstream-Chromium).

### Which platforms does Crosswalk support?

Crosswalk officially supports [Android](http://www.android.com/) (version 4.0 and above), [Tizen Mobile](https://www.tizen.org/) (Tizen version 2) and [Tizen IVI](https://wiki.tizen.org/wiki/IVI) (Tizen version 3). Pre-built packages are available from https://download.01.org/ for each of these platforms. See the [downloads page](https://crosswalk-project.org/#documentation/downloads) for details.

Crosswalk can also be built to run on Windows, Mac OS X and Linux, though no official packages are produced for those platforms.

Crosswalk does not support iOS.

### Can I get involved?

Yes. We welcome contributions from anyone who would like to make the project better, whether by writing code, filing bugs, or adding documentation. Full details of how to get involved are [on the Crosswalk website](https://crosswalk-project.org/#contribute/overview).

### Do I have to pay for Crosswalk?

No, Crosswalk is an open source project, hosted on [github](https://github.com/crosswalk-project/crosswalk), and licensed under the [BSD licence](https://github.com/crosswalk-project/crosswalk/blob/master/LICENSE). It is free to use for any purpose, commercial or otherwise.

### If I'm not paying for Crosswalk, who is?

Crosswalk development is largely sponsored by Intel, but builds on top of [Chromium](http://www.chromium.org/) development.

### Can I get commercial support for Crosswalk?

Not at the moment, but we would love to hear from you if you need it.
