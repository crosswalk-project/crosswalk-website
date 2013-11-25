# Contributing Code
This page outlines the process for contributing code to Crosswalk. For information on obtaining the source and building Crosswalk, see the [Building Crosswalk](#contribute/building_crosswalk) page.

## Overview
Below are the recommended steps. Later sections of this page explain each step in more detail.
* Choose or create a bug report to work on.
* Develop your changes.
* Make sure your changes meet the code style guidelines. The check-style script may be of help.
* Run the unit tests.
* Add any new files to your working directory.
* Prepare your commit message.
* Submit your patch for review using the Github pull request system.
* Make any changes recommended by the reviewer.
* Once reviewed, the patch will be landed for you.
* Please watch for any regressions it may have caused (hopefully none)!

More detail about these steps is below.

## Choose a bug report
The github issue tracker is the central point of communication for contributions to Crosswalk. Nearly every contribution corresponds to a bug report there.

Choose a bug report to work on. You can also create a new report. Be sure to search the database before creating new reports to avoid duplication.

If your change may be controversial, you may want to check in advance with the crosswalk-dev mailing list.

## Develop your changes
In addition, make sure that any new source code and script files you introduce contain license text at the beginning of the file. If you are the author of a new file, preferred license text to include can be found in the LICENSE file or any existing file. You need to add yourself to the AUTHORS file the first time you make a patch.

## Code Style Guidelines
Patches must comply with the code style guidelines. Your patch will be automatically checked for style compliance when you upload it to the bug tracker.

### Code Style Guide for Android
* [Crosswalk coding style for Android](Coding-Style-of-XWalk-for-Android)

## Regression tests

A buildbot infrastructure will be available soon. Those buildbots will run the unit tests and the browser tests. You are responsible to keep 
the tree green. If the tree is red because one of your patch, it may be that your commit will be reverted if the other contributors can't reach 
you or can't fix themselves the problem.

## Respond to reviewers
A Crosswalk reviewer must approve your patch before Crosswalk can accept it into the source control repository. A reviewer will typically either approve your patch (by responding with an LGTM in the pull request) or request revisions to your patch. In rare cases a patch may be permanently rejected, meaning that the reviewer believes the feature should never be committed to the tree. The review process can consist of multiple iterations between you and the reviewer as you submit revised patches.

## Obtaining Commit Privileges
By default contributors do not have push rights to Crosswalk repositories. After contributing few patches (~5-10) you may ask an OWNER to nominate you for push access. This privilege is granted with some expectation of responsibility: committers are people who care about the Crosswalk project and want to help them meet their goals. A committer is not just someone who can make changes, but someone who has demonstrated his or her ability to collaborate with the team, get the most knowledgeable people to review code, contribute high-quality code, and follow through to fix issues (in code or tests).

## Obtaining Review Privileges
Our [Reviewer policy](#contribute/Reviewer_Policy) provides details on obtaining review privileges.
