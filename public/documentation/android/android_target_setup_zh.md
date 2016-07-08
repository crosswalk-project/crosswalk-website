# 安卓设备配置

Crosswalk应用将会运行在实体机或者安卓虚拟机上。

安卓4.0以及以上版本都可以正常运行应用。

## 安卓设备

为了在安卓设备上运行Crosswalk应用，您需要连接您的电脑主机和设备。最简单的方式是用USB线连接它们。

在命令行下使用下面的命令，测试安卓设备是否连接上主机：

```cmdline
> adb devices
List of devices attached
Medfield532DC30E	device
```

如果挂在设备列表为空，您可以在开发者选项中打开USB调试(*设置* &gt; *开发者选项* &gt; 打开 *USB调试*).

<h3 id="Fixing-device-access-issues-on-Linux">解决Linux平台下获取设备的权限问题</h3>

在某些情况下，比如在Linux平台下，非root用户运行`adb`，可能会造成检测不到您的设备：

```cmdline
> adb devices
List of devices attached
```

或者没有足够的权限：

```cmdline
> adb devices
List of devices attached
????????????	no permissions
```

(后者似乎在Android 4.0.*版本上会发生)

针对这些情况，可以使用root用户运行`adb`：

```cmdline
# 杀掉已有的adb实例
> sudo <path to Android SDK>/platform-tools/adb kill-server

# 以root身份运行adb server
> sudo <path to Android SDK>/platform-tools/adb start-server

# 查看设备(这时候非root用户也可以运行这个命令)
> adb devices
List of devices attached
HT23KW103989	device
```

## 安卓虚拟机

如果需要在您没有的安卓平台上测试应用，最好的方法就是使用虚拟机。您可以通过Android SDK安装。

1. 通过在Linux下运行`android`命令，或者在Windows下运行`SDK Manager.exe`，启动Android SDK。

2. 在SDK Manager窗口，检查如下列表中的内容：

   ```
   [ ] Android 4.3 (API 18)
       [x] Intel x86 Atom System Image
   ```
   
   如果您想要测试其他版本的Android API，只需要安装相应x86系统的镜像。

3. <strong>仅适用于Windows</strong>，使用SDK Manager下载HAXM：

   ```
   [ ] Extras
       [x] Intel x86 Emulator Accelerator (HAXM)
   ```
   
   Windows平台上运行虚拟的x86设备，会获得更好的图形性能。

   注意SDK Manager仅下载HAXM，并没有安装它，因此您需要找到并安装它。您需要的文件名称是`IntelHaxm.exe`。

4. 在下载相应的包之后，通过运行AVD Manager来配置虚拟机的系统：

```cmdline
> android avd
```

5. 创建名为<strong>Tablet</strong>的系统并选择下面的选项：

  <ul>
    <li><em>Target</em>: <strong>Android 4.3</strong></li>
    <li><em>CPU/ABI</em>: <strong>Intel Atom (x86)</strong> (如果您只下载了x86的镜像，这一步会自动选择)</li>
    <li><strong>Use Host GPU</strong> (打钩这个选项)</li>
  </ul>

  配置如下：

  <img src='/assets/emulator.png' style="display:block;margin:0 auto">

6. 在命令行模式下启动新的模拟器：

   ```cmdline
   > emulator -avd Tablet
   ```

   您可以像对待硬件设备一样，使用adb连接任何正在运行的虚拟机：

   ```cmdline
   $ adb devices
   List of devices attached
   emulator-5554   device
   ```

现在，虚拟机已经配置完毕，并且可以作为部署平台。
