# Develop extension for embedding API

[Extension for embedding API](https://crosswalk-project.org/apis/embeddingapidocs_v2/reference/org/xwalk/core/XWalkExtension.html) is supportted since v2.1. The interface is similar with [Crosswalk runtime extensions](https://crosswalk-project.org/#documentation/android_extensions) with below differences because of the different usage modle of Runtime vesus Embedding API:
* No lifecycle API support includes: onResume(), onPause(), onDestroy(), onActivityResult(). It should be take care by embedder itself.
* No configuration file needed because embeder is responsible to create and destroy the extensions in Java.
* No make_apk.py supporting because embedder needs to take care of packaging.

## Add code for the extension

Continued with [Creating an application with the embedding API](https://crosswalk-project.org/#documentation/embedding_crosswalk), create a Java class which extends [`XWalkExtension`](https://crosswalk-project.org/apis/embeddingapidocs_v2/reference/org/xwalk/core/XWalkExtension.html).

**`org/crosswalkproject/xwalkembed/ExtentionEcho.java`:**

    ```
    package org.crosswalkproject.xwalkembed;

    import org.xwalk.core.XWalkExtension;

    public class ExtensionEcho extends XWalkExtension {

        public ExtensionEcho() {
            super("echo",
                  "var echoListener = null;"
                  + "extension.setMessageListener(function(msg) {"
                  + "  if (echoListener instanceof Function) {"
                  + "    echoListener(msg);"
                  + "  };"
                  + "});"
                  + "exports.echo = function(msg, callback) {"
                  + "  echoListener = callback;"
                  + "  extension.postMessage(msg);"
                  + "};"
                  + "exports.echoSync = function(msg) {"
                  + "  return extension.internal.sendSyncMessage(msg);"
                  + "};"
                 );
        }

        @Override
        public void onMessage(int instanceID, String message) {
            postMessage(instanceID, "From java:" + message);
        }

        @Override
        public String onSyncMessage(int instanceID, String message) {
            return "From java sync:" + message;
        }
    }
    ```


Some hints:
* The javascript string in the constructure can be passed in different ways, you could read from a asset file of your APK and pass it in as String. Thus you could have seperate .js file which is more developer friendly.
* You could use JSON format your string to better serialising and deserialising. Check the related part of [the runtime extension] (https://crosswalk-project.org/#documentation/android_extensions/write_an_extension) for more details.

## Use extension in your app
### Add code to your activity(src/org.crosswalkproject.xwalkembed/MainActivity.java)

    ```
    import org.crosswalkproject.xwalkembed.ExtensionEcho;

    public class MainActivity extends Activity {
        private XWalkView mXWalkView;
        private ExtensionEcho mExtension;

        @Override
        protected void onCreate(Bundle savedInstanceState) {
            super.onCreate(savedInstanceState);
            setContentView(R.layout.activity_main);
            mExtension = new ExtensionEcho();
            mXWalkView = (XWalkView) findViewById(R.id.activity_main);
            
            // this loads a file from the assets/ directory
            mXWalkView.load("file:///android_asset/index.html", null);

        }
    }
    ```

### Add JS code to your web page

    ```
    <script>
    try {
      var d = new Date().toString();
      echo.echo(d, function(msg) {
        document.write(msg + "<br>");
        var expected = "From java:" + d;
        if (msg === expected) {
          document.write("Async echo <font color=green>passed</font>.");
          document.title = "Pass";
        } else {
          document.write("Async echo <font color=red>failed</font>.");
          document.title = "Fail";
        }
      });
    } catch(e) {
      console.log(e);
      document.title = "Fail";
    }
    </script>
    ```

### Pluggable support

It is possible to include a extension in binary format, either in .jar, or .class. It is same as how to build a normal .java into seperate library and bundle into another application. We'll add the example as required.

### FAQ

1. Will the extension available when i create it after xwalkview
  Yes, the order of extension and xwalkview is not limited, but we'd suggest init extension first.

2. What's the behavior if i create more than one instance of an extension class.
  Ideally, second extension instance won't be registered correctly the first instance will work as wish. This is because those two instance has same name parameter, the extension service in C++ side will ignore the second one.
