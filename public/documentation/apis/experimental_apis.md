# Experimental APIs

In addition to standard APIs, the Crosswalk Project provides additional experimental or emerging-standards APIs to further support building a native application experience using web platform technologies.

* [Device Capabilities](http://www.w3.org/2012/sysapps/device-capabilities/) - Retrieve information about the underlaying system.
	* [GitHub Repo](https://github.com/sysapps/device-capabilities)
	* [Tizen System Namespace Reference](https://developer.tizen.org/dev-guide/2.2.0/org.tizen.native.apireference/namespaceTizen_1_1System.html)
	* [Chrome](http://www.chromium.org/developers/design-documents/extensions/proposed-changes/apis-under-development/systeminfo)

* [Launch Screen](https://crosswalk-project.org/documentation/manifest/launch_screen.html)<sup><a href="#v">[6.35.131.4]</a></sup> - Display a static user interface on application launch and hide it when the application is ready.

* [Presentation API](http://webscreens.github.io/presentation-api/)<sup>[\[a\]](#a)</sup> - Access external displays from within web applications. For more information see the [Presentation API developer documentation](https://github.com/crosswalk-project/crosswalk-website/wiki/Presentation-api-manual).
	* [Tutorial on Presentation API](https://crosswalk-project.org/documentation/presentation_api.html) based on [HTML5Hub](http://html5hub.com/presentation-api-tutorial/)/

* [Raw Sockets](http://www.w3.org/TR/raw-sockets/) - Raw TCP and UDP sockets for client and server sides.

* [SIMD](https://github.com/johnmccutchan/ecmascript_simd)<sup><a href="#v">[5.34.104.0]</a></sup> - Data types and operations for access to the Single Instruction Multiple Data (SIMD) instruction sets available on common CPU architectures, such as SSE (IA32/X64) and NEON (ARMv7). **Note that the implementation to generate NEON instructions is not done.**
	* [Tutorial on SIMD](https://crosswalk-project.org/documentation/using_simd.html)
	* [Getting Started Developing HTML5 projects with Pipeline](https://docs.unrealengine.com/latest/INT/Platforms/HTML5/GettingStarted/index.html)