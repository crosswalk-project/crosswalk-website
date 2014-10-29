# Write a web application

In this section, you will create the web application (HTML5/JavaScript).

The application can be split into two pieces: the application "proper", containing the HTML, JavaScript, CSS, and other assets; and the metadata describing the application and how it should be installed on the system.

You will also add some supporting files for packaging the extension into a Tizen package (`rpm` file).

Before starting, make sure you have already followed the steps in [Host and target setup](/documentation/tizen_IVI_extensions/host_and_target_setup.html).

In the sections below, you'll create the metadata and the application; then add the build infrastructure.

## Create project files and directories

The first step is to set up the basic project structure inside a `simple-extension-app` directory:

    > mkdir simple-extension-app
    > cd simple-extension-app

    # directory for web application files + metadata
    > mkdir app

    # directory for the packaging specification file
    > mkdir packaging

    # initialise the directory as a git repository (see below)
    > git init .

Because you'll be using gbs to build the rpm file for your extension, you need to make your project into a git repository (gbs won't work on plain directories).

### Add an icon

Copy an icon file to the `simple-extension-app/app` directory, to serve as the application icon (128px square).

You can use this image if you don't have a suitable one of your own:

<img src="/assets/cw-app-icon.png">

To use this example, right click on the image and select <em>Save Image As...</em> (or its equivalent in your browser); save it as `icon.png`.

### Create the manifest

The application metadata consists of platform-specific files which aren't properly part of the application. They are really supporting files, which are used to integrate the application with the environment. Examples might be platform-specific configuration files and icons for different screen resolutions.

A manifest file for an application provides Crosswalk with metadata about that application: for example, which HTML file to load as the entry point, which icon to use for the application, and which permissions the application needs.

For now, this file can be very simple. Create `app/manifest.json` with this content:

    {
      "name": "simple_extension_app",
      "description": "simple extension app (demo)",
      "version": "1.0.0",
      "app": {
        "launch":{
          "local_path": "index.html"
        }
      },
      "icons": {
        "128": "icon.png"
      }
    }

For more information about what the manifest can contain, see [Crosswalk manifest](https://github.com/crosswalk-project/crosswalk-website/wiki/Crosswalk-manifest).

### Create the web application

This is a standalone HTML5 application which uses the Crosswalk extension. It consists of a single HTML file, `index.html`, in the `app` directory. This file also contains the JavaScript to invoke the Crosswalk extension.

Create this file as `app/index.html`:

    <!DOCTYPE html>
    <html>
    <head>
    <title>simple extension app</title>
    <meta name="viewport" content="width=device-width">
    <style>
    body {
      font-size: 2em;
    }
    </style>
    </head>
    <body>

    <p>This uses the echo extension defined to
    extend Crosswalk.</p>

    <div id="out"></div>

    <script>
    var div = document.getElementById('out');

    var p1 = document.createElement('p');
    var p2 = document.createElement('p');

    // async call to extension
    echo.echoAsync('hello async echo', function (result) {
      p1.innerText = result;
      div.appendChild(p1);
    });

    // sync call to extension
    p2.innerText = echo.echoSync('hello sync echo');
    div.appendChild(p2);
    </script>
    </body>
    </html>

Note that the `echo` extension is available globally to the application: there's no need to include a script to make use of it.

When the application runs, the extension's API is invoked asynchronously and synchronously (`echo.echoAsync()` and `echoSync()`). The returned responses (with the "You said: " prefixes added) are used to set the text of two paragraph (`p`) elements.

## Create the package

Now that you have the web application, follow the steps in [Run on Tizen](/documentation/getting_started/run_on_tizen.html) to create the Tizen package (`.xpk` file).

The steps are summarised below:

1.  Add the `make_xpk.sh` script to the project. See [Create the xpk file](/documentation/getting_started/run_on_tizen.html#Create-the-xpk-file) for the content of the script and how to create it.

2.  Create a private key for signing packages:

        > openssl genrsa -out ~/mykey.pem 1024

3.  Create the `.xpk` file:

        > ./make_xpk.sh app/ ~/mykey.pem

    Note that you are just including the `app` directory in the `.xpk` file, not the whole project directory (i.e. not the whole of `simple-extension-app`).

    The output from this command is an `app.xpk` file in the project directory. The [next section](/documentation/tizen_ivi_extensions/run_on_tizen_vm.html) explains how to install and run it.
