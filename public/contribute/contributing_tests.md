# Contributing Tests
This page outlines the process for contributing test cases or test suites to Crosswalk.  The process follows [contributing code](/contribute/contributing-code.html) but with more details as follows.

For information on obtaining the source code, see [Crosswalk Test Suite](/documentation/qa/test_suite.html).


## Overview

Everyone is welcome and even encouraged to contribute test cases and test suites. 

These are the recommended steps for contributing test cases to Crosswalk Test Suite:

* Fork [Crosswalk Test Suite repository](https://github.com/crosswalk-project/crosswalk-test-suite) (and make sure you're still relatively in sync with it if you forked a while ago).
* Create a new branch for your changes.
* Develop your changes.
* Make sure your changes meet the code style guidelines. The check-style script may be of help.
* Run the unit tests.
* Add any new files to your working directory.
* Prepare your commit message.
* Submit your patch for review using the Github pull request system.
* Make any changes recommended by the reviewer.
* Once reviewed, the patch will be landed for you.

More detail about some of these steps is below:

    $ git clone https://github.com/crosswalk-project/crosswalk-test-suite.git
    $ git checkout -b topic
    $ git add file1 file2 ... fileN 
    $ git commit (Follow "Commit message guidelines" section below to add commit messages)
    $ git push origin topic

## Choose a bug report

The [Jira issue tracker](https://crosswalk-project.org/jira/) is the central point of communication for contributions to Crosswalk. For test suite bugs and tasks, you can choose the [Crosswalk Test Suite component](https://crosswalk-project.org/jira/browse/XWALK/component/10303), choose an issue to work on, or create a new one if no suitable issue exists. To avoid duplication, be sure to search the database before creating new issues.

You should note the ID of the issue you work on, so you can include it in your test case PR, if you need to declare one (see below). The ID is the **XWALK-N** identifier at the end of the URL for the issue: for example, for https://crosswalk-project.org/jira/browse/XWALK-4782, the ID is **XWALK-4782**.


## Code style guidelines

Patches must comply with the code style guidelines. See the [Crosswalk Test Suite coding style](https://github.com/crosswalk-project/crosswalk-test-suite/blob/master/doc/Coding_Style_Guide_CheatSheet.md) for details.


## Commit message guidelines

Your commits and/or PRs should reference the ID of the issue you are working on. Please refer to "Commit message guidelines" section in [contributing code](/contribute/contributing-code.html) page. 

It's better to follow the commit message template for contributing test cases:


    [area prefix] Capitalized, short (50 chars or less) summary

    More detailed explanatory text, if necessary.  Wrap it to about 72
    characters or so.  In some contexts, the first line is treated as the
    subject of an email and the rest of the text as the body.  The blank
    line separating the summary from the body is critical (unless you omit
    the body entirely); tools like rebase can get confused if you run the
    two together.

    Failure analysis goes here; describe possible reasons for the failed.
    If refer to a bug, please use full URL.

    Impacted tests(designed|approved): new 0, update 5, delete 0
    Unit test platform: Crosswalk Project for <Android|IoT|Linux|Windows>; <version>
    Unit test result summary: pass 5, fail 0, block 0

    BUG=https://crosswalk-project.org/jira/browse/XWALK-4782

## Respond to reviewers

[Crosswalk Test Suite repository](https://github.com/crosswalk-project/crosswalk-test-suite) reviewer will approve your patch when the PR is reviewed. A reviewer will either approve your patch by responding with "LGTM" in the pull request, or request revisions to your patch. The review process can consist of multiple iterations between you and the reviewer as you submit revised patches.


## License and author name

Except as noted in COPYING and/or NOTICE files, or as headed with license info, Crosswalk Test Suite source code uses a BSD-style license that can be found in the LICENSE file.

Make sure that any new source code you introduce contains the appropriate license text at the beginning of the file. If you are the author of a new file, the preferred license text to include can be found in the LICENSE file or any existing file.

You should also add your name to the AUTHORS file the first time you make a patch. Please see the [Crosswalk test templates](https://github.com/crosswalk-project/crosswalk-test-suite/blob/master/tools/template/).
 
