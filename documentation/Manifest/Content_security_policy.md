# Content security policy

The `content_security_policy` field specifies which types of content can be loaded and executed by the application. If this field is not set, no content security policy is enforced.

The suggested default value to provide minimal protection is:

    "content_security_policy": "script-src 'self'; object-src 'self'"

This restricts the application to using only JavaScripts and objects (loaded via `<object>`, `<embed>`, and `<applet>` elements) from its own origin (i.e. distributed as part of the application, in the case of Crosswalk).

See Google's [Content Security Policy (CSP)](https://developer.chrome.com/extensions/contentSecurityPolicy) page for some examples of the types of policy which can be set; [the full specification](http://www.w3.org/TR/CSP/), albeit for the HTTP headers variant of CSP, is also available.

## When do I need to change it?

Changing the CSP may be necessary in a couple of cases:

*   Enabling the application to load scripts from remote hosts.

    In some cases, you may want to load a remote resource into a Crosswalk application: for example, a resource from a content delivery network. In these cases, you can relax the CSP to allow this by whitelisting a domain:

        "content_security_policy": "script-src 'self' https://crosswalk-project.org/; object-src 'self'"

    This example allows scripts to be downloaded from https://crosswalk-project.org/ and executed. Note that you can only use arbitrary URLs with the "https://" scheme; or "http://" URLs which refer to "localhost" or "127.0.0.1". The latter may be useful if you have your own server running on the same machine as the application.

*   Enabling the use of `eval()` inside scripts.

    The following CSP (recommended above as the minimum policy):

        "content_security_policy": "script-src 'self'; object-src 'self'"

    disables the use of "unsafe" JavaScript. This means that any calls to `eval()` will not be allowed.

    In most cases, the recommendation is to avoid `eval()` at all costs, as it can be a vector for Cross-Site Scripting attacks. However, you may find yourself in a situation where you are trying to use a third party library which depends on `eval()`; or in a situation where using `eval()` is the most sensible and pragmatic solution. In these situations, you may feel that it is appropriate to relax the content security policy to allow `eval()` to be invoked.

    You can allow the use of `eval()` by adding `'unsafe-eval'` to the CSP, as follows:

        "content_security_policy": "script-src 'self' 'unsafe-eval'; object-src 'self'"
