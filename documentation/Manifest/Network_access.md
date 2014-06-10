# Network access

The [XMLHttpRequest object](http://www.w3.org/TR/XMLHttpRequest/) is used to interact with remote servers over HTTP (an approach commonly known as [Ajax](http://www.adaptivepath.com/ideas/ajax-new-approach-web-applications/)). Under normal circumstances, such requests are constrained by the [same-origin policy](http://www.w3.org/Security/wiki/Same_Origin_Policy): resources can only be accessed if they reside on the same origin as the script making the request.

If the requesting script and the resource being accessed have different origins, [cross-origin resource sharing](http://www.w3.org/TR/cors/) may come into effect. This enables a client to make requests to a server on a different origin, providing the client and server negotiate via required headers. However, in practice, most web services you are likely to want to access (unless you control them yourself) will not allow cross-origin resource sharing without authentication.

Crosswalk provides a way to get around this restrictions for applications deployed to Android.

## `xwalk_hosts`

Crosswalk provides a manifest field, `xwalk_hosts`, which enables an application to make cross-origin requests using XMLHttpRequest on Android. This circumvents same-origin constraints, at the same time avoiding the need to use cross-origin resource sharing.









## Other approaches

???do I need this???





## Requesting cross-origin permissions

This is the error you're likely to see if you attempt XMLHttpRequest on a site which doesn't allow cross-origin requests:

XMLHttpRequest cannot load http://crosswalk-project.org/. No 'Access-Control-Allow-Origin' header is present on the requested resource. Origin 'file://' is therefore not allowed access.




By adding hosts or host match patterns to the "xwalk_hosts" list in manifest.json, you applications can request access to remote servers outside of its origin.

```
{
  ...
  "xwalk_hosts": [
    "http://crosswalk-project.org/*"
  ],
  ...
}
```

"xwalk_hosts" valus can be fully qualified host names, like:

* "http://crosswalk-project.org/"

Or they can be match patterns, like:

* "http://*.org/"
* "https://*/"

## Send Cross-Origin XHR
After adding permissions to manifest.json, you can access the target URL directly with XMLHttpRequest Object.

```
var xhr = new XMLHttpRequest();
xhr.open("GET", "http://crosswalk-project.org/", true);
xhr.onreadystatechange = function() {
  if (xhr.readyState == 4) {
    // Do something with xhr.responseText.
  }
}
xhr.send();
```
