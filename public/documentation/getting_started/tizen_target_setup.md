# Tizen target setup

To run Crosswalk applications on Tizen, you need to install the Crosswalk runtime on each target. (Unlike Crosswalk for Android, it is currently not possible to bundle the Crosswalk runtime with the application for deployment to Tizen.)

You can use either a physical Tizen device or an emulated one (virtual machine) as a target. The instructions below cover both, but mainly concentrate on using a Tizen IVI virtual machine.

Crosswalk is available for Tizen version 2.1 or higher.

Tizen extensions for Crosswalk are also available, which enable you to make use of [Tizen APIs](https://developer.tizen.org/documentation/dev-guide/2.2.1?redirect=https%3A//developer.tizen.org/dev-guide/2.2.1/org.tizen.web.appprogramming/html/api_reference/api_reference.htm) in applications running in Crosswalk on Tizen. We don't need them for this tutorial, so we won't cover them here.

In the instructions below, `<path to Tizen SDK>` refers to the path to the root directory of your Tizen SDK installation.

## Tizen device

At the time of writing (April 2014), there are no off-the-shelf Tizen devices on the market.

However, if you acquire one of the supported hardware platforms for Tizen IVI, you can [install Tizen IVI on it](https://wiki.tizen.org/wiki/IVI/IVI_Platforms) and use it for testing. Once you've set it up, follow the [Install Crosswalk](#Install-Crosswalk) instructions below to install Crosswalk on the device.

## Tizen virtual machine

There are several ways to run Tizen IVI for development purposes:

1.  [Run a Tizen IVI image under VMWare](https://wiki.tizen.org/wiki/IVI/IVI_3.0_VMware).
2.  [Use a physical device and install Tizen IVI on it](https://wiki.tizen.org/wiki/IVI/IVI_Platforms).
3.  [Run a Tizen IVI image from a hard drive (e.g. USB stick) on Intel-based hardware](https://wiki.tizen.org/wiki/IVI/IVI_Installation).
4.  [Run a Tizen IVI image using the Tizen SDK Emulator Manager](https://wiki.tizen.org/wiki/Tizen_IVI_SDK).

The preferred approach, covered in the sections below, is to set up a Tizen IVI image under VMware.

**Note:** If you use the Tizen SDK Emulator manager or a physical device, some of the instructions below may not be applicable, as you will be using `sdb` to copy files to and run a shell on the target, rather than `ssh`.

### Set up a Tizen IVI virtual machine with VMware

After you have set up your host machine with the VMware Player or Workstation ([Windows](/documentation/getting_started/windows_host_setup.html#Installation-for-Crosswalk-Tizen), [Linux](/documentation/getting_started/linux_host_setup.html#Installation-for-Crosswalk-Tizen)), you can create a virtual Tizen IVI machine.

To do this, follow [these instructions on the Tizen wiki](https://wiki.tizen.org/wiki/IVI/IVI_3.0_VMware).

Here's a summary of the steps for Fedora 20, using VMware Workstation and the `tizen_20140410.5_ivi-release-mbr-i586-sdb.raw.bz2` daily build (available from the [daily builds page](http://download.tizen.org/releases/daily/tizen/ivi/ivi-release/latest/images/ivi-release-mbr-i586/)). The steps for Windows are similar, though you will need to use [install git SCM](/documentation/getting_started/windows_host_setup.html) to get the `curl` and `bunzip2` commands.

1.  Download, unpack and convert the Tizen IVI image:

        > curl -O http://download.tizen.org/releases/daily/tizen/ivi/ivi-release/latest/images/ivi-release-mbr-i586/tizen_20140410.5_ivi-release-mbr-i586-sdb.raw.bz2

        > bunzip2 tizen_*_ivi-release-mbr-i586-sdb.raw.bz2

        > qemu-img convert -f raw -O vmdk \
            tizen_20140410.5_ivi-release-mbr-i586-sdb.raw \
            tizen-ivi-pre-3.0.vmdk

2.  Start VMware Workstation.

3.  Select *File* &gt; *New Virtual Machine*.

4.  Select *Custom (advanced)*, then *Next*.

5.  Accept the defaults for *Virtual Machine Hardware Compatibility*, then *Next*.

6.  Select **I will install the operating system later**, then *Next*.

7.  For the *Guest operating system*, check **Linux** then select **Fedora** from the drop-down. Select *Next*.

8.  Set the *Name* to **Tizen-IVI**. Either accept the default location or choose a new one. I used `~/.vmware/Tizen-IVI` as the location for my VM.

9.  Accept the defaults for *Processors* and *Memory* (select *Next* for both).

10. Select **Use bridged networking**. This makes the virtual machine try to get an IP address from the local network when booted. If you know what you're doing, feel free to choose a different type of networking.

11. Accept the defaults for *I/O Controller Types* by selecting *Next*.

12. For *Virtual Disk Type*, choose **IDE**, then select *Next*.

13. Under *Disk*, select **Use an existing virtual disk** then *Next*.

14. *Browse* to the `tizen-ivi-pre-3.0.vmdk` disk image you created earlier, *Open* it, then click *Finish*. If you're prompted to change the format, click *Keep existing format*.

15. Select *Next* then *Customize hardware*.

16. In the *Virtual Machine Settings*, ensure that the following are configured correctly:

    <ul>
    <li>Remove <em>New CD/DVD</em></li>
    <li>Remove <em>Printer</em></li>
    <li><em>Display</em> &gt; <em>Accelerate 3D graphics</em>: <strong>on</strong></li>
    </ul>

    You can remove items by selecting them then clicking the *-* button at the bottom of the screen.

17. Select *Finish* then *Close* to save your changes.

18. Close VMware Workstation, so the configuration for the VM is saved to disk. This is so we can make a manual adjustment to the configuration in the next step.

19. If you are using Intel integrated graphics, you need to force VMware to use 3D acceleration. Append a line to the `.vmx` file associated with this VM:

        echo 'mks.gl.allowBlacklistedDrivers = "TRUE"' >> ~/.vmware/Tizen-IVI/Tizen-IVI.vmx

20. If you are using VMware Player on a Linux host, you will also need to enable TF2-S3TC Texture Support in DRI. The problem is described [here](http://dri.freedesktop.org/wiki/S3TC). It can be turned on with local `.drirc` file. On Ubuntu one can install the `driconf` application and change that setting from the GUI.

21. Open VMware Workstation again, and start the configured VM from the front screen by right-clicking on its name (**Tizen-IVI**) and selecting *Play Virtual Machine*.

22. The first time the virtual machine boots, you will be prompted to download the VMware Tools for Linux. Don't install these for now.

23. Eventually, you should see the Tizen IVI image boot:

    <img src="/assets/tizen-ivi-vmware.png">

#### VMware tips/reminders

*   Ctrl+Alt releases input capturing in the VM, giving you back the mouse and keyboard.
*   My preference is to use bridged networking between the VM and the host, as this simplifies using ssh (for me at least).

## Install Crosswalk

Once you have a running Tizen IVI virtual machine ready as a target, you can install Crosswalk on it. The instructions below assume that the target machine can be reached from the host machine over the network (to create the examples, I used bridged networking on the VM).

1.  Get a root shell on the VM with:

        ssh root@<ip address>

    When prompted to accept the host key, do so.

    The default Tizen IVI password for root is **tizen**.

    The shell prompt should be `root:~>` if you logged in successfully.

2.  From the shell on the target, download the Crosswalk package:

        root:~> curl -O https://download.01.org/crosswalk/releases/crosswalk/tizen-ivi/canary/6.35.119.0/crosswalk-6.35.119.0-0.i686.rpm

    The URL above is for the rpm I tested most recently, but you can try a newer one if you wish. However, bear in mind that these files are canaries, and will not necessarily work or be stable; the version referenced above is known to work.

3.    Install it:

        root:~> rpm -ih crosswalk-6.35.119.0-0.i686.rpm

4.  Check the installation is working by switching to the `app` user. This user has the correct configuration to be able to run Crosswalk on the target:

        root:~> su - app

    Then test the status of the Crosswalk service:

        app:~> systemctl --user status xwalk.service
        xwalk.service - Crosswalk
        Loaded: loaded (/usr/lib/systemd/user/xwalk.service; static)
        Active: inactive (dead)

The [Run on Tizen](/documentation/getting_started/run_on_tizen.html) section explains how to run an application with Crosswalk.
