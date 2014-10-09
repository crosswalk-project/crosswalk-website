# Application entry points

An *entry point* for a web application is the first resource (or set of resources) which should be loaded to "bootstrap" the application when it is first started.

The recommendation is to use a single HTML file as the entry point, and specify it using `start_url` (if available) or `app.launch.local_path`.

The following fields are available for specifying the entry point:

*   `app.launch.local_path`

    This is the preferred field for Crosswalk on Android. It is also compatible with Crosswalk on Tizen.

    It should refer to a single HTML file path, relative to the application root.

*   `start_url`

    This is the preferred field for later versions of Crosswalk on Tizen. It plays the same role as `app.launch.local_path`.

    It should refer to a single HTML file path, relative to the application root.

*   `app.main.source`

    This field is deprecated (as of Crosswalk 7).

    If used, it should refer to a single HTML file path, relative to the application root (similar to `app.launch.local_path`).

*   `app.main.scripts`

    This field is deprecated (as of Crosswalk 7).

    If used, it should refer to an array of JavaScript files which would be loaded into a default empty HTML file, generated at runtime.

## Field precedence

If you are using the deprecated `app` properties, you should bear in mind the rules which govern their precedence:

*   If either or both of `app.main.source` or `app.main.scripts` are specified, they take precedence over `app.local.launch_path`.

*   If both `app.main.source` and `app.main.scripts` are specified, `app.main.source` takes precedence.
