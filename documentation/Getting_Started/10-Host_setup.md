# Host setup

The **host** is the computer where you are writing your code. It is typically not the same computer as the one where Crosswalk is running (the **target**).

You will need to install two groups of tools:

1.  <a href="#documentation/getting_started/host_setup/Installing-the-dev-tools">Development tools</a> for your chosen platform.
2.  <a href="#documentation/getting_started/host_setup/Installing-target-specific-tools">Target-specific tools</a> for each target platform you want to deploy to.</p>

## Deciding what you need on the host

What you need to set up on the host depends on two things:

<ol>

  <li>
    <p><em>Host platform</em></p>

    <p>This is the operating system you're using for development. Your choice of host platform depends on the targets you wish to deploy to:</p>

    <ul>

      <li>

      <em>For Crosswalk Android</em>, Linux and Windows are officially supported as host platforms. However, it may be possible to build Crosswalk apps on other platforms which support the Android SDK and Python (e.g. Mac).</li>

      <li><p><em>For Crosswalk Tizen</em>, you need a platform which can run the <a href="https://developer.tizen.org/documentation/articles/smart-development-bridge" target="_blank"><code>sdb</code></a> tool. <code>sdb</code> is officially supported on platforms where the <a href="https://developer.tizen.org/downloads/tizen-sdk" target="_blank">Tizen SDK</a> is available, i.e. Ubuntu Linux, Windows, and Mac OS X.</p>

      <p>However, <code>sdb</code> is the only tool you really need from the Tizen SDK for Crosswalk development, and is relatively simple to build from source. So other platforms may be used with a little effort.</p>

      <p>Note that (at the moment) you have to manually install and run Crosswalk applications on Tizen, as there are no stable packaging tools.</p>
      </li>

    </ul>
  </li>

  <li>

  <p><em>Target platforms</em></p>

  <p>These are the devices or emulators you want to develop for and deploy to. Android and Tizen, on both ARM and x86 architectures, are the supported targets. These can be either real devices (e.g. phones, tablets) or emulated ones.</p>

  </li>

</ol>

## Installing the development tools

The instructions below explain how to set up the required dev tools on the following host platforms:

*   Ubuntu Linux 12.10, 64 bit (if you are using a different flavour of Linux, the package names may vary slightly); Ubuntu is used as this is the only Linux flavour officially supported by the Tizen SDK.
*   Windows Enterprise 7, 64 bit.

The steps for installing the dev tools are:

