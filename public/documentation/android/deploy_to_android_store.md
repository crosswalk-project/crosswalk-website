# Deploy to an Android app store

A Crosswalk app for Android can be deployed to an app store after following the previous steps. The deployment mode varies depending on the packaging mode used (embedded or shared).

In this tutorial, you packaged the application using embedded mode. However, it is also possible to [package an application using shared mode](/documentation/android/run_on_android.html#shared-vs-embedded-mode).

Methods for deploying both types of package to an app store are briefly outlined below.

## Deploying an embedded mode Crosswalk app

To ensure that a Crosswalk app runs on as many Android devices as possible, developers are advised to upload both embedded packages to the app store. In practice, this means that a developer should do the following for each web app packaged in embedded mode:

*   Upload an x86-based Crosswalk app apk to app store.
*   Upload an ARM-based Crosswalk app apk to app store.

Google Play store already supports multiple apks for each app: see [this article](http://developer.android.com/google/play/publishing/multiple-apks.html) for details.

## Deploying a shared mode Crosswalk app

For each web app packaged in shared mode, developers are advised to:

*   Upload the Crosswalk runtime apk for Intel architecture to app store. This only needs to be done once for all applications sharing the runtime.
*   Upload the Crosswalk runtime apk for ARM architecture to app store.  This only needs to be done once for all applications sharing the runtime.
*   Upload the apk for each Crosswalk app to app store. Each of these will use the shared architecture-specific runtime apk.

Google Play store already supports multiple apks for a single "app" (here, the Crosswalk runtime counts as an "app" which is used to run your actual, *real* web apps): see [this article](http://developer.android.com/google/play/publishing/multiple-apks.html) for details.
