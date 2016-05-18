# Extensions for Windows Apps
Extensions enhance the capabilities of the Crosswalk Project, enabling applications to make full use of platform features via native code.

Extensions are suitable for a number of use cases:

* Accessing a device capability not available via Crosswalk's JavaScript APIs. For example, using a specialised sensor or accessing part of the filesystem outside the web application's sandbox.
* Integrating existing C/C++ libraries with a web application where no JavaScript equivalents are available.
* Including technology or intellectual property in your application without distributing an easily-readable JavaScript library.
* Optimizing parts of your application after reaching the limits of what can be done with JavaScript.

Crosswalk extensions can be implemented in C or C++ and can link with external libraries, potentially allowing you to make use of any of the underlaying OS APIs, as well as other third party libraries.  In addition, extensions are a safe way to add native capabilities to Crosswalk: each runs in its own process, so it won't directly affect the stability of the runtime.

This tutorial explains how to write a simple "echo" extension for Crosswalk running on 64-bit Windows. The extension accepts an input and returns that input prefixed with "You said: ". It will be used from JavaScript code (in an HTML5 web application) like this:

    // Synchronous call will wait until function returns
    var response = echo.echoSync('hello');
      // response == 'You said: hello'

    // Asynchronous call. Pass in the callback function as the 2nd paramter
    echo.echoAsync('hello', function (response) {
      // response == 'You said: hello'
    });

The native part of the extension can be implemented in C or C++. Our simple example is trivial and could be implemented using JavaScript. However, the aim is to reduce the complexity of the extension to focus on code structure and workflow.

The C code which corresponds to the JavaScript API above looks like this:

    // echoSync() handler
    static void handle_sync_message(XW_Instance instance, const char* message) {
      char* response = build_response(message);
      g_sync_messaging->SetSyncReply(instance, response);
      free(response);
    }

    // echoAsync() handler
    static void handle_message(XW_Instance instance, const char* message) {
      char* response = build_response(message);
      g_messaging->PostMessage(instance, response);
      free(response);
    }

In this section, we write, build and package the Crosswalk extension. The extension itself is written in C and built using Visual Studio. Although we create both the extension and the application in this tutorial, they are actually two separate pieces: one extension can be used to support multiple applications, if desired.

The extension consists of three parts:

* A JavaScript file. This defines the API which web applications can invoke.
* A C header file containing a "stringified" version of the JavaScript file. This is used to define the JavaScript API for the extension inside the C program. This file is generated at build-time, before the C library is compiled.
* The C file which implements the native side of the extension.

You also need some supporting files to build and package the extension.

## Install the Visual Studio Template

Download the template zip file: [Crosswalk C-C++ Extensions Template.zip](/assets/Crosswalk%20C-C++%20Extensions%20Template.zip>) (14KB)

