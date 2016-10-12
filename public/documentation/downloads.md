# Downloads

The Crosswalk Project provides binaries for multiple operating systems and platforms.

The recommended approach to building web applications with Crosswalk is to use the NPM-based [Crosswalk App Tools](/documentation/crosswalk-app-tools.html) as explained in the [Getting Started](/documentation/getting_started.html) instructions. This page is primarily for downloading non-stable or shared library versions.

Cordova developers are encouraged to use the [Crosswalk Webview Plugin](/documentation/cordova.html), which will download the Crosswalk library automatically.

| | Stable (21.51.546.7)
| ------------ | -------------
| **Android (ARM + x86)** | [32-bit](https://download.01.org/crosswalk/releases/crosswalk/android/stable/latest/crosswalk-21.51.546.7.zip) / [64-bit](https://download.01.org/crosswalk/releases/crosswalk/android/stable/latest/crosswalk-21.51.546.7-64bit.zip)
| **Android webview (x86)** | [32-bit](https://download.01.org/crosswalk/releases/crosswalk/android/stable/latest/x86/crosswalk-webview-21.51.546.7-x86.zip) / [64-bit](https://download.01.org/crosswalk/releases/crosswalk/android/stable/latest/x86_64/crosswalk-webview-21.51.546.7-x86_64.zip)
| **Android webview (ARM)** | [32-bit](https://download.01.org/crosswalk/releases/crosswalk/android/stable/latest/arm/crosswalk-webview-21.51.546.7-arm.zip) / [64-bit](https://download.01.org/crosswalk/releases/crosswalk/android/stable/latest/arm64/crosswalk-webview-21.51.546.7-arm64.zip)

All versions (including our betas and canaries) can be found in https://download.01.org/crosswalk/releases/crosswalk/.

**Release notes** can be found here: [Crosswalk Project Release Notes](/documentation/release-notes.html)
(Features, API changes, and known issues).

See also:
* [Getting started](/documentation/getting_started.html): How to use these downloads.
* [Quality Dashboard](/documentation/qa/quality_dashboard.html): Test coverage and results for each build.
* [Release methodology](https://github.com/crosswalk-project/crosswalk-website/wiki/release-methodology#version-numbers): The meaning of the version numbers.

## Quality summary

Please view [Quality Dashboard](/documentation/qa/quality_dashboard.html) for detailed quality summary.

## Release channels

There are three release channels (in order of increasing instability):

1. **Stable**

   A Stable release is a release intended for end users. Once a Crosswalk release is promoted to the Stable channel, that release will only see new binaries for critical bugs and security issues.

1. **Beta**

    A Beta release is intended primarily for application developers looking to test their application against any new changes to Crosswalk, or use new features due to land in the next Stable release. Beta builds are published based on automated basic acceptance tests (ABAT), manual testing results, and functionality changes. There is an expected level of stability with Beta releases; however, it is still Beta, and may contain significant bugs.

1. **Canary**

    The Canary release is generated on a frequent basis (sometimes daily). It is based on a recent tip of master that passes a full build and automatic basic acceptance test. The Canary build is an easy option for developers who are interested in the absolute latest features, but don't want to build Crosswalk themselves.

More information is available on the [Release Channels page](https://github.com/crosswalk-project/crosswalk-website/wiki/Release-methodology).

The [Crosswalk version numbers page](https://github.com/crosswalk-project/crosswalk-website/wiki/release-methodology#version-numbers) describes how Crosswalk versions are assigned.
