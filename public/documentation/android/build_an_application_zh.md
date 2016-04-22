# 编译Crosswalk应用

Crosswalk是HTML5应用的引擎。这意味着可以使用Crosswalk来运行现有的HTML5应用。使用下面任何一种方式去创建和打包一个web应用。

* [使用已有的web工程](#existing)
* [使用crosswalk-app create &lt;package id&gt;创建一个简单的工程](#create)
* [手动创建一个简单的web工程](#manual)

## <a class="doc-anchor" id="existing"></a>使用已有的web工程
如果您已经有一个工程，只需添加一个图标和manifest.json文件。

<img src="/assets/existing-project.png" style="border:solid black 1px; display: block; margin: 0 auto"/>

关于manifest的描述，参照下面[3.2](#manifest)。

添加过图标和manifest之后, 您就可以[编译应用](#build-application)。

## <a class="doc-anchor" id="create"></a>使用crosswalk-app create创建一个简单的工程
crosswalk-app tool可以为您的应用创建一个初始的模板：
```
> crosswalk-app create <package id>
```
`package-id`是用来标记应用的第三方网络域名，例如com.abc.myappname。关于格式的细节，参见[Android package documentation](http://developer.android.com/guide/topics/manifest/manifest-element.html#package).

上面的命令将会创建具有下面目录结构的工程：

<img src="/assets/create-project.png" style="border:solid black 1px; display: block; margin: 0 auto"/>

创建完成之后，您就可以[编译程序](#build-application)。

## <a class="doc-anchor" id="manual"></a>手动创建一个简单的web工程
简单起见，本教程使用一个极简单的Crosswalk应用：一个HTML文件。

1.  首先，创建一个`xwalk-simple`目录：

        > mkdir xwalk-simple/
        > cd xwalk-simple/

2.  其次，拷贝图标文件到上面创建的目录。图标文件是作为应用的图标显示的，您可以使用下面的图片：

    <img src="/assets/cw-app-icon.png" style="width: 128px; margin:0 auto;display:block;" />

    右键点击图标并选择 <em>Save Image As...</em> (或者浏览器提供相关的保存选项)。 作为`icon.png`保存到`xwalk-simple`目录。 (图片出自Crosswalk源码并且遵循[BSD许可证](https://github.com/crosswalk-project/crosswalk/blob/master/LICENSE).)

    如果您有自己喜欢的图片，那么可以放弃上面的Crosswalk图片，拷贝自己的图片到`xwalk-simple`目录下面。图片应该为128像素的正方形。

3.  在`xwalk-simple`目录下创建两个文件(可以用任何文本编辑器创建，比如Windows上的Notepad,Ubuntu上面的gedit)：

    1. `index.html`

       这是一个简单的HTML文件，表示应用程序的入口。简单起见，这个文件没有用任何CSS或者JavaScript文件。

       内容如下：

           <!DOCTYPE html>
           <html>
			  <head>
			    <meta name="viewport"
					  content="width=device-width, initial-scale=1.0">
			    <meta charset="utf-8">
			    <title>Crosswalk Simple</title>
			  </head>
			  <body>
			    <p>Hello World! Crosswalk is great.</p>
			  </body>
		   </html>

    2. <a class="doc-anchor" id="manifest"></a>`manifest.json`

       它包含了应用的元数据（参照上面）。manifest.json最少应该包含下面的内容：
	   
		   {
			  "name": "Crosswalk Simple",
			  "xwalk_app_version": "0.1",
			  "start_url": "index.html",
			  "xwalk_package_id": "com.xwalk.simple",
			  "icons": [
			    {
					"src": "icon.png",
					"sizes": "72x72"
				}
			  ]
		   }

    详情参见[manifest说明](/documentation/manifest_zh.html)。

## <a class="doc-anchor" id="build-application"></a>编译应用
在应用含有图标和manifest文件之后，它就可以用Crosswalk打包了。 

    > crosswalk-pkg <含有manifest文件的目录>

这个命令用于下载/导入Crosswalk，将目录下面的文件打包成APK文件。默认情况下，它将创建同时包含x86和arm两个平台下的APK(默认推荐)，这两个APK是[调试版](android_remote_debugging_zh.html), [嵌入模式](/documentation/shared_mode_zh.html), [32位](android_64bit_zh.html)的。当然也可以创建64位的APK：可以参考[Crosswalk-app-tools页面](/documentation/crosswalk-app-tools_zh.html)“常见用法”章节，或查看帮助命令：

    > crosswalk-pkg help

现在，您的应用已经迫不及待地准备运行了。
