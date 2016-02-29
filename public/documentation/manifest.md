# Manifest

The manifest file (e.g. manifest.json) is located in your project source and used to specify meta data for your application (title, icon, etc.) as well as how it should behave and present itself.

The Crosswalk Project uses a JSON file based on the [W3C Manifest for Web Application specification](http://w3c.github.io/manifest/). In addition, the Crosswalk Project manifest extends the W3C manifest with additional fields prefixed with the `xwalk_` keyword.

See [Using the manifest](manifest/using_the_manifest.html) for details of how to use a `manifest.json` file with a Crosswalk Project application.

The minimal manifest.json required to build a simple application is:

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

Below is the default manifest created by `crosswalk-build app` when creating a template. 

Running this command: 

    > crosswalk-app create com.abc.myapp

creates the following manifest.json in your project's root directory:

````
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
````

