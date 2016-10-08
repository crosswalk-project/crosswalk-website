# Android build instructions

## Requirements

Crosswalk for Android, just like Chromium for Android, is only expected to be
built on a 64-bit Linux installation. It is possible that it also works on OS
X, but it is not supported by either the Crosswalk Project or the Chromium
project.

These instructions assume you have already downloaded `depot_tools` and that it
is in your `PATH` environment variable. See the
[prerequisites](prerequisites.html) page for further information.

## Crosswalk 22 and earlier: gyp setup

Up to Crosswalk 22, both Chromium and Crosswalk used gyp as a meta-build system
that generated the actual build system files used by ninja.

gyp accepts flags to change its behavior, and you **must** set some of those
flags in order to create an Android build.

There are two ways to define those flags:

1. Create a file called `chromium.gyp_env` on the directory where you will
   later call `gclient config` and `gclient sync` (so the one above `src/`).
   It needs to look like this:

    ```
    {
      'GYP_DEFINES': 'key1=value1 key2=value2',
    }
    ```

1. Using `export` in your shell to set the `GYP_DEFINES` environment variable.

The very least you need to add to your `GYP_DEFINES` is `OS=android`. The
default build will target 32-bit ARM; if you want to target a different
architecture, you must also set the `target_arch` variable to `ia32`, `x64` or
`arm64`. For example, if you are using `chromium.gyp_env`, it should look like
this to create an Android build for 64-bit Intel processors.

```
{
  'GYP_DEFINES': 'OS=android target_arch=x64',
}
```

Note that at the moment it is **not** possible to target multiple architectures
in a single build: if you want to build for both ARM and x86, you need to have
two separate builds.

## Fetching the source code

1. Before calling `gclient sync` below, you have to set the `XWALK_OS_ANDROID`
   environment variable so that all Android-specific git repositories are
   fetched before the build.

    ```
    export XWALK_OS_ANDROID=1
    ```

1. Choose where you want to download and build Crosswalk. If you are using gyp
   and opted for having a `chromium.gyp_env` file, this directory must contain
   that file.

   ```
   cd /path/to/crosswalk-checkout
   ```

1. Generate a `.gclient` file. This tells `gclient` where to fetch Crosswalk
   from. It assumes `depot_tools` is in your `PATH` variable.

    ```
    gclient config --name src/xwalk https://github.com/crosswalk-project/crosswalk.git
    ```

1. Edit this newly-created `.gclient` file, and add the following to the
   bottom:

    ```
    target_os = ['android']
    ```

1. Run `gclient sync` to fetch Crosswalk, all other git repositories it depends
   on as well as any additional helper files. If you are using Crosswalk 22 or
   earlier, this will also automatically run the `gyp_xwalk` script in the end.

    ```
    gclient sync
    ```

    Do not worry if `gyp_xwalk` fails due to missing dependencies; installing
    them is covered in a later section, after which you can run `gyp_xwalk`
    manually again.

## Dependency installation

The Crosswalk for Android build requires several packages to be present on your
Linux installation. All dependencies are checked for at configuration time --
either when `gyp_xwalk` or `gn gen` (or `gn args`) is run, so if something is
missing on your system you will get an error before you are able to start
building.

If you are using Ubuntu or another Debian-based distribution, you can use
Chromium's `install-build-deps-android.sh` script to install all dependencies:

```
cd /path/to/crosswalk-checkout/src
./build/install-build-deps-android.sh
```

Users of other distros can take a look at `src/build/install-build-deps.sh` and
`src/build/install-build-deps-android.sh` to see which packages are listed and
install the analogous versions shipped by their distributions.

If you are using gyp, make sure you call `gyp_xwalk` again once everything has
been installed.

## Crosswalk 23 and later: GN setup

Starting with Crosswalk 23, GN has replaced gyp as the meta-build system for
both Chromium and Crosswalk. Its setup is a bit different compared to gyp, as
`chromium.gyp_env` (and the `GYP_DEFINES` environment variable) does not have
any effect, and the `gyp_xwalk` is not used.

Instead, you use the `gn` binary to both edit a file with your settings as well
as generate the files required by Ninja to build.

In order to build Crosswalk, you need to have at least two lines in your
settings to pull some default variables we set:

```
cd /path/to/crosswalk-checkout
gn args out/Default
```

A text editor will be shown, where you must add the following line:

```
import("//xwalk/build/android.gni")
target_os = "android"
```

You can add other settings after this statement, such as `is_debug = false` for
a release build or `target_arch = "x64"` to create a 64-bit IA build. By
default, `target_arch` is set to `arm`, which creates a 32-bit ARM build.

Once you have done this once, you can later call `gn gen out/Default` to just
regenerate the ninja files based on your existing settings if you need to (most
of the time, this will be done automatically for you when building).

See Chromium's
[page about GN](https://www.chromium.org/developers/gn-build-configuration) for
more information, including other common settings.

## Building Crosswalk

Once either gyp or GN has been properly configured, it is time to build
Crosswalk.

```
cd C:\path\to\crosswalk-checkout\src
ninja -C YOUR-BUILD-DIR xwalk
```

You should replace **YOUR-BUILD-DIR** above with the appropriate directory
created with gyp or GN:
* With gyp and a 32-bit build, it's `out/Release`.
* With gyp and a 64-bit build, it's `out/Release_x64`.
* With GN, it's whatever directory you chose, such as `out/Default`.

**YOUR-TARGET** needs to be replaced with the kind of target you want to build
(more than one can be passed in the same invocation). For example:
* `xwalk_core_library` creates a directory called `xwalk_core_library` in
  `YOUR-BUILD-DIR` with an architecture-specific Crosswalk library for use when
   embedding Crosswalk in a project.
* `xwalk_runtime_lib_apk` will generate a file called `XWalkRuntimeLib.apk` in
  `YOUR-BUILD-DIR/apks` containing Crosswalk's runtime library (which can act
  as the runtime for applications built using Crosswalk's shared mode).
* `xwalk_app_template_apk` creates a sample web app APK for testing. It is
  located in `YOUR-BUID-DIR/apks/XWalkAppTemplate.apk`.

## Chromium information

Most of the instructions described in this page are similar to Chromium's own
instructions. We suggest you take a look at its instructions as well; it might
help solve any questions you have after reading our documentation.

* [Chromium Android build instructions](https://chromium.googlesource.com/chromium/src/+/master/docs/android_build_instructions.md)
