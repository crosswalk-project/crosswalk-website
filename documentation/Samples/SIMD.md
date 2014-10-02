# SIMD sample

<img class='sample-thumb' src='/assets/sampapp-icon-simd.png'>

This sample shows how to use [SIMD](https://github.com/johnmccutchan/ecmascript_simd)
in a Crosswalk application. In this sample, a Mandelbrot set
is animated at different zoom levels.

The code is based on the Mandelbrot animation demo developed by Intel's
Peter Jensen and Ningxin Hu.

**Note that Crosswalk has native support for SIMD on x86 architecture
since version 5.34.104.0. You will need a version of Crosswalk with
that version or later to be able to run this demo; and you will need
a device with an Intel x86 chipset (emulated or physical).**

This application is part of the
[Crosswalk samples download](https://github.com/crosswalk-project/crosswalk-samples/archive/0.2.tar.gz).

The steps for setting up an environment for Crosswalk are explained
in detail in the [Getting started](/documentation/getting_started)
tutorial.

## SIMD on Android

Once you've set up the Crosswalk Android packaging dependencies,
follow the steps in [Run on Android](/documentation/getting_started/run_on_android)
to install and run the SIMD sample on Android.

The quick version is that you can build the SIMD apk with:

```sh
> cd <xwalk_app_template directory>
> python make_apk.py --package=org.crosswalkproject.example \
    --manifest=<path to crosswalk-samples>/simd/manifest.json
```

`<xwalk_app_template directory>` refers to the directory where you
downloaded and unpacked Crosswalk Android.

Then install the apk file on Android:

```sh
> adb install simd*.apk
```

## SIMD on Tizen

Follow the steps in
[Run on Tizen](/documentation/getting_started/run_on_tizen)
to install and run the SIMD sample on Tizen.

Or here's the quick version:

```sh
sdb push <path to crosswalk-samples>/simd /home/developer/simd
sdb shell "xwalk /home/developer/simd/"
```
