<style>
.simple-table {
    table-layout:fixed;
    padding: 0px;
}

.simple-table td {
    height: 5px !important;
}

</style>

# 远程调试

基于Crosswalk的web应用，是可以用[Chrome dev tools](https://developer.chrome.com/devtools/index)进行远程调试的。

如果想取得最佳的调试效果，请使用和您现在的Crosswalk匹配的(或者更新的)Chrome版本。可以使用下面的下拉框查看匹配关系。

<select>
  <option>Crosswalk 18, 使用Chrome >= 47</option>
  <option>Crosswalk 17, 使用Chrome >= 46</option>
  <option>Crosswalk 16, 使用Chrome >= 45</option>
  <option>Crosswalk 15, 使用Chrome >= 44</option>
  <option>Crosswalk 14, 使用Chrome >= 43</option>
  <option>Crosswalk 13, 使用Chrome >= 42</option>
  <option>Crosswalk 12, 使用Chrome >= 41</option>
  <option>...</option>
  <option>Crosswalk &nbsp;x, 使用Chrome >= (x+29)</option>
</select>
在安卓设备上，[Android SDK](/documentation/android/system_setup_zh.html#Android)中的[`adb`](http://developer.android.com/tools/help/adb.html)用来建立它和主机的联系。同时，您还需要设置安卓并安装Crosswalk应用。详情参见[这些命令](/documentation/android/android_target_setup_zh.html)。

调试开关打开与否是在编译Crosswalk应用的时候决定的。

## <a class="doc-anchor" id="Enable-debugging"></a>打开调试选项

* **crosswalk-app build**

  `crosswalk-app build`接受"release"和"debug"两个选项。debug是默认选项，因此创建debug版本时不需要做任何事，但是使用“debug”选项会提示你在正式发布您的应用之前改变编译版本。

        > crosswalk-app build [release|debug] [<dir>] 

* **cordova build**

  `cordova build`默认也是创建debug版本。它接受`--debug`和`--release`两个选项。
  
        > cordova build android [--release|--debug]

* **应用中嵌入Crosswalk**

  如果您使用Crosswalk嵌入模式下的api，将Crosswalk嵌入到应用中（例如在本地应用中使用Crosswalk webview)，您可以使用[embedding API](/documentation/android/embedding_crosswalk_zh.html)来打开debug选项。

  * 修改应用中的main activity来打开调试选项。例如：

        XWalkPreferences.setValue(XWalkPreferences.REMOTE_DEBUGGING, true);

  * 按照正常方式编译应用（比如Ant或者ADT）。

  详情参见[Crosswalk嵌入模式教程](/documentation/android/embedding_crosswalk_zh.html#Debugging)。


## 安装与调试

* 安装应用：
      > adb install com.abc.myapp

* 点击应用图标运行[更多](/documentation/android/run_on_android_zh.html)

* 在主机上（电脑上），打开Chrome浏览器并在地址栏输入 "chrome://inspect"。将会显示已经挂载的所有设备，例如：

  <img src="/assets/crosswalk-debug-in-chrome.png" title="Debugging a Crosswalk application in Chrome" style="display:block;margin:0 auto;">

* 点击"inspect"打开应用，就可以用Chrome dev tools进行调试。

  <img src="/assets/crosswalk-debug-in-chrome2.png" style="display:block;margin:0 auto;">
  
## 问题解决

* **adb 无法连接到设备**

  偶尔您可能会发现adb无法连接到安卓设备，远程调试无法工作。您可以尝试插拔USB线 (如果您使用的是USB线连接)，然后重新插入，这种办法可能会解决问题；或者你可以尝试[以root身份运行`adb`](/documentation/android/android_target_setup_zh.html#Fixing-device-access-issues-on-Linux)。

* **应用无法在chrome://inspect页面显示**

  如果应用在chrome的inpectation页面无法显示，那么用`adb`检查应用是否打开调试选项

    ```
    host$ adb shell
    shell@android$ cat /proc/net/unix |grep devtools_remote
    00000000: 00000002 00000000 00010000 0001 01 1102698 @org.crosswalkproject.app_devtools_remote
    00000000: 00000002 00000000 00010000 0001 01 1092981 @org.xwalk.core.xwview.shell_devtools_remote
    ```

  如果没有看到任何以`_devtools_remote`结尾的条目，那么可能是应用没有打开调试选项。遵循上面的步骤，打开debug选项重新编译应用或者在运行的时候打开调试选项。

## 更多信息

更多使用chrome dev tools信息，参考[页面](https://developer.chrome.com/devtools/index)。
