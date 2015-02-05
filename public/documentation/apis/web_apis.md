# Web APIs

The Crosswalk Project supports most HTML5 and CSS3-compliant API standards from the W3C.   The following are supported by Crosswalk Project on both Android and Tizen since [Crosswalk 3.32.53.2](https://github.com/crosswalk-project/crosswalk-website/wiki/Crosswalk-3-release-notes).

<div>_Version Key_:<br />
&nbsp;&nbsp;&nbsp;<sup id="t">[t]</sup> = Tizen only;<br/>
&nbsp;&nbsp;&nbsp;<sup id="a">[a]</sup> = Android only<br/>
&nbsp;&nbsp;&nbsp;<sup id="v">[X.X.X.X]</sup> = this Crosswalk version or later<br/>
&nbsp;&nbsp;&nbsp;<sup id="va">[X.X.X.X;x86|ARM]</sup> = this version or later for the specified architecture.</div>


## Runtime &amp; Packaging
* [app: URI](http://www.w3.org/2012/sysapps/app-uri/)<sup><a href="#t">[t]</a></sup> - URL schema can be used by packaged applications to obtain resources  in a container. These resources can then be used with web platform features that accept URLs. 
	* example:  zip file within a container


## Multimedia &amp; Graphics

_CSS Properties_

* [Animations](http://www.w3.org/TR/css3-animations/) - Animate CSS properties over time using keyframes.
* [Backgrounds and Borders Level 3](http://www.w3.org/TR/css3-background/) - CSS features for borders and backgrounds.
* [Color Module Level 3](http://www.w3.org/TR/css3-color/) - CSS features for color values and properties.
* [Flexible Box Layout ](http://www.w3.org/TR/css3-flexbox/) - A CSS box model for user interface design.
* [Fonts Level 3](http://www.w3.org/TR/css3-webfonts/) - Font properties and dynamic font resource loading.
* [Multi-column Layout](http://www.w3.org/TR/css3-multicol/) - Multi-column layouts with CSS.
* [Text Decoration Level 3](http://www.w3.org/TR/css-text-decor-3/) - Text decoration CSS features.
* [Transforms](http://www.w3.org/TR/css3-transforms/) - Change the position of content in 3D space without disrupting the normal flow using CSS.
* [Transitions](http://www.w3.org/TR/css3-transitions/) - Enables property changes in CSS values over time.

_Canvas_ 

* [HTML Canvas 2D Context](http://www.w3.org/TR/2dcontext/) - An API for 2D immediate mode graphics.
	* objects, methods, and properties to draw and manipulate graphics on a canvas drawing surface.

_HTML 5 &amp; Media Support_

* [HTML5](http://www.w3.org/TR/html5/) - Interactive media support without plugins
	* [Audio](http://www.w3.org/TR/html5/embedded-content-0.html#the-audio-element) - embedded audio support
	* [Video](http://www.w3.org/TR/html5/embedded-content-0.html#the-video-element) - embedded video support

_Other / Miscellaneous_ 

* [Media Queries Level 3](http://www.w3.org/TR/css3-mediaqueries/) - @media CSS queries that can adapt the same content to different output devices and screens.
<!-- (Bob: Waiting for official support)* [Responsive Images](http://picture.responsiveimages.org/)<sup><a href="#v">[5.34.104.5]</a></sup> - Control which image resource is presented to a user, based on media query and/or image format support. -->
* [Scalable Vector Graphics (SVG) 1.1](http://www.w3.org/TR/SVG11/) - An XML markup language for 2D vector graphics.
* [WebRTC](http://www.w3.org/TR/webrtc/)<sup><a href="#a">[a]</a></sup> <sup><a href="#va">[7.36.154.6;x86]</a></sup> - Peer to peer sharing of video and audio streams.


## File-Handling &amp; Client-Server

_File Handling_

* [File API](http://dev.w3.org/2006/webapi/FileAPI/) - Asynchronously read the contents of files or raw data buffers stored on the client.
* [File API: Directories and System](http://dev.w3.org/2009/dap/file-system/file-dir-sys.html) - Expose a sandboxed filesystem on the client.
* [File API: Writer](http://dev.w3.org/2009/dap/file-system/file-writer.html) - Write files to a sandboxed sandboxed filesystem on the client.

_Client / Server_ 

* [Indexed DB](https://dvcs.w3.org/hg/IndexedDB/raw-file/default/Overview.html) - An asynchronous client-side storage API for fast access to large amounts of structured data.
* [Online State](http://www.w3.org/html/wg/drafts/html/CR/browsers.html#browser-state) - Online and offline events for the network state.
* [Web SQL](http://www.w3.org/TR/webdatabase/) - Store data in databases on the client using a variant of SQL.
* [Web Sockets](http://www.w3.org/TR/websockets/) - A low overhead bi-directional communication with web servers over a persistent TCP connection.
* [Web Storage](http://www.w3.org/TR/webstorage/) - A simple synchronous client-side storage API for storing name-value pairs.
* [XMLHttpRequest](http://www.w3.org/TR/XMLHttpRequest/) - Transfer data between a client and a server programmatically over HTTP.
* [HTML5 Web Messaging](http://www.w3.org/TR/webmessaging/) - A mechanism for communicating between browsing contexts.
	* example:  messages in server-sent events, Web sockets, cross-document messaging, 


## Performance &amp; Optimization
* [Navigation Timing](http://www.w3.org/TR/navigation-timing/) - Access timing information related to navigation and elements.
* [Page Visibility](http://www.w3.org/TR/page-visibility/)<sup><a href="#a">[a]</a></sup> - Programmatically determine the visibility state of the web application.
* [Resource Timing](http://www.w3.org/TR/resource-timing/) - Access timing information related to HTML elements.
* [Selectors Level 1](http://www.w3.org/TR/selectors-api/) and [Level 2](http://www.w3.org/TR/selectors-api2/) - Retrieve elements from the DOM using CSS selectors.
* [Typed Array 1.0](http://www.khronos.org/registry/typedarray/specs/latest/) - Data types for raw binary data for performance-critical tasks.
* [Web Workers](http://www.w3.org/TR/workers/) - Run scripts in parallel to the main page.

## Device &amp; Hardware
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


## Social
* [Contacts](http://www.w3.org/2012/sysapps/contacts-manager-api/)<sup>[\[a\]](#a)</sup> - Enables the management of contact information.
	* [GitHub repo]( https://github.com/sysapps/contacts-manager-api)
* [Messaging](http://www.w3.org/2012/sysapps/messaging/)<sup><a href="#a">[a]</a></sup> - Allows SMS and MMS message sending and receiving.
	* [GitHub repo](https://github.com/sysapps/messaging)