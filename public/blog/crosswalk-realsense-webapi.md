Intel® [RealSense](https://software.intel.com/en-us/realsense/home)™ technology redefines how we interact with our devices for a more natural, intuitive and immersive experience. By introducing 3D cameras with depth information combined with regular RGB data, new use cases are possible such as augmented reality, hand/face recognition, object tracking, and etc. The Crosswalk Project Extensions for RealSense now brings these amazing capabilities to web developers.

The first public release of Crosswalk [v18.6.0](https://github.com/crosswalk-project/realsense-extensions-crosswalk/releases/tag/v18.6.0) includes Depth Enhanced Photography ([spec](http://crosswalk-project.github.io/realsense-extensions-crosswalk/spec/depth-enabled-photography.html)), Scene Perception ([spec](http://crosswalk-project.github.io/realsense-extensions-crosswalk/spec/scene-perception.html)) and Face Tracking and Recognition ([spec](http://crosswalk-project.github.io/realsense-extensions-crosswalk/spec/face.html)). Developers can create 3D-aware web applications that can be run on any Windows tablet with a built-in [R200 camera](https://software.intel.com/en-us/RealSense/R200Camera), such as the [HP Spectre x2](http://store.hp.com/us/en/ContentView?storeId=10151&langId=-1&catalogId=10051&eSpotName=new-detachable), or a Windows PC with peripheral R200 camera. Please check below screenshots to get better understanding what those APIs can do.

<figure>
![](/assets/screenshots/realsense/dep.png)
<figcaption>Depth Enhanced Photography (DEP): Captures depth photo and provides various amazing effects on the photo, like Refocus, Segmentation, Motion, etc. </figcaption>
</figure><br/>

<figure>
![](/assets/screenshots/realsense/sp.png)
<figcaption>Scene Perception (SP): Creates a digital representation of the observed environment and estimates in real-time the camera pose.</figcaption>
</figure><br/>

<figure>
![](/assets/screenshots/realsense/face.png)
<figcaption>Face Tracking and Recognition (Face): Tracks faces and does face recognition in real-time.</figcaption>
</figure><br/>

###Try it!
Please follow the [quick start page](https://github.com/crosswalk-project/realsense-extensions-crosswalk/wiki/Usage-of-RealSense-Extensions) to build your Realsense powered web application. Or you could download the [sample](https://github.com/crosswalk-project/realsense-extensions-crosswalk/releases/download/v18.6.0/rs_sample_v18.6.0.0_signed.zip) to have an instant glance.

###Get involved
We invite you to contribute.  This project is under the Crosswalk Project umbrella. The APIs are developed as external Crosswalk extensions, and the source code hosted at http://github.com/crosswalk-project/realsense-extensions-crosswalk. Please share any ideas you have for discussion on our mailing list ([crosswalk-help](https://lists.crosswalk-project.org/mailman/listinfo/crosswalk-help) and [crosswalk-dev](https://lists.crosswalk-project.org/mailman/listinfo/crosswalk-dev)). Follow the [instructions](https://github.com/crosswalk-project/realsense-extensions-crosswalk/wiki/Build-Instructions-for-Windows) to build the project.  Contribute your changes following regular github pull requests.

The bug tracking system is https://crosswalk-project.org/jira/ with component *Extensions for RealSense*.

###FAQ
* Q: Is Windows the only platform supported? How about other platforms like Android?<br/>
  A: Currently Windows is the only platform shipping with R200 cameras, support for Android coming at a later time.

* Q: RealSense SDK has a lot of native APIs.  Why are the web APIs limited to DEP, SP and Face?<br/>
  A: The team is working hard to add support for more capabilities, please check the API dashboard [page](https://github.com/crosswalk-project/realsense-extensions-crosswalk/wiki/API-Dashboard).
