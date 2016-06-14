# 安卓扩展程序

本教程叙述了如何使用Java编写Crosswalk安卓扩展程序。

接下来您将编译的是一个简单的“显示”插件程序。它接收字符串，加上一个前缀"You said:"并返回。本教程使用如此简化的插件程序，目的是能使用更多篇幅来叙述Crosswalk安卓扩展程序背后的原理。

本教程**不**包含web开发的最佳案例。例如，简单起见，没有包括[grunt](http://gruntjs.com/)，[bower](http://bower.io/)和任何第三方前端的库。它主要是帮助你探索混合式Crosswalk应用的一点一滴以及它们如何组装在一起的。

本教程使用了[Ant](http://ant.apache.org/)和[Ivy](http://ant.apache.org/projects/ivy.html)来编译插件程序。或者，您也可以使用Java工具例如[gradle](http://www.gradle.org/)。

**阅读完本教程**，您将可以在Crosswalk安卓应用上面开发自己的Java插件程序。

## 教程简介

在本教程中，您将编译一个使用Java编写的Crosswalk安卓扩展程序。它主要包含俩部分：

<ol>

<li>
<p><strong>一个Crosswalk插件程序</strong></p>

<p>插件程序包括：</p>

<ul>
<li>Java代码：打包到jar文件中的标准Android/Java类。</li>
<li>JavaScript包装器：一个JavaScript文件，作用是暴露Java代码到运行在Crosswalk上面的app中。</li>
<li>配置：连接JavaScript包装器到Java类的JSON文件。</li>
</ul>

<p>插件程序提供了一种显示服务，它添加前缀到接收的字符串并返回。</p>

<p>注意，如果需要，一个Crosswalk应用可以使用多个插件程序。</p>

</li>

<li>
<p><strong>一个HTML5应用程序</strong></p>

<p>这是一个“存在于”Android应用内部的web程序，它使用Crosswalk作为runtime。包含了标准的web资源例如HTML文件，Javascript文件，图片以及字体等等。</p>

<p>在web程序中，Crosswalk插件程序被上面提到的Javascript包装器代码调用。在这个例子中，从插件程序返回的字符串将在DOM元素中被渲染。</p>

</li>

</ol>

<p>这个工程也包含一些文件。这些文件的作用是帮助打包上面的文件到<code>.apk</code>文件，apk文件是安装在安卓机器上的。</p>

本教程中所有的源码可以从[crosswalk-samples](https://github.com/crosswalk-project/crosswalk-samples/releases)获取, 或者从github https://github.com/crosswalk-project/crosswalk-samples (在`extensions-android`目录里)。
