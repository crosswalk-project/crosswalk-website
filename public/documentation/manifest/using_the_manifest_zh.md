# 使用manifest文件

这个页面叙述了在部署Crosswalk应用的时候如何使用`manifest.json`文件。

<p>manifest可以通过以下"方式"使用：</p>

1.  [配置将Crosswalk应用打包成Android应用](#Configure-Android-packaging_zh).
2.  [将一个应用加载到嵌入式Crosswalk运行时](#Load-an-application-into-an-embedded-Crosswalk_zh).

<h2 id="Configure-Android-packaging_zh">配置Android打包</h2>

Crosswalk manifest文件可以被用来作为一个应用生成Android包的基础。详情参见[入门指导](/documentation/getting_started_zh.html)。

然而，上述教程只使用了一个基本版的manifest，并没有详细地解释某些manifest字段如何影响Android打包过程。下面的链接提供了关于这些的额外信息：

* `图标`: [在Android打包过程中的作用](/documentation/manifest/icons_zh.html#Effect-on-Android-packaging_zh)

* `permissions`: [在Android打包过程中的应用](/documentation/manifest/permissions_zh.html#Effect-on-Android-packaging_zh)
注意，在[嵌入式Crosswalk中](#Load-an-application-into-an-embedded-Crosswalk_zh)，上述俩个字段在`manifest`中不会生效。

<h2 id="Load-an-application-into-an-embedded-Crosswalk_zh">加载应用到嵌入模式Crosswalk</h2>

[嵌入式API](/documentation/apis/embedding_api_zh.html) 使得你可以将Crosswalk运行时环境嵌入到一个Android应用中。[嵌入模式Crosswalk指南](/documentation/embedding_crosswalk_zh.html)解释了如何使用embedding API加载一个应用的主HTML文件到嵌入式Crosswalk。

然而，embedding API也提供了另外一个选择：[从manifest文件加载应用](/documentation/apis/embedding_api_zh.html)。比起通过URL加载应用,从manifest文件加载应用的优点是更加灵活。

例如，如果你决定修改应用的入口点（例如，将`index.html`改名成`home.html`），你可以在manifest中修改，而不用去修改任何Java代码。同样，如果新字段对于Crosswalk manifest变得可用，你可以在自己的manifest文件中利用这些字段的优势，而不用去修改任何Java代码。

为了展示该方法如何工作，可以很容易地遵循[嵌入模式API的应用开发指南](/documentation/embedding_crosswalk_zh.html)使用manifest。按照上述指南做完所有步骤，然后按如下内容修改项目：

1.  添加一个`manifest.json`文件到应用的web根目录（对于教程中使用embedding API的应用，根目录便是`assets/`目录）：

        {
	        "name": "XWalkEmbed",
	        "xwalk_version": "0.0.1",
	        "start_url": "index.html"
        }

2.  修改`org.crosswalkproject.xwalkembed.MainActivity`类（在`src/`下），它使用`loadAppFromManifest()`方法而不是`load()`方法：

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

当你运行应用时，`manifest.json`中`start_url`属性所规定的HTML文件将会被加载。它跟之前被加载的`index.html`文件相同；但是修改应用时，使用manifest会更加容易，而不用修改Java代码。

注意，在嵌入式crosswalk模式中，`manifest.json`中某些被用于打包android应用的字段*不*会奏效。关于这些字段详见[this section](##Configure-Android-packaging_zh)。
