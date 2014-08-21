# Orientation

The `orientation` field sets the default orientation for the application running under Crosswalk. Even if the physical device is rotated to a different orientation, the application will remain in the orientation set in the manifest. However, the orientation may still be changed at runtime; for example, by using the [screen orientation API](http://www.w3.org/TR/screen-orientation/).

An example manifest setting the default orientation to portrait:

    {
      "name": "app name",
      "description": "a sample description",
      "version": "1.0.0",
      "start_url": "index.html",
      "orientation": "portrait"
    }

Another example, setting the default orientation to landscape:

    {
      "name": "app name",
      "description": "a sample description",
      "version": "1.0.0",
      "start_url": "index.html",
      "orientation": "landscape"
    }

Other values for the `orientation` field can be used: for example, on Crosswalk Android, `"portrait-primary"` refers to the "natural" portrait orientation, with respect to the user (i.e. application in portrait, with the top of the application at the *top* of the device screen); while `"portrait-secondary"` refers to "inverted" portrait orientation (i.e. application in portrait orientation, but with the top of the application at the *bottom* of the device screen). Similarly, `"landscape-primary"` sets "natural" landscape orientation, while `"landscape-secondary"` sets "inverted" landscape orientation. The full range of valid values is defined in [the screen orientation specification](https://w3c.github.io/screen-orientation/#idl-def-OrientationLockType).
