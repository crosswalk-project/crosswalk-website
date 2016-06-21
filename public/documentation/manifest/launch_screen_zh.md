# 启动画面

启动画面是一个当应用启动后立刻显示的静态用户界面。当应用的“真实”用户界面可以被构建后，也就是说，当应用和任何需要的资源被加载后，启动画面将会被隐藏。

因为启动画面几乎可以让用户立刻浏览应用的内容，所以它可以显著地提高用户对app性能好感并且提升用户体验。

## 完整的规范

[启动画面](https://docs.google.com/a/intel.com/document/d/17PuNuHRTQuREUpaCvj-eEx7uYi2avd-VW-oaMXMpvwo/edit).

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

## xwalk_launch_screen` 属性成员

|属性成员 | 描述|
|---|---|
|`"ready_when"` |  应用就绪状态，即隐藏启动画面的状态。如果没有定义，默认值是"first-paint"`。详情参考下文。|
|`"default"` | 横屏和竖屏下都适用的启动画面。|
|`"landscape"` | 横屏模式下使用启动画面。|
|`"portrait"` | 竖屏模式下使用启动画面。|

### `ready_when`

应用就绪状态 | 前置条件
--- | ---
`"first-paint"` | *第一帧非空画面开始渲染*
`"user-interactive"` | * 第一帧非空画面开始渲染。<br>* DOM和CSSOM构建完成。
`"complete"` | * 第一帧非空画面开始渲染。 <br>* 所有资源完成加载<br> **警示：这可能会消耗很长时间，因为只有所有的资源下载完成后，才会触发此状态。**
`"custom"` | * 第一帧非空画面开始渲染。<br>* ```window.screen.show()``` 函数被调用。

### `default` / `portrait` / `landscape`

这些属性规定了在不同的屏幕方向上的启动画面时使用的图像和背景。如果仅仅设定`portrait`或者`landscape`，启动画面将仅仅出现在设定的方向上（分别为竖屏或者横屏）。

每一个方向属性均可以被含有下列字段的对象定义：

* `background_color`: 启动画面的背景颜色，形式为[16进制](http://www.w3.org/TR/css3-color/#rgb-color)。
* `background_image`: 重复的背景图片。图像的左上角对齐屏幕浏览区域的左上角。详细请参见 [背景图片规范](https://docs.google.com/a/intel.com/document/d/17PuNuHRTQuREUpaCvj-eEx7uYi2avd-VW-oaMXMpvwo/edit?pli=1#heading=h.p51ynj4nuqv7)。
* `image`: 在背景中水平和垂直方向居中的图片。
* `image_border`: 一个边界图片，它被切分成９小块缩小和／或延伸来填满整个视图。详见[图像边界规范](https://docs.google.com/a/intel.com/document/d/17PuNuHRTQuREUpaCvj-eEx7uYi2avd-VW-oaMXMpvwo/edit?pli=1#heading=h.rq1ayw778vp6)。

注意：当用户启动应用时，`background_color`和`background_image`将是第一个被展示的。在`image`属性中规定的图片将在背景上展示。

### 图像和颜色定义

支持的图片格式包括**png**, **bmp**, **jpg**, and **gif**。

`background_image`和`image`属性包含如下格式：

    "<filename1> <density1>, <filename2> <density2>, ..."

其中`<filename*>`是一个相对于manifest的图像文件的路径；`<density*>`是图像将被应用的屏幕的密度（如果仅规定了一个文件`density`是随意的）。例如，对于下列的字段值：

    "background_image": "file1.png 1x, file2.png 2x, file3.png"

并且一个2.5 dppx（每个像素点）的屏幕密度，`file2.png`将会被选取（因为它是低于dppx的最近的值）

对于这个字段值:

    "background_image": "file1.png"

文件`file1.png`将被用于所有屏幕密度的背景图片。
