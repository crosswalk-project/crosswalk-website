<h1>Crosswalk manifest</h1>

<p>Crosswalk applications can use an optional <code>manifest.json</code> file which specifies some aspects of how an application should behave and present itself.</p>

<p>The current Crosswalk manifest implementation is still under heavy development, but the eventual aim is for it to adhere to, influence, and act as a reference implementation of the <a href="http://w3c.github.io/manifest/">W3C Manifest for Web Application specification</a>. Once this is achieved, a Crosswalk application + manifest should behave consistently (within the scope of the specification) across all runtimes which implement it.</p>

<p>In the meantime, any fields in the Crosswalk manifest which are not part of the W3C Manifest specification are marked with <strong>"(extension)"</strong> below.</p>

<p>See <a href="#documentation/manifest/using_the_manifest">Using the manifest</a> for details of how to use a <code>manifest.json</code> file with a Crosswalk application.</p>

<p>Use the form below to display the valid manifest fields for a Crosswalk version and platform combination.</p>

<div data-role="manifest-description" class="hidden">
  <div data-role="manifest-filter">
    <p>
      <strong>Show information about the manifest for</strong>&nbsp;

      <br>

      <select name="crosswalk-version">
        <option value="8">Crosswalk 8</option>
        <option value="7">Crosswalk 7</option>
        <option value="6">Crosswalk 6</option>
        <option value="5">Crosswalk 5</option>
        <option value="4">Crosswalk 4</option>
        <option value="3">Crosswalk 3</option>
        <option value="2">Crosswalk 2</option>
        <option value="1">Crosswalk 1</option>
      </select>
      &nbsp;<strong>on</strong>&nbsp;
      <select name="crosswalk-platform">
        <option value="android">Android</option>
        <option value="webview">Android (embedding API)</option>
        <option value="tizen">Tizen</option>
      </select>
    </p>

    <p data-crosswalk-versions="1+" data-crosswalk-platforms="android">
      (applies to applications packaged using
      <a href="#documentation/getting_started/run_on_android">the Crosswalk Android bundle</a>)
    </p>
    <p data-crosswalk-versions="1+" data-crosswalk-platforms="webview">
      (applies to Android applications using an
      <a href="#documentation/embedding_crosswalk">embedded Crosswalk</a>)
    </p>
    <p data-crosswalk-versions="1+" data-crosswalk-platforms="tizen">
      (applies to applications running on
      <a href="#documentation/getting_started/run_on_tizen">Crosswalk
      on Tizen</a>)
    </p>
  </div>

  <div data-role="manifest-fields">
    <h2>
      Manifest fields for <span data-role="crosswalk-version"></span>
      on <span data-role="crosswalk-platform"></span>
    </h2>

    <p>Note that some of the manifest field descriptions are based on the <a href="http://w3c.github.io/manifest/">W3C Manifest for Web Application draft specification</a>, where they are compatible with it. You may want to read the full specification to understand the nuances of how these fields are intended to work.</p>

    <!-- special case for Android, which had no manifest support in Crosswalk 1 -->
    <p data-crosswalk-versions="1" data-crosswalk-platforms="android">
      <strong>Version 1 of Crosswalk did not support Android.</strong>
    </p>

    <p data-crosswalk-versions="1-5" data-crosswalk-platforms="webview">
      <strong>Versions 1 to 5 of Crosswalk did not provide an embedding API.</strong>
    </p>

    <ul>
      <li data-field="app.launch.local_path.extension">
        <p><strong data-role="field-name"></strong></p>
        <p>Specifies an HTML file to use as the entry point for running the application.</p>
        <p><a href="#documentation/manifest/entry_points">Read more...</a></p>
      </li>

      <li data-field="app.main.scripts.extension">
        <p><strong data-role="field-name"></strong></p>
        <p>Specifies an array of JavaScript files to load as the application entry point. A main document will be automatically generated as the context for the scripts.</p>
        <p><a href="#documentation/manifest/entry_points">Read more...</a></p>
      </li>

      <li data-field="app.main.source.extension">
        <p><strong data-role="field-name"></strong></p>
        <p>Specifies an HTML file to use as the entry point for running the application. This has the same effect as the <strong>app.launch.local_path</strong> field.</p>
        <p><a href="#documentation/manifest/entry_points">Read more...</a></p>
      </li>

      <li data-field="content_security_policy.extension">
        <p><strong data-role="field-name"></strong></p>
        <p>Represents the <a href="http://w3c.github.io/webappsec/specs/content-security-policy/csp-specification.dev.html">content security policy (CSP)</a> which should be enforced for the application. The CSP restricts the types of locations of resources the application can load, to help prevent <a href="https://www.owasp.org/index.php/Cross-site_Scripting_(XSS)">Cross-Site Scripting (XSS)</a> and related attacks. CSP is disabled if this field is not set.</p>
        <p><a href="#documentation/manifest/content_security_policy">Read more...</a></p>
      </li>

      <li data-field="csp.extension">
        <p><strong data-role="field-name"></strong></p>
        <p>Represents the <a href="http://w3c.github.io/webappsec/specs/content-security-policy/csp-specification.dev.html">content security policy (CSP)</a> which should be enforced for the application. The CSP restricts the types of locations of resources the application can load, to help prevent <a href="https://www.owasp.org/index.php/Cross-site_Scripting_(XSS)">Cross-Site Scripting (XSS)</a> and related attacks. CSP is disabled if this field is not set.</p>
        <p><a href="#documentation/manifest/content_security_policy">Read more...</a></p>
      </li>

      <li data-field="description.extension">
        <p><strong data-role="field-name"></strong></p>
        <p>Free-form text describing the application.</p>
      </li>

      <li data-field="display">
        <p><strong data-role="field-name"></strong></p>
        <p>The preferred display mode for the application (e.g. "fullscreen", "standalone").</p>
        <p>
          <a href="http://w3c.github.io/manifest/#display-member">W3C spec</a> |
          <a href="#documentation/manifest/display">Read more...</a>
        </p>
      </li>

      <li data-field="icons.extension">
        <p><strong data-role="field-name"></strong></p>
        <p>Graphics files to use for the application icon at different resolutions.</p>
        <p><a href="#documentation/manifest/icons">Read more...</a></p>
      </li>

      <li data-field="launch_screen.extension">
        <p><strong data-role="field-name"></strong></p>
        <p>Defines a static user interface to be shown immediately after the application is launched.</p>
        <p><a href="#documentation/manifest/launch_screen">Read more...</a></p>
      </li>

      <li data-field="name.extension">
        <p><strong data-role="field-name"></strong></p>
        <p>The name of the application as it is displayed to a user.</p>
      </li>

      <li data-field="name">
        <p><strong data-role="field-name"></strong></p>
        <p>The name of the application as it is displayed to a user.</p>
        <p>
          <a href="http://w3c.github.io/manifest/#name-member">W3C spec</a>
        </p>
      </li>

      <li data-field="orientation">
        <p><strong data-role="field-name"></strong></p>
        <p>???TODO</p>
        <p>
          <a href="http://w3c.github.io/manifest/#???">W3C spec</a>
          <a href="#documentation/manifest/orientation">Read more...</a>
        </p>
      </li>

      <li data-field="permissions.extension">
        <p><strong data-role="field-name"></strong></p>
        <p>Defines permissions the application needs so it can access platform features. <a href="#manifest-permissions">See below</a> for details of the available permissions.</p>
        <p><a href="#documentation/manifest/permissions">Read more...</a></p>
      </li>

      <li data-field="start_url">
        <p><strong data-role="field-name"></strong></p>
        <p>The URL to load when the user launches the web application.</p>
        <p>
          <a href="http://w3c.github.io/manifest/#start_url-member">W3C spec</a> |
          <a href="#documentation/manifest/entry_points">Read more...</a>
        </p>
      </li>

      <li data-field="version.extension">
        <p><strong data-role="field-name"></strong></p>
        <p>A string containing one to four dot-separated integers identifying the application version; for example: "2", "1.2", "1.3.0", "4.0.0.11". A couple of rules apply to the integers: they must be between 0 and 65535, inclusive; and you can't prefix any non-zero values with "0" (e.g. "01.1" is invalid).</p>
      </li>

      <li data-field="xwalk_hosts.extension">
        <p><strong data-role="field-name"></strong></p>
        <p>Defines host URL patterns to which the application can make Ajax requests, allowing <a href="https://developer.chrome.com/extensions/xhr">Cross-Origin Ajax requests</a> (using a mechanism similar to Chrome's).</p>
        <p><a href="#documentation/manifest/xwalk_hosts">Read more...</a></p>
      </li>

      <li data-field="xwalk_launch_screen.extension">
        <p><strong data-role="field-name"></strong></p>
        <p>Defines a static user interface to be shown immediately after the application is launched.</p>
        <p><a href="#documentation/manifest/launch_screen">Read more...</a></p>
      </li>
    </ul>

  </div>

  <div data-role="manifest-permissions" data-crosswalk-versions="6+"
       data-crosswalk-platforms="webview">
     <h2>Permissions for embedded Crosswalk</h2>

     <p>If you are using the <a href="#documentation/apis/embedding_api">embedding
     API</a> to embed Crosswalk in your application, you may need to
     manually set some permissions in the <code>AndroidManifest.xml</code>
     file. See
     <a href="#documentation/manifest/permissions/Permissions-required-by-API">this
     section</a> for more details.</p>
  </div>

  <div data-role="manifest-permissions" data-crosswalk-versions="4+"
       data-crosswalk-platforms="android">
    <h2><a name="manifest-permissions"></a>
      Permissions for <span data-role="crosswalk-version"></span>
      on <span data-role="crosswalk-platform"></span>
    </h2>

    <p>See the <a href="#documentation/manifest/permissions">permissions page</a> for details of what this field is for.</p>

    <p>The following values (in bold) can be used in the <strong>permissions</strong> list in the manifest:</p>

    <ul>

      <li data-crosswalk-versions="4+" data-crosswalk-platforms="android">
        <p><strong>Contacts</strong>: sets Android permissions which allow use of the <em>Contacts Manager</em> API in Crosswalk.</p>
      </li>

      <li data-crosswalk-versions="4+" data-crosswalk-platforms="android">
        <p><strong>DeviceCapabilities</strong>: sets Android permissions which allow use of the <em>Device Capabilities</em> API in Crosswalk.</p>
      </li>

      <li data-crosswalk-versions="4+" data-crosswalk-platforms="android">
        <p><strong>Fullscreen</strong>: sets Android permissions which allow use of the <em>Fullscreen</em> API in Crosswalk.</p>
      </li>

      <li data-crosswalk-versions="4+" data-crosswalk-platforms="android">
        <p><strong>Geolocation</strong>: sets Android permissions which allow use of the <em>Geolocation</em> API in Crosswalk.</p>
      </li>

      <li data-crosswalk-versions="4+" data-crosswalk-platforms="android">
        <p><strong>Messaging</strong>: sets Android permissions which allow use of the <em>Messaging</em> API in Crosswalk.</p>
      </li>

      <li data-crosswalk-versions="4+" data-crosswalk-platforms="android">
        <p><strong>Presentation</strong>: sets Android permissions which allow use of the <em>Presentation</em> API in Crosswalk.</p>
      </li>

      <li data-crosswalk-versions="4+" data-crosswalk-platforms="android">
        <p><strong>RawSockets</strong>: sets Android permissions which allow use of the <em>Raw Sockets</em> API in Crosswalk.</p>
      </li>

      <li data-crosswalk-versions="4+" data-crosswalk-platforms="android">
        <p><strong>ScreenOrientation</strong>: sets Android permissions which allow use of the <em>ScreenOrientation</em> API in Crosswalk.</p>
      </li>

      <li data-crosswalk-versions="5+" data-crosswalk-platforms="android">
        <p><strong>Vibration</strong>: sets Android permissions which allow use of the <em>Vibration</em> API in Crosswalk.</p>
      </li>

    </ul>
  </div>

  <div data-role="manifest-example">
    <h2>
      Example manifest for <span data-role="crosswalk-version"></span>
      on <span data-role="crosswalk-platform"></span>
    </h2>

    <p data-crosswalk-versions="1" data-crosswalk-platforms="android">
      <strong>Version 1 of Crosswalk did not support Android.</strong>
    </p>

    <p data-crosswalk-versions="1-5" data-crosswalk-platforms="webview">
      <strong>Versions 1 to 5 of Crosswalk did not provide an embedding API.</strong>
    </p>

    <!-- TIZEN MANIFEST EXAMPLES -->
    <div data-crosswalk-versions="1-3" data-crosswalk-platforms="tizen">
      <p>Using <code>app.launch.local_path</code> to specify the entry point:</p>

      <pre>
{
  "name": "app name",
  "description": "a sample description",
  "version": "1.0.0",
  "app": {
    "launch": {
      "local_path": "index.html"
    }
  },
  "icons": {
    "128": "icon128.png"
  }
}
      </pre>

      <p>Using <code>app.main.scripts</code> to specify the entry point:</p>

      <pre>
{
  "name": "app name",
  "description": "a sample description",
  "version": "1.0.0",
  "app": {
    "main": {
      "scripts": ["main.js"]
    }
  },
  "icons": {
    "128": "icon128.png"
  }
}
      </pre>

      <p>Using <code>app.main.source</code> to specify the entry point:</p>

      <pre>
{
  "name": "app name",
  "description": "a sample description",
  "version": "1.0.0",
  "app": {
    "main": {
      "source": "main.html"
    }
  },
  "icons": {
    "128": "icon128.png"
  }
}
      </pre>
    </div>

    <div data-crosswalk-versions="4-6" data-crosswalk-platforms="tizen">
      <p>Using <code>app.launch.local_path</code> to specify the entry point:</p>

      <pre>
{
  "name": "app name",
  "description": "a sample description",
  "version": "1.0.0",
  "app": {
    "launch": {
      "local_path": "index.html"
    }
  },
  "content_security_policy": "script-src 'self'; object-src 'self'",
  "icons": {
    "128": "icon128.png"
  }
}
      </pre>

      <p>Using <code>app.main.scripts</code> to specify the entry point:</p>

      <pre>
{
  "name": "app name",
  "description": "a sample description",
  "version": "1.0.0",
  "app": {
    "main": {
      "scripts": ["main.js"]
    }
  },
  "content_security_policy": "script-src 'self'; object-src 'self'",
  "icons": {
    "128": "icon128.png"
  }
}
      </pre>

      <p>Using <code>app.main.source</code> to specify the entry point:</p>

      <pre>
{
  "name": "app name",
  "description": "a sample description",
  "version": "1.0.0",
  "app": {
    "main": {
      "source": "main.html"
    }
  },
  "content_security_policy": "script-src 'self'; object-src 'self'",
  "icons": {
    "128": "icon128.png"
  }
}
      </pre>
    </div>

    <div data-crosswalk-versions="7" data-crosswalk-platforms="tizen">
      <pre>
{
  "name": "app name",
  "description": "a sample description",
  "version": "1.0.0",
  "start_url": "index.html",
  "display": "fullscreen",
  "content_security_policy": "script-src 'self'; object-src 'self'",
  "icons": {
    "128": "icon128.png"
  }
}
      </pre>
    </div>

    <div data-crosswalk-versions="8+" data-crosswalk-platforms="tizen">
      <pre>
{
  "name": "app name",
  "description": "a sample description",
  "version": "1.0.0",
  "start_url": "index.html",
  "display": "fullscreen",
  "orientation": "landscape",
  "csp": "script-src 'self'; object-src 'self'",
  "icons": {
    "128": "icon128.png"
  }
}
      </pre>
    </div>

    <!-- ANDROID MANIFEST EXAMPLES -->
    <div data-crosswalk-versions="2-3" data-crosswalk-platforms="android">
      <pre>
{
  "name": "app name",
  "description": "a sample description",
  "version": "1.0.0",
  "app": {
    "launch": {
      "local_path": "index.html"
    }
  },
  "icons": {
    "128": "icon128.png"
  }
}
      </pre>
    </div>

    <div data-crosswalk-versions="4" data-crosswalk-platforms="android">
      <pre>
{
  "name": "app name",
  "description": "a sample description",
  "version": "1.0.0",
  "app": {
    "launch": {
      "local_path": "index.html"
    }
  },
  "icons": {
    "128": "icon128.png"
  },
  "permissions": [
    "Contacts",
    "DeviceCapabilities",
    "Fullscreen",
    "Geolocation",
    "Messaging",
    "Presentation",
    "RawSockets",
    "ScreenOrientation"
  ]
}
      </pre>
    </div>

    <div data-crosswalk-versions="5" data-crosswalk-platforms="android">
      <pre>
{
  "name": "app name",
  "description": "a sample description",
  "version": "1.0.0",
  "app": {
    "launch": {
      "local_path": "index.html"
    }
  },
  "icons": {
    "128": "icon128.png"
  },
  "permissions": [
    "Contacts",
    "DeviceCapabilities",
    "Fullscreen",
    "Geolocation",
    "Messaging",
    "Presentation",
    "RawSockets",
    "ScreenOrientation",
    "Vibration"
  ],
  "launch_screen": {
    "ready_when": "custom",
    "portrait": {
       "background_color": "#ff0000",
       "background_image": "bgfoo.png 1x, bgfoo-2x.png 2x",
       "image": "foo.png 1x, foo-2x.png 2x",
       "image_border": "30px 40px stretch"
     }
  }
}
      </pre>
    </div>

    <div data-crosswalk-versions="6" data-crosswalk-platforms="android">
      <pre>
{
  "name": "app name",
  "description": "a sample description",
  "version": "1.0.0",
  "app": {
    "launch": {
      "local_path": "index.html"
    }
  },
  "content_security_policy": "script-src 'self'; object-src 'self'",
  "icons": {
    "128": "icon128.png"
  },
  "permissions": [
    "Contacts",
    "DeviceCapabilities",
    "Fullscreen",
    "Geolocation",
    "Messaging",
    "Presentation",
    "RawSockets",
    "ScreenOrientation",
    "Vibration"
  ],
  "launch_screen": {
    "ready_when": "custom",
    "portrait": {
       "background_color": "#ff0000",
       "background_image": "bgfoo.png 1x, bgfoo-2x.png 2x",
       "image": "foo.png 1x, foo-2x.png 2x",
       "image_border": "30px 40px stretch"
     }
  },
  "xwalk_hosts": [
    "http://*"
  ]
}
      </pre>
    </div>

    <div data-crosswalk-versions="7" data-crosswalk-platforms="android">
      <pre>
{
  "name": "app name",
  "description": "a sample description",
  "version": "1.0.0",
  "app": {
    "launch": {
      "local_path": "index.html"
    }
  },
  "content_security_policy": "script-src 'self'; object-src 'self'",
  "icons": {
    "128": "icon128.png"
  },
  "permissions": [
    "Contacts",
    "DeviceCapabilities",
    "Fullscreen",
    "Geolocation",
    "Messaging",
    "Presentation",
    "RawSockets",
    "ScreenOrientation",
    "Vibration"
  ],
  "launch_screen": {
    "ready_when": "custom",
    "portrait": {
       "background_color": "#ff0000",
       "background_image": "bgfoo.png 1x, bgfoo-2x.png 2x",
       "image": "foo.png 1x, foo-2x.png 2x",
       "image_border": "30px 40px stretch"
     }
  },
  "xwalk_hosts": [
    "http://*"
  ]
}
      </pre>
    </div>

    <div data-crosswalk-versions="8" data-crosswalk-platforms="android">
      <pre>
{
  "name": "app name",
  "description": "a sample description",
  "version": "1.0.0",
  "start_url": "index.html",
  "csp": "script-src 'self'; object-src 'self'",
  "icons": {
    "128": "icon128.png"
  },
  "display": "fullscreen",
  "orientation": "landscape",
  "permissions": [
    "Contacts",
    "DeviceCapabilities",
    "Fullscreen",
    "Geolocation",
    "Messaging",
    "Presentation",
    "RawSockets",
    "ScreenOrientation",
    "Vibration"
  ],
  "xwalk_launch_screen": {
    "ready_when": "custom",
    "portrait": {
       "background_color": "#ff0000",
       "background_image": "bgfoo.png 1x, bgfoo-2x.png 2x",
       "image": "foo.png 1x, foo-2x.png 2x",
       "image_border": "30px 40px stretch"
     }
  },
  "xwalk_hosts": [
    "http://*"
  ]
}
      </pre>
    </div>

    <!-- ANDROID EMBEDDING API EXAMPLE MANIFESTS -->
    <div data-crosswalk-versions="6-7" data-crosswalk-platforms="webview">
      <pre>
{
  "name": "app name",
  "description": "a sample description",
  "version": "1.0.0",
  "app": {
    "launch": {
      "local_path": "index.html"
    }
  },
  "content_security_policy": "script-src 'self'; object-src 'self'",
  "xwalk_hosts": [
    "http://*"
  ]
}
      </pre>
    </div>

    <div data-crosswalk-versions="8+" data-crosswalk-platforms="webview">
      <pre>
{
  "name": "app name",
  "description": "a sample description",
  "version": "1.0.0",
  "start_url": "index.html",
  "display": "fullscreen",
  "orientation": "landscape",
  "csp": "script-src 'self'; object-src 'self'",
  "xwalk_hosts": [
    "http://*"
  ]
}
      </pre>
    </div>

  </div>
</div>

<script>
// selectedVersion: '1', '2', '3' etc.
// versions: string like '1,2,3' or '4,5+' or '3-6'
// returns true if selectedVersion is in versions;
// or if there is a string "X+" in versions, where X < selectedVersion;
// or if there is a string "X-Y" in versions, and selectedVersion >= X
// && selectedVersion <= Y
var checkVersion = function (selectedVersion, versions) {
  selectedVersion = parseInt(selectedVersion);

  var versionRegex = new RegExp(selectedVersion);

  if (versionRegex.test(versions)) {
    return true;
  }

  // try to find an "X+" version
  var versionWithPlus = /(\d+)\+$/.exec(versions);

  if (versionWithPlus) {
    var version = parseInt(versionWithPlus[1]);
    if (version < selectedVersion) {
      return true;
    }
  }

  // try to find an "X-Y" version
  var versionRange = /(\d+)\-(\d+)/.exec(versions);

  if (versionRange) {
    var earliest = parseInt(versionRange[1]);
    var latest = parseInt(versionRange[2]);
    if (selectedVersion >= earliest && selectedVersion <= latest) {
      return true;
    }
  }

  return false;
};

// selectedPlatform: 'android' or 'tizen'
// platforms: 'android', 'tizen', 'android,tizen'
// returns true if selectedPlatform regex matches platforms
var checkPlatform = function (selectedPlatform, platforms) {
  var re = new RegExp(platforms);
  return re.test(selectedPlatform);
};

// object for toggling/rewriting elements on this page
var ManifestDisplay = function (opts) {
  // specification of manifest fields with versions where they
  // are available and/or when they were deprecated
  this.manifestFields = opts.manifestFields;

  // data-field elements; these are turned on/off according to data
  // in the manifestFields object
  this.fieldElts = opts.fieldElts;

  // elements whose inner text is rewritten when platform or version
  // changes respectively
  this.platformElts = opts.platformElts;
  this.versionElts = opts.versionElts;

  // elements which are toggled on/off when platform or version changes
  this.toggleElts = opts.toggleElts;

  // version and platform drop-down elements
  this.versionSelect = opts.versionSelect;
  this.platformSelect = opts.platformSelect;
};

// bind handlers so changes on the drop-downs cause sections to be
// re-displayed; this is done here to prevent a double-update the
// first time the page is displayed (when we set both platform and
// version manually)
ManifestDisplay.prototype.bindSelects = function () {
  var filter = this.filter.bind(this);
  this.platformSelect.addEventListener('change', filter);
  this.versionSelect.addEventListener('change', filter);
};

// filter elements on the page so only those applicable to the
// currently-selected platform and version are visible; also
// alter text in elements which display the platform and version
ManifestDisplay.prototype.filter = function () {
  var i;

  // get the version and platform from the drop-downs
  var platformIndex = this.platformSelect.selectedIndex;
  var crosswalkPlatform = this.platformSelect.options[platformIndex].value;

  var versionIndex = this.versionSelect.selectedIndex;
  var crosswalkVersion = this.versionSelect.options[versionIndex].value;

  // get the pretty version and platform names (for populating
  // any elements which display version and platform)
  var crosswalkPlatformText = this.platformSelect.options[platformIndex].text;
  var crosswalkVersionText = this.versionSelect.options[versionIndex].text;

  // hide any elements which don't match version + platform,
  // show the ones which do
  var entry = '';
  var versionMatches = false;

  var field = '';

  var deprecatedAt = '';
  var nameElt = null;
  var text = '';

  // show/hide manifest example, properties and permissions for this
  // version+platform; each available field is marked with
  // data-available="true" (which could be used to automatically
  // construct a manifest example in future, maybe)
  var elts = this.fieldElts;
  for (i = 0; i < elts.length; i++) {
    versionMatches = false;

    // look up the versions for which this manifest field is supported
    // on the selected platform
    field = elts[i].getAttribute('data-field');
    entry = this.manifestFields[field][crosswalkPlatform];
    versionMatches = entry && checkVersion(crosswalkVersion, entry.versions);

    if (versionMatches) {
      text = this.manifestFields[field].name;

      deprecatedAt = this.manifestFields[field][crosswalkPlatform].deprecated;

      // any deprecated fields are in grey and have " (deprecated)" appended
      if (deprecatedAt && checkVersion(crosswalkVersion, deprecatedAt)) {
        text = '<span style="color:gray;">' + text + ' (deprecated)</span>';
      }

      nameElt = elts[i].querySelector('[data-role="field-name"]');

      if (nameElt) {
        nameElt.innerHTML = text;
      }

      elts[i].classList.remove('hidden');
    }
    else {
      elts[i].classList.add('hidden');
    }

    elts[i].setAttribute('data-available', '' + versionMatches);
  }

  // rewrite content of data-role="crosswalk-platform" elements
  // to show selected platform
  elts = this.platformElts;
  for (i = 0; i < elts.length; i++) {
    elts[i].innerHTML = crosswalkPlatformText;
  }

  // rewrite content of data-role="crosswalk-version" elements
  // to show selected version
  elts = this.versionElts;
  for (i = 0; i < elts.length; i++) {
    elts[i].innerHTML = crosswalkVersionText;
  }

  // display the manifest example element for this platform/version
  // (if one exists)
  elts = this.toggleElts;
  for (i = 0; i < elts.length; i++) {
    if (checkVersion(crosswalkVersion, elts[i].getAttribute('data-crosswalk-versions')) &&
        checkPlatform(crosswalkPlatform, elts[i].getAttribute('data-crosswalk-platforms'))) {
      elts[i].classList.remove('hidden');
    }
    else {
      elts[i].classList.add('hidden');
    }
  }
};

// manually set version drop-down
ManifestDisplay.prototype.selectCrosswalkVersion = function (version) {
  var sel = 'option[value="' + version + '"]';
  var opt = this.versionSelect.querySelector(sel);
  opt.selected = true;
};

// manually set platform drop-down
ManifestDisplay.prototype.selectCrosswalkPlatform = function (platform) {
  var sel = 'option[value="' + platform + '"]';
  var opt = this.platformSelect.querySelector(sel);
  opt.selected = true;
};

// MAIN
// asyncJsonGet() is defined in utils.js
asyncJsonGet('manifest-fields.json', function (err, manifestFields) {
  var manifestDiv = document.querySelector('[data-role="manifest-description"]');
  manifestDiv.classList.remove('hidden');

  if (err) {
    manifestDiv.classList.add('error');
    manifestDiv.innerHTML = 'ERROR: could not load manifest information ' +
                            'from the manifest-fields.json file';
    return;
  }

  // drop-down elements
  var platformSelect = document.querySelector('select[name="crosswalk-platform"]');
  var versionSelect = document.querySelector('select[name="crosswalk-version"]');

  // elements whose text is rewritten when version or platform changes
  var platformElts = document.querySelectorAll('[data-role="crosswalk-platform"]');
  var versionElts = document.querySelectorAll('[data-role="crosswalk-version"]');

  // elements which can be toggled on/off depending on the platform+version
  // combination selected
  var sel = '[data-field]';
  var fieldElts = document.querySelectorAll(sel);

  // manifest example elements, turned on or off depending on selected
  // version+platform
  sel = '[data-crosswalk-versions][data-crosswalk-platforms]';
  var toggleElts = document.querySelectorAll(sel);

  // replace_version_string() is defined in xwalk.js;
  // this gets the current stable Android version number
  var currentVersion = replace_version_string('${XWALK-STABLE-ANDROID-X86-MAJOR}');

  // set up the object which handles the page filtering
  var manifestDisplay = new ManifestDisplay({
    manifestFields: manifestFields,
    fieldElts: fieldElts,
    platformSelect: platformSelect,
    versionSelect: versionSelect,
    platformElts: platformElts,
    versionElts: versionElts,
    toggleElts: toggleElts
  });

  // apply initial filter
  manifestDisplay.selectCrosswalkVersion(currentVersion);
  manifestDisplay.selectCrosswalkPlatform('android');

  // start listening to the drop-downs
  manifestDisplay.bindSelects();

  // apply the current filter by version+platform
  manifestDisplay.filter();
});
</script>
