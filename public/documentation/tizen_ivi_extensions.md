# Writing extensions for Tizen IVI on x86

Extensions enhance the capabilities of Crosswalk, enabling applications to make full use of platform features via native code.

Extensions are suitable for a number of use cases:

*   You need to access a device capability not available via Crosswalk's JavaScript APIs: for example, use a specialised sensor or access part of the filesystem outside the web application's sandbox.
*   You want to integrate existing C/C++ libraries with a web application where no JavaScript equivalents are available.
*   You have some intellectual property in your application which you would prefer to not to distribute in an easily-readable JavaScript library.
*   You want to optimize parts of your application but have reached the limits of what you can do with JavaScript.

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

The native part of extension is implemented in C or in C++. Note that it is trivial and could easily be implemented using pure JavaScript. However, the aim is to reduce the complexity of the extension to focus on code structure and workflow.

This tutorial covers both C and C++. If you are a developer willing to contribute to the Tizen API crosswalk extensions, you should choose the C++ way.

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

The diagram below shows how the application and the extension interact:

![Structure of a Crosswalk application with extension](/assets/crosswalk-extension-layout.png)

**The tutorial contains the following steps:**

1.  [Set up your host development environment](/documentation/tizen_ivi_extensions/host_and_target_setup.html).
2.  [Write a Tizen Crosswalk extension in C](/documentation/tizen_ivi_extensions/write_an_extension_in_c.html).
3.  [Write a Tizen Crosswalk extension in C++](/documentation/tizen_ivi_extensions/write_an_extension_in_c++.html).
4.  [Write a web application](/documentation/tizen_ivi_extensions/write_a_web_application.html).
<!-- 5.  [Run the application and extension using the Tizen emulator](/documentation/tizen_ivi_extensions/run_on_tizen_emulator.html). -->

As far as tooling goes, you'll use command line tools to compile the extension, and simple text editors to write the code. It is possible to use the Tizen SDK IDE to build Tizen applications; but using command line tools and a text editor exposes the internals of the extension more clearly.

Note that the tutorial focuses on Tizen IVI, but works exactly the same on Tizen Common. The tutorial uses Linux as a build environment, as the tool for creating native Tizen packages (`gbs`) is not compatible with Windows.

**By the end of this tutorial**, you will understand the basics of writing a C or C++ extension for Crosswalk running on Tizen IVI.
