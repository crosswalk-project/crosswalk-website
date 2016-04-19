# 应用入口点

一个web应用的*入口点*就是第一次启动一个应用时应该首先被加载的资源（或者资源集合）。

推荐使用一个单独的HTML文件作为入口点，并且使用`start_url`规定它。

`start_url`是[专门针对W3C的manifest](https://w3c.github.io/manifest/#start_url-member)的一部分。

样例：

    {
      "name": "app name",
      "start_url": "index.html"
    }


