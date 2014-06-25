# Android extensions

This tutorial explains how to write Crosswalk Android extensions using Java.

The application you'll build in the tutorial demonstrates a very simple "echo" extension. This simply returns the string passed to it, prefixed with "You said: ". The extension is deliberately trivial so that the tutorial can focus on explaining the principles behind Crosswalk Android extensions.

This tutorial **doesn't** cover best practices for web development. For example, it avoids [grunt](http://gruntjs.com/) and [bower](http://bower.io/) and doesn't use any third party front-end libraries, for simplicity's sake. Instead, it focuses on helping you explore the pieces of a hybrid Crosswalk application and how they fit together.

You'll need to be familiar with Java development tools like [Ant](http://ant.apache.org/) and [Ivy](http://ant.apache.org/projects/ivy.html).

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

<p>The extension you'll write provides an echo service, which prefixes any string passed to it and returns it.</p>

<p>Note that a Crosswalk application can use multiple extensions if desired.</p>

</li>

<li>
<p><strong>An HTML5 web application</strong></p>

<p>This is a self-contained web application which "lives inside" the Android application, but uses Crosswalk as its runtime. It consists of standard assets like HTML files, JavaScript files, images, fonts etc.</p>

<p>The Crosswalk extension is invoked by code in the web application, via the JavaScript wrapper mentioned above. In the tutorial application, the response from the echo extension is rendered into a DOM element.</p>

</li>

</ol>

<p>The project you create will also contain a few files to assist with packaging the above components into an <code>.apk</code> package file you can install on the Android target.</p>

All of the source code for the tutorial is available as part of the [crosswalk-samples download](https://github.com/crosswalk-project/crosswalk-samples/releases), or on github at https://github.com/crosswalk-project/crosswalk-samples (inside the `extensions-android` directory).
