# 应用入口点

web应用的*入口点*就是第一次应用第一次启动时应该首先被加载的资源（或者资源集合）。

推荐使用一个单独的HTML文件作为入口点，并且使用`start_url`指定它。

`start_url`是[W3C manifest标准](https://w3c.github.io/manifest/#start_url-member)的一部分。

样例：

    {
      "name": "app name",
      "start_url": "index.html"
    }


