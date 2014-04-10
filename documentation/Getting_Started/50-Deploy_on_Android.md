# Deploying a Crosswalk app on Android

A Crosswalk app on Android can be deployed to app store right after above several steps. The deployment mode varies for the two packaging modes (aka embedded and shared modes) provided by Crosswalk.

## Deploying a Crosswalk app in embedded mode

For embedded mode, each web app is bundled with the full Crosswalk runtime. Since there is an architecture-dependent native library of Crosswalk runtime, two Android apks will be generated for each web app for IA and ARM achitectures respectively. To make the Crosswalk app running on more Android devices, developers are recommended to upload both IA and ARM apks to app store. Google play store already supports multiple apks for each app.

So for each web app packaged in embedded mode, developers are suggested to

* Upload the Crosswalk IA-based app apk to app store.
* Upload the Crosswalk ARM-based app apk to app store.

## Deploying a Crosswalk app in shared mode

For shared mode, a Crosswalk app is bundled with a thin layer of Java code which is architecture independent. Crosswalk runtime is built as one standalone Android apk and could be shared by multiple Crosswalk apps though developers still have to upload two apks for both IA and ARM to app store. Once a Crosswalk app is installed and run on a device, it requires users to install Crosswalk runtime apk if it does not exist. It is more valuable once more than one Crosswalk app from developers are deployed to application store.

So for each web app packaged in shared mode, developers are suggested to

* Upload the Crosswalk app apk to app store.
* Upload the Crosswalk runtime apk for IA architecture to app store. This is not needed if it is done for previous apps.
* Upload the Crosswalk runtime apk for ARM architecture to app store. This is not needed if it is done for previous apps.
