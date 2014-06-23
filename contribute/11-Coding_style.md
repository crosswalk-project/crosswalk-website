# Crosswalk coding style

In general, we follow the Chromium coding style: http://www.chromium.org/developers/coding-style.

For quick reference, here are some key rules:

 * *Never* use tabs for indentation; use spaces instead.
 * *Don't* leave spaces at the end of a line.
 * *Don't* leave blank lines at the end of a file.
 * Be careful with license headers: if you copy or modify a file from somewhere else, ensure that the licence header remains intact.

## Coding Style for C++

For C++ code, we follow the [Google C++ Style Guide](http://google-styleguide.googlecode.com/svn/trunk/cppguide.xml).

Note that C++11 can be used in Crosswalk extensions for Tizen.

## Coding Style for Java

For Java code, we follow the Android Open Source style guide:

 * [Android Open Source style guide](http://source.android.com/source/code-style.html)
 * [Java style guide for Chromium](http://www.chromium.org/developers/coding-style/java)

## Coding Style for Python

For Python code, we follow PEP-8, with the following exceptions:

*   Use two spaces for indentation instead of four.
*   Use `MixedCase` for method names and function names instead of `lower_case_with_underscores`.

(These exceptions align Crosswalk Python code with Chromium Python code.)

For more details, see:

*   [PEP-8 guide](http://www.python.org/dev/peps/pep-0008/)
*   [Python style guide for Chromium](http://www.chromium.org/chromium-os/python-style-guidelines)

## Coding Style of HTML/JavaScript/CSS

For web resources, we follow [Chromium's "Web Development Style Guide"](http://www.chromium.org/developers/web-development-style-guide).