Copy the zip file into: `C:\Users\<your user name>\Documents\Visual Studio 2015\Templates\ProjectTemplates\Visual C++ Project\` 

 (Note: Windows File Explorer may display "Documents" as "My Documents") <br><br>
 
<img src="/assets/windows-extension-dir.png" style="display: block; margin: 0 auto"/>

## Getting started

Open Visual Studio and click “New Project” then “Templates” then “Visual C++” then in the list select “Crosswalk C-C++ Extensions Template”

<img src="/assets/windows-extension-dlg.png" style="display: block; margin: 0 auto"/>

Select the location of your code and enter the name of your project.

<img src="/assets/windows-extension-dlg2.png" style="display: block; margin: 0 auto"/>

## JavaScript bridge API to the C extension
This file wires the C interface to JavaScript and provides the bridge between the HTML5 application and the C code.

Let’s look at the JavaScript API :

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

#### A note on the JavaScript API
The asynchronous part of this API is not suitable for a real production environment.
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

When invocation 1 occurs, the message listener is set to `callback1`. If the extension takes several seconds to respond, invocation 2 may have already happened before invocation 1 completes; and invocation 2 sets the message listener to `callback2` (there is only one listener for all responses). Consequently, when invocation 1 does eventually return, its handler (callback1) has been overwritten; so the responses for both invocation 1 and invocation 2 will be (incorrectly) handled by callback2.
In practice, this means that you would see this on the console:

    hello cruel world
    goodbye cruel world

instead of the anticipated:

    hello world
    goodbye cruel world

The solution is to pass a token from the JavaScript API to the C code, then return that token as part of the response from the C code. The JavaScript API would maintain a mapping from tokens to callbacks, so when responses are returned (containing a token), the correct handler can be looked up and invoked. A typical way to implement this would be to JSON-encode messages between the JavaScript and C parts of the extension, and include a token in each message. However, this process is too complex for the scope of this tutorial.

## C static constant for the JavaScript API
In `extension.c`, you should have:

    static const char* kAPI= "var echoListener = null;"
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

## C program code
This implements the Crosswalk extension API and has access to the full native API. For the purposes of this tutorial, the C code simply prefixes a message string with "You said: " and returns it.

Create a file `extension/echo-extension.c` with this content:

    // echo extension for Crosswalk
    // adapted from
    // https://github.com/crosswalk-project/crosswalk/blob/master/extensions/test/echo_extension.c
    // Copyright (c) 2016 Intel Corporation. All rights reserved.
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
        int length = strlen(echo_ext_response_prefix) + strlen(message);
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

#### Some notes on the code:

* The `XW_Extension.h` header is used to define the structures used by the extension system.
* The only symbol that should be exported is the function:
`int32_t XW_Initialize(XW_Extension extension, XW_GetInterface get_interface)`
Its parameters are:

 * `extension`: The identifier for this extension. This identifier will be used for the extension
 * `get_interface`: A function with signature `const void* XW_GetInterface(const char* interface_name)` used for accessing the interfaces provided for integrating native code with the Crosswalk runtime. For each `interface_name`, it returns a pointer to a structure with functions that the extension can call.

* This function should be implemented and exported in the shared object; and it should return `XW_OK` when the extension is correctly initialized.
* Be sure to use `extern "C"` when defining this function to avoid name mangling if using a C++ compiler (you're not in this tutorial).
* `SetExtensionName()` sets the public name for the JavaScript API which will be available to your web application.
* `SetJavaScriptAPI()` takes a JavaScript string to be presented as the API. The name you set with `SetExtensionName()` should match the one you use in the JavaScript API string. In the case of this extension, the API string is loaded from a header file which is generated by the build.
* This example provides synchronous and asynchronous versions of the same handler. But an extension doesn't have to handle both synchronous and asynchronous messages: it can handle only one type if desired.
* You should use asynchronous messaging where the processing performed by the C extension is likely to take some time (i.e. more than a few milliseconds; usually, this means anything involving disk or network access). Where the C code will return quickly (perhaps tens of milliseconds), synchronous messaging is safe enough.
* However, in most cases, the safest approach is to provide an asynchronous API. The synchronous alternative is shown here for the sake of completeness.
* Both the sync (`XW_Internal_SyncMessagingInterface->SetSyncReply()`) and async (`XW_MessagingInterface->PostMessage()`) functions for returning a response "preserve their inputs", so you can free any pointers you pass to those functions once you've invoked them.

## More details on the C interfaces

### Core interface: XW_CORE_INTERFACE
* This interface is defined in the `XW_Extension.h` header file.
* It is a struct `XW_CoreInterface` with the following fields:
 * `SetExtensionName()` sets name of the extension (identified by extension) to name. This is mandatory. It should only be called during `XW_Initialize()`.
 * `SetJavaScriptAPI()` exports the JavaScript shim that will be available to all page contexts. The JavaScript codeapi will be associated with extension. It should only be called during `XW_Initialize()`.
 * `RegisterInstanceCallbacks()` informs the Crosswalk runtime of functions that should be called when new instances of the extension are created or destroyed. Instances have the same lifetime of the web content. This should only be called during `XW_Initialize()`.
 * `RegisterShutdownCallback()` registers a callback that will be called when the extension is unloaded. This function should only be called during `XW_Initialize()`.
 * `SetInstanceData()` and `GetInstanceData()` are convenience functions that allow for arbitrary data to be associated with each instance, and for that data to be retrieved. These functions may be called at any time during the lifecycle of an instance.

### Messaging interface: XW_MESSAGING_INTERFACE
* This interface is defined in the `XW_Extension.h` header file.
* It is a struct `XW_MessagingInterface` with the following fields:
 * `Register()`: when called, this function tells Crosswalk which function should be called in event of a message from the JavaScript side.
 * `PostMessage()` sends a message to the web content associated with the instance.

### Sync messaging interface (experimental): XW_INTERNAL_SYNC_MESSAGING_INTERFACE
* This interface is defined in the `XW_Extension_SyncMessage.h` header file. It is marked as internal, and no guarantee will be made for its compatibility with future Crosswalk versions.
* It is a struct `XW_Internal_SyncMessagingInterface` with the following fields:
 * Register(): this function tells Crosswalk which function should be called in event of a synchronous message from the JavaScript side.
 * SetSyncReply() responds to a synchronous (blocking) message from the JavaScript side. The renderer process will be blocked until this function is called.rc/xwalk/extensions/public/*.h

The interface names and structures described above have a versioning suffix in their names. However, extension writers should use the unversioned macros to get the desired interfaces.

## Build the project
You can just build the solution in Visual Studio which should just output the .DLL of your extension.

## Run your extension
After downloading Crosswalk for Windows and unzipping it you can easily try your extension.

Inside your command line, navigate to the path where you unzipped Crosswalk and launch it using the method described in Run using Crosswalk binary directly with the following parameters:

* --allow-external-extensions-for-remote-sources (which allow external extension for localhost, you do not need to worry about that when packing with Crosswalk app tools), and

* --external-extensions-path=/path/to/myextension/myextension/directory.

The command line should look like this :

```cmdline
> xwalk.exe --allow-external-extensions-for-remote-sources \
            --external-extensions-path=/path/to/myextension/directory \
            http://localhost:8000
```

Your extension should be loaded. Please note that you can put multiple extensions in the same directory, Crosswalk will load all of them.
