# Icons

The `icons` field specifies one or more icon graphics which visually represent the application. 

An icon may be used by a task switcher, in a notifications area, or in an application list on the device. The image below shows the icon for the *townxelliot media player* being used in the *application list* (left) and *recent apps* (right) contexts on Android:

![Manifest icon used in "application list" and "recent apps" context on Android](/assets/manifest-icon-contexts.png)

The `icons` field is compatible with the [`icons` field in the W3C manifest specification](http://w3c.github.io/manifest/#icons-member).

Note that this field has no effect when used in a `manifest.json` file [loaded into an embedded Crosswalk on Android](/documentation/manifest/using_the_manifest.html#Load-an-application-into-an-embedded-Crosswalk).

The field is a list of icon objects, each of which defines a URL for the icon as a minimum (via a `src` attribute). The icon's `type` attribute can be specified, describing the graphics format of the icon; and the optimal rendering `sizes` and screen pixel `density` for which the icon is intended can also be set.

As an example, here is a simple Crosswalk manifest which specifies three icons: one to be used when a 64x64 pixel icon is needed; one to be used when a 128x128 pixel icon is needed; and one to be used when a 128x128 pixel icon is needed and the screen pixel density is 2:

    {
      "name": "simple_app",
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
          "src": "icon_large_hd.png",
          "type": "image/png",
          "sizes": "128x128",
          "density": "2"
        }
      ],
      "start_url": "index.html",
      "display": "fullscreen",
      "orientation": "landscape"
    }

The properties for each object in the `icons` array are described below.

### src

The path to the icon file, relative to the manifest.

### type

The [MIME type](http://www.iana.org/assignments/media-types/media-types.xhtml) of the icon.

### sizes

The icon sizes for which this image is suitable, as space-separated `<width>x<height>` substrings. For example:

*   `"sizes": "128x128"`: this image is suitable for use as a 128x128 pixel icon.
*   `"sizes": "128x128 256x256"`: this image is suitable for use as a 128x128 or 256x256 pixel icon.

Note that the value for the `sizes` property is *not* the intrinsic size of the image (i.e. the pixel size stored in the image file): it is the set of icon sizes for which this image file is suitable. This means it is possible to have an image file with an intrinsic size smaller or larger than the icon it will serve as.

For example, an image file may be 128x128 pixels in size; but the manifest may state that this image can be used for 128x128 or 256x256 pixel icons. When used as a 256x256 pixel icon, the image file will be scaled up (2x) to fit the required icon size (which may result in some degradation in its appearance).

### density

The `density` property specifies which screen densities an icon should be used for. Coupled with the `sizes` property, it makes it possible to adjust which image file is delivered for a particular icon size at a given screen density.

If you are unfamiliar with the concept of screen density, see the [screen measurements page](/documentation/screens/screen_measurements.html).

The property's value is a float representing the screen density for which the image is suitable, in dots per pixel (dppx). For example:

*   `"density": "2"`: this image is suitable for screens with a density of 2dppx.
*   `"density": "1.5"`: this image is suitable for screens with a density of 1.5dppx.

If `density` is not set, the default is `1.0`.

To see how this works out in practice, imagine you have an icon which is 128x128 raw pixels. Now you put it on a device with one physical pixel per CSS pixel (1dppx) where a 128x128 pixel icon is required. Each raw pixel of the original image occupies one pixel in the icon on the device's screen.

Now imagine a different device with density of 2dppx, which also requires a 128x128 icon. If the same 128x128 image file is used to fill that space on the screen, each raw pixel of the image occupies 4 pixels (2x2) on the device screen. The result is a blurred image, as the same single pixel is used to fill 4 pixels on the screen.

To resolve this, the [W3C specification](http://www.whatwg.org/specs/web-apps/current-work/#attr-link-sizes) recommends using images with different raw sizes, depending on the desired icon size and screen density. It provides this example:

> "An icon that is 50 CSS pixels wide intended for displays with a device pixel density of two device pixels per CSS pixel (2x, 192dpi) would have a width of 100 raw pixels."

From this, we can derive a formula for working out how big a raw image file should be for a given icon size and density:

> raw image file dimensions = dimensions of required icon in device-independent pixels * device screen pixel density

For our case, we use two image files:

*   `foo.png` with raw size of 128x128 pixels, intended for use as a 128x128 dip icon on screens with density of 1dppx.
*   `foo_hd.png` with raw size of 256x256 pixels, intended for use as a 128x128 dip icon on screens with density of 2dppx.

And set the `icons` property in the manifest as:

    "icons": [
      {
        "src": "foo.png",
        "type": "image/png",
        "sizes": "128x128",
        "density": "1.0"
      },

      {
        "src": "foo_hd.png",
        "type": "image/png",
        "sizes": "128x128",
        "density": "2.0"
      }
    ]

Note that we don't mention anywhere that `foo_hd.png` is 256x256 raw pixels in size: we just specify that it is suitable for a 128x128 pixel icon on a screen with density of 2dppx.

<h2 id="Effect-on-Android-packaging">Effect on Android packaging</h2>

Rather than affecting the Crosswalk runtime on Android directly, the `icons` field affects how an application is packaged by [`make_apk.py`](/documentation/getting_started/run_on_android.html). The `make_apk.py` script discards some of the information included in the `icon` field, for example the `density`.

If the <code>icons</code> field contains multiple icon sizes, the `make_apk.py` script will map the corresponding icon files to [Android drawable resources](http://developer.android.com/guide/topics/resources/providing-resources.html) depending on their size.

When the application is installed, Android will use the file appropriate for the target's screen resolution as the application icon in the home screen, application list and other relevant locations.

## Acknowledgements

The *townxelliot media player* icon was modified from an original icon created by [wpzoom.com](http://www.wpzoom.com/) under a [CC BY-SA licence](https://creativecommons.org/licenses/by-sa/3.0/). It was sourced from [Find Icons](http://findicons.com/icon/457729/radio?id=457887).

The modified icon is made available here to satisfy the terms of the licence, and is released under the same CC BY-SA licence:

<img alt="Modified radio icon" title="Modified radio icon" src="/assets/radio.png">
