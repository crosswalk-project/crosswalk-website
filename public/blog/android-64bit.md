Android devices with 64-bit CPUs, such as the Nexus 9 and the Lenovo P90 are increasingly entering the market. To fully support this new generation, builds of the Crosswalk for Android binaries are now available in our canary and beta channels and the Google Play Store. When using the 64-bit version of the runtime, Crosswalk applications can take full advantage of the modern CPUs found in the latest mobile devices and Android versions.

## Why should you care?

Depending on their functionality, applications optimized for 64-bit architectures can run slightly faster on 64-bit CPUs than their 32-bit counterparts. But aside from the performance benefit, there are cases where using 64-bit Crosswalk is mandatory for your application to work: for example, if your application is using any other library that is optimized for 64-bit, using the 32-bit version of Crosswalk will cause the build to fail.

## How to create 64-bit versions of Crosswalk applications

By default, Crosswalk applications are packaged for 32-bit architectures. It's possible to specify 64-bit targets for both ARM and x86 using the `-t <target-archs>` option in the `crosswalk-pkg` command. For example, use the command below to build both ARM and x86 64-bit packages:

```
crosswalk-pkg -c beta -t 64 /path/to/app
```

At the moment you need to specify either the Beta or Canary channel because 64-bit support is not yet available from the stable releases.

If you are using Crosswalk in shared mode, your application will automatically download the 64-bit optimized version of Crosswalk from the Play Store and no special action is required when packaging.

Note: support for 64-bit is not yet available in the Crosswalk Cordova Webview plugin and will be enabled soon.

## Uploading 64-bit apps to the Play Store

If you are bundling Crosswalk with your application, the Crosswalk App Tool will build architecture specific packages for ARM64 and for x86_64 with correct version code. You can upload these to the play store and they will be automatically downloaded by compatible devices.

For more information, see the [documentation](/documentation/android/android_64bit).