Crosswalk 14 is in beta! Starting from this release, Crosswalk is [available](https://play.google.com/store/apps/details?id=org.xwalk.core&hl=en) in the Google Play Store and applications can be configured to download it on demand. Also, this release comes with the usual update to Chromium (M43)and introduces the WebCL API for devices that support OpenCL. Finally, we have added SIMD support for code written in asm.js.  

## Shared Crosswalk runtime available in the Google Play Store
<quality test-type="feature" test-segment="Embedded Crosswalk" test-arch="X86" test-build="14.43.343.9" test-feature="Shared Runtime in Google Play"></quality>

You can now package your application in “shared mode”. This means that your application’s APK won’t include the Crosswalk runtime, which will be downloaded from the Play Store when the application is first started. From that point on, the Crosswalk runtime will be shared with any other Crosswalk application that is also packaged in shared mode. The advantage is that your APK doesn’t need to include the Crosswalk library, making it a lot smaller. A few things to notice:

* The user will be directed to the Play Store and will need to accept the installation of Crosswalk
* There will be only one version of Crosswalk at any given time and it will be updated automatically 
* Shared mode is not yet supported by the Crosswalk Cordova WebView plugin. To use it with Cordova applications you’ll need to use our fork of Cordova Android 3.6
* This feature is currently in Beta and backwards compatibility won’t be guaranteed until it’s promoted to stable. If you use it in your published applications, be prepared to release an update

To package the application in shared mode with make_apk use the option:

```
./make_apk.py --mode=shared ...
```

To create a cordova project in shared mode, use the option:

```
./bin/create --xwalk-shared-library ...
```

## Chromium 43
<quality test-type="feature" test-segment="Embedded Crosswalk" test-arch="X86" test-build="14.43.343.9" test-feature="Chromium 43 New Features"></quality>

Chromium 43 introduces Web MIDI support, new features to improve security and compatibility and a number of small changes to enable developers to build more powerful web applications. For more information see the [announcement](http://blog.chromium.org/2015/04/chrome-43-beta-web-midi-and-upgrading.html) in the Chromium Developers blog.


## WebCL
<quality test-type="feature" test-segment="Embedded Crosswalk" test-arch="X86" test-build="14.43.343.9" test-feature="WebCL"></quality>

[WebCL](https://www.khronos.org/webcl/) defines a JavaScript binding to the Khronos [OpenCL](https://www.khronos.org/opencl/) standard for heterogeneous parallel computing. WebCL enables web applications to harness GPU and multi-core CPU parallel processing and achieve significant acceleration. The WebCL API first landed in Crosswalk Canary (version 13.41.304.0) as an experimental feature, and is now enabled by default on Android.

Because WebCL feature depends on the OpenCL library, before you try it, please make sure that “libPVROCL.so*” or “libOpenCL.so*” are present under “/system/vendor/lib” folder on the device. The WebCL feature was verified on a Asus MemoPad8 and Xiaomi3. You can also check the OpenCL support with an application like [OpenCL Info](https://play.google.com/store/apps/details?id=com.xh.openclinfo).

## SIMD.js and ASM.js optimizations in V8 Turbo Fan
<quality test-type="feature" test-segment="Embedded Crosswalk" test-arch="X86" test-build="14.43.343.9" test-feature="SIMD"></quality>

[asm.js](http://asmjs.org/) is a highly optimizable, low-level subset of JavaScript. With the newly introduced TurboFan engine, V8 is able to recognize asm.js module (“use asm” directive) and emits very efficient machine code. Crosswalk 14 adds the SIMD.js support to the TurboFan engine. It allows web developers to utilize SIMD.js API in asm.js code or cross-compile C/C++ code with SSE intrinsics to asm.js with SIMD.js via [emscripten](http://kripken.github.io/emscripten-site/index.html).

## Embedding API Updates
<quality test-type="feature" test-segment="Embedded Crosswalk" test-arch="X86" test-build="14.43.343.9" test-feature="Embedding API"></quality>

The Crosswalk XwalkView class has a new method to control whether its surface is placed on top of its window. Note that this only works when XWalkPreferences.ANIMATABLE_XWALK_VIEW is false.

```public void setZOrderOnTop (boolean onTop);```

## Notable bug fixes

The --disk-cache-size command line switch now behaves as expected (bug XWALK-3821)

## Quality Summary

Please view the [Quality Dashboard](/documentation/quality_dashboard.html?build=14.43.343.9) for detailed quality summary of this release.
