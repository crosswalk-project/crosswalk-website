# Tizen target setup

To run Crosswalk applications on Tizen, you need to install the Crosswalk runtime on each target. (Unlike Crosswalk for Android, it is currently not possible to bundle the Crosswalk runtime with the application for deployment to Tizen.)

You can use either a physical Tizen device or an emulated one (virtual machine) as a target. The instructions below cover both, but mainly concentrate on using a Tizen IVI virtual machine.

Crosswalk is available for Tizen version 2.1 or higher.

Tizen extensions for Crosswalk are also available, which enable you to make use of [Tizen APIs](https://developer.tizen.org/documentation/dev-guide/2.2.1?redirect=https%3A//developer.tizen.org/dev-guide/2.2.1/org.tizen.web.appprogramming/html/api_reference/api_reference.htm) in applications running in Crosswalk on Tizen. We don't need them for this tutorial, so we won't cover them here.

In the instructions below, `<path to Tizen SDK>` refers to the path to the root directory of your Tizen SDK installation.

## Tizen device

At the time of writing (April 2014), there are no off-the-shelf Tizen devices on the market.

However, if you acquire one of the supported hardware platforms for Tizen IVI, you can [install Tizen IVI on it](https://wiki.tizen.org/wiki/IVI/IVI_Platforms) and use it for testing. Once you've set it up, follow the [Install Crosswalk](#Install-Crosswalk) instructions below to install Crosswalk on the device.

## Tizen emulator

There are several ways to run Tizen IVI for development purposes:

1.  [Run a Tizen IVI image under VMWare](https://wiki.tizen.org/wiki/IVI/IVI_3.0_VMware).

2.  [Use a physical device and install Tizen IVI on it](https://wiki.tizen.org/wiki/IVI/IVI_Platforms).

3.  [Run a Tizen IVI image from a hard drive (e.g. USB stick) on Intel-based hardware](https://wiki.tizen.org/wiki/IVI/IVI_Installation).

4.  [Run a Tizen IVI image using the Tizen SDK Emulator Manager](https://wiki.tizen.org/wiki/Tizen_IVI_SDK).

The preferred approach, covered in the sections below, is to set up a Tizen IVI image under VMware.

**Note:** If you use the Tizen SDK Emulator manager or a physical device, some of the instructions below may not be applicable, as you will be using `sdb` to copy files to and run a shell on the target, rather than `ssh`.

### Set up a Tizen IVI virtual machine with VMware

After you have set up your host machine with the VMware player ([Windows](#documentation/getting_started/Windows_host_setup/Installation-for-Crosswalk-Tizen), [Linux](#documentation/getting_started/Linux_host_setup/Installation-for-Crosswalk-Tizen)), you can create a virtual Tizen IVI machine.

To do this, follow [these instructions on the Tizen wiki](https://wiki.tizen.org/wiki/IVI/IVI_3.0_VMware).

Here's a summary of the steps for Fedora 20, using the `tizen_20140410.5_ivi-release-mbr-i586-sdb.raw.bz2` daily build file (available from the [daily builds page](http://download.tizen.org/releases/daily/tizen/ivi/ivi-release/latest/images/ivi-release-mbr-i586/)). The steps for Windows are similar, though you will need to use [install git SCM](#documentation/getting_started/Windows_host_setup) to get access to the `curl` and `bunzip2` commands.

1.  Download, unpack and convert the Tizen IVI image:

    > curl -O http://download.tizen.org/releases/daily/tizen/ivi/ivi-release/latest/images/ivi-release-mbr-i586/tizen_20140410.5_ivi-release-mbr-i586-sdb.raw.bz2

    > bunzip2 tizen_*_ivi-release-mbr-i586-sdb.raw.bz2

    > qemu-img convert -f raw -O vmdk \
        tizen_20140410.5_ivi-release-mbr-i586-sdb.raw tizen-ivi-pre-3.0.vmdk

2.  Start the VMware player, either from the command line (`vmplayer`) or menu.

3.  Select *Create a New Virtual Machine*.

4.  Select **I will install the operating system later**, then *Next*.

5.  For the *Guest operating system*, check **Linux** then select **Fedora** from the drop-down. Select *Next*.

6.  Set the *Name* to **Tizen-IVI**. Either accept the default location or choose a new one. I used `~/.vmware/Tizen-IVI` as the location for my VM.

7.  In the *Disk Size* screen, set *Maximum disk size* to **1Gb** and check *Store virtual disk as a single file* (these settings don't matter too much, as we're going to delete this virtual disk later anyway).

8.  Select *Next* then *Customize hardware*.

9.  In the *Virtual Machine Settings*, ensure that the following are configured correctly:

    *   Remove *New CD/DVD*
    *   Remove *Printer*
    *   *Display* > *Accelerate 3D graphics*: **on**

    You can remove items by selecting them then clicking the *-* button at the bottom of the screen.

10. Now the VM is set up, configure it by right-clicking on it and selecting *Virtual Machine Settings*.

11. Remove the *Hard disk*.

12. Add a new hard disk by clicking on *+*, selecting *Hard disk*, and then *Next*.

13. Choose **IDE** for the *Hard Disk Type* and click *Next*.

14. Select **Use an existing virtual disk** then *Next*.

15. *Browse* to the `tizen-ivi-pre-3.0.vmdk` disk image you created earlier then click *Finish*. If you're prompted to change the format, click *Keep existing format*.

16. *Save* your changes and close VMware player.

17. If you are using Intel integrated graphics, you need to force VMware player to use 3D acceleration. Append a line to the `.vmx` file associated with this VM:

        echo 'mks.gl.allowBlacklistedDrivers = "TRUE"' >> ~/.vmware/Tizen-IVI/Tizen-IVI.vmx

18. OpenVMware player again, and start the configured VM from the front screen by right-clicking on its name (**Tizen-IVI**) and selecting *Play Virtual Machine*.

19. The first time the virtual machine boots, you will be prompted to download the VMware Tools for Linux. Accept the prompts and follow the instructions to download and install them.

20. Eventually, you should see the Tizen IVI image boot:

    <img src="assets/tizen-ivi-vmware.png">

#### VMware tips/reminders

*   Ctrl+Alt releases input capturing in the VM, giving you back the mouse and keyboard.
*   My preference is to use bridged networking between the VM and the host, as this simplifies using ssh (for me at least).

## Install Crosswalk

Once you have a running Tizen IVI virtual machine ready as a target, you can install Crosswalk on it. The instructions below assume that the target machine can be reached from the host machine over the network (to create the examples, I used bridged networking on the VM).

1.  Get a root shell on the VM with:

        ssh root@<ip address>

    When you prompted to accept the host key, do so.

    The default Tizen IVI password for root is **tizen**.

    The shell prompt should be `root:~>` if you logged in successfully.

2.  From the shell on the target, download the Crosswalk package:

        curl -O https://download.01.org/crosswalk/releases/crosswalk/tizen-ivi/canary/6.35.119.0/crosswalk-6.35.119.0-0.i686.rpm

    The URL above is for the rpm I tested most recently, but you can try a newer one if you wish. However, bear in mind that these files are canaries, and will not necessarily work or be stable; the version referenced above is known to work.

3.    Install it:

        rpm -ih crosswalk-6.35.119.0-0.i686.rpm

4.  Check the installation is working by switching to the `app` user. This user has the correct configuration to be able to run Crosswalk on the target:

        su - app

    Then test the status of the Crosswalk service:

        app:~> systemctl --user status xwalk.service
        xwalk.service - Crosswalk
        Loaded: loaded (/usr/lib/systemd/user/xwalk.service; static)
        Active: inactive (dead)

The [Run on Tizen](#documentation/getting_started/run_on_tizen) section explains how to run an application with Crosswalk.
