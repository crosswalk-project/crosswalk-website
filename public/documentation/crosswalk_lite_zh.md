# Crosswalk Lite

Crosswalk Lite，或者称为"Lite", 均表示精简版的Crosswalk运行时，通过移除较不常见的库和特性，压缩APK实现将Crosswalk运行时的容量尽量减少。

## 概述

Crosswalk是基于[开源的Chromium](https://www.chromium.org/Home)进行开发，随着不断添加的新特性，Crosswalk也在不断成长。通过Crosswalk编译会使APK大小增加~20MB，这对于较小的应用而言将会负担很重。这些应用也许并不需要所有的前沿特性。Crosswalk Lite通过难度较大的裁剪来创建一个更小的重发布的运行时，同时保持了大部分用户常使用的特性。

最终，选择哪个版本的Crosswalk取决于你的需求。我们希望了解到你们项目的需求。

### 支持的平台
* Lite目前只支持Android，并且不支持共享模式
* Lite只支持x86和ARM的32位版本。x86_64和ARM64都还未支持。

### 大小和特性选择

下表展示了使用Crosswalk和Crosswalk Lite编译一个web应用时，可能会增加的额外大小。
<table style="text-align:center;border:1px solid black">
<tr><th colspan=2 style="text-align:center;border:1px solid black">Crosswalk</th>
    <th colspan=2 style="color:blue;text-align:center">Crosswalk Lite</th></tr>
<tr><td style="border:1px solid black">APK</td>
    <td style="border:1px solid black">Installed</td>
    <td style="border:1px solid black;color:blue;">APK</td>
	<td style="border:1px solid black;color:blue;">Installed</td></tr>
<tr><td style="border:1px solid black">20MB</td>
     <td style="border:1px solid black">55MB</td>
	 <td style="border:1px solid black;color:blue;">10-15MB</td>
	 <td style="border:1px solid black;color:blue;">40MB</td></tr>
</table>

* Lite大约是通常的Crosswalk大小的一半
* 占用~10MB的大小，使用Lite可以为应用数据留下大约40MB的空间，因为当前Google Play Store对于50MB的限制
* 被移除的特性列表会在项目的wiki页面：[Crosswalk Lite disabled feature list](/documentation/crosswalk_lite/lite_disabled_feature_list.html)中被紧密评估和追踪。我们使用标记来去除一些特征，例如WebRTC, WebDatabase等。
* 使用LZMA压缩最后的库生成一个更小的APK。在应用第一次运行时，APK必须被解压。参见下文中的[运行时表现](#runtime-behavior)。
* 编译选项被设置为对大小最优的。

### 可构性
理想情况下，开发人员可以选取他们需要的选项，为他们的应用创建一个定制的运行时。可是Chromium是一个大型并且相对负责的项目，它没有被设计成模块化，团队重构的能力和保证可靠编译目前都不可行。从长远来看，我们希望改进Chromium, Blink和Crosswalk来实现特性模块化，这样便可以在APK编译阶段打开／关闭某些特性（例如WebRTC）。

### <a class="doc-anchor" id="runtime-behavior"></a> 运行时表现
* 通过Lite编译的应用在第一次启动时会弹出一个显示["Preparing runtime..."](/assets/crosswalk-lite-uncompress-dialog.png)的对话框。
  
### 发布周期

* Lite的发布不会像主线Crosswalk一样频繁，也不会使用相同的频率去rebase最新的Chromium。
* Lite跟主项目不同，不遵循canary/beta/stable发布通道。
* 发行周期为12周，或者根据需求调整。

### QA和验证

* Lite会定期测试。然而，我们的主要精力还是放在主流的Crosswalk中。同时因为跟Crosswalk相比，Lite与Chromium的差异更多，所以可能存在更多意外的bug。
* 假设Lite移除了很多用户较少使用的特性，请在选取使用Lite编译之前确定你的应用并不需要这些特性。
* 如果Lite中做的一些优化被证明是安全的，它们将会被合并到主流的Crosswalk发行版中。

## 怎样参与贡献
为了对Crosswalk Lite做贡献，请遵循页面 https://crosswalk-project.org/contribute/index_zh.html 上的指南。

```cmdline
$ gclient config --name=src/xwalk \
    git://github.com/crosswalk-project/crosswalk.git@origin/crosswalk-lite
```

* 代码审阅和贡献模型均与Crosswalk相同
* Crosswalk Lite在多个分支下被开发，虽然我们将考虑backport相关分支到Crosswalk。
