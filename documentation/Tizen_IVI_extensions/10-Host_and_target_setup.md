# Host and target setup

In this section, we outline how to prepare for developing Tizen Crosswalk extensions. The steps are:

*   Set up a *host* computer for Crosswalk Tizen development.
*   Create a *target* Tizen IVI virtual machine and install Crosswalk on it.

**Note:** These steps are only necessary if you are writing Crosswalk Tizen extensions: they are not required if you are writing Crosswalk Android extensions, or just using Crosswalk as a runtime for a web app.

# Host setup

First follow the instructions for your host platform:

*   [Windows](#documentation/getting_started/Windows_host_setup/Installation-for-Crosswalk-Tizen)
*   [Linux](#documentation/getting_started/Linux_host_setup/Installation-for-Crosswalk-Tizen)

Then follow the steps below to install the extra pieces needed for developing Crosswalk extensions.

## Install gbs

The recommended command-line build environment for Tizen is the [git build system (gbs)](https://source.tizen.org/documentation/reference/git-build-system). You should set this up for your host by following [these instructions](https://source.tizen.org/documentation/developer-guide/installing-development-tools).

### Set up gbs profile

To build projects, `gbs` needs a configuration file for the Tizen IVI version you are using. Example configuration files for different Tizen IVI versions are available on [this page](https://wiki.tizen.org/wiki/IVI/GBS_configuration_files_Tizen_IVI).

The Tizen IVI version used to write this tutorial was [this daily build](http://download.tizen.org/releases/daily/tizen/ivi/ivi-release/latest/images/ivi-release-mbr-i586/tizen_20140410.5_ivi-release-mbr-i586-sdb.raw.bz2). The gbs config file for working with this version looks like this:

    [general]
    profile = profile.3.0

    [obs.tizen]
    url = https://api.tizen.org

    [repo.tizen-3.0]
    url = http://download.tizen.org/releases/daily/tizen/ivi/ivi-release/tizen_20140410.5/repos/ivi/ia32/packages/

    [profile.3.0]
    obs = obs.tizen
    repos = repo.tizen-3.0

Adjust the URL to suit the version of Tizen IVI you are using. Then save this content to a file called `~/.gbs.conf`.

# Target setup

For the purposes of this tutorial, we'll use an Tizen IVI virtual machine, running under VMware.

Instructions for setting up this type of target are on the [Tizen target set up page](#documentation/getting_started/tizen_target_setup). Ensure that you follow all the steps, so that the virtual machine is set up with Crosswalk installed on it.

You are now ready to start developing the extension.
