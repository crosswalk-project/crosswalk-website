# Linux build instructions

## Requirements

These instructions assume you have already downloaded `depot_tools` and that it
is in your `PATH` environment variable. See the
[prerequisites](prerequisites.html) page for further information.

Additionally, you are expected to be using a 64-bit Linux installation.

## Crosswalk 22 and earlier: gyp setup

Up to Crosswalk 22, both Chromium and Crosswalk used gyp as a meta-build system
that generated the actual build system files used by ninja. gyp accepts flags to change its behavior.

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

## Fetching the source code

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

The Crosswalk for Linux build requires several packages to be present on your
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

In order to build Crosswalk, you need to have at least one line in your
settings to pull some default variables we set:

```
cd /path/to/crosswalk-checkout
gn args out/Default
```

A text editor will be shown, where you must add the following line:

```
import("//xwalk/build/linux.gni")
```

You can add other settings after this statement, such as `is_debug = false` for
a release build or `target_arch = "arm"` to create a 32-bit ARM build.

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

## Chromium information

Most of the instructions described in this page are similar to Chromium's own
instructions. We suggest you take a look at its instructions as well; it might
help solve any questions you have after reading our documentation.

* [Chromium Linux build instructions](https://chromium.googlesource.com/chromium/src/+/master/docs/linux_build_instructions.md)
