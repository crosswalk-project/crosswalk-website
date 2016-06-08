# Crosswalk AAR开发篇

Crosswalk AAR组件是`xwalk_core_library`的二进制发布形式并且它包含了x86和ARMv7两种架构。开发者不再需要手动下载crosswalk-webview组件，可以使用Gradle或者Maven工程去指定一个版本自动下载。

## 配置主机

### 安装JDK

Gradle需要JDK 6或者更高的版本。环境变量JAVA_HOME必须设置成正确的JDK安装路径。

    $ export JAVA_HOME=<path to JDK>

### 安装Android SDK

1.  从http://developer.android.com/sdk/index.html下载Android SDK。

2.  从下载文件中提取SDK。

3.  添加Android SDK路径到您的PATH:
    ```
    $ export PATH=<path to Android SDK>:$PATH
    $ export PATH=<path to Android SDK>/tools:$PATH
    $ export PATH=<path to Android SDK>/platform-tools:$PATH
    ```

4.  在SDK Manager窗口中，在列表中选择下面的条目：
    ```
    [ ] Tools
      [x] Android SDK Platform-tools 19.0.1
      [x] Android SDK Build tools 18.0.1
    [ ] Android 4.3 (API 18)
      [x] SDK Platform
    ```

    注意：如果您的安卓版本高于4.3，那么您需要下载对应版本的`platform tools`，`build tools`和`SDK platform`。

### 使用Gradle工程开发

#### 安装Gradle

