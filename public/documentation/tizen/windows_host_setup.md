# Windows host setup

These instructions have been tested on Windows 7 Enterprise, 64 bit.

In this tutorial, you're going to use an emulated Tizen IVI image, running under VMware. To be able to create this image and access it, you need to install a few packages on the host machine:

1. <a id="Utilities"></a>**Utilities**, available as part of the [git SCM tools for Windows](http://git-scm.com/download/win).

    <ul>
    <li><code>bash</code>: this is required to run the script for generating Tizen packages.</li>
    <li><code>ssh</code>: this command is used to push files to, and log in to, the virtual machine.</li>
    <li><code>openssl</code>: the command used to create a key for signing your Tizen packages.</li>
    <li><code>bunzip2</code>: the command to unpack the Tizen IVI disk image.</li>
    </ul>

    Download a git SCM tools `.exe` for your architecture and install it.

    Once installed, you should have a *Git Bash* entry in the Windows menu, which will bring up a `bash` shell.

2.  **QEMU:** you need the <code>qemu-img</code> tool to convert a Tizen IVI disk image into a format suitable for use with VMware.

    A version of QEMU for Windows is available from http://qemu.weilnetz.de/. Use this at your own risk.

3.  **VMware Player** or **VMware Workstation**, to create and run the virtual machine. The free version of Player can be downloaded from [the VMware website](https://my.vmware.com/web/vmware/free). However, if you are using Player for commercial purposes, you will [need a licence](http://store.vmware.com/buyplayerplus).

    If you need help with installing VMware products, see [this page on the VMware website](http://kb.vmware.com/selfservice/microsites/search.do?language=en_US&cmd=displayKC&externalId=2053973).

Now the host is setup, you can prepare your targets.
