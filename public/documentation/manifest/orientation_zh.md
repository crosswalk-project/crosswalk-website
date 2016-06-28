# 方向

`orientation`字段用于设定在Crosswalk上运行的应用的默认方向。即使物理设备在不同的方向上旋转，应用将仍然能保持在manifest中设置的方向。然而，在运行时方向仍然可以改变；例如，通过使用[屏幕方向API](http://www.w3.org/TR/screen-orientation/)。


一个将默认方向设置为纵向的manifet样例：

    {
      "name": "app name",
      "start_url": "index.html",
      "orientation": "portrait"
    }

另一个样例，将默认方向设置为横向：

    {
      "name": "app name",
      "start_url": "index.html",
      "orientation": "landscape"
    }

其他`orientation`字段可以使用的值：例如，在Crosswalk Android中，`"portrait-primary"`表示对于用户的“自然”纵向（例如纵向的应用，其中应用的顶部位于设备屏幕的*顶部*）；然而`"portrait-secondary"`表示“反向”纵向（例如纵向的应用，但是应用的顶部却位于设备屏幕的*底部*）。同样，`"landscape-primary"`用于设定“自然”横向，`"landscape-secondary"`设定“反向”横向。所有的有效值均定义在[关于画面方向的具体规范](https://w3c.github.io/screen-orientation/#idl-def-OrientationLockType)中。
