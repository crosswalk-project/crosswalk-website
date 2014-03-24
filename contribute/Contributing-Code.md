# Contributing Code
This page outlines the process for contributing code to Crosswalk. For information on obtaining the source and building Crosswalk, see [Building Crosswalk](#contribute/building_crosswalk).

## Overview
These are the recommended steps for contributing code to Crosswalk:

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

More detail about some of these steps is below.

## Choose a bug report

The [Jira issue tracker](https://crosswalk-project.org/jira/) is the central point of communication for contributions to Crosswalk. Nearly every contribution corresponds to an issue there.

Choose an issue to work on, or create a new one if no suitable issue exists. To avoid duplication, be sure to search the database before creating new issues.

If your change is possibly controversial, you may want to ask for advice on how to proceed, by using the [crosswalk-dev mailing list or #crosswalk IRC channel](#contribute/overview).

## Develop your changes

Make sure that any new source code you introduce contains the appropriate license text at the beginning of the file. If you are the author of a new file, the preferred license text to include can be found in the LICENSE file or any existing file.

You should also add your name to the AUTHORS file the first time you make a patch.

## Code style guidelines

Patches must comply with the code style guidelines. Your patch will be automatically checked for style compliance when you upload it to the bug tracker.

### Code style guide for Android
* [Crosswalk coding style for Android](Coding-Style-of-XWalk-for-Android)

## Commit message guidelines

Your commits and/or pull requests should reference the *ID* of the issue you are working on. The ID is the **XWALK-N** identifier at the end of the URL for the issue: for example, for https://crosswalk-project.org/jira/browse/XWALK-898, the ID is **XWALK-898**.

You can use issue IDs in commit messages and pull requests in the following ways:

*   To refer to an issue from a pull request (PR), include the issue ID in the PR's description. This will result in a mention of the PR in the corresponding Crosswalk Jira issue.

    For example, if you were working on issue XWALK-898, you could reference the issue in a commit message like this:

        Append "-tests" to package name, as per XWALK-898.

    Only opening and closing a pull request (with an issue ID in the description) will result in a comment being added to Jira. Updating a pull request will *not* update related Jira issues.

*   To close a related Jira issue, add a line with the format **BUG=XWALK-N** to a PR description (NB this is case sensitive). Doing this will resolve the corresponding issue in Jira when the PR is merged. Using the issue URL (**BUG=https://path/to/issue/XWALK-N**) is also acceptable.

    For example, if your PR closes issue XWALK-898, you could include this in the PR description:

        BUG=XWALK-898

    or this:

        BUG=https://crosswalk-project.org/jira/browse/XWALK-898

    If a PR doesn't completely fix an issue, do not use the "BUG=" prefix, but do mention the issue ID. Only use "BUG=" when the PR fixes the issue.

    If a PR fixes multiple issues, reference them on separate lines of the description, starting each with "BUG=".

Note that although the prefix is "BUG=", this mechanism applies to features and tasks as well.

## Regression tests

A buildbot infrastructure will be available soon. Those buildbots will run the unit tests and the browser tests.

You are responsible for keeping the tree green. If the tree is red because of your patch, it may be that your commit will be reverted if the other contributors can't reach you or can't fix the problem themselves.

## Respond to reviewers

A Crosswalk reviewer must approve your patch before Crosswalk can accept it into the source control repository. A reviewer will either approve your patch by responding with "LGTM" (Looks Good To Me) in the pull request, or request revisions to your patch. In rare cases, a patch may be permanently rejected, meaning that the reviewer believes the feature should never be committed to the tree. The review process can consist of multiple iterations between you and the reviewer as you submit revised patches.

## Obtaining commit privileges

By default, contributors do not have push rights to Crosswalk repositories. After contributing a few patches (~5-10) you may ask an OWNER to nominate you for push access. This privilege is granted with some expectation of responsibility: committers are people who care about the Crosswalk project and want to help it meet its goals. A committer is not just someone who can make changes, but someone who has demonstrated his or her ability to collaborate with the team, get the most knowledgeable people to review code, contribute high-quality code, and follow through to fix issues (in code or tests).

## Obtaining review privileges

Our [Reviewer policy](#contribute/Reviewer_Policy) provides details on obtaining review privileges.
