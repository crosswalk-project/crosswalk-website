# 权限

`xwalk_permissions`字段是Crosswalk对于W3C manifest的扩展。在打包安卓版Crosswalk应用时，它被用来在`AndroidManifest.xml`文件中设定权限。

这是必须的，因为Android应用不可以在运行时请求permission：所有应用需要的permission必须在安装的时候被授予。在其他章节中（(Crosswalk Tizen，嵌入式API），这个字段是被忽略的。

**注意：** 如果你正在[通过个嵌入式API从manifest中加载应用](/documentation/manifest/using_the_manifest.html#Load-an-application-into-an-embedded-Crosswalk)，那么你必须在`AndroidManifest.xml`中为Crosswalk应用手动添加permission。请参见 [下文](#Permissions-required-by-API)，其中是关于Crosswalk的[web APIs](/documentation/apis/web_apis.html)需要的permission的指导。

<h2 id="Effect-on-Android-packaging">对Android打包的影响</h2>

`Crosswalk App Tools`将`manifest.json`中的`permissions`或者`xwalk_permissions`转换成`AndroidManifest.xml`中的`<android:uses-permission>`元素。

例如，给定下列的manifest：

    {
      "name": "simple",
      "start_url": "index.html",
      "xwalk_permissions": [
        "Contacts",
        "Geolocation",
        "Messaging",
        "Vibration"
      ],
      "icons": [{
        "src": "icon96.png",
        "type": "image/png",
        "sizes": "96x96",
        "density": "2.0"
        }
      ]
    }

...并且这个`crosswalk-pkg <Path to directory that contains a web app>`命令行：

    crosswalk-pkg <Path to directory that contains a web app>

...生成下列的`AndroidManifest.xml`权限：

    <!-- 打包过程中默认添加的权限 -->
    <uses-permission android:name="android.permission.ACCESS_NETWORK_STATE"/>
    <uses-permission android:name="android.permission.ACCESS_WIFI_STATE"/>
    <uses-permission android:name="android.permission.CAMERA"/>
    <uses-permission android:name="android.permission.INTERNET"/>
    <uses-permission android:name="android.permission.MODIFY_AUDIO_SETTINGS"/>
    <uses-permission android:name="android.permission.RECORD_AUDIO"/>
    <uses-permission android:name="android.permission.WAKE_LOCK"/>
    <uses-permission android:name="android.permission.WRITE_EXTERNAL_STORAGE"/>

    <!-- 在manifest文件中添加的权限-->
    <uses-permission android:name="android.permission.READ_CONTACTS"/>
    <uses-permission android:name="android.permission.WRITE_CONTACTS"/>
    <uses-permission android:name="android.permission.ACCESS_FINE_LOCATION"/>
    <uses-permission android:name="android.permission.READ_SMS"/>
    <uses-permission android:name="android.permission.READ_PHONE_STATE"/>
    <uses-permission android:name="android.permission.RECEIVE_SMS"/>
    <uses-permission android:name="android.permission.SEND_SMS"/>
    <uses-permission android:name="android.permission.WRITE_SMS"/>
    <uses-permission android:name="android.permission.VIBRATE"/>

注意`crosswalk-pkg`命令通常添加一个默认集合的permission（在上例中的第一组）。第二组的permission是在manifest中的`xwalk_permissions`字段添加的。manifest中的permission和Android permission的对应关系在[下表](#Permissions-required-by-API)中展示。

<h2 id="Permissions-required-by-API">API需要的permission</h2>

如果你想在应用中使用一些Crosswalk的web API，你可能需要在`AndroidManifest.xml`中添加权限使得那些API可访问。你需要手动添加（如果你在使用嵌入式API）。

下表展示了web API分别需要哪些permission。

<table>
  <tr>
    <th>
      Crosswalk web API
    </th>

    <th>
      AndroidManifest.xml permission(s)
    </th>
  </tr>

  <tr>
    <td>
      Contacts
    </td>

    <td>
      android.permission.READ_CONTACTS<br>
      android.permission.WRITE_CONTACTS
    </td>
  </tr>

  <tr>
    <td>
      <a href="http://www.w3.org/TR/geolocation-API/">Geolocation</a>
    </td>

    <td>
      android.permission.ACCESS_FINE_LOCATION
    </td>
  </tr>

  <tr>
    <td>
      Messaging
    </td>

    <td>
      android.permission.READ_SMS<br>
      android.permission.READ_PHONE_STATE<br>
      android.permission.RECEIVE_SMS<br>
      android.permission.SEND_SMS<br>
      android.permission.WRITE_SMS
    </td>
  </tr>

  <tr>
    <td>
      <a href="http://www.w3.org/TR/vibration/">Vibration</a>
    </td>

    <td>
      android.permission.VIBRATE
    </td>
  </tr>
</table>

例如，你可能有一个嵌入Crosswalk运行时的Android应用，并且想要在应用的web应用部分使用JavaScript [震动API](http://www.w3.org/TR/vibration/)。这种情况下，你需要手动地将这个permission加入到`AndroidManifest.xml`中：

    <uses-permission android:name="android.permission.VIBRATE"/>
