# Writing extensions for Tizen IVI on x86

Extensions can be used to extend the capabilities of Crosswalk, enabling applications to make full use of platform features via native code.

Extensions are suitable for a number of use cases:

*   You need to access a device capability not available via Crosswalk's JavaScript APIs: for example, use a specialised sensor or access part of the filesystem outside the web application's sandbox.
*   You want to integrate existing C/C++ libraries with a web application where no JavaScript equivalents are available.
*   You have some intellectual property in your application which you would prefer to not to distribute in an easily-readable JavaScript library.
*   You want to optimise parts of your application but have reached the limits of what you can do with JavaScript.

Crosswalk extensions for Tizen can be implemented in C or C++ and can link with external libraries, potentially allowing you to make use of any of the Tizen Core APIs, as well as other third party libraries.

In addition, extensions are a safe way to add native capabilities to Crosswalk: each runs in its own process on Tizen, so it won't directly affect the stability of the runtime.

## Introduction to the tutorial

This tutorial explains how to write a simple "echo" extension for Crosswalk running on [Tizen IVI](https://wiki.tizen.org/wiki/IVI) (x86 architecture).

The extension accepts an input and returns that input prefixed with "You said: ". It will be used from JavaScript code (in a HTML5 web application) like this:

    var response = echo.echoSync('hello');
    // response == 'You said: hello'

    echo.echoAsync('hello', function (response) {
      // response == 'You said: hello'
    });

The native part of extension is implemented in C. It is trivial and could easily be implemented using pure JavaScript. However, the aim is to reduce the complexity of the extension so you can focus on code structure and workflow.

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

As far as tooling goes, you'll use the Tizen SDK command line tools to compile the extension, and simple text editors to write the code. It is possible to use the Tizen SDK IDE to build Tizen applications; but using command line tools and a text editor exposes the internals of the extension more clearly.

Note that though the tutorial focuses on Tizen IVI, the extension also works on Tizen mobile 3.0 (x86).

**The tutorial contains the following steps:**

1.  [Set up your host development environment](#documentation/tizen_ivi_extensions/host_and_target_setup).
2.  [Write a small web application and Crosswalk Tizen extension](#documentation/tizen_ivi_extensions/build_an_application).
3.  [Run the application and extension using the Tizen emulator](#documentation/tizen_ivi_extensions/run_on_tizen_emulator).

**By the end of this tutorial**, you will understand the basics of writing a C extension for Crosswalk running on Tizen IVI.
