# 资源配置

**_这个页面主要描述了在iOS web应用中，Crosswalk配置文件的使用。_**

资源配置文件（例如manifest.plist）位于你工程源中，被用来具体规范应用中的元数据（名称、图标等）以及它们应该如何展现自己。

针对iOS平台的Crosswalk使用json格式来描述资源配置文件，并且基于[W3C Manifest for Web Application](http://w3c.github.io/manifest/)。同时，针对iOS平台的Crosswalk通过以关键字`xwalk_`为前缀的附加字段来扩展W3C的资源配置说明。

当前支持的成员字段如下：

<table style="table-layout: auto;">
 <tr><th>字段名称</th><th>类型</th><th width=100%>描述</th></tr>
 <tr><td>start_url</td><td>String</td><td nowrap>定义了web应用的开始URL</td></tr>
 <tr><td>xwalk_extensions</td><td>Array</td><td>Crosswalk extension打包后的信息</td></tr>
 <tr><td>cordova_plugins</td><td>Array</td><td>打包的Cordova插件的信息</td></tr>
</table>

### xwalk_extensions

在`xwalk_extension`中的项应该是**String**类型，定义如下:

<table style="table-layout: auto;">
 <tr><th>类型</th><th width=100%>描述</th><th>样例</th></tr>
 <tr><td>字符串</td><td>打包的extension命名空间</td><td>`"xwalk.experimental.presentation"`</td></tr>
</table>

### cordova_插件

因为Cordova的插件支持是基于Cordova Extension，我们需要在`xwalk_extension`中添加*"xwalk.cordova"*。

在`cordova_plugins`中的项应该是**Dictionary**。每一项应该有如下定义：

<table style="table-layout: auto;">
 <tr><th>关键词</th><th>值类型</th><th width="100%">内容</th><th>样例</th></tr>
 <tr><td>class</td><td>String</td><td>Cordova插件的本地属性类类型</td><td>`"CDVFile"`</td></tr>
 <tr><td>name</td><td>String</td><td>在JavaScript中Cordova插件的命名空间</td><td>`"File"`</td></tr>
</table>

## 样例

```json
{
    "start_url": "index.html",
    "xwalk_extensions": [
        "xwalk.cordova",
        "xwalk.experimental.presentation"
    ],
    "cordova_plugins": [
        {
            "class": "CDVFile",
            "name": "File"
        },
        {
            "class": "CDVDevice",
            "name": "Device"
        }
    ]
}
```

