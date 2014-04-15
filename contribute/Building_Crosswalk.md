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
for Android, covered in [building Chrome for Android](http://code.google.com/p/chromium/wiki/AndroidBuildInstructions#Install_prerequisites).

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
          git://github.com/crosswalk-project/crosswalk.git

    You can replace `git://` with `ssh://git@` to use your
GitHub credentials when checking out the code.

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

1.  `gyp` is the tool used to generate Crosswalk projects. These projects
are then used as the basis for the actual code compilation.

    To build the projects, go to the `src` directory and run:

        export GYP_GENERATORS='ninja'
        python xwalk/gyp_xwalk

2.  At this point you have built the projects with `gyp` and are ready for
the actual compilation. To build the Crosswalk launcher (which can
run a web application):

        ninja -C out/Release xwalk

### Testing a desktop build

Once you've built the launcher, the executable you need is at:

    ~/crosswalk-src/src/out/Release/xwalk

You can test it by using it to load an HTML file:

    ~/crosswalk-src/src/out/Release/xwalk index.html

If you don't have any HTML applications to test, the
[Crosswalk samples](#documentation/samples) includes a few you can try.

## Building Crosswalk for Android

1.  Install the dependencies for building Crosswalk for Android:

        sudo ./build/install-build-deps-android.sh

    Note that this requires support for `apt-get` on your system.
    If you are on system without `apt-get` (like Fedora Linux), the
    following command should install the dependencies:

    ```
    sudo yum install git svn gcc-c++ nss-devel cups-devel \
    gtk2-devel pulseaudio-libs-devel dbus-devel gconf-devel \
    gconf GConf2-devel libgnome-keyring-devel libgcrypt-devel \
    libpciaccess-devel pciutils-devel libgudev1-devel \
    systemd-devel gperf bison libcap-devel expat-devel \
    alsa-lib-devel libXtst-devel lighttpd python-pexpect \
    xorg-x11-server-Xvfb xorg-x11-utils zlib.i686 \
    libstdc++.i686 glibc-devel.i686 libXScrnSaver-devel
    ```

2.  Install the Oracle JDK. It is
    available from the
    [Oracle Java download site](http://www.oracle.com/technetwork/java/javase/downloads/java-archive-downloads-javase6-419409.html).

3.  Set up the Android build environment.

    Crossswalk <= 5 :

        . xwalk/build/android/envsetup.sh --target-arch=x86

    If you are targeting ARM, pass `--target-arch=arm` instead of `--target-arch=x86`.

    Crosswalk > 5 :

        . xwalk/build/android/envsetup.sh

4.  To generate the Crosswalk projects, execute:

        export GYP_GENERATORS='ninja'
        
        Crosswalk <= 5 :
        
            xwalk_android_gyp
        
        Crosswalk > 5 :
            
            xwalk_android_gyp -Dtarget_arch=ia32
        
        If you are targeting ARM, pass `-Dtarget_arch=arm` instead of `-Dtarget_arch=ia32`.
        
        ./xwalk/gyp_xwalk

5.  To build xwalk core and runtime shell (for developer testing purposes, not
for end users), execute:

        ninja -C out/Release xwalk_core_shell_apk xwalk_runtime_shell_apk

    To build the xwalk runtime library apk, execute:

        ninja -C out/Release xwalk_runtime_lib_apk

    To build a sample web app apk, execute:

        ninja -C out/Release xwalk_app_template_apk

### Testing an Android build

To test the runtime shell, install the runtime library and shell apks
on a physical device with:

    adb install -r out/Release/apks/XWalkRuntimeLib.apk
    adb install -r out/Release/apks/XWalkRuntimeClientShell.apk
    adb shell am start -n org.xwalk.runtime.client.shell/org.xwalk.runtime.client.shell.XWalkRuntimeClientShellActivity

If you don't have an Android device, you can use an Android virtual
device (AVD) for testing instead. The Crosswalk source actually contains
enough of the Android SDK to enable you to download, create and run an AVD.

To make an AVD, run the `android` tool first:

    src/third_party/android_tools/sdk/tools/android

Then use it to install the **Intel x86 Atom System Image**, and
create an AVD using that. Make sure you allocate enough
*internal storage* and *SD card storage*: 1000MiB for each is
known to work.

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
[Tizen target set up](#documentation/getting_started/tizen_target_setup).

Running an application on Tizen is covered in
[Run on Tizen](#documentation/getting_started/run_on_tizen).

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
