# Manifest

**_This page describes the use of the manifest file in Crosswalk for iOS web applications._**

The manifest file (e.g. manifest.plist) is located in your project source and used to specify meta data for your application (title, icon, etc.) as well as how it should behave and present itself.

Crosswalk for iOS uses json format as its manifest format, and based on the [W3C Manifest for Web Application](http://w3c.github.io/manifest/). Crosswalk for iOS also extends the W3C manifest spec with additional fields prefixed with `xwalk_` keyword.

The currently supported member fields are below:

<table style="table-layout: auto;">
 <tr><th>Field Name</th><th>Type</th><th width=100%>Description</th></tr>
 <tr><td>start_url</td><td>String</td><td nowrap>Defines the start url of the web application</td></tr>
 <tr><td>xwalk_extensions</td><td>Array</td><td>Information of the packaged Crosswalk Extensions</td></tr>
 <tr><td>cordova_plugins</td><td>Array</td><td>Information of the packaged Cordova plugins</td></tr>
</table>

### xwalk_extensions

The type of the items in `xwalk_extension` should be **String**, defined as follows:

<table style="table-layout: auto;">
 <tr><th>Type</th><th width=100%>Description</th><th>Example</th></tr>
 <tr><td>String</td><td>The packaged extension namespace</td><td>`"xwalk.experimental.presentation"`</td></tr>
</table>

### cordova_plugins

Since the Cordova plugin support is based on the Cordova Extension, we need to add *"xwalk.cordova"* in the `xwalk_extension` section to enable it.

The type of the items in `cordova_plugins` should be **Dictionary**. Every item should have the definitions as follows:

<table style="table-layout: auto;">
 <tr><th>Key</th><th>Value type</th><th width="100%">Content</th><th>Example</th></tr>
 <tr><td>class</td><td>String</td><td>The native entry class type of the Cordova plugin</td><td>`"CDVFile"`</td></tr>
 <tr><td>name</td><td>String</td><td>The namespace of the Cordova plugin in JavaScript</td><td>`"File"`</td></tr>
</table>

## Example

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

