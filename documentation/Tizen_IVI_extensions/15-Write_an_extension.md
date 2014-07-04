# Write an extension

In this section, you will write, build and package the Crosswalk extension. The extension itself is written in C and built using `make`. For packaging, you'll use the Tizen IVI `gbs` tool, which can build an [rpm file](http://rpm.org/) for a make-enabled project (rpm is the packaging format used by Tizen IVI).

Although you are creating the extension and the application alongside each other in this tutorial, they are actually two separate pieces: one extension can be used to support multiple applications if desired.

The extension consists of three parts:

1.  A JavaScript file. This defines the API which web applications can invoke.

2.  A C header file containing a "stringified" version of the JavaScript file. This is used to define the JavaScript API for the extension inside the C program. This file is generated at build-time, before the C library is compiled.

3.  The C file which implements the native side of the extension.

You also need some supporting files to build and package the extension.

Before starting, make sure you have already followed the steps in [Host and target setup](#documentation/Tizen_IVI_extensions/Host_and_target_setup).

## Create project files and directories

The first step is to set up the basic project directories and include the Crosswalk headers (for compiling the code).

Put the extension in an `echo-extension` directory with these commands:

    > mkdir echo-extension
    > cd echo-extension

    # directory for the Crosswalk extension source
    > mkdir extension

    # directory for the packaging specification file
    > mkdir packaging

    # directory for Crosswalk headers
    > mkdir common

    # initialise the directory as a git repository (see below)
    > git init .

Because you'll be using gbs to build the rpm file for your extension, you need to make your project into a git repository (gbs won't work on plain directories).

### Include Crosswalk headers

You will need a copy of the Crosswalk headers, to compile your extension against:

1.  Checkout the Crosswalk github repo on the host machine (the machine where you intend to compile your extension):

        git clone https://github.com/crosswalk-project/crosswalk ~/crosswalk-source

