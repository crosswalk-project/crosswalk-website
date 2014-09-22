# Developing with Crosswalk AAR

The crosswalk AAR bundle is the binary distribution of xwalk_core_library, it include both x86 and armv7 architecture. Developer don’t need to download the crosswalk-webview bundle manually, only specify a version code with Gradle or Maven project.

Maven will download the AAR automatically into local Maven repository.

    path/to/.m2/repository/

Gradle will cache it in caches directory.

    path/to/.gradle/caches/modules-2/files-2.1/

## Set up host

### Install JDK

Install JDK that version need 6 or higher for Gradle. Then set JAVA_HOME environment to point to the install directory of the desired JDK. Edit your '~/.bashrc' file, adding these lines to the end:

    export JAVA_HOME = <path to JDK>

### Install the Android SDK

1.  Download the Android SDK from http://developer.android.com/sdk/index.html.

2.  Extract the SDK from the archive file you downloaded.

3.  Add the Android SDK directories to your PATH by editing ~/.bashrc:
    ```
    export PATH=<path to Android SDK>:$PATH
    export PATH=<path to Android SDK>/tools:$PATH
    export PATH=<path to Android SDK>/platform-tools:$PATH
    ```

4.  In the SDK Manager window, select the following items from the list:
    ```
    [ ] Tools
      [x] Android SDK Platform-tools 19.0.1
      [x] Android SDK Build tools 18.0.1
    [ ] Android 4.3 (API 18)
      [x] SDK Platform
    ```

    Note that if you are using devices with versions of Android later than 4.3, you should install the platform tools, build tools and SDK platform for those versions too.


### Develop With Gradle Project

#### Install Gadle

