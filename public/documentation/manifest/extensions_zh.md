# 扩展程序

`xwalk_extensions`字段用于指定Crosswalk应用中的插件程序。如果您使用crosswalk-app-tools打包Crosswalk应用时，并且需要连同插件一起打包进去，那么需要在manifest文件中指定插件。


一个包含插件的manifet样例：

    {
      "name": "app name",
      "start_url": "index.html",
      "xwalk_extensions": ["xwalk-echo-extension"]
    }

注意，`"xwalk-extensions"`字段的值表示含有插件目录的路径，上文中的`"xwalk-echo-extension"`与`"index.html"`在同一目录下，其中包含的插件程序如下：
```ruby
xwalk-echo-extension.jar
xwalk-echo-extension.js
xwalk-echo-extension.json
```
关于插件程序的更多细节，请参考[crosswalk-samples](https://github.com/crosswalk-project/crosswalk-samples)(在extensions-android目录里)。

如果您的程序中同时包含多个插件，那么`"xwalk_extensions"`的值可以包含多个插件的路径：

    {
      "name": "app name",
      "start_url": "index.html",
      "xwalk_extensions": ["path to extension1", "path to extension2"]
    }

