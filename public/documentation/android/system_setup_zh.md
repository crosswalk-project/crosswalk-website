# 系统配置

本节叙述如何使用[crosswalk-app-tools](/documentation/crosswalk-app-tools_zh.html)来编译和调试，crosswalk-app-tools是一个全新的，简易的，基于NPM的工具。[过时的make_apk.py](make_apk_docs_zh.html)依然可以用。

下面这些步骤将引导您如何开发基于安卓系统的Crosswalk应用。

## 安装工具
在您的开发机上编译安卓程序，需要以下工具。无论是Windows还是Linux系统，这些工具是相同的。请下载并安装正确的版本。

* [Java JDK](#Java)
* [Apache Ant](#Ant)
* [Android SDK](#Android)
* [NPM](#NPM)
* [Crosswalk App Tools](#Crosswalk)

### <a class="doc-anchor" id="Java"></a>安装Oracle Java Development Kit (JDK)
   Oracle JDK下载安装地址：http://www.oracle.com/technetwork/java/javase/downloads/ (Java 7和8可以正常工作)

### <a class="doc-anchor" id="Ant"></a>安装Apache Ant

   编译工具Apache Ant下载地址： http://www.apache.org/dist/ant/binaries/ （版本1.9.3可以正常工作） 

### <a class="doc-anchor" id="Android"></a>安装Android SDK

   * Android Studio下载地址： <a href='http://developer.android.com/sdk/index.html' target='_blank'>http://developer.android.com/sdk/index.html</a>.

   * 启动*SDK Manager*，可以从命令行或者Android Studio里面启动。
      <p>在Windows上：<pre><code>> "SDK Manager.exe"</code></pre></p>
      <p>在Linux上：<pre><code>> android</code></pre></p>
      <p>或者在Android Studio中：</p>
      <img src="/assets/sdk-manager1.png" style="margin: 0 auto"/>

   * 在SDK Manager中，安装您感兴趣的版本：Platform tools, Build tools和SDK Platform。
       <img src="/assets/sdk-manager-select.png" style="display: block; margin: 0 auto"/>

### <a class="doc-anchor" id="NPM"></a>安装node.js和npm

   安装适合您系统的node.js:https://nodejs.org/en/download/. 其中附带安装npm。

### <a class="doc-anchor" id="Crosswalk"></a>安装crosswalk-app-tools
在命令行界面使用npm安装crosswalk-app-tools

        > npm install -g crosswalk-app-tools

   注意: 如果您使用了代理, 请参考[这里](/documentation/npm-proxy-setup_zh.html)

## <a class="doc-anchor" id="Verify-your-environment"></a>验证环境
通过运行下面的命令，来验证您正确地安装了工具：

在windows上：
```
C:\dev>crosswalk-app check android
  + Checking host setup for target android
  + Checking for android... C:\dev\android\sdk\tools\android.bat
  + Checking for ant... C:\dev\apache-ant-1.9.4\bin\ant
  + Checking for java... C:\ProgramData\Oracle\Java\javapath\java.exe
  + Checking for ANDROID_HOME... C:\dev\Android\
  ...
```
如果界面显示有些工具丢失，那么在环境变量中，添加目录添加到指定路径。
*注意：* 目前，如果lzma没有安装，会报如下的ERROR，您可以忽略这个错误。
```
  ERROR: Checking for lzma... null
```

## 下一步
您的系统已经具备使用Crosswalk开发安卓应用了。

