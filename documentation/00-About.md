# About

Crosswalk is an HTML application runtime, built on open source foundations, which extends the web platform with new capabilities.

The web platform already brings many advantages, ranging from easy integration with cloud services to flexible user interface elements. It also benefits developers, due to its openness, easy deployment model, and adaptability to different operating systems and form factors. Recent years have seen dramatic improvements in the platform: richer versions of HTML, CSS and JavaScript, driven by an increased focus on mobile performance, modular application architecture, and device integration.

But, for many developers, basic functionality is still missing, making it hard to adopt the web as it is today.

With Crosswalk, this situation changes.

By using Crosswalk, an application developer can:

*   Access the latest recommended and emerging web standards.
*   Control the upgrade cycle of an application by distributing it with its own runtime.
*   Add custom extensions to an application, to leverage platform features not exposed by Crosswalk or the standardized web platform.

At the heart of Crosswalk is the Blink rendering and layout engine. This provides the same HTML5 features and capabilities you would expect to find in any modern web browser.

Building on Blink, Crosswalk uses Chromium features to provide a multi-process architecture, designed for security and performance, as well as state-of-the-art performance and graphics.

Crosswalk is open source, released under a [BSD licence](https://github.com/crosswalk-project/crosswalk/blob/master/LICENSE). The project was founded by Intel's Open Source Technology Center.

## Frequently-asked questions

If you have any questions that are not answered below, the crosswalk-help mailing list is a good place to ask them. Alternatively, contact us directly via the #crosswalk IRC channel on Freenode. See the [Community page](#documentation/community) for more details.

### What is Crosswalk for?

If you are a developer working with web technologies, Crosswalk enables you to deploy a web app with its own dedicated runtime. This means three things:

1.  You can distribute your application via app stores.
2.  Your application won't break in whatever ancient webviews or browsers your audience is using, as you control the runtime and its upgrade cycle.
3.  You can build applications without worrying so much about runtime differences and quirks: you only have one runtime to deal with.

### Can one Crosswalk installation be shared between multiple applications?

Bundling the runtime with the application (aka "embedded mode") is the simplest approach for distribution purposes. But Crosswalk applications *can* share a single Crosswalk runtime library (in "shared mode"); and a package which enables shared mode is part of the Crosswalk for Android distribution. However, you would have to distribute this shared runtime package yourself.

### How big is the Crosswalk runtime, and how will it affect my application's size?

To give a rough idea, the HTML/JS/CSS for [one of the project's test applications](https://github.com/crosswalk-project/crosswalk-apk-generator/tree/master/test/functional/demo-app) takes up 4Mb of disk space.

Once this application is packaged with its own Crosswalk (x86 Android) runtime, the apk file size is 24Mb.

Unpacked, the files take up 63Mb of disk space, 59Mb of which is occupied by the Crosswalk runtime and its supporting files.

### Can I use Crosswalk to "appify" my website?

Yes. You can "package" a website URL with a Crosswalk runtime so it behaves like an "app" (fullscreen, no browser chrome, home screen icon etc.).

### Can I customise Crosswalk?

Yes. Crosswalk itself can be modified, as the code is open source. We actively encourage [contributions](https://crosswalk-project.org/#contribute/overview).

Alternatively, you can add extra capabilities to Crosswalk through its [extension mechanism](#wiki/Crosswalk-Extensions) without having to modify the core code. This enables an application to access platform features via native code (Java on Android, C/C++ on Tizen) and go beyond the boundaries of the web runtime.

### What does Crosswalk's architecture look like?

The diagram below gives a high-level overview of how Crosswalk is put together:

<div id='illustration'></div>

### Who is using Crosswalk?

Crosswalk is still a young project. However, the last couple of months have seen some impressive take-up, with dozens of Crosswalk-based applications (mainly games) making it into app stores.

### How does Crosswalk relate to the Intel XDK?

The [Intel XDK](http://xdk-software.intel.com/) is a development environment (IDE) for HTML5 applications. When a developer builds an application using the XDK, they have a choice of exporting their application to a Crosswalk Android package (apk). Packages built this way by the XDK actually consist of the HTML5 application, bundled with its own Crosswalk runtime.

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

No, Crosswalk is an open source project, hosted on [github](https://github.com/crosswalk-project/crosswalk), and licensed under the Apache version 2 licence. It is free to use for any purpose, commercial or otherwise.

### If I'm not paying for Crosswalk, who is?

Crosswalk development is largely sponsored by Intel, but builds on top of [Chromium](http://www.chromium.org/) development.

### Can I get commercial support for Crosswalk?

Not at the moment, but we would love to hear from you if you need it.