1.  [Install utilities](#documentation/getting_started/host_setup/Installing-utilities) (curl, unzip, tar, gzip; used to install other tools)
2.  [Install Python](#documentation/getting_started/host_setup/Installing-Python).
3.  [Install the Oracle Java Development Kit (JDK)](#documentation/getting_started/host_setup/Installing-the-Oracle-JDK). **Note: It is important that you use the Oracle JDK, rather than the OpenJDK, as Ant may not work correctly with the latter.**
4.  [Install Ant](#documentation/getting_started/host_setup/Installing-Ant).
5.  [Configure your environment](#documentation/getting_started/host_setup/Configuring-your-environment).
6.  [Verify your environment](#documentation/getting_started/host_setup/Verifying-your-environment).

On both host platforms, a bash shell is used as the main installation environment. This is readily available on Linux (look for *Terminal* in the list of applications); or can be <a href="#documentation/getting_started/host_setup/Installing-utilities">installed easily on Windows</a>.

### bash and cmd

Note: Some of the tools do not behave well when invoked from a bash shell on Windows. In these cases, a Windows `cmd` console is used instead.

To start a Windows console, do the following:

1.  Open the Windows menu.
2.  In the text entry at the bottom of the menu ("Search programs and files"), type the letters `cmd`.
3.  Press *Return*.

This will open a console window:

<img src="assets/windows-console.png">

Throughout this tutorial, when a `cmd` console is in use, it is denoted with a `>` prompt. A bash shell is denoted with a `$` prompt. In cases where either bash or cmd will do, no prompt is shown.

### Installing utilities

#### Linux

The utilities can be installed from a bash shell with this command on Ubuntu:

    $ sudo apt-get install curl unzip tar

#### Windows

Bash is provided as part of the git download for Windows. In addition to bash, git for Windows also provides curl, tar, unzip, and gzip, which are used in later steps.

Download and install git for Windows from the git-scm website:
<a href='http://git-scm.com/download/win' target='_blank'>http://git-scm.com/download/win</a>

While installing git, select the following options:

<img src='assets/integrate.png'>

Then select *Run Git from the Windows command prompt*:

<img src='assets/path.png'>

You can now open a Git Bash session by going to your Start Menu and typing **Git Bash** to find the command:

<img src='assets/launch.png'>

Select *Git Bash* to open a console window running the bash shell.

### Installing Python

#### Linux

This can be installed via your package manager. For example, on Ubuntu:

    $ sudo apt-get install python

This installs Python globally, so any user can run it.

#### Windows

Get Python from http://www.python.org/getit, choosing an "MSI installer" for your architecture (32 or 64 bit).

When the installer starts, choose *Install for all users* and set **C:\Python** as the installation location. You will need to manually add the Python directory to your path for it to be available in the bash shell (see [Configuring your environment](#Configuring-your-environment)).

### Installing the Oracle JDK

The Oracle JDK has to be downloaded manually (you must accept a licence agreement):

1.  Go to the Oracle Java 7 SE JDK download page in a browser:

    http://www.oracle.com/technetwork/java/javase/downloads/jdk7-downloads-1880260.html

2.  Accept the licence agreement.

3.  In the section headed *Java SE Development Kit*, choose the appropriate archive file for your platform. Save it to your home directory.

4.  Once downloaded:

    <ul>

    <li>On Linux, unpack the tarball and symlink it:

    ```
    $ cd ~
    $ tar zxvf <jdk file>.tar.gz
    $ ln -s ~/<jdk directory> ~/jdk7
    ```
    </li>

    <li>On Windows, run the Java <code>.exe</code> installer, and set <code>C:\jdk7</code> as the installation directory.</li>

    </ul>

### Installing Ant

The instructions are the same for Linux and Windows. From a bash shell:

    $ cd ~
    $ curl http://www.apache.org/dist/ant/binaries/apache-ant-1.9.3-bin.zip \
        -o ant.zip
    $ unzip ant.zip

### Configuring your environment

The next step is to set up your environment so that binaries and scripts which were installed manually (ant, JDK, Python on Windows) are on your `PATH`. The easiest way to do this is to edit your `~/.bashrc` file, adding these lines at the end (create the file if it doesn't exist):

    # note that we prepend the new paths to the
    # PATH variable to ensure that we use scripts and binaries
    # from our newly-installed packages
    export PATH=~/apache-ant-1.9.3/bin:$PATH

    # on Windows, you need to add the Python install directory
    # and the JDK bin directory
    export PATH=/c/jdk7/bin:$PATH
    export PATH=/c/Python/:$PATH

    # on Linux, you just need the JDK bin directory as Python is
    # installed globally
    export PATH=~/jdk7/bin:$PATH

You should also set the `JAVA_HOME` environment variable to the path of the JDK installation (ant uses this to choose the java binary to invoke) by adding another line to `.bashrc`:

    # Windows
    export JAVA_HOME=/c/jdk7

    # OR

    # Linux
    export JAVA_HOME=~/jdk7/

To activate these changes in your current bash shell:

    $ source ~/.bashrc

When you start a bash shell in future, your `.bashrc` file will automatically be used to set `PATH` and `JAVA_HOME`.

### Verifying your environment

Check that you have installed the tools properly by trying these commands from a bash shell:

    $ java -version
    java version "1.7.0_45"
    Java(TM) SE Runtime Environment (build 1.7.0_45-b18)
    Java HotSpot(TM) 64-Bit Server VM (build 24.45-b08, mixed mode)

    $ ant -version
    Apache Ant(TM) version 1.9.3 compiled on December 23 2013

    $ python --version
    Python 2.7.6

### Avoiding GNU gcj on Linux

If you are on Linux and your version of java returns something like this:

    $ java -version
    java version "1.5.0"
    gij (GNU libgcj) version 4.6.3

then it may be that gcj is still set as the default `java` for your host.

To fix this, you can append an alias to the bottom of your `~/.bashrc` file to ensure that the correct java binary is being used:

    alias java="~/jdk7/bin/java"

### ant unable to locate tools.jar

When you run ant, if you see something like this:

    Unable to locate tools.jar.
    Expected to find it in c:\Program Files (x86)\Java\jre7\lib\tools.jar

it may mean that ant is using a Java Runtime Environment (JRE) version of Java, rather than the full JDK. This can be resolved by setting an alias for java in your `.bashrc` file, pointing at your JDK install:

    alias java="/c/jdk7/bin/java"

## Installing target-specific tools

These are optional tools, installed on the host to support the targets you intend to deploy to:

*   **Android targets**

    To deploy to Android targets, you need:

    *   *Crosswalk for Android:* This includes utilities for generating Crosswalk Android packages for installation on Android targets.

    *   *Android SDK:* This includes generic tools for creating Android packages, as well as the `adb` tool, for installing those packages on Android targets.

*   **Tizen targets**

    To deploy to Tizen targets, you need:

    *   *Tizen SDK:* This includes the [`sdb`](https://developer.tizen.org/documentation/articles/smart-development-bridge) tool for communicating with Tizen targets, which enables you to install the Crosswalk runtime on a target and run Tizen applications from the shell.

### Optional: installing tools for Android targets

These tools are only required if you intend to deploy Crosswalk applications to Android targets.

#### Installing the Android SDK

1.  Download the Android SDK from <a href='http://developer.android.com/sdk/index.html' target='_blank'>http://developer.android.com/sdk/index.html</a>
2.  Extract the file into `~/android-sdk`.
3.  Run the *SDK Manager*. You can do this from a bash shell as follows:

        # on Windows
        $ cd ~/android-sdk
        $ "SDK Manager.exe"

        # on Linux
        $ cd ~/android-sdk/tools
        $ ./android

4.  In the SDK Manager window, select the following items from the list:

        [ ] Tools
          [x] Android SDK Platform-tools 19.0.1
          [x] Android SDK Build tools 18.0.1
        [ ] Android 4.3 (API 18)
          [x] SDK Platform

    Note that if you are using devices with versions of Android later than 4.3, you should install the platform tools, build tools and SDK platform for those versions too.

5.  Add the SDK directories to your `PATH` by appending these lines to `~/.bashrc`:

        # Windows
        export PATH=~/android-sdk:$PATH
        export PATH=~/android-sdk/sdk/tools:$PATH
        export PATH=~/android-sdk/sdk/platform-tools:$PATH

        # Linux
        export PATH=~/android-sdk:$PATH
        export PATH=~/android-sdk/tools:$PATH
        export PATH=~/android-sdk/platform-tools:$PATH

    Read in `~/.bashrc` for your changes to take in the current shell:

        $ source ~/.bashrc

6.  Test the installation:

        $ adb help
        Android Debug Bridge version 1.0.31
        ...

#### Downloading the Crosswalk Android app template

The Crosswalk Android distribution contains an application template which can be used as a wrapper for an HTML5 application. It also includes a script which will convert a wrapped HTML5 application into an installable Android `apk` file.

To get Crosswalk Android for x86, run these commands in a bash shell:

    $ cd ~

    # the `-k` option prevents curl from failing due to SSL
    # certificate verification errors.
    $ curl -k https://download.01.org/crosswalk/releases/crosswalk/android/stable/${XWALK-STABLE-ANDROID-X86}/x86/crosswalk-${XWALK-STABLE-ANDROID-X86}-x86.zip -o crosswalk-${XWALK-STABLE-ANDROID-X86}-x86.zip

    # unzip the Crosswalk Android archive
    $ unzip crosswalk-${XWALK-STABLE-ANDROID-X86}-x86.zip

    # unpack the xwalk app template archive inside it
    $ tar zxf crosswalk-${XWALK-STABLE-ANDROID-X86}-x86/xwalk_app_template.tar.gz \
      -C crosswalk-${XWALK-STABLE-ANDROID-X86}-x86

You should now have a `~/crosswalk-${XWALK-STABLE-ANDROID-X86}-x86` directory with an `xwalk_app_template` directory inside it.

### Optional: installing tools for Tizen targets

If you intend to deploy Crosswalk applications to Tizen targets, you only need to install the Tizen SDK: there are no Crosswalk packaging tools for Tizen yet. Though, as a consequence, a Crosswalk runtime for Tizen must be manually installed on each target instead (see [Tizen target setup](#documentation/getting_started/tizen_target_setup)).

<ol>

  <li>Download the Tizen SDK for your platform from <a href="https://developer.tizen.org/downloads/tizen-sdk" target="_blank">https://developer.tizen.org/downloads/tizen-sdk</a>. <strong>Note that if you intend to deploy applications to an emulated Tizen device, you must use version 2.2.0 of the Tizen SDK: see <a href="https://developer.tizen.org/downloads/sdk/advanced-configuration">the advanced configuration instructions</a> for details of how to change versions.</strong></li>

  <li>
    <p><a href="https://developer.tizen.org/downloads/sdk/installing-tizen-sdk">Follow the instructions</a> to install it. If you have a physical Tizen device, you don't need to select any of the optional components.</p>

    <p>However, if you intend to use an emulated Tizen image, you may want to install those components now. See <a href="#documentation/getting_started/tizen_target_setup">Tizen target setup</a> for details.</p>
  </li>

  <li>
    <p>Once installed, you need to ensure that the <code>sdb</code> tool is on your <code>PATH</code>.</p>

    <p>On Linux, using a bash shell:</p>

    <ul>

      <li>Add this line to <code>~/.bashrc</code>:

      ```
      # assuming the Tizen SDK is installed in ~/tizen-sdk
      export PATH=~/tizen-sdk/tools/:$PATH
      ```

      <li>Read <code>~/.bashrc</code> to update your <code>PATH</code>:

      ```
      source ~/.bashrc
      ```

      </li>

      <li>Test <code>sdb</code>:

      ```
      $ sdb
      Smart Development Bridge version 2.0.2
      ...
      ```

      </li>

    </ul>

    <p>On Windows, from a <code>cmd</code> prompt:</p>

    <ul>

      <li>Add the <code>tools</code> directory (containing <code>sdb</code>) to your Windows path using this command:

      ```
      > setx path "%path%;c:\tizen-sdk\tools"
      ```
      </li>


      <li>Test <code>sdb</code>:

      ```
      > sdb
      Smart Development Bridge version 2.0.2
      ...
      ```
      </li>

    </ul>

  </li>

</ol>
