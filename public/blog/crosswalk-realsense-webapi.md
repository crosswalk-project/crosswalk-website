Intel® [RealSense](https://software.intel.com/en-us/realsense/home)™ technology redefines how we interact with our devices for a more natural, intuitive and immersive experience. By introducing 3D cameras with depth information combined with regular RGB data, new use cases are possible such as augmented reality, hand/face recognition, object tracking, and more. The **Crosswalk Project Extensions for RealSense** now brings these amazing capabilities to web developers.

Our first public release (part of [Crosswalk v18.6.0](https://github.com/crosswalk-project/realsense-extensions-crosswalk/releases/tag/v18.6.0)) includes the following:

* Depth Enhanced Photography ([spec](http://crosswalk-project.github.io/realsense-extensions-crosswalk/spec/depth-enabled-photography.html))
* Scene Perception ([spec](http://crosswalk-project.github.io/realsense-extensions-crosswalk/spec/scene-perception.html))
* Face Tracking and Recognition ([spec](http://crosswalk-project.github.io/realsense-extensions-crosswalk/spec/face.html))

Developers can create 3D-aware web applications that can be run on any Windows tablet with a built-in [R200 camera](https://software.intel.com/en-us/RealSense/R200Camera), such as the [HP Spectre x2](http://store.hp.com/us/en/ContentView?storeId=10151&langId=-1&catalogId=10051&eSpotName=new-detachable), or a Windows PC with peripheral R200 camera. 

##Getting Started
Follow the [quick start page](https://github.com/crosswalk-project/realsense-extensions-crosswalk/wiki/Usage-of-RealSense-Extensions) to build your Realsense-powered web application. You can also download the [sample](https://github.com/crosswalk-project/realsense-extensions-crosswalk/releases/download/v18.6.0/rs_sample_v18.6.0.0_signed.zip) for a quick glance.

##Get involved
We invite you to contribute. This project is under the Crosswalk Project umbrella. The APIs are developed as external Crosswalk extensions, and the source code is hosted at http://github.com/crosswalk-project/realsense-extensions-crosswalk. Please share any ideas you have for discussion on our mailing list: [crosswalk-dev](https://lists.crosswalk-project.org/mailman/listinfo/crosswalk-dev) and [crosswalk-help](https://lists.crosswalk-project.org/mailman/listinfo/crosswalk-help). Follow the [instructions](https://github.com/crosswalk-project/realsense-extensions-crosswalk/wiki/Build-Instructions-for-Windows) to build the project.  Contribute your changes following regular github pull requests.

The Crosswalk Project bug tracking system is: https://crosswalk-project.org/jira/. For the RealSense API bugs, go to the [*Extensions for RealSense*](https://crosswalk-project.org/jira/browse/XWALK/component/11000/?selectedTab=com.atlassian.jira.jira-projects-plugin:component-summary-panel) component.

##FAQ
####Is Windows the only platform supported? How about other platforms like Android?<br/>
Currently Windows is the only platform shipping with R200 cameras. Support for Android will be considered once hardware is available and shipping with this capability.

####RealSense SDK has a lot of native APIs.  Why are the web APIs limited to DEP, SP and Face?<br/>
The team is working hard to add support for more capabilities. Please check the API dashboard [page](https://github.com/crosswalk-project/realsense-extensions-crosswalk/wiki/API-Dashboard).

## Feature Examples
The screenshots below show a few of the features that are available with RealSense and the Web API.

<figure>
<a href="/assets/realsense/dep.png"><img src="/assets/realsense/dep.png"></a>
<figcaption>Depth Enhanced Photography (DEP): Captures photo depth and provides various amazing effects on the photo, such as refocus, segmentation, and motion. </figcaption>
</figure>

<a href="/assets/realsense/sp.png"><img src="/assets/realsense/sp.png"></a>
<figcaption>Scene Perception (SP): Creates a digital representation of the observed environment and estimates in real-time the camera pose.</figcaption>

<a href="/assets/realsense/face.png"><img src="/assets/realsense/face.png"></a>
<figcaption>Face Tracking and Recognition (Face): Tracks faces and does face recognition in real-time.</figcaption>



