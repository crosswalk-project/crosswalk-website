# 在windows平台下使用Microsoft Visual Studio开发Crosswalk

我们可以使用Visual Studio IDE来开发、调试和运行Crosswalk。Visual Studio通过提供方便的功能使得贡献更加容易，例如代码补全，先进的debugger等。然而因为Crosswalk是一个相当大的代码库，所以Visual Studio可能需要很多的RAM用于操作（~1.2GB）。


## Visual Studio入门

1.  进入Tools -> Options。打开"Projects and Solutions"，然后"Build and Run"。在新的屏幕上，输入１作为并行项目建立的编号。这是因为整合[Ninja](https://chromium.googlesource.com/chromium/src/+/master/docs/ninja_build.md)和MSVS不支持并行建立。但是这并不意味着不存在多个编译作业。每个目标将会被一一建立（例如`base/`然后`content/`），但是为了建立不同的文件，多个作业将会被创建。最终，你的CPU内核将得到充分利用。

2.  进入File -> Open -> Project/Solution。导航到`crosswalk-src/src/xwalk/`并且打开`xwalk.sln`。你也可以打开另一个工程，例如`content/content_shell.vcxproj`如果你仅仅想建立`content_shell`。如果`xwalk.sln`不存在，请确定你正确地设定了`GYP_GENERATORS`（参见[Building Section](/contribute/building_crosswalk.html)）。

3.  MSVS将会打开和解析所有的依赖。现在你已经准备充分了。这将需要一段时间来首次解析，然后加载所有的解决方案。解析只需要一次，然后便是增量式。

4.  在Build菜单下选择你是否通过进入"Configuration Manager"并选择需要的"Active Solution configuration"来安装Release或者Debug模式。然后"Build" -> "Build Solution"，等待。或者你也可以在Solution Explorer中选取一个目标（例如，`xwalk`或者`xwalk_builder`）并且仅仅建立那个。

5.  `xwalk.exe`可执行应该在`crosswalk-src/src/out/Release`或者`crosswalk-src/src/out/Debug`下。

## 在Visual Studio内部运行和调试Crosswalk

1.  在Solution Explorer (右侧面板)中，你可以点击想要调试的目标。假设你想调试`xwalk`，你可以立刻点击它并且选择"Set as startup project"。请确保你在调试中，否则断点将不会被解决。

2.  现在你需要将正确的参数传递给Crosswalk的可执行文件，这样它便能加载一个页面／应用。在Debug菜单中，选择"xwalk properties"（或者"mytarget properties"），然后在新的窗口，导航到"Configuration Properties"，最后"Debugging"。在"Command Arguments"具体化你想要传递的参数。点击OK。

<a href="/assets/win-10-visual-studio-debug.png"><img src="/assets/win-10-visual-studio-debug.png" style="display: block; margin: 0 auto"/></a>

3.  如果你按下F5或者点击"Debug" -> "Start Debugging"，它将发布一个带有debugger的xwalk。注意它是依附于Browser Process。如果你想依附于Render Process，点击Debug菜单中的"Attach to Process"并点击正确的process。你可以在编辑器里面添加断点，并且观察变量等。非常方便。

<a href="/assets/win-10-visual-studio-debug2.png"><img src="/assets/win-10-visual-studio-debug2.png" style="display: block; margin: 0 auto"/></a>

## 充分利用Visual Studio

关于充分利用Visual Studio创建的一些小技巧：

*  因为Chromium很大，Visual Studio会花费大量的时间用于打开xwalk工程并解析很多在大多数情况下你并不需要的材料（例如，所有的第三方代码）。幸运的是有一款可以用于过滤加载素材的插件。请下载并安装[Funnel](https://visualstudiogallery.msdn.microsoft.com/5396fa4a-d638-471b-ac3d-671ccd2ea369)。现在每次打开xwalk工程时，你可以选择是否部分加载项目并且可以选择哪些需要加载。一旦你开启你的过滤器，Funnel便会记下。它使得更快地加载整个解决方案，并且同时在保证优先使用IDE的所有优势的前提下，降低了内存使用。

*  确保安装`Microsoft Web Essentials`，你便可以在编辑HTML/JS/CSS文件时获取额外的功能。

*  Visual Studio拥有一个功能，即使有错误发生它也会尽力尝试并创建。有时候你不想等到编译结束便得到一个不错的方式跳到错误处。为了在第一个错误处停止编译（有点像ninja的-V1），你可以在下载[StopOnFirstBuildError extension](https://visualstudiogallery.msdn.microsoft.com/91aaa139-5d3c-43a7-b39f-369196a84fa5)。 

*  如果你想为所有的xwalk过程自动附加调试器，请使用[Microsoft Child Process Debbugging Power Tool](https://visualstudiogallery.msdn.microsoft.com/a1141bff-463f-465f-9b6d-d29b7b503d7a)。

*  如果你想加载链接时间，你可以设置`GYP_DEFINES = component=shared_library`。它会为每个crosswalk的子项目创建DLL并且避免xwalk.exe与所有其中的子项目链接。
