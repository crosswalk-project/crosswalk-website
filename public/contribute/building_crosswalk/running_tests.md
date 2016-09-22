# Running Crosswalk tests

If you are making changes to Crosswalk in addition to building it yourself, you
should probably run Crosswalk's tests to make sure your changes do not
introduce any regressions.

This page assumes you have already built Crosswalk by following the
platform-specific instructions on the other pages.

## Simple test for Linux and Windows

If you just want to find out if your `xwalk` or (`xwalk.exe`) binary is working
as expected, you can simply pass it a manifest:

```
# Linux
cd /path/to/crosswalk-checkout/src/YOUR-BUILD-DIR
./xwalk /path/to/manifest.json

# Windows
cd C:\path\to\crosswalk-checkout\src\YOUR-BUILD-DIR
xwalk.exe C:\path\to\manifest.json
```

Crosswalk will parse the manifest and launch the application from the entry
point specified in `start_url`.

If you don't have any HTML applications to test, the
[Crosswalk samples](/documentation/samples.html) includes a few you can try.

## Build all available tests

The easiest way to build all Crosswalk tests, which are different and have
different names depending on the platform, is to build the `xwalk_builder`
target. This work for on all supported platforms (Android, Windows and Linux).

```
cd /path/to/crosswalk-checkout/src
ninja -C YOUR-BUILD-DIR xwalk_builder
```

## Linux and Windows

On Linux and Windows, building the `xwalk_builder` target generates the
following executables in `YOUR-BUILD-DIR` (remember they end in `.exe` on
Windows):

* `xwalk_browsertest`
* `xwalk_extensions_browsertest`
* `xwalk_extensions_unittest`
* `xwalk_sysapps_browsertest`
* `xwalk_sysapps_unittest`
* `xwalk_unittest`

Those are also target names, so if you only want to build a subset of the tests
you can pass one of those names to ninja instead of `xwalk_builder`.

## Android

At the moment, Crosswalk for Android is tested using device tests which are run
directly on an Android device or emulator. These are known as _instrumentation
tests_.

The `xwalk_builder` target is actually building the following targets (and
respective APKs):

* `xwalk_core_internal_shell_apk`
* `xwalk_core_internal_test_apk`
* `xwalk_core_shell_apk`
* `xwalk_core_test_apk`
* `xwalk_runtime_client_embedded_shell_apk`
* `xwalk_runtime_client_embedded_test_apk`
* `xwalk_runtime_client_shell_apk`
* `xwalk_runtime_client_test_apk`

Those tests all come in pairs, and can be invoked like this:

```
cd /path/to/crosswalk-checkout/src
python build/android/test_runner.py instrumentation \
       --apk-under-test BUILD-DIR/apks/XWalkCoreShell.apk \
       --output-directory BUILD-DIR \
       --test-apk BUILD-DIR/apks/XWalkCoreTest.apk \
       --test-data xwview:xwalk/test/android/data/device_files/
```

Chromium's Android testing infrastructure is very flexible (but also quite
complex). Please take some time to
[read its documentation](https://chromium.googlesource.com/chromium/src/+/master/docs/android_test_instructions.md),
which covers, for example, how to run only a subset of all tests or how to
avoid some common setup issues with your device.
