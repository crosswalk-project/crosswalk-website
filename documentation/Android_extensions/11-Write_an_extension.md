# Write an extension

Crosswalk extensions are written in Java, and have access to the standard [Android APIs](http://developer.android.com/reference/packages.html).

In addition, because Crosswalk on Android acts as an [Activity](http://developer.android.com/reference/android/app/Activity.html) and has access to a [Context](http://developer.android.com/reference/android/content/Context.html), an extension can make also use of those objects. However, the extension you'll develop in this tutorial doesn't use any Android APIs, as it only does simple string manipulation.

Even with the extensive Android APIs available to you, there may be some cases where you need to use other third-party libraries in your extension. This tutorial explains how to do this, with an example of using [Gson](https://code.google.com/p/google-gson/) to read and write JSON. (While the Android API has some JSON capabilities, Gson is much neater and easier to use.)

## What does the extension do?

Here's an overview of how this extension works:

<ol>
  <li>
  When the Crosswalk application (with extension) starts on the Android device, an instance of the <code>Echo</code> extension is instantiated.
  </li>

  <li>
    <p>The web part of the application calls one of the methods exported by the JavaScript part of the extension; either <code>echo.echoAsync()</code> (asynchronous) or <code>echo.echo()</code> (synchronous). Both take a single argument, representing the message to echo.</p>

    <p>In both cases, the JavaScript part of the extension creates a message object with this structure:</p>

    <pre>
{
  "id": "1",
  "content": "Hello world"
}
    </pre>

    <p>Each message contains a unique request ID. This ID is used to ensure that the response is married up to the correct callback when the extension returns its results.</p>

    <p>This message object is serialised to JSON and sent to the Java part of the extension.</p>
  </li>

  <li>
    Inside the <code>Echo</code> instance (Java), the JSON string received from the JavaScript part of the extension is deserialised to a <code>Message</code> object.
  </li>

  <li>
    Next, the private <code>echo()</code> method of the <code>Echo</code> instance is invoked. Note that this method receives the request ID (from the JavaScript side). <code>echo()</code> simply prefixes the string it was passed and returns it.
  </li>

  <li>
    <p>The <code>Echo</code> instance then creates a <code>Message</code> object. This is serialised to a JSON string (via Gson) before being returned to the original caller.</p>

    <p>The JSON string is either returned immediately (synchronous) or posted back to the JavaScript side of the extension as a message (asynchronous).</p>
  </li>

  <li>
    <p><a name="message-data-structure"></a>On the JavaScript side, the JSON string is deserialised into an object with this structure:</p>

    <pre>
{
  "id": "1",
  "content": "You said: Hello world"
}
    </pre>

    <p>The web application can do what it wants with the object; in this tutorial, it creates a DOM element containing the prefixed string held in the <code>content</code> property.</p>
  </li>
</ol>

In the following sections, you'll create the Java and JavaScript sides of the extension and wire them together with a configuration file.

## Set up the directory structure

    cd ~/<my projects directory>/xwalk-echo-project

    # top-level directory for extension code
    mkdir xwalk-echo-extension-src
    cd xwalk-echo-extension-src

    # for the extension Java code; NB the Java classes
    # will be in the package org.crosswalkproject.sample
    # - adjust this for your own project
    mkdir -p java/org/crosswalkproject/sample

    # for extension JavaScript code
    mkdir js

    # for third party libs distributed with the project
    mkdir tools

The `build/`, `lib/` and `xwalk-echo-extension/` directories (shown in the [project outline](#documentation/android_extensions/host_and_target_setup/project_outline)) will be created at build time.

Note that the following instructions assume you're in `xwalk-echo-extension-src/` (the top-level directory for the extension project).

## Add Java code for the extension

First, create a Java class which extends [`XWalkExtensionClient`](https://github.com/crosswalk-project/crosswalk/blob/master/app/android/runtime_client/src/org/xwalk/app/runtime/extension/XWalkExtensionClient.java).

**`java/org/crosswalkproject/sample/Echo.java`:**

    package org.crosswalkproject.sample;

    import org.xwalk.app.runtime.extension.XWalkExtensionClient;
    import org.xwalk.app.runtime.extension.XWalkExtensionContextClient;
    import com.google.gson.Gson;

    public class Echo extends XWalkExtensionClient {
      private Gson gson = new Gson();

      public Echo(String name, String jsApiContent, XWalkExtensionContextClient xwalkContext) {
        super(name, jsApiContent, xwalkContext);
      }

      private String echo(String requestJson) {
        Message request = gson.fromJson(requestJson, Message.class);
        String reply = "You said: " + request.content;
        Message response = new Message(request.id, reply);
        return gson.toJson(response);
      }

      @Override
      public void onMessage(int instanceId, String requestJson) {
        postMessage(instanceId, echo(requestJson));
      }

      @Override
      public String onSyncMessage(int instanceId, String requestJson) {
        return echo(requestJson);
      }
    }

The two key methods in this class are the ones which override the default `XWalkExtensionClient` methods. These provide the means for communicating between the JavaScript and Java parts of the extension:

*   `onMessage()`: For asynchronous messages.
*   `onSyncMessage()`: For synchronous messages.

Internally, both methods invoke the private `echo()` method, which:

1.  Deserialises the original request string to a `Message` object (see below).
2.  Prefixes the content string (passed in the request) with "You said: ".
3.  Creates a new `Message` object as the response, with the prefixed string and the original request ID.
4.  Serialises it back to JSON.

However, they return the result in different ways:

*   `onSyncMessage()` returns the JSON string directly to the calling JavaScript code.
*   `onMessage()` indirectly returns the result by invoking `postMessage()` (a method on the `XWalkExtensionClient` class). This posts a JSON string back to the JavaScript side of the API asynchronously, where it can be handled by a listener.

A supporting class is used to make these JSON operations easier, as shown below.

**`java/org/crosswalkproject/sample/Message.java`:**

    package org.crosswalkproject.sample;

    public class Message {
      public String id;
      public String content;

      public Message(String id, String content) {
        this.id = id;
        this.content = content;
      }
    }

(Gson provides methods for serialising to and deserialising from Java objects, providing they have public member variables, as the `Message` class above does.)

Before you can build the extension, you will need to add the other required files, as explained below.

## Add the extension configuration file

The configuration file tells the Crosswalk packaging tool (`make_apk.py`) how the Java and JavaScript parts of the extension work together.

Create a JSON file `xwalk-echo-extension.json` with this content:

    {
      "name":  "echo",
      "class": "org.crosswalkproject.sample.Echo",
      "jsapi": "xwalk-echo-extension.js",
      "permissions": []
    }

The properties in the object defined in this file have the following roles:

*   `name`: The extension's namespace, exposed to the web application's global scope. For example, as the namespace is `echo` for your extension, the web application can use it as follows:

        // async
        echo.echoAsync("Hello world").then(
          function (result) {
            // ...process result...
          }
        );

        // sync
        var message = echo.echo("Hello world");

    Note that the web application doesn't have to import a JavaScript file: the extension's API is automatically made available in the global JavaScript scope when the extension is instantiated. The methods available on the API are the ones defined in the `xwalk-echo-extension.js` file you will create next.

*   `class`: The Java class which implements the extension; in your case, the `org.crosswalkproject.sample.Echo` class. Note that this should include the package name as well as the class name.

*   `jsapi`: The file which defines the JavaScript API. You will create this in the next section.

*   `permissions`: A list of additional permissions required by the extension. It's included here for completeness, but is empty in this case as this extension only requires Crosswalk's default permissions. If you are writing your own application, you may need to add extra permissions.

    The strings in the `permissions` array should match the corresponding Android permission; see the [list of Android permissions](http://developer.android.com/reference/android/Manifest.permission.html) for details. For example, if you needed access to the `FLASHLIGHT` and `GET_ACCOUNTS` permissions, your extension configuration file would have a `permissions` property like this:

        "permissions": ["FLASHLIGHT", "GET_ACCOUNTS"]

At build time, `make_apk.py` will combine this JSON file with configuration files for other extensions into a single `extensions-config.json` file. This is the file that Crosswalk actually uses to load the extension class and its corresponding JavaScript API.

## Add the JavaScript API file

Create `js/xwalk-echo-extension.js` with this content:

    /*
    echoAsync() and echo() resolve to/return an object (respectively)
    with the form:

    {
      id: '<request ID>',
      content: '<content of reply from Java extension code>'
    }
    */

    // provides a unique ID for each call to the extension
    var counter = 0;

    // map from a request ID to a callback for the response
    var successCbs = {};

    // private method for building the message object and converting it
    // to a JSON string for transfer to the Java part of the extension
    var messageToJson = function (counter, message) {
      var obj = {
        id: '' + counter,
        content: message
      };

      return JSON.stringify(obj);
    };

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
    exports.echoAsync = function (message) {
      counter += 1;
      var messageJson = messageToJson(counter, message);

      return new Promise(function (resolve, reject) {
        successCbs[counter] = resolve;

        // NB you MUST pass a string to postMessage()
        try {
          extension.postMessage(messageJson);
        }
        catch (e) {
          reject(e);
        }
      });
    };

    // returns a Response object
    exports.echo = function (message) {
      counter += 1;
      var messageJson = messageToJson(counter, message);

      // NB you MUST pass a string to sendSyncMessage()
      var result = extension.internal.sendSyncMessage(messageJson);

      return JSON.parse(result);
    };

A few notes on the content of this file:

*   The `counter` variable is incremented and passed as the request ID each time a call is made to the `postMessage()` method in the Java part of the extension. This is so that any messages returning from the extension can be married up to a corresponding callback. The counter is also passed to the `sendSyncMessage()` method for the sake of consistency (the Java part of the extension always receives a JSON string with an `id` and `content`); this is despite the fact that there's no need to coordinate synchronous method calls with callbacks (as the result is returned immediately).

*   When `echoAsync()` is invoked, a new Promise is created (see the next section). The function for handling a successful resolution of the Promise (`resolve`) is associated with the unique ID for this request by adding it to `successCbs`.

*   `extension.setMessageListener()` sets a function to invoke for each message returned by the Java side of the extension. This function will be invoked for *every* message; but the handlers which will actually deal with the data are stored in the `successCbs` object: a map from request IDs to handlers. When a message is received, the correct handler is looked up from `successCbs` and invoked with the message as an argument. The handler is deleted from the `successCbs` object after it has been invoked.

*   Any properties (methods/objects/constants etc.) you want to expose as the JavaScript API for your extension should be appended to the `exports` object inside the JavaScript API file. This has a similar role to the `exports` object in nodejs modules, defining the public face of the API. Any other variables *not* attached to `exports` are only scoped to this file, and won't pollute the web application's global scope.

    The namespace the JavaScript API is exported to here (`echo`) is set in the extension configuration file, `xwalk-echo-extension.json`, which you created in the previous section.

*   The Java side of the extension is available via the `extension` object. Note that the code above invokes the `extension.internal.sendSyncMessage()` and `extension.postMessage()` functions, which communicate with the Java code you wrote earlier.

### Promises, promises

The synchronous method defined for the extension's JavaScript API (`echo()`) is straightforward, and has this signature:

    echo.echo() : Message

where `Message` has the shape described [here](#message-data-structure).

By contrast, the asynchronous `echoAsync()` method has this signature:

    echo.echoAsync() : Promise

If you're not familiar with [Promises](http://promises-aplus.github.io/promises-spec/) (a relatively recent addition to the web application developer's toolkit), this might look odd. Why not just use callbacks? For example, replace the Promise-returning method with an async method which has this signature:

    echo.echoAsync(callback) : undefined

and call `callback(message)` when the Java side of the API returns its `Message` object (serialised to JSON).

The problem with callbacks is that they have to be managed carefully: if callbacks are nested inside callbacks inside  callbacks etc., it can lead to the so-called [pyramid of doom](http://survivejs.com/common_problems/pyramid.html). For example, imagine that you wanted to do some additional work on the response to set extra properties on it. You might have a couple of objects to do this, and invoke their methods once the initial result is passed to the callback:

    echo.echoAsync(function (result) {

      // decorate result with more properties
      decorator1.decorate(result, function (decoratedResult) {

        // decorate decoratedResult with even more properties
        decorator2.decorate(decoratedResult, function (evenMoreDecoratedResult) {
          displayData(evenMoreDecoratedResult);
        });

      });

    });

In the code sample above, there are calls to two asynchronous functions, `decorator1.decorate()` and `decorator2.decorate()`, which decorate the response with additional properties. Note how the pyramid is already starting to emerge. Adding yet more decorators could make it even worse.

There are other ways to avoid the pyramid emerging here; but the point of using Promises is to codify those approaches with extra sugar on top, to simplify asynchronous code and avoid nested callbacks. For example, if `echoAsync()` returns a Promise, and the `*.decorate()` methods also return Promises, we could instead use this code:

    echo.echoAsync()
    .then(
      function (result) {
        return decorator1.decorate(result);
      }
    )
    .then(
      function (decoratedResult) {
        return decorator2.decorate(decoratedResult);
      }
    )
    .then(displayData);

The `then()` method takes a function to apply to the value returned by the Promise when it is *resolved* (i.e. it successfully completes its asynchronous operation and "becomes" a value which is not an error). It can also optionally take a method to apply to any errors returned by the Promise if it is *rejected* (i.e. it fails in some way and "becomes" an error).

You can see that the pyramid effect has been avoided, and it's much clearer which processing steps are being applied to the data when.

Note that in the last `then()` call, because we're not returning anything, we can just pass the `displayData` function and the result of `decorator2.decorate()` will be implicitly passed to that method.

In many environments, Promises are not natively available; the typical solution is to incorporate a library like [Q](http://documentup.com/kriskowal/q/) to fill the gap. By contrast, in Crosswalk, you *do* have native access to Promises, which is what you're using in the JavaScript side of the extension (above); so there's less need for an external library.

## Add build infrastructure

To use an extension in a Crosswalk application, you have to include it in the Android package for your application. The Crosswalk packaging tool has stringent requirements about how an extension should be structured to be included in a package. The layout of the extension *must* be like this:

    myextension/
      myextension.jar
      myextension.js
      myextension.json

All of the names *must* match: the directory name must match the prefix of the `.jar`, `.js` and `.json` files, otherwise the extension won't be included in the package.

You would replace "myextension" with your extension's name. For the extension in this tutorial, the layout you need is:

    xwalk-echo-extension/
      xwalk-echo-extension.jar
      xwalk-echo-extension.js
      xwalk-echo-extension.json

However, you may notice that the files you have so far don't match this layout (e.g. the `xwalk-echo-extension.js` is in a `js` directory and you don't have a `.jar` file at all). This is where the build infrastructure comes in. Rather than manually create these files and place them in the right directory, you'll set up an automatic build which will create a temporary `xwalk-echo-extension` directory and copy/compile the three required files into it.

Ivy and Ant are common tools for working with Java projects, so you'll use them to build the extension: Ivy to download the Gson jar file at build time (as `Echo` depends on it); and Ant to compile the Java code for the extension and copy files to the required locations. (If you are familiar with Eclipse, it's possible to use that as well as, or instead of, command-line tools.)

The next two sections explain how to set up Ivy and Ant.

### Set up and configure Ivy

Follow the instructions below to install and configure Ivy:

1.  Download the Apache Ivy distribution, which contains the Ivy tasks for Ant. It's available from [the Apache Ivy download site](https://ant.apache.org/ivy/download.cgi). For example, to get Ivy 2.4.0-rc1:

        $ wget http://www.mirrorservice.org/sites/ftp.apache.org/ant/ivy/2.4.0-rc1/apache-ivy-2.4.0-rc1-bin.zip

2.  Unpack it and copy the Ivy jar file to the `tools/` directory:

        $ unzip apache-ivy-2.4.0-rc1-bin.zip
        $ cp apache-ivy-2.4.0-rc1/ivy-2.4.0-rc1.jar tools/

    You can remove the zip file once you're done with it.

3.  Add the Ivy configuration file, `ivy.xml`, to the top-level directory:

        <?xml version="1.0" encoding="UTF-8"?>
        <ivy-module version="2.3">
          <info organisation="org.crosswalkproject.sample" module="xwalk-echo-extension" />
          <dependencies>
            <dependency org="com.google.code.gson"
                        name="gson"
                        rev="2.2.4"
                        conf="default->master" />
          </dependencies>
        </ivy-module>

    You only have one dependency here (`gson.jar`), but you could add other third-party libraries to this file.

    Note that Crosswalk is not in the Ivy repositories, and will be downloaded by Ant in the main buildfile.

### Add an Ant buildfile

You should have already installed Ant as described in the *Getting started* instructions ([Windows](#documentation/getting_started/windows_host_setup/Install-Ant), [Linux](#documentation/getting_started/linux_host_setup/Install-Ant)).

Once Ant is installed, add a buildfile, `build.xml`, to the top-level directory of your project with this content:

    <?xml version="1.0" encoding="UTF-8"?>
    <project xmlns:ivy="antlib:org.apache.ivy.ant" name="xwalk-echo-extension" default="dist">
      <!-- Java source -->
      <property name="src" value="java" />

      <!-- downloaded third party libraries -->
      <property name="lib" value="lib" />

      <!-- Crosswalk Android version -->
      <property name="crosswalk-version" value="5.34.104.5" />

      <!-- location of downloaded Crosswalk Android file -->
      <property name="crosswalk-zip" value="${lib}/crosswalk.zip" />

      <!-- temporary build directory -->
      <property name="build" value="build" />

      <!-- final location for the built extension -->
      <property name="dist" value="xwalk-echo-extension" />

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
        <available file="${crosswalk-zip}" property="crosswalk-zip.present"/>
      </target>

      <!-- manually get crosswalk.zip if it's not already there -->
      <target name="download-crosswalk" depends="prepare, check-crosswalk-present"
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
      jar, and copy supporting files to the xwalk-echo-extension/
      directory; NB we don't need to pack any Crosswalk jars, as they
      will be added by the packaging tool; and we don't need android.jar,
      as that is on the Android target already
      -->
      <target name="dist" depends="compile">
        <unjar dest="${build}">
          <fileset dir="${lib}">
            <include name="*.jar" />
          </fileset>
        </unjar>

        <jar destfile="${dist}/xwalk-echo-extension.jar">
          <fileset dir="${build}" excludes="META-INF/**" />
        </jar>

        <copy file="xwalk-echo-extension.json" todir="${dist}" />
        <copy file="js/xwalk-echo-extension.js" todir="${dist}" />
      </target>
    </project>

This is a fairly standard Ant buildfile for a small project. The default task is `dist`, which does the following:

1.  Deletes and recreates the `build/` and `xwalk-echo-extension/` directories.
2.  Downloads the Gson jar file dependency and puts it in the `lib/` directory (via Ivy).
3.  Downloads Crosswalk Android (via HTTP) and unpacks it in the `lib/` directory.
4.  Compiles the extension Java source in the `src/` directory, placing the output `.class` files into the `build/` directory.
5.  Unpacks the Gson jar file into the `build/` directory. This is so it can be included in the extension jar file.
6.  Creates a jar file in `xwalk-echo-extension/` containing the extension `.class` files and the content unpacked from the Gson jar file.
7.  Copies the extension JSON configuration `xwalk-echo-extension.json` and the JavaScript API definition `js/xwalk-echo-extension.js` into the `xwalk-echo-extension/` directory.

The final output of this task, the `xwalk-echo-extension/` directory, contains an extension with the correct layout to be included in a Crosswalk `.apk` file, i.e.

    xwalk-echo-extension/
      xwalk-echo-extension.jar
      xwalk-echo-extension.js
      xwalk-echo-extension.json

## Build the extension

As you added a standard Ant buildfile, building the extension is as simple as running this command in the `xwalk-echo-extension-src/` directory:

    $ ant

This runs the default `dist` task (see above). (It may take a while the first time, as it will download the third party dependencies.)

Once the extension is built, the next step is to create the web application which can use it.
