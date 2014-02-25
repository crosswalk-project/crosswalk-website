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

// get the download URL for a Crosswalk OS/channel/version
// (see for how they're organised);
// channel: 'stable', 'beta', 'canary'
// OS: 'android-x86', 'android-arm', 'tizen-mobile', 'tizen-ivi',
// 'tizen-emulator'
// version: e.g. "4.32.25.1";
// version is optional; if omitted, the URL points to the parent folder
// rather than a specific download file
function getXwalkDownloadUrl(OS, arch, channel, version) {
    var file_prefix = "crosswalk-";

    // tizen emulator downloads are in the same directory as the
    // tizen-mobile ones...
    if (OS === "tizen-emulator") {
        OS = "tizen-mobile";
        file_prefix += "emulator-support-";
    }

    var download_url = 'https://download.01.org/crosswalk/releases/' + OS;

    if (OS === "android") {
        download_url += "-" + arch
    }

    download_url += "/" + channel + "/";

    if (version) {
        download_url += file_prefix + version;

        if (OS === "android" && arch === "x86") {
            download_url += "-x86.zip";
        }
        else if (OS === "android" && arch === "arm") {
            download_url += "-arm.zip";
        }
        // as of tizen-mobile 5.32.88.0, suffix changed to 686
        else if (OS === "tizen-ivi" || (OS === "tizen-mobile" && channel === "canary")) {
            download_url += "-0.i686.rpm";
        }
        // older tizen-mobile
        else {
            download_url += "-0.i586.rpm";
        }
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
    return "#wiki/Crosswalk-" + majorVersion + "-release-notes";
}
