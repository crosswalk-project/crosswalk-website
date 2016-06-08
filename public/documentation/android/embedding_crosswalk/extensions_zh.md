# 通过Embedding API使用Crosswalk插件

[Embedding API](/documentation/apis/embedding_api_zh.html)从2.1版本及其之后开始支持[插件](https://crosswalk-project.org/apis/embeddingapidocs_v2/reference/org/xwalk/core/XWalkExtension.html)。extensions API与[Crosswalk插件](/documentation/android/android_extensions_zh.html)类似，除了在*嵌入模式应用*的用法上存在一些差异(也就是使用Java嵌入Crosswalk的安卓应用)：

* 不支持生命周期管理。插件*不能*使用`onResume()`，`onPause()`，`onDestroy()`，或者`onActivityResult()`。在嵌入模式下应该谨慎使用诸如此类的事件。
* 不需要配置文件，因为嵌入模式中是由Java代码负责创建和销毁插件的。
* 不支持[`make_apk.py`]。嵌入模式是通过Android SDK tools，ant等进行编译的；作为编译的一部分，插件代码也由相同的工具进行编译。

下面将介绍如何在插件中添加代码并在嵌入模式下使用插件。在此之前，您首先需要[创建嵌入模式的应用](/documentation/android/embedding_crosswalk_zh.html)。

## 编写插件

创建一个继承[`XWalkExtension`](https://crosswalk-project.org/apis/embeddingapidocs_v2/reference/org/xwalk/core/XWalkExtension.html)的Java类。比如：

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

上面的插件做了一些基本的字符串操作，但是一个真正的插件可能像其他导入的Java包一样，使用很多Android APIs。

代码解释：

* 构造函数中的JavaScript字符串可以有多种传入方式：可以作为一个`String`传入(就像上面做的一样)，从`assets/`目录下读入一个js文件，从web server端获取等等。

* 可以将方法`onMessage()`和`onSyncMessage()`返回的字符串序列化/反序列化成JSON格式以便进行更加复杂的操作。详情参见[插件文档](/documentation/android/android_extensions/write_an_extension_zh.html)。

注意在嵌入模式下的应用可以将插件以二进制形式，比如以jar或者`.class`形式的文件包含进来。那么插件就可以按照其他导入的Java类一样使用。

## 嵌入模式下使用插件

使用插件，需要在嵌入模式的main activity启动之前实例化它。例如，如果您的activity是`src/org/crosswalkproject/sample/MainActivity.java` (参见[嵌入模式教程](/documentation/android_extensions/write_an_extension_zh.html))，那么您按照下面的方式使用插件：

1.  当activity准备好之后，添加创建插件实例化的代码到main activity：

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

    注意：推荐您在创建`XWalkView`实例前创建插件。

    同时需要注意的是如果您创建了多个含有相同名字的插件实例(像上面例子中的`"mExtension"`)，只有第一个有效。后续创建的实例将不会发挥任何作用(Crosswalk中的C++代码忽略任何名字与之前重复的实例)。

2.  Web应用的JavaScript模块中使用插件。下面的代码对插件进行了一个简单的测试并将结果写入到p元素中：

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
