The manifest file (e.g. manifest.json) is located in your project source and used to specify meta data for your application (title, icon, etc.) as well as how it should behave and present itself.

Crosswalk uses a JSON file based on the [W3C Manifest for Web Application specification](http://w3c.github.io/manifest/). In addition, the Crosswalk manifest extends the W3C manifest with additional fields prefixed with the `xwalk_` keyword.

Below is an example of a basic manifest for the Hello World application. See [Using the manifest](manifest/using_the_manifest.html) for details of how to use a `manifest.json` file with a Crosswalk application.

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
