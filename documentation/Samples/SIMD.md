# SIMD sample

<img class='sample-thumb' src='assets/sampapp-icon-simd.png'>

This sample provides shows how to use SIMD in a Crosswalk application.
In this case, SIMD is used to draw an image of a Mandelbrot set. The code
is based on
[this Mandelbrot animation project](https://github.com/PeterJensen/mandelbrot)
by Intel's Peter Jensen and Ningxin Hu.

The steps for setting up Crosswalk for Android and Crosswalk for Tizen
are explained in detail in the [Getting started](#documentation/getting_started)
tutorial.

## SIMD on Android

Once you've set up the Crosswalk Android packaging dependencies,
follow the steps in [Run on Android](#documentation/getting_started/run_on_android)
to install and run the SIMD sample on Android.

The quick version is that you can build the SIMD apk with:

```sh
> cd <xwalk_app_template directory>
> python make_apk.py --manifest=<path to crosswalk-samples>/simd/manifest.json
```

`<xwalk_app_template directory>` refers to the directory where you
downloaded and unpacked Crosswalk Android.

Then install the apk file on Android:

```sh
> adb install simd*.apk
```

## SIMD on Tizen

Follow the steps in
[Run on Tizen](#documentation/getting_started/run_on_tizen)
to install and run the SIMD sample on Tizen.

Or here's the quick version:

```sh
sdb push <path to crosswalk-samples>/simd /home/developer/simd
sdb shell "xwalk /home/developer/simd/"
```
