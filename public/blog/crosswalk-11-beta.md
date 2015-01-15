Crosswalk 11 for Android has entered the Beta channel. This release sees an update to Chromium 40 and introduces some new functionality in the SIMD.js API. As usual, we welcome feedback on the [crosswalk-help](https://lists.crosswalk-project.org/mailman/listinfo/crosswalk-help) mailing list or [JIRA](https://crosswalk-project.org/jira/).

## Chromium 40 and Service Worker

Crosswalk 11 is rebased to Chromium 40, which introduces the [Service Worker API](http://www.w3.org/TR/service-workers/). Service Worker is a powerful API that can be used to improve the offline experience of applications that access data across a network.  

Two APIs for use within the service worker are included. The [Fetch API](https://fetch.spec.whatwg.org/) which allows to make cross-origin network requests and return the responses as well as the [Cache API](https://slightlyoff.github.io/ServiceWorker/spec/service_worker/index.html#cache-objects) which makes it possible to save fetched responses and return the from the cache next time the resource is requested, while bypassing the network and thus making the app work offline.

Only a subset of the Cache API is supported for now but full compatibility with the spec can be reached by using a [polyfill](https://github.com/coonsta/cache-polyfill/blob/master/dist/serviceworker-cache-polyfill.js). 

Below are some useful resources about Service Worker, we are eager to hear how you will use the API for your applications!

* http://blog.chromium.org/2014/12/chrome-40-beta-powerful-offline-and.html
* https://github.com/w3c-webmob/ServiceWorkersDemos
* http://www.chromium.org/blink/serviceworker

For a list of new features in Chromium 40, check the [Chromium Dashboard](https://www.chromestatus.com/features)

## SIMD updates

The [SIMD.js API](https://github.com/johnmccutchan/ecmascript_simd/) now implements load and store of data types. These APIs allow developers to load or store all or partial elements of SIMD data from or to variable typed arrays and are important for several use cases:

1. load/store SIMD data from/to non 16 bytes aligned memory.
1. load/store 1, 2 or 3 float32/int32 packed data structure from/to memory to/from SIMD types.
1. enable Emscripten (https://github.com/kripken/emscripten) generated SIMD.js code. 

APIs include:

	SIMD.float32x4.load
	SIMD.float32x4.loadX
	SIMD.float32x4.loadXY
	SIMD.float32x4.loadXYZ
	SIMD.float32x4.store
	SIMD.float32x4.storeX
	SIMD.float32x4.storeXY
	SIMD.float32x4.storeXYZ
	SIMD.float64x2.load
	SIMD.float64x2.loadX
	SIMD.float64x2.store
	SIMD.float64x2.storeX
	SIMD.int32x4.load
	SIMD.int32x4.loadX
	SIMD.int32x4.loadXY
	SIMD.int32x4.loadXYZ
	SIMD.int32x4.store
	SIMD.int32x4.storeX
	SIMD.int32x4.storeXY
	SIMD.int32x4.storeXYZ

***

[Full release notes in Jira](https://crosswalk-project.org/jira/secure/ReleaseNote.jspa?projectId=10001&version=10609)

Download Crosswalk 11 from the [Beta Channel](https://download.01.org/crosswalk/releases/crosswalk/android/beta/) 