# Icons (Crosswalk custom field)

*Note: This section only applies to the custom <a href="#documentation/manifest/icons_(custom)"><code>icons</code> field</a>, not to the W3C-compatible `icons` field used in more recent versions of Crosswalk.*

The `icons` field implementation in the earliest versions of Crosswalk is non-standard and based on the format used in the [manifest for Chromium extensions](https://developer.chrome.com/apps/manifest/icons).

The field is an object whose keys represent the icon's pixel size (width and height are the same); the value for each key is the path to the appropriate image file, relative to the application root. For example, here's a manifest with icons at three different sizes:

    {
      "name": "app name",
      "description": "a sample description",
      "version": "1.0.0",
      "app": {
        "launch": {
          "local_path": "index.html"
        }
      },
      "icons": {
        "16":  "icon16.png",
        "48":  "icon48.png",
        "128": "icon128.png"
      }
    }

Setting icons for multiple sizes [can affect Android packaging for your application](#documentation/manifest/using_the_manifest/Use-it-to-create-an-Android-package).

As a minimum, the `"128"` key (for a 128x128 pixel image) should be specified. The preferred file format is PNG, but BMP, GIF, ICO, and JPEG formats may also be used.

## Effect on Android packaging

Rather than affecting the Crosswalk runtime on Android directly, the `icons` field ???

If the <a href="#documentation/manifest/icons_(custom)"><code>icons</code> field</a> contains multiple keys, the `make_apk.py` script will map the corresponding icon files to [Android drawable resources](http://developer.android.com/guide/topics/resources/providing-resources.html) as follows:

|Icon key range...|`make_apk.py` copies the icon file to...|
|:---------------:|----------------------------------------|
|1-36             |`res/drawable/ldpi/icon.<suffix>`       |
|37-72            |`res/drawable/mdpi/icon.<suffix>`       |
|73-95            |`res/drawable/hdpi/icon.<suffix>`       |
|96-119           |`res/drawable/xhdpi/icon.<suffix>`      |
|120-143          |`res/drawable/xxhdpi/icon.<suffix>`     |
|144-167          |`res/drawable/xxxhdpi/icon.<suffix>`    |

where `<suffix>` is the file suffix (`.png`, `.jpg` etc.) of the original file.

For example, the `icons` field in this manifest:

    {
      "name": "app name",
      "description": "a sample description",
      "version": "1.0.0",
      "app": {
        "launch": {
          "local_path": "index.html"
        }
      },
      "icons": {
        "16":  "icon16.png",
        "48":  "icon48.png",
        "128": "icon128.png"
      }
    }

would cause the following resources to be added to the Android application package:

    res/drawable/ldpi/icon.png   (copied from icon16.png)
    res/drawable/hdpi/icon.png   (copied from icon48.png)
    res/drawable/xxhdpi/icon.png (copied from icon128.png)

When the application is installed, Android will use the file appropriate for the target's screen resolution as the application icon in the home screen, app list and other relevant locations.
