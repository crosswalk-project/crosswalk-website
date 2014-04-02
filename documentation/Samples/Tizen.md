# Tizen APIs sample

<img class='sample-thumb' src='assets/sampapp-icon-api.png'>

A sample application leveraging a Crosswalk extension which implements
some of the Tizen APIs. This sample demonstrates usage of the
`tizen.systeminfo` API.

To use this example, you need to run the application on a Tizen device
or in the Tizen emulator. Instructions for preparing both are in
the [Getting started](#documentation/getting_started) tutorial.

The following steps use the helper script **tizen-extension-crosswalk**
to load the Tizen extension API into Crosswalk during load time.

```sh
> sdb push samples/tizen_apis /home/developer/tizen_apis
> sdb shell "tizen-extensions-crosswalk /home/developer/tizen_apis/index.html"
```
