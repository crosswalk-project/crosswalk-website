# Manifest

manifest文件（例如manifest.json）位于你的项目源代码中并且用于具体规定你的应用中的元数据（标题，图标等）以及它如何表现和展示自己。

Crosswalk项目使用了一个基于[W3C Manifest for Web Application specification](http://w3c.github.io/manifest/)的json文件。除此之外，Crosswalk项目的manifest通过添加以`xwalk_` keyword`为前缀的字段对W3C manifest进行了扩展。

下文是一个关于hello world应用的基础manifest文件示例。关于如何在Crosswalk项目应用中使用`manifest.json`文件的细节请参见[Using the manifest](manifest/using_the_manifest.html)。

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
