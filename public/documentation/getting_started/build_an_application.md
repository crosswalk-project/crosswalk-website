# Building a Crosswalk application

Crosswalk is a runtime for HTML5 applications. This means that any existing HTML5 applications should run on Crosswalk, providing they already run in a modern browser (Chrome, Firefox, Safari).

For the purposes of this tutorial, we use the simplest possible Crosswalk application: one HTML file.

However, because Crosswalk applications are intended to integrate well with the target environment, they require an additional file, `manifest.json`, containing metadata for that purpose. The manifest can be used to specify icons to use at different resolutions, set an app description, adjust [content security policy settings](http://developer.chrome.com/extensions/contentSecurityPolicy.html), and otherwise configure how the app integrates with the target environment.

<h2 id="A-simple-application">A simple application</h2>

1.  First, create a directory called `xwalk-simple` for the project:

        > mkdir xwalk-simple/
        > cd xwalk-simple/

2.  Next, copy an icon file to that directory, to serve as the application icon. You can use this image:

    <img src="/assets/icon.png">

    To use this example, right click on the image and select <em>Save Image As...</em> (or its equivalent in your browser). Save it into the `xwalk-simple` directory as `icon.png`. (Note that this image is from the Crosswalk source code and is [BSD licensed](https://github.com/crosswalk-project/crosswalk/blob/master/LICENSE).)

    If you have your own favourite icon, copy that to the `xwalk-simple` directory instead. It should be 128 pixels square.

3.  Create two text files inside `xwalk-simple` (create them using any text editor, e.g. Notepad on Windows, gedit on Ubuntu):

    1.  `index.html`

        This is a single HTML file which represents the user interface for the application. For the purposes of this tutorial, we are not using any CSS or JavaScript.

        The content should be:

            <!DOCTYPE html>
            <html>
              <head>
                <meta name="viewport"
                      content="width=device-width, initial-scale=1.0">
                <meta charset="utf-8">
                <title>simple</title>
              </head>
              <body>
                <p>hello world</p>
              </body>
            </html>

    2.  `manifest.json`

        This contains the application metadata (see above).

        The content should be:

            {
              "name": "simple",
              "version": "0.0.0.1",
              "app": {
                "launch":{
                  "local_path": "index.html"
                }
              },
              "icons": {
                "128": "icon.png"
              }
            }

        See [the page about the manifest](/documentation/manifest.html) for more information.

Once you've done this, you're ready to run the application on a target.
