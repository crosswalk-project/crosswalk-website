
# 显示

`display`字段用于为应用设定更优的*显示模式*：应用占据多大的可用显示区域，用户界面的其他元素（导航栏，其他工具栏）是否可见。 

当前，下面显示模式可用：

*   `"fullscreen"`

    在这种模式下，应用的显示将充满整个屏幕，Chrome UI将不可见。

    注意规定"fullscreen"显示模式和在应用里中使用的[fullscreen API](http://fullscreen.spec.whatwg.org/)是不同的：前一种情况下，运行时显示应用，使其充满整个显示区域；然而后一种情况下，一个应用可以要求它的一个UI元素填充整个显示区域。

*   `"stanalone"` (默认)

    在这种模式下，应用显示的方式和本地应用相同。上下文中标准的UI元素（例如，设备的状态栏）依然可见。

详见[W3C spec](http://w3c.github.io/manifest/#display-member) 。
