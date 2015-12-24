# Developing Crosswalk on Windows with Microsoft Visual Studio

It is possible to use Visual Studio IDE to develop, debug and run Crosswalk. Visual Studio can provide great features to make it easier to contribute such as code completion, advanced debugger and so forth. However Crosswalk is a pretty big project with a rather big codebase and Visual Studio can take a big amount of RAM to be able to operate (~1-1.2Gb).

## Getting started with Visual Studio

1.  Navigate to "Tools" menu then "Options". Open "Projects and Solutions" and then "Build and Run". In the new screen enter 1 as the number of parallel project builds. This is needed because ninja, thus it's integration with MSVS doesn't really support being built concurrently. However that doesn't mean that you won't have multiple jobs of compilation. Each target is going to be build one by one (e.g. base/ then content/) but multiple jobs are going to be created to build the various files. End of the day your CPU cores are going to be fully utilised.

2.  Go to File -> Open -> Project/Solution. Navigate to crosswalk-src/src/xwalk/ and opens xwalk.sln. You can open another project such as content/content_shell.vcxproj if you want to build content_shell only. If xwalk.sln is missing make sure that you set correctly the GYP_GENERATORS (See Building Section).

3.  MSVS will then open and parse all the dependencies. You're ready to go. It's going to take a while to first parse and load all the solutions. Parsing is done once, then it's incremental.

4.  In the Build menu you can select if you want to build in Release or Debug by going into the "Configuration Manager" and select the "Active Solution configuration" you want. Then "Build" -> "Build Solution" and then wait. Alternatively you can also select a target (for example, xwalk or xwalk_builder) in the Solution Explorer and build only that one.

5.  xwalk.exe executable should be in crosswalk-src/src/out/Release or crosswalk-src/src/out/Debug

## Running and debugging inside Visual Studio

1.  In the Solution Explorer (right hand side panel) you can click on which target you want to debug. Say you want to debug xwalk, you can right click on it and select "Set as startup project". Make sure you built in Debug otherwise breakpoints are not going to be resolved.

2.  Now you need to make sure to pass the arguments to Crosswalk executable so it can load a page/application. In the Debug menu, select "xwalk properties" (or mytarget properties), then in the new window, navigate to "Configuration Properties" then "Debugging" then in the "Command Arguments" specify what you want to pass. Click ok.

<a href="/assets/win-10-visual-studio-debug.png"><img src="/assets/win-10-visual-studio-debug.png" style="display: block; margin: 0 auto"/></a>

3.  If you press F5 or click "Debug" menu -> "Start Debugging" it will launch xwalk with a debugger attached. Note that it's attaching to the Browser Process. If you want to attach to the Render Process, click "Attach to Process" in the Debug menu and pick the right process. You can add breakpoints by clicking in the editor and look up variables and so forth. Very convenient.

<a href="/assets/win-10-visual-studio-debug2.png"><img src="/assets/win-10-visual-studio-debug2.png" style="display: block; margin: 0 auto"/></a>

## Getting the most out of Visual Studio

*  As Chromium is big, Visual Studio spend lot of time opening the xwalk project and it parses a lot of stuff that in most cases you would not need (e.g. all the third_party code). It exists a plugin which allows you to filter what is going to be loaded. Download and install [Funnel](https://visualstudiogallery.msdn.microsoft.com/5396fa4a-d638-471b-ac3d-671ccd2ea369). Now everytime you're going to open say xwalk project you're going to be presented a dialog to choose whether you want to partially load or not the project (and you can select to load what you need, say base/, content/, third_party/WebKit). Note that you setup your filter once and then Funnel remembers it. It makes loading the entire solution way faster and lower the memory usage while keeping all the benefits of using an IDE in the first place.

*  Make sure to install the Microsoft Web Essentials so you get extra features when editing the HTML/JS/CSS files.

*  Visual Studio also have a "feature" to try to build as much as it can even if an error occurred and sometimes you don't want to wait the compilation to finish to get a nice way to jump to the error. You can download an [extension](https://visualstudiogallery.msdn.microsoft.com/91aaa139-5d3c-43a7-b39f-369196a84fa5) to stop the build at the first error if you need to (something like -V1 of ninja).

*  If you want to automatically attach the debugger for all the xwalk processes (browser process, render process...) please use [Microsoft Child Process Debbugging Power Tool](https://visualstudiogallery.msdn.microsoft.com/a1141bff-463f-465f-9b6d-d29b7b503d7a).

*  If you want to speed-up link time you can set your GYP_DEFINES to be GYP_DEFINES = component=shared_library. It will create DLLs for each of the crosswalk subprojects avoiding linking xwalk.exe with all the subprojects in it. 