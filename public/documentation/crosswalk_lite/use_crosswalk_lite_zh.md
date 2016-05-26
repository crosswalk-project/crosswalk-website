# 使用Crosswalk Lite

使用Crosswalk Lite或者"Lite"编译你的应用与使用正常的Crosswalk编译是相同的。关于如何搭建你的开发环境，请参见[开始页面](/documentation/getting_started_zh.html)。基于NPM的`crosswalk-app-tools`是用于创建Crosswalk应用的打包工具。关于工具更多的细节，请参见[crosswalk-app-tools的使用](/documentation/crosswalk-app-tools.html)。

## 使用Lite编译
为了使用Lite编译你的应用，请在打包命令中添加参数`--android="lite"`：
```cmdline
$ crosswalk-pkg --android="lite" <your webapp path>
```

`crosswalk-pkg`将会下载Lite引擎并且创建一个你的应用的APK。


### 手动下载Crosswalk-Lite
或者你可以从[build目录](https://download.01.org/crosswalk/releases/crosswalk-lite/android/canary/latest/)手动下载Lite引擎到你本地的机器，然后运行：

```cmdline
$ crosswalk-pkg --android="lite" \
                --crosswalk=<downloads path>/crosswalk-17.46.455.1.zip \
				<your webapp path> 
```

其他的编译版本可以在[编译输出变量](https://download.01.org/crosswalk/releases/crosswalk-lite/android/canary/)上查找。

Lite只能通过标准的“嵌入模式”绑定你的应用，其中Lite库会被添加到你的APK中。不支持[共享模式](/documentation/shared_mode_zh.html)。

## 嵌入Lite webview

开发人员可以像使用Crosswalk一样，将Lite webview嵌入到它们基于Java的Android项目中。关于嵌入的详细细节，请参见[嵌入Crosswalk项目](https://crosswalk-project.org/documentation/android/ embedding_crosswalk_zh.html)和[使用Crosswalk AAR开发](https://crosswalk-project.org/documentation/android/embedding_crosswalk/crosswalk_aar_zh.html)。一个关于Lite的不同点就是[下文](#onXwalkReady)中描述的`onXWalkReady()`。

从Lite v17开始，我们为Lite添加了maven服务器。现在我们针对Crosswalk和Crosswalk Lite拥有两个分离的maven链接。请确认你项目中的maven链接是针对Crosswalk Lite的，而不是Crosswalk。

* Crosswalk: https://download.01.org/crosswalk/releases/crosswalk/android/maven2/ 
* Crosswalk Lite: https://download.01.org/crosswalk/releases/crosswalk-lite/android/maven2/

最新的Lite发行版本：<br/> 
 https://download.01.org/crosswalk/releases/crosswalk-lite/android/canary/latest/
  
### <a class="doc-anchor" id="onXwalkReady"></a>新的API: `org.xwalk.core.XWalkActivity.onXWalkReady()`

`onXwalkReady` API是为了检测Crosswalk环境是否可用而引入的。Crosswalk环境在如下两种场景下不可用：

* 共享模式: 需要从例如Google Play之类的app store下载。
* Crosswalk-Lite: `libxwalkcore.so`被LZMA压缩，首先需要被解压。

需要下列改变：

* `XWalkView` 初始化代码从`onCreate()`移到`onXWalkReady()`。
* `Activity` 应该从 `org.xwalk.core.XWalkActivity` 继承，而不是 `android.app.Activity`。
* 在`AndroidManifest.xml`中， `android:name`应该变为 `org.xwalk.core.XWalkApplication`。

```
import org.xwalk.core.XWalkActivity;
import org.xwalk.core.XWalkResourceClient;
import org.xwalk.core.XWalkUIClient;
import org.xwalk.core.XWalkView;

public class MainActivity extends XWalkActivity {
    XWalkView mXWalkView;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        //Don't initXWalkView here!
    }

    public void initXWalkView() {
        mXWalkView = new XWalkView(this);
        RelativeLayout Ll = (RelativeLayout) findViewById(R.id.layout1);
        Ll.addView(mXWalkView);

        mXWalkView.setUIClient(new XWalkUIClient(mXWalkView));
        mXWalkView.setResourceClient(new XWalkResourceClient(mXWalkView));
    
        mXWalkView.load("http:///xxxx/test.html", null);
    }
    
    @Override
    protected void onXWalkReady() {
        //initXWalkView in onXWalkReady().
        initXWalkView();
    }
}
```

## 运行一个Lite应用

一个通过Lite编译的应用第一次运行时，`libxwalkcore.so`必须被解压。下列对话框将会展示几秒钟：
  <img src='/assets/crosswalk-lite-uncompress-dialog.png' style="display: block; margin: 0 auto; width: 50%"/>
这个仅在第一次运行时展示。
