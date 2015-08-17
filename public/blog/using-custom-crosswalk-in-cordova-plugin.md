With the [release of Cordova CLI 5](https://cordova.apache.org/news/2015/04/21/tools-release.html), using Crosswalk in a Cordova application is as easy as installing a plugin. The Crosswalk webview plugin uses the latest stable Crosswalk release by default, but some users have expressed the wish to change this to a different version. Luckily, this is not very difficult to do.

The plugin uses the [Gradle](https://gradle.org/) build system, and expects the Crosswalk library to be packaged as an [AAR bundle](http://tools.android.com/tech-docs/new-build-system/aar-format) and published to a [Maven](https://maven.apache.org/) repository, so changing the Crosswalk version means modifying the build configuration. For the Beta and Stable releases this is very easy, whereas for Canary or to use a custom-built version of Crosswalk it's a little more complicated.

## Specifying a Crosswalk version from the Stable or Beta channels

The Crosswalk Project hosts the official [Maven repository](https://download.01.org/crosswalk/releases/crosswalk/android/maven2/) for Crosswalk, for the Stable and Beta channels. To use any release in either of these channels, it's enough to add one preference in the application's config.xml file.

For example for the Stable channel:

```
<platform name="android">
    ...
    <preference name="xwalkVersion" value="xwalk_core_library:14.43.343.17" />
    ...
</platform>
```

For the Beta channel:

```
<preference name="xwalkVersion" value="xwalk_core_library_beta:15.44.384.4" />
```

It's also possible to specify just the major Crosswalk version, or an open-ended range. See the [plugin documentation on NPM](https://www.npmjs.com/package/cordova-plugin-crosswalk-webview#configure) for more details.

## Specifying a Crosswalk version from the Canary channel

Canary releases are not currently published to the Crosswalk Maven repository, mainly because we don't encourage their use in production applications. If needed, however, they can be installed in a local Maven repository that Gradle can point to.

First of all, you'll need to have Maven installed. On OS X, wich is what I used to test these instructions:

```
brew install maven
```

You'll then need to download the Crosswalk canary AAR from the Crosswalk download site, for example:


```
wget https://download.01.org/crosswalk/releases/crosswalk/android/canary/16.44.389.0/crosswalk-16.44.389.0.aar
```

And install the downloaded file to the local Maven repository:

```
mvn install:install-file -DgroupId=org.xwalk -DartifactId=xwalk_core_library_canary \
      -Dversion=16.44.389.0 -Dpackaging=aar  -Dfile=crosswalk-16.44.389.0.aar \
      -DgeneratePom=true
```

Next, you'll need to change the gradle configuration of the plugin. From your cordova project root folder open the file 

```
platforms/android/cordova-plugin-crosswalk-webview/<appname>.gradle
```

At the beginning of the file, you'll see the Maven repository configuration:

```
Repositories {
    Maven {
        url 'https://download.01.org/crosswalk/releases/crosswalk/android/maven2'
    }
}
```

To use the local Maven repository instead, replace the text above with the following:

```
Repositories {
    mavenLocal()
}
```

Finally, configure the Crosswalk version in config.xml:

```
<platform name="android">
    ...
    <preference name="xwalkVersion" value="xwalk_core_library_canary:16.44.389.0" />
    ...
</platform>
```

The application is now using a canary version of Crosswalk.

## Using a custom-built version of Crosswalk

The instructions for the canary channel are the same also when using a local build of Crosswalk, except that you'll be building the Crosswalk AAR instead of downloading it. To build the Crosswalk AAR, use

```
ninja -C out/Release xwalk_core_library_aar 
```

For more information about building Crosswalk, check [this link](https://crosswalk-project.org/contribute/building_crosswalk.html).