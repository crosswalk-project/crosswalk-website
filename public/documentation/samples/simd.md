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
[Crosswalk samples](https://github.com/crosswalk-project/crosswalk-samples).

The steps for setting up an environment for Crosswalk are explained
in detail in the [Getting started](/documentation/getting_started.html)
tutorial.

## SIMD on Android

Once you've set up the Crosswalk Android packaging dependencies,
follow the steps in [Run on Android](/documentation/android/run_on_android.html)
to install and run the SIMD sample on Android.

The quick version is that you can build the SIMD apk with:

```cmdline
> <crosswalk-app-tools>/src/crosswalk-pkg --crosswalk=<crosswalk version> \
    --platforms=android <path to crosswalk-samples>/simd
```

`<crosswalk-app-tools>` refers to the directory where you downloaded crosswalk-app-tools.

Then install the apk file on Android:

```cmdline
> adb install org.xwalk.simd*.apk
```
