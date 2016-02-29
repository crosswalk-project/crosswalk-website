# WebVR

This short tutorial describes how to bundle some of the nifty sample applications recently released (Dec 2015) as part of the [A-Frame](https://aframe.io/) project, authored by Mozilla's VR team ([MozVR](http://mozvr.com/)). Your system should already be [set up for Crosswalk development](/documentation/android/system_setup.html).

## Background
Virtual Reality has garnered increased attention in the past year, from [Google Cardboard](https://www.google.com/get/cardboard/) on the low end to [Oculus Rift](https://www.oculus.com/en-us/rift/) or [Moverio glasses/headsets](https://www.epson.com/cgi-bin/Store/jsp/Landing/moverio-augmented-reality-smart-glasses.do) on the high end. The immersive, 3-dimensional visual experience gives consumers the feeling they are in the scene. Given the advances in viewing devices and APIs, the technology, while still in the early stages, promises to shake up how we consume media and interact in the digital world.

VR for web applications has been promoted by [three.js](http://threejs.org/), a JavaScript library for 3D animations and graphics, and a lot of great examples using three.js can be found online. [A-Frame](http://aframe.io) was released in Dec 2015 and simplifies the task of creating and navigating basic 3D objects with just markup. No WebGL experience required. It is a nice way to get your feet wet.

## From A-Frame Samples to Android Applications

If you want to see how these applications will work on your phone, you can open up Chrome on your phone and navigate to http://aframe.io. Click the glasses icon in the bottom corner of any of the samples to split the screen:

<img src="/assets/hello-world-in-chrome.png" style="margin:0 auto;display:block;" />

Using Crosswalk we can convert these samples to Android applications (Kudos to Pablo Mendigochea for his webinar on [How to use Crosswalk with Moverio](https://www.youtube.com/watch?v=Tt-pX1JMt60)).

### Create an app

1. Download the full project source from the [A-Frame github](https://github.com/aframevr/aframe) repo:

   **[aframe-master.zip](https://github.com/aframevr/aframe/archive/master.zip)**
   
   Unzip the file.

2. Pick any sample you wish to build and copy the directory to a new location. For this tutorial, we will build the Composite example:

       > cp -r ./aframe-master/examples/showcase-composite ~/src/

3. Copy required resources
   * Investigate the `index.html` file and note any resources that are not included in the directory. In the Composite index.html we see there are 2 resources pulled from other locations:

     `<script src="../../dist/aframe.js"></script>`<br>
     `<a-sky src="../_skies/lake.jpg"></a-sky>`
   
   * Find these resources in the master tree and copy them to your new folder.

         > cp ./aframe-master/dist/aframe.min.js ~/src/showcase-composite/
         > cp ./aframe-master/examples/_skies/lake.jpg ~/src/showcase-composite/

     (aframe.min.js can also be used online:<br>
	 `<script src="https://aframe.io/releases/latest/aframe.min.js"></script>` )
	 
   * Update the index.html to reference the local versions.  In our case:

     `<script src="aframe.min.js"></script>`<br>
     `<a-sky src="lake.jpg"></a-sky>`
   
4. Copy an icon to your project folder. Name it "icon.png" or whatever you call it in the manifest.json file (next step). Here is an icon you can use:

   <img src="/assets/cw-app-icon.png" style="width:128px; margin:0 auto;display:block;" />

5. Create a manifest.json file for the project. In our example:

       {
        "name": "A-Frame Composite",
        "start_url": "index.html",
        "xwalk_app_version": "0.1",
        "xwalk_package_id": "com.aframe.composite",
        "icons": [
          {
           "src": "icon.png",
           "sizes": "72x72"
          }
        ]
       }

6. Build the apk and install

	   > crosswalk-pkg ./showcase-composite/
       > adb install -r <apk for your architecture>

7. On your device, run the program. Below is what the Composite application looks like. As you move your phone around, the accelerometer will adjust the view. Drop your phone into Google Cardboard and take a trip to beautiful Portland!

   <img src="/assets/aframe-composite.jpg" style="margin:0 auto;display:block;" />

## All-in-one

If you would like to try all the applications on your phone without individually building each one, we created an "All-in-one" application:

<img src="/assets/aframe-allinone.jpg" style="width: 50%; margin:0 auto;display:block;" />

1. Download [aframe-allinone.zip](/assets/aframe-allinone.zip). Unzip it.

2. Copy the contents into the aframe-master directory. This adds a manifests.json, icon, and top-level index.html to launch all example applications:

       > cp -r ./aframe-allinone/* ./aframe-master

3. Build and install

       > crosswalk-pkg ./aframe-master
	   > adb install -r <apk for your architecture>
	   



