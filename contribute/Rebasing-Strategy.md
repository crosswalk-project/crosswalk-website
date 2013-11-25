## Overall strategy
Crosswalk will adopt the same process as Chromium. A DEPS file will be used to point to the right branch and revision in the Crosswalk 
downstream repo (aka fork) of Chromium (https://github.com/crosswalk-project/chromium-crosswalk).

Before the first public release, chromium-crosswalk will always track the beta channel of Chromium. It is the responsibility of the Crosswalk team to update chromium-crosswalk to reflect the updates of the beta channel in chromium. The site http://omahaproxy.appspot.com/ is a good pointer to track the correct revisions.

The update will happen every week and every six weeks a major version will hit the beta channel which will be the time a potential rebase work will happen as we need to rebase our patches on top of Chromium.

Every DEPS major roll (the 6 weeks one) will be announced on the dev mailing list so that people will be aware of the upcoming changes. How often Crosswalk will be released is yet to be determined; right now we aim towards a 1.0 release.

## Releasing Crosswalk
* Branch the Crosswalk source code into a release branch inside the Crosswalk repository (e.g. v1.0)
* Make sure that the DEPS file in that newly created Crosswalk branch stay attach to the same major version that was used in trunk and wait the beta channel used up until branching time to be promoted stable. Trunk can then move freely forward and rebase/update on a new major revision.
* Roll the QA work in that branch; bug fixing and testing.

## Rebasing process
When Crosswalk trunk branches for release, chromium-crosswalk does the same.
Every week for trunk, we should:
* Branch current chromium-crosswalk trunk to a unique named branch. This new created branch will not evolve anymore, it's just for history recovery since rebase will force-update trunk.
* Do rebase for chromium-crosswalk trunk branch. Rebase it to latest upstream beta and force update chromium-crosswalk trunk branch.
* Update DEPS in Crosswalk trunk to integrate the new rebased chromium.

A major upgrade every six weeks, and patch update for others. Chromium is versioned major.minor.build.patch

Every week for release, we should:
* Branch current chromium-crosswalk release to a unique named branch. Similar to what we need to do for trunk.
* Do rebase for chromium-crosswalk release branch. Rebase it to latest upstream branch and force update chromium-crosswalk release branch. (upstream branch means for example, rebase from 28.0.1500.50 to 28.0.1500.80)
* Update DEPS in Crosswalk release branch to integrate the new rebased chromium.

## Release Criteria
After the branching no new features will be added in release branches, only bug fixes and security fixes will go in both in Crosswalk and chromium-crosswalk. This will reduce potential regressions.

A few days after branching we will release as-is the branch as part of the beta offer even with major bugs opened so we can get early feedback.

Final releases will happen only if no major bugs are open on that given release branch.

## Updating releases
It is the responsibility of the Crosswalk team and the release management to update each stable branches of chromium-crosswalk tracked by Crosswalk releases so that old Crosswalk releases receive security and bug fixes from upstream chromium.

Fixes in Crosswalk will be landed in trunk always and backported in appropriate release branches.

Fixes in our custom patches in Chromium will always be backported in the current “trunk” (the tracked beta channel) and backported to appropriate release branches.

Bug fixes and security fixes aimed for a Crosswalk release but inside Chromium or Blink will be landed upstream and we will wait Google to backport them in the stable channel we’re tracking.

## QA activities
Up until July and the first release it’s safe for the QA to focus on trunk and provide feedback about it.

When we will create the first 1.0 release branch of Crosswalk, QA should focus on the release branch to make sure the quality is good while doing a soft testing on trunk.

In the future ideally we want to make sure QA is focusing more on upcoming release branches.

## What goes inside chromium-crosswalk?
We try to aim upstream as first. If this option is not possible then chromium-crosswalk is the place to land the patch. It will be carefully reviewed and will land there. We need to remember that any patch in there as a high cost of maintenance.

## What to do if we are blocked on new features in Chromium or Blink?
In really rare cases our work depends on something we landed upstream in Chromium or Blink and that is required to move forward in Crosswalk without paying the high cost of maintaining changes in chromium-crosswalk. In the meantime we will allow crosswalk-trunk to track another channel than beta.

This should be communicated to the mailing list and agreed with the community.

## Resources
Before the first release, it will be fine to rely on two persons for updating chromium-crosswalk and rolling the DEPS inside Crosswalk repository. This is a few hours work for one engineer once a week. They can alternate.

After the first release we that it will start to be a dedicated role for a person. Managing the releases branches, backporting, and roll should be done by a given person dedicated doing so.
