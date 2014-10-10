# Content security policy

The content security policy field specifies which types of content can be loaded and executed by the application. If this field is not set, no content security policy is enforced. By default, this means that a Crosswalk application can load scripts and objects from any host (via `<script>`, `<object>`, `<embed>` and `<applet>` elements).

**For Crosswalk 4-7, this field was called `content_security_policy`. The name was changed to `csp` for Crosswalk 8+.**

The suggested default value to provide minimal protection (Crosswalk 4-7) is:

    "content_security_policy": "script-src 'self'; object-src 'self'"

For Crosswalk 8+, the value is the same, but the field name is different:

    "csp": "script-src 'self'; object-src 'self'"

This restricts the application to using only JavaScripts (loaded via `<script>` elements) and objects (loaded via `<object>`, `<embed>`, and `<applet>` elements) from its own origin (i.e. distributed as part of the application, in the case of Crosswalk). It also implicitly applies other constraints, such as preventing the execution of inline scripts, and disabling `eval()` and other unsafe functions (see the next section).

See Google's [Content Security Policy (CSP)](https://developer.chrome.com/extensions/contentSecurityPolicy) page for some examples of the types of policy which can be set; [the full specification](http://www.w3.org/TR/CSP/), albeit for the HTTP headers variant of CSP, is also available.

In the examples below, the long name for the field is used (`content_security_policy`); this should be replaced with `csp` if you are using Crosswalk 8 or later.

## When to change the content security policy

You only need to change the content security policy settings if you want to tighten your application's security: as stated above, Crosswalk does not enforce content security unless you explicitly set the `content_security_policy` field.

If you do decide to tighten your application's security and set a content security policy, you may find that the policy is *too* tight. In this case, you may need to loosen some of its constraints. The list below gives a few examples of setting a content security policy, but loosening it under specific conditions.

*   **Enable the application to load scripts from a restricted range of remote hosts**

    In some cases, you may want to load a remote resource into a Crosswalk application: for example, load a resource from a content delivery network in an HTML page:

        <script src="http://cdn.crosswalk-project.org/utils.js"></script>

    At the same time, you may want to tighten Crosswalk's security to restrict the application to loading scripts *only* from that domain and from the app. (The default Crosswalk configuration allows loading scripts from any remote host.)

    In these cases, you can explicitly specify the CSP, whitelisting resources from the application itself and from known domains:

        "content_security_policy": "script-src 'self' https://cdn.crosswalk-project.org/; object-src 'self'"

    This only allows scripts to be downloaded from the application itself or from https://cdn.crosswalk-project.org/. Note that you can only use arbitrary URLs with the "https://" scheme; or "http://" URLs which refer to "localhost" or "127.0.0.1". The latter may be useful if you have your own server running on the same machine as the application.

*   **Enable the use of `eval()` inside scripts**

    The following CSP (recommended above as the minimum policy):

        "content_security_policy": "script-src 'self'; object-src 'self'"

    disables the use of "unsafe" JavaScript. This means that any calls to `eval()` will not be allowed.

    In most cases, the recommendation is to avoid `eval()` **at all costs**, as it can be a vector for Cross-Site Scripting attacks. These can be particularly dangerous in the context of an application which has privileged access to system resources (the way a Crosswalk application does).

    However, you may find yourself in a situation where you are trying to use a third party library which depends on `eval()`; or in a situation where using `eval()` is the most sensible and pragmatic solution. In these situations, you may decide that it is appropriate to relax the content security policy to allow `eval()` to be invoked.

    You can allow the use of `eval()` by adding `'unsafe-eval'` to the CSP, as follows:

        "content_security_policy": "script-src 'self' 'unsafe-eval'; object-src 'self'"

*   **Enable inline JavaScript**

    In some situations, you may want to incorporate inline JavaScript in an HTML file via `<script>` elements. For example:

        <!DOCTYPE html>
        <html>
          <head>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta charset="utf-8">
            <title>my app</title>
          </head>

          <body>
            <div id="content"></div>

            <!-- this is the inline script -->
            <script>
            document.addEventListener('DOMContentLoaded', function () {
              var elt = document.querySelector('#content');
              elt.innerHTML = '<p>Hello world</p>';
            });
            </script>
          </body>
        </html>

    This is usually not necessary, as you can often put the script into an external file instead and reference it via the `src` attribute:

        <script src="myscript.js"></script>

    But there are occasionally cases where you may want to inline a script (e.g. for performance reasons).

    If you have inline JavaScript and have specified a `content_security_policy`, you may find that you get this warning:

        Refused to execute inline script because it violates the following
        Content Security Policy directive: "script-src 'self'". Either
        the 'unsafe-inline' keyword, a hash ('sha256-...'), or a
        nonce ('nonce-...') is required to enable inline execution.

    The quickest solution is to add the `'unsafe-inline'` keyword:

        "content_security_policy": "script-src 'self' 'unsafe-inline'; object-src 'self'"

    Inline scripts should now execute.
