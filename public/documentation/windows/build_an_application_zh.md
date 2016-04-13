# 构建一个Crosswalk应用

Crosswalk是一个HTML5应用的运行时。这意味着任何存在的HTML5应用都应该可以在Crosswalk上运行，假设它们已经在一个现代浏览器上运行（Chrome,Firefox,Safari）。

为了这份教程的目的，我们使用可能的最简单的Crosswalk应用：一个HTML文件。

然而，因为Crosswalk应用用于整合目标环境，它们需要一个额外的文件，`manifest.json`，包含了目的中的元数据。manifest可以用于规定在不同情况下使用的图标，设置一个app描述，调整[内容安全策略的设置](http://developer.chrome.com/extensions/contentSecurityPolicy.html)，以及其他关于app和目标环境整合的配置。

## <a class="doc-anchor" id="A-simple-application"></a>一个简单的应用

1.  首先，为项目创建一个称为`xwalk-simple`的目录： 

        > mkdir xwalk-simple/
        > cd xwalk-simple/

2.  然后，复制一个图标文件到目录下，作为应用的图标。你可以使用下面这个图标：

    <img src="/assets/crosswalk.ico" style="display: block; margin: 0 auto"/>

    为了使用这个样例，右击图片并且选取<em>Save Image As...</em>（或者在浏览器中它的等效）。将其命名为`icon.ico`存入`xwalk-simple`目录中。（注意这张图片来源于Crosswalk源代码并且通过[BSD licensed](https://github.com/crosswalk-project/crosswalk/blob/master/LICENSE)。）

    如果你有自己最喜欢的图标，复制那个到`xwalk-simple`目录。它应该是96像素的正方形或者更大。

3.  在`xwalk-simple`中创建两个文本文件。（可以使用任何编辑器创建，例如Notepad。）：

    1.  `index.html`

        这个一个单独表示应用用户接口的HTML文件。为了这份教程的目的，我们不使用任何CSS或者JavaScript。

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

        这个包含应用的元数据（见上文）。

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

        详细参见[manifest文档](/documentation/manifest.html)。

3. 在`xwalk-simple`目录下，运行：

   ```
    > crosswalk-pkg --platforms=windows .
   ```
   通过将到达你工程目录的路径作为最后一个参数，上面的`crosswalk-pkg`命令可以在任何地方运行。

   这个将下载Crosswalk库，按照规定好的manifest.json文件中的定义打包应用并且生成一个.msi文件（在我们的样例中是`com.app.simple-0.1.0.0.msi`）。.msi现在是针对Windows平台的一个64-bit的Crosswalk的构建并且将只能运行在64位的Windows。一旦你已经完成这些，你已经可以在目标上运行应用了。
