# Host and target setup

Follow the [system setup page](/documentation/android/system_setup.html) to set up the Crosswalk development environment.

You will also need to [set up an Android target](/documentation/android/android_target_setup.html). For testing, you can either use [your own Android device](/documentation/android/android_target_setup.html#Android-Device) or [an emulated device](/documentation/android/android_target_setup.html#android-emulator). I used:

* x86 ZTE Geek phone with Android 4.2.2
* ARM HTC OneX with Android 4.0.4

<h2 id="Project-outline">Project outline</h2>

For the purposes of the tutorial, the extension and the application are developed within a single top-level project directory. Here is an outline of the structure:

    # top-level project directory
    xwalk-echo-project/

      # directory for the extension
      xwalk-echo-extension-src/
        build/
          ...temporary build artefacts...
        java/
          ...Java source files for the extension...
        js/
          ...JavaScript for the extension...
        lib/
          ...third party jar files (installed via Ivy)...
        tools/
          ...jar files to assist with the build...
        xwalk-echo-extension/
          ...temporary output directory for the extension...
        build.xml                     # Ant build file
        ivy.xml                       # Ivy configuration for Ant
        xwalk-echo-extension.json     # extension configuration

      # directory for the web application
      xwalk-echo-app/
        assets/
          ...images, stylesheets etc...
        js/
          ...JavaScript files...
        icon.png                      # application icon
        index.html                    # main HTML file
        manifest.json                 # Crosswalk manifest

Although you'll be developing the extension alongside the application, bear in mind that a Crosswalk extension can be reused between projects if you wish.

### Create the project directories

For the purposes of the tutorial, it's assumed that you have a directory for your own projects in `~/<my projects directory>`. Create the required directories for *this* tutorial project inside your projects directory as follows:

    cd ~/<my projects directory>

    # set up top-level directory for the xwalk-player project
    mkdir xwalk-echo-project
