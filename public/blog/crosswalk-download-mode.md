Starting with Crosswalk 17 beta, you can package your application in **download mode**. 

Download Mode provides another way to shrink the size of your APK and is similar to shared mode. Your application is still bundled with the `xwalk_shared_library` which contains only the API layer of Crosswalk.  When the application is first run on the client device, the Crosswalk core libraries and resources are downloaded in the background from a server that you specify. Compared to shared mode, the Crosswalk runtime is exclusive to your application and therefore you control the runtime lifecycle.  The Crosswalk runtime can be downloaded silently in the background without user interaction.

Details on creating applications using Download Mode are here: [Download Mode](/documentation/download_mode.html) 
