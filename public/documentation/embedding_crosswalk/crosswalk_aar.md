# Developing with Crosswalk AAR

The Crosswalk AAR bundle is the binary distribution of the `xwalk_core_library` and includes both x86 and armv7 architectures. A developer no longer needs to download the crosswalk-webview bundle manually but can specify a version code using either the Gradle or Maven projects.

## Set up host

### Install JDK

Gradle requires JDK version 6 or higher. The JAVA_HOME environment variable must be set to the correct JDK installation directory.

    $ export JAVA_HOME=<path to JDK>

### Install the Android SDK

1.  Download the Android SDK from http://developer.android.com/sdk/index.html.

2.  Extract the SDK from the archive file you downloaded.

3.  Add the Android SDK directories to your PATH:
    ```
    $ export PATH=<path to Android SDK>:$PATH
    $ export PATH=<path to Android SDK>/tools:$PATH
    $ export PATH=<path to Android SDK>/platform-tools:$PATH
    ```

4.  In the SDK Manager window, select the following items from the list:
    ```
    [ ] Tools
      [x] Android SDK Platform-tools 19.0.1
      [x] Android SDK Build tools 18.0.1
    [ ] Android 4.3 (API 18)
      [x] SDK Platform
    ```

    Note: if you are using devices with versions of Android later than 4.3, you should also install the `platform tools`, `build tools` and `SDK platform` for those versions.

### Develop With the Gradle Project

#### Install Gradle

