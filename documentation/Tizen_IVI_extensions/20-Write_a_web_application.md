# Write a web application

In this section, you will create the web application (HTML5/JavaScript).

The application can be split into two pieces: the application "proper", containing the HTML, JavaScript, CSS, and other assets; and the metadata describing the application and how it should be installed on the system.

You will also add some supporting files for packaging the extension into a Tizen package (`rpm` file).

Before starting, make sure you have already followed the steps in [Host and target setup](#documentation/Tizen_IVI_extensions/Host_and_target_setup).

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

<img src="assets/icon.png">

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

For more information about what the manifest can contain, see [Crosswalk manifest](#wiki/Crosswalk-manifest).

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

## Add build infrastructure

To install the application on the Tizen IVI target, you're going to use the `rpm` package manager. An rpm file is an archive containing the files to be copied to the destination system, along with metadata about what the package contains, where its files should be installed, and (optionally) what to do before and after installation of the files.

In this section, you'll add the following files to the project:

1.  A `make_xpk.sh` script (inside the project) to generate an xpk file.
2.  A private key file `~/tizen-key.pem` (NOT inside the project) for signing xpk files.
3.  A `Makefile` (inside the project) to generate and sign the `xpk` file.
4.  An rpm spec file, `packaging/simple-extension-app.spec` (inside the project): this will package the generated xpk file inside an rpm file.
    The spec file will also contain instructions which instruct the target about what to do when the rpm is installed or uninstalled. In this case, after installing the `xpk` file, the `xwalkctl` command will be invoked to install it to its final position on the filesystem; and after uninstalling the package, `xwalkctl` will be invoked again to remove the application.

### Script to generate xpk file

Follow the steps in [Create a Tizen package](#documentation/getting_started/Run_on_Tizen/Add-the-make_xpk-script) to add the `make_xpk.sh` script to the project; this is needed to generate the `xpk` package file.

Note that you will use xwalk itself to install the package (the `xwalkctl` command), so it properly integrates with its environment. However, this will be done during installation of the rpm file: you'll set up the rpm so that calls to `xwalkctl` automatically install the xpk file when the rpm package is installed. (This is in contrast to the steps in [the Getting started tutorial](#documentation/getting_started/run_on_tizen), where you manually installed the `xpk` file on the target.)

### Create a private key for signing xpk files

You will need a private key file to sign your xpk file. You can generate this with:

    openssl genrsa -out ~/tizen-private-key.pem 1024

Note that this should not be included in the project, but put in your home directory. We'll use this key to sign the xpk file, but won't include it in the generated rpm file.

### Makefile

This uses the `make_xpk.sh` script and the private key file to generate the xpk file and copy it to a `build` directory. It also contains a task to copy the xpk file to the `/usr/share/xwalk-simple-extension-app/` directory. The rpm spec file will use the `install` task to generate the xpk file and copy it to this directory.

    all: package

    package: prepare
	    ./make_xpk.sh app/ $(PEM)
	    mv app.xpk build/simple-extension-app.xpk

    prepare:
	    mkdir -p build/

    install: package
	    install -D build/simple-extension-app.xpk \
	      $(DESTDIR)/$(PREFIX)/share/xwalk-simple-extension-app/simple-extension-app.xpk

    clean:
	    rm -Rf build

    .PHONY: all prepare clean package install

Note that this Makefile needs a `PEM` environment variable for the `package` task; and the `install` task depends on `DESTDIR` and `PREFIX` environment variables.

You can test the Makefile with:

    # make the xpk file in build/simple-extension-app.xpk
    make PEM=~/tizen-key.pem

    # do a mock "install" of the xpk file to the /tmp directory
    # (file should end up in
    make install PEM=~/tizen-key.pem DESTDIR=/tmp

### RPM spec file

This file contains metadata about the application (name, description, licence etc.). It also provides instructions for copying the xpk file to the right place on the target, then installing it using `xwalkctl` command as the "app" user:

    Name:     simple-extension-app
    Version:  0.1
    Release:  1
    Summary:  App which uses a Crosswalk extension
    Group:    Applications/Other

    License:	BSD-3-Clause
    URL:		  https://crosswalk-project.org/
    Source0:	%{name}-%{version}.tar.gz

    BuildRequires: vim
    BuildRequires: zip
    BuildRequires: openssl
    Requires: crosswalk
    Requires: echo-extension

    %description
    App which uses the Crosswalk echo extension

    %prep
    %setup -q

    %build

    %install
    make install DESTDIR=%{buildroot} PREFIX=%{_prefix} PEM=%{PEM}

    %post
    su - app -c "export XDG_RUNTIME_DIR=/run/user/5000 && \
      export DBUS_SESSION_BUS_ADDRESS=unix:path=/run/user/5000/dbus/user_bus_socket && \
      xwalkctl --install %{_prefix}/share/xwalk-simple-extension-app/simple-extension-app.xpk"

    %postun
    out=$(su - app -c "export XDG_RUNTIME_DIR=/run/user/5000 && \
      export DBUS_SESSION_BUS_ADDRESS=unix:path=/run/user/5000/dbus/user_bus_socket && \
      xwalkctl | grep simple_extension_app")
    appid=`echo $out | awk '{print $1}'`
    su - app -c "export XDG_RUNTIME_DIR=/run/user/5000 && \
      export DBUS_SESSION_BUS_ADDRESS=unix:path=/run/user/5000/dbus/user_bus_socket && \
      xwalkctl --uninstall $appid"

    %files
    %{_prefix}/share/xwalk-simple-extension-app/simple-extension-app.xpk

Some notes about the content of this file:

*   The install part of the spec copies the `xpk` file (by default) to the `/usr/share/xwalk-simple-extension-app/` directory.
*   The `post` and `postun` sections call `xwalkctl` to repectively install or uninstall the `xpk` file. Note that `xwalkctl` has to be invoked as the "app" user. Some environment variables are also set so that `xwalkctl` has the correct context to operate within.

## Run the build

Before running your build, make sure all your changes are committed to the local git repository:

    git add -A
    git commit -m "Initial import"

To build the project using `gbs`, do:

    gbs -c ~/.gbs.conf build -A i586 --define "PEM ~/tizen-key.pem"

Note how you pass the location of your private key to `gbs` via the variable `PEM` . This allows you to keep the private key file outside the project directory, but still use it to build the xpk file. Once the xpk file is built, the spec file instructs rpm to embed the xpk file inside the rpm file so it can be installed/uninstalled, as per the spec file's `post` and `postun` directives (see previous section).

Also note that you're using the gbs configuration file from your home directory, so you're working with the correct Tizen IVI buildroot.

During the build, `gbs` will download the appropriate Tizen IVI packages, then package your application. The output `.rpm` files should end up in `/home/ell/GBS-ROOT/local/repos/tizen3.0/i586/RPMS` (unless you changed the gbs root location).

**Note:** Technically speaking, there's no need to use `gbs` to build your web application package, as it is architecture-independent (in fact, when you run the build, you'll get a warning to this effect). However, using `gbs` greatly simplifies configuring your environment for building rpms, as well as applying Tizen rules for rpm linting, both of which simplify the process considerably.
