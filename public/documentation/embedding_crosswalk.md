# Embedding the Crosswalk Project

The Crosswalk Project embedding API enables you to embed the Crosswalk Project runtime in an Android application. You can then load a web page (or whole web application) into the embedded runtime, similar to how you might with an Android [WebView](http://developer.android.com/guide/webapps/webview.html).

Using the embedding API is only recommended for cases where you have a substantial amount of Java code in your application, but want to write the UI (or parts of the UI) using web technologies. If you just need a runtime wrapper for a web application, there are two simpler options for deploying to Android with Crosswalk:

*   [Use the default Crosswalk packaging tools](/documentation/android/build_an_application.html) to generate an Android package for your web application.
*   [Use Cordova with Crosswalk](/documentation/cordova/cordova_4.html): that way, you get de facto standard device APIs, as well as advanced web APIs, but can still mostly build your application using web technologies.

If you do decide to use the embedding API, follow the instructions below which explain how to use it.

## Creating an application with the embedding API

In this tutorial, you'll learn how to create an Android application with an embedded Crosswalk webview.

To follow the tutorial, you'll need to be familiar with Android development. Because Android applications are typically developed using [ADT](http://developer.android.com/tools/sdk/eclipse-adt.html), this tutorial also uses that tool; so familiarity with that will be useful.

The tutorial steps were tested on Linux (Fedora 20), but should be adaptable to other platforms and operating systems (e.g. Windows).

**By the end of the tutorial**, you will be able to develop Android applications which embed the Crosswalk runtime.

### Set up the host and target

Before you use the embedding API, ensure that you have [set up your host environment for Android development](/documentation/android/system_setup.html).

You will also need to set up an Android target to deploy the application to, as described on the [Android target setup](/documentation/android/android_target_setup.html) page.

As this tutorial uses ADT, ensure that you have [installed the necessary ADT components](http://developer.android.com/tools/sdk/eclipse-adt.html) for your host.

### Download the Crosswalk webview bundle

<button onclick="location.href = '/documentation/downloads.html';">Downloads</button>

Download the Crosswalk webview bundle to your development system. The webview bundle contains the libraries and supporting tools for embedding Crosswalk in an application.  Note that the webview is architecture specific, so you will need to build multiple version for ARM, x86, and 64-bit devices.

Unzip the downloaded `.zip` file.

### Import the Crosswalk webview project into ADT

The next step is to create a project in ADT by importing the unpacked Crosswalk webview bundle. Your own application projects can then reference this project to build against the Crosswalk embedding API.

To set up this project:

1.  Open ADT.

2.  Select *File* > *New* > *Project...*, then *Android* > *Android Project From Existing Code*.

3.  Set *Root Directory* to the path of the `crosswalk-webview/` directory you extracted.

4.  Click *Finish*. The **crosswalk-webview** project will now be visible in the *Package Explorer*.

### Create an Android application with ADT

Next, create an Android application which will use the Crosswalk embedding API (still in ADT):

<ol>
  <li>
    <p>Select <em>File</em> &gt; <em>New</em> &gt; <em>Android Application Project</em>.</p>

    <p>Fill in the <em>New Android Application</em> dialog as follows:</p>

    <ul>
      <li><em>Application Name:</em> <strong>XWalkEmbed</strong></li>
      <li><em>Project Name:</em> <strong>XWalkEmbed</strong></li>
      <li><em>Package Name:</em> <strong>org.crosswalkproject.xwalkembed</strong></li>
      <li><em>Minimum Required SDK:</em> <strong>API 14</strong> (the minimum version supported by Crosswalk)</li>
      <li><em>Target SDK:</em> <strong>API 19</strong> (or whatever version you have)</li>
      <li><em>Compile With:</em> <strong>API 19</strong></li>
      <li><em>Theme:</em> <strong>None</strong></li>
    </ul>

    <p>It should look like this:</p>

    <img src="/assets/embedding-api-app1.png">

    <p>Click <em>Next</em>.</p>
  </li>

  <li>
    <p>In the <em>Configure Project</em> panel, set the options as follows:</p>

    <ul>
      <li>Untick <em>Create Custom Launcher Icon</em>.</li>
      <li>Tick <em>Create activity</em>.</li>
      <li>Untick <em>Mark this project as a library</em>.</li>
      <li>Either create the project in your workspace or somewhere else on your filesystem. Add to working sets if you want to.</li>
    </ul>

    <p>The result should look like this:</p>

    <img src="/assets/embedding-api-app2.png">

    <p>Click <em>Next</em>.</p>
  </li>

  <li>
    <p>In the <em>Create Activity</em> panel, select <em>Create Activity</em> and <em>Blank Activity</em>:</p>

    <img src="/assets/embedding-api-app3.png">

    <p>Click <em>Next</em>.</p>
  </li>

  <li>
    <p>Fill in the <em>Blank Activity</em> panel as follows:</p>

    <ul>
      <li><em>Activity Name:</em> <strong>MainActivity</strong></li>
      <li><em>Layout Name:</em> <strong>activity_main</strong></li>
      <li><em>Fragment Layout Name:</em> <strong>fragment_main</strong></li>
      <li><em>Navigation Type:</em> <strong>none</strong></li>
    </ul>

    <p>It should look like this:</p>

    <img src="/assets/embedding-api-app4.png">

    <p>Click <em>Finish</em>.</p>
  </li>
</ol>

Your project is now ready to work on.

### Reference Crosswalk webview from your project

The next step is to add a reference for the Crosswalk webview project to your application project.

Do the following (in ADT):

1.  Right-click on your project in the *Package Explorer* and select *Properties*.

2.  Select *Android*.

3.  In the *Library* tab, click *Add*. Select the **crosswalk-webview-${XWALK-BETA-ANDROID-X86}-x86** project and click OK.

Click Ok to accept.

Your project should now be linked to the Crosswalk webview project.

### <a class="doc-anchor" id="Add-code-to-integrate-the-webview"></a>Add code to integrate the webview

1.  Crosswalk requires a few permissions to be enabled on Android. To enable these, modify the `AndroidManifest.xml` file, adding permission lines before the `<application>` element. The minimal permissions required for the Crosswalk WebView to render pages are:
    ```
    <uses-permission android:name="android.permission.ACCESS_NETWORK_STATE" />
    <uses-permission android:name="android.permission.INTERNET" />
    <uses-permission android:name="android.permission.ACCESS_WIFI_STATE" />
    ```
    
    Depending on the features of your app, you may need to request additional permissions. It is considered good security practice on Android to request only those permissions that your app actually needs.
    
    *Accessing Location Information*
    ```
    <uses-permission android:name="android.permission.ACCESS_FINE_LOCATION" />
    ```
    
    *Accessing Camera, Video and Microphone*
    ```
    <uses-permission android:name="android.permission.CAMERA" />
    <uses-permission android:name="android.permission.MODIFY_AUDIO_SETTINGS" />
    <uses-permission android:name="android.permission.RECORD_AUDIO" />
    ```
    
    *Writing data to SD Card*
    ```
    <uses-permission android:name="android.permission.WRITE_EXTERNAL_STORAGE" />
    ```
    
    *Keeping Screen On*
    ```
    <uses-permission android:name="android.permission.WAKE_LOCK" />
    ```

2.  When the application was generated, some default layout resources were added to the project. Replace the content of the main layout resource file, `res/layout/activity_main.xml`, with this:

    ```
    <org.xwalk.core.XWalkView android:id="@+id/activity_main"
      xmlns:android="http://schemas.android.com/apk/res/android"
      android:layout_width="fill_parent"
      android:layout_height="fill_parent">
    </org.xwalk.core.XWalkView>
    ```

    This replaces the default view for the application with a single `XWalkView` (Crosswalk webview) resource.

    You can remove the other default file, `res/layout/fragment_main.xml`, as you won't be needing it.

3.  Edit the `MainActivity` class (find it via the *Package Explorer*, in `src/org.crosswalkproject.xwalkembed/MainActivity.java`). Replace its content with this:

    ```
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
        mXWalkView.load("http://crosswalk-project.org/", null);
      }
    }
    ```

    `R.layout.activity_main` refers to the `activity_main` layout component you defined above: the webview part of the Crosswalk runtime which is exposed by the embedding API. The activity's main content view is set to this webview.

    The code casts the layout component to an `XWalkView`, so its methods for loading web resources are accessible. In this case, the `load()` method is called to load the Crosswalk project website and render it into the webview component. Note that a `loadAppFromManifest()` method is also available, which can load a Crosswalk application via a manifest file. See [the embedding API docs](/apis/embeddingapidocs/reference/org/xwalk/core/XWalkView.html) for full details.

You should now be able to run your application, as per usual: right click on the project in the *Package Explorer*, then select *Run As* > *Android Application*.

### Load an HTML page into the Crosswalk webview

In the previous section, you loaded a remote website into the webview, demonstrating a simple way to "wrap" an Android application around a website. However, you may want to distribute a web application as part of an Android application, rather than host it on a website. In this case, you need to load a local resource into the Crosswalk webview instead.

To include local web resources, you need to place the web assets inside the Android application and distribute them as part of the application package (`.apk` file).

Follow the steps below to add some web assets and bundle them with your application package:

1.  Create an `assets/` directory. This is the standard location for Android application assets, and is a good location for web assets.

2.  Add an `index.html` file to the `assets/` directory with this content:

    ```
    <!DOCTYPE html>
    <html>
      <head>
        <meta name="viewport"
              content="width=device-width, initial-scale=1.0">
        <meta charset="utf-8">
        <title>simple</title>
      </head>
      <body>
        <p>hello world</p>
      </body>
    </html>
    ```

3.  Edit the `MainActivity` class (`src/org.crosswalkproject.xwalkembed/MainActivity.java`):

    ```
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

        // this loads a file from the assets/ directory
        mXWalkView.load("file:///android_asset/index.html", null);
      }
    }
    ```

    The main difference here is that the `load()` function is passed an asset URL, rather than an absolute web-based URL:

    ```
    mXWalkView.load("file:///android_asset/index.html", null);
    ```

    The URL is just the prefix "file:///android_asset/", followed by the path to the file you want to load, relative to the `assets/` directory.

    Once the main HTML page for the application is loaded, any URLs inside that page will resolve as per a normal web application, treating the `assets/` directory as the application root. For example, if you had a second HTML page called `page2.html` in the `assets/` directory, you could link to it with a standard hyperlink like:

    ```
    <a href="page2.html">Page 2</a>
    ```

    Similarly, any CSS or media files (audio, video) can be added inside the `assets/` directory and referred to as per usual, for example:

    ```
    <!--
    HTML5 audio element in index.html referring to a file
    at assets/audio/mytrack.ogg
    -->
    <audio src="audio/mytrack.ogg">

    /*
    CSS declaration in index.html referring to an
    image in assets/images/myimage.png
    */
    .myclass {
      background-image: url(images/myimage.png);
    }
    ```

### Enable Proguard to compress apk size (Optional)
ProGuard is a free Java class file shrinker, optimizer, obfuscator and pre-verifier. Those operations are optional when generating your final apk. If you have developed your own Proguard config file along with your Java code, you might want to add some Proguard rules to compress the size of the Crosswalk library: <a href="/documentation/samples/proguard-xwalk.txt">Proguard rules for crosswalk example</a>.

The purpose of using Proguard on the Crosswalk library is to shrink the size not for anti-decompilation consideration. Note that there are a lot of reflections in Crosswalk code, many of which directly reference class names as strings. Thus the shrink and obfuscate operations mainly affect the chromium areas. 

<h3 id="Debugging">Debugging</h3>

To enable debugging of the web application running in an embedded Crosswalk webview, modify the `MainActivity.java` file to look like this:

    package org.crosswalkproject.xwalkembed;

    import org.xwalk.core.XWalkPreferences;
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

        // turn on debugging
        XWalkPreferences.setValue(XWalkPreferences.REMOTE_DEBUGGING, true);

        mXWalkView.load("file:///android_asset/index.html", null);
      }
    }

The key line is:

    XWalkPreferences.setValue(XWalkPreferences.REMOTE_DEBUGGING, true);

`XWalkPreferences.setValue()` sets global preferences for Crosswalk; in this case, you are turning on the debugging flag.

Now install and run your application as usual. Then, to perform remote debugging, follow [these instructions](/documentation/android/android_remote_debugging.html).

## Further information

Further resources about working with the embedding API are listed on the [embedding API docs page](/documentation/apis/embedding_api.html).
