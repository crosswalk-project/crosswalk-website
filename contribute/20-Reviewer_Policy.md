## The OWNERS files

The `OWNERS` files are a way of specifying a set of reviewers whose review is required to modify certain areas of code.

When contributing code, for each file modified in a pull request, a "LGTM" review is required from someone listed in the OWNERS file in that directory, or in the OWNERS file in a parent / higher-level directory of that file. That means that the approver must type "lgtm" (case insensitive) into a comment on the pull request.

### Examples 

You want to change code in `xwalk/extensions/browser/xwalk_extension_service.h`.

Either you or one of the change’s reviewers must be an owner of `xwalk/extensions/browser/`.

Who owns `xwalk/extensions/browser`? Everyone named in `xwalk/extensions/browser/OWNERS`. In addition, everyone named in `xwalk/extensions/OWNERS` can review this code, and so on up the directory tree. If no `OWNERS` file exists in the directory containing a modified file, crawl up the hierarchy to find the nearest one. 

Another example:

You want to do a refactoring and make some changes to `XWalkExtension` that require you touch code in many different directories due to a renamed method. You make changes to `xwalk/extensions/common/xwalk_extension.h` and to callsites in `xwalk/experimental`, `xwalk/application`, and elsewhere, across different sub directories. You could either get a LGTM from someone listed in the OWNERS file of each of these 20 subdirectories, or you could get a LGTM from someone listed in `xwalk/OWNERS`.

## Why OWNERS?

OWNERS are people who are intimately familiar with specific areas of code. They have a deep understanding of how the code works, why it was built the way it was and what needs to happen to the code to improve it.

OWNERS are responsible for ensuring the quality of code in their directory remains high and improves over time.

## Who should be in an OWNERS file?

Only the people who are actively investing energy in the improvement of a directory should be listed as OWNERS.

OWNERS are expected to have demonstrated excellent judgment, teamwork and ability to uphold Crosswalk development principles. They must understand the development process.

Additionally, for someone to be listed as an OWNER of a directory they must be approved by the other OWNERS of the affected directory. Some guidelines:

  * already acting as an OWNER, providing high-quality reviews and design feedback
  * have submitted a substantial number of non-trivial changes to the affected directory
  * have committed or reviewed substantial work to the affected directory within the last 90 days
  * have the bandwidth to contribute with other OWNERS to review of code within the affected directory
  * have demonstrated ability to understand how the directory interacts with other parts of Crosswalk

## How to change the OWNERS file?

It is up to OWNERS of a directory (and of its parent directories) to keep the list up to date. OWNERS lists should be as small as possible to encourage better code modularization. When a directory has many hundreds of files, it becomes hard to understand which OWNERS are familiar with which files.

There is a mailing list in Crosswalk dedicated to maintainence of the OWNERS files, in which all OWNERS participate. Before changing an OWNERS file in a directory, one OWNER must first send an email about the change to this list, making it clear the reasons why that change is in accordance with the guidelines above.

To actually make the change, the change must get positive support from at least other two OWNERS. If there is negative feedback on the change, the OWNERS of the parent directory have the final word (up to the `xwalk/OWNERS`).

The email and approval of other OWNERS ensure that guidelines are being followed and serve as notification for other OWNERS. After 7 days with at least two supporters and without negative feedback, a patch with the pull request should be created, and if it's a new OWNER, he/she should merge the pull request to ensure repository permissions are working.