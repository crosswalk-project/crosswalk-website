# Using the manifest file

This page covers how to use a `manifest.json` file when deploying a Crosswalk application. A manifest can be used in the following "modes":

1.  [To configure how a Crosswalk application is packaged for Android](#Configure-Android-packaging).
2.  [To configure how a Crosswalk application launches on Tizen](#Configure-launch-on-Tizen).
3.  [Load an application into an embedded Crosswalk runtime](#Load-an-application-into-an-embedded-Crosswalk).

## Configure Android packaging

A Crosswalk manifest file can be used as the basis for generating an Android package for an application. The basics of how to do this are given in [the Getting started tutorial](#documentation/getting_started).

However, that tutorial only uses a basic manifest, and does not explain in detail how some manifest fields can affect Android packaging. The sections below provide some extra information about this.

### The `icons` (Crosswalk custom) field

*Note: This section only applies to the custom <a href="#documentation/manifest/icons_(custom)"><code>icons</code> field</a>, not to the W3C-compatible `icons` field used in more recent versions of Crosswalk.*

If the <a href="#documentation/manifest/icons_(custom)"><code>icons</code> field</a> contains multiple keys, the `make_apk.py` script will map the corresponding icon files to [Android drawable resources](http://developer.android.com/guide/topics/resources/providing-resources.html) as follows:

|Icon key range...|`make_apk.py` copies the icon file to...|
|:---------------:|----------------------------------------|
|1-36             |`res/drawable/ldpi/icon.<suffix>`       |
|37-72            |`res/drawable/mdpi/icon.<suffix>`       |
|73-95            |`res/drawable/hdpi/icon.<suffix>`       |
|96-119           |`res/drawable/xhdpi/icon.<suffix>`      |
|120-143          |`res/drawable/xxhdpi/icon.<suffix>`     |
|144-167          |`res/drawable/xxxhdpi/icon.<suffix>`    |

where `<suffix>` is the file suffix (`.png`, `.jpg` etc.) of the original file.

For example, the `icons` field in this manifest:

    {
      "name": "app name",
      "description": "a sample description",
      "version": "1.0.0",
      "app": {
        "launch": {
          "local_path": "index.html"
        }
      },
      "icons": {
        "16":  "icon16.png",
        "48":  "icon48.png",
        "128": "icon128.png"
      }
    }

would cause the following resources to be added to the Android application package:

    res/drawable/ldpi/icon.png   (copied from icon16.png)
    res/drawable/hdpi/icon.png   (copied from icon48.png)
    res/drawable/xxhdpi/icon.png (copied from icon128.png)

When the application is installed, Android will use the file appropriate for the target's screen resolution as the application icon in the home screen, app list and other relevant locations.

## Configure launch on Tizen

???

## Load an application into an embedded Crosswalk

The [embedding API](#documentation/apis/embedding_api) enables you to embed a Crosswalk runtime in an Android application. [The embedding Crosswalk tutorial](#documentation/embedding_crosswalk) explains how to use this API to load an application's main HTML file into an embedded Crosswalk.

However, the API also [exposes methods for loading an application from a manifest file](/apis/embeddingapidocs/reference/org/xwalk/core/XWalkView.html) as an alternative. The advantage of loading an application from a manifest is that it provides more flexibility than loading an application by URL.

For example, if you decide to change the entry point for your application (e.g. rename `index.html` to `home.html`), you can do this in the manifest without having to change any Java application code. Similarly, if new fields become available for Crosswalk manifests, you can take advantage of those fields in your own manifest without changing any Java code.

To give an idea of how this would work, the [application developed for the embedded API tutorial](#documentation/embedding_crosswalk) can easily be adapted to use a manifest. Follow the tutorial to the end then modify the project as follows:

1.  Add a `manifest.json` file to the web root of the application (in the case of the embedding API app in the tutorial, this is the `assets/` directory):

        {
	        "name": "XWalkEmbed",
	        "version": "0.0.1",
	        "app": {
		        "launch": {
			        "local_path": "index.html"
		        }
	        }
        }

2.  Modify the `org.crosswalkproject.xwalkembed.MainActivity` class (under `src/`) so it uses the `loadAppFromManifest()` method, rather than the `load()` method:

        package org.crosswalkproject.xwalkembed;

        import org.xwalk.core.XWalkView;
        import android.app.Activity;
        import android.os.Bundle;

        public class MainActivity extends Activity {
	        private XWalkView mXWalkView;

	        @Override
	        protected void onCreate(Bundle savedInstanceState) {
		        super.onCreate(savedInstanceState);
		        setContentView(R.layout.activity_main);
		        mXWalkView = (XWalkView) findViewById(R.id.activity_main);

            // replace this line:
		        //mXWalkView.load("file:///android_asset/index.html", null);

            // with this:
		        mXWalkView.loadAppFromManifest("file:///android_asset/manifest.json", null);
	        }
        }

Now when you run the application, the HTML file specified by the `app.launch.local_path` property in `manifest.json` is being loaded. It's the same `index.html` file as was being loaded previously; but using a manifest has made it easier to  change the application without changing Java code.
