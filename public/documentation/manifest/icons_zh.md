# 图标

`icons`字段用于规定在视觉上代表应用的一个或者多个图标图形。

一个图标可能被一个任务转换器使用，或者在一个通知区域或者一个设备上的应用列表中。下图展示了在Android平台中，*townxelliot media player*在*应用列表*（左边）和*最近使用应用*（右边）的图标。

![Manifest icon used in "application list" and "recent apps" context on Android](/assets/manifest-icon-contexts.png)

`icons`字段与[W3C manifest标准中的`icon`字段](http://w3c.github.io/manifest/#icons-member)一致。

注意[在嵌入式Crosswalk应用中](/documentation/manifest/using_the_manifest.html#Load-an-application-into-an-embedded-Crosswalk)，`manifest.json`中的`icon`字段不生效。

该字段是一个图标对象列表，每一个对象至少定义了一个图标的URL（通过`src`属性）。图标的`type`属性可以用于设置图标的类型；也可以设置图标的最佳渲染`sizes`和屏幕像素`density`。

例如，这里有一个规定三个图标的简单的Crosswalk manifest：一个在需要64x64像素图标时被使用；一个在需要128x128像素图标时被使用；一个在需要128x128像素图标并且屏幕像素密度为２时被使用：

    {
      "name": "simple_app",
      "icons": [
        {
          "src": "icon_small.png",
          "type": "image/png",
          "sizes": "64x64"
        },
        {
          "src": "icon_large.png",
          "type": "image/png",
          "sizes": "128x128"
        },
        {
          "src": "icon_large_hd.png",
          "type": "image/png",
          "sizes": "128x128",
          "density": "2"
        }
      ],
      "start_url": "index.html",
      "display": "fullscreen",
      "orientation": "landscape"
    }

在`icons`中每个对象的属性值解释都在下文中。

### src

指定图标文件的路径，路径是相对于manifest文件所在目录。

### type

图标的[MIME类型](http://www.iana.org/assignments/media-types/media-types.xhtml)。

### sizes

图片适合的图标大小，用以空格分离的`<width>x<height>`字符串表示。例如：

*   `"sizes": "128x128"`: 这个图片适合被用做一个128x128像素的图标。
*   `"sizes": "128x128 256x256"`: 这个图片适合被用做一个128x128或者256x256像素的图标。

请注意`sizes`属性的值*不是*图片的实际大小（例如，图片文件存储的像素大小）：它指定了这个图片文件所适合的图标集合。这意味着可以使用一个大于或小于图标应有尺寸的图片作为图标。

例如，一个图片文件大小可能为128x128；但是manifest中规定这个图片可以被用于128x128或者256x256像素的图标，这个图片将被按比例放大(2x)以适应需要的图标大小（这可能导致其看起来有些模糊）。

### density

`density`属性用于规定一个图标应该被应用于哪个屏幕密度。外加`sizes`属性，在给定一个屏幕密度后便可以调整哪个图片文件被用于某个特殊的图标。

如果你不熟悉屏幕密度的概念，请参见[屏幕测量页面](/documentation/screens/screen_measurements_zh.html)。

属性值是一个代表图片适合的屏幕密度的浮点数，即每个点的像素（dppx）。例如：

*   `"density": "2"`: 这个图片适合于密度为2dppx的屏幕。
*   `"density": "1.5"`: 这个图片适合于密度为1.5dppx的屏幕。

如果`density`没有设定，则默认为`1.0`。

为了展示它在实际中如何工作，假设你有一个128x128原始像素的图标。现在你将其按每个CSS像素(1dppx)一个物理像素放到一个需要一个128x128像素图标的设备中。原图中的每个原始像素占据设备屏幕中图标的一个像素。

现在假设有一个密度为2dppx的不同设备，也需要一个128x128的图标。如果相同的那个128x128像素的图片文件被用于填充屏幕的那块空白，图片的每个原始像素将占据设备屏幕的4个像素(2x2)。结果就是一张模糊图像，因为相同的单个像素被用于填充屏幕中的4个像素。

为了解决这个，[W3C标准](http://www.whatwg.org/specs/web-apps/current-work/#attr-link-sizes)推荐根据需要的图标大小和屏幕密度选择不同原始大小的图片。其中提供了这个例子：

> "一个50 CSS像素宽的图标，假设它将展示在一个像素密度为每个CSS像素两个设备像素(2x, 192dpi)的设备上，则宽度的原始像素需要为100。

从这里我们可以得出一个计算公式:对于一个给定的图标大小和密度时,需要多大的原始图像文件。

> 原始图像尺寸 = 设备独立像素下的图标尺寸 * 设备屏幕像素密度

对于我们这种情况，我们使用两个图像文件：

*   原始大小为128x128像素的`foo.png`，打算被用于密度为1dppx的屏幕中的一个128x128倾斜图标。
*   原始大小为256x256像素的`foo_hd.png`，打算被用于密度为2dppx的屏幕中的一个128x128倾斜图标。 

并且在manifest中设置`icons`属性如下：

    "icons": [
      {
        "src": "foo.png",
        "type": "image/png",
        "sizes": "128x128",
        "density": "1.0"
      },

      {
        "src": "foo_hd.png",
        "type": "image/png",
        "sizes": "128x128",
        "density": "2.0"
      }
    ]

注意我们没有提到`foo_hd.png`原始像素大小为256x256：我们仅仅说明它适合于一个密度为2dppx的屏幕中的一个128x128像素的图标。

<h2 id="Effect-on-Android-packaging">对Android打包的影响</h2>

不会直接影响Android中的Crosswalk运行时，`icons`字段影响一个应用如何被[`make_apk.py`](/documentation/android/run_on_android.html)打包。`make_apk.py`脚本丢弃了一些在`icon`字段中包含的信息，例如`density`。

如果<code>icons</code>字段包含多个图标大小，`make_apk.py`将根据它们的大小选择对应的图标文件到[Android可绘制资源](http://developer.android.com/guide/topics/resources/providing-resources.html)。

当一个应用被安装，Android将会选取适合目标屏幕分辨率的文件作为主屏幕上，应用列表和其他相关位置上的应用图标。

## 致谢

*townxelliot media player*图标是在[CC BY-SA licence](https://creativecommons.org/licenses/by-sa/3.0/)下，根据[wpzoom.com](http://www.wpzoom.com/)原创的一个图标修改得来。它来源于[Find Icons](http://findicons.com/icon/457729/radio?id=457887)。

为了满足证书上的条款，修改后的图标在这里可用，并且在相同的CC BY-SA许可下发布：

<img alt="Modified radio icon" title="Modified radio icon" src="/assets/radio.png">
