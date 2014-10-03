# Hello World sample

<img class='sample-thumb' src='/assets/sampapp-icon-helloworld.png'>

The smallest of applications -- Hello, World! This sample provides a manifest.json and a minimal set of HTML files to start an application from the ground up.

This application is part of the
[Crosswalk samples download](https://github.com/crosswalk-project/crosswalk-samples/archive/0.2.tar.gz).

The steps for setting up an environment for Crosswalk are explained
in detail in the [Getting started](/documentation/getting_started.html)
tutorial.

## Hello World on Android

Once you've set up the Crosswalk Android packaging dependencies,
follow the steps in [Run on Android](/documentation/getting_started/run_on_android.html).

The quick version is that you can build the Hello World apk with:

```sh
> cd <xwalk_app_template directory>
> python make_apk.py --package=org.crosswalkproject.example \
    --manifest=<path to crosswalk-samples>/hello_world/manifest.json
```

`<xwalk_app_template directory>` refers to the directory where you
downloaded and unpacked Crosswalk Android.

Then install the apk file on Android:

```sh
> adb install HelloWorld*.apk
```

## Hello World on Tizen

Follow the steps in
[Run on Tizen](/documentation/getting_started/run_on_tizen.html)
to install and run the Hello World sample.

Or here's the quick version:

```sh
> sdb push <path to crosswalk-samples>/hello_world /home/developer/hello_world
> sdb shell "xwalk /home/developer/hello_world/"
```
