# Permissions

The `xwalk_permissions` field is a Crosswalk extension to the W3C manifest. It is used to set permissions in the `AndroidManifest.xml` file when packaging an application for Crosswalk on Android.

This is necessary because Android applications cannot request permissions at runtime: all the permissions required by the application must be granted during installation. In other contexts (Crosswalk Tizen, embedding API), this field is ignored.

**Note:** If you are [loading an application from a manifest with the embedding API](/documentation/manifest/using_the_manifest.html#Load-an-application-into-an-embedded-Crosswalk), you will have to manually specify permissions for Crosswalk in `AndroidManifest.xml`. See the [section below](#Permissions-required-by-API) for guidance on which Android permissions are required by Crosswalk's [web APIs](/documentation/apis/web_apis.html).

<h2 id="Effect-on-Android-packaging">Effect on Android packaging</h2>

The `make_apk.py` script translates the `permissions` or `xwalk_permissions` field in `manifest.json` into `<android:uses-permission>` elements in `AndroidManifest.xml`.

For example, given the following manifest:

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

...and this `make_apk.py` command line:

    python make_apk.py --package=org.crosswalkproject.example \
      --manifest=manifest.json

...the following `AndroidManifest.xml` permission elements are generated:

    <!-- permissions always added during packaging -->
    <uses-permission android:name="android.permission.ACCESS_NETWORK_STATE"/>
    <uses-permission android:name="android.permission.ACCESS_WIFI_STATE"/>
    <uses-permission android:name="android.permission.CAMERA"/>
    <uses-permission android:name="android.permission.INTERNET"/>
    <uses-permission android:name="android.permission.MODIFY_AUDIO_SETTINGS"/>
    <uses-permission android:name="android.permission.RECORD_AUDIO"/>
    <uses-permission android:name="android.permission.WAKE_LOCK"/>
    <uses-permission android:name="android.permission.WRITE_EXTERNAL_STORAGE"/>

    <!-- permissions added due to the "permissions" manifest field -->
    <uses-permission android:name="android.permission.READ_CONTACTS"/>
    <uses-permission android:name="android.permission.WRITE_CONTACTS"/>
    <uses-permission android:name="android.permission.ACCESS_FINE_LOCATION"/>
    <uses-permission android:name="android.permission.READ_SMS"/>
    <uses-permission android:name="android.permission.READ_PHONE_STATE"/>
    <uses-permission android:name="android.permission.RECEIVE_SMS"/>
    <uses-permission android:name="android.permission.SEND_SMS"/>
    <uses-permission android:name="android.permission.WRITE_SMS"/>
    <uses-permission android:name="android.permission.VIBRATE"/>

Note that the `make_apk.py` script always adds a default set of permissions (the first group in the example above). Only the second group of permissions are the ones added because of the presence of the `xwalk_permissions` field in the manifest. The mappings from permissions in the manifest to Android permissions are shown in the [table in the next section](#Permissions-required-by-API).

Alternatively, permissions can be specified on the command line and not included in `manifest.json`. For example, the following command line option would have the same effect as the manifest shown:

    --permissions=Contacts:Geolocation:Messaging:Vibration

Note that permission values are not case sensitive (either in the manifest or on the command line), so the following would be equivalent:

    --permissions=contacts:geolocation:messaging:vibration

<h2 id="Permissions-required-by-API">Permissions required by API</h2>

If you want to use some of Crosswalk's web APIs in an application, you may need to add permissions to `AndroidManifest.xml` to make those APIs accessible. You can either do this via the [packaging script](/documentation/android/run_on_android.html), or manually (if you are using the embedding API).

The table below shows which web APIs require which permissions.

<table>
  <tr>
    <th>
      Crosswalk web API
    </th>

    <th>
      Permission in manifest.json or on <code>make_apk.py</code> command line
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
      Geolocation
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
      Vibration
    </td>

    <td>
      android.permission.VIBRATE
    </td>
  </tr>
</table>

For example, you may have an Android application which embeds Crosswalk, and want to use the JavaScript [Vibration API](http://www.w3.org/TR/vibration/) in the web application part of the app. In this case, you would need to manually add this permission to `AndroidManifest.xml`:

    <uses-permission android:name="android.permission.VIBRATE"/>
