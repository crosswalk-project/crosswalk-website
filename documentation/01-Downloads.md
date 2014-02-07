# Downloads

The Crosswalk project provides binaries for multiple operating systems and platforms.

For instructions on how to use these downloads, see the [Getting started](#documentation/getting_started) pages.

For more information about the release channels, see [the *Release channels* section](#Release-channels).

<table class="downloads-table">

<tr>
<th>&nbsp;</th>
<th>Stable</th>
<th>Beta</th>
<th>Canary</th>
</tr>

<tr>
<th>Android (x86)</th>
<td><a href="https://download.01.org/crosswalk/releases/android-x86/stable/crosswalk-${XWALK-STABLE-ANDROID-X86}-x86.zip">${XWALK-STABLE-ANDROID-X86}</a></td>
<td><a href="https://download.01.org/crosswalk/releases/android-x86/beta/crosswalk-${XWALK-BETA-ANDROID-X86}-x86.zip">${XWALK-BETA-ANDROID-X86}</a></td>
<td><a href="https://download.01.org/crosswalk/releases/android-x86/canary/">downloads</a></td>
</tr>

<tr>
<th>Android (ARM)</th>
<td>-</td>
<td>-</td>
<td><a href="https://download.01.org/crosswalk/releases/android-arm/canary/">downloads</a></td>
</tr>

<tr>
<th>Tizen 2.x Mobile (x86)</th>
<td><a href="https://download.01.org/crosswalk/releases/tizen-mobile/stable/crosswalk-${XWALK-STABLE-TIZEN-X86}-0.i586.rpm">${XWALK-STABLE-TIZEN-X86}</a></td>
<td><a href="https://download.01.org/crosswalk/releases/tizen-mobile/beta/crosswalk-${XWALK-BETA-TIZEN-X86}-0.i586.rpm">${XWALK-BETA-TIZEN-X86}</a></td>
<td><a href="https://download.01.org/crosswalk/releases/tizen-mobile/canary/">downloads</a></td>
</tr>

<tr>
<th>Tizen 2.x Emulator (x86)</th>
<td><a href="https://download.01.org/crosswalk/releases/tizen-mobile/stable/crosswalk-emulator-support-${XWALK-STABLE-TIZEN-X86}-0.i586.rpm">${XWALK-STABLE-TIZEN-X86}</a></td>
<td><a href="https://download.01.org/crosswalk/releases/tizen-mobile/beta/crosswalk-emulator-support-${XWALK-BETA-TIZEN-X86}-0.i586.rpm">${XWALK-BETA-TIZEN-X86}</a></td>
<td><a href="https://download.01.org/crosswalk/releases/tizen-mobile/canary/">downloads</a></td>
</tr>

<tr>
<th>Tizen 3.0 IVI (x86)</th>
<td>-</td>
<td>-</td>
<td><a href="https://download.01.org/crosswalk/releases/tizen-ivi/canary/">downloads</a></td>
</tr>

<tr class="downloads-row release-notes-row">
<th class="release-notes-heading">Release notes</th>
<td><a href="#wiki/Crosswalk-${XWALK-STABLE-ANDROID-X86-MAJOR}-release-notes">Crosswalk-${XWALK-STABLE-ANDROID-X86-MAJOR}</a></td>
<td><a href="#wiki/Crosswalk-${XWALK-BETA-ANDROID-X86-MAJOR}-release-notes">Crosswalk-${XWALK-BETA-ANDROID-X86-MAJOR}</a></td>
<td>*</td>
</tr>

</table>

\* - note that release notes are only produced for stable and beta versions.

## Release channels

There are three release channels (in order of increasing instability):

1.  **Stable**

    A Stable release is a release intended for end users. Once a Crosswalk release is promoted to the Stable channel, that release will only see new binaries for critical bugs and security issues.

2.  **Beta**

    A Beta release is intended primarily for application developers looking to test their application against any new changes to Crosswalk, or use new features due to land in the next Stable release. Beta builds are published based on automated basic acceptance tests (ABAT), manual testing results, and functionality changes. There is an expected level of stability with Beta releases; however, it is still Beta, and may contain significant bugs.

3.  **Canary**

    The Canary release is generated on a frequent basis (sometimes daily). It is based on a recent tip of master that passes a full build and automatic basic acceptance test. The Canary build is an easy option for developers who are interested in the absolute latest features, but don't want to build Crosswalk themselves.

More information is available on the [Release Channels page](#wiki/Release-methodology).

The [Channel Viewer](#contribute/channels-viewer) shows detailed information about the status of each release channel.

The [Crosswalk version numbers page](#wiki/release-methodology/version-numbers) describes how Crosswalk versions are assigned.
