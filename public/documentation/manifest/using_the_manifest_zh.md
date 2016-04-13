# 使用manifest文件

这个页面展示了在部署一个Crosswalk应用的时候如何使用`manifest.json`文件。

<p>一个manifest可以通过以下"modes"使用：</p>

1.  [配置如何为Android打包一个Crosswalk应用](#Configure-Android-packaging).
2.  [如何将一个应用加载到一个嵌入式Crosswalk运行时](#Load-an-application-into-an-embedded-Crosswalk).

<h2 id="Configure-Android-packaging">配置Android packaging</h2>

一个Crosswalk manifest文件可以被用来作为为一个应用生成Android包的基础。详细指导请参见[the Getting started tutorial](/documentation/getting_started.html)。

然而，那个指导只使用了一个基本的manifest，并没有详细地解释某些manifest字段如何影响Android包生成。下面的链接提供了关于这些的额外信息：

* `图标`: [effect on Android packaging](/documentation/manifest/icons.html#Effect-on-Android-packaging)

* `permissions`: [effect on Android packaging](/documentation/manifest/permissions.html#Effect-on-Android-packaging)
如果没有被包含进`manifest.json`文件[loaded into an embedded Crosswalk](#Load-an-application-into-an-embedded-Crosswalk)，则这些字段不会生效。

<h2 id="Load-an-application-into-an-embedded-Crosswalk">加载一个应用到嵌入式Crosswalk</h2>

[embedding API](/documentation/apis/embedding_api.html) 使得你可以将一个Crosswalk运行时嵌入到一个Android应用中。[The embedding Crosswalk tutorial](/documentation/embedding_crosswalk.html)解释了如何使用这个API加载一个应用的主要HTML文件到嵌入式Crosswalk。

然而，API[exposes methods for loading an application from a manifest file](/documentation/apis/embedding_api.html)也可以作为另一种选择。从manifest加载一个应用的优点是它比通过URL加载一个应用提供更多的灵活性。

例如，如果你决定为你的应用修改入口点（例如，将`index.html`改名成`home.html`），你可以在manifest中进行而不用去修改任何Java应用代码。同样，如果一个新的字段对于Crosswalk manifest变得可用，你便可以利用你自己manifest中的这些字段而不用去修改任何Java代码。

为了展示该方法如何工作，[application developed for the embedded API tutorial](/documentation/embedding_crosswalk.html)可以很容易地用采用为manifest。遵循指导，然后按下文修改项目：

1.  添加一个`manifest.json`文件到应用的web根目录（如果是在指导中的嵌入API app便是`assets/`目录）：

        {
	        "name": "XWalkEmbed",
	        "xwalk_version": "0.0.1",
	        "start_url": "index.html"
        }

2.  修改`org.crosswalkproject.xwalkembed.MainActivity`类（在`src/`下）所以它使用`loadAppFromManifest()`方法而不是`load()`方法：

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

现在你可以运行应用，`manifest.json`中`start_url`属性所规定的HTML文件将会被加载。它是跟之前被加载的相同的`index.html`文件；但是使用manifest使得在不修改Java代码的情况下，更容易对项目进行修改。

注意在`manifest.json`中，某些被用于打包android应用的字段*不*被用于在嵌入式Crosswalk中加载manifest。关于这些字段详见[this section](#Configure-Android-packaging)。
