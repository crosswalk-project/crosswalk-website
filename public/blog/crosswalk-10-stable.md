Crosswalk 10.39.235.15 is our new stable release.

This release fixes two major issues:

- Updates the SSL implementation to BoringSSL. This solves an issue with the Google Play Store warning about a security vulnerability in the version of OpenSSL previously used by Crosswalk ([XWALK-3217](https://crosswalk-project.org/jira/browse/XWALK-3217))
- Due to some changes to support multiple profiles, upgrading an application to Crosswalk 9 was causing local storage data to disappear. With Crosswalk 10 the data is migrated correctly ([XWALK-3252](https://crosswalk-project.org/jira/browse/XWALK-3252))

Crosswalk 10 is updated to Chromium 39 and introduces the following new features:

- Updated Embedding API (v4), adding the following methods:
  - [getRemoteDebuggingUrl](https://crosswalk-project.org/jira/browse/XWALK-2763)
  - [onReceivedSslError](https://crosswalk-project.org/jira/browse/XWALK-2762)
  - [onCreateWindowRequested](https://crosswalk-project.org/jira/browse/XWALK-2374)
  - [onIconAvailable and onReceivedIcon](https://crosswalk-project.org/jira/browse/XWALK-2373)
- Support for Cordova 3.6.3
- Crosswalk AAR package in Stable and Beta channels for Maven/Gradle integration ([documentation](https://crosswalk-project.org/documentation/embedding_crosswalk/crosswalk_aar.html))
- Remote debugging is disabled by default and needs to be enabled with `--enable-remote-debugging`

Download Crosswalk 10 from the [Stable Channel](https://download.01.org/crosswalk/releases/crosswalk/android/stable/10.39.235.15/) 

***

[Full release notes in Jira](https://crosswalk-project.org/jira/secure/ReleaseNote.jspa?projectId=10001&version=10609)
