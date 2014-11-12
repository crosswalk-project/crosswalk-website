We are happy to announce that Crosswalk 10 for Android has entered the [Beta Channel](https://download.01.org/crosswalk/releases/crosswalk/android/beta/).

This release is rebased to the latest Chromium Beta (39) and introduces the following new features:

Updated Embedding API (v4), adding the following methods:

- [getRemoteDebuggingUrl](https://crosswalk-project.org/jira/browse/XWALK-2763)
- [onReceivedSslError](https://crosswalk-project.org/jira/browse/XWALK-2762)
- [onCreateWindowRequested](https://crosswalk-project.org/jira/browse/XWALK-2374)
- [onIconAvailable and onReceivedIcon](https://crosswalk-project.org/jira/browse/XWALK-2373)

Support for Cordova 3.6.3

Crosswalk AAR package in Stable and Beta channels for Maven/Gradle integration ([documentation](https://crosswalk-project.org/documentation/embedding_crosswalk/crosswalk_aar.html))

Remote debugging is disabled by default and needs to be enabled with `--enable-remote-debugging` 

***

[Full release notes in Jira](https://crosswalk-project.org/jira/secure/ReleaseNote.jspa?projectId=10001&version=10609)

[Current release blockers to be resolved before promotion to stable](https://crosswalk-project.org/jira/issues/?filter=11712)

You can propose new release blockers by adding the `Release-Blocker` label to a bug.
