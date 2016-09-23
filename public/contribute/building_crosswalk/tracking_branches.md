# Tracking a different Crosswalk branch

If you want to track a Crosswalk branch other than `master` (e.g. a beta or
stable branch), there are two different approaches you can use, described
below.

## Set the branch before the initial checkout

If you haven't checked out Crosswalk's source code yet, just pass the URL of
the branch you want to checkout to the `gclient config` call. For example, to
track the _crosswalk-2_ branch:

    gclient config --name=src/xwalk \
      https://github.com/crosswalk-project/crosswalk.git@origin/crosswalk-2

## Change the branch for an existing checkout

If you have already cloned Crosswalk and want to change the branch being
tracked, you need to check out a new git branch and then edit your `.gclient`
file.

For example, let's assume you want to track the _crosswalk-2_ branch. First of
all, check out a new branch in your Crosswalk repository:

    cd /path/to/src/xwalk
    git checkout -b crosswalk-2 origin/crosswalk-2

Next, edit your `.gclient` file (generated above) and change the `url` entry.
It should look like this:

```python
"url": "https://github.com/crosswalk-project/crosswalk.git@origin/crosswalk-2",
```

After that, sync your code again:

    gclient sync

