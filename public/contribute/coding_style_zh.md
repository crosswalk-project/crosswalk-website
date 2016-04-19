# Crosswalk代码风格

一般情况下，我们遵循Chromium的代码风格：http://www.chromium.org/developers/coding-style。

为了快速查阅，这里有一些关键规则：

 * *从不*使用tab用于缩进；使用空格代替。每一层缩进的空格数量依赖于编程语言：详细信息参见下方的链接。
 * 删除每一行后面的空格。
 * 删除每个文件后面的空白行。
 * 留意认证书的头部：如果是你从别处复制或者修改的某个文件，需要确定认证书头部保持完整。

## 针对C++的代码风格

针对C++代码，我们遵循[Google C++ 风格指南](http://google-styleguide.googlecode.com/svn/trunk/cppguide.xml)。

注意Tizen平台下的Crosswalk扩展程序应该使用C++11代码风格。

## 针对Java的代码风格

针对java代码，我们遵循Android开源风格指南：

 * [Android开源风格指南](http://source.android.com/source/code-style.html)
 * [针对Chromium的Java风格指南](http://www.chromium.org/developers/coding-style/java)

## 针对Python的代码风格

针对Python代码，我们遵循PEP-8， 包括以下特例：

*   使用两个空格的缩进来代替四个。
*   针对方法命名采用`混合大小写`代替`小写加下划线`。

（这些特例使Crosswalk Python代码符合Chromium Python代码。）

更多细节，请参见：

*   [PEP-8 guide](http://www.python.org/dev/peps/pep-0008/)
*   [针对Chromium的Python风格指南](http://www.chromium.org/chromium-os/python-style-guidelines)

## 针对HTML/JavaScript/CSS的代码风格

针对web资源，我们遵循[Chromium的"Web开发风格指南"](http://www.chromium.org/developers/web-development-style-guide).
