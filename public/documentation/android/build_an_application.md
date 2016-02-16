# Building a Crosswalk application

Crosswalk is a runtime for HTML5 applications. This means that existing HTML5 applications should run with Crosswalk. Follow any of the options below to create and package a web application.

* [Use an existing web application project](#existing)
* [Create a sample project using crosswalk-app create &lt;package id&gt;](#create)
* [Manually create a very simple web project](#manual)

## <a class="doc-anchor" id="existing"></a>Use an existing web application
If you already have an existing project, you need only add an icon and a manifest.json file.

<img src="/assets/existing-project.png" style="border:solid black 1px; display: block; margin: 0 auto"/>

See step [3.2](#manifest) below for a description of the manifest.

After adding the icon(s) and manifest, You can proceed to [build your application](#build-application).

## <a class="doc-anchor" id="create"></a>Create a sample project using crosswalk-app
The crosswalk-app tool can create a starting template for your application:
```
> crosswalk-app create <package id>
```
`package-id` is the 3-part Internet-domain name for your package, such as com.abc.myappname. For details on the format, see the [Android package documentation](http://developer.android.com/guide/topics/manifest/manifest-element.html#package).

The command above will create a project with the following structure:

<img src="/assets/create-project.png" style="border:solid black 1px; display: block; margin: 0 auto"/>

Once created, you can proceed to [build your application](#build-application).

## <a class="doc-anchor" id="manual"></a>Manually create a very simple web project
For the purposes of this tutorial, we use the simplest possible Crosswalk application: one HTML file.

1.  First, create a directory called `xwalk-simple` for the project:

        > mkdir xwalk-simple/
        > cd xwalk-simple/

2.  Next, copy an icon file to that directory, to serve as the application icon. You can use this image:

    <img src="/assets/cw-app-icon.png">

    To use this example, right click on the image and select <em>Save Image As...</em> (or its equivalent in your browser). Save it into the `xwalk-simple` directory as `icon.png`. (Note that this image is from the Crosswalk source code and is [BSD licensed](https://github.com/crosswalk-project/crosswalk/blob/master/LICENSE).)

    If you have your own favourite icon, copy that to the `xwalk-simple` directory instead. It should be 128 pixels square.

3.  Create two text files inside `xwalk-simple` (create them using any text editor, e.g. Notepad on Windows, gedit on Ubuntu):

    1. `index.html`

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

    2. <a class="doc-anchor" id="manifest"></a>`manifest.json`

       This contains the application metadata (see above).

       The content should be:

            {
              "name": "simple",
              "xwalk_version": "0.0.1",
              "start_url": "index.html",
              "icons": [
                {
                  "src": "icon.png",
                  "sizes": "128x128",
                  "type": "image/png",
                  "density": "4.0"
                }
              ]
            }

        See [the manifest documentation](/documentation/manifest.html) for more information.

## <a class="doc-anchor" id="build-application"></a>Build the application
Once your application is ready, with an icon and manifest.json file, it is ready to be packaged with Crosswalk. 

    > crosswalk-app build <path>

This command downloads and imports Crosswalk and creates a package using the files in the path. By default, it will create [debug](android_remote_debugging.html), [embedded](/documentation/shared_mode.html), [32-bit](android_64bit.html) APKs for both x86 and ARM architectures (recommended). 64-bit APKs can also be created. For options, see the "Usage" section of the [Crosswalk-app-tools page](/documentation/crosswalk-app-tools.html), or view the help:

    > crosswalk-app help

You are now ready to run the application on a target.
