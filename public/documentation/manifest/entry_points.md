# Application entry points

An *entry point* for a web application is the first resource (or set of resources) which should be loaded to "bootstrap" the application when it is first started.

The recommendation is to use a single HTML file as the entry point, and specify it using `start_url`.

`start_url` is part of the [W3C manifest specification](https://w3c.github.io/manifest/#start_url-member).

Example:

    {
      "name": "app name",
      "start_url": "index.html"
    }


