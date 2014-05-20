# Adding Cordova plugins

Cordova's web APIs are packaged as **plugins**, optional add-ons for the Cordova runtime. Each plugin you add provides extra functionality and/or additional web API methods for the Cordova-wrapped web application. For example, the [Cordova Device Motion Plugin](http://plugins.cordova.io/#/package/org.apache.cordova.device-motion) plugin adds the following JavaScript methods for working with accelerometer data:

*   navigator.accelerometer.getCurrentAcceleration
*   navigator.accelerometer.watchAcceleration
*   navigator.accelerometer.clearWatch

The [Cordova Plugin Registry](http://plugins.cordova.io/) lists many of the available plugins (including all of the official ones) and where to get them.

## Cordova plugins and Crosswalk

Cordova Crosswalk for Android can also be extended with Cordova plugins. However, the plugin version you need depends on the Crosswalk version you are using. The [plugins compatibility list](#wiki/Crosswalk-Cordova-for-Android:-plugin-compatibility-lists) shows which Cordova plugin versions are supported by which version of Crosswalk Cordova for Android.

It's also worth noting that some functionality provided by Cordova plugins already has an equivalent in Crosswalk (for example, geolocation data). In cases where you are only using Crosswalk for the webview (e.g. you're only deploying to Android), you could use such Crosswalk native APIs, rather than those provided by Cordova plugins.

## Adding a plugin to a project

**If you aren't set up for Crosswalk Cordova development, see the [host setup instructions](#documentation/cordova/develop_an_application/set_up_the_host). You can also follow those instructions to create a Crosswalk Cordova HelloWorld project. Plugins are added to a HelloWorld sample application in the instructions below, but the same steps will work for any Crosswalk Cordova application.**

In addition to the standard Crosswalk Cordova pre-requisites, you will also need to install [node.js](http://nodejs.org) for your platform.

The Cordova tool for installing plugins is `plugman`. Install it by running:

    $ npm install -g plugman

Once installed, add a plugin to your Crosswalk Cordova project as follows:

1.  Go to the top-level project directory:

        cd HelloWorld

2.  Find the URL for the plugin you want to install, by looking it up in the appropriate [plugins compatibility list](#wiki/Crosswalk-Cordova-for-Android:-plugin-compatibility-lists). For example, if you've using version 5 of Crosswalk, the [compatible plugins for Cordova 3.3.0 list](#wiki/Plugins-list-@-3.3.0-supported-by-crosswalk-cordova-android) is the one you need.

    For example, to install the accelerometer plugin for Cordova 3.3.0, the URL you need is: https://git-wip-us.apache.org/repos/asf/cordova-plugin-device-motion.git#r0.2.4.

3.  Add the plugin to the project:

    $ plugman install --platform android --project . \
        --plugin https://git-wip-us.apache.org/repos/asf/cordova-plugin-device-motion.git#r0.2.4

    Please refer to [Using Plugman to Manage Plugins](http://cordova.apache.org/docs/en/3.3.0/plugin_ref_plugman.md.html) for more details about the `plugman` command line options.

4.  Use the plugin in the application. As a simple example, you could modify the HelloWorld project to use the plugin as follows:

    In `assets/www/index.html`, modify the `<div class="app">` element, adding some paragraphs to display the x, y and z accelerometer coordinates:

        <div class="app">
            <h1>Apache Cordova</h1>
            <div id="deviceready" class="blink">
                <p class="event listening">Connecting to Device</p>
                <p class="event received">Device is Ready</p>
            </div>

            <!-- display x,y,z accelerometer coordinates -->
            <p style="color: white;">x: <span id="x">0</span></p>
            <p style="color: white;">y: <span id="y">0</span></p>
            <p style="color: white;">z: <span id="z">0</span></p>
        </div>


    In `assets/www/js/index.js`, modify the `onDeviceReady()` function to watch the accelerometer:

        onDeviceReady: function() {
          app.receivedEvent('deviceready');

          // listen to accelerometer events every 100ms
          navigator.accelerometer.watchAcceleration(
            // success handler
            function (evt) {
              document.getElementById('x').innerHTML = evt.x;
              document.getElementById('y').innerHTML = evt.y;
              document.getElementById('z').innerHTML = evt.z;
            },

            // error handler
            function (e) {
              alert("accel fail (" + e.name + ": " + e.message + ")");
            },

            // options: update every 100ms
            { frequency: 100 }
          );
        },

5.  Install and run the application as per usual:

        $ cd HelloWorld
        $ ./cordova/run android

    The result should look like this:

    <img src="assets/cordova-hello-world-with-accelerometer.png">

As with standard Cordova, you should still follow the Cordova guidelines for integrating plugins with your application (e.g. [waiting for the "device ready" event before using plugin features](http://cordova.apache.org/docs/en/3.3.0/cordova_events_events.md.html#deviceready)).

##  Additional external plugins

External plugins (i.e. not part of the main Cordova distribution) can also be used with Crosswalk Cordova for Android:

* [Use AdMob plugin in Crosswalk](#wiki/AdMob-Plugin-on-Crosswalk)
* [Use IAP plugin in Crosswalk ](#wiki/IAP-Plugin-on-Crosswalk)