2.  Copy the Crosswalk headers for extensions into the `common` directory in your project:

        cp ~/crosswalk-source/src/xwalk/extensions/public/*.h echo-extension/common/

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

This JavaScript file is converted into a C header file at build-time; that header file is then referenced from the extension code. This is the simplest way to incorporate the JavaScript code into the C extension. See [this section](#documentation/tizen_ivi_extensions/build_an_application/C-header-file-for-the-JavaScript-API) for details of how the conversion happens.

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

Note that this mirrors the JavaScript file created you created earlier, but is generated by a script (in the root directory of the project). Create a file called `js2c.sh` in the root of the **echo-extension** project, with this content:

    #!/bin/sh
    INPUT=${1:-/dev/stdin}
    [ "$INPUT" = "-" ] && INPUT="/dev/stdin"
    OUTPUT=${2:-/dev/stdout}
    [ "$OUT" = "-" ] && OUT="/dev/stdout"

    if [ ! -r "$INPUT" ] ; then
      echo "Usage: $(basename $0) [js api file] [output c header file]"
      exit 1
    fi

    exec > $OUTPUT

    echo "static const char* kSource_echo_api = "
    tr -d '\r' < "$INPUT" | sed 's:["\\]:\\&:g;s:.*:    "&\\n":'
    echo ";"

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
    #include "XW_Extension.h"
    #include "XW_Extension_SyncMessage.h"

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

*   The [XW_Extension.h](https://github.com/crosswalk-project/crosswalk/blob/master/extensions/public/XW_Extension.h) header is used to define the structures used by the extension system.

*   The only symbol that should be exported is the function:

        int32_t XW_Initialize(XW_Extension extension, XW_GetInterface get_interface)

    Its parameters are:

    *   `extension`: The identifier for this extension. This identifier will be used for the extension
    *   `get_interface`: A function with signature `const void* XW_GetInterface(const char* interface_name)` used for accessing the interfaces provided for integrating native code with the Crosswalk runtime. For each `interface_name`, it returns a pointer to a structure with functions that the extension can call.

    This function should be implemented and exported in the shared object; and it should return `XW_OK` when the extension is correctly initialized.

    Be sure to use `extern "C"` when defining this function to avoid name mangling if using a C++ compiler (you're not in this tutorial).

*   `SetExtensionName()` sets the public name for the JavaScript API which will be available to your web application.

*   `SetJavaScriptAPI()` takes a JavaScript string to be presented as the API. The name you set with `SetExtensionName()` should match the one you use in the JavaScript API string. In the case of this extension, the API string is loaded from a header file which is generated by the build.

*   This example provides synchronous and asynchronous versions of the same handler. But an extension doesn't have to handle both synchronous and asynchronous messages: it can handle only one type if desired.

    You should use asynchronous messaging where the processing performed by the C extension is likely to take some time (i.e. more than a few milliseconds; usually, this means anything involving disk or network access). Where the C code will return quickly (perhaps tens of milliseconds), synchronous messaging is safe enough.

    However, in most cases, the safest approach is to provide an asynchronous API. The synchronous alternative is shown here for the sake of completeness.

*   Both the sync (`XW_Internal_SyncMessagingInterface->SetSyncReply()`) and async (`XW_MessagingInterface->PostMessage()`) functions for returning a response "preserve their inputs", so you can free any pointers you pass to those functions once you've invoked them.

#### More details on the C interfaces

*   Core interface: `XW_CORE_INTERFACE`

    This interface is defined in the [XW_Extension.h](https://github.com/crosswalk-project/crosswalk/blob/master/extensions/public/XW_Extension.h) header file.

    It is a `struct XW_CoreInterface` with the following fields:

    *   `SetExtensionName()` sets name of the extension (identified by `extension`) to `name`. This is mandatory. It should only be called during `XW_Initialize()`.
    *   `SetJavaScriptAPI()` exports the JavaScript shim that will be available to all page contexts. The JavaScript code `api` will be associated with `extension`. It should only be called during `XW_Initialize()`.
    * `RegisterInstanceCallbacks()` informs the Crosswalk runtime of functions that should be called when new instances of the extension are created or destroyed. Instances have the same lifetime of the web content. This should only be called during `XW_Initialize()`
    * `RegisterShutdownCallback()` registers a callback that will be called when the extension is unloaded. This function should only be called during `XW_Initialize()`.
    * `SetInstanceData()` and `GetInstanceData()` are convenience functions that allow for arbitrary data to be associated with each instance, and for that data to be retrieved. These functions may be called at any time during the lifecycle of an instance.

*   Messaging interface: `XW_MESSAGING_INTERFACE`

    This interface is defined in the [XW_Extension.h](https://github.com/crosswalk-project/crosswalk/blob/master/extensions/public/XW_Extension.h) header file.

    It is a `struct XW_MessagingInterface` with the following fields:

    *   `Register()`: when called, this function tells Crosswalk which function should be called in event of a message from the JavaScript side.
    *   `PostMessage()` sends a message to the web content associated with the `instance`.

*   Sync messaging interface (experimental): `XW_INTERNAL_SYNC_MESSAGING_INTERFACE`

    This interface is defined in the [XW_Extension_SyncMessage.h](https://github.com/crosswalk-project/crosswalk/blob/master/extensions/public/XW_Extension_SyncMessage.h) header file. It is marked as internal, and no guarantee will be made for its compatibility with future Crosswalk versions.

    It is a `struct XW_Internal_SyncMessagingInterface` with the following fields:

    *   `Register()`: this function tells Crosswalk which function should be called in event of a synchronous message from the JavaScript side.
    *   `SetSyncReply()` responds to a synchronous (blocking) message from the JavaScript side. The renderer process will be blocked until this function is called.rc/xwalk/extensions/public/*.h

The interface names and structures described above have a versioning suffix in their names. However, extension writers should use the unversioned macros to get the desired interfaces.

## Add build infrastructure

The build infrastructure enables generating an installable rpm package for your extension. This `.rpm` file can then be installed to the Tizen IVI target using the `rpm` package manager tool.

The files you need to add are:

1.  A `Makefile`. This is used to build the C program and output a shared library file `libecho.so`.

    Note that the name is very important: it should begin with a "lib" prefix. Crosswalk will not load the extension correctly if it is called anything else.

2.  An rpm spec file, `packaging/echo-extension.spec`. This file defines how the extension should be packaged and installed on the Tizen system.

### Makefile

You can use a `Makefile` to invoke the compiler and generate the header file for the JavaScript API. In the project directory, add a file called `Makefile` with this content:

    ECHO_CFLAGS=$(CFLAGS) -fPIC -Wall

    all: libecho.so

    echo-extension.h:
	    ./js2c.sh extension/api.js extension/echo-extension.h

    libecho.so: prepare echo-extension.h
	    $(CC) $(ECHO_CFLAGS) -shared -o build/libecho.so \
	      $(SYSROOT_FLAGS) -Icommon extension/echo-extension.c

    prepare:
	    mkdir -p build

    install: libecho.so
	    install -D build/libecho.so \
	      $(DESTDIR)/$(PREFIX)/lib/tizen-extensions-crosswalk/libecho.so

    clean:
	    rm -Rf build

    .PHONY: all prepare clean

(As with all Makefiles, indent using tabs, rather than spaces.)

### RPM spec file

Crosswalk Tizen extensions should be packaged as rpm files. The structure and content of this rpm file is defined in an rpm `.spec` file. `gbs` uses this spec file to compile the extension against the Tizen IVI librarires; then generates an rpm compatible with a Tizen IVI target.

Create the spec file in `packaging/echo-extension.spec`, with this content:

    Name:     echo-extension
    Version:  0.1
    Release:  1
    Summary:  Example Crosswalk Tizen extension
    Group:    System/Libraries

    License:	BSD-3-Clause
    URL:      https://crosswalk-project.org/
    Source0:  %{name}-%{version}.tar.gz

    Requires: crosswalk

    %description
    Example Crosswalk Tizen extension which echoes any messages sent to it.

    %prep
    %setup -q

    %build
    make %{?_smp_mflags}

    %install
    make install DESTDIR=%{buildroot} PREFIX=%{_prefix}

    %files
    %{_prefix}/lib/tizen-extensions-crosswalk/libecho.so

Placing this file in the `packaging/` directory is important, as this is where `gbs` will expect to find a spec file for a project it's building.

The rpm spec file format is a complicated beast, and out of scope for this tutorial. If you want to learn more about it, see the [RPM website](http://rpm.org).

You should also be aware of the [Tizen packaging guidelines](https://wiki.tizen.org/wiki/Packaging/Guidelines), which explain best practices for writing spec files for Tizen packages.

## Run the build

Before running your build with `gbs`, make sure all your changes are committed to the local git repository:

    git add -A
    git commit -m "Initial import"

To build the project using `gbs`, do:

    gbs -c ~/.gbs.conf build -A i586

Note that you're using the gbs configuration file in your home directory, so you're working with the correct Tizen IVI buildroot. It's also important that you set the architecture correctly with the `-A` option: the Tizen IVI images provided from the download site have `i586` architecture.

During the build, `gbs` will download the appropriate Tizen IVI packages, then compile and package your extension. The output `.rpm` files should end up in `/home/ell/GBS-ROOT/local/repos/tizen3.0/i586/RPMS` (unless you changed the gbs root location).

# Acknowledgements

Thanks to José Bollo for improvements to the `js2c.sh` script via [crosswalk-dev](https://lists.crosswalk-project.org/mailman/listinfo/crosswalk-help).
