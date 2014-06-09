# Icons (Crosswalk custom field)

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
