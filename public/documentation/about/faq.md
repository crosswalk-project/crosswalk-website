# Frequently-asked questions

If you have any questions that are not answered below, the crosswalk-help mailing list is a good place to ask them. Alternatively, contact us directly via the #crosswalk IRC channel on Freenode. See the [Community page](/documentation/community.html) for more details.

### <a class="doc-anchor" id="Contents"></a>Contents

*   [Background to the project](#Background-to-the-project)
*   [Ways to use the Crosswalk Project](#Ways-to-use-the-Crosswalk-Project)
*   [Distributing Crosswalk Project applications](#Distributing-Crosswalk-Project-applications)
*   [Canvas and WebGL support](#Canvas-and-WebGL-support)
*   [Embedding API](#Embedding-API)
*   [iOS Support](#iOS-Support)
*   [The Crosswalk Project community](#The-Crosswalk-Project-community)
*   [Commercial aspects](#Commercial-aspects)
*   [Relationships with other projects](#Relationships-with-other-projects)
*   [Common Issues](#Common-issues)

## <a class="doc-anchor" id="Background-to-the-project"></a>Background to the project

### <a class="doc-anchor" id="What-is-the-Crosswalk-Project-for"></a>What is the Crosswalk Project for?

If you are a developer working with web technologies, the Crosswalk Project enables you to deploy a web application with its own dedicated runtime. This means three things:

1.  You can distribute your web application via app stores.
2.  Your application won't break in whatever ancient webviews or browsers your audience is using, as you control the runtime and its upgrade cycle.
3.  You can build applications without worrying so much about runtime differences and quirks: you only have one runtime to deal with.

### <a class="doc-anchor" id="Is-this-a-runtime-like-Java-or-Visual-Basic"></a>Is this a runtime like Java or Visual Basic?

No, because the Crosswalk Project is based on W3C standards: HTML5, CSS and JavaScript. Unlike the languages supported by earlier runtimes, W3C standards are implemented in multiple contexts, by multiple companies, in both open source and commercial forms. A broad range of open source as well as commercial tools and projects support the developer. When you use the Crosswalk Project application runtime, you are participating in a growing ecosystem.

### <a class="doc-anchor" id="If-my-apps-need-W3C-standards-why-not-target-a-browser"></a>If my apps need W3C standards, why not target a browser?

Browsers do a great job of supporting W3C standards, but they may not be able to support more powerful APIs, or APIs that are considered experimental. This is because these APIs access platform features which, if known to a web site and combined with other data available to the browser, would allow violations of the user's privacy. Because Crosswalk Project applications have a different security model, where a user is able to choose which permissions an application is given, more powerful APIs can be supported. This in turn makes it possible for the Crosswalk Project to run applications which are not possible on the open web.

### <a class="doc-anchor" id="Isnt-the-Crosswalk-Project-just-going-to-mean-more-fragmentation-of-the-web"></a>Isn't the Crosswalk Project just going to mean more fragmentation of the web?

No, because:

* The Crosswalk Project isn't aimed at the web at all: it's aimed at applications that happen to be written in HTML5, CSS and JS.
* Applications using a Crosswalk Project runtime know about the environment they are built for. Minor differences between runtime implementations (e.g. a sensor available on one platform but not on another) can be easily managed by developers.
* We don't intend to fork Blink, the underlying rendering engine for Chromium.
* We rebase regularly to new versions of Blink.
* If a change makes sense for generic Chromium, we will submit it upstream.

## <a class="doc-anchor" id="Ways-to-use-the-Crosswalk-Project"></a>Ways to use the Crosswalk Project

### <a class="doc-anchor" id="Can-I-use-the-Crosswalk-Project-to-appify-my-website"></a>Can I use the Crosswalk Project to "appify" my website?

Yes. You can wrap a website URL with a Crosswalk Project runtime so it behaves like an app (fullscreen, no browser chrome, home screen icon etc.).

### <a class="doc-anchor" id="Can-I-customise-the-Crosswalk-Project"></a>Can I customise the Crosswalk Project?

Yes. The Crosswalk Project itself can be modified, as the code is open source. We actively encourage [contributions](https://crosswalk-project.org/contribute/index.html).

Alternatively, you can add extra capabilities to Crosswalk through its [extension mechanism](https://github.com/crosswalk-project/crosswalk-website/wiki/Crosswalk-Extensions) without having to modify the core code. This enables an application to access platform features via native code (Java on Android, C/C++ on Tizen) and go beyond the boundaries of the web runtime.

## <a class="doc-anchor" id="Distributing-Crosswalk-Project-applications"></a>Distributing Crosswalk Project applications

### <a class="doc-anchor" id="How-big-is-the-Crosswalk-Project-runtime-and-how-will-it-affect-my-applications-size"></a>How big is the Crosswalk Project runtime, and how will it affect my application's size?

To give a rough idea, the HTML/JS/CSS for [one of the project's sample applications](https://github.com/crosswalk-project/crosswalk-samples/tree/master/hello_world) takes up 24Kb of disk space.

Once this application is packaged with its own Crosswalk 10 (x86 Android) runtime, the apk file size is ~20Mb. Installed, the application takes ~58Mb of disk space.

### <a class="doc-anchor" id="Can-one-Crosswalk-installation-be-shared-between-multiple-applications"></a>Can one Crosswalk installation be shared between multiple applications?

Bundling the runtime with the application (aka "embedded mode") is the simplest approach for distribution purposes. But Crosswalk applications *can* share a single Crosswalk runtime library (in "shared mode"); and a package which enables shared mode is part of the Crosswalk for Android distribution. However, you would have to distribute this shared runtime package yourself.

### <a class="doc-anchor" id="How-can-I-distribute-a-Crosswalk-Android-application-across-multiple-architectures"></a>How can I distribute a Crosswalk Android application across multiple architectures?

The Crosswalk binaries are architecture-specific. This means that you will need an x86-compatible Crosswalk on Android devices with x86 chips; and an ARM-compatible Crosswalk on Android devices with ARM chips.

There are two approaches to building an application which supports both x86 and ARM platforms:

*   Build two separate packages for your application, one for x86 and one for ARM; then upload both to the app stores where you are hosting your application. Prominent stores like Google Play have support for [uploading multiple packages for different platforms](http://developer.android.com/google/play/publishing/multiple-apks.html).

    The [Crosswalk apk generation script](/documentation/android/run_on_android.html) (`make_apk.py`) generates packages for both architectures to facilitate this way of working.

*   Build one package for your application, but include both the x86 and ARM versions of Crosswalk in it. The down-side of this approach is that it makes the package file very large (c. 40Mb before you add your application code).

    Creating a package this way requires you to copy the Crosswalk shared object files for both architectures into the `lib/` directory of the apk package file. For example, an apk with support for x86 and ARM would contain the following files:

        lib/armeabi-v7a/libxwalkcore.so
        lib/x86/libxwalkcore.so

    How you achieve this depends on your build process. If you need a reference, see [the Cordova migration instructions](/documentation/cordova/migrate_an_application.html#Multi-architecture-packages), which explain how to do this in the context of Crosswalk Cordova.

### <a class="doc-anchor" id="Which-platforms-does-Crosswalk-support"></a>Which platforms does Crosswalk support?

Crosswalk officially supports [Android (version 4.0 and above)](/documentation/android.html), [iOS](/documentation/ios.html), [Linux desktop (deb)](/documentation/linux.html), and [Tizen 3.0 (Common and IVI profiles)](/documentation/tizen.html). Pre-built packages are available for these platforms. See the [downloads page](https://crosswalk-project.org/documentation/downloads.html) for details. Work is also underway to support Windows 10 desktop.

### <a class="doc-anchor" id="How-to-use-Crosswalk-on-a-project-using-ProGuard"></a>How to use Crosswalk on a project using ProGuard

Don't use ProGuard to obfuscate the xwalk core library because it will create a "broken JNI link" error. Add the following ProGuard rules in the proguard-project.txt file:"

    -keep class org.xwalk.core.** {
        *;
    }
    -keep class org.chromium.** {
	    *;
    }
    -keepattributes **

## <a class="doc-anchor" id="Canvas-and-WebGL-support"></a>Canvas and WebGL support

### <a class="doc-anchor" id="Why-wont-WebGL-work-in-Crosswalk-on-my-device"></a>Why won't WebGL work in Crosswalk on my device?

Chromium has a blacklist of GPUs which are know to cause stability and/or conformance problems when running WebGL. Chromium will disable WebGL if running on a device with one of the GPUs in this list.

Crosswalk uses the same blacklist. Consequently, if Crosswalk is running on a device with a blacklisted GPU, WebGL is disabled by default.

For more information about which GPUs are blacklisted and when, see the [Khronos WebGL wiki](http://www.khronos.org/webgl/wiki/BlacklistsAndWhitelists#Chrome).

### <a class="doc-anchor" id="Can-I-force-Crosswalk-to-enable-WebGL"></a>Can I force Crosswalk to enable WebGL?

A work-around is available if you want to test an application using WebGL on a device with a blacklisted GPU: pass the `--ignore-gpu-blacklist` command-line option to the `xwalk` binary. But you can't do this directly if Crosswalk is embedded in an application as a native library (for example, using Crosswalk Cordova, the Crosswalk Android packaging tool, or using the embedding API).

However, you can use a custom command-line (Crosswalk 6 or later) by adding a text file called `xwalk-command-line` (no suffix) to the `assets/` directory of your Android `apk` package. This file should contain a single line, representing the `xwalk` command line to run; in this case, the line would be:

    xwalk --ignore-gpu-blacklist

(Other command-line options can be added to the file if desired.)

The method for adding this file to your Android package depends on how you are using Crosswalk:

*   If you are **[embedding Crosswalk in an Android application](/documentation/android/embedding_crosswalk.html)**, the file should be placed in the `assets/` directory of your project.

*   If you are **[using Crosswalk Cordova](/documentation/cordova.html)**, the file should be placed in the `assets/` directory of your project.

*   If you are **[building an Android package with the `make_apk.py` script](/documentation/android/run_on_android.html)**, you can pass an option to create the file inside the output Android package:

    ```
    $ make_apk.py --manifest=mygame/manifest.json \
      --xwalk-command-line="--ignore-gpu-blacklist"
    ```

Note that enabling WebGL on platforms with blacklisted GPUs could result in the application (or the whole device) freezing or crashing, so it is not recommended for production applications.

### <a class="doc-anchor" id="Why-is-canvas-performance-poor-on-my-device"></a>Why is canvas performance poor on my device?

If a device has a [blacklisted GPU](#Why-won't-WebGL-work-in-Crosswalk-on-my-device?), canvas elements are not hardware accelerated. This can result in poor performance. [Forcing Crosswalk to ignore the GPU blacklist](#Can-I-force-Crosswalk-to-enable-WebGL?) can improve performance, but may cause your application to become unstable.

## <a class="doc-anchor" id="Embedding-API"></a>Embedding API

### <a class="doc-anchor" id="Webview-compatibility"></a>I used to be able to call WebView methods from the Crosswalk embedding API, but starting with Crosswalk 9 this doesn’t work anymore. Why is that?

The embedding API in Crosswalk 8 unfortunately included some classes and methods that were not meant to be accessed, and have thus been made private starting from Crosswalk 9.

From the start, we have chosen not to be 100% compatible with the existing Android WebView API. The reason behind this decision was that we wanted to avoid locking ourselves into supporting old legacy API which would not be needed for our common use-cases and negatively affect performance and the ability to refactor and improve the project.

The goal of the Crosswalk Project is to provide the latest web technologies to application developers, while being very performant and modern. Making it a drop-in replacement for the WebView technically goes against these goals.

That being said, we are very open to feedback and extending our API.

If you have a specific need, you should create a feature request in the Crosswalk Project JIRA and specify your use-case and requirements for the API.

If the API is blocking you from moving to a newer version of Crosswalk, please explain why and we will try to adjust the priority accordingly.

## <a class="doc-anchor" id="iOS-Support"></a>iOS Support

### <a class="doc-anchor" id="Why-use-iOS"></a>Why should I use Crosswalk when creating an iOS web app?

Crosswalk for iOS advantages include:
<ul><li>a performance boosted web runtime which is based on the latest WKWebView</li>
    <li>a unified web application creation and mantainance experience across platforms (Android, iOS, etc.)</li>
    <li>a much easier way for hybrid developers to extend their own Web APIs</li>
    <li>the ability to leverage Cordova Plugins</li>
</ul>

### What features are different between Android and iOS?  Will my Android+Crosswalk app "just work" on iOS? 

On Android, Crosswalk uses a Chromium-based web engine. iOS store limitations prevent us from using the Chromium engine.  Thus, Crosswalk on iOS relies on WKWebView. The differences between the two engines will result in HTML5 feature differences and some Crosswalk specific features (e.g. WebCL and SIMD.js) can not be supported on iOS.  The Crosswalk unified web API should reduce platform differences for both Android and iOS and, for most features, your unmodified Android web app should works the same on iOS.

### What versions of iOS does Crosswalk support?	

iOS 8 or newer

### Do I need a Mac to create iOS web apps?  Can I build Android and iOS apps from the same system?	

Yes, you need a Mac to build iOS apps with Crosswalk. You can also install the Android SDK and create Android applications using the Crosswalk build tools, although the setup for this environment is not yet documented.

### I use Cordova to build my iOS apps currently.  What is the difference with Crosswalk?  Can I use them both?	

Crosswalk is based on WKWebView which enables a lot of new HTML5 features and is up to 10x times faster than UIWebView used by Cordova. Crosswalk also supports the Cordova Plugins mechanism enabling apps to use the large body of Cordova plugins and leverage a plethora of features. Third-party plugins should also work, though this has not been tested.

### What is the basic workflow for building apps with Crosswalk?	

**Web app developers:**

 * Use `crosswalk-app-tool create` to create a template project
 * Write your HTML5 source codes and place them in the right directory
 * Run `crosswalk-app-tool build` to build them into an iOS application

**Hybrid app developers:**

 * Create your own iOS application project
 * Import `XWalkView.xcodeproj` into your project
 * Embed `XWalkView` target as a framwork in your app target
 * Embed the extensions frameworks into your app, if you have any extensions
 * For the app itself:
  * Add `XWalkView` into your view controller as the main view
  * Create extensions and load them
  * Load your entry page

Please refer to [Crosswalk Project for iOS](/documentation/ios.html) for more details.
	
### Will Crosswalk Project for iOS be published to CocoaPods?	

This is on our plan and we are pushing forward to make it happen.


****************************************************************





## <a class="doc-anchor" id="The-Crosswalk-Project-community"></a>The Crosswalk Project community

### <a class="doc-anchor" id="Who-is-using-the-Crosswalk-Project"></a>Who is using the Crosswalk Project?

Crosswalk is still a young project but quickly gaining momentum.  There are currently over 300 applications (mostly games) in app stores that are built with Crosswalk.

### <a class="doc-anchor" id="How-often-is-the-Crosswalk-Project-released"></a>How often is the Crosswalk Project released?

Crosswalk is updated to the latest Chromium once every six weeks. In practice, this means that the longest gap between a feature appearing in Chromium and the same feature appearing in Crosswalk is six weeks.

For more details, see [this explanation of how Crosswalk relates to Chromium](https://github.com/crosswalk-project/crosswalk-website/wiki/Downstream-Chromium).

### <a class="doc-anchor" id="Can-I-get-involved"></a>Can I get involved?

Yes. We welcome contributions from anyone who would like to make the project better, whether by writing code, filing bugs, or adding documentation. Full details of how to get involved are [on the Crosswalk website](https://crosswalk-project.org/contribute/index.html).

## <a class="doc-anchor" id="Commercial-aspects"></a>Commercial aspects

### <a class="doc-anchor" id="Do-I-have-to-pay-for-the-Crosswalk-Project"></a>Do I have to pay for the Crosswalk Project?

No, Crosswalk is an open source project, hosted on [github](https://github.com/crosswalk-project/crosswalk), and licensed under the [BSD licence](https://github.com/crosswalk-project/crosswalk/blob/master/LICENSE). It is free to use for any purpose, commercial or otherwise.

### <a class="doc-anchor" id="If-Im-not-paying-for-the-Crosswalk-Project-who-is"></a>If I'm not paying for the Crosswalk Project, who is?

Crosswalk development is largely sponsored by Intel, but builds on top of [Chromium](http://www.chromium.org/) development.

### <a class="doc-anchor" id="Can-I-get-commercial-support-for-the-Crosswalk-Project"></a>Can I get commercial support for the Crosswalk Project?

Not at the moment, but we would love to hear from you if you need it.

## <a class="doc-anchor" id="Relationships-with-other-projects"></a>Relationships with other projects

### <a class="doc-anchor" id="How-does-Crosswalk-relate-to-the-Intel-XDK"></a>How does Crosswalk relate to the Intel XDK?

The [Intel XDK](http://xdk-software.intel.com/) is a development environment (IDE) for HTML5 applications. When a developer builds an application using the XDK, they have a choice of exporting their application to a Crosswalk Android package (apk). Packages built this way by the XDK consist of the HTML5 application, bundled with its own Crosswalk runtime.

### <a class="doc-anchor" id="Is-Crosswalk-a-replacement-for-Phonegap-Cordova"></a>Is Crosswalk a replacement for Phonegap/Cordova?

No, they are complementary. If you intend to build for multiple platforms (beyond Android and Tizen), need extensive documentation and a very mature community, Cordova may be a better choice. If you are interested in hardware-accelerated WebGL support and bleeding edge HTML5 features, Crosswalk may be a better choice.

Having said this, you can get the best of both worlds by [using Cordova APIs from Crosswalk](/documentation/cordova.html) if you wish.

### <a class="doc-anchor" id="Does-Crosswalk-for-Android-use-the-Android-webview"></a>Does Crosswalk for Android use the Android webview?

No. Crosswalk is effectively a modified version of Chromium, the open source basis of the Google Chrome browser.

### <a class="doc-anchor" id="Why-do-I-need-Crosswalk-now-that-Android-KitKat-and-later-has-a-Chrome-based-webview"></a>Why do I need Crosswalk now that Android (KitKat and later) has a Chrome-based webview?

Crosswalk provides access to the [full range of modern web APIs](/documentation/apis/web_apis.html) supported by Chrome. By contrast, the Android Chrome-based web view [lacks some features](https://developers.google.com/chrome/mobile/docs/webview/overview#does_the_new_webview_have_feature_parity_with_chrome_for_android) which are available in Chrome on Android.

On top of this, Crosswalk adds extra features which are *not* available in either Chrome or the Android webview, such as experimental support for [SIMD](https://01.org/blogs/tlcounts/2014/bringing-simd-javascript) and support for the [Presentation API](https://github.com/crosswalk-project/crosswalk-website/wiki/presentation-api-manual).

### <a class="doc-anchor" id="Why-use-Blink-vs-the-higher-level-Chromium-Embedded-Framework-as-a-basis-for-Crosswalk"></a>Why use Blink vs. the higher-level Chromium Embedded Framework as a basis for Crosswalk?

[CEF 1.0](https://code.google.com/p/chromiumembedded/) has proven to be quite popular, but is being [phased out](http://www.magpcss.org/ceforum/viewtopic.php?f=10&t=10647&sid=510426ccd8a9650f72ba416d7b51de06) in favor of the larger CEF 3.0. Since we want a consistent implementation in the Crosswalk project, we had to pick a level in the Chromium architecture that could accommodate both use cases. By starting with Blink and building up, rather starting with CEF 3.0 and removing pieces, we think we'll end up with a tighter, more consistent result.

### <a class="doc-anchor" id="When-should-I-use-Chromes-new-packaged-apps-rather-than-Crosswalk"></a>When should I use Chrome's new packaged apps rather than Crosswalk?

With Chrome packaged apps, you get access to the Chrome app store and the capabilities Chrome offers.

With the Crosswalk project, you have different possibilities:

* If you are building a platform, you can include a Crosswalk Application Runtime as a service for your own catalog of applications.
* A developer can package an application with a Crosswalk Application runtime so that the app and runtime are never revised without developer permission.

Of course, because Crosswalk is based on Blink and Chromium, a developer could publish a standard HTML5 app for both Crosswalk and Chrome.

## <a class="doc-anchor" id="Common-issues"></a>Common Issues

### <a class="doc-anchor" id="Switch-to-shared-mode"></a>I packaged my application in embedded mode, but when started it asks to download Crosswalk from the Play Store. Why?

Most probably, you have installed an APK built for ARM architecture on an x86 device. When this happens, Crosswalk tries to download the x86 version of the runtime to be able to start the application. To avoid this, ensure that the x86 version of your application is published to the Play Store.

