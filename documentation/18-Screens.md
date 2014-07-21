# Using Crosswalk screen APIs

There are many challenges when developing HTML5 applications for mobile devices. One of the biggest is making the application work well on a small screen, with the same look and feel as a native application. Some of the most common screen-related issues on mobile devices are:

* Fitting the application to the "real" screen width and height.
* Locking the screen orientation to the one preferred for the application.
* Showing an application in fullscreen mode with no system toolbars.
* Using different layouts or assets depending on screen size.
* Showing a launch screen while an application is loading.

A number of solutions for such issues have surfaced over the last few years, either as de facto "standards" (e.g. the [`<meta name="viewport">` element](https://developer.apple.com/library/safari/documentation/AppleApplications/Reference/SafariHTMLRef/Articles/MetaTags.html#//apple_ref/doc/uid/TP40008193-SW6)), vendor-specific browser extensions (e.g. [Mozilla's implementation of screen.lockOrientation](https://developer.mozilla.org/en-US/docs/Web/API/Screen.lockOrientation)), or application-specific work-arounds in JavaScript and CSS (e.g. [this rotation hack](https://github.com/01org/webapps-slider-puzzle/blob/master/app/js/main.js)). Recently, work has started on creating formal standards based on these interim solutions. However, many browsers and web views lag behind these standards.

By contrast, one major benefit of Crosswalk is that many of these evolving standards are implemented and available for use *right now*. In this article, I'll describe how to resolve some of the issues listed above using Crosswalk, demonstrating how to optimise a simple side-scrolling HTML5 game for mobile screens.

## The game

The game used in this article is a simple side-scrolling dodge game set in space. Throughout the article, I'll demonstrate how to incrementally improve its layout and add features like orientation locking and dynamic canvas sizing.

The layout of the game looks like this (shown in a Chrome browser on a Linux desktop machine):

![space dodge game in Chrome on Linux desktop](assets/space_dodge_game-chrome_linux_desktop.png)

The container element for the whole game is sized using CSS, and is 750 pixels across by 450 pixels down. It is positioned relative to the top-left of the browser window.

Inside the container are a `<div>` for the game screen, consisting of a controls `<div>` on the left and a `<canvas>` for the play area on the right; and another `<div>` for the "game over" screen, which is initially hidden.

The controls `<div>` is sized relative to the container, and take up 20% of its width (150px) and 100% of its height (450px). The elements inside the controls area are positioned using the [CSS flexbox](http://www.w3.org/TR/css-flexbox-1/). The CSS flexbox is the first example of a useful feature which is available in Crosswalk, but not in older browsers. It's very handy for laying out game controls, as you can vertically and horizontally align elements inside a flex element.

`<canvas>` elements have to have a pixel width and height specified for them (you can't size them in CSS). For this first version of the game, the `<canvas>` is sized to 600 pixels across and 450 pixels down. This is then put in the right-hand side of the game screen.

The sprites for the ship and the asteroids are loaded from PNG graphic files and drawn to the canvas. If you are unfamiliar with how to do this, see [this `<canvas>` tutorial](https://developer.mozilla.org/en-US/docs/Web/Guide/HTML/Canvas_tutorial).

The gameplay is nothing special, but is just there to demonstrate the effect of applying various screen-related techniques to the game. The game is touch-enabled, though, so it will work on a touchscreen devices like phones.

### Notes on the game layout

The layout elements in the HTML file `index.html`:

    <!DOCTYPE html>
    <html>
      <head>
        <meta charset="utf-8">
        <title>space dodge game</title>
        <link rel="stylesheet" href="base.css">
      </head>

      <body>

        <div id="container">

          <div id="game-screen">
            <div id="controls" class="vbox">
              <p id="score">Score<br><span id="score-display"></span></p>
              <img id="control-up" src="control-up.png">
              <img id="control-down" src="control-down.png">
            </div>
            <canvas id="play-area" width="600" height="450"></canvas>
          </div>

          <div id="finish-screen" class="vbox" data-visible="false">
            <p id="final-score"></p>
            <button id="restart">Restart</button>
          </div>

        </div>

        <script src="main.js"></script>

      </body>
    </html>

The CSS stylesheet:

    * {
      user-select: none;
      -webkit-user-select: none;
      user-drag: none;
      -webkit-user-drag: none;
    }

    body {
      margin: 0;
    }

    #container {
      position: relative;
      top: 0;
      left: 0;
      width: 750px;
      height: 450px;
    }

    #game-screen, #finish-screen {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: #11F;
    }

    #finish-screen {
      z-index: 100;
    }

    #play-area {
      float: right;
      width: 80%;
    }

    #controls {
      height: 100%;
      width: 20%;
      padding: 5%;
      text-align: center;
      box-sizing: border-box;
      float: left;
    }

    .vbox {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    p, button {
      font-family: sans-serif;
      font-size: 1.5em;
    }

    #control-up, #control-down {
      margin: 1em 0;
    }

    #finish-screen > * {
      text-align: center;
    }

    #score, #final-score {
      color: white;
    }

    [data-visible="false"] {
      display: none !important;
    }

