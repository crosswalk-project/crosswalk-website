# Screen measurements

Understanding screen measurements in web applications is not a simple task. There are many technical terms to get to grips with, and several layers of abstraction between the real, physical screen and how it is presented to web applications.

This page attempts to explain some of the terminology used, and give practical examples of how different types, sizes and resolutions of screens and images affect an application's appearance.

The concepts covered include:

*   *ppi* (pixels per inch)
*   *dpi* (dots per inch)
*   *dips* (device-independent or density-independent pixels)
*   *reference pixel* (CSS)
*   *devicePixelRatio*
*   *screen density*

## Screens and ppi

*Pixels per inch* or *ppi* is a common unit for describing the resolution or pixel density of a screen. The following formula calculates the ppi of a screen on one axis:

`screen ppi = number of physical pixels on the axis / length of screen on the axis`

A variation of this formula is useful for monitors, as they are typically described in terms of their diagonal length (based on Pythagoras' theorem):

`screen ppi = (&sqrt; ((pw * pw) + (ph * ph))) / dl`

where:

*   pw = width of the screen in pixels
*   ph = height of the screen in pixels
*   dl = diagonal length of the screen in inches

To give a concrete example, my Samsung 21" monitor has a maximum resolution of 1680x1050. The formula above gives the following ppi value:

`ppi = (&sqrt; ((1680 * 1680) + (1050 * 1050))) / 21 = 94.34`

Allowing for my inaccurate measurements, these values are in line with the expected ppi of a desktop screen (around 100ppi). Desktop screens are designed to be viewed between 3 and 4 feet away from the user; and, at 100ppi, the human eye can't distinguish individual pixels, making an image drawn in pixels appear continuous. But if you look more closely at a desktop screen (at a distance of less than 3 feet), you start to see individual pixels.

By contrast, mobile screens are designed to be used closer to your face, at a distance of half your arm's length or closer (10-12 inches). At this distance, you can see the individual pixels if the ppi is around 100. Consequently, mobile devices typically have a higher pixel density (250ppi or more), so you can't discern individual pixels from 10-12 inches away.

What has this got to do with density? On a screen with more pixels per inch, the pixels are smaller and closer together (more densely packed). Therefore, a screen with a high number of pixels per inch is often called "high density". Apple's *Retina Display* (trademarked by Apple) was one of the first "high density" screens, and is still prevalent today. It is defined as:

*a screen where the pixel density is typically 300ppi or higher for a device held 10 to 12 inches from the eye.* ([NPR source](http://www.npr.org/blogs/alltechconsidered/2010/06/07/127530049/live-blogging-apple-s-developers-conference))

Keep this definition in mind, and consider devices with a screen pixel density of 250ppi or more as "high density".

## Image resolution and dpi

dpi is a concept related to ppi, in that it describes *dots per inch*. It has its origin in printing, where the number of dots per inch defines the resolution of a printed image (higher dpi == higher resolution).

You may be wondering: why is this relevant to screens?

The first reason is that ppi and dpi are often used interchangeably when discussing screens. This happens in places like the Android documentation, where screens are described in terms of dpi (e.g. [http://developer.android.com/guide/practices/screens_support.html](http://developer.android.com/guide/practices/screens_support.html)); and in W3C specifications which refer to screens (e.g. http://www.w3.org/TR/css3-values/#absolute-lengths). However, for the purposes of this article, I will use "ppi" when referring to screens and reserve "dpi" for referring to print.

The second reason is that various specifications relating to HTML and CSS refer to dpi, particularly with respect to images. This is because CSS is not specific to screens, and can also be applied to print media. Some key concepts in CSS are based around dpi, as they use measurements from printing as part of their definition (even when they relate to screens).

But it's important to note that the resolution of an image (the situation where dpi is used most commonly) has absolutely no bearing on how it displays on a screen: it only affects how it appears in print.

As an illustration, consider two image files, both 128x128 pixels square. (These are "raw" pixels inside the image file, not the pixels occupied by the image on a screen.) Even though these images are both the same raw size, they can have different dpi settings, recorded in the image file itself. Each image's "real" size on printed media is determined by combining the raw pixel size of the image file with its resolution (in dpi).

For example, if the first 128x128 pixel image has a resolution of 96 dpi, its print size will be 1.333x1.333 inches (128 / 96); but if the resolution of the second image is 300 dpi (the typical resolution for printed images), its print size will be 0.427x0.427 inches (128 / 300). This is because more of the pixels of the second image will be printed into each inch of the medium.

But if the images are displayed side by side on a web page, they will be indistinguishable, despite their differing resolutions. The resolution only affects print.

## Device-independent pixels

Having defined ppi and dpi, we are now ready to move on to "device-independent pixels" ("dips" - be careful not to confuse this with dpi). The dip is a convenience for describing "virtual" pixels which don't correspond one to one with real physical pixels on a device's screen. The reason for their existence is to make life easier for developers: an application's layout can be defined in terms of dips and should scale correctly for different physical screen sizes.

For example, imagine two devices: the first is 320 physical pixels wide, and the second 640 physical pixels wide; but both have the same number of dips. The only difference is that the second device displays each device-independent pixel using 4 physical pixels (it has a screen pixel density of 2dppx - see the next section).

Now imagine an application which is 320 dips wide. It will display at the same width on both devices, despite them having different numbers of physical pixels. This is because its width is defined in dips, and both device screens are the same width in dips.

In the context of CSS, a dip corresponds with the "reference pixel". This is equivalent to one physical pixel on a 96dpi screen at arm's length:

*The reference pixel is the visual angle of one pixel on a device with a pixel density of 96dpi and a distance from the reader of an arm's length. For a nominal arm's length of 28 inches, the visual angle is therefore about 0.0213 degrees. For reading at arm's length, 1px thus corresponds to about 0.26 mm (1/96 inch).* ([W3 source](http://www.w3.org/TR/css3-values/#absolute-lengths))

Why is the reference pixel defined this way? It's because the definition originated with the default resolution of images displayed in CSS, as defined by the `image-resolution` CSS property (see https://developer.mozilla.org/en-US/docs/Web/CSS/resolution).

The reference pixel is what you are using when you define a size or length with a `px` value in a CSS stylesheet. When you set sizes in CSS using `px` values, you are referring to the number of reference pixels (i.e. device-independent pixels) the element should span, rather than physical pixels on the screen.

Android uses the term "density-independent pixels" rather than "device-independent pixels", but they are effectively the same thing. Though the definition of a dip in Android is different from the CSS definition:

*The density-independent pixel is equivalent to one physical pixel on a 160 dpi screen, which is the baseline density assumed by the system for a "medium" density screen...The conversion of dp units to screen pixels is simple: px = dp * (dpi / 160). For example, on a 240 dpi screen, 1 dp equals 1.5 physical pixels.* ([Android Developer documentation source](http://developer.android.com/guide/practices/screens_support.html))

(If you're interested in finding out more about density-independent design, [this page](http://developer.android.com/guide/practices/screens_support.html) is generally helpful)

While this calculation holds for applications on Android, the same is not true for browsers: it is not possible to determine the dips a browser will have from a screen's ppi in a similar fashion. Browser vendors actually decide how many dips a browser should have on a particular device or physical screen, regardless of its screen size. [This article](http://www.quirksmode.org/blog/archives/2012/07/more_about_devi.html) explains the details, but the important point is that browsers (and Crosswalk) actually use another calculation to get the dimension of the viewport in dips:

`dips = number of physical pixels / devicePixelRatio`

window.devicePixelRatio is set by the browser, depending on the context where it is running and other quirks. This is explained next.

## devicePixelRatio and screen density

The devicePixelRatio is "the ratio between physical pixels and device-independent pixels (dips) on the device" (http://www.quirksmode.org/blog/archives/2012/06/devicepixelrati.html).

The formula for calculating the devicePixelRatio is:

`devicePixelRatio = physical pixels / dips`

The density of a screen is correlated with devicePixelRatio, and measured in *device pixels per device-independent pixel*, or *dppx*. In fact, the screen density in dppx is the same as the devicePixelRatio, i.e.

`density dppx = physical pixels / dips`

As mentioned previously, the number of dips available depends on a variety of factors, and does not directly correlate with the physical size of the screen nor the number of physical pixels it has. However, the devicePixelRatio can usually be retrieved via the `window.devicePixelRatio` variable in the browser. Combined with the physical size of the device's screen, you can then figure out the number of dips available.

What difference does devicePixelRatio/screen density make? To give a concrete example, imagine two devices which are 2.5" across, displaying a page which is 320 device-independent pixels wide (defined in a CSS stylesheet), at the same zoom level and distance:

*   The first device is 320 physical pixels wide (resolution of 128ppi, so normal density). Each dip of the 320 dip page is represented by one physical pixel.

*   The second device is 640 physical pixels wide (resolution of 256ppi, so high density). Each dip of the 320 dip page is represented by four physical pixels (two across, two down).

This gives the following devicePixelRatio values for the two devices:

`First device devicePixelRatio = 320 / 320 = 1`

`Second device devicePixelRatio = 640 / 320 = 2`

While the page is the same width and scale on both devices, and being viewed on two screens of the same size and at the same distance, the page will appear sharper on the device which is 640 pixels wide. This is because the second, high-density device (2dppx) uses more physical pixels to display each device-independent pixel (4 screen pixels per dip, 2 across and 2 down) than the device with screen density of 1dppx (1 screen pixel per dip).
