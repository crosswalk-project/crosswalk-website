# Manifest

manifest文件（例如manifest.json）位于你的项目源代码中,它用于具体规定你应用中的元数据（标题，图标等）和如何体现作用。

Crosswalk项目使用了一个基于[专门针对Web应用的W3C](http://w3c.github.io/manifest/)的json文件。除此之外，Crosswalk项目的manifest通过添加以`xwalk_` 关键字`为前缀的字段对W3C manifest进行扩展。

关于在Corsswalk应用中如何使用`manifest.json`文件的详细信息请参见[使用manifest](manifest/using_the_manifest_zh.html)。

满足编译一个简单应用所需要的最简单的manifest.json文件如下：

```
{
    "name": "My App Name",
    "start_url": "index.html",
	"xwalk_app_version": "0.1",
	"xwalk_package_id": "com.abc.myapp",
	"icons": [
	  {
		"src": "icon.png",
		"sizes": "72x72"
      }
	]
}
```

当创建模板时，由`crosswalk-build app`生成的默认manifest如下。

运行这个命令：

```cmdline
> crosswalk-app create com.abc.myapp
```

在你的项目根目录下创建如下manifest.json文件：

```
{
  "name": "myapp",
  "short_name": "myapp",
  "background_color": "#ffffff",
  "display": "standalone",
  "orientation": "any",
  "start_url": "index.html",
  "xwalk_app_version": "0.1",
  "xwalk_command_line": "",
  "xwalk_package_id": "com.abc.myapp",
  "xwalk_target_platforms": ["android"],
  "xwalk_android_animatable_view": true,
  "xwalk_android_keep_screen_on": false,
  "xwalk_android_permissions": [
    "ACCESS_NETWORK_STATE",
    "ACCESS_WIFI_STATE",
    "INTERNET"
  ],
  "xwalk_windows_update_id": "73148800-8517-7725-5290-324729867281",
  "icons": [
    {
      "src": "icon.png",
      "sizes": "72x72"
    }
  ]
}
```

