# Android extensions

This tutorial explains how to write Crosswalk Android extensions using Java.

The application you'll build in the tutorial is a very simple audio file player, *xwalk-player*. It plays audio files from an Android device's storage (internal storage or SD card). Normally, device storage is not available to web applications. However, this tutorial explains how to write some Java to find and list audio files on device storage; then wrap the Java code with JavaScript, so those files can be displayed and played by a Crosswalk (HTML5) application.

This tutorial **doesn't** cover best practices for web development. For example, it avoids [grunt](http://gruntjs.com/) and [bower](http://bower.io/) and doesn't use any third party front-end libraries, for simplicity's sake. Instead, it focuses on helping you explore the pieces of a hybrid Crosswalk application and how they fit together.

You'll need to be familiar with Java development tools like [Ant](http://ant.apache.org/) and [Ivy](http://ant.apache.org/projects/ivy.html).

Familiarity with Android development will also help, as the extension makes use of Android APIs.

**By the end of the tutorial**, you will be able to develop your own Java extensions for Crosswalk applications on Android.

## Introduction to the tutorial

In this tutorial, you will build a Crosswalk Android application with a Java extension. This consists of two main pieces:

<ol>

<li>
<p><strong>A Crosswalk extension</strong></p>

<p>The extension consists of:</p>

<ul>
<li>Java source code: Standard Android/Java classes, packaged into a jar file.</li>
<li>JavaScript wrapper: A JavaScript file which exposes the Java code to an app running on Crosswalk.</li>
<li>Configuration: A JSON file to wire up the JavaScript wrapper with the Java classes.</li>
</ul>

<p>The extension you'll write provides an "audio filesystem". This generates an object representing metadata about audio files on an Android device.</p>

<p>Note that a Crosswalk application can use multiple extensions if desired.</p>

</li>

<li>
<p><strong>An HTML5 web application</strong></p>

<p>This is a self-contained web application which "lives inside" the Android application, but uses Crosswalk as its runtime. It consists of standard assets like HTML files, JavaScript files, images, fonts etc.</p>

<p>The Crosswalk extension is invoked by code in the web application, via the JavaScript wrapper mentioned above. In the tutorial application, the list of files returned by the extension is used to create a simple audio player HTML interface.</p>

</li>

</ol>

<p>The project you create will also contain a few files to assist with packaging the above components into an <code>.apk</code> package file you can install on the Android target.</p>

All of the source code for the tutorial is available as part of the [crosswalk-samples download](https://github.com/crosswalk-project/crosswalk-samples/releases), or on github at https://github.com/crosswalk-project/crosswalk-samples (inside the `extensions-android` directory).
