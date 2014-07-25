# Icons

The `icons` field specifies one or more icon graphics which visually represent the application. For example, the icon may be used by a task switcher, in a notifications area, or in an application list on the device. The image below shows the icon for the *townxelliot media player* being used in the *application list* (left) and *recent apps* (right) contexts on Android:

![Manifest icon used in "application list" and "recent apps" context on Android](assets/manifest-icon-contexts.png)

There are two variants of the `icons` field, depending on which version of Crosswalk you are using:

*   In Crosswalk 8 or later, the `icons` field is compatible with the [`icons` field in the W3C manifest specification](http://w3c.github.io/manifest/#icons-member).

*   In Crosswalk 1-7, the `icons` field is a non-standard extension, based on the format used in the [manifest for Chromium extensions](https://developer.chrome.com/apps/manifest/icons).

Note that in both cases, this field has no effect when used in a `manifest.json` file [loaded into an embedded Crosswalk on Android](#documentation/manifest/using_the_manifest/Load-an-application-into-an-embedded-Crosswalk).

## W3C variant (Crosswalk 8)

The field is a list of icon objects, each of which defines a URL for the icon as a minimum (via a `src` attribute). The icon's `type` attribute can be specified, describing the graphics format of the icon; and the optimal rendering `sizes` and screen pixel `density` for which the icon is intended can also be set.

As an example, here is a simple Crosswalk manifest which specifies three icons: one to be used when a 64x64 pixel icon is needed; one to be used when a 128x128 pixel icon is needed; and one to be used when a 128x128 pixel icon is needed and the screen pixel density is 2:

    {
      "name": "simple_app",
      "description": "A simple Crosswalk application",
      "version": "0.0.0.1",
      "icons": [
        {
          "src": "icon_small.png",
          "type": "image/png",
          "sizes": "64x64"
        },
        {
          "src": "icon_large.png",
          "type": "image/png",
          "sizes": "128x128"
        },
        {
          "src": "icon_large_hires.png",
          "type": "image/png",
          "sizes": "128x128",
          "density": "2"
        }
      ],
      "start_url": "index.html",
      "display": "fullscreen",
      "orientation": "landscape"
    }

What's the point of matching against "density"?

???what is density? see screens/screen_measurements.md

Imagine you have an icon which is 128x128 raw pixels. Now you put it on a device with one physical pixel per raw pixel where a 128x128 pixel icon is required. Each raw pixel of the original image occupies one pixel in the icon on the device's screen.

Now imagine a different device with 2 physical pixels per raw pixel, which also requires a 128x128 icon. If the same image file is used to fill that space on the screen, each raw pixel of the image occupies 4 pixels (2x2) on the device screen. The result is a blurred image, as the same single pixel is used to fill 4 pixels on the screen.

To resolve this, you could provide a larger 256x256 raw pixel image for screens with pixel density of 2. Then each pixel on the device screen would correspond to one pixel of the raw image again.

From http://www.whatwg.org/specs/web-apps/current-work/#attr-link-sizes:
"An icon that is 50 CSS pixels wide intended for displays with a device pixel density of two device pixels per CSS pixel (2x, 192dpi) would have a width of 100 raw pixels."

So the raw size of the image you are providing as an icon is calculated as:

raw image file dimensions = dimensions of required icon in device-independent pixels * device screen pixel density

???TODO

## Chromium extensions variant (Crosswalk 1-7)

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

Setting icons for multiple sizes [can affect Android packaging for your application](#documentation/manifest/using_the_manifest/Configure-Android-packaging).

As a minimum, the `"128"` key (for a 128x128 pixel image) should be specified. The preferred file format is PNG, but BMP, GIF, ICO, and JPEG formats may also be used.

## Effect on Android packaging

Rather than affecting the Crosswalk runtime on Android directly, the `icons` field affects how an application is packaged by [`make_apk.py`](#documentation/getting_started/run_on_android).

If the <a href="#documentation/manifest/icons"><code>icons</code> field</a> contains multiple keys, the `make_apk.py` script will map the corresponding icon files to [Android drawable resources](http://developer.android.com/guide/topics/resources/providing-resources.html) as follows:

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

When the application is installed, Android will use the file appropriate for the target's screen resolution as the application icon in the home screen, application list and other relevant locations.

## Acknowledgements

The *townxelliot media player* icon was modified from an original icon created by [wpzoom.com](http://www.wpzoom.com/) under a [CC BY-SA licence](https://creativecommons.org/licenses/by-sa/3.0/). It was sourced from [Find Icons](http://findicons.com/icon/457729/radio?id=457887).

The modified icon is made available here to satisfy the terms of the licence, and is released under the same CC BY-SA licence:

<img alt="Modified radio icon" title="Modified radio icon" src="assets/radio.png">
