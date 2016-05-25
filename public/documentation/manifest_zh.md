# Manifest

manifest文件（例如manifest.json）位于你的项目源代码中并且用于具体规定你应用中的元数据（标题，图标等）以及它如何表现和展示自己。

Crosswalk项目使用了一个基于[专门针对Web应用的W3C](http://w3c.github.io/manifest/)的json文件。除此之外，Crosswalk项目的manifest通过添加以`xwalk_` keyword`为前缀的字段对W3C manifest进行了扩展。

关于在Corsswalk应用中如何使用`manifest.json`文件的详细信息请参见[使用manifest](manifest/using_the_manifest_zh.html)。

为了编译一个简单应用所需要的最基本的manifest.json文件如下：

````
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
````

下列是当创建模板时，由`crosswalk-build应用`生成的默认manifest。

运行这个命令：

　　> crosswalk-app create com.abc.myapp

在你的项目根目录下创建下列manifest.json文件：

````
	{
	  "name": "Hello World",
	  "icons": [
	    {
	      "src": "images/icon192.png",
	      "sizes": "192x192",
	      "type": "image/png",
	      "density": "4.0"
	    },
	    {
	      "src": "images/icon144.png",
	      "sizes": "144x144",
	      "type": "image/png",
	      "density": "3.0"
	    },
	    {
	      "src": "images/icon96.png",
	      "sizes": "96x96",
	      "type": "image/png",
	      "density": "2.0"
	    },
	    {
	      "src": "images/icon72.png",
	      "sizes": "72x72",
	      "type": "image/png",
	      "density": "1.5"
	    },
	    {
	      "src": "images/icon48.png",
	      "sizes": "48x48",
	      "type": "image/png",
	      "density": "1.0"
	    },
	    {
	      "src": "images/icon36.png",
	      "sizes": "36x36",
	      "type": "image/png",
	      "density": "0.75"
	    }
	  ],
	  "start_url": "index.html",
	  "display": "standalone",
	  "orientation": "any"
	}
````　
