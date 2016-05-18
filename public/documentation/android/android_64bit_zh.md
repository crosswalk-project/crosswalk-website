# 为64设备打包应用

Crosswalk应用默认是打包成32位的。打包基于ARM和x86的64位应用，需要在`crosswakl-pkg`命令中使用`-t <target-archs>`选项：

```
  <target-archs>
    List of CPU architectures for which to create packages.
    Currently supported ABIs are: armeabi-v7a, arm64-v8a, x86, x86_64
    * Prefixes will be matched, so "a","ar", or "arm" yield both ARM APKs
    * Same for "x" and "x8", but "x86" is an exact match, only x86-32 conforms
    * Short-hands "32" and "64" build ARM and x86 for the requested word size
    * Default behavior is equivalent to "32", creation of 32-bit installers
    Example: --targets="arm x86" builds both ARM plus 32-bit x86 packages
```

例如打包基于ARM和x86的64位应用：

```
> crosswalk-pkg -c beta -t 64 /path/to/app
```

打包基于x86的64位应用：

```
> crosswalk-pkg -c beta -t x86_64 /path/to/app
```

打包基于x86架构的32位和64位应用：

```
> crosswalk-pkg -c beta -t x /path/to/app
```

开发者可以将64位的应用上传到谷歌应用商店，谷歌应用商店将会根据用户设备选择相应版本的应用供用户下载。同时，由于64位暂时还未支持stable版本，所以开发者打包64位应用时需要指定Beta或者Canary版本。

如果开发者使用的是Crosswalk共享模式，那么您开发的应用被用户下载时，将会自动下载Crosswalk 64位runtime APK，也就是您在打包应用的时候，不需要做额外的工作。

注意：在Crosswalk Cordova Webview中，64位暂时还不支持，稍后会推出64位版本。
