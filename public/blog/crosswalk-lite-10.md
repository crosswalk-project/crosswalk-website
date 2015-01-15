# Introducing the Crosswalk Project “Lite” branch

One of the most frequent complaints about Crosswalk is that it makes the packaged application bigger. We’ve been hard at work to find out whether we could shave off any MBs from the Crosswalk binary, and we are happy to present the first results by introducing the Crosswalk Project Lite. 

“Lite” is developed in a separate branch (currently based on Crosswalk 10.39.232.0) and attempts to reduce the size of the APK and installed application footprint as much as possible, without compromising commonly used features. Below you can see the current measurements using Crosswalk master and Lite:
<table>
  <tr align="center">
    <td colspan="2">Crosswalk Project</td>
    <td colspan="2">Crosswalk Lite</td>
  </tr>
  <tr align="center">
    <td>APK</td>
    <td>Installed</td>
    <td>APK</td>
    <td>Installed</td>
  </tr>
  <tr align="center">
    <td>20M</td>
    <td>55M</td>
    <td>10-15M</td>
    <td>40M</td>
  </tr>
</table>

More precisely:
* x86: 11.1M (vs. 20.8M on Crosswalk 10.39.232.0)
* ARM: 9.63M (vs. 18M on Crosswalk 10.39.232.0)

As with any diet, a smaller size means some sacrifices. Here are the changes done so far to achieve these results:
* [NEW] Use LZMA compress xwalk_core_library (at the cost of longer fisrt-time startup time) [PR 2733] (https://github.com/crosswalk-project/crosswalk/pull/2733)
* Replace ICU with Java utils [PR 30] (https://github.com/crosswalk-project/blink-crosswalk/pull/30)
* Remove ANGLE (only needed on Windows) [PR 2683] (https://github.com/crosswalk-project/crosswalk/pull/2683)
* No support for Web Inspector nor remote debugging [PR 2685] (https://github.com/crosswalk-project/crosswalk/pull/2685)
* No support for the experimental QUIC protocol [PR 2686] (https://github.com/crosswalk-project/crosswalk/pull/2686)
* No support for the FTP protocol
* No support for WebRTC and WebRTC: Media streams ([PR 2596] (https://github.com/crosswalk-project/crosswalk/pull/2596) and [PR 2614] (https://github.com/crosswalk-project/crosswalk/pull/2614) respectively)
* No support for WebP [PR 2670] (https://github.com/crosswalk-project/crosswalk/pull/2670)
* No support for XSLT [PR 2628] (https://github.com/crosswalk-project/crosswalk/pull/2628)

### What next?
Our goal with the Lite branch is to try out different size optimization techniques, and if they are proven to be effective without compromising quality, apply them to the Crosswalk master branch. Therefore we encourage you to try it out and send us feedback, however we don’t recommend using it in production applications at this early stage. That being said, we do run our usual QA tests on Lite and try to ensure as much as possible the same experience as the “regular” Crosswalk.

You can download precompiled Crosswalk Project Lite binaries from our [download area] (https://download.01.org/crosswalk/releases/crosswalk/android/lite/canary/10.39.232.1/). For more details, please refer to the Crosswalk Project Lite [wiki] (https://github.com/crosswalk-project/crosswalk-website/wiki/Crosswalk-Project-Lite).
