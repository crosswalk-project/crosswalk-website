# display

The `display` field sets the preferred *display mode* for the application: how much of the available display area is occupied by the application, and whether other elements of the user interface (navigation bars, other toolbars) are visible.

At the moment, the following display modes are available:

*   `"fullscreen"`

    In this mode, the application is displayed so that it fills the entire area of the screen, with none of the runtime's UI elements ("chrome") visible.

    Note that specifying the "fullscreen" display mode is distinct from using the [fullscreen API](http://fullscreen.spec.whatwg.org/) within an application: in the former case, you are instructing the runtime to display the application so that it fills the display area; in the latter case, an application can request that one of its UI elements be allowed to fill the display area.

*   `"standalone"` (the default)

    In this mode, the application displays in the same manner as a native application. The standard UI elements from the context (e.g. the device's status bar) remain visible.

See the [W3C spec](http://w3c.github.io/manifest/#display-member) for full details.

## Examples

To give an idea of how this field setting affects the application's appearance, here are some screenshots of the "hello world" application in each mode on a Tizen IVI virtual machine.

**Fullscreen:**

![Application in fullscreen display mode on Tizen IVI](assets/crosswalk-manifest-tizen-fullscreen.png "Fullscreen")

**Standalone (note the device's toolbar):**

![Application in standalone display mode on Tizen IVI](assets/crosswalk-manifest-tizen-standalone.png "Standalone")
