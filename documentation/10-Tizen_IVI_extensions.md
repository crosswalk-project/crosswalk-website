# Writing extensions for Tizen IVI on x86

This tutorial explains how to write a simple "echo" extension for Crosswalk running on [Tizen IVI](https://wiki.tizen.org/wiki/IVI) (x86 architecture).

Crosswalk extensions for Tizen enable you to access native platform capabilities which are otherwise not available via standard web APIs. Extensions can also be used to integrate existing C/C++ libraries with a web application where no JavaScript equivalents are available.

The extension you'll write in this tutorial accepts an input and returns that input prefixed with "You said: ". It will be used from JavaScript code (in a HTML5 web application) like this:

    var response = echo.echoSync('hello');
    // response == 'You said: hello'

    echo.echoAsync('hello', function (response) {
      // response == 'You said: hello'
    });

The extension is implemented in C (though it's possible to write extensions in C++ as well). It is trivial and could easily be implemented using pure JavaScript. However, the aim is to reduce the complexity of the extension so you can focus on code structure and workflow.

As far as tooling goes, you'll use the Tizen SDK command line tools to compile the extension, and simple text editors to write the code. It is possible to use the Tizen SDK IDE to build Tizen applications; but using command line tools and a text editor exposes the internals of the extension more clearly.

Note that though the tutorial focuses on Tizen IVI, the extension also works on Tizen mobile 3.0 (x86).

**The tutorial contains the following steps:**

1.  [Set up your host development environment](#documentation/tizen_ivi_extensions/host_and_target_setup).
2.  [Write a small web application and Crosswalk Tizen extension](#documentation/tizen_ivi_extensions/build_application_and_extension).
3.  [Run the application and extension using the Tizen emulator](#documentation/tizen_ivi_extensions/run_on_tizen_emulator).

**By the end of this tutorial**, you will understand the basics of writing a C extension for Crosswalk running on Tizen IVI.
