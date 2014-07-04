# Frequently-asked questions

If you have any questions that are not answered below, the crosswalk-help mailing list is a good place to ask them. Alternatively, contact us directly via the #crosswalk IRC channel on Freenode. See the [Community page](#documentation/community) for more details.

### What is Crosswalk for?

If you are a developer working with web technologies, Crosswalk enables you to deploy a web application with its own dedicated runtime. This means three things:

1.  You can distribute your web application via app stores.
2.  Your application won't break in whatever ancient webviews or browsers your audience is using, as you control the runtime and its upgrade cycle.
3.  You can build applications without worrying so much about runtime differences and quirks: you only have one runtime to deal with.

### How big is the Crosswalk runtime, and how will it affect my application's size?

To give a rough idea, the HTML/JS/CSS for [one of the project's test applications](https://github.com/crosswalk-project/crosswalk-apk-generator/tree/master/test/functional/demo-app) takes up 3.9Mb of disk space.

Once this application is packaged with its own Crosswalk (x86 Android) runtime, the apk file size is 21.2Mb.

Unpacked, the files take up 49.8Mb of disk space, 45.9Mb of which is occupied by the Crosswalk runtime and its supporting files.

### Can one Crosswalk installation be shared between multiple applications?

Bundling the runtime with the application (aka "embedded mode") is the simplest approach for distribution purposes. But Crosswalk applications *can* share a single Crosswalk runtime library (in "shared mode"); and a package which enables shared mode is part of the Crosswalk for Android distribution. However, you would have to distribute this shared runtime package yourself.

### How can I distribute a Crosswalk Android application across multiple architectures?

The Crosswalk binaries are architecture-specific. This means that you will need an x86-compatible Crosswalk on Android devices with x86 chips; and an ARM-compatible Crosswalk on Android devices with ARM chips.

There are two approaches to building an application which supports both x86 and ARM platforms:

*   Build two separate packages for your application, one for x86 and one for ARM; then upload both to the app stores where you are hosting your application. Prominent stores like Google Play have support for [uploading multiple packages for different platforms](http://developer.android.com/google/play/publishing/multiple-apks.html).

    The [Crosswalk apk generation script](#documentation/getting_started/run_on_android) (`make_apk.py`) generates packages for both architectures to facilitate this way of working.

*   Build one package for your application, but include both the x86 and ARM versions of Crosswalk in it. The down-side of this approach is that it makes the package file very large (c. 40Mb before you add your application code).

    Creating a package this way requires you to copy the Crosswalk shared object files for both architectures into the `lib/` directory of the apk package file. For example, an apk with support for x86 and ARM would contain the following files:

    <pre>
    lib/armeabi-v7a/libxwalkcore.so
    lib/x86/libxwalkcore.so
    </pre>

    How you achieve this depends on your build process. If you need a reference, see [the Cordova migration instructions](#documentation/cordova/migrate_an_application/Multi-architecture-packages), which explain how to do this in the context of Crosswalk Cordova.

### Can I use Crosswalk to "appify" my website?

Yes. You can wrap a website URL with a Crosswalk runtime so it behaves like an app (fullscreen, no browser chrome, home screen icon etc.).

### Can I customise Crosswalk?

Yes. Crosswalk itself can be modified, as the code is open source. We actively encourage [contributions](https://crosswalk-project.org/#contribute/overview).

Alternatively, you can add extra capabilities to Crosswalk through its [extension mechanism](#wiki/Crosswalk-Extensions) without having to modify the core code. This enables an application to access platform features via native code (Java on Android, C/C++ on Tizen) and go beyond the boundaries of the web runtime.

### Why won't WebGL work in Crosswalk on my device?

Chromium has a blacklist of GPUs which are know to cause stability and/or conformance problems when running WebGL. Chromium will disable WebGL if running on a device with one of the GPUs in this list.

Crosswalk uses the same blacklist. Consequently, if Crosswalk is running on a device with a blacklisted GPU, WebGL is disabled by default.

For more information about which GPUs are blacklisted and when, see the [Khronos WebGL wiki](http://www.khronos.org/webgl/wiki/BlacklistsAndWhitelists#Chrome).

### Can I force Crosswalk to enable WebGL?

A work-around is available if you want to test an application using WebGL on a device with a [blacklisted GPU](#Why-won't-WebGL-work-in-Crosswalk-on-my-device?): pass the `--ignore-gpu-blacklist` command-line option to the `xwalk` binary. However, you can't do this directly if Crosswalk is embedded in an application as a native library (for example, using Crosswalk Cordova, the Crosswalk Android packaging tool, or using the embedding API).

In these situations, you can use a custom command-line by adding a file called `xwalk-command-line` to the root directory of your application. (NB this only works for Crosswalk 6 and above.) The file should contain a single line, representing the `xwalk` command line to run:

    xwalk --ignore-gpu-blacklist

(Other command-line options can be added via the same mechanism.)

Alternatively, you can set a custom command line when building an Android package with the [`make_apk.py` script](#documentation/getting_started/run_on_android):

    make_apk.py --manifest=mygame/manifest.json \
      --xwalk-command-line="--ignore-gpu-blacklist"

Note that enabling WebGL on platforms with blacklisted GPUs could result in the application (or the whole device) freezing or crashing, so it is not recommended for production applications.

### Why is canvas performance poor on my device?

If a device has a [blacklisted GPU](#Why-won't-WebGL-work-in-Crosswalk-on-my-device?), canvas elements are not hardware accelerated. This can result in poor performance. [Forcing Crosswalk to ignore the GPU blacklist](#Can-I-force-Crosswalk-to-enable-WebGL?) can improve performance, but may cause your application to become unstable.

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

Crosswalk provides access to the [full range of modern web APIs](https://crosswalk-project.org/#documentation/apis/web_apis) supported by Chrome. By contrast, the Android Chrome-based web view [lacks some features](https://developers.google.com/chrome/mobile/docs/webview/overview#does_the_new_webview_have_feature_parity_with_chrome_for_android) which are available in Chrome on Android.

On top of this, Crosswalk adds extra features which are *not* available in either Chrome or the Android webview, such as experimental support for [SIMD](https://01.org/blogs/tlcounts/2014/bringing-simd-javascript) and support for the [Presentation API](https://crosswalk-project.org/#wiki/presentation-api-manual).

### How often is Crosswalk released?

Crosswalk is updated to the latest Chromium once every six weeks. In practice, this means that the longest gap between a feature appearing in Chromium and the same feature appearing in Crosswalk is six weeks.

For more details, see [this explanation of how Crosswalk relates to Chromium](https://crosswalk-project.org/#wiki/Downstream-Chromium).

### Which platforms does Crosswalk support?

Crosswalk officially supports [Android](http://www.android.com/) (version 4.0 and above), [Tizen Mobile](https://www.tizen.org/) (Tizen version 2) and [Tizen IVI](https://wiki.tizen.org/wiki/IVI) (Tizen version 3). Pre-built packages are available from https://download.01.org/ for each of these platforms. See the [downloads page](https://crosswalk-project.org/#documentation/downloads) for details.

Crosswalk builds for Windows, Mac OS and Linux are available: see https://build.crosswalk-project.org/waterfall. However, these platforms do not receive intensive QA, and are community-supported. If you would like to help with these efforts, please get in touch.

Also note that we don't have a way to package your app with Crosswalk for deployment on desktop (as an .exe, .dmg or similar). Again, if you are interested in working on this, please feel free to contact us.

Crosswalk does not support iOS.

### Can I get involved?

Yes. We welcome contributions from anyone who would like to make the project better, whether by writing code, filing bugs, or adding documentation. Full details of how to get involved are [on the Crosswalk website](https://crosswalk-project.org/#contribute/overview).

### Do I have to pay for Crosswalk?

No, Crosswalk is an open source project, hosted on [github](https://github.com/crosswalk-project/crosswalk), and licensed under the [BSD licence](https://github.com/crosswalk-project/crosswalk/blob/master/LICENSE). It is free to use for any purpose, commercial or otherwise.

### If I'm not paying for Crosswalk, who is?

Crosswalk development is largely sponsored by Intel, but builds on top of [Chromium](http://www.chromium.org/) development.

### Can I get commercial support for Crosswalk?

Not at the moment, but we would love to hear from you if you need it.
