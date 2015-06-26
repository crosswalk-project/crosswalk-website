# Manifest

**_This page describes the usage of manifest file in Crosswalk for iOS web applications._**

## Concept

The manifest file (e.g. manifest.plist) is located in your project source and used to specify meta data for your application (title, icon, etc.) as well as how it should behave and present itself.

Crosswalk for iOS uses json format as its manifest format, and based on the [W3C Manifest for Web Application](http://w3c.github.io/manifest/). Crosswalk for iOS also extends the W3C manifest spec with additional fields prefixed with `xwalk_` keyword.

Here're the currently supported member fields:

  | Field Name | Type | Content |
  | ------------- |:-------------:| -----:|
  | start_url | String | _Defines the start url of the web application_ |
  | xwalk_extensions | Array | _Information of the packaged Crosswalk Extensions_ |
  | cordova_plugins | Array | _Information of the packaged Cordova plugins_ |

--

### xwalk_extensions

The type of the items in `xwalk_extension` should be **String**, which defines as follows:

  | Type | Content | Example |
  | ------------- |:-------------:|:-----:|
  | String | _The packaged extension namespace_ | `"xwalk.experimental.presentation"` |

### cordova_plugins

As Cordova plugin support is based on the Cordova Extension, we need to add *"xwalk.cordova"* in `xwalk_extension` section to enable it.

The type of the items in `cordova_plugins` should be **Dictionary**. Every item should has the definitions as follows:

  | Key | Value Type | Content | Example |
  | ------------- | ------------- |:-------------:|:-----:|
  | class | String | _The native entry class type of the Cordova plugin_ | `"CDVFile"` |
  | name | String | _The namespace of the Cordova plugin in javascript_ | `"File"` |


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

