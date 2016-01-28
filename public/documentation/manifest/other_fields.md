# Other fields

Two more manifest extensions, `xwalk_description` and `xwalk_version` are specified by the Crosswalk manifest to add application specific metadata.

- `xwalk_version`: the version of the application. If present, it will be included in the package name.
- `xwalk_description`: a description of the application. At present this field has no effect on the packaged application.
- `xwalk_bounds`: Window size configuration in manifest. Desktop application only.

Example:

    {
      "name": "app name",
      "start_url": "index.html",
      "orientation": "portrait",
      "xwalk_version": "1.0.0",
      "xwalk_description": "A sample application",
      "xwalk_bounds": {
          "width": 500,
          "height": 600,
          "min-width": 250,
          "min-height": 300,
          "max-width": 800,
          "max-height": 1000
      }
    }
