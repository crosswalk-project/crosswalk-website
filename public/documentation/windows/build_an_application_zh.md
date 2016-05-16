# 构造一个Crosswalk应用

Crosswalk是一个HTML5应用的运行时。这意味着任何已有的HTML5应用都可以在Crosswalk上运行，假设它们已经可以在一个现代浏览器上运行（Chrome,Firefox,Safari）。

为了阐述这份教程，我们尽可能地使用一个最简单的Crosswalk应用：一个HTML文件。

然而，因为Crosswalk应用将来需要集成到目标环境，所以它们需要一个额外的文件，`manifest.json`，该文件中包含了整合所需要的元数据。Manifest可以用于规定在不同环境下使用的图标，设置一个app描述，调整[内容安全策略的设置](http://developer.chrome.com/extensions/contentSecurityPolicy.html)，以及其他关于应用和目标环境集成的配置。

## <a class="doc-anchor" id="A-simple-application"></a>一个简单的应用

1.  首先，为项目创建一个名称为`xwalk-simple`的目录： 

        > mkdir xwalk-simple/
        > cd xwalk-simple/

2.  然后，复制一个图标文件到目录下，作为应用的图标。你可以使用下面这个图标：

    <img src="/assets/crosswalk.ico" style="display: block; margin: 0 auto"/>

    为了使用这个样例，右击图片并且选取<em>Save Image As...</em>（或者在浏览器中功能相同的选项）。将其命名为`icon.ico`存入`xwalk-simple`目录中。（注意这张图片来源于Crosswalk源代码并且通过[BSD认证](https://github.com/crosswalk-project/crosswalk/blob/master/LICENSE)。）

    如果你有自己喜欢的图标，将它拷贝到`xwalk-simple`目录。它应该是96像素的正方形或者更大。

3.  在`xwalk-simple`中创建两个文本文件。（可以使用任何编辑器创建，例如Notepad。）：

    1.  `index.html`

        这是一个单独的HTML文件，用于代表应用的用户接口。为了专注于这份教程的内容，我们不使用任何CSS或者JavaScript。

        内容应该如下：

            <!DOCTYPE html>
            <html>
              <head>
                <meta name="viewport"
                      content="width=device-width, initial-scale=1.0">
                <meta charset="utf-8">
                <title>simple</title>
              </head>
              <body>
                <p>hello world</p>
              </body>
            </html>

    2.  `manifest.json`

        该文件包含应用的元数据（见上文）。

       内容应该如下：

            {
              "name": "simple",
              "xwalk_package_id": "com.app.simple",
              "xwalk_version": "0.0.1",
              "start_url": "index.html",
              "icons": [
                {
                  "src": "icon.ico",
                  "sizes": "96x96",
                  "type": "image/vnd.microsoft.icon",
                  "density": "4.0"
                }
              ]
            }

        详细参见[manifest文档](/documentation/manifest_zh.html)。

3. 在`xwalk-simple`目录下，运行：

   ```
    > crosswalk-pkg --platforms=windows .
   ```
   通过工程目录路径作为最后一个参数，上面的`crosswalk-pkg`命令可以在任何地方运行。

   这条命令将下载Crosswalk库，按照规定好的manifest.json文件中的定义打包应用并且生成一个.msi文件（在我们的样例中是`com.app.simple-0.1.0.0.msi`）。这个.msi是Crosswalk Windows的64位安装包，且仅能运行在64位的Windows平台。一旦你完成了这些，便可以在目标上运行应用了。
