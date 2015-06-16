# Write a web application

The web application for this project is a simple HTML5/CSS/Javascript application, with some additional files to enable it to be used in a Crosswalk package. Full details about adapting web applications for Crosswalk are given [in the *Getting started* pages](/documentation/android/build_an_application.html).

1.  Create the directories for the web application:

        cd ~/<my projects directory>/xwalk-echo-project

        # top-level directory for web application
        mkdir xwalk-echo-app
        cd xwalk-echo-app

        # directories for application components
        mkdir -p assets js

    The steps below assume you are adding files to the `xwalk-echo-app` directory.

2.  Add a `manifest.json` file:

        {
          "name": "xwalk_echo_app",
          "xwalk_version": "0.0.0.1",
          "start_url": "index.html",
          "icons": [
            {
              "src": "icon.png",
              "sizes": "96x96",
              "type": "image/png",
              "density": "4.0"
            }
          ]
        }

3.  Add an icon file `icon.png`.

    The manifest file (above) specifies a 96x96px icon with filename `icon.png` as the application icon. Crosswalk will use this as the icon for the application in the Android target's application list/home screen etc.

    You can either use your own icon (with the right dimensions), or copy the default Crosswalk one by following the instructions on [this page](/documentation/android/build_an_application.html#A-simple-application).

4.  Add the HTML file `index.html`:

        <!DOCTYPE html>
        <html>
        <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="utf-8">
        <title>xwalk-echo-app</title>
        <link rel="stylesheet" href="assets/base.css">
        </head>
        <body>

        <header>
          <h1>xwalk-echo-app</h1>
        </header>

        <script src="js/main.js"></script>
        </body>
        </html>

5.  Add some simple CSS styling in `assets/base.css`:

        body {
          font-size: 1.5em;
          margin: 0;
          font-family: sans-serif;
        }

        h1 {
          width: 100%;
          text-align: center;
          margin: 0.5em 0;
        }

        header {
          border-bottom: 0.25em solid black;
        }

    This increases the font sizes and adds some lines and spacing to make the layout slightly more appealing.

6.  Add the JavaScript file `js/main.js`. The code in this file uses the Crosswalk extension to echo a message, then display the reply in a new `<p>` element:

        document.addEventListener('DOMContentLoaded', function () {
          echo.echoAsync('Hello world').then(
            function (result) {
              var p = document.createElement('p');
              p.innerHTML = result.content;
              document.body.appendChild(p);
            }
          );
        });

    The listener added to the `DOMContentLoaded` event is the core piece of code here. This is responsible for calling the extension API by invoking the asynchronous `echoAsync()` method, and adding handlers which deal with the returned Promise. The `content` property of the object returned by the extension is used as the text for a new paragraph, which is then appended to the document body.

Now both the web application and the extension are ready, you can create a package and run it on Android.
