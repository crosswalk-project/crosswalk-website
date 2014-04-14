# Host and target setup

In this section, we set up the host computer for Crosswalk Tizen development and create a Tizen target (emulated Tizen IVI device).

**Note:** These steps are only necessary if you are writing Crosswalk Tizen extensions: they are not required if you are writing Crosswalk Android extensions, or just using Crosswalk as a runtime for a web app.

# Host setup

The host should first be set up for Crosswalk development by following these steps:

*   [Windows](#documentation/getting_started/Windows_host_setup/Installation-for-Crosswalk-Tizen)
*   [Linux](#documentation/getting_started/Linux_host_setup/Installation-for-Crosswalk-Tizen)

Once this is done, you will also need to install and configure the Tizen SDK, as explained below.

## Set up the Tizen SDK

These steps will enable you to compile Crosswalk extensions to run on Tizen:

<ol>

  <li>Download the Tizen SDK for your platform from <a href="https://developer.tizen.org/downloads/tizen-sdk" target="_blank">https://developer.tizen.org/downloads/tizen-sdk</a>.</li>

  <li>
    <p><a href="https://developer.tizen.org/downloads/sdk/installing-tizen-sdk">Follow the instructions</a> to install it.</p>
  </li>

  <li>
    <p>The Tizen SDK includes a set of command line tools for building and packaging Tizen native apps. You can use these tools to build an extension for Crosswalk. Make sure these tools are on your path, e.g. if using bash you can edit your `~/.bashrc` file:</p>

<pre>
export PATH=<path to Tizen SDK>/tools:$PATH
</pre>

    <p>Then, to make the setting take effect:</p>

    <pre>source ~/.bashrc</pre>
  </li>
</ol>

## Checkout Crosswalk source code

You will need a copy of the Crosswalk source, to enable you to compile against the Crosswalk headers.

Checkout the Crosswalk github repo on the host machine (the machine where you intend to compile your extension):

    git clone https://github.com/crosswalk-project/crosswalk ~/crosswalk-source/xwalk

It's important that you clone the code to a directory called `xwalk`, as this is the path the compiler will be looking on for headers/libraries.

## Create project directories

The project name is **simple**. Set up the directories for the project from a command line as follows:

    mkdir simple
    cd simple

    # directory for the HTML5 web app
    mkdir app

    # directory for the Crosswalk extension
    mkdir extension

Now the preparation is complete, you can start building the application and associated extension.

# Target setup

For the purposes of this tutorial, we'll use an emulated Tizen IVI device, running under VMware.

Instructions for setting up this type of target are on the [Tizen target set up page](#documentation/getting_started/tizen_target_setup).

Once you've got a working Tizen IVI virtual machine, you are ready to start developing the extension.
