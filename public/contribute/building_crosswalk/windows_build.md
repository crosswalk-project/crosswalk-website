# Windows build instructions

## Requirements

Just like Chromium itself, building Crosswalk for Windows requires Visual Studio and the Windows 10 SDK. Also similarly to Chromium, different Crosswalk releases only work with specific versions of Visual Studio and the Windows 10 SDK. The table below summarizes the requirements for each Crosswalk release that supports Windows.

| Crosswalk version | Visual Studio version | Other |
| --- | --- |
| 19 | Visual Studio 2013 Update 4 or Visual Studio 2015 Update 1 | Windows 10 SDK (10.0.10586) |
| 20 | Visual Studio 2013 Update 4 or Visual Studio 2015 Update 1 | Windows 10 SDK (10.0.10586) |
| 21 | Visual Studio 2015 Update 2 | Windows 10 SDK (10.0.10586) |
| 22 | Visual Studio 2015 Update 2 | Windows 10 SDK (10.0.10586) |
| 23 | Visual Studio 2015 Update 2 | Windows 10 SDK (10.0.10586) |

Additionally, you must use at least Windows 7 to be able to build Crosswalk.
32-bit installations are **not** supported. Also make sure your system locale
is set to English, otherwise you might run into error messages such as _"The
file contains a character that cannot be represented in the current code
page"_.

These instructions assume you have already downloaded `depot_tools`,
bootstrapped it (ie. git and Python have been installed) and it is in your
`PATH` environment variable. See the [prerequisites](prerequisites.html) page
for further information.

## Environment setup

By default, when checking out Chromium as part of Crosswalk's check out
process, the scripts will attempt to download Visual Studio from internal
Google servers by default (this comes from Chromium).

The first thing you need to do then is to make sure this does not happen, as
only Google employees are able to reach those servers. You need to install
Visual Studio yourself.

When using `cmd`:

    set DEPOT_TOOLS_WIN_TOOLCHAIN=0
    setx DEPOT_TOOLS_WIN_TOOLCHAIN 0

The first command will set the environment variable for the existing prompt,
while the second will set it for all future prompts.

See the [prerequisites](prerequisites.html) section if you prefer to configure
those environment variables using a GUI.

## Crosswalk 22 and earlier: gyp setup

Up to Crosswalk 22, both Chromium and Crosswalk used gyp as a meta-build system
that generated the actual build system files used by ninja.

gyp accepts flags to change its behavior. They can be used to tell it you want
to create a 64-bit build, for example.

There are two ways to define those flags:

1. Create a file called `chromium.gyp_env` on the directory where you will
   later call `gclient config` and `gclient sync` (so the one above `src/`).
   It needs to look like this:

    ```
    {
      'GYP_DEFINES': 'key1=value1 key2=value2',
    }
    ```

1. Use `set` and/or `setx` in a command prompt to set `GYP_DEFINES` as an
   environment variable.

These values are read every time you run the `src/xwalk/gyp_xwalk` script, so
whenever you want to change them remember to run the script afterwards.

To generate a 64-bit build instead of a 32-bit one, you can set `target_arch`
to `x64`. For example, if you are using `chromium.gyp_env`, its contents should
look like this:

```
{
  'GYP_DEFINES': 'target_arch=x64',
}
```

## Fetching the source code

1. Choose where you want to download and build Crosswalk. If you are using gyp
   and opted for having a `chromium.gyp_env` file, this directory must contain
   that file.

   ```
   cd C:\path\to\crosswalk-checkout
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
cd C:\path\to\crosswalk-checkout\src
gn args out/Default
```

A text editor will be shown, where you must add the following line:

```
import("//xwalk/build/windows.gni")
```

You can add other settings after this statement, such as `is_debug = false` for
a release build or `target_cpu = "x64"` to create a 64-bit build.

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
ninja -C YOUR-BUILD-DIR YOUR-TARGET
```

You should replace **YOUR-BUILD-DIR** above with the appropriate directory
created with gyp or GN:
* With gyp and a 32-bit build, it's `out/Release`.
* With gyp and a 64-bit build, it's `out/Release_x64`.
* With GN, it's whatever directory you chose, such as `out/Default`.

## Special topics: Visual Studio integration

It is possible to use Visual Studio as an IDE when working on Chromium and
Crosswalk with minimal changes.

### gyp

If you are using `gyp`, you need to set the `GYP_GENERATORS` flag to
`ninja,msvs-ninja` and call the `gyp_xwalk` script again.

To build in Visual Studio, open xwalk.sln from
`path\to\crosswalk-checkout\src\xwalk` and you’re ready to go. Select a target
and click Build (for example `xwalk`, or `xwalk_builder`). Please note that
`xwalk.sln` actually has all the Chromium code as a dependency therefore
xwalk.sln has something like 600 subprojects which requires a pretty powerful
machine with a lot of RAM to be able to handle that correctly.

We suggest using the [Funnel extension](http://vsfunnel.com/) which allows you
to select which subproject you want to load.

### GN

If you are using `gn`, you need to pass `--ide=vs` to `gn gen`.

GN will produce a file called `all.sln` in your build directory. It will
internally use Ninja to compile while still allowing most IDE functions to
work. If you manually run `gn gen` again you will need to resupply this
argument, but normally GN will keep the build and IDE files up to date
automatically when you build.

The generated solution will contain several thousand projects and will be very
slow to load. Use the `--filters` argument to restrict generating project files
for only the code you're interested in, although this will also limit what
files appear in the project explorer. A minimal solution that will let you
compile and run Chrome in the IDE but will not show any source files is:

    gn gen --ide=vs --filters=//xwalk out\Default

## Chromium information

Most of the instructions described in this page are similar to Chromium's own
instructions. We suggest you take a look at its instructions as well; it might
help solve any questions you have after reading our documentation.

* [Chromium Windows build instructions](https://chromium.googlesource.com/chromium/src/+/master/docs/windows_build_instructions.md)
