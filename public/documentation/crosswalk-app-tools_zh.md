# Crosswalk App Tools

Crosswalk-app-tools用来创建和编译Crosswalk应用，是一款新的、基于NPM发布的工具。它取代了基于Python的make_apk.py脚本。由于它基于Node.js，因此是一款跨平台的工具。目前支持的平台有Windows，Linux，和Apple OS X。

### 预装软件

* Node.js
* NPM
* Android SDK with 5.0 (target-21) or later
* Java JDK
* Apache Ant

安装细节参见[开始章节](/documentation/getting_started_zh.html).

### 安装

    > npm install -g crosswalk-app-tools

可能需要Root或者管理员权限。

安装完成之后，检查环境依赖是否满足：
    > crosswalk-app check android

会提供俩个可执行的工具：

* crosswalk-app: 是一个实现低层次的辅助命令工具
* crosswalk-pkg: 是打包的主要工具

## 用法

### crosswalk-app
 创建Crosswalk工程应用和编译工具
 
<div class="usage">
 用法: crosswalk-app &lt;options&gt;

   crosswalk-app check [&lt;platforms&gt;]    检查平台配置
                                        如果没有设置平台选项,会检查所有平台配置

   crosswalk-app manifest &lt;path&gt;        在&lt;路径&gt;下创建并初始化web应用的manifest文件;
                --package-id=&lt;pkg-id&gt;   标准的包名,例如 com.example.foo
                --platforms=&lt;target&gt;    可选,例如 "windows"

   crosswalk-app create &lt;package-id&gt;    创建包名为&lt;package-id&gt;的工程
                 --platforms=&lt;target&gt;   可选,例如 "windows"

   crosswalk-app build &lt;type&gt; [&lt;dir&gt;]   编译工程,生成与平台相应的目标文件(即android下生成APK,windows下生成.exe等)
                                        type = [release|debug]
                                        如果没有设置,默认是"debug"
                                        默认在当前路径下进行编译

   crosswalk-app platforms              显示所有可用平台

   crosswalk-app help                   显示工具常用方法

   crosswalk-app version                显示版本信息

 <strong>安卓平台下的可选项</strong>
 用法: crosswalk-app create 'android' &lt;options&gt;

   --android-crosswalk                  指定Crosswalk版本周期名称(stable/beta/canary)
						                或者 指定Crosswalk版本号 (w.x.y.z)
						                或者 下载的Crosswalk压缩包路径
						                或者 "xwalk_app_template"路径(编译Crosswalk源码，会产生xwalk_app_template文件夹，适合开发者自己定制Crosswalk包)
   --android-lite                       使用crosswalk-lite,详情参见Crosswalk Wiki
   --android-shared                     依赖于共享模式Crosswalk的安装
   --android-targets                    用于工程的目标ABIs

适用于 'android'的环境变量

   CROSSWALK_APP_TOOLS_CACHE_DIR        保存下载文件的路径

'build'命令的可选项
 用法: crosswalk-app build &lt;options&gt;

   --android-targets                    用于编译的目标ABIs

 <strong>Windows平台下的可选项</strong>

   --windows-crosswalk                  crosswalk压缩包的路径

</div>

### crosswalk-pkg
 Crosswalk工程应用打包工具

<div class="usage">
 用法: crosswalk-pkg &lt;options&gt; &lt;path&gt;

  &lt;options&gt;
    -a --android=&lt;android-conf&gt;      安卓平台的额外配置
    -c --crosswalk=&lt;version-spec&gt;    指定Crosswalk版本或者路径
    -h --help                        打印用法信息
    -k --keep                        保留用于调试的构造树
    -m --manifest=&lt;package-id&gt;       初始化manifest.json文件
    -p --platforms=&lt;android|windows&gt; 指定目标平台
    -r --release                     编译正式版
    -t --targets=&lt;target-archs&gt;      指定CPU架构
    -v --version                     显示工具版本

  &lt;path&gt;
    包含web应用的目录路径

  &lt;android-conf&gt;
    带有引号的字符串，指定额外配置，例如 "shared"
      "shared"  编译APK，这个APK依赖谷歌应用商店中的crosswalk
      "lite"    使用crosswalk-lite，详情参见Crosswalk Wiki

  &lt;package-id&gt;
    标准的包名，例如com.example.foo，需要
     - 包括3个或者更多以“.”隔开的部分
     - 需要以小写字母开头

  &lt;target-archs&gt;
    指定应用的CPU架构
    目前支持的ABIs有：armeabi-v7a, arm64-v8a, x86, x86_64
     - 前缀的字母将会匹配相应平台，例如"a"，"ar"，或者"arm" 都将生成基于ARM运行的APK
     - "x"和"x8"也是类似的，他们匹配的是x86和x86_64俩个平台, 但是"x86"只能匹配32位的x86平台
     - "32" and "64"将会编译相应的ARM和x86平台下的APK
     - 默认情况下，将会采用"32"，创建32位的可安装程序
    示例： --targets="arm x86"将会编译ARM和32位的x86安装包

  &lt;version-spec&gt; 本参数是指定使用打包的Crosswalk详细信息(同上方crosswalk-app下的参数"--android-crosswalk"一样)
     - 指定Crosswalk版本周期名称，例如stable/beta/canary
     - 指定Crosswalk版本号，例如14.43.343.25
     - 下载的Crosswalk压缩包路径，例如$HOME/Downloads/crosswalk-14.43.343.25.zip
     - 编译Crosswalk源码产生的xwalk_app_template路径，例如crosswalk/src/out/Release/xwalk_app_template
    当传递一个本地的文件或者路径时，只有包含ABIs时，才能进行编译
    详情参见&lt;target-archs&gt;。

  环境变量
    CROSSWALK_APP_TOOLS_CACHE_DIR=&lt;path&gt;: 保存下载文件的路径

</div>


## 示例：创建并打包应用

开始之前，您需要一个manifest文件和一个html文件。Manifest文件包含应用名字和应用的一些配置，一个简化的manifest.json文件如下：

<pre><code>{
  "name": "My first Crosswalk application",
  "start_url": "index.html",
  "xwalk_package_id": "com.example.foo"
}
</code></pre>

然后在本级目录下面，添加index.html：

    <!DOCTYPE html>
    <html>
       <head>
          <title>My first Crosswalk application</title>
       </head>
       <body>This is my first Crosswalk application</body>
    </html>

最后，生成apk文件：

    > crosswalk-pkg <path>

上述命令会创建一个工程，下载并导入Crosswalk包，然后利用上面的文件创建安装包。

## 许可证

本教程遵循Apache 2.0许可证，详情参见LICENSE-APACHE-V2。
