# Extensions

`"xwalk_extensions"` is used to specify extension in crosswalk application. When package a crosswalk application with extension, you should add extensions in manifest file.


A manifest file with one extension named xwalk-echo-extension:

    {
      "name": "app name",
      "start_url": "index.html",
      "xwalk_extensions": ["xwalk-echo-extension"]
    }

Note, content in `"xwalk-extensions"` is the path which includes extensions, `"xwalk-echo-extension"` in the above is in the same folder as `"index.html"`, it includes the following extensions files:
```ruby
xwalk-echo-extension.jar
xwalk-echo-extension.js
xwalk-echo-extension.json
```
Please refer to [crosswalk-samples](https://github.com/crosswalk-project/crosswalk-samples)(in extensions-android folder) if you want more details.

If your application contains many extentions, there should be many extensions's paths in `"xwalk_extensions"`:

    {
      "name": "app name",
      "start_url": "index.html",
      "xwalk_extensions": ["path to extension1", "path to extension2"]
    }

