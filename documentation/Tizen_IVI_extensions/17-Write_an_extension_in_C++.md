# Write an extension in C++

In this section, you will write, build and package a Crosswalk extension for Tizen. The extension itself is written in C++ and built using `make`. For packaging, you'll use the Tizen `gbs` tool, which can build an [rpm file](http://rpm.org/) for a make-enabled project (rpm is the packaging format used by Tizen IVI).

Although you are creating the extension and the application alongside each other in this tutorial, they are actually two separate pieces: one extension can be used to support multiple applications if desired.

The extension consists of three parts:

1.  A JavaScript file. This defines the API which web applications can invoke. It will be embedded in our extension as a global constant (see below).

2.  A C++ class which declares and  sets up the extension.

3.  The C++ class which implements the native side of the extension.

You also need some supporting files to build and package the extension.

Before starting, make sure you have already followed the steps in [Host and target setup](#documentation/Tizen_IVI_extensions/Host_and_target_setup).

## Create project files and directories

The first step is to set up the basic project directories and include the Crosswalk headers and utilities (for compiling the code).

Put the extension in an `echo-extension` directory with these commands:

    > mkdir echo-extension
    > cd echo-extension

    # directory for the Crosswalk extension source
    > mkdir extension

    # directory for the packaging specification file
    > mkdir packaging

    # directory for Crosswalk headers
    > mkdir common

    # directory for helper tools
    > mkdir tools

    # initialise the directory as a git repository (see below)
    > git init .

Because you'll be using gbs to build the rpm file for your extension, you need to make your project into a git repository (gbs won't work on plain directories).

### Include Crosswalk C++ extension headers

You will need a copy of the Crosswalk C++ extension classes, to compile your extension against:

1.  Checkout the tizen-extensions-crosswalk github repo on the host machine (the machine where you intend to compile your extension):

        git clone https://github.com/crosswalk-project/tizen-extensions-crosswalk.git ~/tizen-extensions-crosswalk

