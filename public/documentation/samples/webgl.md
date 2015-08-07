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
> cd <xwalk_app_template directory>
> python make_apk.py --package=org.crosswalkproject.example \
    --manifest=<path to crosswalk-samples>/webgl/manifest.json
```

`<xwalk_app_template directory>` refers to the directory where you
downloaded and unpacked Crosswalk Android.

Then install the apk file on Android:

```sh
> adb install WebGL*.apk
```

## WebGL on Tizen

Follow the steps in [Run on Tizen](/documentation/tizen/run_on_tizen.html) to install and run the WebGL sample on Tizen.

Or here's the quick version:

```sh
sdb push <path to crosswalk-samples>/webgl /home/developer/webgl
sdb shell "xwalk --usegl=osmesa /home/developer/webgl/"
```

Note that you pass the `--usegl=osmesa` option to enable WebGL on
Tizen Crosswalk.
