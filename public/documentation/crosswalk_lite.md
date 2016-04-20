# Crosswalk Lite

Crosswalk Lite, or "Lite", is the Crosswalk runtime designed to be as small as possible by removing less common libraries and features and compressing the APK.

## Overview

Crosswalk is based on the [open-source Chromium project](https://www.chromium.org/Home) which continues to grow as new features are added. For smaller applications, the additional ~20MB added to the APK size when built with Crosswalk might be too much. These applications may not require all of the advanced features that are part of the default Chromium (and Crosswalk) builds. Crosswalk Lite makes some hard cuts to create a smaller redistributable runtime, while still keeping the most commonly-used features.

In the end, what Crosswalk version you choose depends on your needs. We are happy to listen to input on your project requirements.

### Supported platforms
* Lite is currently Android only and does not support shared mode
* Lite only supports 32-bit builds for x86 and ARM. x86_64 and ARM64 are not yet supported.

### Size and Feature Selection

The table below shows the estimated <i>additional</i> size added to a web application built with Crosswalk and Crosswalk Lite.
<table style="text-align:center;border:1px solid black">
<tr><th colspan=2 style="text-align:center;border:1px solid black">Crosswalk</th>
    <th colspan=2 style="color:blue;text-align:center">Crosswalk Lite</th></tr>
<tr><td style="border:1px solid black">APK</td>
    <td style="border:1px solid black">Installed</td>
    <td style="border:1px solid black;color:blue;">APK</td>
	<td style="border:1px solid black;color:blue;">Installed</td></tr>
<tr><td style="border:1px solid black">20MB</td>
     <td style="border:1px solid black">55MB</td>
	 <td style="border:1px solid black;color:blue;">10-15MB</td>
	 <td style="border:1px solid black;color:blue;">40MB</td></tr>
</table>

* Lite is approximately half the size of regular Crosswalk
* At ~10MB in size, using Lite leaves about 40MB for application data, due to the current limit of 50MB in Google Play store
* The list of features removed is evaluated closely and tracked on the project wiki page: [Crosswalk Lite Disabled feature list](/documentation/crosswalk_lite/lite_disabled_feature_list.html). We use flags to disable features like WebRTC, WebDatabase, etc.
* The final library is compressed using LZMA to produce a smaller APK. The APK must then be decompressed when the application is first run.  See [Runtime behavior](#runtime-behavior) below.
* The compile options are set to optimize for size.

### Configurability
Ideally developers could select which options they need and build a custom runtime for their project. Unfortunately Chromium, a large and relatively complex project, is not designed to be modular and the ability of the team to restructure it and guarantee reliable builds is not feasible at this time. In the long term, we would like to improve Chromium, Blink, and Crosswalk to modularize its features so that specific features (like WebRTC) can be turned on/off at APK build time.

### <a class="doc-anchor" id="runtime-behavior"></a> Runtime behavior
* A dialog showing ["Preparing runtime..."](src='/assets/crosswalk-lite-uncompress-dialog.png') pops up when an application built with Lite is first started. This only happens the first time it is run.
  
### Release cycle

* Lite is not released as often as mainline Crosswalk, nor it is rebased to the latest Chromium with the same cadence. 
* Lite does not follow canary/beta/stable channels like the main project. 
* Release cycles are 12-weeks, or on request.

### QA and validation

* Lite is tested regularly.  However, our main focus remains on the mainstream Crosswalk. Also, because Lite diverges from Chromium more than Crosswalk does, there is more opportunity for unexpected bugs.
* Given that Lite removes many lesser-used features, please make sure your app does not require these before selecting to build with Lite
* If optimizations done in Lite are proven to be safe, they will be merged to the mainstream Crosswalk releases

## How to contribute
To contribute to Crosswalk Lite, follow the same guideline as in https://crosswalk-project.org/contribute/.

```
$ gclient config --name=src/xwalk \
    git://github.com/crosswalk-project/crosswalk.git@origin/crosswalk-lite
```

* Code reviews and contribution model is the same as for Crosswalk
* Crosswalk Lite is developed in a separte branch, though we will consider backporting relevant patches to Crosswalk proper

