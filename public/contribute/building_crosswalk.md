# Building Crosswalk

The Crosswalk Project is structured around and heavily depends on the
[Chromium project](https://www.chromium.org/Home).

As such, Crosswalk uses the same build system and auxiliary tools as Chromium:

* `depot_tools` manages source code checkouts, the dependencies among them and
  ships some additional tools required to build Chromium and Crosswalk (such as
  ninja on all platforms and git and Python on Windows).
* `ninja` is the actual build system, responsible for invoking the compiler,
  running scripts, linking binaries and creating APKs.

The following sub-pages describe all the steps required in order to build your
own copy of Crosswalk, from setting up network proxies to building to running
our tests.

The instructions below correspond to the operating system you are using to
_build_ Crosswalk (the "host"), not the platform you are targeting. That is, if
you want to build Crosswalk for Android, you still need to follow the
instructions for Linux below.

1. [Prerequisites](building_crosswalk/prerequisites.html) covers dealing with
   network proxies and correctly installing and bootstrapping `depot_tools`.
1. The platform-specific pages detail how to check out and build Crosswalk for
   different operating systems:
   * [Android](building_crosswalk/android_build.html)
   * [Linux](building_crosswalk/linux_build.html)
   * [Windows](building_crosswalk/windows_build.html)
1. [Running tests](building_crosswalk/running_tests.html) covers how to run
   Crosswalk's tests, with instructions for all different platforms we support.
1. [Tracking a different branch](building_crosswalk/tracking_branches.html)
   covers how to build a Crosswalk branch other than `master` (a stable or
   older branch, for example).
