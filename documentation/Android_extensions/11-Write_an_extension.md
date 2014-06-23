# Write an extension

Crosswalk extensions are written in Java, and have access to the standard [Android APIs](http://developer.android.com/reference/packages.html).

In addition, because Crosswalk on Android acts as an [Activity](http://developer.android.com/reference/android/app/Activity.html) and has access to a [Context](http://developer.android.com/reference/android/content/Context.html), an extension can make also use of those objects. This is important in the case of the audio player in this tutorial, as a `Context` is used to get access to a `ContentResolver` instance: both classes are part of the API that Android provides for accessing media on a device.

Even with the extensive Android APIs available to you, there may be some cases where you need to use other third-party libraries in your extension. This tutorial explains how to do this, too, with an example of using [Gson](https://code.google.com/p/google-gson/) to read and write JSON. (While the Android API has some JSON capabilities, Gson is much neater and easier to use.)

## What does the extension do?

Here's an overview of how this extension works:

<ol>
  <li>
  When the Crosswalk application (with extension) starts on the Android device, an instance of the <code>AudioFs</code> extension is instantiated. At construction time, the extension gets a reference to the <code>Context</code> associated with the running application's process, so it can access a <code>ContentResolver</code> later.
  </li>

  <li>
    <p>The web part of the application calls one of the methods exported by the JavaScript part of the extension; either <code>audioFs.listFilesAsync()</code> (asynchronous) or <code>audioFs.listFiles()</code> (synchronous).</p>

    <p>In both cases, the JavaScript part of the extension sends a unique request ID to the Java part. This ID is used to ensure that the response is married up to the correct callback when the extension returns its results.</p>
  </li>

  <li>

    <p>Inside the <code>AudioFs</code> instance (Java), the <code>listFiles()</code> method is invoked. Note that this method receives the request ID (from the JavaScript side).</p>

    <p><code>listFiles()</code> does the following:</p>

    <ol>
      <li>
      Gets a <code>ContentResolver</code> from the application's context (via the reference created at construction time). A content resolver enables you to access content (like audio files) on the device, and returns the results in table-like structure. You can then get a cursor on that table (c.f. a database result cursor) to loop through the rows, retrieving the content of each field for the row.
      </li>

      <li>
      Queries the <code>ContentResolver</code> for audio files on the device, retrieving the columns for data, title and artist. These are built-in fields provided by Android which are filled for audio files; the data column corresponds to the absolute path to the file on the device filesystem; title and artist are hopefully self-explanatory for audio files. The query returns a <code>Cursor</code> object you can use to iterate the resultset.
      </li>

      <li>
      Uses the cursor to loop through the audio files, creating a <code>FileInfo</code> object for each, setting its <code>uri</code>, <code>title</code> and <code>artist</code> instance variables. The <code>FileInfo</code> objects are added to a list. These interim objects are used to make it easier to serialize the result to a JSON string (it's difficult to serialize a <code>Cursor</code> object directly).
      </li>
    </ol>
  </li>

  <li>
  Creates a <code>Response</code> object once the file list is ready. This is serialized to a JSON string (via Gson) before being returned to the original caller.
  </li>

  <li>
  The JSON string is either returned immediately (synchronous) or posted back to the JavaScript side of the extension as a message (asynchronous).
  </li>

  <li>
    <p><a name="files-data-structure"></a>On the JavaScript side, the JSON string is deserialized into an object with this structure:</p>

    <pre>
    {
      "id": "1",
      "success": true,
      "files": [
        {
          "path": "/foo/bar/1",
          "title": "Hello World Song",
          "artist": "Hello World Band"
        },
        {
          "path": "/foo/bar/2",
          "title": "Goodbye Everyone",
          "artist": "Goodbye Group"
        }
      ]
    }
    </pre>

    <p>The web application can do what it wants with the object; in this tutorial, it creates a simple user interface to play the audio files.</p>
  </li>
</ol>

In the following sections, you'll create the Java and JavaScript sides of the extension and wire them together with a configuration file.

## Set up the directory structure

    cd ~/<my projects directory>/xwalk-player-project

    # top-level directory for extension code
    mkdir xwalk-audiofs-extension-src
    cd xwalk-audiofs-extension-src

    # for the extension Java code; NB the Java classes
    # will be in the package org.crosswalkproject.sample
    # - adjust this for your own project
    mkdir -p java/org/crosswalkproject/sample

    # for extension JavaScript code
    mkdir js

    # for third party libs distributed with the project
    mkdir tools

The `build/`, `lib/` and `xwalk-audiofs-extension/` directories (shown in the [project outline](#documentation/android_extensions/host_and_target_setup/project_outline)) will be created at build time.

Note that the following instructions assume you're in `xwalk-audiofs-extension-src/` (the top-level directory for the extension project).

## Add Java code for the extension

First, create a Java class which extends [`XWalkExtensionClient`](https://github.com/crosswalk-project/crosswalk/blob/master/app/android/runtime_client/src/org/xwalk/app/runtime/extension/XWalkExtensionClient.java). The key thing here is that you should override `onSyncMessage()` and/or `onMessage()`, as these provide the means for communication from the JavaScript side to the Java side of the extension.

**`java/org/crosswalkproject/sample/AudioFs.java`:**

    package org.crosswalkproject.sample;

    import org.xwalk.app.runtime.extension.XWalkExtensionClient;
    import org.xwalk.app.runtime.extension.XWalkExtensionContextClient;
    import java.util.List;
    import java.util.ArrayList;
    import com.google.gson.Gson;
    import android.content.ContentResolver;
    import android.database.Cursor;

    public class AudioFs extends XWalkExtensionClient {
      private ContentResolver resolver;
      private Gson gson = new Gson();

      // the constructor should have this signature so that Crosswalk
      // can instantiate the extension
      public AudioFs(String name, String jsApiContent,
      XWalkExtensionContextClient xwalkContext) {
        super(name, jsApiContent, xwalkContext);

        // get a reference to the ContentResolver for the application's
        // context
        this.resolver = xwalkContext.getContext().getContentResolver();
      }

      // retrieve audio file metadata from a ContentProvider via
      // a ContentResolver;
      // see http://developer.android.com/guide/topics/providers/content-provider-basics.html
      private String listFiles(String requestId) {
        // columns to retrieve
        String[] projection = {
          android.provider.MediaStore.Audio.Media.DATA,
          android.provider.MediaStore.Audio.Media.TITLE,
          android.provider.MediaStore.Audio.Media.ARTIST
        };

        Cursor audioCursor = this.resolver.query(
          android.provider.MediaStore.Audio.Media.EXTERNAL_CONTENT_URI,
          projection,
          null, // selection
          null, // selectionArgs
          null  // sortOrder
        );

        // build the list of file objects
        List<FileInfo> files = new ArrayList<FileInfo>();

        if (audioCursor != null && audioCursor.moveToFirst()) {
          do {
            // columns are ordered as in projection array
            files.add(new FileInfo(
              audioCursor.getString(0), // uri
              audioCursor.getString(1), // title
              audioCursor.getString(2)  // artist
            ));
          }
          while (audioCursor.moveToNext());
        }

        Response resp = new Response(requestId, true, files);

        return gson.toJson(resp);
      }

      // implement the public extension API methods which will be
      // invoked from the JavaScript side
      @Override
      // for asynchronous requests
      public void onMessage(int instanceId, String requestId) {
        postMessage(instanceId, listFiles(requestId));
      }

      @Override
      // for synchronous requests
      public String onSyncMessage(int instanceId, String requestId) {
        return listFiles(requestId);
      }
    }

The two key methods in this class are the ones which override the default `XWalkExtensionClient` methods for handling incoming messages:

*   `onMessage()`: For asynchronous messages.
*   `onSyncMessage()`: For synchronous messages.

Internally, both methods invoke the private `listFiles()` method, which fetches the audio file metadata and serializes it to a JSON string. However, they return the result in different ways:

*   `onMessage()` indirectly returns the result by invoking `postMessage()` (a method on the `XWalkExtensionClient` class). This posts a string back to the JavaScript side of the API asynchronously, where it can be handled by a listener.
*   `onSyncMessage()` returns the string directly to the calling JavaScript code.

Two supporting classes are used to make serialization to JSON easier, as shown below.

**`java/org/crosswalkproject/sample/FileInfo.java`:**

    package org.crosswalkproject.sample;

    public class FileInfo {
      public String uri;
      public String title;
      public String artist;

      public FileInfo(String path, String title, String artist) {
        this.uri = path;
        this.title = title;
        this.artist = artist;
      }
    }

**`java/org/crosswalkproject/sample/Response.java`:**

    package org.crosswalkproject.sample;

    import java.util.List;

    public class Response {
      public String id;
      public boolean success;
      public List<FileInfo> files;

      public Response(String id, boolean success, List<FileInfo> files) {
        this.id = id;
        this.success = success;
        this.files = files;
      }
    }

Before you can build the extension, you will need to add the other required files.

## Add the extension configuration file

The configuration file tells the Crosswalk packaging tool (`make_apk.py`) how the Java and JavaScript parts of the extension work together.

Create a JSON file `xwalk-audiofs-extension.json` with this content:

    {
      "name":  "audioFs",
      "class": "org.crosswalkproject.sample.AudioFs",
      "jsapi": "xwalk-audiofs-extension.js",
      "permissions": []
    }

The properties in the object defined in this file have the following roles:

*   `name`: The extension's namespace, exposed to the web application's global scope. For example, as the namespace is `audioFs` for your extension, the web application can use it as follows:

        // async
        audioFs.listFilesSync().then(
          function (result) {
            // ...process result...
          }
        );

        // sync
        var files = audioFs.listFiles();

    Note that the web application doesn't have to import a JavaScript file: the extension's API is automatically made available on the global JavaScript scope when the extension is instantiated. The methods available on the API are the ones defined in the `xwalk-audiofs-extension.js` file you will create next.

*   `class`: The Java class which implements the extension; in your case, the `AudioFs` class. Note that this should include the package name as well as the class name.

*   `jsapi`: The file which defines the JavaScript API. You will create this in the next section.

*   `permissions`: A list of additional permissions required by the extension. It's included here for completeness, but is empty in this case as this extension only requires Crosswalk's default permissions. If you are writing your own application, you may need to add extra permissions.

    The strings in the `permissions` array should match the corresponding Android permission; see the [list of Android permissions](http://developer.android.com/reference/android/Manifest.permission.html) for details. For example, if you needed access to the `FLASHLIGHT` and `GET_ACCOUNTS` permissions, your extension configuration file would have a `permissions` property like this:

        "permissions": ["FLASHLIGHT", "GET_ACCOUNTS"]

At build time, `make_apk.py` will combine this JSON file with configuration files for other extensions into a single `extensions-config.json` file. This file is what Crosswalk actually uses to load the extension class and its corresponding JavaScript API.

## Add the JavaScript API file

Create `js/xwalk-audiofs-extension.js` with this content:

    /*
    AudioFs extension

    exports listFiles() and listFilesAsync(),
    which return or resolve to (respectively) a response object:

       {
         id: "<call id>",
         success: true | false,
         files: []
       }

    files is an array of file objects on the device in format:
    [{title: ..., artist: ..., path: ...}, ...]
    */

    // provides a unique ID for each async call to the extension
    var counter = 0;

    // map from a request ID to a callback for the response
    var successCbs = {};

    // message listener for ALL messages; this invokes the correct
    // callback depending on the ID in the message
    extension.setMessageListener(function (message) {
      var data = JSON.parse(message);
      var cb = successCbs[data.id];

      if (cb) {
        cb(data);
        delete successCbs[data.id];
      }
    });

    // returns a promise which resolves to an array of file objects, or
    // rejects with an error if the call to the extension fails
    exports.listFilesAsync = function () {
      // counter contains a unique request ID for this invocation
      counter += 1;

      return new Promise(function (resolve, reject) {
        // associate the request ID with the method which will be invoked
        // if the request is successful
        successCbs[counter] = resolve;

        // you MUST pass a string to postMessage()
        try {
          extension.postMessage('' + counter);
        }
        catch (e) {
          reject(e);
        }
      });
    };

    // returns an array of file objects
    exports.listFiles = function () {
      // you MUST pass a string to sendSyncMessage(), even if it's empty
      var result = extension.internal.sendSyncMessage('');
      return JSON.parse(result);
    };

A few notes on the content of this file:

*   The `counter` variable is incremented and passed *as a string* (it must be a string) each time a call is made to the `postMessage()` method on the Java side of the extension. This is so that any messages returning from the extension can be married up to a corresponding callback.

    The `sendSyncMessage()` method also requires a string argument, though in this case it doesn't contain anything: there's no need to coordinate synchronous method calls with callbacks (the result is returned immediately), so we don't need to pass a request ID.

*   When `listFilesAsync()` is invoked, a new Promise is created (see the next section). The function for handling a successful resolution of the Promise (`resolve`) is associated with the unique ID for this request by adding it to `successCbs`.

*   `extension.setMessageListener()` sets a function to invoke for each message returned by the Java side of the extension. This function will be invoked for *every* message; but the handlers which will actually deal with the data are stored in the `successCbs` object: a map from request IDs to handlers. When a message is received, the correct handler is looked up from `successCbs` and invoked with the message as an argument.

*   Any properties (methods/objects/constants etc.) you want to expose as the JavaScript API for your extension should be appended to the `exports` object inside the JavaScript API file. This has a similar role to the `exports` object in nodejs modules, defining the public face of the API. Any other variables *not* attached to `exports` are only scoped to this file, and won't pollute the web application's global scope.

    The namespace the JavaScript API is exported to is determined by the extension configuration file, `xwalk-audiofs-extension.json`, which you created in the previous section.

*   The Java side of the extension is available via the `extension` object. Note that the code above invokes the `extension.internal.sendSyncMessage()` and `extension.postMessage()` functions, which communicate with the Java code you wrote earlier.

### Promises, promises

The synchronous method defined for the extension's JavaScript API (`listFiles()`) is straightforward, and has this signature:

    audioFs.listFiles() : Response

where `Response` has the shape described [here](#files-data-structure).

By contrast, the asynchronous `listFilesAsync()` method has this signature:

    audioFs.listFilesAsync() : Promise

If you're not familiar with [Promises](http://promises-aplus.github.io/promises-spec/) (a relatively recent addition to the web application developer's toolkit), this might look odd. Why not just use callbacks? For example, replace the Promise-returning method with an async method which has this signature:

    audioFs.listFilesAsync(callback) : undefined

and call `callback(response)` when the Java side of the API returns its `Response` object (serialized to JSON).

The problem with callbacks is that they have to be managed carefully: if callbacks are nested inside callbacks inside  callbacks etc., it can lead to the so-called [pyramid of doom](http://survivejs.com/common_problems/pyramid.html). For example, imagine that you wanted to do some additional processing of the response to add extra metadata to the files. You might have a couple of objects to do this, and invoke their methods once the initial result is passed to the callback:

    audioFs.listFilesAsync(function (result) {
      // annotate result with local metadata about progress through the file
      metaStore.annotate(result, function (resultWithAnnotations) {
        // annotate resultWithAnnotations with remote metadata about the artist
        artistDatabase.annotate(resultWithAnnotations, function (resultWithMoreAnnotations) {
          displayData(resultWithMoreAnnotations);
        });
      });
    });

In the code sample above, there are calls to two imaginary asynchronous functions, `metaStore.annotate()` and `artistDatabase.annotate()`, which decorate the raw filesystem listing (`result`) with additional metadata. Note how the pyramid is already starting to emerge. Adding yet more metadata decorators could make it even worse.

There are other ways to avoid the pyramid emerging here; but the point of using Promises is to codify those approaches with extra sugar on top, to simplify asynchronous code and avoid nested callbacks. For example, if `listFilesAsync()` returns a Promise, and the `*.annotate()` methods also return Promises, we could instead use this code:

    audioFs.listFilesAsync()
    .then(
      function (result) {
        // annotate with local metadata
        return metaStore.annotate(result);
      }
    )
    .then(
      function (resultWithAnnotations) {
        // annotate with remote metadata about the artist
        return artistDatabase.annotate(resultWithAnnotations);
      }
    )
    .then(displayData);

The `then()` method takes a function to apply to the value returned by the Promise when it is *resolved* (i.e. it successfully completes its asynchronous operation and "becomes" a value which is not an error). It can also optionally take a method to apply to any errors returned by the Promise if it is *rejected* (i.e. it fails in some way and "becomes" an error).

You can see that the pyramid effect has been avoided, and it's much clearer which processing steps are being applied to the data when.

Note that in the last `then()` call, because we're not returning anything, we can just pass the `displayData` function and the result of `artistDatabase.annotate()` will be implicitly passed to that method.

In many environments, Promises are not natively available; the typical solution is to incorporate a library like [Q](http://documentup.com/kriskowal/q/) to fill the gap. By contrast, in Crosswalk, you *do* have native access to Promises, which is what you're using in the JavaScript side of the extension (above); so there's less need for an external library.

## Add build infrastructure

To use an extension in a Crosswalk application, you have to include it in the Android package for your application. The Crosswalk packaging tool has stringent requirements about how an extension should be structured to be included in a package. The layout of the extension *must* be like this:

    myextension/
      myextension.jar
      myextension.js
      myextension.json

All of the names *must* match: the directory name must match the prefix of the `.jar`, `.js` and `.json` files, otherwise the extension won't be included in the package.

You would replace "myextension" with your extension's name. For the extension in this tutorial, the layout you need is:

    xwalk-audiofs-extension/
      xwalk-audiofs-extension.jar
      xwalk-audiofs-extension.js
      xwalk-audiofs-extension.json

However, you may notice that the files you have so far don't match this layout (e.g. the `xwalk-audiofs-extension.js` is in a `js` directory and you don't have a `.jar` file at all). This is where the build infrastructure comes in. Rather than manually create these files and place them in the right directory, you'll set up an automatic build which will create a temporary `xwalk-audiofs-extension` directory and copy/compile the three required files into it.

Ivy and Ant are de facto standard tools for working with Java projects, so you'll use them to build the extension: Ivy to download the Gson and Android jar files at build time (as `AudioFs` depends on both); and Ant to compile the Java code for the extension and copy files to the required locations. (If you are familiar with Eclipse, it's possible to use that as well, as or instead of, the command-line tools.)

The next two sections explain how to set up Ivy and Ant.

### Set up and configure Ivy

Follow the instructions below to install and configure Ivy:

1.  Download the Apache Ivy distribution, which contains the Ivy tasks for Ant. It's available from [the Apache Ivy download site](https://ant.apache.org/ivy/download.cgi). For example, to get Ivy 2.4.0-rc1:

        wget http://www.mirrorservice.org/sites/ftp.apache.org/ant/ivy/2.4.0-rc1/apache-ivy-2.4.0-rc1-bin.zip

2.  Unpack it and copy the Ivy jar file to the `tools/` directory:

        unzip apache-ivy-2.4.0-rc1-bin.zip
        cp apache-ivy-2.4.0-rc1/ivy-2.4.0-rc1.jar tools/

    You can remove the zip file once you're done with it.

3.  Add the Ivy configuration file, `ivy.xml`, to the top-level directory:

        <?xml version="1.0" encoding="UTF-8"?>
        <ivy-module version="2.3">
          <info organisation="org.crosswalkproject.sample" module="xwalk-audiofs-extension" />
          <dependencies>
            <dependency org="com.google.code.gson"
                        name="gson"
                        rev="2.2.4"
                        conf="default->master" />
            <dependency org="com.google.android"
                        name="android"
                        rev="4.0.1.2"
                        conf="default->master" />
          </dependencies>
        </ivy-module>

    You only have two dependencies here (`gson.jar`, `android.jar`), but you could add other third-party libraries to this file. The Android version specified is 4.0.1.2, which is the earliest public `android.jar` file publically available to Ivy which is compatible with Crosswalk.

    Note that Crosswalk is not in the Ivy repositories, and will be downloaded by Ant in the main buildfile.

### Add an Ant buildfile

You should have already installed Ant as described in the *Getting started* instructions ([Windows](#documentation/getting_started/windows_host_setup/Install-Ant), [Linux](#documentation/getting_started/linux_host_setup/Install-Ant)).

Once Ant is installed, add a buildfile, `build.xml`, to the top-level directory of your project with this content:

    <?xml version="1.0" encoding="UTF-8"?>
    <project xmlns:ivy="antlib:org.apache.ivy.ant"
             name="xwalk-audiofs-extension" default="dist">
      <!-- Java source -->
      <property name="src" value="java" />

      <!-- downloaded third party libraries -->
      <property name="lib" value="lib" />

      <!-- Crosswalk Android version -->
      <property name="crosswalk-version"
                value="${XWALK-STABLE-ANDROID-X86}" />

      <!-- location of downloaded Crosswalk Android file -->
      <property name="crosswalk-zip" value="${lib}/crosswalk.zip" />

      <!-- temporary build directory -->
      <property name="build" value="build" />

      <!-- final location for the built extension -->
      <property name="dist" value="xwalk-audiofs-extension" />

      <!-- classpath containing the Ivy Ant tasks jar file -->
      <path id="ivy.lib.path">
        <fileset dir="tools" includes="*.jar"/>
      </path>

      <!-- delete + make the temporary build directories -->
      <target name="prepare">
        <delete dir="${build}" quiet="true" />
        <delete dir="${dist}" quiet="true" />

        <mkdir dir="${build}" />
        <mkdir dir="${lib}" />
        <mkdir dir="${dist}" />
      </target>

      <!-- download dependencies using Ivy -->
      <target name="download-deps" depends="prepare">
        <taskdef resource="org/apache/ivy/ant/antlib.xml"
                 uri="antlib:org.apache.ivy.ant"
                 classpathref="ivy.lib.path" />
        <ivy:retrieve pattern="${lib}/[artifact]-[revision].[ext]" />
      </target>

      <!-- check whether the Crosswalk zip file is present -->
      <target name="check-crosswalk-present" depends="prepare">
        <available file="${crosswalk-zip}"
                   property="crosswalk-zip.present"/>
      </target>

      <!-- manually get crosswalk.zip if it's not already there -->
      <target name="download-crosswalk"
              depends="prepare, check-crosswalk-present"
              unless="crosswalk-zip.present">
        <!-- fetch from the download site -->
        <get src="https://download.01.org/crosswalk/releases/crosswalk/android/stable/${crosswalk-version}/crosswalk-${crosswalk-version}.zip" dest="${crosswalk-zip}" />

        <!-- unpack to lib/crosswalk-*/ -->
        <unzip src="${crosswalk-zip}" dest="${lib}" />
      </target>

      <!-- compile the extension Java code -->
      <target name="compile" depends="download-deps, download-crosswalk">
        <javac srcdir="${src}" destdir="${build}"
               encoding="utf-8" debug="true" verbose="true">
          <classpath>
            <fileset dir="${lib}" includes="*.jar" />
            <file file="${lib}/crosswalk-${crosswalk-version}/libs/xwalk_app_runtime_java.jar" />
          </classpath>
        </javac>
      </target>

      <!--
      pack third party Java code and extension code into a single
      jar, and copy supporting files to the xwalk-audiofs-extension/
      directory; NB we don't need to pack any Crosswalk jars, as they
      will be added by the packaging tool; and we don't need android.jar,
      as that is on the Android target already
      -->
      <target name="dist" depends="compile">
        <unjar dest="${build}">
          <fileset dir="${lib}">
            <include name="*.jar" />
            <exclude name="android*.jar" />
          </fileset>
        </unjar>

        <jar destfile="${dist}/xwalk-audiofs-extension.jar">
          <fileset dir="${build}" excludes="META-INF/**" />
        </jar>

        <copy file="xwalk-audiofs-extension.json" todir="${dist}" />
        <copy file="js/xwalk-audiofs-extension.js" todir="${dist}" />
      </target>
    </project>

This is a fairly standard Ant buildfile for a small project. The default task is `dist`, which does the following:

1.  Deletes and recreates the `build/` and `xwalk-audiofs-extension/` directories.
2.  Downloads the Gson and Android jar file dependencies and puts them in the `lib/` directory (via Ivy).
3.  Downloads Crosswalk Android (via HTTP) and unpacks it in the `lib/` directory.
4.  Compiles the extension Java source in the `src/` directory, placing the output `.class` files into the `build/` directory.
5.  Unpacks the Gson jar file into the `build/` directory. This is so it can be included in the extension jar file. Note that `android.jar` is not included as part of the extension.
6.  Creates a jar file in `xwalk-audiofs-extension/` containing the extension `.class` files and the content unpacked from the Gson jar file.
7.  Copies the extension JSON configuration `xwalk-audiofs-extension.json` and the JavaScript API definition `js/xwalk-audiofs-extension.js` into the `xwalkwebrtc-1741-audiofs-extension/` directory.

The final output of this task, the `xwalk-audiofs-extension/` directory, contains an extension with the correct layout to be included in a Crosswalk `.apk` file, i.e.

    xwalk-audiofs-extension/
      xwalk-audiofs-extension.jar
      xwalk-audiofs-extension.js
      xwalk-audiofs-extension.json

## Build the extension

As you added a standard Ant buildfile, building the extension is as simple as running this command in the `xwalk-audiofs-extension-src/` directory:

    $ ant

This runs the default `dist` task (see above). (It may take a while the first time, as it will download the third party dependencies.)

Once the extension is built, the next step is to create the web application which can use it.
