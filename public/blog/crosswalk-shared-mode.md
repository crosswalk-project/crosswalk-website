Starting wiht Crosswalk 14, you can package your application in “shared mode”. This means that your application’s APK won’t include the Crosswalk runtime, which will be downloaded from the Play Store when the application is first started. From that point on, the Crosswalk runtime will be shared with any other Crosswalk application that is also packaged in shared mode. This has a few pros and cons:

## Pros

* Produces a significant smaller APK file size for Crosswalk applications. For example if packaging a simple HelloWorld web application, the APK file size is 20MB for ARM and 23MB for x86. If the same contents is packaged in shared mode, the APK file size will shrink to 68KB
* Applications built in shared mode are architecture independent. Only one package needs to be submitted to the Play Store
* Crosswalk is updated independently of the application. Taking advantage of the latest improvements doesn't require an update of the application
* Web applications that use Crosswalk embedded which are downloaded to the wrong architecture will now automatically download the correct Crosswalk runtime for the architecture and use it in shared mode. ([Read more](/documentation/shared_mode.html#Architecture-Independence))

## Cons

* If the Crosswalk runtime library is not installed, the user will have to download the APK of the runtime library in the first run. The normal startup process of the application will be interrupted.
* While we strive to maintain backwards compatibility, an updated Crosswalk runtime may be introduce incompatibility with the previous version. Developers need to test their applications against the latest Crosswalk release.

Follow [these instructions](/documentation/shared_mode.html) to package your application in shared mode.