1.  Download a recent version of the `gradle` binary (~40MB): [http://www.gradle.org/downloads/](http://www.gradle.org/downloads)

2.  Unzip it:

        $ unzip gradle-<version>-bin.zip

3.  For convenience, add the `gradle` binary to your PATH environment variable:

        $ export PATH=$PATH:<path to gradle>/bin

### Develop With Maven Project

#### Install Maven

1.  Download Maven from <a href="http://maven.apache.org/download.cgi">http://maven.apache.org/download.cgi</a>. Version 3.2.2 is known to work.

2.  Unzip it:

        $ tar -zxvf apache-maven-3.2.2-bin.tar.gz

3.  Set the Maven enviroment variables to point to the install directory:

        $ M2_HOME=/path/to/apache-maven-3.2.2
        $ export MAVEN_OPTS="-Xms256m -Xmx512m"
        $ export PATH=$M2_HOME/bin:$PATH

#### Install Maven Android SDK Deployer

1.  Checkout Maven Android SDK deployer from [git](https://github.com/mosabua/maven-android-sdk-deployer) 

        $ git clone git@github.com:mosabua/maven-android-sdk-deployer.git

2.  Use the Maven Android SDK deployer to install the Maven components for Android 4.4*:

        $ cd maven-android-sdk-deployer
        $ mvn install -P 4.4

#### Install Maven Android Plugin

1.  Checkout Maven Android Plugin from [git](https://github.com/jayway/maven-android-plugin)
    
        $ git clone git@github.com:jayway/maven-android-plugin.git

2.  Install Maven Android Plugin into your local Maven repository
    
        $ cd maven_android_plugin
        $ mvn clean install

## Crosswalk AAR Version

The AAR remote Maven repository is https://download.01.org/crosswalk/releases/crosswalk/android/maven2/. Versions are available for [stable](https://download.01.org/crosswalk/releases/crosswalk/android/maven2/org/xwalk/xwalk_core_library/) and [beta](https://download.01.org/crosswalk/releases/crosswalk/android/maven2/org/xwalk/xwalk_core_library_beta/). If you need the canary version you can download it:

    $ wget https://download.01.org/crosswalk/releases/crosswalk/android/canary/9.38.207.0/crosswalk-9.38.207.0.aar

Then install it into the local Maven repository:
    
    $ mvn install:install-file -DgroupId=org.xwalk -DartifactId=xwalk_core_library_canary \
          -Dversion=9.38.207.0 -Dpackaging=aar  -Dfile=crosswalk-9.38.207.0.aar \
          -DgeneratePom=true

## Create and Build your Android project with Crosswalk AAR

1.  Create Android Project with the Crosswalk embedding APIs.

    Refer to the section in the Embedding Crosswalk article called [Add code to integrate the webview](/documentation/embedding_crosswalk.html#Add-code-to-integrate-the-webview).

2.  The reference to Maven repo in build.gradle will look like:
    
    ```
    Repositories {
        Maven {
            url 'https://download.01.org/crosswalk/releases/crosswalk/android/maven2'
        }
    }
    ```

    Use <code>mavenLocal()</code> instead of the remote repo url to refer to the local aar:
    ```
    Repositories {
        mavenLocal()
    }
    ```

3. The dependency (a reference) to the AAR library will look like (9.38.208.8 is a crosswalk version):
    
    ```
    Dependencies {
        Compile 'org.xwalk:xwalk_core_library_beta:9.38.208.8'
    }
    ```

   Gradle places the AAR library in its project directory:

    ~/.gradle/caches/modules-2/files-2.1/

4.  Support different CPU architectures with each APK (such as for ARM, x86).
    
    A product flavor defines a customized version of the application build by the project. We can have different flavors which generate apk for each  architecture.

        android {
          ...
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

    Get the version code from the manifest.  Add an extra digit to the end of the version code which implicity specifies the architecture. The x86 final digit is 4, arm is 2.

        versionCode manifest.versionCode + 4

5.  Build your project with Gradle, the following commands will build the corresponding arch apk in build/apk directory.
    
        $ gradle assemblex86
        $ gradle assemblearmv7

    Use `$ gradle build` to build both arm and x86 APKs at once.

## Build with Maven

The Maven android plugin has a known issue that it can't build multiple APKs for different architectures automatically. We need to specify x86/arm aar in classifier. You can try to test from [the sample](/documentation/samples/AAR-Sample.tar.gz)

### Create Maven Project

1.  Create Android Project with the Crosswalk webview APIs.

    Refer to the section in the Embedding Crosswalk article called [Add code to integrate the webview](/documentation/embedding_crosswalk.html#Add-code-to-integrate-the-webview).

2.  The reference to the remote repo in pom.xml will look like:

        <repositories>
          <repository>
            <id>01-org</id>
            <name>o1 org repo</name>
            <url>https://download.01.org/crosswalk/releases/crosswalk/android/maven2</url>
            <layout>default</layout>
          </repository>
        </repositories>

3.  The dependency (a reference) to the x86 AAR library in pom.xml will look like:
    
        <dependency>
          <groupId>org.xwalk </groupId>
          <artifactId> xwalk_core_library_beta</artifactId>
          <version>9.38.208.8</version>
          <classifier>x86</classifier>
          <typr>aar</type>
        </dependency>

    If you want to use arm AAR in your Maven project, you can set classifier to arm:
    
        <classifier>arm</classifier>

    Maven will download the AARs automatically into project repository:

        ~/.m2/repository/

4.  Support multiple APKs

    *  Build different APKs using 'Build profile'

       A Build profile is a set of configuration values which can be used to set or override default values of Maven build.

            <profile>
              <id>x86</id>
            </profile>

      Execute the following mvn command. Pass the profile name as argument using -P option.
        
            $ mvn install -Px86
  
    *  Update manifest versioncode and versionname. Refer to [stackoverflow](http://stackoverflow.com/questions/10803088/how-do-i-change-my-androidmanifest-as-its-being-packaged-by-maven)

       android-maven-plugin support resource filtering to update manifest.

       Modify AndroidManifest.xml:

           <manifest xmlns:android="http://schemas.android.com/apk/res/android"
             package="com.example.xwalkEmbedd"
             android:versionCode="${app.version.code}"
             android:versionName="${app.version.name}"  >

       Set versioncode and versionname.
       
       arm profile:
       
           <app.version.code>1.0.0.2</app.version.code>
           <app.version.name>1.0.0-SNAPSHOT</app.version.name>

       x86 profile:
        
           <app.version.code>1.0.0.4</app.version.code>
           <app.version.name>1.0.0-SNAPSHOT</app.version.name>

       Filter manifest and put filtered file in target/filtered-manifest:

           <resource>
             <directory>${project.basedir}</directory>
             <filtering>true</filtering>
             <targetPath>${project.build.directory}/filtered-manifest</targetPath>
             <includes>
               <include>src/main/AndroidManifest.xml</include>
             </includes>
           </resource>
           ...
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
             ...

    *  Output different name to apk:

            <finalName>${project.artifactId}-${project.version}-${profile-id}</finalName>
            <profile-id>x86</profile-id>
            <profile-id>arm</profile-id>

5.  Build your project with Maven, the following commands will build the corresponding arch apk in target/ directory:

        $ mvn clean install -Px86
        $ adb install ./target/xwalk-aar-example-1.0.0-SNAPSHOT-x86.apk
        $ mvn clean install -Parm
        $ adb install ./target/xwalk-aar-example-1.0.0-SNAPSHOT-arm.apk

## Samples

We have created two sample applications demonstrating Gradle and Maven to get you started:  <a href="/documentation/samples/AAR-Sample.tar.gz">AAR-Sample.tar.gz</a>. 

After downloading, unzip it from the command line:

    $ tar -xzvf AAR-Sample.tar.gz

This will create an `AAR-Sample` directory with two project sub-directories:

&nbsp;&nbsp;   \xwalkEmbed-Gradle &ndash; An example using multiple architecture AAR with Gradle.<br />
&nbsp;&nbsp;   \xwalkEmbed-Maven &ndash; An example using classifier AAR with Maven.
