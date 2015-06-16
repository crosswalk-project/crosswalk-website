# Linux host setup

These instructions have been tested on Fedora Linux 20, 64 bit. If you are using a different platform, you may need to modify them to suit your environment (e.g. use `apt-get` instead of `yum` if using Ubuntu, change package names where they differ from Fedora's package names).

In this tutorial, you're going to use an emulated Tizen IVI image, running under VMware. To be able to create this image and access it, you need to install a few packages on the host machine:

1.  **bash:** the script for generating Tizen packages runs under a bash shell. Usually, bash is installed by default, but just in case it's not:

        sudo yum install bash

2.  Packages containing utilities:

    <ul>
    <li><strong>openssh:</strong> this is so you can use the <code>ssh</code> command to push files to, and log in to, the virtual machine./li>
    <li><strong>openssl:</strong> this provides the <code>openssl</code> command used to create a key for signing your Tizen packages.</li>
    <li><strong>bzip2:</strong> to unpack the Tizen IVI disk image.</li>
    <li><strong>qemu-img:</strong> this is used to convert a Tizen IVI disk image into a format suitable for use with VMware.</li>
    </ul>

    You can usually install all of these with your package manager. For example, on Fedora

        sudo yum install openssh openssl bzip2 qemu-img

3.  **Kernel headers:** these are required so that VMware can compile the Linux kernel modules which enable it to run:

        sudo yum install kernel-headers

4.  **VMware Player** or **VMware Workstation**, to create and run the virtual machine. The free version of Player can be downloaded from [the VMware website](https://my.vmware.com/web/vmware/free). However, if you are using Player for commercial purposes, you will [need a licence](http://store.vmware.com/buyplayerplus).

    If you need help with installing VMware products, see [this page on the VMware website](http://kb.vmware.com/selfservice/microsites/search.do?language=en_US&cmd=displayKC&externalId=2053973).

Now the host is setup, you can prepare your targets.
