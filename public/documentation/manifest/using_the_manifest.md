# Using the manifest file

This page covers how to use a `manifest.json` file when deploying a Crosswalk application. A manifest can be used in the following "modes":

1.  [To configure how a Crosswalk application is packaged for Android](#Configure-Android-packaging).
2.  [To configure how a Crosswalk application launches on Tizen](#Configure-launch-on-Tizen).
3.  [To load an application into an embedded Crosswalk runtime](#Load-an-application-into-an-embedded-Crosswalk).

<h2 id="Configure-Android-packaging">Configure Android packaging</h2>

A Crosswalk manifest file can be used as the basis for generating an Android package for an application. Instructions on how to do this are given in [the Getting started tutorial](/documentation/getting_started.html).

However, that tutorial only uses a basic manifest, and does not explain in detail how some manifest fields can affect Android packaging. The links below provide some extra information about this:

* `icons`: [effect on Android packaging](/documentation/manifest/icons.html#Effect-on-Android-packaging)

* `permissions`: [effect on Android packaging](/documentation/manifest/permissions.html#Effect-on-Android-packaging)

Note that neither of these fields has an effect if it is included in a `manifest.json` file [loaded into an embedded Crosswalk](#Load-an-application-into-an-embedded-Crosswalk).

<h2 id="Configure-launch-on-Tizen">Configure launch on Tizen</h2>

When you create an xpk package to install with Crosswalk on Tizen, the `manifest.json` file should be included with the other assets in the application, as described in the [Run on Tizen](/documentation/getting_started/run_on_tizen.html) section of the "Getting started" tutorial.

Once installed, Crosswalk on Tizen will use the field values in `manifest.json` to control how the application is launched: which [entry point](/documentation/manifest/entry_points.html) is used, whether the application is [displayed in fullscreen mode](/documentation/manifest/display.html), etc.

<h2 id="Load-an-application-into-an-embedded-Crosswalk">Load an application into an embedded Crosswalk</h2>

The [embedding API](/documentation/apis/embedding_api.html) enables you to embed a Crosswalk runtime in an Android application. [The embedding Crosswalk tutorial](/documentation/embedding_crosswalk.html) explains how to use this API to load an application's main HTML file into an embedded Crosswalk.

However, the API also [exposes methods for loading an application from a manifest file](/documentation/apis/embedding_api.html) as an alternative. The advantage of loading an application from a manifest is that it provides more flexibility than loading an application by URL.

For example, if you decide to change the entry point for your application (e.g. rename `index.html` to `home.html`), you can do this in the manifest without having to change any Java application code. Similarly, if new fields become available for Crosswalk manifests, you can take advantage of those fields in your own manifest without changing any Java code.

To give an idea of how this works, the [application developed for the embedded API tutorial](/documentation/embedding_crosswalk.html) can easily be adapted to use a manifest. Follow the tutorial to the end then modify the project as follows:

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

Note that some of the fields in `manifest.json` which are used when packaging an application for Android are *not* used when loading a manifest into an embedded Crosswalk. See [this section](#Configure-Android-packaging) for details of these fields.
