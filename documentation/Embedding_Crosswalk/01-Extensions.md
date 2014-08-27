# Use Crosswalk extensions with the embedding API

[Extensions](https://crosswalk-project.org/apis/embeddingapidocs_v2/reference/org/xwalk/core/XWalkExtension.html) are supported in version 2.1 and later of the [embedding API](#documentation/apis/embedding_api). The extensions API is similar to the one for [Crosswalk runtime extensions](#documentation/android_extensions), but with a few differences due to the usage model of an *embedding application* (i.e. an Android application which embeds Crosswalk using Java code):

* There is no support for lifecycle events. The extension *cannot* use `onResume()`, `onPause()`, `onDestroy()`, or `onActivityResult()`. Such events should be taken care of by the embedding application.
* No configuration file is needed, because the embedding application is responsible for creating and destroying the extensions in Java code.
* No support for [`make_apk.py`]. The embedding application is likely to be built using Android SDK tools, ant etc.; the extension code should be compiled and packaged as part of this build.

The following sections explain how to add code for an extension and use it in an embedding application. Before following them, you will need to [create an application with the embedding API](#documentation/embedding_crosswalk).

## Write the extension

Create a Java class which extends [`XWalkExtension`](https://crosswalk-project.org/apis/embeddingapidocs_v2/reference/org/xwalk/core/XWalkExtension.html). For example:

**`org/crosswalkproject/sample/ExtensionEcho.java`:**

```
package org.crosswalkproject.sample;

import org.xwalk.core.XWalkExtension;

public class ExtensionEcho extends XWalkExtension {
  private static String name = "echo";

  private static String jsapi = "var echoListener = null;" +
    "extension.setMessageListener(function(msg) {" +
    "  if (echoListener instanceof Function) {" +
    "    echoListener(msg);" + "  };" + "});" +
    "exports.echo = function (msg, callback) {" +
    "  echoListener = callback;" + "  extension.postMessage(msg);" +
    "};" + "exports.echoSync = function (msg) {" +
    "  return extension.internal.sendSyncMessage(msg);" + "};";

  public ExtensionEcho() {
    super(name, jsapi);
  }

  @Override
  public void onMessage(int instanceID, String message) {
    postMessage(instanceID, "From java: " + message);
  }

  @Override
  public String onSyncMessage(int instanceID, String message) {
    return "From java sync: " + message;
  }

}
```

This extension only does some basic string manipulation, but a real extension could make use of the full range of Android APIs, as well as any imported Java libraries.

Some hints about the code:

* The JavaScript string in the constructor can be passed in different ways: it could be passed in as a `String` (as done here), read from a `.js` file in the `assets/` directory, fetched from a web server, etc.

* The string returned by the `onMessage()` and `onSyncMessage()` methods could be serialised to/from JSON to handle more complex interactions. See [the runtime extension documentation](#documentation/android_extensions/write_an_extension) for details.

Note that it is also possible to include an extension in binary format, in a jar or `.class` file bundled with the embedding application. Extensions included by the embedding application can be used the same way as any other imported Java classes.

## Use the extension in the embedding application

To use the extension, you need to initialise it just after the embedding application's main activity is started. For example, if your activity was `src/org/crosswalkproject/sample/MainActivity.java` (see the [embedding tutorial](#documentation/android_extensions/write_an_extension)), you could use the extension as follows:

1.  Add code to the main activity to create an instance of the extension when the activity is ready:

    ```
    import org.crosswalkproject.sample.ExtensionEcho;

    public class MainActivity extends Activity {
      private XWalkView mXWalkView;
      private ExtensionEcho mExtension;

      @Override
      protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        // here is where the extension instance is created
        mExtension = new ExtensionEcho();

        // create the main Crosswalk view
        mXWalkView = (XWalkView) findViewById(R.id.activity_main);
        mXWalkView.load("file:///android_asset/index.html", null);
      }
    }
    ```

    Note that it is recommended that you create extensions before creating the `XWalkView` instance.

    Also note that if you create multiple instances of an extension with the same name (`"echo"` in the example above), only the first has any effect. Any subsequent instances you create will not have any effect (the C++ code in Crosswalk ignores an attempt to add an extension which has the same name as an existing one).

2.  Use the extension from JavaScript in the web part of the application. The following code performs a simple test on the extension and writes the result into a paragraph element:

    ```
    <script>
    try {
      var now = new Date().toString();

      // test the async API of the extension
      echo.echo(now, function (msg) {
        var p1 = document.createElement('p');
        p1.innerHTML = msg + "<br>";
        document.body.appendChild(p1);

        var expected = "From java:" + now;

        var p2 = document.createElement('p');

        if (msg === expected) {
          p2.innerHTML = 'Async echo <span style="color:green;">passed</span>.';
          document.title = 'Pass';
        }
        else {
          p2.innerHTML = 'Async echo <span style="color:red;">failed</span>.';
          document.title = 'Fail';
        }

        document.body.appendChild(p2);
      });
    }
    catch (e) {
      console.log(e);
      document.title = "Fail";
    }
    </script>
    ```
