# xwalk_hosts

The [XMLHttpRequest object](http://www.w3.org/TR/XMLHttpRequest/) is used to interact with remote servers over HTTP (an approach commonly known as [Ajax](http://www.adaptivepath.com/ideas/ajax-new-approach-web-applications/)). Under normal circumstances, such requests are constrained by the [same-origin policy](http://www.w3.org/Security/wiki/Same_Origin_Policy): resources can only be accessed if they reside on the same origin as the script making the request.

If you've attempted to access resources from your own web application, but they are on a different origin, you may have seen this message:

    XMLHttpRequest cannot load http://crosswalk-project.org/. No
    'Access-Control-Allow-Origin' header is present on the requested
    resource. Origin 'file://' is therefore not allowed access.

This hints that it is possible to make such requests, providing the server you are accessing returns the right headers. The 'Access-Control-Allow-Origin' header mentioned here is part of the [cross-origin resource sharing specification](http://www.w3.org/TR/cors/). Cross-origin resource sharing enables a client to make requests to a server on a different origin, providing the client and server negotiate via required headers. However, in practice, most web services you are likely to want to access (unless you control them yourself) will not allow cross-origin resource sharing without authentication.

Crosswalk provides a way to get around these restrictions for applications deployed to Android.

## Allowing cross-origin requests

Crosswalk provides a manifest field, `xwalk_hosts`, which enables an application to make cross-origin requests using XMLHttpRequest on Android. This circumvents same-origin constraints, at the same time avoiding the need to use cross-origin resource sharing.

The field takes an array of URL patterns representing hosts which the application should be able to access. The values can be fully qualified host names, like this:

* `"http://crosswalk-project.org/"`

Or patterns with wild-card characters, such as:

* `"http://*.org/"`
* `"https://*/"`

For example, this manifest would allow access to the Crosswalk website, or any intel.com sub-domains:

    {
      "name": "app name",
      "description": "a sample description",
      "version": "1.0.0",
      "app": {
        "launch": {
          "local_path": "index.html"
        }
      },
      "xwalk_hosts": [
        "http://crosswalk-project.org/",
        "http://*.intel.com/"
      ]
    }

Once you've specified the domains which can be accessed by the application, `XMLHttpRequest` objects can send requests to them as per usual:

    var xhr = new XMLHttpRequest();
    xhr.open("GET", "http://crosswalk-project.org/", true);
    xhr.onreadystatechange = function () {
      if (xhr.readyState == 4) {
        // do something with xhr.responseText
      }
    }
    xhr.send();

Wrappers for Ajax requests which use XMLHttpRequest underneath (such as jQuery's `$.ajax()`) will also be able to access the domains you specified.
