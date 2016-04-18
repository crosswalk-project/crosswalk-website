## Why Crosswalk Project Lite might matter to you

As we known, Crosswalk is based on chromium which is bigger and bigger. For smaller applications, an increased size of approximately 20MB might be too much for some users. As these users often don't depend on advanced features such as WebRTC, Crosswalk Project Lite attempts to make some hard cuts, bringing you a smaller redistributable runtime, while still having most common features available.

In the end, what Crosswalk version you choose depends on your needs, and we are happy to listen to input on your exact requirements.

Some quick facts:
* Crosswalk Project Lite (Lite or Crosswalk-Lite for brevity) is approximately half the size of the regular Crosswalk, 
* Lite is Android only and doesn´t support shared mode.
* This leaves around 40MB for application data, due to the current limit of 50MB in Google Play store
* Supports x86, ARM, no plan to support x86_64 and ARM64 so far.
* Lite is not released as often as mainline Crosswalk, nor it is rebased to the latest Chromium with the same cadence.
* We do test Lite, but our main focus remains the mainstream Crosswalk. Also, because Lite diverges from Chromium more than Crosswalk does, there may be unexpected bugs. We cut off lots "seldom used" features, please make sure your app doesn't need these cut-off features first. you might have to turn to Crosswalk normal build if unlucky.
* Eventually, if the optimizations done in Lite prove to be safe, they will be merged to the official Crosswalk releases.

Long term, we would like to improve Chromium, Blink and Crosswalk to modularize its features, so that specific features (like WebRTC) can be turned on at the time the users build an APK.

## Join the project

Crosswalk Project Lite is developed in a separte branch, though we will consider backporting relevant patches to Crosswalk proper.

* Uses a separate branch and has separate binary releases
* Features which are cut off are evaluated closely by the team and users are encouraged to provide feedback
* Code reviews and contribution model is the same as for Crosswalk
* Currently the project does not follow canary/beta/stable channels like the main project.
* Release cycles are 12-week - or on-request.

## Way to reduce binary size

* Cut feature([Crosswalk Lite Disabled feature list](/documentation/crosswalk_lite/lite_disabled_feature_list.html)). We use flags to enable/disable features like WebRTC/WebDatabase/WebGeolocation/... etc.
* Compress Library. The native library is compressed by LZMA by default, So user can get a smaller apk. The decompressing happens only when the first time it is opened.
* Compiler Optimize Options. Tell compiler to Optimize for size, to reduce code size.

## Pros

* Smaller size, Just smaller.

## Cons

* Some feature are disabled. Apparently.
* A decompressing dialog pops out for serveral seconds when the first time your app is opened.
* Many hard modification/deletion made in the engine may bring unexpected bugs.


## How to contribute
To contribute to Crosswalk Project Lite, follow the same guideline as in https://crosswalk-project.org/contribute/
```
gclient config --name=src/xwalk \
  git://github.com/crosswalk-project/crosswalk.git@origin/crosswalk-lite
```