1.  下载最新版本的`gradle`二进制文件(~40MB): [http://www.gradle.org/downloads/](http://www.gradle.org/downloads)

2.  解压：

        $ unzip gradle-<version>-bin.zip

3.  方便起见，添加`gradle`到您的环境变量PATH中：

        $ export PATH=$PATH:<path to gradle>/bin

### 使用Maven工程开发

#### 安装Maven

1.  从<a href="http://maven.apache.org/download.cgi">http://maven.apache.org/download.cgi</a>下载Maven。版本3.2.2是被验证过可以工作的。

2.  解压：

        $ tar -zxvf apache-maven-3.2.2-bin.tar.gz

3.  将Maven环境变量设置成安装目录：

        $ M2_HOME=/path/to/apache-maven-3.2.2
        $ export MAVEN_OPTS="-Xms256m -Xmx512m"
        $ export PATH=$M2_HOME/bin:$PATH

#### 安装Maven Android SDK Deployer

1.  使用[git](https://github.com/mosabua/maven-android-sdk-deployer)获取Maven Android SDK deployer

        $ git clone git@github.com:mosabua/maven-android-sdk-deployer.git

2.  使用Maven Android SDK deployer为安卓4.4*安装Maven组件：

        $ cd maven-android-sdk-deployer
        $ mvn install -P 4.4

#### 安装Maven Android插件

1.  使用[git](https://github.com/jayway/maven-android-plugin)获取Maven Android插件
    
        $ git clone git@github.com:jayway/maven-android-plugin.git

2.  安装Maven Android插件到您本地的Maven仓库
    
        $ cd maven_android_plugin
        $ mvn clean install

## Crosswalk AAR版本

AAR的远程Maven仓库在https://download.01.org/crosswalk/releases/crosswalk/android/maven2/。 [stable](https://download.01.org/crosswalk/releases/crosswalk/android/maven2/org/xwalk/xwalk_core_library/)和[beta](https://download.01.org/crosswalk/releases/crosswalk/android/maven2/org/xwalk/xwalk_core_library_beta/)版是可用的。如果您需要canary版本，您可以使用下面的命令下载：

    $ wget https://download.01.org/crosswalk/releases/crosswalk/android/canary/9.38.207.0/crosswalk-9.38.207.0.aar

然后安装Crosswalk AAR到本地Maven仓库：
    
    $ mvn install:install-file -DgroupId=org.xwalk -DartifactId=xwalk_core_library_canary \
          -Dversion=9.38.207.0 -Dpackaging=aar  -Dfile=crosswalk-9.38.207.0.aar \
          -DgeneratePom=true

## 用Crosswalk AAR创建并编译安卓工程

1.  使用Crosswalk Embedding APIs创建安卓工程。

    参考嵌入模式中的章节[添加代码集成Webview](/documentation/android/embedding_crosswalk_zh.html#Add-code-to-integrate-the-webview)。

2.  在build.gradle文件中关于Maven仓库的代码如下：
    
    ```
    Repositories {
        Maven {
            url 'https://download.01.org/crosswalk/releases/crosswalk/android/maven2'
        }
    }
    ```

    使用<code>mavenLocal()</code>替代上面的远程仓库链接，指向本地的aar：
    ```
    Repositories {
        mavenLocal()
    }
    ```

3. AAR库的依赖关系如下所示(9.38.208.8是crosswalk版本)：
    
    ```
    Dependencies {
        Compile 'org.xwalk:xwalk_core_library_beta:9.38.208.8'
    }
    ```

   在build.gradle文件中，最新版本的AAR需要把compileSdkVersion设置成21。

   Gradle把AAR库放到工程中下面的位置：

    ~/.gradle/caches/modules-2/files-2.1/

4.  支持不同架构的CPU(例如ARM，x86)。
    
    开发者可以定制不同版本的应用。我们可以通过配置产生不同架构下的多个APK。

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

    通过manifest获取版本号。在版本号的最后添加一个指定架构的数字。数字4代表x86，2代表ARM。

        versionCode manifest.versionCode + 4

5.  使用Gradle编译工程，使用下面的命令可以在build/apk目录下产生相应架构的APK。
    
        $ gradle assemblex86
        $ gradle assemblearmv7

    使用`$ gradle build`同时产品ARM和x86架构的APK。

## 使用Maven编译

Maven android插件有一个问题，不同自动编译不同架构下的APK。我们需要在classifier中指定x64/aar。您可以尝试[示例](/documentation/samples/AAR-Sample.tar.gz)。

### 创建Maven工程

1.  使用Crosswalk webview APIs创建安卓工程

    参考嵌入模式中的章节[添加代码集成webview](/documentation/android/embedding_crosswalk_zh.html#Add-code-to-integrate-the-webview)。

2.  在pom.xml中指定远程仓库的代码如下：

        <repositories>
          <repository>
            <id>01-org</id>
            <name>o1 org repo</name>
            <url>https://download.01.org/crosswalk/releases/crosswalk/android/maven2</url>
            <layout>default</layout>
          </repository>
        </repositories>

3.  在pom.xml中指定x86 AAR库的依赖关系代码如下：
    
        <dependency>
          <groupId>org.xwalk </groupId>
          <artifactId> xwalk_core_library_beta</artifactId>
          <version>9.38.208.8</version>
          <classifier>x86</classifier>
          <typr>aar</type>
        </dependency>

    如果您需要在Maven工程中使用ARM AAR，可以设置classifier的值为arm：
    
        <classifier>arm</classifier>

    Maven将会自动下载AARs到工程仓库中：

        ~/.m2/repository/

4.  支持多个APK。

    *  使用'Build profile'编译不同的APK

       编译文件是很多用于配置的元素组成。这些元素用来设置或者覆盖Maven编译时的默认值。

            <profile>
              <id>x86</id>
            </profile>

      执行下面的mvn命令。使用-P选项传入CPU架构的属性值。
        
            $ mvn install -Px86
  
    *  更新manifest的versioncode和versionname。参考[stackoverflow](http://stackoverflow.com/questions/10803088/how-do-i-change-my-androidmanifest-as-its-being-packaged-by-maven)

       android-maven-plugin支持esource filtering，更新manifest。

       修改AndroidManifest.xml：

           <manifest xmlns:android="http://schemas.android.com/apk/res/android"
             package="com.example.xwalkEmbedd"
             android:versionCode="${app.version.code}"
             android:versionName="${app.version.name}"  >

       设置versioncode和versionname。
       
       ARM情况下：
       
           <app.version.code>1.0.0.2</app.version.code>
           <app.version.name>1.0.0-SNAPSHOT</app.version.name>

       x86情况下：
        
           <app.version.code>1.0.0.4</app.version.code>
           <app.version.name>1.0.0-SNAPSHOT</app.version.name>

       过滤manifest并且把过滤的文件放到target/filtered-manifest:

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

    *  输出不同名字的APK：

            <finalName>${project.artifactId}-${project.version}-${profile-id}</finalName>
            <profile-id>x86</profile-id>
            <profile-id>arm</profile-id>

5.  使用Maven编译工程，使用下面的命令将会在target/目录下产生相应架构的APK：

        $ mvn clean install -Px86
        $ adb install ./target/xwalk-aar-example-1.0.0-SNAPSHOT-x86.apk
        $ mvn clean install -Parm
        $ adb install ./target/xwalk-aar-example-1.0.0-SNAPSHOT-arm.apk

## 示例

您可以从<a href="https://github.com/crosswalk-project/crosswalk-samples/tree/master/embedded-gradle">crosswalk示例</a>获取Crosswalk库的示例程序，Crosswalk库是在Android Studio中被使用的。示例程序使用Maven中的classifier AAR，您可以从<a href="/documentation/samples/xwalkEmbed-Maven.tar.gz">xwalkEmbed-Maven.tar.gz</a>获取。

下载完成之后，使用命令解压：

    $ tar -xzvf xwalkEmbed-Maven.tar.gz

