# System setup

This page describes using the new, simpler, NPM-based [crosswalk-app-tools](/documentation/crosswalk-app-tools.html) to build and debug. The [deprecated instructions for make_apk.py](make_apk_docs.html) are still available.

These steps will enable you to develop Crosswalk applications to run on Android. 

## Install tools
The following tools are needed on your development system to build Android apps. The same tools are needed on Linux and Windows. Download and install the appropriate version for your platform.

* [Java JDK](#Java)
* [Apache Ant](#Ant)
* [Android SDK](#Android)
* [NPM](#NPM)
* [Crosswalk App Tools](#Crosswalk)

### <a class="doc-anchor" id="Java"></a>Install the Oracle Java Development Kit (JDK)

   Download and install the Oracle JDK: http://www.oracle.com/technetwork/java/javase/downloads/  (Java 7 and 8 are known to work)

### <a class="doc-anchor" id="Ant"></a>Install Apache Ant

   Download and install the Apache Ant build tool: http://www.apache.org/dist/ant/binaries/ (Version 1.9.3 is known to work)

### <a class="doc-anchor" id="Android"></a>Install the Android SDK

   * Download and install Android Studio from <a href='http://developer.android.com/sdk/index.html' target='_blank'>http://developer.android.com/sdk/index.html</a>.

   * Start the *SDK Manager*, from the command-line or inside Android Studio:

      <p>In Windows: <pre><code class="bash">> "SDK Manager.exe"</code></pre></p>
      <p>In Linux: <pre><code class="bash">> android</code></pre></p>
      <p>or</p>
      <img src="/assets/sdk-manager1.png" style="margin: 0 auto"/>

   * In the SDK Manager window, install the Platform tools, Build tools, and SDK Platform for the version(s) you are interested in:

       <img src="/assets/sdk-manager-select.png" style="display: block; margin: 0 auto"/>

### <a class="doc-anchor" id="NPM"></a>Install node.js and npm

   Download and install node.js for your platform: https://nodejs.org/en/download/.  This will also install npm.

### <a class="doc-anchor" id="Crosswalk"></a>Install crosswalk-app-tools
From a cmd shell use npm to install crosswalk-app-tools

```bash
> npm install -g crosswalk-app-tools
```

   Note: If you are developing behind a proxy, see [this page](/documentation/npm-proxy-setup.html)

## <a class="doc-anchor" id="Verify-your-environment"></a>Verify your environment
Check that you have installed the tools properly by running these commands:

Example on Windows:
```cmdline
C:\dev>crosswalk-app check android
  + Checking host setup for target android
  + Checking for android... C:\dev\android\sdk\tools\android.bat
  + Checking for ant... C:\dev\apache-ant-1.9.4\bin\ant
  + Checking for java... C:\ProgramData\Oracle\Java\javapath\java.exe
  + Checking for ANDROID_HOME... C:\dev\Android\
  ...
```
If any items report missing, add the directory to the program binaries in your PATH.
*Note:* Currently the tool reports an ERROR if lzma is not found. You can safely ignore this for now.
```bash
ERROR: Checking for lzma... null
```

## Next Steps
Your system is ready for Android development with Crosswalk.

