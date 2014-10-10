/*
 * This file can be automatically edited and pushed to the
 * live website through the use of the `./site.sh promote`
 * script.
 *
 * version.js used to dynamically update the content
 * shown on the main site, including the home page and any
 * content loaded from documentation/, contribute/, and wiki/.
 *
 * Client side replacement occurs in xwalk.js replace_version_string
 *
 * To see which pages from the main site are using this replacement:

   grep -ricE '[^!]\${XWALK(-[^-]+){3}}' * | grep -v '0$'

 *
 * The following pattern is replaced:
 *
 * ${XWALK-<CHANNEL>-<PLATFORM>-<ARCH>}
 *
 * To prevent replacement (eg., for a wiki page documenting
 * this process, prefix the ${XWALK...} with an exclamation (!)
 *
 * For example:
 *
 *   crosswalk-${XWALK-BETA-ANDROID-X86}.zip
 *   crosswalk-!${XWALK-BETA-ANDROID-X86}.zip
 *
 * would result in:
 *
 *   crosswalk-2.31.27.0.zip
 *   crosswalk-${XWALK-BETA-ANDROID-X86}.zip
 *
 * See './site.sh promote' for a script to update this file
 * and push it to the website without pushing an entirely new
 * website (eg., without needing to run './site.sh mklive').
 *
 */
var versions = {
    stable: {
        android: {
            x86: "8.37.189.12",
            arm: "8.37.189.12"
        },
        'android-webview': {
            x86: "8.37.189.12",
            arm: "8.37.189.12"
        },
        tizen: {
            x86: "3.32.53.4"
        },
        cordova: {
            x86: "8.37.189.12",
            arm: "8.37.189.12"
        }
    },
    beta: {
        android: {
            x86: "9.38.208.1",
            arm: "9.38.208.1"
        },
        'android-webview': {
            x86: "9.38.208.1",
            arm: "9.38.208.1"
        },
        tizen: {
            x86: "4.32.76.2"
        },
        cordova: {
            x86: "9.38.208.1",
            arm: "9.38.208.1"
        }
    }
};
