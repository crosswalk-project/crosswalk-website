# 启动画面

启动画面是一个当应用启动后立刻展示的静态用户接口。当应用的“真实”用户接口可以被构建后，启动画面将会被隐藏：例如，当应用和任何需要的资源被加载后。

因为启动画面几乎立刻可以让用户浏览应用的内容，它可以显著地提高他们对应用性能的感知并且提高用户体验。

## 完整的规范

[Interstitial launch screens](https://docs.google.com/a/intel.com/document/d/17PuNuHRTQuREUpaCvj-eEx7uYi2avd-VW-oaMXMpvwo/edit).

## manifest属性的定义

在manifest中启动画面的属性被称为`xwalk_launch_screen`并且定义如下：

    "xwalk_launch_screen": {
      "ready_when": "first-paint | complete | custom",
      "default | portrait | landscape": {
         "background_color": "#ff0000",
         "background_image": "bgfoo.png [1x, bgfoo-2x.png 2x]",
         "image": "foo.png [1x, foo-2x.png 2x]",
         "image_border": "30px [30px 30px 30px] [repeat | stretch | round] [repeat | stretch | round]"
       }
    }

例如：

    "xwalk_launch_screen": {
      "ready_when": "custom",
      "portrait": {
         "background_color": "#ff0000",
         "background_image": "bgfoo.png 1x, bgfoo-2x.png 2x",
         "image": "foo.png 1x, foo-2x.png 2x",
         "image_border": "30px 40px stretch",
       }
    }

## xwalk_launch_screen` 成员

|Member | Description|
|---|---|
|`"ready_when"` |  The application readiness state at which to hide the launch screen. If undefined, defaults to `"first-paint"`. See the next section for more details.|
|`"default"` | The launch screen to use for both landscape and portrait mode.|
|`"landscape"` | The launch screen to use for landscape mode.|
|`"portrait"` | The launch screen to use for portrait mode.|

### `ready_when`

Application readiness state | Preconditions
--- | ---
`"first-paint"` | * The first visually non-empty paint has occurred.
`"user-interactive"` | * The first visually non-empty paint has occurred.<br>* The DOM and CSSOM have been constructed.
`"complete"` | * The first visually non-empty paint has occurred. <br>* All the resources have been loaded.<br> **WARNING: This may take a long time, as it only triggers after all sub-resources have been downloaded.**
`"custom"` | * The first visually non-empty paint has occurred. <br>* The ```window.screen.show()``` method was called.

### `default` / `portrait` / `landscape`

这些属性规定了在不同的屏幕方向上的启动画面时使用的图像和背景。如果仅仅`portrait`或者`landscape`被设定，启动画面将仅仅出现在对应的方向上（分别为横向或者纵向）。

这些方向属性中的每一个均可以被包含下列属性的对象定义：

* `background_color`: 启动画面的背景颜色，在[hexadecimal notation](http://www.w3.org/TR/css3-color/#rgb-color)。
* `background_image`: 重复的背景图片。图像的左上角对齐屏幕浏览区域的左上角。详细请参见 [background_image spec](https://docs.google.com/a/intel.com/document/d/17PuNuHRTQuREUpaCvj-eEx7uYi2avd-VW-oaMXMpvwo/edit?pli=1#heading=h.p51ynj4nuqv7)。
* `image`: 在背景中水平和垂直方向居中的图片。
* `image_border`: 一个边界图片，分成９小块与中间边界块扩展和／或延伸来填满整个视图。详见[Image Border spec](https://docs.google.com/a/intel.com/document/d/17PuNuHRTQuREUpaCvj-eEx7uYi2avd-VW-oaMXMpvwo/edit?pli=1#heading=h.rq1ayw778vp6)。

注意：当用户启动应用时，`background_color`和`background_image`将是第一个被展示的。在`image`属性中规定的图片将在背景上展示。

### 图像和颜色定义

支持的图片格式包括**png**, **bmp**, **jpg**, and **gif**。

`background_image`和`image`属性包含如下格式：

    "<filename1> <density1>, <filename2> <density2>, ..."

其中`<filename*>`是一个相对于manifest的指向图像文件的路径；`<density*>`是图像将被应用的屏幕的密度（如果仅规定了一个文件`density`是随意的）。例如，对于下列的字段值：

    "background_image": "file1.png 1x, file2.png 2x, file3.png"

并且一个2.5 dppx（每个像素点）的屏幕密度，`file2.png`将会被选取（因为它是低于dppx的最近的值）

对于这个字段值:

    "background_image": "file1.png"

文件`file1.png`将被用于所有屏幕密度的背景图片。
