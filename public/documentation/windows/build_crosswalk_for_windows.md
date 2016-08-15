# Building Crosswalk For Windows

1. Download and install [Microsoft Visual Studio 2015 update 2](https://www.visualstudio.com/en-us/downloads/download-visual-studio-vs.aspx). Visual Studio 2015 update 3 is not supported at the moment. The Visual Studio dependencies of Crosswalk follow Chromium's, so for Crosswalk before 21, Microsoft Visual Studio 2013 is required. And for Crosswalk 21 and above, Microsoft Visual Studio 2015 is required. During installing Visual Studio 2015 Update 2 - Community Edition should work if its license is appropriate, Please use the Custom Install option and select:
   * Visual C++, which will select three sub-categories including MFC
   * Universal Windows Apps Development Tools > Tools
   * Universal Windows Apps Development Tools > Windows 10 SDK (10.0.10586)

2. Windows system local must be set to English.or else a build errors maybe happen about "The file contains a character that cannot be represented in the current code page".

3. 64-bit Windows builds on Windows 7 or later are tested and supported. 32-bit builds may work, but are unsupported.

2. Install Git For Windows from http://git-scm.com/download/win and make sure it’s added to your PATH. Edit environment variables for your account. In the Windows Start menu, search for "Environment variables". Alternatively, click on the System icon in the Control Panel; then go to Advanced system settings and click the Environment Variables button. You should see this dialog box:

   <img src="/assets/win8.png" style="display: block; margin: 0 auto"/>

3. Clone depot_tools from Google by running:

   ```cmdline
   > git clone https://chromium.googlesource.com/chromium/tools/depot_tools.git
   ```

   Make sure you add the `depot_tools` directory in your PATH using the documented procedure above.

4. In the Environment Variables dialog create new variables (using the “New...” button) and add:

   *  `GYP_DEFINES` set to `target_arch=x64` as Crosswalk only supports 64 bits builds.
   *  `GYP_GENERATORS` set to `ninja,msvs-ninja` (this will create Visual Studio solutions if you want to run Crosswalk for Windows inside the IDE)
   *  `DEPOT_TOOLS_WIN_TOOLCHAIN`  set to 0 (which will tell Crosswalk for Windows to use your installation of Visual Studio)

5. Navigate where you want to checkout Crosswalk for Windows, create a directory, navigate into it, and pull the source:

   ```cmdline
   > mkdir crosswalk-src
   > cd crosswalk-src
   > gclient config --name=src/xwalk git://github.com/crosswalk-project/crosswalk.git
   > gclient sync
   ```
   
It may take a while. Download size is >3GB.

## Build Crosswalk

At this point you have two alternatives :

*  Building from the command line
*  Building from Visual Studio

### Command line build
To build from the command line, navigate into crosswalk-src/src and invoke:

```cmdline
> ninja -C out/Release_64 xwalk or ninja -C out/Debug_x64 xwalk
```

### Visual Studio build
To generate solution and project files, navigate into crosswalk-src/src and invoke:

```cmdline
> python xwalk\gyp_xwalk
```

To build in Visual Studio, open xwalk.sln from `crosswalk-src/src/xwalk` and you’re ready to go. Select a target and click Build (for example `xwalk`, or `xwalk_builder`). Please note that xwalk.sln actually has all the Chromium code as a dependency therefore xwalk.sln has something like 600 subprojects which requires a pretty powerful machine with a lot of RAM to be able to handle that correctly. We suggest using the [Funnel extension](http://vsfunnel.com/) which allows you to select which subproject you want to load.
