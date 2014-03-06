# WebGL Sample
<img class='sample-thumb' src='assets/sampapp-icon-webgl.png'>
Ah, the power of WebGL. This sample provides a quick example of integrating ThreeJS into a Crosswalk application.

Follow the steps on [Running an Application](#documentation/getting_started/running_an_application), using ***webgl*** as the source path for either Android or Tizen.

## WebGL on Android
Following the steps from the [Packaging for 
Android](#documentation/getting_started/building_an_application/packaging-for-android), 
you build the WebGL.APK as follows:
```sh
python make_apk.py --package=com.sample.webgl --name=WebGL \
      --app-root=samples/webgl --app-local-path=index.html
```
Then install the APK on Android:
```sh
adb install WebGL.apk
```

## WebGL on Tizen
Following the steps from the [Running on Tizen](#documentation/getting_started/running_an_application/running-on-tizen), you can install and run the WebGL sample as follows:
```sh
sdb push samples/webgl /home/developer/webgl
sdb shell "xwalk --usegl=osmesa /home/developer/webgl/index.html"
```