2.  Copy the Crosswalk headers and classes for extensions and utilities from the `common` directory into your project:

        cp ~/tizen-extensions-crosswalk/common/*.h echo-extension/common/
        cp ~/tizen-extensions-crosswalk/common/*.cc echo-extension/common/

3. Copy the API generator tool (see below) into the `tools` directory in your project:

        cp ~/tizen-extensions-crosswalk/tools/generate_api.py echo-extension/tools/

### JavaScript bridge API to the C++ extension

This file wires the C++ interface to JavaScript and provides the bridge between the HTML5 application and the C++ code.

Note that it's not essential to maintain the JavaScript in a separate file: you can just add the JavaScript API inline to your C++ code. However, for purposes of maintainability, it makes sense to maintain the JavaScript API in its own file.

Add a file at `extension/echo_api.js` with this content:

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

This JavaScript file is converted into a const char array in a C++ file at build-time; that array is then referenced from the extension code. This is the simplest way to incorporate the JavaScript code into the C++ extension.

#### A note on the JavaScript API

Note that the asynchronous part of this API is *not suitable* for a real production environment.

At the moment, when you invoke the `echoAsync()` method, you set a single global message listener: a function which waits for the next response to be returned by the C++ part of the extension. However, this approach would not work correctly if the processing which occurred in the extension took some time, and the `echoAsync()` method were invoked during that processing time.

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

The solution is to pass a token from the JavaScript API to the C++ code, then return that token as part of the response from the C++ code. The JavaScript API would maintain a mapping from tokens to callbacks, so when responses are returned (containing a token), the correct handler can be looked up and invoked. A typical way to implement this would be to JSON-encode messages between the JavaScript and C++ parts of the extension, and include a token in each message. However, this process is too complex for the scope of this tutorial. Look at the [more elaborate version](#documentation/Tizen_IVI_extensions/18-JSON_based_C++_extension) of this tutorial extension to see that method in action.

If you're interested in seeing a more  real world example, the [Crosswalk Tizen extensions](https://github.com/crosswalk-project/tizen-extensions-crosswalk) are a good place to start, e.g. [the application API JavaScript file](https://github.com/crosswalk-project/tizen-extensions-crosswalk/blob/master/application/application_api.js).

### C++ file for the JavaScript API

The C++ file, `extension/echo_api.cc`, is a generated file which looks like this :

    extern const char kSource_echo_api[];
    const char kSource_echo_api[] = { 47, 42, 42, 10, 32, 42, 32, 74, 97, 118, 
    97, 115, 99, 114, 105, 112, 116, 32, 65, 80, 73, 32, 102, 105, 108, 101, 
       --some lines omitted--
    103, 101, 40, 109, 115, 103, 41, 59, 10, 125, 59, 0 };

The numeric values are the ascii codes of the characters representing the javascript string.

By compiling and linking that file, you can access the `kSource_echo_api` constant, which defines the JavaScript API for the extension.

Note that this mirrors the JavaScript file you created earlier, but is generated by the python script `tools/generate_api.py`.

Invoke it like this:

    python tools/generate_api.py <.js file> <constant name> <output.cc file>

While the script can be invoked manually for testing (you will need python installed on your host for that), the aim is to incorporate it into an automated build later.

### C++ program code

This implements the Crosswalk extension API and has access to the full Tizen native API. For the purposes of this tutorial, the C++ code simply prefixes a message string with "You said: " and returns it.

You need to define two classes, a `common::Extension` child class and a `common::Instance` child class. The former registers your extension to the crosswalk extension system, the latter implements the actual behavior and the communication with the javascrpt API.

#### Extension class

Create a file `extension/echo_extension.h` with this content:

    // echo extension for Crosswalk Tizen
    // adapted from
    // https://github.com/crosswalk-project/crosswalk/blob/master/extensions/test/echo_extension.c
    // Copyright (c) 2013 Intel Corporation. All rights reserved.
    // Use of this source code is governed by a BSD-style license
    // that can be found in the LICENSE file.

    #ifndef ECHO_EXTENSION_H_
    #define ECHO_EXTENSION_H_

    #include "common/extension.h"

    class EchoExtension : public common::Extension {
     public:
      EchoExtension();
      virtual ~EchoExtension();

     private:
      // common::Extension implementation.
      virtual common::Instance* CreateInstance();
    };

    #endif  // ECHO_EXTENSION_H_


And the concrete implemetation in `extension/echo_extension.cc`:

    #include "extension/echo_extension.h"
    #include "extension/echo_instance.h"

    common::Extension* CreateExtension() {
      return new EchoExtension();
    }

    extern const char kSource_echo_api[];

    EchoExtension::EchoExtension() {
      SetExtensionName("echo");
      SetJavaScriptAPI(kSource_echo_api);
    }

    EchoExtension::~EchoExtension() {}

    common::Instance* EchoExtension::CreateInstance() {
      return new EchoInstance();
    }


Some notes on the code:

*   The Extension class is used to define the structures used by the extension system. At a minimum, you must implement the virtual methods `CreateExtension` and `CreateInstance` from `common::Extension`, and also declare the name and API of your extension in the constructor with `SetExtensionName` and `SetJavascriptAPI`.

*   `SetExtensionName()` sets the public name for the JavaScript API which will be available to your web application.

*   `SetJavaScriptAPI()` takes JavaScript as a string  to be presented as the API. The name you set with `SetExtensionName()` should match the one you use in the JavaScript API string. In the case of this extension, the API string is loaded from a C++ file which is generated by the build.

*   More methods from the base class [common::Extension](https://github.com/crosswalk-project/tizen-extensions-crosswalk/blob/master/common/extension.h) can be overridden:

    * `OnShutdown()`: called when the extension is unloaded.
    * `OnInstanceCreated()` and `OnInstanceDestroyed()`: called when new instances of the extension are created or destroyed. Instances have the same lifetime than the web content.


#### Instance class

Create a file `extension/echo_instance.h` with this content:

    // echo extension for Crosswalk Tizen
    // adapted from
    // https://github.com/crosswalk-project/crosswalk/blob/master/extensions/test/echo_extension.c
    // Copyright (c) 2013 Intel Corporation. All rights reserved.
    // Use of this source code is governed by a BSD-style license
    // that can be found in the LICENSE file.

    #ifndef ECHO_INSTANCE_H_
    #define ECHO_INSTANCE_H_

    #include <string>

    #include "common/extension.h"

    class EchoInstance : public common::Instance {
     public:
      EchoInstance();
      ~EchoInstance();

      // common::Instance implementation
      void HandleMessage(const char* message);
      void HandleSyncMessage(const char* message);

     private:
      std::string PrepareMessage(std::string msg) const;
    };

    #endif  // ECHO_INSTANCE_H_

And its implementation in `extension/echo_instance.cc`:

    #include "extension/echo_instance.h"

    EchoInstance::EchoInstance() {
    }

    EchoInstance::~EchoInstance() {
    }

    void EchoInstance::HandleMessage(const char* message) {
      std::string resp = PrepareMessage(message);
      PostMessage(resp.c_str());
    }

    void EchoInstance::HandleSyncMessage(const char* message) {
      std::string resp = PrepareMessage(message);
      SendSyncReply(resp.c_str());
    }

    std::string EchoInstance::PrepareMessage(std::string msg) const {
      return "You said: " + msg;
    }


Some notes on the code:

*   This example provides synchronous and asynchronous versions of the same handler. But an extension doesn't have to handle both synchronous and asynchronous messages: it can handle only one type if desired.

*   The base class implementation calls the `HandleMessage()` method for incoming asynchronous calls and the `HandleSyncMessage()` for synchronous ones.

    You should use asynchronous messaging where the processing performed by the C++ extension is likely to take some time (i.e. more than a few milliseconds; usually, this means anything involving disk or network access). Where the C++ code will return quickly (perhaps tens of milliseconds), synchronous messaging is safe enough.

    However, in most cases, the safest approach is to provide an asynchronous API. The synchronous alternative is shown here for the sake of completeness.

*   Both the sync (`SendSyncReply()`) and async (`PostMessage()`) functions (inherited from `common::Instance`)  for returning a response "preserve their inputs", so you can free any pointers you pass to those functions once you've invoked them.

*   If you need to make some resources setup in your instance, consider implementing the virtual method `Initialize` instead of doing your intialization in the constructor.


## Add build infrastructure

The build infrastructure enables generating an installable rpm package for your extension. This `.rpm` file can then be installed to the Tizen IVI target using the `rpm` package manager tool.

The files you need to add are:

1.  A `Makefile`. This is used to build the C++ program and output a shared library file `libecho.so`.

    Note that the name is very important: it should begin with a "lib" prefix. Crosswalk will not load the extension correctly if it is called anything else.

2.  An rpm spec file, `packaging/echo-extension.spec`. This file defines how the extension should be packaged and installed on the Tizen system.

### Makefile

You can use a `Makefile` to invoke the compiler and generate the header file for the JavaScript API. In the project directory, add a file called `Makefile` with this content:

    ECHO_CFLAGS=$(CFLAGS) -fPIC -Wall
    SOURCES=extension/echo_extension.cc extension/echo_instance.cc extension/echo_api.cc \
     common/extension.cc

    all: libecho.so

    echo_api.cc:
	    python tools/generate_api.py extension/echo_api.js kSource_echo_api extension/echo_api.cc

    libecho.so: prepare echo_api.cc
	    $(CC) $(ECHO_CFLAGS) -shared -o build/libecho.so \
	     $(SYSROOT_FLAGS) -I./ $(SOURCES)

    prepare:
	    mkdir -p build

    install: libecho.so
	    install -D build/libecho.so \
	     $(DESTDIR)/$(PREFIX)/lib/tizen-extensions-crosswalk/libecho.so

    clean:
	    rm -Rf build
	    rm extension/echo_api.cc

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

    License:    BSD-3-Clause
    URL:      https://crosswalk-project.org/
    Source0:  %{name}-%{version}.tar.gz

    BuildRequires: python
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

## Test

Here is a sample test application to check your code:

    <script>

    var callback = function(response) {
      console.log("Async>>> " + response);
    };

    echo.echoAsync('Hello world', callback);

    console.log("Sync -- " + echo.echoSync("Hello tizen"));

    </script>