The Gradle Wrapper is the preferred way of starting a Gradle build. The wrapper is a batch script on Windows, and a shell script for other operating systems. When you start a Gradle build via the wrapper, Gradle will be automatically downloaded and used to run the build. You can copy following construct files in your project from [my sample](https://github.com/crosswalk-website/documentation/Samples/AAR-Sample.tar.gz).

    RootProject/
      gradlew
      gradlew.bat
      gradle/wrapper/
        gradle-wrapper.jar
        gradle-wrapper.properties

Also you can specify the Gladle version you wish to use. The gradlew command will download the appropriate distribution from the Gradle repository.

    task wrapper(type: Wrapper) {
        gradleVersion = '1.12'
    }

### Develop With Maven Project

#### Install Maven

1.  Download Maven from maven.apache.org/download.cgi

    Version 3.2.2 is Know to work.

2.  Unzip it

    $ tar -zxvf apache-maven-3.2.2-bin.tar.gz

3.  Set maven enviroment to point to the install directory of the maven. Edit your '~/.bashrc' file, adding these lines to the end:

    M2_HOME=/path/to/apache-maven-3.2.2
    
    export MAVEN_OPTS="-Xms256m -Xmx512m"
    
    export PATH=$M2_HOME/bin:$PATH

4.  Refresh your PATH variable in the shell:

    source ~/.bashrc

#### Install Maven Android SDK deployer

1.  Checkout Maven Android SDK deployer from [git](https://github.com/mosabua/maven-android-sdk-deployer) 

    git clone git@github.com:mosabua/maven-android-sdk-deployer.git

2.  Use the Maven Android SDK deployer to install the Maven components for Android 4.4*:

    cd maven-android-sdk-deployer

    mvn install -P 4.4

#### Install Maven Android Plugin

1.  Checkout Maven Android Plugin from [git](https://github.com/jayway/maven-android-plugin)
    
    git clone git@github.com:jayway/maven-android-plugin.git

2.  Install Maven Android Plugin into your local Maven repository
    
    cd maven_android_plugin

    mvn clean install

## Crosswalk AAR Version

The AAR remote maven repository is https://download.01.org/crosswalk/releases/crosswalk/android/maven2/. It hosts both stable and beta in maven repository, if you want to use canary version, you can download it.

    $ wget https://download.01.org/crosswalk/releases/crosswalk/android/canary/9.38.207.0/crosswalk-9.38.207.0.aar

Then install it in local maven repository for using.
    
    mvn install:install-file -DgroupId=org.xwalk -DartifactId=xwalk_core_library -Dversion=9.38.207.0 -Dpackaging=aar  -Dfile=crosswalk-9.38.207.0.aar -DgeneratePom=true

All of the stable version you want from [the xwalk_core_library](https://download.01.org/crosswalk/releases/crosswalk/android/maven2/org/xwalk/xwalk_core_library/).

All of the beta version you want from [the xwalk_core_library_beta](https://download.01.org/crosswalk/releases/crosswalk/android/maven2/org/xwalk/xwalk_core_library_beta/).

## Create and Build your Android project with Crosswalk AAR

1.  Create Android Project with the Crosswalk webview APIs.

    Refer to "Add code to integrate the webview" steps from [Embedding Crosswalk](#documentation/embedding_crosswalk).

2.  The reference to maven repo in build.gradle will look like:
    
    ```
    Repositories {
        Maven {
            url ‘https://download.01.org/crosswalk/releases/crosswalk/android/maven2’
        }
    }
    ```

3. The dependency (a reference) to the AAR library will look like(9.38.206.0 is crosswalk version):
    
    ```
    Dependencies {
        Compile ‘org.xwalk:xwalk_core_library:9.38.206.0’
    }
    ```

4.  Support different CPU architectures with each APK (such as for ARM, x86).
    
    A product flavor defines a customized version of the application build by the project. We can have different flavors which generate apk for each  architecture.

    ```
    android {
        ....

        productFlavors {
            armv7 {
                ndk {
                    abiFilters "armeabi-v7a", ""
                }
            }
            x86 {
                ndk {
                    abiFilters "x86", ""
                }
            }
        }
    }
    ```

    Get Version code from Manifest, Add an extra digit to the end of the version code, which implicity specifies the architecture. The x86 final digit is 4, arm is 2.

    ```
    versionCode manifest.versionCode + 4
    ```

5.  Build your project with Gradle, the following commands will build the corresponding arch apk in build/apk directory.
    
    > ./gradlew assemblex86
    
    > ./gradlew assemblearmv7

## Build with Maven

The Maven android plugin have an know issue that can’t build multiple APK for different architectures automatically. We need to specify x86/arm aar in classifier. You can try to test from [my sample](https://github.com/crosswalk-website/documentation/Samples/AAR-Sample.tar.gz)

### Create Maven Project

1.  Create Android Project with the Crosswalk webview APIs.

    Refer to "Add code to integrate the webview" steps from [Embedding Crosswalk](#documentation/embedding_crosswalk).

2.  The reference to the remote repo in pom.xml will look like:

    ```
    <repositories>
      <repository>
        <id>01-org</id>
        <name>o1 org repo</name>
        <url>https://download.01.org/crosswalk/releases/crosswalk/android/maven2</url>
        <layout>default</layout>
      </repository>
    </repositories>
    ```

3.  The dependency (a reference) to the x86 AAR library in pom.xml will look like:
    
    ```
    <dependency>
      <groupId>org.xwalk </groupId>
      <artifactId> xwalk_core_library</artifactId>
      <version>9.38.206.0</version>
      <classifier>x86</classifier>
      <typr>aar</type>
    </dependency>
    ```

    If you want to use armeabi-v7a AAR in your maven project, you can set classifier to armeabi-v7a:
    
    ```
    <classifier>armeabi-v7a</classifier>
    ```

4.  Support multiple APK

    *  Build different apk using 'Build profile'

       A Build profile is a set of configuration values which can be used to set or override default values of Maven build.
    
       ```
       <profile>
           <id>x86</id>
       <profile>
       ```
       Execute the following mvn command. Pass the profile name as argument using -P option.
        
       ```
       mvn install -Px86
       ```
    *  Update manifest versioncode and versionname. Refer to [stackoverflow](http://stackoverflow.com/questions/10803088/how-do-i-change-my-androidmanifest-as-its-being-packaged-by-maven)

       android-maven-plugin support resource filtering to update manifest.

       Modify AndroidManifest.xml.
       ```
        <manifest xmlns:android="http://schemas.android.com/apk/res/android"
          package="com.example.xwalkEmbedd"
          android:versionCode="${app.version.code}"
          android:versionName="${app.version.name}"
          >
       ```
       Set versioncode and versionname. looks like:
       
       arm profile:
       ```
       <app.version.code>1.0.0.2</app.version.code>
       <app.version.name>1.0.0-SNAPSHOT</app.version.name>

       ```
       x86 profile:
       ```
       <app.version.code>1.0.0.4</app.version.code>
       <app.version.name>1.0.0-SNAPSHOT</app.version.name>

       ```

       Filter manifest and put filtered file in target/filtered-manifest.
       ```
       <resource>
          <directory>${project.basedir}</directory>
          <filtering>true</filtering>
          <targetPath>${project.build.directory}/filtered-manifest</targetPath>
          <includes>
            <include>src/main/AndroidManifest.xml</include>
          </includes>
       </resource>

       ... ...

      <plugins>
        <plugin>
          <groupId>com.jayway.maven.plugins.android.generation2</groupId>
          <artifactId>android-maven-plugin</artifactId>
          <extensions>true</extensions>
          <configuration>
            <undeployBeforeDeploy>true</undeployBeforeDeploy>
            <!-- tell build process to use filtered manifest -->
            <androidManifestFile>${project.build.directory}/src/main/filtered-manifest/AndroidManifest.xml</androidManifestFile>
          </configuration>
        </plugin>
       ... ...
       ```

    *  Output different name to apk.
       ```
       <finalName>${project.artifactId}-${project.version}-${profile-id}</finalName>

       <profile-id>x86</profile-id>
       <profile-id>arm</profile-id>
       ```
5.  Build your project with maven, the following commands will build the corresponding arch apk in target/ directory. 
    > mvn install -Px86

    > mvn install -Parmeabi-v7a

## Samples

We have packaged some simple applications to get you up. These applications are <a href="https://github.com/crosswalk-website/documentation/Samples/AAR-Sample.tar.gz">available as a download</a>. 

To use the samples, download the sample archive, then unpack it from the command line:

```sh
tar -xzvf AAR-Sample.tar.gz
```

This will create a `AAR-Sample` directory with several project sub-directories:

*   xwalkEmbed-Gradle &ndash; An example of using multiple architecture AAR with Gradle.

*   xwalkEmbed-Maven &ndash; An example of using classifier AAR with Maven.
