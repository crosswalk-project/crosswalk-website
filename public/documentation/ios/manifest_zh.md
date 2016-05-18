# Manifest

**_这个页面主要描述了在iOS web应用中，Crosswalk的manifest文件的使用。_**

Manifest文件（例如manifest.plist）位于你工程源文件中，被用来定义应用中的元数据（名称、图标等）以及它们应该如何展现自己。

针对iOS平台的Crosswalk使用json格式来描述资源配置文件，并且是基于[W3C Web应用中的Manifest规范](http://w3c.github.io/manifest/)。同时，针对iOS平台的Crosswalk通过以关键字`xwalk_`为前缀的附加字段来扩展W3C的manifest说明。

当前支持的成员字段如下：

<table style="table-layout: auto;">
 <tr><th>字段名称</th><th>类型</th><th width=100%>描述</th></tr>
 <tr><td>start_url</td><td>String</td><td nowrap>定义了web应用的入口URL</td></tr>
 <tr><td>xwalk_extensions</td><td>Array</td><td>Crosswalk extension打包后的信息</td></tr>
 <tr><td>cordova_plugins</td><td>Array</td><td>打包的Cordova插件的信息</td></tr>
</table>

### xwalk_extensions

在`xwalk_extension`中的项应该是**String**类型，定义如下:

<table style="table-layout: auto;">
 <tr><th>类型</th><th width=100%>描述</th><th>样例</th></tr>
 <tr><td>字符串</td><td>被打包的extension命名空间</td><td>`"xwalk.experimental.presentation"`</td></tr>
</table>

### cordova_插件

因为对Cordova的插件的支持是基于Cordova扩展，所以我们需要在`xwalk_extension`中添加*"xwalk.cordova"*。

在`cordova_plugins`中，每一项应该是**Dictionary**类型，有如下定义：

<table style="table-layout: auto;">
 <tr><th>关键词</th><th>值类型</th><th width="100%">内容</th><th>样例</th></tr>
 <tr><td>class</td><td>String</td><td>Cordova插件的原生属性类类型</td><td>`"CDVFile"`</td></tr>
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