Some remarks about the code:

*   The margins are removed from the `body` element with:

    ```
    body {
      margin: 0;
    }
    ```

    This makes it easier to do accurate positioning and sizing of elements inside the body.

*   The outer element with ID `container` contains all of the other game elements. Its size is sets in CSS, which constrains the size of the whole game.

*   There are two separate "screens" inside the container (elements with the IDs `game-screen` and `finish-screen`). `finish-screen` is hidden initially, but is placed on top of `game-screen` by setting a `z-index` of 100. When the game ends, `finish-screen` is shown, which obscures the `game-screen` element underneath it.

*   `<canvas>` elements must have their height and width attributes set to define the size of the canvas in pixels. This can't be done in CSS. In the code above, the height and width are fixed; but they can be set dynamically based on the size of the screen (see later).

*   The container has `position: relative` set in the CSS, and the child screens have `position: absolute`. This enables the child screens to be aligned with the top-left corner of the container.

*   `float` properties are used to position the controls to the left of the canvas `play-area`.

The HTML and CSS for the game so far are naive, representing a first pass at a working layout. There is plenty of room for optimisation and improvement.

### Running it yourself

If you are interested in following along with the article, rather than just looking at screenshots, you can checkout the project and build an Android package for it by following the steps below.

1.  Download the latest stable Crosswalk Android bundle:

    ```
    $ wget https://download.01.org/crosswalk/releases/crosswalk/android/stable/${XWALK-STABLE-ANDROID-X86}/crosswalk-${XWALK-STABLE-ANDROID-X86}.zip
    ```

    If you are interested in running the version of the game designed to work with Crosswalk 8, fetch one of the Crosswalk Android bundles from [the download page](#documentation/downloads).

2.  Unzip the bundle, e.g. on Linux:

    ```
    $ unzip crosswalk-${XWALK-STABLE-ANDROID-X86}.zip
    ```

3.  Check out the code for the Crosswalk samples ([hosted on github](https://github.com/crosswalk-project/crosswalk-samples)):

    ```
    git checkout https://github.com/crosswalk-project/crosswalk-samples.git
    ```

    The game used in this tutorial is in the `space-dodge-game` directory.

4.  Use `make_apk.py` to build a Crosswalk package from the checked-out crosswalk-samples project:

    ```
    $ cd crosswalk-${XWALK-STABLE-ANDROID-X86}
    $ python make_apk.py --manifest=/path/to/crosswalk-samples/space-dodge-game/<version>
    ```

    Replace `<version>` with whichever version of the game you want to run; `master` is the version of the game before optimisation, and there are four other versions for different Crosswalk versions and techniques. See the `README.md` file in the `space-dodge-game` directory for details.

5.  Once you have built a package for the game, you can deploy it to Android using `adb`:

    ```
    adb install space_dodge_game_0.0.0.1_x86.apk
    ```

    or

    ```
    adb install space_dodge_game_0.0.0.1_arm.apk
    ```

    depending on your target hardware.

    For more details about building and running Crosswalk applications on Android, see [the Getting started pages](#documentation/getting_started/run_on_android).

## Problem 1: The game sometimes displays in portrait orientation

If the game is packaged and deployed to a small screen device in its initial state, this is what it looks like:

![space dodge game in portrait on ZTE Geek](assets/space_dodge_game-zte_geek_portrait.png)

It obvious that it doesn't take up enough of the screen. The first reason for this is that the game is in portrait orientation, when it should be in landscape. By rotating the device (so the game rotates), you can see an immediate improvement:

![space dodge game in landscape on ZTE Geek](assets/space_dodge_game-zte_geek_landscape.png)

However, the game shouldn't accidentally rotate if the screen orientation changes: it should always display in landscape mode.

Crosswalk provides an easy fix for this, as it implements the [Screen Orientation API](http://www.w3.org/TR/screen-orientation/). Among other things, this enables an application to lock itself to a particular orientation at run time.

To use this, add the following code to the main JavaScript entry point for your application. This depends on the application, but for the space dodge game, it's at the top of the `main.js` file, triggered once the DOM is ready:

    document.addEventListener('DOMContentLoaded', function () {
      // check whether the runtime supports screen.lockOrientation
      if (screen.lockOrientation) {
        // lock the orientation
        screen.lockOrientation('landscape');
      }

      // ...rest of the application code...
    };

If you run the application now, you'll notice that the game rotates to landscape when it starts. Its appearance is the same as if the physical device is rotated to landscape. An application can also be locked to portrait orientation using this approach (`screen.lockOrientation('portrait')`).

If you prefer, there are a couple of other ways to force an application's orientation:

*   Use the `orientation` field in the manifest (this only works for Crosswalk 8 or later).

    For example, the following manifest would force the application to landscape orientation:

    ```
    {
      "name": "space_dodge_game",
      "version": "0.0.0.1",
      "start_url": "index.html",
      "orientation": "landscape"
    }
    ```

    You could then [build an application package for Android](#documentation/getting_started/run_on_android) from this manifest with:

    ```
    $ python make_apk.py --manifest=/projects/space_dodge_game/manifest.json
    ```

    Then install it on an Android target as described in [the Getting started pages](#documentation/getting_started/run_on_android).

    Using the `orientation` field in the manifest has exactly the same effect as using `screen.lockOrientation` in your application code: the application rotates to the requested orientation after the application starts. But `screen.lockOrientation` has the advantage of being supported by other runtimes (e.g. Firefox OS), so may be a better choice if you need your application to work cross-platform.

    The `orientation` manifest field is defined in the [W3C Manifest for web application specification](http://w3c.github.io/manifest/).

*   Use the `--orientation` option when running `make_apk.py`. Note that this won't work unless you are using `make_apk.py` to package your application. If you're using the embedding API, use `screen.lockOrientation` or the manifest approach.

    For example:

    ```
    $ python make_apk.py --manifest=/projects/space_dodge_game/manifest.json \
        --orientation=landscape
    ```

    This is really a hack, as it actually modifies the `AndroidManifest.xml` to rotate Crosswalk itself (rather than the application being rotated by Crosswalk). But it's a viable alternative for older versions of Crosswalk which don't support the `orientation` field in the manifest and where you prefer not to use `screen.lockOrientation`.

    This also has the same effect as using the `orientation` field, rotating the application after it has started.

## Problem 2: The status bar is distracting

The next issue is that the toolbar is still visible, which is a distraction while playing a game. There are two ways to make the application occupy the whole screen, hiding the status bar (on Android):

*   Use the `display` field in `manifest.json` (this only works for Crosswalk 8 or later).

    For example, here's a manifest which sets fullscreen, and also retains the landscape orientation applied in the previous step:

    ```
    {
      "name": "space_dodge_game",
      "version": "0.0.0.1",
      "start_url": "index.html",
      "display": "fullscreen",
      "orientation": "landscape"
    }
    ```

    [Build and deploy to the device](#documentation/getting_started/run_on_android) as usual.

    The `display` manifest field is defined in the [W3C Manifest for web application specification](http://w3c.github.io/manifest/).

*   Use the `--fullscreen` option with the `make_apk.py` script.

    For example:

    ```
    $ python make_apk.py --manifest=/projects/space_dodge_game/manifest.json \
        --orientation=landscape --fullscreen
    ```

    This is a useful option if you are using an older version of Crosswalk which doesn't support the `display` field in the manifest. See [the Getting started pages](#documentation/getting_started/run_on_android) for more information about using `make_apk.py`.

Using either the `display` field or the `--fullscreen` option has the same effect: the application displays in fullscreen, hiding the system status bar on Android:

![space dodge game in fullscreen, landscape orientation](assets/space_dodge_game-zte_geek_fullscreen.png)

You may have heard of the [fullscreen API](https://dvcs.w3.org/hg/fullscreen/raw-file/tip/Overview.html), which enables an application to request that all or part of its user interface occupy the whole device screen. However, the fullscreen API has a different purpose from the approaches covered above: it requires some user activity to trigger the fullscreen request *after* an application is running.

In the case of a Crosswalk application, you can use the fullscreen API, providing the user interacts with the application (e.g. push a button or make a gesture) to trigger the fullscreen request. But it is not possible to *automatically* make the application go fullscreen without user interaction.

## Problem 3: The game doesn't fit the screen

The game is now consistently bigger because it's always displayed fullscreen and in landscape orientation. But there's a lot of whitespace around it, and it's not visually appealing. It would be nicer if the game fitted the whole screen.

The area occupied by the application is called its *viewport*. On a mobile device, this is the area under or between any toolbars on the screen (e.g. the status bar on Android) if the application is running in "windowed" mode; or the whole device screen if it is running in "fullscreen" mode. What we're aiming for here is to get the game to fill the whole of the device screen.

Approaches:

1.  Scale the application to fit the smallest dimension (width or height), keeping aspect ratio and centering it in the viewport. The game has the same number of pixels, but they are scaled to fit into the viewport.

2.  Do the same as above, but instead of scaling, physically change the size of all the game elements. This would make the canvas larger (in pixels) on a large screen and smaller on a small screen. The disadvantage of this approach is that you have to scale the image assets used in the game to ensure that they keep the same proportions: for example, if you have an image which is 40px square on a 400px square game area, then change the game canvas to 600px square, you will have to scale the image to 60px square. The advantage of this approach is that you're not scaling everything, just sprites; but the disadvantage is that it's more complicated to implement, as you have to track the size of the sprites (on screen) in isolation from their actual size (in the graphic file).

3.  Change the dimensions of the game, while keeping the size of the `<canvas>` the same. The area occupied by the game could be made physically larger, and perhaps make the controls larger too, while leaving the `<canvas>` element the same size. The reason for doing this is that the `<canvas>` is sensitive to size changes, as described in the previous bullet point.

For this game, the canvas is already too big for small screens, so the third approach is not really practical. (That approach only works if you know your game is only going to be played on a small range of screens, which can all fit the canvas at the size you specify. Similar to how old PC games knew they would be played on a device with at least a 640x400 pixel screen.)

This leaves approaches 1 and 2, which are covered shortly. However, before getting onto those, the screen size can be standardised by making the application viewport a consistent size.

### Pre-amble: Make the viewport fit the screen

When building mobile phones, manufacturers realised that if 90% of websites were shown in a small screen, they would not fit. This is because websites used to be designed primarily for desktop screens; mobile sites were often separate from the main site, with reduced functionality or even written in a [different markup language](http://en.wikipedia.org/wiki/Wireless_Markup_Language). But users wanted to be able to access the "real" website from a phone, rather than a mobile-specific site; and do this without compromising its appearance. Manufacturers resolved this issue by equipping their mobile browsers with a default "zoom out", so that websites intended for desktops would display reasonably well in mobile browsers. Most phone browsers still work this way: for example, the HTC One X I have for testing reports its width as 980px, while its actual physical width is 360px.

Around the same time, web developers and designers changed their approach, designing websites which would display differently depending on device capabilities. These techniques are now known as [responsive web design](http://alistapart.com/article/responsive-web-design), and encompass a range of approaches including use of [media queries](http://www.w3.org/TR/css3-mediaqueries/) and [delivering different images to different screens](http://www.w3.org/community/respimg/). Contemporary developers also often follow a [mobile first](http://www.abookapart.com/products/mobile-first) philosophy, ensuring a website is highlighy functional first and foremost on small form factors, with added bells and whistles (typically, more and larger graphics) on bigger form factors.

Where these two roads meet is at an awkward crossroads: developers are trying hard to provide sites tailored specifically for small screen devices; but small screen devices apply an artificial zoom which make them appear larger than they are. As a result, the mobile version of a site could be bypassed (the site calculates it is being viewed on a desktop browser) and the desktop site end up being delivered to a screen that is too small to display it optimally.

A solution was initially developed by [Apple](https://developer.apple.com/library/safari/documentation/AppleApplications/Reference/SafariHTMLRef/Articles/MetaTags.html), taking the form of an ad hoc HTML `<meta>` element named `viewport`. This could be used to ask a mobile browser to change various aspects of its viewport. For example, the page could ask the browser to set the viewport width to the device's real physical width:

    <meta name="viewport" content="width=device-width">

Or prevent the user zooming the page in and out (e.g. with pinch gestures):

    <meta name="viewport" content="width=device-width, user-scalable=no">

Other vendors followed suit and added support for this `<meta>` viewport element to their browsers.

By using the `<meta>` viewport element, a developer could now prevent the browser from applying its default zoom to an HTML page. This would in turn mean that a web site or app could get a correct reading for the device's physical screen dimensions, enabling media queries to be applied accurately to select the best CSS for the screen.

The `<meta>` viewport element is *not* a standard: it is not implemented consistently across browsers, and the syntax for declaring its content also varies between browsers. However, there are currently attempts to formalise [viewport rules in a CSS specification](http://dev.w3.org/csswg/css-device-adapt/). For now, the existing syntax works well for Crosswalk, and can be used as a stop-gap.

To apply a viewport meta element to the space dodge game in this article, add it to the `<head>` of the `index.html` file. This instructs the browser to use its physical width as the viewport width, without zooming:

    <!DOCTYPE html>
    <html>
      <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width">
        <title>space dodge game</title>
        <link rel="stylesheet" href="base.css">
      </head>

      <body>

      ... rest of HTML file ...

The result is this:

![space dodge game with landscape orientation, fullscreen and viewport meta](assets/space_dodge_game-viewport_meta.png)

Note how the game is now filling the whole screen, and looks roughly the right size: the buttons are nice and big, and the graphics clear. However, the screen size on this device is 640px wide by 360px down, so the game canvas is spilling over the edges of the screen.

By contrast, it doesn't take up enough space on a larger screen. Here it is on a Nexus 7:

![space dodge game on Nexus 7 with landscape orientation, fullscreen, and viewport meta](assets/space_dodge_game-nexus7.png)

In the next sections, I describe two ways to alter the size of the game to fit better into the screen.

### Approach 1: Scale the game

The aim here is to scale the whole game (in CSS) so that it retains its aspect ratio but fits in the screen. So the first step is to figure out the optimum position and size for the element which contains the whole game.

Here is the algorithm in pseudo-code:

*   Find the width and height of the viewport (which is the same as the screen dimensions, thanks to the viewport meta applied previously).

*   Find the width and height of the application container.

*   Find the ratio of viewport height to container height (scaleHeight).

*   Find the ratio of screen width to container width (scaleWidth).

*   Choose the smallest of scaleHeight and scaleWidth, and use this as the scale factor for both width and height (scaleBoth).

*   Work out the newHeight and newWidth of the container by multiplying its width and height by scaleBoth.

*   Work out a top and left offset for the container, as follows:

    *   left = (1 / scaleBoth) * ((viewport width - container newWidth) / 2)
    *   top = (1 / scaleBoth) * ((viewport height - container newHeight) / 2)

    Note that the offsets are being scaled, so that they are in proportion to the scaled game.

*   Apply the scaling and offsets to the whole game container using [CSS transforms](http://www.w3.org/TR/css-transforms-1/).

With this information, a scale can be applied to the whole container using JavaScript:

    var scale = function () {
      var container = document.querySelector('#container');
      var containerWidth = container.offsetWidth;
      var containerHeight = container.offsetHeight;

      var viewportWidth = document.documentElement.clientWidth;
      var viewportHeight = document.documentElement.clientHeight;

      var scaleWidth = viewportWidth / containerWidth;
      var scaleHeight = viewportHeight / containerHeight;
      var scaleBoth = (scaleHeight < scaleWidth) ? scaleHeight : scaleWidth;

      var newContainerWidth = containerWidth * scaleBoth;
      var newContainerHeight = containerHeight * scaleBoth;

      var left = (viewportWidth - newContainerWidth) / 2;
      left = parseInt(left * (1 / scaleBoth), 10);

      var top = (viewportHeight - newContainerHeight) / 2;
      top = parseInt(top * (1 / scaleBoth), 10);

      // scale the whole container
      var transform = 'scale(' + scaleBoth + ',' + scaleBoth + ') ' +
                      'translate(' + left + 'px, ' + top + 'px)';
      container.style['-webkit-transform-origin'] = 'top left 0';
      container.style['-webkit-transform'] = transform;
      container.style['transform-origin'] = 'top left 0';
      container.style['transform'] = transform;
    };

    window.onresize = scale;
    scale();

The `scale()` function implements the pseudo-code at the start of this section. To give an example of the CSS transforms which will be applied, consider the case where the scale is 2.05 and the top and left offsets are 0px and 40px respectively. The resulting CSS transforms would be:

    -webkit-transform-origin: 0 0 0;
    transform-origin: 0 0 0;
    -webkit-transform: scale(2.05, 2.05) translate(40px, 0px);
    transform: scale(2.05, 2.05) translate(40px, 0px);

Setting the transform origin to `top left 0` ensures that the transforms are applied from the top-left corner of the container. The `scale(2.05, 2.05)` function changes the scaling of the container; and the `translate(40px, 0px)` function moves the container to the correct position on screen. Here's the result on a ZTE Geek:

![space dodge game on ZTE Geek: landscape, fullscreen, viewport meta, CSS transform](assets/space_dodge_game-zte_geek_scale.png)

Notice how the application is fitted vertically, then centered horizontally.

This approach is good because it is simple. However, because the scaling is applied to the whole application, it can cause some blurring, especially when scaling up. The next section describes a more complex alternative which uses resizing plus selective scaling of game assets.

### Approach 2: Resize the game

This approach requires a more extensive reworking of the CSS for the game, as it affects the positioning, size and appearance of every element. It's tempting to ignore this approach, as the scaling approach of the previous section works pretty well. But there's actually good discipline involved in making this alternative approach work.

The first step is to make the container occupy the whole screen. In the olden days, this would have meant measuring the screen, then manually setting the width and height CSS properties of the container (in JavaScript). However, Crosswalk supports a handy CSS feature which means one doesn't have to do this any more: [viewport-percentage lengths](http://dev.w3.org/csswg/css-values/#viewport-relative-lengths). This allows you to specify the dimensions of elements in terms of a percentage of the viewport dimensions. As the application is now fullscreen, the viewport fills the whole screen; so the container can fill 100% of the device's height and width using this CSS rule:

    #container {
      position: relative;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
    }

Note the `vw` (viewport width) and `vh` (viewport height) suffixes to the container's width and height settings. Here's what the game looks like on a ZTE Geek with this change to the container sizing:

![space dodge game on ZTE Geek: landscape, fullscreen, viewport meta, resize](assets/space_dodge_game_zte_geek_resize.png)

Not bad for a first pass. But the bottom of the canvas has disappeared off-screen; and the buttons look squashed. We'll fix this in a minute.

Note that viewport-percentage lengths don't work well for testing in a desktop browser, as desktop environments often have toolbars on the browser and on the desktop itself. This means that the viewport height can be considerably larger than the amount of space inside the browser window. However, you could try experimenting with [Chrome's device emulation support](https://developer.chrome.com/devtools/docs/mobile-emulation) if you want a quick way to test your app without a mobile or tablet.

At this point in testing, I realised something. When I first wrote the game, I concentrated on getting it to look right in a desktop browser. This meant that I didn't necessarily do everything "properly". For example, with a fixed width container, I could arrange the control buttons easily: setting a few margins and a bit of padding seemed to work.

However, once I started thinking about resizing *everything*, it became clear that I hadn't done things the "right way". I needed to make some fixes so that the buttons and score would be positioned and sized correctly. This could be done easily in CSS, without having to resort to JavaScript.

I changed this rule:

    #control-up, #control-down {
      margin: 20% 0;
    }

to:

    #control-down {
      margin: 0.5em 0 0 0;
    }

(set the top margin of the "down" button to a fixed amount so it stays close to the "up" button")

I also changed this rule:

    #controls {
      height: 100%;
      width: 20%;
      padding: 5%;
      text-align: center;
      box-sizing: border-box;
      float: left;
    }

to:

    #controls {
      height: 100%;
      width: 20%;
      padding: 0.5em;
      text-align: center;
      box-sizing: border-box;
      float: left;
    }

(padding changed from `5%` to `0.5em`)

This fixes the spacing of the controls so that they stay a reasonable distance apart as the screen size changes. Here's the result:

![space dodge game on ZTE Geek: landscape, fullscreen, viewport meta, resize, fixed margin](assets/space_dodge_game-zte_geek_resize_2.png)

The buttons now look right, but the canvas is too tall for the screen (remember, its pixel height is set as an attribute on the canvas). This currently means you can move the spaceship off the bottom of the screen. The canvas needs to be resized to fit in the space to the right of the controls, while maintaining its aspect ratio.

To do this, I applied the technique from the previous section, where I scaled the whole game to fit the screen. But in this case, I can scale just the canvas to fit into the area to the right of the controls. In addition, the canvas scaling should be done using its width and height attributes, rather than scaling it in CSS. (This is a bit confusing, as the height and width attributes of a canvas are different from the CSS height and width of its `<canvas>` element.)

First, I added a new parent element for the canvas (the `<div>` with ID `play-area-container` below):

    <div id="container">

      <div id="game-screen">
        <div id="controls" class="vbox">
          <p id="score">Score<br><span id="score-display"></span></p>
          <img id="control-up" src="control-up.png">
          <img id="control-down" src="control-down.png">
        </div>

        <div id="play-area-container">
          <canvas id="play-area" width="600" height="450"></canvas>
        </div>
      </div>

      <div id="finish-screen" class="vbox" data-visible="false">
        <p id="final-score"></p>
        <button id="restart">Restart</button>
      </div>

    </div>

I modified the CSS so it's `play-area-container` and not `play-area` which is sized to fit the right-hand section of the game by changing:

    #play-area {
      float: right;
      width: 80%;
    }

to:

    #play-area-container {
      float: right;
      width: 80%;
      height: 100%;
    }

    #play-area {
      position: relative;
      box-sizing: border-box;
      border: 2px solid darkblue;
    }

I added a border to `play-area` so you can see where the play area really is, as it now won't necessarily fill its parent. This allows the player to see where the asteroids are going to come from (it might not be the right-hand edge of the screen any more). I also changed `play-area` to use `position: relative`, so it can be positioned relative to its parent (i.e. `play-area-container`).

Then I used a variant of the scale algorithm from the previous section to fit the canvas into the `play-area-container`:

    // the canvas element
    var playArea = document.querySelector('#play-area');

    // factor by which to modify canvas width and height
    var scaleCanvas = 1;

    var fitCanvas = function () {
      var container = document.querySelector('#play-area-container');
      var containerWidth = container.offsetWidth;
      var containerHeight = container.offsetHeight;

      var playAreaWidth = playArea.width;
      var playAreaHeight = playArea.height;

      var scaleWidth = containerWidth / playAreaWidth;
      var scaleHeight = containerHeight / playAreaHeight;
      scaleCanvas = (scaleHeight < scaleWidth) ? scaleHeight : scaleWidth;

      var newPlayAreaWidth = playAreaWidth * scaleCanvas;
      var newPlayAreaHeight = playAreaHeight * scaleCanvas;

      var left = (containerWidth - newPlayAreaWidth) / 2;
      var top = (containerHeight - newPlayAreaHeight) / 2;

      // resize and position the canvas
      playArea.width = parseInt(newPlayAreaWidth, 10);
      playArea.height = parseInt(newPlayAreaHeight, 10);
      playArea.style.top = top + 'px';
      playArea.style.left = left + 'px';
    };

    window.onresize = fitCanvas;
    fitCanvas();

NB this changes the canvas width and height attributes then positions it, rather than scaling the canvas in CSS.

This is the result on a ZTE Geek (canvas size is "shrunk" to 480px by 360px):

![space dodge game on ZTE Geek: landscape, fullscreen, viewport meta, resize, fixed margin, canvas resized](assets/space_dodge_game_zte_geek_resize_canvas.png)

#### Resize the sprites

One side-effect of changing the canvas size is that the sprites are no longer in the same proportions as they were. Previously, the canvas was 600px wide and 450px high; the sprite for the spaceship is 75px wide by 44px tall, which is 12.5% of the canvas width and 10% of its height. But now the canvas size has changed, with the result that the spaceship is 16% of the width of the canvas and 12% of its height (i.e. it is relatively larger). This makes the game harder, as the asteroids will reach the ship sooner (they haven't got so far to travel). Conversely, on a large screen, the game is too easy: the spaceship takes up less of the canvas and the asteroids are further away.

The solution is to draw the images onto a larger area of the canvas, so that their dimensions are always in the same ratio to the canvas dimensions.

In the previous code fragment, I laid the foundation for this by recording the canvas scale factor in a variable outside the `fitCanvas()` function:

    // factor by which to modify canvas width and height
    var scaleCanvas = 1;

After the first call to `fitCanvas()`, this variable is set to the canvas scale factor. To scale the sprites up, I can apply the same factor to the size of the sprites when they are drawn. In the code for the game, the images were previously written to the canvas like this:

    ctx.drawImage(image, x, y, image.width, image.height);

(`ctx` is the canvas' 2D context)

This code can be modified as follows to draw the image at the correct scale:

    ctx.drawImage(
      image,
      x,
      y,
      image.width * scaleCanvas,
      image.height * scaleCanvas
    );

The result is as follows (on the ZTE Geek again):

![space dodge game on ZTE Geek: landscape, fullscreen, viewport meta, resize, fixed margin, canvas and sprites resized](assets/space_dodge_game-zte_geek_resize_canvas_2.png)

Compare with the previous screenshot and you should be able to see that the ship and asteroids are slightly smaller in this screenshot (80% of the size of the previous one).

The distances moved by the game objects are described in terms of multiples of their height (each control press moves the ship three times its own height per second, and asteroids move 3-5 times their width per second). This means that there is no need to modify any of the movement code, providing the scaled heights and widths are used in the calculations, for example:

    // the ship height on screen is the Image height of the
    // loaded graphics file * the canvas scale factor
    var shipHeight = shipImg.height * scaleCanvas;

    // get the number of seconds since the last animation frame
    var timeDelta = (currentTime - lastTime) / 1000;

    // calculate movement on the y axis;
    // the player moves three times their own height per second;
    // direction = 1 for down, -1 for up
    var moveY = direction * shipHeight * timeDelta * 3;

Any other code which refers to the height and width of the sprites (such as the code for collision testing) will also have to apply the canvas scale. The simplest approach is to add functions for fetching the scaled height of the ship and the asteroids, then using those whenever the height and width of an `Image` object are required. For example:

    // img is an Image object loaded with a ship or asteroid graphics
    // file
    function getImgWidth(img) {
      return img.width * scaleCanvas;
    }

    function getImgHeight(img) {
      return img.height * scaleCanvas;
    }

The movement code could then be rewritten as:

    // get the number of seconds since the last animation frame
    var timeDelta = (currentTime - lastTime) / 1000;

    // calculate movement on the y axis;
    // the player moves three times their own height per second;
    // direction = 1 for down, -1 for up
    var moveY = direction * getImgHeight(shipImg) * timeDelta * 3;

Depending on the game and the environment, it may be possible to cache the width and height calculations. However, you have to be careful with this, ensuring that you invalidate the cached values if `canvasScale` changes. In my code, `fitCanvas()` is called each time the `onresize` event fires on the screen. This happens at least twice on a mobile device, while Crosswalk locks the screen orientation to landscape. For this reason, and to keep things simple, I dynamically get the height and width of the image each time, as this is relatively inexpensive for the two images I'm using.

### Problem 4: Sprites blur on large screens

Having applied the lessons in the previous section, one other possible issue is the potential for blurring due to scaling. If you take a close look at this image:

![space dodge game: sprites blur on large screens](assets/space_dodge_game-large_screen_blur.png)

you may notice that it appears blurry. This image was copied from a screenshot of a large browser window, where the sprite graphic was being scaled up to twice its original size, making it blur.

One solution is to provide a larger graphic which can scale without blurring, and use this graphic when the canvas is scaled up (`scaleCanvas > 1`). The `src` attribute of the ship and asteroid `Image` objects can then be set inside the `fitCanvas()` function, depending on `scaleCanvas`:

    // player ship image (to draw onto canvas)
    var ship = new Image();

    // asteroid image (to draw onto canvas)
    var asteroid = new Image();

    // the canvas element
    var playArea = document.querySelector('#play-area');

    // factor to modify canvas width and height by
    var scaleCanvas = 1;

    var fitCanvas = function () {
      var container = document.querySelector('#play-area-container');
      var containerWidth = container.offsetWidth;
      var containerHeight = container.offsetHeight;

      var playAreaWidth = playArea.width;
      var playAreaHeight = playArea.height;

      var scaleWidth = containerWidth / playAreaWidth;
      var scaleHeight = containerHeight / playAreaHeight;
      scaleCanvas = (scaleHeight < scaleWidth) ? scaleHeight : scaleWidth;

      var newPlayAreaWidth = playAreaWidth * scaleCanvas;
      var newPlayAreaHeight = playAreaHeight * scaleCanvas;

      var left = (containerWidth - newPlayAreaWidth) / 2;
      var top = (containerHeight - newPlayAreaHeight) / 2;

      // resize and position the canvas
      playArea.width = parseInt(newPlayAreaWidth, 10);
      playArea.height = parseInt(newPlayAreaHeight, 10);
      playArea.style.top = top + 'px';
      playArea.style.left = left + 'px';

      // use a double-sized image if the canvas is being scaled up
      if (scaleCanvas > 1) {
        ship.src = 'rocket2x.png';
        asteroid.src = 'asteroid2x.png';

        // reset the scale so that the double-sized images are scaled
        // down to the same size as the original image
        scaleCanvas /= 2;
      }
      // otherwise use the original-sized image
      else {
        ship.src = 'rocket.png';
        asteroid.src = 'asteroid.png';
      }
    };

    window.onresize = fitCanvas;
    fitCanvas();

Compare the double-sized image when scaled down slightly (on the left) with the smaller image scaled up to twice its original size (on the right):

![space dodge game: large image scaled down vs small image scaled up](assets/space_dodge_game-large_screen_less_blur.png)

As you can see here, the large image scaled down is far less blurry than the small image scaled up.

### Problem 5: Showing a launch screen

???Crosswalk 8 only

1.  First, I added a foreground image `fg.png`. This is a simple graphic composed of the name of the game plus the rocket sprite, with a transparent background (I've used a blue background here so the white letters show up):

<img style="background-color: blue;" alt="space dodge game launch screen foreground" src="assets/space_dodge_game-launch_screen_fg.png">

2.  To activate the launch screen, I added an `xwalk_launch_screen` field to the `manifest.json` file:

    ```
    {
      "name": "space_dodge_game",
      "version": "0.0.0.1",
      "start_url": "index.html",
      "orientation": "landscape",
      "display": "fullscreen",
      "xwalk_launch_screen": {
        "ready_when": "custom",
        "landscape": {
          "background_color": "#11f",
          "image": "fg.png"
       }
      }
    }
    ```

    The `ready_when` property specifies when to stop showing the launch screen. By setting this to `custom`, the launch screen can be closed programmatically once all the assets are loaded. This is the typical mode you would want to use for launch screens in an HTML5 game, as you will often be doing some initialisation work in JavaScript. In the next step, I'll add the code which does this.

    The `landscape` property specifies the background colour and `image` (foreground image) to use for the launch screen when in landscape mode.  Any image paths are relative to `manifest.json`, and the foreground image will be centered on the background.

    As the application will always be in landscape orientation (I set `"orientation": "landscape"` in the manifest), there's no need for a `portrait` property. Note that you can use the `default` key to specify the settings for all orientations; and can specify different backgrounds and images for different orientations and screen densities. See [this explanation](#documentation/manifest/launch_screen) for more details about the available launch screen options.

3.  The final step is to modify the JavaScript to close the launch screen.

    When the `ready_when` property is set to `custom`, you close the launch screen by calling the Crosswalk-specific `window.show()` method. For this game, I added an artificial 5 second timeout before calling the `window.show()` method (otherwise the game loads so quickly that you only see a flash of the launch screen). The code looks like this:

    ```
    setTimeout(function () {
      // check that the screen.show method is available
      if (screen.show) {
        screen.show();
      }

      gameLoop();
    }, 5000);
    ```

    The `gameLoop()` function starts the animation loop for the game, once the launch screen has been closed.

Now the application can be packaged as usual with `make_apk.py` and installed on a target. This is what the launch screen looks like on a ZTE Geek:

![space dodge game launch screen on ZTE Geek](assets/space_dodge_game_launch_screen-zte_geek.png)

## Summary

???TODO
