# Building Crosswalk

## Environment
Please setup the build environment by following the instructions in Chromium's wiki:
 * [Setting up the environment for Windows](http://www.chromium.org/developers/how-tos/build-instructions-windows#TOC-Build-environment).
 * [Setting up the environment for Linux](http://code.google.com/p/chromium/wiki/LinuxBuildInstructionsPrerequisites).
 * [Setting up the environment for Android](http://code.google.com/p/chromium/wiki/AndroidBuildInstructions#Install_prerequisites).

## Get the code
### Before starting: Android
If you are building Crosswalk for Android, you should first set the `XWALK_OS_ANDROID` environment variable:

    export XWALK_OS_ANDROID=1

### Run `gclient`
Crosswalk uses `gclient` to manage the code and dependencies. To get `gclient` tool, you should install [depot_tools](http://www.chromium.org/developers/how-tos/install-depot-tools).

First, you need to create a source directory:

    mkdir ~/git/crosswalk
    cd ~/git/crosswalk

Execute the following command to gclient auto-generate `.gclient` file.

    gclient config --name=src/xwalk \
                   git://github.com/crosswalk-project/crosswalk.git

At the same level of `.gclient` file, execute

    gclient sync

to fetch all codes.

### Tracking a different Crosswalk branch
If you want to track a Crosswalk branch other than `master` (a beta or stable branch, for example), there are two different approaches you can use.

#### From scratch
In case you have not cloned Crosswalk's source code yet, just pass a different URL to the `gclient config` call above. To track the _crosswalk-2_ branch, for example:

    gclient config --name=src/xwalk \
                   git://github.com/crosswalk-project/crosswalk.git@origin/crosswalk-2

#### Changing an existing checkout
If you have already cloned Crosswalk and want to change the branch being tracked, you need to check out a new git branch and then edit your `.gclient` file.

For example, let us assume you want to track the _crosswalk-2_ branch. First of all, check out a new branch in your Crosswalk repository:

    cd /path/to/src/xwalk
    git checkout -b crosswalk-2 origin/crosswalk-2

Next, edit your `.gclient` file (generated above) and change the `url` entry. It should look like this:

```python
  # ...
  "url": "git://github.com/crosswalk-project/crosswalk.git@origin/crosswalk-2",
  # ...
```

After that, just sync your code again:

    gclient sync

## Build Instructions
`ninja` is the recommended by build for building Crosswalk in most platforms. Its [website](http://code.google.com/p/chromium/wiki/NinjaBuild) contains more usage instructions.

We use `gyp` to generate Crosswalk projects, go to `src` directory, execute

    export GYP_GENERATORS='ninja'
    python xwalk/gyp_xwalk

**NOTE**: it is possible also to enable [Aura UI framework](http://www.chromium.org/developers/design-documents/aura) for Tizen 3.0 build; in that case execute:

    python xwalk/gyp_xwalk -Duse_aura=1 -Duse_gconf=0 -Duse_xi2_mt=2

at this point you have built the projects with gyp and are ready for the actual compilation. To build the launcher now execute:

    ninja -C out/Release xwalk

Optionally, to build the tests, execute:

    ninja -C out/Release xwalk_unittest
    ninja -C out/Release xwalk_browsertest

### Build Instructions for Android
First of all, set up the Android build environment. If you are targeting ARM, pass `arm` instead of `x86` below:

    . xwalk/build/android/envsetup.sh --target-arch=x86

Generate Crosswalk projects, execute

     export GYP_GENERATORS='ninja'
     xwalk_android_gyp

To build xwalk core and runtime shell(for developer testing purpose), execute:

    ninja -C out/Release xwalk_core_shell_apk xwalk_runtime_shell_apk

To build xwalk runtime library APK, execute:
   
    ninja -C out/Release xwalk_runtime_lib_apk

To build a sample web app APK, execute:
   
    ninja -C out/Release xwalk_app_template_apk

### Build Instructions for Tizen
If you are unfamiliar with the RPM packaging process on Tizen, be sure to take a look at the [GBS documentation](https://source.tizen.org/documentation/reference/git-build-system) in Tizen's website.

Crosswalk's layout is a bit unusual, as it actually contains several independent git and Subversion repositories in the same directory tree. We employ some tricks to make it work with GBS, but it should all be transparent to users -- the only actual caveat is that GBS will build _everything_ that is in your source tree regardless of whether it has been committed or not; that is, it always acts as if the `--include-all` parameter has been passed to `gbs build`.

That said, building an RPM for Tizen (after properly setting up your Tizen repositories) should be a matter of calling `gbs build`:

    cd /path/to/src/xwalk
    gbs build -A i586

By default, the generated RPM files should be in `~/GBS-ROOT/local/repos/<repository name>/i586/RPMS`.

#### Incremental builds
By default, each time you call `gbs build` the Tizen build directory will be erased and the build will start from scratch. To avoid that, you can set a different build directory in the Tizen chroot, outside `/home/abuild/rpmbuild`:

    gbs build -A i586 --define 'BUILDDIR_NAME /tmp/crosswalk-builddir'

#### Troubleshooting
* The directory you use as the GBS root must be an actual directory: symbolic links can cause problems.
* The GBS root directory must be on the same partition as your root directory (`/`).
