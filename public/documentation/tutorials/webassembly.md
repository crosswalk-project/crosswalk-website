# Web Assembly

WebAssembly or wasm is a new portable, size- and load-time-efficient format suitable for compilation to the web. It has following advantages:

*   Efficient and fast.
*   Safe.
*   Open and debuggable.
*   Part of the open web platform.

WebAssembly is currently being designed as an open standard by a [W3C Community Group](https://www.w3.org/community/webassembly/) that includes representatives from all major browsers. Now it is available in Crosswalk 22 or later on Android, Windows and Linux. It's also [supported in multiple browsers](https://hacks.mozilla.org/2016/03/a-webassembly-milestone/)

In the following tutorial, you'll learn how to enable WebAssembly in Crosswalk and run the demo [Angrybots](https://webassembly.github.io/demo/) on Windows and Android platforms.

1. Check out the binary file of Angrybots:
        $ git clone https://github.com/WebAssembly/webassembly.github.io.git

2. Go to the demo folder of Angrybots:
        $ cd webassembly.github.io/demo/AngryBots/

3. Create a minimal [Crosswalk manifest](/documentation/manifest.html) file, `manifest.json`, with this content:

        {
          "name": "Angrybots",
          "description": "WebAssembly demo",
          "xwalk_version": "0.0.1",
          "start_url": "index.html",
          "xwalk_package_id": "com.wasm.angrybots",
          "xwalk_command_line": "--js-flags=--expose_wasm"
        }

4. Use [crosswalk-app-tools](https://github.com/crosswalk-project/crosswalk-app-tools)  to create the Windows .msi file (on crosswalk 22 or above):
        $ crosswalk-pkg -p windows --crosswalk="22.52.557.0" .

   or .apk file for Android, then [sign](https://developer.android.com/studio/publish/app-signing.html#signing-manually) it:
        $ crosswalk-pkg --targets="arm" --platform="android"  --crosswalk="22.52.557.0" .

5. Install the .msi file on Windows, install the apk file on Android:
        $ adb install com.wasm.angrybots*.apk

6. Run AngryBots.
![Angrybots on Windows](/assets/crosswalk-webassembly-angrybots.png)

## Useful WebAssembly resources

* [W3C Community Group](https://www.w3.org/community/webassembly/)
* [WebAssembly Design Documents](https://github.com/WebAssembly/design)
