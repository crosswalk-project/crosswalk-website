# WebGL sample

<img class='sample-thumb' src='/assets/sampapp-icon-webgl.png'>

Ah, the power of WebGL. This sample provides a quick example of integrating ThreeJS into a Crosswalk application.

This application is part of the
[Crosswalk samples](https://github.com/crosswalk-project/crosswalk-samples).

The steps for setting up an environment for Crosswalk are explained
in detail in the [Getting started](/documentation/getting_started.html)
tutorial.

## WebGL on Android

Once you've set up the Crosswalk Android packaging dependencies,
follow the steps in [Run on Android](/documentation/android/run_on_android.html)
to install and run the WebGL sample on Android.

The quick version is that you can build the WebGL apk with:

```sh
> <crosswalk-app-tools>/src/crosswalk-pkg --crosswalk=<crosswalk version> \
    --platforms=android  <path to crosswalk-samples>/webgl
```

`<crosswalk-app-tools>` refers to the directory where you downloaded crosswalk-app-tools.

Then install the apk file on Android:

```sh
> adb install org.xwalk.webgl*.apk
```

