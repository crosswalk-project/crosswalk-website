# Web APIs

The following standard APIs are supported by Crosswalk.

Version key:<br />
&nbsp;&nbsp;&nbsp;<sup id="a">[a]</sup> = Android only<br/>
&nbsp;&nbsp;&nbsp;<sup id="t">[t]</sup> = Tizen only;<br/>
&nbsp;&nbsp;&nbsp;<sup id="v">[X.X.X.X]</sup> = this Crosswalk version or later<br/>
&nbsp;&nbsp;&nbsp;<sup id="va">[X.X.X.X;x86|ARM]</sup> = this version or later for the specified architecture.

## Runtime & Packaging
* [app: URI](http://www.w3.org/2012/sysapps/app-uri/)<sup><a href="#t">[t]</a></sup> - Address resources inside a packaged application.

## Multimedia & Graphics
* [CSS Animations](http://www.w3.org/TR/css3-animations/) - Animate CSS properties over time using keyframes.
* [CSS Backgrounds and Borders Level 3](http://www.w3.org/TR/css3-background/) - CSS features for borders and backgrounds.
* [CSS Color Module Level 3](http://www.w3.org/TR/css3-color/) - CSS features for color values and properties.
* [CSS Flexible Box Layout ](http://www.w3.org/TR/css3-flexbox/) - A CSS box model for user interface design.
* [CSS Fonts Level 3](http://www.w3.org/TR/css3-webfonts/) - Font properties and dynamic font resource loading.
* [CSS Multi-column Layout](http://www.w3.org/TR/css3-multicol/) - Multi-column layouts with CSS.
* [CSS Text Decoration Level 3](http://www.w3.org/TR/css-text-decor-3/) - Text decoration CSS features.
* [CSS Transforms](http://www.w3.org/TR/css3-transforms/) - Change the position of content in 3D space without disrupting the normal flow using CSS.
* [CSS Transitions](http://www.w3.org/TR/css3-transitions/) - Enables property changes in CSS values over time.
* [HTML Canvas 2D Context](http://www.w3.org/TR/2dcontext/) - An API for 2D immediate mode graphics.
* [HTML5 Audio/Video](http://www.w3.org/TR/html5/) - Audio and Video support without plugins.
* [Media Queries Level 3](http://www.w3.org/TR/css3-mediaqueries/) - CSS media features for adapting the same content to different output devices and screens.
<!-- (Bob: Waiting for official support)* [Responsive Images](http://picture.responsiveimages.org/)<sup><a href="#v">[5.34.104.5]</a></sup> - Control which image resource is presented to a user, based on media query and/or image format support. -->
* [Scalable Vector Graphics (SVG) 1.1](http://www.w3.org/TR/SVG11/) - An XML markup language for 2D vector graphics.
* [WebRTC](http://www.w3.org/TR/webrtc/)<sup><a href="#a">[a]</a></sup> <sup><a href="#va">[7.36.154.6;x86]</a></sup> - Peer to peer sharing of video and audio streams.

## Networking & Storage
* [File API](http://dev.w3.org/2006/webapi/FileAPI/) - Asynchronously read the contents of files or raw data buffers stored on the client.
* [File API: Directories and System](http://dev.w3.org/2009/dap/file-system/file-dir-sys.html) - Expose a sandboxed filesystem on the client.
* [File API: Writer](http://dev.w3.org/2009/dap/file-system/file-writer.html) - Write files to a sandboxed sandboxed filesystem on the client.
* [HTML5 Web Messaging](http://www.w3.org/TR/webmessaging/) - A mechanism for communicating between browsing contexts.
* [Indexed DB](https://dvcs.w3.org/hg/IndexedDB/raw-file/default/Overview.html) - An asynchronous client-side storage API for fast access to large amounts of structured data.
* [Online State](http://www.w3.org/html/wg/drafts/html/CR/browsers.html#browser-state) - Online and offline events for the network state.
* [Web SQL](http://www.w3.org/TR/webdatabase/) - Store data in databases on the client using a variant of SQL.
* [Web Sockets](http://www.w3.org/TR/websockets/) - A low overhead bi-directional communication with web servers over a persistent TCP connection.
* [Web Storage](http://dev.w3.org/html5/webstorage/) - A simple synchronous client-side storage API for storing name-value pairs.
* [XMLHttpRequest](http://www.w3.org/TR/XMLHttpRequest/) - Transfer data between a client and a server programmatically over HTTP.

## Performance & Optimization
* [Navigation Timing](http://www.w3.org/TR/navigation-timing/) - Access timing information related to navigation and elements.
* [Page Visibility](http://www.w3.org/TR/page-visibility/)<sup><a href="#a">[a]</a></sup> - Programmatically determine the visibility state of the web application.
* [Resource Timing](http://www.w3.org/TR/resource-timing/) - Access timing information related to HTML elements.
* [Selectors Level 1](http://www.w3.org/TR/selectors-api/) and [Level 2](http://www.w3.org/TR/selectors-api2/) - Retrieve elements from the DOM using CSS selectors.
* [Typed Array 1.0](http://www.khronos.org/registry/typedarray/specs/latest/) - Data types for raw binary data for performance-critical tasks.
* [Web Workers](http://www.w3.org/TR/workers/) - Run scripts in parallel to the main page.

## Device & Hardware
* [Device Orientation Events](http://www.w3.org/TR/orientation-event/) - DOM events which provide information about the physical orientation and motion of the device.
* [Fullscreen](http://fullscreen.spec.whatwg.org/) - Programmatically instruct an element on the page to be presented in full screen mode.
* [Geolocation](http://www.w3.org/TR/geolocation-API/) - Access geographical location information associated with the hosting device.
* <a href="http://www.w3.org/TR/html5/forms.html#date-and-time-state-(type=datetime)">HTML5 Date and Time state for input element</a><sup><a href="#a">[a]</a></sup> - Picker controls for date and time input types.
*  <a href="http://www.w3.org/TR/html5/forms.html#telephone-state-(type=tel)">HTML5 Telephone, Email and URL state for input element</a><sup><a href="#a">[a]</a></sup> - Picker controls for telephone, email and URL input types.
* [Media Capture and Streams](http://www.w3.org/TR/mediacapture-streams/)<sup><a href="#a">[a]</a></sup> - Provides access to local media streams including video (camera) and audio (microphone).
* [Screen Orientation](http://www.w3.org/TR/screen-orientation/) - Enables screen orientation locking and provides access to screen orientation data and events.
* [Touch Events](https://dvcs.w3.org/hg/webevents/raw-file/v1/touchevents.html) - Handle touch events programmatically.
* [Vibration](http://www.w3.org/TR/vibration/)<sup><a href="#v">[5.34.104.5]</a></sup> - Programmatically control a device's vibration mechanism.
* [Web Notifications](http://notifications.spec.whatwg.org/)<sup><a href="#v">[5.34.93.0]</a></sup> - Use the device's native notification mechanism (e.g. status bar on Android) to display messages to the user.

# Experimental APIs

In addition to the standard APIs, Crosswalk provides additional experimental or emerging standards APIs to further support building a native application experience using web platform technologies.

* [Device Capabilities](http://www.w3.org/2012/sysapps/device-capabilities/) - Retrieve information about the underlaying system.
* [Launch Screen](/documentation/manifest/launch_screen.html)<sup><a href="#v">[6.35.131.4]</a></sup> - Display a static user interface on application launch and hide it when the application is ready.
* [Presentation API](http://webscreens.github.io/presentation-api/)<sup>[\[a\]](#a)</sup> - Access external displays from within web applications. Get started with this tutorial: [Presentation API Tutorial](/documentation/apis/presentation_api_tutorial.html). For more information see the [developer documentation](https://github.com/crosswalk-project/crosswalk-website/wiki/Presentation-api-manual).
* [Raw Sockets](http://www.w3.org/TR/raw-sockets/) - Raw TCP and UDP sockets for client and server sides.
* [SIMD](https://github.com/johnmccutchan/ecmascript_simd)<sup><a href="#v">[5.34.104.0]</a></sup> - Data types and operations for access to the Single Instruction Multiple Data (SIMD) instruction sets available on common CPU architectures, such as SSE (IA32/X64) and NEON (ARMv7. This implemention is not yet complete).  Whitepaper: [SIMD in Javascript via C++ and Emscripten](https://www.google.com/url?q=https%3A%2F%2Fdocs.google.com%2Fviewer%3Fa%3Dv%26pid%3Dsites%26srcid%3DZGVmYXVsdGRvbWFpbnx3cG12cDIwMTV8Z3g6NTkzYWE2OGNlNDAyMTRjOQ).
* [WebCL](https://www.khronos.org/registry/webcl/specs/1.0.0/)<sup>[\[a\]](#a)</sup><sup><a href="#v">[13.41.304.0]</a></sup> - JavaScript binding to the Khronos OpenCL standard. It enables web applications to access harness GPU and multi-core CPU for parallel processing.

# Tizen Extension APIs

In addition to the experimental Crosswalk APIs, the Crosswalk developers have created
a Crosswalk extension for Tizen users which provides many of the Tizen
APIs. You can find information on those APIs on the [Tizen Extension APIs](https://github.com/crosswalk-project/tizen-extensions-crosswalk/wiki/APIs) page.

<small>Some content in the Standard APIs section of this page has been adapted from [chromestatus.com](http://www.chromestatus.com/), &copy; 2013 The Chromium Authors, used under [Creative Commons Attribution 2.5 license](http://creativecommons.org/licenses/by/2.5/)</small>
