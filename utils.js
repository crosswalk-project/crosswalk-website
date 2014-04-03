// simple templating function;
// str: a string like "Hello {name}";
// data: object like {"name": "Elliot"}
function tpl(str, data) {
    return str.replace(/\{([^\}]+)\}/g, function (sub, prop) {
        return data[prop];
    });
}

// async GET for JSON responses only;
// path: path to a file on the domain serving this script;
// cb: function with signature cb(error, responseText)
function asyncJsonGet(path, cb) {
    var xhr = new XMLHttpRequest();
    xhr.timeout = 15000;

    xhr.ontimeout = function () {
        cb(new Error('request timed out for path ' + path));
    }

    xhr.onreadystatechange = function () {
        if (this.readyState == 4 && this.status === 200) {
            cb(null, JSON.parse(this.responseText));
        }
        else if (this.status >= 400) {
            cb(new Error('request for ' + path + ' failed; status was ' +
                         this.status));
        }
    }

    xhr.open('GET', path, true);
    xhr.send();
}

// compare version with basis, returning true if version >= basis;
// version needs the format P.Q.R.S
function isLaterOrEqualVersion(basis, version) {
  var basisParts = basis.split('.');
  var versionParts = version.split('.');

  var ok = true;

  for (var i = 0; i < basisParts.length; i++) {
    if (versionParts[i] < basisParts[i]) {
      ok = false;
      break;
    }
    else if (versionParts[i] > basisParts[i]) {
      break;
    }
  }

  return ok;
}

// get the download URL for a Crosswalk OS/channel/version
// channel: 'stable', 'beta', 'canary'
// OS: 'android-x86', 'android-arm', 'tizen-mobile', 'tizen-ivi',
// 'tizen-emulator'
// version: e.g. '4.32.25.1'
function getXwalkDownloadUrl(OS, arch, channel, version) {
    var file_prefix = 'crosswalk-';

    // tizen emulator downloads are in the same directory as the
    // tizen-mobile ones...
    if (OS === 'tizen-emulator') {
        OS = 'tizen-mobile';
        file_prefix += 'emulator-support-';
    }

    var download_url = 'https://download.01.org/crosswalk/releases/crosswalk/'
                     + OS + '/' + channel + '/' + version + '/';

    // android: crosswalk-beta >= 5.34.104.1 and crosswalk-canary >= 6.34.106.0
    // and crosswalk-stable >= 4.32.76.6
    // will be architecture-independent, so once we move to those we need to
    // remove |arch| from here and the checks below.
    var androidStableArchIndependent = (OS === 'android' &&
                                        channel === 'stable' &&
                                        isLaterOrEqualVersion('4.32.76.6', version));

    var androidBetaArchIndependent = (OS === 'android' &&
                                      channel === 'beta' &&
                                      isLaterOrEqualVersion('5.34.104.1', version));

    var androidCanaryArchIndependent = (OS === 'android' &&
                                        channel === 'canary' &&
                                        isLaterOrEqualVersion('6.34.106.0', version));

    if (OS === 'android' &&
        !(androidBetaArchIndependent || androidCanaryArchIndependent || androidStableArchIndependent)) {
      download_url += arch + '/';
    }

    download_url += file_prefix + version;

    if (OS === 'android') {
      if (androidBetaArchIndependent || androidCanaryArchIndependent || androidStableArchIndependent) {
        download_url += '.zip';
      }
      else if (arch === 'x86') {
        download_url += '-x86.zip';
      }
      else if (arch === 'arm') {
        download_url += '-arm.zip';
      }
    }
    // as of tizen-mobile 5.32.88.0, suffix changed to 686
    else if (OS === 'tizen-ivi' ||
             (OS === 'tizen-mobile' && isLaterOrEqualVersion('5.32.88.0', version))) {
        download_url += '-0.i686.rpm';
    }
    // older tizen-mobile
    else {
        download_url += '-0.i586.rpm';
    }

    return download_url;
}

// version is a full Crosswalk version number, e.g. "4.23.4.2";
// we just take the digits up to the first '.'
function getXwalkMajorVersion(version) {
    var matches = /^(\d+)?\./.exec(version);

    if (matches.length < 2) {
        majorVersion = '0';
    }
    else {
        majorVersion = matches[1];
    }

    return majorVersion;
}

// return a URL for the release notes for majorVersion in the wiki;
// NB this assumes that release notes are given a consistent name
// "Crosswalk N release notes"
function getReleaseNotesUrl(majorVersion) {
    return '#wiki/Crosswalk-' + majorVersion + '-release-notes';
}

// create a static download link based on data in versions.js;
// link needs these attributes set:
// data-os, data-arch, data-channel
function populateStaticLink(link) {
  os = link.getAttribute('data-os');
  arch = link.getAttribute('data-arch');
  channel = link.getAttribute('data-channel');

  // versions.js only knows about 'tizen', not 'tizen-mobile'
  // and 'tizen-ivi' and 'tizen-emulator'...
  versionsOs = os;
  if (/tizen/.test(os)) {
      versionsOs = 'tizen';
  }

  version = versions[channel][versionsOs][arch];

  url = getXwalkDownloadUrl(os, arch, channel, version);
  link.setAttribute('href', url);
  link.innerHTML = version;
}

// populate the static download links (i.e. <a> elements
// with data-role="static-download-link") from data in versions.js
function populateStaticLinks() {
  var sel = 'a[data-role="static-download-link"]';
  var staticLinks = document.querySelectorAll(sel);
  var link, os, arch, channel, versionsOs, url, version;
  for (var i = 0; i < staticLinks.length; i++) {
    populateStaticLink(staticLinks.item(i));
  }
}
