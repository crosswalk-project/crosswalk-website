# Hello World sample

<img class='sample-thumb' src='/assets/sampapp-icon-helloworld.png'>

The smallest of applications -- Hello, World! This sample provides a manifest.json
and a minimal set of HTML files to start an application from the ground up.

This application is part of the
[Crosswalk samples](https://github.com/crosswalk-project/crosswalk-samples).

The steps for setting up an environment for Crosswalk are explained
in detail in the [Getting started](/documentation/getting_started.html)
tutorial.

## Hello World on Android

Once you have set up the Crosswalk Android packaging dependencies,
follow the steps in [Run on Android](/documentation/android/run_on_android.html).

The quick version is that you can build the Hello World apk with:

```sh
> cd <crosswalk directory>
> python make_apk.py --package=org.crosswalkproject.helloworld \
    --manifest=<path to crosswalk-samples>/hello-world/manifest.json
```

`<crosswalk directory>` refers to the directory where you downloaded and
unpacked Crosswalk Android.

Then install the apk file on Android:

```sh
> adb install Helloworld*.apk
```

