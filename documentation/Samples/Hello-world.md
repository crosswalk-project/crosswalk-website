# Hello World
<img class='sample-thumb' src='assets/sampapp-icon-helloworld.png'>
The smallest of applications--Hello, World! This sample provides a manifest.json and a minimal set of HTML files to start an application from the ground up.

Follow the steps on [Running an Application](#documentation/getting_started/running_an_application), using ***hello_world*** as the source path for either Android or Tizen.

## Hello World on Android
Following the steps from the [Packaging for 
Android](#documentation/getting_started/building_an_application/packaging-for-android), 
you build 
the HelloWorld.apk as follows:
```sh
python make_apk.py --package=com.sample.hello_world --name=HelloWorld \
           --app-root=samples/hello_world --app-local-path=index.html
```
Then install the APK on Android:
```sh
adb install HelloWorld.apk
```

## Hello World on Tizen
Following the steps from the [Running on Tizen](#documentation/getting_started/running_an_application/running-on-tizen), you can install and run the Hello World sample as follows:
```sh
sdb push samples/hello_world /home/developer/hello_world
sdb shell "xwalk /home/developer/hello_world/index.html"
```
