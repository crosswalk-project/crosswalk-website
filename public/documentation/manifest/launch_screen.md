# Launch screen

The launch screen is a static user interface shown immediately after the application is launched. The launch screen will then be hidden at the point when the application's "real" user interface can be constructed: i.e. when the application and any required resources have loaded.

Because the launch screen allows a user to view application content almost immediately, it can greatly improve their perception of the application's performance and generally improve the user experience.

## The full specification

[Interstitial launch screens](https://docs.google.com/a/intel.com/document/d/17PuNuHRTQuREUpaCvj-eEx7uYi2avd-VW-oaMXMpvwo/edit).

## Definition of the manifest property

The launch screen property in the manifest is called `xwalk_launch_screen` and is defined as follows:

    "xwalk_launch_screen": {
      "ready_when": "first-paint | complete | custom",
      "default | portrait | landscape": {
         "background_color": "#ff0000",
         "background_image": "bgfoo.png [1x, bgfoo-2x.png 2x]",
         "image": "foo.png [1x, foo-2x.png 2x]",
         "image_border": "30px [30px 30px 30px] [repeat | stretch | round] [repeat | stretch | round]"
       }
    }

Example:

    "xwalk_launch_screen": {
      "ready_when": "custom",
      "portrait": {
         "background_color": "#ff0000",
         "background_image": "bgfoo.png 1x, bgfoo-2x.png 2x",
         "image": "foo.png 1x, foo-2x.png 2x",
         "image_border": "30px 40px stretch",
       }
    }

## xwalk_launch_screen` members

|Member | Description|
|---|---|
|`"ready_when"` |  The application readiness state at which to hide the launch screen. If undefined, defaults to `"first-paint"`. See the next section for more details.|
|`"default"` | The launch screen to use for both landscape and portrait mode.|
|`"landscape"` | The launch screen to use for landscape mode.|
|`"portrait"` | The launch screen to use for portrait mode.|

### `ready_when`

Application readiness state | Preconditions
--- | ---
`"first-paint"` | * The first visually non-empty paint has occurred.
`"user-interactive"` | * The first visually non-empty paint has occurred.<br>* The DOM and CSSOM have been constructed.
`"complete"` | * The first visually non-empty paint has occurred. <br>* All the resources have been loaded.<br> **WARNING: This may take a long time, as it only triggers after all sub-resources have been downloaded.**
`"custom"` | * The first visually non-empty paint has occurred. <br>* The ```window.screen.show()``` method was called.

### `default` / `portrait` / `landscape`

These properties specify the images and backgrounds to use for the launch screen in different screen orientations. If only `portrait` or `landscape` are set, the launch screen will only be shown in the corresponding orientation (portrait or landscape respectively).

Each of these orientation properties can be defined by an object with the following properties:

* `background_color`: The background color for the launch screen, in [hexadecimal notation](http://www.w3.org/TR/css3-color/#rgb-color).
* `background_image`: The repeating background image. The upper left corner of the image is aligned with the upper left corner of the viewing area on the screen. See the [background_image spec](https://docs.google.com/a/intel.com/document/d/17PuNuHRTQuREUpaCvj-eEx7uYi2avd-VW-oaMXMpvwo/edit?pli=1#heading=h.p51ynj4nuqv7) for full details.
* `image`: The image to be centered horizontally and vertically within the background.
* `image_border`: A border image, split into a 9-piece image with the intermediate border pieces scaled and/or stretched to fill the whole viewport. See the [Image Border spec](https://docs.google.com/a/intel.com/document/d/17PuNuHRTQuREUpaCvj-eEx7uYi2avd-VW-oaMXMpvwo/edit?pli=1#heading=h.rq1ayw778vp6) for full details.

Note: The `background_color` and `background_image` will be the first thing displayed when user starts the application. The image specified by the `image` property will then be displayed over the background.

### Image and color definitions

The supported image formats are **png**, **bmp**, **jpg**, and **gif**.

The `background_image` and `image` properties have the following format:

    "<filename1> <density1>, <filename2> <density2>, ..."

where `<filename*>` is a path to an image file, relative to the manifest; and `<density*>` is the screen density where this image should be applied (`density` is optional if only one file is specified). For example, for the following field value:

    "background_image": "file1.png 1x, file2.png 2x, file3.png"

and a screen density of 2.5 dppx (dots per pixel), `file2.png` will be selected (as it is the closest value which is lower than the dppx).

For this field value:

    "background_image": "file1.png"

the file `file1.png` will be used as the background image at all screen densities.
