We are happy to announce that version 12 of the Crosswalk Project for Android is in Beta. This release sees an update to Chromium 41 and introduces some new functionality in the Embedding and SIMD.js API. As usual, we welcome feedback on the [crosswalk-help](https://lists.crosswalk-project.org/mailman/listinfo/crosswalk-help) mailing list or [JIRA](https://crosswalk-project.org/jira/).


## Chromium 41 

With this release Crosswalk Project is updated to Chromium 41, which brings new ES6 template literals and new debugging features for CSS Animation and Service Worker. Read more about Chromium 41 in this [announcement](http://blog.chromium.org/2015/01/chrome-41-beta-new-es6-features-and.html).

For a list of new features in Chromium 41, check the [Chromium Dashboard](https://www.chromestatus.com/features)


## Embedding API updates

### XWalkView

Set the User Agent string of the application:

    void setUserAgentString(String userAgent)

Use `XWalkView.setBackgroundColor(0)` to make the XWalkView transparent ([XWALK-3308](https://crosswalk-project.org/jira/browse/XWALK-3308))

### XWalkUIClient

Notify the host application of a console message.:

    void onConsoleMessage(XWalkView view, String message, int lineNumber, String sourceId, ConsoleMessageType messageType)


## SIMD updates

The [SIMD.js API](https://github.com/johnmccutchan/ecmascript_simd/) now implements load and store of data types. These APIs allow developers to load or store all or partial elements of SIMD data from or to variable typed arrays and are important for several use cases:


Adds "ByScalar" suffix to shift operations according to the latest [SIMD.js spec](https://github.com/johnmccutchan/ecmascript_simd/). It clarifies that their shift count is a scalar value, and to make namespace room for adding vector-vector shifts in the future.

    SIMD.int32x4.shiftLeftByScalar
    SIMD.int32x4.shiftRightLogicalByScalar
    SIMD.int32x4.shiftRightArithmeticByScalar

SIMD.js introduces new swizzle and shuffle APIs to rearrange lanes in SIMD data types. They include:

    SIMD.float32x4.swizzle
    SIMD.float32x4.shuffle
    SIMD.float64x2.swizzle
    SIMD.float64x2.shuffle
    SIMD.int32x4.swizzle
    SIMD.int32x4.shuffle

***

[Full release notes in Jira](https://crosswalk-project.org/jira/secure/ReleaseNote.jspa?projectId=10001&version=10800)

Download Crosswalk 12 from the [Beta Channel](https://download.01.org/crosswalk/releases/crosswalk/android/beta/) 