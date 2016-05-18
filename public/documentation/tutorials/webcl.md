# WebCL Overview

[WebCL](https://www.khronos.org/webcl/) defines a JavaScript binding to
[the Khronos OpenCL standard](https://www.khronos.org/opencl/) for
heterogeneous parallel computing. WebCL enables web applications to
harness GPU and multi-core CPU parallel processing and achieve
significant acceleration. The WebCL 1.0 specification was released by
Khronos Group in 2014, which is based on OpenCL 1.1 (Embedded Profile).

**WebCL does not make Facebook run faster on your browser. According to
Amdahl's law, the speedup of a program using multiple processors in
parallel computing is limited by the serial part of the program.**

WebCL has been enabled by default on Android since Crosswalk 14.
Currently, the feature requires OpenCL runtime library to be installed.
There are [several apps available in the Play Store](https://play.google.com/store/search?q=opencl&c=apps)
to check its existence. Some OEM ship the OpenCL runtime library by
default in their products, and the library is typically named as
`libOpenCL.so`, `libOpenCL.so.1` or `libPVROCL.so` in `/system/lib/` or
`/system/vendor/lib/`. If you cannot find any of these libraries, then
unfortunately WebCL will not work on your device, and you probably want
to manually install the OpenCL runtime library.

In this tutorial, we will create our first WebCL program. The program
calculates the square root of 1,000,000 numbers stored in an array, or
in other words, it is the parallel implementation of the following
JavaScript program:

    var len = 1000000;
    var i;
    var arr1 = new Float32Array(len);
    var arr2 = new Float32Array(len);
    for (i = 0; i < len; i++)
      arr1[i] = i;
    for (i = 0; i < len; i++)
      arr2[i] = Math.sqrt(arr1[i]);

A WebCL program is actually involved with two languages. The first one
is the **kernel** program in C99-like syntax, which runs on your GPU or
any other OpenCL devices. The other is the host side program in
JavaScript, which controls the execution of the kernel program by
invoking the APIs defined by the WebCL specification.

To implement the program above with WebCL, we will go through the
following steps one by one:

* Create a WebCL context.
* Allocate buffers on your WebCL device. They will be used to hold the
input and output data.
* Create a WebCL program object. The program object contains the code to
be executed on your WebCL device.
* Create a WebCL kernel object. The kernel object designates the
function to be called in the WebCL program, as well as the function
arguments.
* Kick off the computing by creating a command queue and enqueuing a
series of commands into the command queue.

# Creating a WebCL context

A WebCL context is the collection of a lot of things. It designates the
device(s) we are going to use, and it also manages other WebCL objects
like memory buffers, command queues and WebCL programs. The following
shows how to create a WebCL context which utilizes GPU as the backend:

    var context = webcl.createContext(webcl.DEVICE_TYPE_GPU);

In case the GPU is not OpenCL-capable, we also have a try on CPU, and if
that fails again, an exception will be thrown. Here is a more robust
context creation:

    var context;
    try {
      context = webcl.createContext(webcl.DEVICE_TYPE_GPU);
    } catch (e) {
      try {
        context = webcl.createContext(webcl.DEVICE_TYPE_CPU);
      } catch (e) {
        alert("WebCL is not supported by your device!");
        throw e;
      }
    }

# Allocating input and output buffers

For the sake of simplicity, we still use the sequential way to construct
our input array:

    var len = 1000000;
    var i;
    var arr1 = new Float32Array(len);
    for (i = 0; i < len; i++)
      arr1[i] = i;

However, the variable `arr1` cannot be accessed directly by the kernel
program we are going to create soon. A buffer needs to be allocated in
this WebCL context and initialized with data in `arr1`:

    var buf1 = context.createBuffer(webcl.MEM_READ_ONLY, arr1.byteLength, arr1);

The constant `webcl.MEM_READ_ONLY` means that the created buffer will be
read-only for the kernel program, so that specific optimizations can be
performed based on this hint.

Similarly, kernel program cannot write to a JavaScript typed array
directly. We must create another buffer as an intermediate holder for
the result:

    var buf2 = context.createBuffer(webcl.MEM_WRITE_ONLY, arr1.byteLength);

You must have guessed what the first argument `webcl.MEM_WRITE_ONLY`
means. Also, we do not provide a third argument here since there's no
need to initialize the output buffer with an existing typed array.

# Creating the WebCL kernel program object

To parallelize the calculation, we divide the task into 1,000,000 **work
item**s. Each work item is assigned with a unique ID starting from 0,
and it just calculates the square root of one element in the input
array. The logic of each work item is programmed as a function in
C99-like syntax, and the following shows how to create the WebCL kernel
program object:

    var source =
    "__kernel void sqrtPar(__global float *buf1, __global float *buf2)\n" +
    "{\n" +
    "  size_t i = get_global_id(0);\n" +
    "  buf2[i] = sqrt(buf1[i]);\n" +
    "}";
    var program = context.createProgram(source);

`source` contains the source code of the WebCL kernel program. There are
a few things to be explained here:

* The keyword `__kernel` indicates that the function `sqrtPar()` will be
"exported" so that we can find it later from the host side in
JavaScript. Such kind of functions will be termed as **kernel
function**s later in the tutorial. The return type of a kernel function
must be `void`. If a function is not annotated with `__kernel`, then it
will be invisible to the host side and therefore it can only be invoked
internally by the WebCL kernel program.
* `buf1` and `buf2` will point to the input and output buffers we
allocated before. There are different kinds of memory address spaces on
your OpenCL device, and access speed differs among them. The keyword
`__global` means that the arrays pointed to by `buf1` and `buf2` are
located in the global memory (since `createBuffer()` always allocates
buffers in global memory).
* `get_global_id()` is a built-in function and it returns the ID of the
work item in the dimension identified by its argument. We will simply
launch `sqrtPar()` in one dimension, so in each work item we just read
the ID in first dimension by passing `0` to `get_global_id()`. Sometimes
we may want to launch WebCL kernel programs in two dimensions. As an
example, in image processing we can use `get_global_id(0)` and
`get_global_id(1)` to get the horizontal and vertical coordination of
the pixel the work item handles.
* `sqrt()` is another built-in function and it calculates the square
root of a float number.

Next, the WebCL kernel program is built into device-specific machine
code:

    program.build();

If there's anything wrong with your WebCL kernel program, then an
exception will be thrown.

# Creating the WebCL kernel object

Now we set the value of `buf1` and `buf2` in our WebCL kernel program.

In a complex WebCL kernel program, there might be multiple kernel
functions, and one kernel function might be called multiple times with
different arguments. Information about the kernel function name and its
associated arguments are bundled in WebCL kernel objects. The following
shows how to create a WebCL kernel object for function `sqrtPar()`:

    var kernel = program.createKernel("sqrtPar");

And we let `buf1` and `buf2` point to the input and output buffer
separately:

    kernel.setArg(0, buf1);
    kernel.setArg(1, buf2);

`0` and `1` are the corresponding indices in the kernel function's
parameter list.

# Launching the WebCL kernel program

Finally, we are about to launch our WebCL kernel program. We create a
command queue, enqueue a kernel launch command into it, and copy the
result back to JavaScript scope by enqueuing a buffer read command.
Commands in the command queue will be submitted to your OpenCL device
for execution. The procedure is shown below:

    var queue = context.createCommandQueue();

    var workDim = 1;
    var globalWorkOffset = null;
    var globalWorkSize = [len];
    queue.enqueueNDRangeKernel(kernel, workDim, globalWorkOffset, globalWorkSize);

    var arr2 = new Float32Array(len);
    var blockingRead = true;
    var bufOffset = 0;
    var bufSize = arr2.byteLength;
    queue.enqueueReadBuffer(buf2, blockingRead, bufOffset, bufSize, arr2);

As it is mentioned before, `sqrtPar()` is launched in one dimension
(`workDim = 1`), and totally 1,000,000 work items are spawned
(`globalWorkSize = [len]`). In this tutorial we do not care about
`globalWorkOffset` and simply initialize it with `null`.

`blockingRead = true` means that `enqueueReadBuffer()` won't return
until the read command is finished. This is not recommended since the
main thread will be blocked, but to make things simple, we will not
touch asynchronous read here. The meaning of `bufOffset` and `bufSize`
is quite self-explanatory － they define the range of data to be copied
to `arr2` from the output buffer.

# Putting it all together

Here comes the complete program:

    <!DOCTYPE html>
    <html>
    <body>
    <script>
    // Create a WebCL context.
    var context;
    try {
      context = webcl.createContext(webcl.DEVICE_TYPE_GPU);
    } catch (e) {
      try {
        context = webcl.createContext(webcl.DEVICE_TYPE_CPU);
      } catch (e) {
        alert("WebCL is not supported by your device!");
        throw e;
      }
    }

    // Allocate the input and output buffer.
    var len = 1000000;
    var i;
    var arr1 = new Float32Array(len);
    for (i = 0; i < len; i++)
      arr1[i] = i;
    var buf1 = context.createBuffer(webcl.MEM_READ_ONLY, arr1.byteLength, arr1);
    var buf2 = context.createBuffer(webcl.MEM_WRITE_ONLY, arr1.byteLength);

    // Create and build the WebCL kernel program.
    var source =
    "__kernel void sqrtPar(__global float *buf1, __global float *buf2)\n" +
    "{\n" +
    "  size_t i = get_global_id(0);\n" +
    "  buf2[i] = sqrt(buf1[i]);\n" +
    "}";
    var program = context.createProgram(source);
    program.build();

    // Initialize the WebCL kernel object.
    var kernel = program.createKernel("sqrtPar");
    kernel.setArg(0, buf1);
    kernel.setArg(1, buf2);

    // Create a command queue.
    var queue = context.createCommandQueue();

    // Enqueue a kernel launch command to spawn 1,000,000 work items in one dimension.
    var workDim = 1;
    var globalWorkOffset = null;
    var globalWorkSize = [len];
    queue.enqueueNDRangeKernel(kernel, workDim, globalWorkOffset, globalWorkSize);

    // Enqueue a buffer read command to get the result.
    var arr2 = new Float32Array(len);
    var blockingRead = true;
    var bufOffset = 0;
    var bufSize = arr2.byteLength;
    queue.enqueueReadBuffer(buf2, blockingRead, bufOffset, bufSize, arr2);
    </script>
    </body>
    </html>

You can check the result in `arr2` in your favorite way, e.g. within a
JavaScript debugger.
