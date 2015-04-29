/*
 * version.js used to dynamically update the content
 * shown on the main site, including the home page and any
 * content loaded from documentation/, contribute/.
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
            x86: "12.41.296.9",
            arm: "12.41.296.9"
        },
        'android-webview': {
            x86: "12.41.296.9",
            arm: "12.41.296.9"
        },
        cordova: {
            x86: "12.41.296.9",
            arm: "12.41.296.9"
        },
        tizen: {
            x86: "3.32.53.4"
        }
    },
    beta: {
        android: {
            x86: "13.42.319.10",
            arm: "13.42.319.10"
        },
        'android-webview': {
            x86: "13.42.319.10",
            arm: "13.42.319.10"
        },
        cordova: {
            x86: "13.42.319.10",
            arm: "13.42.319.10"
        },
        tizen: {
            x86: "4.32.76.2"
        }
    },
    canary: {
      android: {
          x86: "14.43.341.0",
          arm: "14.43.341.0"
      },
      'android-webview': {
          x86: "14.43.341.0",
          arm: "14.43.341.0"
      },
      cordova: {
          x86: "14.43.341.0",
          arm: "14.43.341.0"
      },
      tizen: {
          x86: "14.43.341.0"
      }
    }
};
