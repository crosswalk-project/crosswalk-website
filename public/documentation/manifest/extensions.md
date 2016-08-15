# Extensions

The `"xwalk_extensions"` field is used to specify the location of [Crosswalk extensions](https://crosswalk-project.org/documentation/android/android_extensions.html). When packaging a crosswalk application with extensions, this field should be added to the manifest to ensure that all the required resources
are included in the package. The content of the field is a list of the paths where the extensions are located.

Here is an example of a manifest file that declares one extension contained in the folder xwalk-echo-extension:

    {
      "name": "app name",
      "start_url": "index.html",
      "xwalk_extensions": ["xwalk-echo-extension"]
    }

The value of `"xwalk-extensions"` is the path to the folder that includes the extension, relative to the location of `"index.html"`. In this example the folder includes the following extensions files:
```ruby
xwalk-echo-extension.jar
xwalk-echo-extension.js
xwalk-echo-extension.json
```
See also the sample code in [crosswalk-samples](https://github.com/crosswalk-project/crosswalk-samples) (folder `extensions-android`) for additional details.

If your application contains many extensions, the `"xwalk_extensions"` field should contain a list of
all the paths where the extensions are located:

    {
      "name": "app name",
      "start_url": "index.html",
      "xwalk_extensions": ["path to extension1", "path to extension2"]
    }
