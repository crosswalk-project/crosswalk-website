# Standard APIs

The following standard APIs are supported by Crosswalk on both Android and Tizen since [Crosswalk 1.29.4.2 - Beta Build (release 1)] (wiki/Crosswalk-1-release-notes), unless noted otherwise (<sup id="t">[t]</sup> = Tizen only; <sup id="a">[a]</sup> = Android only).

## Runtime & Packaging
* [app: URI] (http://www.w3.org/2012/sysapps/app-uri/)<sup><a href="#t">[t]</a></sup> - Address resources inside a packaged application.

## Multimedia & Graphics
* [CSS Animations] (http://www.w3.org/TR/css3-animations/) - Animate CSS properties over time using keyframes.
* [CSS Backgrounds and Borders Level 3] (http://www.w3.org/TR/css3-background/) - CSS features for borders and backgrounds.
* [CSS Color Module Level 3] (http://www.w3.org/TR/css3-color/) - CSS features for color values and properties.
* [CSS Flexible Box Layout ] (http://www.w3.org/TR/css3-flexbox/) - A CSS box model for user interface design.
* [CSS Fonts Level 3] (http://www.w3.org/TR/css3-webfonts/) - Font properties and dynamic font resource loading.
* [CSS Multi-column Layout] (http://www.w3.org/TR/css3-multicol/) - Multi-column layouts with CSS.
* [CSS Text Decoration Level 3] (http://www.w3.org/TR/css-text-decor-3/) - Text decoration CSS features.
* [CSS Transforms] (http://www.w3.org/TR/css3-transforms/) - Change the position of content in 3D space without disrupting the normal flow using CSS.
* [CSS Transitions] (http://www.w3.org/TR/css3-transitions/) - Enables property changes in CSS values over a duration of time.
* [HTML Canvas 2D Context] (http://www.w3.org/TR/2dcontext/) - An API for 2D immediate mode graphics.
* [Media Queries Level 3] (http://w3c-test.org/csswg/mediaqueries3/) - CSS media features for adapting the same content to different output devices and screens.
* [Scalable Vector Graphics (SVG) 1.1] (http://www.w3.org/TR/SVG11/) - An XML markup language for 2D vector graphics.

## Networking & Storage
* [File API] (http://dev.w3.org/2006/webapi/FileAPI/) - Asynchronously read the contents of files or raw data buffers stored on the client.
* [File API: Directories and System] (http://dev.w3.org/2009/dap/file-system/file-dir-sys.html) - Expose a sandboxed filesystem on the client.
* [File API: Writer] (http://dev.w3.org/2009/dap/file-system/file-writer.html) - Write files to a sandboxed sandboxed filesystem on the client.
* [HTML5 Web Messaging] (http://www.w3.org/TR/webmessaging/) - A mechanism for communicating between browsing contexts.
* [Indexed DB] (https://dvcs.w3.org/hg/IndexedDB/raw-file/default/Overview.html) - An asynchronous client-side storage API for fast access to large amounts of structured data.
* [Online State] (http://www.w3.org/html/wg/drafts/html/CR/browsers.html#browser-state) - Online and offline events for the network state. 
* [Web SQL] (http://www.w3.org/TR/webdatabase/) - Store data in databases on the client using a variant of SQL.
* [Web Sockets] (http://www.w3.org/TR/websockets/) - A low overhead bi-directional communication with web servers over a persistent TCP connection.
* [Web Storage] (http://www.w3.org/TR/webstorage/) - A simple synchronous client-side storage API for storing name-value pairs.
* [XMLHttpRequest] (http://www.w3.org/TR/XMLHttpRequest/) - Transfer data between a client and a server programmatically over HTTP.

## Performance & Optimization
* [Navigation Timing] (http://www.w3.org/TR/navigation-timing/) - Access timing information related to navigation and elements.
* [Page Visibility] (http://www.w3.org/TR/page-visibility/) - Programmatically determine the visibility state of the web application.
* [Resource Timing] (http://www.w3.org/TR/resource-timing/) - Access timing information related to HTML elements.
* [Selectors Level 1] (http://www.w3.org/TR/selectors-api/) and [Level 2] (http://www.w3.org/TR/selectors-api2/) - Retrieve elements from the DOM using CSS selectors.
* [Typed Array 1.0] (http://www.khronos.org/registry/typedarray/specs/latest/) - Data types for raw binary data for performance critical tasks.
* [Web Workers] (http://www.w3.org/TR/workers/) - Run scripts in parallel to the main page.

## Device & Hardware
* [Fullscreen] (http://fullscreen.spec.whatwg.org/) - Programmatically instruct an element on the page to be presented in full screen mode.
* [HTML5 Date and Time state of input element] (http://www.w3.org/TR/html5/forms.html#date-and-time-state-(type=datetime))<sup><a href="#a">[a]</a></sup> - Picker controls for date and time input types.
*  [HTML5 Telephone, Email and URL state of input element] (http://www.w3.org/TR/html5/forms.html#telephone-state-(type=tel))<sup><a href="#a">[a]</a></sup> - Picker controls for telephone, email and URL input types.
* [Touch Events] (https://dvcs.w3.org/hg/webevents/raw-file/v1/touchevents.html) - Handle touch events programmatically.

# Experimental APIs
In addition to the standard APIs, Crosswalk provides additional experimental or emerging standards APIs to further support building a native application experience using web platform technologies.

* [Presentation API] (http://webscreens.github.io/presentation-api/) - Access external displays from within web applications. For more information see the [Presentation API developer documentation] (https://github.com/crosswalk-project/crosswalk-website/wiki/Presentation-api-manual). Introduced in [Crosswalk 3.32.49.0] (https://download.01.org/crosswalk/releases/).

<!--

This section is where those APIs will be listed and documented. Until the build system is hooked in to auto generate the API pages, you can view the source for the <a href='https://github.com/crosswalk-project/crosswalk/tree/master/experimental'>experimental Crosswalk APIs</a>.

The APIs linked above are exposed under the **xwalk** namespace. For example, the <a href='https://github.com/crosswalk-project/crosswalk/blob/master/jsapi/runtime.idl'>xwalk.runtime</a> API exposes the function **getAPIVersion**. This can be used as follows:

```javascript
function versionCallback (version) {
  console.log ('Version: ' + version);
}

xwalk.getAPIVersion (versionCallback);
```
-->

# Tizen Extension APIs

In addition to the experimental Crosswalk APIs, the Crosswalk developers have created 
a Crosswalk extension for Tizen users which provides many of the Tizen 
APIs. You can find information on those APIs in [Tizen Extension APIs](https://github.com/crosswalk-project/tizen-extensions-crosswalk/wiki/APIs) list.

<small>Some content in the Standard APIs section of this page has been adapted from [chromestatus.com] (http://www.chromestatus.com/), &copy; 2013 The Chromium Authors, used under [Creative Commons Attribution 2.5 license] (http://creativecommons.org/licenses/by/2.5/)</small>
