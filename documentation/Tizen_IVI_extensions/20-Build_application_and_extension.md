# Build application and extension

In this section, you will create the web application (HTML5/JavaScript) and its associated Tizen extension (written in C).

Although we are creating the application and the extension alongside each other, they are actually two separate pieces: one extension can be used to support multiple applications if desired.

The application itself can also be split into two: the application "proper", containing the HTML, JavaScript, CSS, and other assets; and the metadata describing the application and how it should be installed on the system.

The diagram below shows how these pieces interact:

![Structure of a Crosswalk application with extension](assets/crosswalk-extension-layout.png)

In the sections below, we create the metadata, the application, and the extension.

## Create the metadata

The application metadata consists of platform-specific files which aren't properly part of the application. They are really supporting files, which are used to integrate the application with the environment. Examples might be platform-specific configuration files and icons for different screen resolutions.

A manifest file for an application provides Crosswalk with metadata about that application: for example, which HTML file to load as the entry point, which icon to use for the application, and which permissions the application needs.

For now, this file can be very simple. Create `app/manifest.json` with this content:

    {
      "name": "simple_extension",
      "description": "simple extension example",
      "version": "1.0.0",
      "app": {
        "launch":{
          "local_path": "index.html"
        }
      }
    }

For more information about what the manifest can contain, see [Crosswalk manifest](#wiki/Crosswalk-manifest).

## Create the web application

This is a standalone HTML5 application which uses the Crosswalk extension. It consists of a single HTML file, `index.html`, in the `app` directory. This file also contains the JavaScript to invoke the Crosswalk extension.

Create this file as `app/index.html`:

    <!DOCTYPE html>
    <html>
    <head>
    <title>Crosswalk extensions demo</title>
    <meta name="viewport" content="width=device-width">
    <style>
    body {
      font-size: 2em;
    }
    </style>
    </head>
    <body>

    <p>This uses the echo extension defined in
    echo-extension.c (compiled to libecho.so) to
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

## Create the Crosswalk extension

The extension consists of three parts:

1.  A JavaScript file. This defines the API which web applications can invoke.

2.  A C header file containing a "stringified" version of the JavaScript file. This is used to set the JavaScript API for the extension. This file is generated at build-time, before the C library is compiled.

3.  The C file which implements the native side of the extension. This is compiled into a shared library file `libecho.so`.

    Note that the name is very important: it should begin with a "lib" prefix. Crosswalk will not load the extension correctly if it is called anything else.

### JavaScript bridge API to the C extension

This file wires the C interface to JavaScript and provides the bridge between the HTML5 application and the C code.

Note that it's not essential to maintain the JavaScript in a separate file: you can just add the JavaScript API inline to your C code. However, for purposes of maintainability, it makes sense to maintain the JavaScript API in its own file.

Add a file at `extension/api.js` with this content:

    var echoListener = null;

    extension.setMessageListener(function(msg) {
      if (echoListener instanceof Function) {
        echoListener(msg);
      };
    });

    exports.echoAsync = function (msg, callback) {
      echoListener = callback;
      extension.postMessage(msg);
    };

    exports.echoSync = function (msg) {
      return extension.internal.sendSyncMessage(msg);
    };

This JavaScript file is converted into a C header file at build-time; that header file is then referenced from the extension code. This is the simplest way to incorporate the JavaScript code into the C extension. See [this section](#documentation/tizen_ivi_extensions/build_application_and_extension/C-header-file-for-the-JavaScript-API) for details of how the conversion happens.

#### A note on the JavaScript API

Note that the asynchronous part of this API is *not suitable* for a real production environment.

At the moment, when you invoke the `echoAsync()` method, you set a single global message listener: a function which waits for the next response to be returned by the C part of the extension. However, this approach would not work correctly if the processing which occurred in the extension took some time, and the `echoAsync()` method were invoked during that processing time.

For example, consider the following program:

    var callback1 = function (response) {
      console.log(response + ' world');
    };

    var callback2 = function (response) {
      console.log(response + ' cruel world');
    };

    // invocation 1
    echo.echoAsync('hello', callback1);

    // invocation 2
    echo.echoAsync('goodbye', callback2);

When invocation 1 occurs, the message listener is set to `callback1`. If the extension takes several seconds to respond, invocation 2 may have already happened before invocation 1 completes; and invocation 2 sets the message listener to `callback2` (there is only one listener for all responses). Consequently, when invocation 1 does eventually return, its handler (`callback1`) has been overwritten; so the responses for both invocation 1 and invocation 2 will be (incorrectly) handled by `callback2`.

In practice, this means that you would see this on the console:

    hello cruel world
    goodbye cruel world

instead of the anticipated:

    hello world
    goodbye cruel world

The solution is to pass a token from the JavaScript API to the C code, then return that token as part of the response from the C code. The JavaScript API would maintain a mapping from tokens to callbacks, so when responses are returned (containing a token), the correct handler can be looked up and invoked. A typical way to implement this would be to JSON-encode messages between the JavaScript and C parts of the extension, and include a token in each message. However, this process is too complex for the scope of this tutorial.

If you're interested in seeing a real world example of how this would be implemented, the [Crosswalk Tizen extensions](https://github.com/crosswalk-project/tizen-extensions-crosswalk) are a good place to start, e.g. [the application API JavaScript file](https://github.com/crosswalk-project/tizen-extensions-crosswalk/blob/master/application/application_api.js).

### C header file for the JavaScript API

The header file, `extension/echo-extension.h`, is a generated file which looks like this:

    static const char* kSource_echo_api = "var echoListener = null;"
    ""
    "extension.setMessageListener(function(msg) {"
    "  if (echoListener instanceof Function) {"
    "    echoListener(msg);"
    "  };"
    "});"
    ""
    "exports.echoAsync = function (msg, callback) {"
    "  echoListener = callback;"
    "  extension.postMessage(msg);"
    "};"
    ""
    "exports.echoSync = function (msg) {"
    "  return extension.internal.sendSyncMessage(msg);"
    "};"
    ;

By including this header file in a C file, you can access the `kSource_echo_api` constant, which defines the JavaScript API for the extension.

Note that this mirrors the JavaScript file created we created earlier, but is generated by a script (in the root directory of the project). Create a file called `js2c.sh` in the root of the **simple** project, with this content:

    #!/bin/sh
    JS=$1
    COUT=$2

    if [ ! $JS -o ! $COUT ] ; then
      echo "Usage $0 <js api file> <output c header file>"
      exit 1
    fi

    echo -n "static const char* kSource_echo_api = " > $COUT

    cat $JS | awk -F\n '{print "\"" $_ "\""}' | \
      tr -d $'\r' >> $COUT

    echo ";" >> $COUT

You should make the script executable once you've created it with:

    chmod +x js2c.sh

Invoke it like this:

    ./js2c.sh <.js file> <output .h file>

While the script can be invoked manually for testing, the aim is to incorporate it into an automated build later.

### C program code

This implements the Crosswalk extension API and has access to the full Tizen native API. For the purposes of this tutorial, the C code simply prefixes a message string with "You said: " and returns it.

Create a file `extension/echo-extension.c` with this content:

    // echo extension for Crosswalk Tizen
    // adapted from
    // https://github.com/crosswalk-project/crosswalk/blob/master/extensions/test/echo_extension.c
    // Copyright (c) 2013 Intel Corporation. All rights reserved.
    // Use of this source code is governed by a BSD-style license
    // that can be found in the LICENSE file.

    #include <stdio.h>
    #include <stdlib.h>
    #include <string.h>
    #include "xwalk/extensions/public/XW_Extension.h"
    #include "xwalk/extensions/public/XW_Extension_SyncMessage.h"

    // load kSource_echo_api string to set JavaScript API;
    // echo-extension.h is generated by the makefile at build time
    #include "echo-extension.h"

    static const char* echo_ext_response_prefix = "You said: ";

    static XW_Extension g_extension = 0;
    static const XW_CoreInterface* g_core = NULL;
    static const XW_MessagingInterface* g_messaging = NULL;
    static const XW_Internal_SyncMessagingInterface* g_sync_messaging = NULL;

    static void instance_created(XW_Instance instance) {
      printf("Instance %d created!\n", instance);
    }

    static void instance_destroyed(XW_Instance instance) {
      printf("Instance %d destroyed!\n", instance);
    }

    // add a "You said: " prefix to message
    static char* build_response(const char* message) {
      int length = strlen(echo_ext_response_prefix) +
                   strlen(message);
      char* response = malloc(length);
      strcpy(response, echo_ext_response_prefix);
      strcat(response, message);
      return response;
    }

    static void handle_message(XW_Instance instance, const char* message) {
      char* response = build_response(message);
      g_messaging->PostMessage(instance, response);
      free(response);
    }

    static void handle_sync_message(XW_Instance instance, const char* message) {
      char* response = build_response(message);
      g_sync_messaging->SetSyncReply(instance, response);
      free(response);
    }

    static void shutdown(XW_Extension extension) {
      printf("Shutdown\n");
    }

    // this is the only function which needs to be public
    int32_t XW_Initialize(XW_Extension extension, XW_GetInterface get_interface) {
      // set up the extension
      g_extension = extension;
      g_core = get_interface(XW_CORE_INTERFACE);
      g_core->SetExtensionName(extension, "echo");

      // kSource_echo_api comes from the echo-extension.h
      // header file
      g_core->SetJavaScriptAPI(extension, kSource_echo_api);

      g_core->RegisterInstanceCallbacks(
        extension, instance_created, instance_destroyed);
      g_core->RegisterShutdownCallback(extension, shutdown);

      g_messaging = get_interface(XW_MESSAGING_INTERFACE);
      g_messaging->Register(extension, handle_message);

      g_sync_messaging = get_interface(XW_INTERNAL_SYNC_MESSAGING_INTERFACE);
      g_sync_messaging->Register(extension, handle_sync_message);

      return XW_OK;
    }

Some notes on the code:

*   The only mandatory public function is `XW_Initialize()`, where the work is done to configure the extension.

*   `SetExtensionName()` sets the public name for the JavaScript API which will be available to your web application.

*   `SetJavaScriptAPI()` takes a JavaScript string to be presented as the API. The name you set with `SetExtensionName()` should match the one you use in the JavaScript API string. In the case of this extension, the API string is loaded from a header file which is generated by the build.

*   This example provides synchronous and asynchronous versions of the same handler. But an extension doesn't have to handle both synchronous and asynchronous messages: it can handle only one type if desired.

    You should use asynchronous messaging where the processing performed by the C extension is likely to take some time (i.e. more than a few milliseconds; usually, this means anything involving disk or network access). Where the C code will return quickly (perhaps tens of milliseconds), synchronous messaging is safe enough.

    However, in most cases, the safest approach is to provide an asynchronous API. The synchronous alternative is shown here for the sake of completeness.

*   Both the sync (`XW_Internal_SyncMessagingInterface->SetSyncReply()`) and async (`XW_MessagingInterface->PostMessage()`) functions for returning a response "preserve their inputs", so you can free any pointers you pass to those functions once you've invoked them.

## Build the extension

The C compiler is part of the Tizen SDK. The compiler for x86 architecture is:

    <tizen SDK>/tools/i386-linux-gnueabi-gcc-4.5/bin/i386-linux-gnueabi-gcc-4.5.4.exe

The Tizen SDK also provides a *rootstrap*, which contains headers and libraries for compiling your code against. For code you intend to run on the emulator, the rootstrap is located at:

    <tizen SDK>/platforms/mobile-3.0/rootstraps/mobile-3.0-emulator.native

You can use a small `makefile` to invoke the compiler and generate the header file for the JavaScript API. The make file will also contain some conditional code, so that if the `TIZEN_SDK` environment variable is set, the Tizen SDK compiler and rootstrap will be used for compilation.

In the project directory, add a file called `makefile` with this content:

    ifneq ($(strip $(TIZEN_SDK)),)
	    CC=$(TIZEN_SDK)/tools/i386-linux-gnueabi-gcc-4.5/bin/i386-linux-gnueabi-gcc-4.5.4.exe
	    SYSROOT_FLAGS=--sysroot $(TIZEN_SDK)/platforms/mobile-3.0/rootstraps/mobile-3.0-emulator.native
    endif

    ECHO_CFLAGS=$(CFLAGS) -fPIC -Wall

    all: libecho.so
	    cp -a app/* build/app/

    echo-extension.h:
	    ./js2c.sh extension/api.js extension/echo-extension.h

    libecho.so: prepare echo-extension.h
	    $(CC) $(ECHO_CFLAGS) -shared -o build/extension/libecho.so
	      $(SYSROOT_FLAGS) -I$(XWALK_HEADERS) extension/echo-extension.c

    prepare: check
	    mkdir -p build/app
	    mkdir -p build/extension

    check:
    ifeq ($(strip $(XWALK_HEADERS)),)
	    echo "XWALK_HEADERS must be set"
	    exit 1
    endif

    clean:
	    rm -Rf build

    .PHONY: all prepare check clean

(As with all makefiles, indent using tabs, rather than spaces.)

The `--sysroot` option is set so that the libraries and headers used as the ones included with the Tizen SDK, rather than the host's.

The Tizen SDK provides `make` in `<tizen-sdk>/tools/mingw/bin/make.exe`. You added this to your `PATH` variable at the start of this tutorial. So you can now invoke the above `makefile` from your **simple** project directory. In a bash shell, run:

    TIZEN_SDK=/path/to/tizen-sdk XWALK_HEADERS=/path/to/crosswalk-source make

`/path/to/tizen-sdk` should point at the root directory of your Tizen SDK installation (e.g. `~/tizen-sdk` if you use the default location).

`/path/to/crosswalk-source` should point at the directory *above* the Crosswalk source code; the Crosswalk source code itself should be in a directory called `xwalk`.

Once the build completes, the output directory `build/` should contain two folders: `app` and `extension`. `app` contains the web application and its manifest; `extension` contains the compiled extension library (`libecho.so`).

Also note that you can use you host's compiler, providing you compile for the correct architecture (Tizen IVI emulator images are 32 bit). For example, on a 64 bit host, you would do:

    CFLAGS=-m32 XWALK_HEADERS=/path/to/crosswalk-source make
