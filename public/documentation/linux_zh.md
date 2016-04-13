# 针对Linux平台的Crosswalk项目

针对Linux平台的Crosswalk项目使得可以使用web技术来创建Linux桌面应用。它是基于Chromium的内容层以及和Linux桌面IU的整合。

至于其他平台，针对Linux的Crosswalk项目支持先进的Web API，例如WebGL, WebRTC, WebAudio, ServiceWorker, SIMD和Web Manifest。

这个分配包括一个Crosswalk运行时的.deb包和一个为了打包Debian Linux平台的Crosswalk应用的crosswalk-app-tools命令行套件的后端。Crosswalk包在Ubuntu 14.04和[Deepin Linux](http://www.deepin.org/) 2014.2进行了测试。

## 下载并安装针对Linux平台的Crosswalk项目

从 https://download.01.org/crosswalk/releases/crosswalk/linux/deb/ 下载Crosswalk deb包

双击打开deb文件并开始使用系统软件管理器（你可能需要输入你的管理员密码）安装Crosswalk。

或者，你可以直接使用命令行`sudo dpkg -i crosswalk_xxx.deb`安装它。
 
## 运行一个Crosswalk应用

开启一个Crosswalk应用最简单的方法是使用带有[application’s manifest](/documentation/manifest.html)参数的`xwalk`命令：

```
$ xwalk /path/to/manifest.json
```

Crosswalk将解析manifest并且从一个在`start_url`中被规范化的入口点发布应用。Crosswalk同时支持"packaged"和"hosted"应用，意味着`start_url`既可以指向一个在应用程序文件夹中的本地文件，也可以指向一个外部的URL。点击[here](https://crosswalk-project.org/documentation/manifest.html)查看关于Crosswalk manifest的文档。 

如果应用被打包成XPK格式，则可以直接使用xwalk命令发布：

```
$ xwalk /path/to/app.xpk
```

最终，如果一个应用被打包成.deb包（参加下小节），它可以通过`dpkg`安装并从桌面图标发布或者从命令行调用它的名字。

## 打包一个web应用

**打包成.xpk**
为了将一个Crosswalk应用打包成XPK包，遵循 https://github.com/crosswalk-project/crosswalk-website/wiki/Crosswalk-package-management#xpk-package-generator-python-version 上的指导。

XPK包可以直接通过xwalk命令发布（参见上小节）

**打包成.deb**
为了将一个Crosswalk应用打包成一个deb包，你将需要使用[crosswalk-app-tools](https://www.npmjs.com/package/crosswalk-app-tools/)CLI套件和它的debian后台。遵循 https://github.com/crosswalk-project/crosswalk-app-tools-deb 上的指导安装后端，使用命令`crosswalk-app build`来打包应用。

注意：crosswalk-app-tool目前还不支持多种后端。一旦你安装了.deb后端，在它被删除之前你将只能创建.deb的包。你可能想通过复制多个crosswalk-app-tool来支持多个平台。

## 其他注意事项和指导

通过crosswalk-app-tool创建debian包时需要“devscripts”和“debhelper”包。

Crosswalk遵循[W3C manifest specification](http://www.w3.org/TR/appmanifest/)。尤其当在manifest中没有`display`成员被具体规定，Crosswalk将使用`minimal-ui`作为默认值并且显示应用简单的导航控制。为了删除它们，你需要明确地在manifest中规定`“display”: “standalone”`或者`“display”: “fullscreen”`。

当发布一个应用时，Crosswalk将显示错误：

```
[0630/233246:ERROR:browser_main_loop.cc(185)] Running without the SUID sandbox! 
See https://code.google.com/p/chromium/wiki/LinuxSUIDSandboxDevelopment 
for more information on developing with the sandbox on.
```

这个是因为在Crosswalk中，suid sandbox没有被启用（参见 https://crosswalk-project.org/jira/browse/XWALK-3839 ）。它对应用没有影响，这种错误可以被安全地忽略。

如果你想深入了解针对Linux平台的Crosswalk，下面的链接可能会有用：

* 创建Crosswalk: https://crosswalk-project.org/contribute/building_crosswalk.html
* 贡献Crosswalk: https://crosswalk-project.org/contribute/contributing-code.html
* 实现Extensions: https://github.com/crosswalk-project/crosswalk-website/wiki/Crosswalk-Extensions
