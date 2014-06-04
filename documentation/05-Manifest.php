<h1>Crosswalk manifest</h1>

<p>???preamble about how manifest has changed, what it does???</p>

<div data-role="manifest-description" class="hidden">
  <div data-role="manifest-filter">
    <p>
      Show information about manifest for&nbsp;

      <select name="crosswalk-version">
        <option value="7">Crosswalk 7</option>
        <option value="6">Crosswalk 6</option>
        <option value="5">Crosswalk 5</option>
        <option value="4">Crosswalk 4</option>
        <option value="3">Crosswalk 3</option>
        <option value="2">Crosswalk 2</option>
        <option value="1">Crosswalk 1</option>
      </select>
      &nbsp;on&nbsp;
      <select name="crosswalk-platform">
        <option value="android">Android</option>
        <option value="tizen">Tizen</option>
      </select>
    </p>
  </div>

  <div data-role="manifest-fields">
    <h2>
      Manifest fields for <span data-role="crosswalk-version"></span>
      on <span data-role="crosswalk-platform"></span>
    </h2>

    <!-- special case for Android, which had no manifest support in Crosswalk 1 -->
    <p data-crosswalk-versions="1" data-crosswalk-platforms="android">
      Version 1 of Crosswalk for Android did not support manifests.
    </p>

    <ul>
      <li data-field="app.launch.local_path">
        <p><strong data-role="field-name"></strong></p>
        <p><a href="#documentation/manifest/entry_points">Read more...</a></p>
      </li>

      <li data-field="app.main.scripts">
        <p><strong data-role="field-name"></strong></p>
        <p><a href="#documentation/manifest/entry_points">Read more...</a></p>
      </li>

      <li data-field="app.main.source">
        <p><strong data-role="field-name"></strong></p>
        <p><a href="#documentation/manifest/entry_points">Read more...</a></p>
      </li>

      <li data-field="content_security_policy">
        <p><strong data-role="field-name"></strong></p>
        <p><a href="#documentation/manifest/content_security_policy">Read more...</a></p>
      </li>

      <li data-field="description">
        <p><strong data-role="field-name"></strong></p>
        <p><a href="#documentation/manifest/description">Read more...</a></p>
      </li>

      <li data-field="icons.128">
        <p><strong data-role="field-name"></strong></p>
        <p><a href="#documentation/manifest/icons">Read more...</a></p>
      </li>

      <li data-field="launch_screen">
        <p><strong data-role="field-name"></strong></p>
        <p><a href="#documentation/manifest/launch_screen">Read more...</a></p>
      </li>

      <li data-field="name">
        <p><strong data-role="field-name"></strong></p>
        <p><a href="#documentation/manifest/name">Read more...</a></p>
      </li>

      <li data-field="permissions">
        <p><strong data-role="field-name"></strong></p>
        <p><a href="#documentation/manifest/permissions">Read more...</a></p>
      </li>

      <li data-field="version">
        <p><strong data-role="field-name"></strong></p>
        <p><a href="#documentation/manifest/version">Read more...</a></p>
      </li>

      <li data-field="xwalk_hosts">
        <p><strong data-role="field-name"></strong></p>
        <p><a href="#documentation/manifest/xwalk_hosts">Read more...</a></p>
      </li>
    </ul>

  </div>

  <div data-role="manifest-permissions" data-crosswalk-versions="4+"
       data-crosswalk-platforms="android">
    <h2>
      Permissions for <span data-role="crosswalk-version"></span>
      on <span data-role="crosswalk-platform"></span>
    </h2>
  </div>

  <div data-role="manifest-example">
    <h2>
      Example manifest for <span data-role="crosswalk-version"></span>
      on <span data-role="crosswalk-platform"></span>
    </h2>

    <p data-crosswalk-versions="1" data-crosswalk-platforms="android">
      Version 1 of Crosswalk for Android did not support manifests.
    </p>

    <!-- TIZEN MANIFEST EXAMPLES -->
    <div data-crosswalk-versions="1" data-crosswalk-platforms="tizen">
      <pre>
{
  "name": "app name",
  "description": "a sample description",
  "version": "1.0.0",
  "app": {
    "main": {
      "scripts": ["main.js"],
      "source": "main.html"
    },
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

    <div data-crosswalk-versions="2" data-crosswalk-platforms="tizen">
      <pre>
{
  "name": "app name",
  "description": "a sample description",
  "version": "1.0.0",
  "app": {
    "main": {
      "scripts": ["main.js"],
      "source": "main.html"
    },
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

    <div data-crosswalk-versions="3" data-crosswalk-platforms="tizen">
      <pre>
{
  "name": "app name",
  "description": "a sample description",
  "version": "1.0.0",
  "app": {
    "main": {
      "scripts": ["main.js"],
      "source": "main.html"
    },
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

    <div data-crosswalk-versions="4" data-crosswalk-platforms="tizen">
      <pre>
{
  "name": "app name",
  "description": "a sample description",
  "version": "1.0.0",
  "app": {
    "main": {
      "scripts": ["main.js"],
      "source": "main.html"
    },
    "launch": {
      "local_path": "index.html"
    }
  },
  "content_security_policy": "script-src 'self'",
  "icons": {
    "128": "icon128.png"
  }
}
      </pre>
    </div>

    <div data-crosswalk-versions="5" data-crosswalk-platforms="tizen">
      <pre>
{
  "name": "app name",
  "description": "a sample description",
  "version": "1.0.0",
  "app": {
    "main": {
      "scripts": ["main.js"],
      "source": "main.html"
    },
    "launch": {
      "local_path": "index.html"
    }
  },
  "content_security_policy": "script-src 'self'",
  "icons": {
    "128": "icon128.png"
  }
}
      </pre>
    </div>

    <div data-crosswalk-versions="6" data-crosswalk-platforms="tizen">
      <pre>
{
  "name": "app name",
  "description": "a sample description",
  "version": "1.0.0",
  "app": {
    "main": {
      "scripts": ["main.js"],
      "source": "main.html"
    },
    "launch": {
      "local_path": "index.html"
    }
  },
  "content_security_policy": "script-src 'self'",
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
  "app": {
    "launch": {
      "local_path": "index.html"
    }
  },
  "content_security_policy": "script-src 'self'",
  "icons": {
    "128": "icon128.png"
  }
}
      </pre>
    </div>

    <!-- ANDROID MANIFEST EXAMPLES -->
    <div data-crosswalk-versions="2" data-crosswalk-platforms="android">
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

    <div data-crosswalk-versions="3" data-crosswalk-platforms="android">
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
    "Geolocation",
    "Messaging"
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
    "Geolocation",
    "Messaging"
  ],
  "launch_screen": {
    "ready_when": "custom",
    "portrait": {
       "background_color": "#ff0000",
       "background_image": "bgfoo.png 1x, bgfoo-2x.png 2x",
       "image": "foo.png 1x, foo-2x.png 2x",
       "image_border": "30px 40px stretch",
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
  "content_security_policy": "script-src 'self'",
  "icons": {
    "128": "icon128.png"
  },
  "permissions": [
    "Contacts",
    "Geolocation",
    "Messaging"
  ],
  "launch_screen": {
    "ready_when": "custom",
    "portrait": {
       "background_color": "#ff0000",
       "background_image": "bgfoo.png 1x, bgfoo-2x.png 2x",
       "image": "foo.png 1x, foo-2x.png 2x",
       "image_border": "30px 40px stretch",
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
  "content_security_policy": "script-src 'self'",
  "icons": {
    "128": "icon128.png"
  },
  "permissions": [
    "Contacts",
    "Geolocation",
    "Messaging"
  ],
  "launch_screen": {
    "ready_when": "custom",
    "portrait": {
       "background_color": "#ff0000",
       "background_image": "bgfoo.png 1x, bgfoo-2x.png 2x",
       "image": "foo.png 1x, foo-2x.png 2x",
       "image_border": "30px 40px stretch",
     }
  },
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

      // any deprecated fields are in grey and have (deprecated) appended
      if (deprecatedAt && checkVersion(crosswalkVersion, deprecatedAt)) {
        text = '<span style="color:gray;">' + text + ' (deprecated)</span>';
      }

      nameElt = elts[i].querySelector('[data-role="field-name"]')

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
      elts[i].style.display = 'block';
    }
    else {
      elts[i].style.display = 'none';
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
