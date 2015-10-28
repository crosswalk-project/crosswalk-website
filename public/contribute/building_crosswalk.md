# Building Crosswalk

## Environment

1.  Please set up your build environment by following the instructions
on the Chromium wiki:

    <ul>
    <li><a href="http://www.chromium.org/developers/how-tos/build-instructions-windows">Building on Windows</a></li>
    <li><a href="http://code.google.com/p/chromium/wiki/LinuxBuildInstructionsPrerequisites">Building on Linux</a></li>
    <li><a href="https://code.google.com/p/chromium/wiki/MacBuildInstructions">Building on Mac</a></li>
    </ul>

2.  You need to install extra pre-requisites if you're building Crosswalk
for Android, covered in [building Chrome for Android](http://code.google.com/p/chromium/wiki/AndroidBuildInstructions).

    The
    [section below](#contribute/building_crosswalk/Building-Crosswalk-for-Android)
    summarises the steps involved.

3.  [depot_tools](http://www.chromium.org/developers/how-tos/install-depot-tools)
contains the following tools, used to manage and build Crosswalk from
source:

    <ul>
    <li><code>gclient</code> manages code and dependencies.</li>
    <li><code>ninja</code> is the recommended tool for building Crosswalk on most platforms.
    Its <a href="http://code.google.com/p/chromium/wiki/NinjaBuild">website</a>
    contains detailed usage instructions.</li>
    </ul>

## Proxy setup

If you are behind a network proxy, you need to make sure your environment is
properly set up and the appropriate variables are set. On Linux, you need to do
at least the following:

1. Set the `http_proxy` and `https_proxy` environment variables.

    ```
    export http_proxy=http://example-host:port
    export https_proxy=http://example-host:port
    ```

1. Create a Boto configuration file somewhere with the following contents:

    ```
    [Boto]
    proxy = example-host
    proxy_port = port number
    ```

    After that, point the `NO_AUTH_BOTO_CONFIG` environment variable to the file
    you created:

    ```
    export NO_AUTH_BOTO_CONFIG=/path/to/boto-file
    ```

1. Set up your Subversion proxy settings. At least `http-proxy-host` and
   `http-proxy-port` must be set in the `[global]` section. On Linux, the file
   is called `.subversion/servers`, and on Windows it is
   `C:\Users\<YOUR_USER>\AppData\Roaming\Subversion\servers`.

## Download the Crosswalk source

### Before starting: Android

If you are building Crosswalk for Android, you should first set the
`XWALK_OS_ANDROID` environment variable:

    export XWALK_OS_ANDROID=1

It's essential that you do this, otherwise your checkout will not
contain the necessary components for the Android build.

If you checkout the source without this setting, you can set it later
and do `gclient sync` again to pull in the extra Android-specific
components.

### Fetch the source

1.  Create a source directory:

        cd <home directory>
        mkdir crosswalk-src
        cd crosswalk-src

2.  Auto-generate gclient's configuration file (`.gclient`):

        gclient config --name=src/xwalk \
          https://github.com/crosswalk-project/crosswalk.git

    You can replace `https://` with `git://` or `ssh://git@` depending on your
    proxy needs and whether you want to use your GitHub credentials.

3.  From the same directory containing the `.gclient` file, fetch the source with:

        gclient sync

### Tracking a different Crosswalk branch

If you want to track a Crosswalk branch other than `master`
(e.g. a beta or stable branch), there are two different approaches you can use,
described below.

*Set the branch before the initial checkout*

If you haven't Crosswalk's source code yet, just pass
the URL of the branch you want to checkout to the `gclient config` call.
For example, to track the _crosswalk-2_ branch:

    gclient config --name=src/xwalk \
      git://github.com/crosswalk-project/crosswalk.git@origin/crosswalk-2

*Change the branch for an existing checkout*

If you have already cloned Crosswalk and want to change the branch
being tracked, you need to check out a new git branch and then edit
your `.gclient` file.

For example, let's assume you want to track the _crosswalk-2_ branch.
First of all, check out a new branch in your Crosswalk repository:

    cd /path/to/src/xwalk
    git checkout -b crosswalk-2 origin/crosswalk-2

Next, edit your `.gclient` file (generated above) and change the `url`
entry. It should look like this:

```python
"url": "git://github.com/crosswalk-project/crosswalk.git@origin/crosswalk-2",
```

After that, sync your code again:

    gclient sync

## Building Crosswalk for desktop

These instructions cover building Crosswalk to run in a desktop
environment (Windows, Linux, or Mac OS).

1. `gyp` is the tool used to generate Crosswalk projects. These projects
are then used as the basis for the actual code compilation.

   To build the projects, go to the `src` directory and run

   on Linux, Mac OS:

   ```
   export GYP_GENERATORS='ninja'
   python xwalk/gyp_xwalk
   ```

   on Windows:

   ```
   set GYP_GENERATORS=ninja
   python xwalk\gyp_xwalk
   ```
   Note: in Windows, `set` takes effect in the current cmd window. `setx` makes the setting permanent in *future* cmd windows, similar to setting the variable in the Environment Variables dialog.

2. At this point you have built the projects with `gyp` and are ready for
the actual compilation. To build the Crosswalk launcher (which can
run a web application):

        ninja -C out/Release xwalk

### Testing a desktop build

The simplest way to start a Crosswalk application is to use the `xwalk` command
with an application’s manifest as argument:

    xwalk /path/to/manifest.json

Crosswalk will parse the manifest and launch the application from the entry
point specified in `start_url`.

If you don't have any HTML applications to test, the
[Crosswalk samples](/documentation/samples.html) includes a few you can try.

## Building Crosswalk for Android

As mentioned before, Crosswalk's build process for Android is heavily based on
Chrome's process, so make sure you are
[familiar with it](http://code.google.com/p/chromium/wiki/AndroidBuildInstructions).

1.  Install the dependencies for building Crosswalk for Android:

        ./build/install-build-deps-android.sh

    Note that this requires support for `apt-get` on your system.
    If you are on system without `apt-get` (like Fedora Linux), the
    following command should install the dependencies:

    ```
    sudo yum install alsa-lib-devel alsa-lib-devel.i686 bison \
    cairo-devel.i686 cups-devel cups-devel.i686 dbus-devel \
    dbus-devel.i686 elfutils-libelf-devel.i686 elfutils-libelf-devel.x86_64 \
    expat-devel expat-devel.i686 fontconfig-devel.i686 freetype-devel.i686 \
    gcc-c++ gconf GConf2-devel GConf2-devel.i686 gconf-devel git \
    glib2-devel.i686 glibc-devel.i686 gperf gtk2-devel harfbuzz-devel.i686 \
    krb5-devel.i686 libcap-devel libcap-devel.i686 libcom_err-devel.i686 \
    libgcrypt-devel libgcrypt-devel.i686 libgnome-keyring-devel \
    libgpg-error-devel.i686 libgudev1-devel libpciaccess-devel \
    libstdc++.i686 libX11-devel.i686  libXcomposite-devel.i686 \
    libXcursor-devel.i686 libXdamage-devel.i686 libXext-devel.i686 \
    libXfixes-devel.i686 libXi-devel.i686 libXrandr-devel.i686 \
    libXrender-devel.i686 libXScrnSaver-devel libXtst-devel \
    libXtst-devel.i686 lighttpd nss-devel nss.i686 pango-devel.i686 \
    pciutils-devel pciutils-devel.i686 pulseaudio-libs-devel python-pexpect \
    svn systemd-devel systemd-devel.i686 xorg-x11-server-Xvfb \
    xorg-x11-utils zlib-devel.i686 zlib.i686
    ```

2.  Configure gyp.

    gyp is the build system generator used in Chromium to generate actual build
    files for Ninja and other systems. It needs a few variables in order to
    generate build files for Android.

    Have a file called `chromium.gyp_env` in the directory containing your
    `.gclient` file (so the one above `src/`).

        echo "{ 'GYP_DEFINES': 'OS=android target_arch=ia32', }" > chromium.gyp_env

    If you are building for ARM, use `target_arch=arm` instead of
    `target_arch=ia32` above.

3.  Configure your setup to generate the Crosswalk projects.

        export GYP_GENERATORS='ninja'
        python xwalk/gyp_xwalk

4.  To build the contents of the main Crosswalk package for Android, with tools
    and an embeddable library, you can run:

        ninja -C out/Release xwalk_core_library

    This will create a directory in `out/Release` called `xwalk_core_library`
    with an architecture-specific Crosswalk library for use when embedding
    Crosswalk in a project.

    To build Crosswalk's runtime library APK (which can act as the runtime for
    applications built using Crosswalk's shared mode), run:

        ninja -C out/Release xwalk_runtime_lib_apk

    This will create an APK called `XWalkRuntimeLib.apk` in `out/Release/apks`.

    To build a sample web app APK (for quickly installing/testong on a target),
    execute:

        ninja -C out/Release xwalk_app_template_apk

    which will create an APK called `XWalkAppTemplate.apk` in
    `out/Release/apks`.

    Finally, you can also build AAR files for use with Gradle and Maven. The
    following command will create an architecture-specific AAR file with the
    main Crosswalk library, as well as an architecture-independent AAR for use
    with Crosswalk's shared mode:

        ninja -C out/Release xwalk_core_library_aar xwalk_shared_library_aar

    The AAR files will both be located in the `out/Release` directory.

## Building Crosswalk for Tizen

If you are unfamiliar with the RPM packaging process on Tizen, please
consult the
[GBS documentation](https://source.tizen.org/documentation/reference/git-build-system).

Crosswalk's layout is a bit unusual, as it actually contains several
independent git and Subversion repositories in the same directory tree.
We employ some tricks to make it work with GBS, but it should all be
transparent to users - the only actual caveat is that GBS will
build _everything_ that is in your source tree regardless of whether
it has been committed or not; that is, it always acts as if
the `--include-all` parameter has been passed to `gbs build`.

That said, building an RPM for Tizen (after properly setting up your
Tizen repositories) should be a matter of calling `gbs build`:

    cd /path/to/src/xwalk
    gbs build -A i586

By default, the generated RPM files end up in
`~/GBS-ROOT/local/repos/<repository name>/i586/RPMS`.

### Testing a Tizen build

The steps for installing a Tizen rpm on a device are covered in
[Tizen target set up](/documentation/tizen/tizen_target_setup.html).

Running an application on Tizen is covered in
[Run on Tizen](/documentation/tizen/run_on_tizen.html).

### Incremental builds

By default, each time you call `gbs build`, the Tizen build directory
will be erased and the build will start from scratch. To avoid that,
you can set a different build directory in the Tizen chroot,
outside `/home/abuild/rpmbuild`:

    gbs build -A i586 --define 'BUILDDIR_NAME /tmp/crosswalk-builddir'

### Troubleshooting

* The directory you use as the GBS root must be an actual directory:
symbolic links can cause problems.
* The GBS root directory must be on the same partition as your root
directory (`/`).

## Running the test suites

If you are interested in running Crosswalk's test suites, you can
build the test suites from the `src` directory with:

    ninja -C out/Release xwalk_unittest
    ninja -C out/Release xwalk_browsertest

Then run them with:

    ./out/Release/xwalk_unitttest
    ./out/Release/xwalk_browsertest
