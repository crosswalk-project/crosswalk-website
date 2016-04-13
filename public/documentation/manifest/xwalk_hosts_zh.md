# xwalk_hosts

[XMLHttpRequest 对象](http://www.w3.org/TR/XMLHttpRequest/)通过HTTP与远程服务器通信（一个通常称为[Ajax](http://www.adaptivepath.com/ideas/ajax-new-approach-web-applications/)的方法）。正常情况下，这些请求会被[same-origin policy](http://www.w3.org/Security/wiki/Same_Origin_Policy)约束：只有和发出请求的脚本位于相同主机的资源才可以被访问。

如果你已经尝试从你的web应用访问资源，但是它们却位于不同的来源，你可能已经看到下列信息：

    XMLHttpRequest cannot load http://crosswalk-project.org/. No
    'Access-Control-Allow-Origin' header is present on the requested
    resource. Origin 'file://' is therefore not allowed access.

这表明如果提供你要访问的服务器返回正确的头部，便有可能发出这样的请求。这里提到的'Access-Control-Allow-Origin'头部是[cross-origin resource sharing specification](http://www.w3.org/TR/cors/)的一部分。跨源的资源分享使得一个客户端可以向不同源的服务器发出请求，通过需要的header提供了客户端和服务器的通信。然而实际上，很多你可能想要访问的web服务（除非你自己控制它们）将没有认证的情况下不允许跨源的资源共享。

Crosswalk为部署在Android上的应用提供了一种绕开这些限制的方法。

## 允许跨源请求

Crosswalk提供了一个manifest字段,`xwalk_hosts`, 其中它可以保证在Android平台上应用可以使用XMLHttpRequest发出跨源请求。这样避免了同源的约束，同时也避免了使用跨源资源共享的需求。

这个字段包含一组代表应用可以访问的主机的URL模式。其中值完全可以表示主机名，像：

* `"http://crosswalk-project.org/"`

或者通配符字符串的模式，例如：

* `"http://*.org/"`
* `"https://*/"`

例如，这个manifest将允许访问Crosswalk网站，或者任何intel.com子域：

    {
      "name": "app name",
      "start_url": "index.html",
      "xwalk_hosts": [
        "http://crosswalk-project.org/",
        "http://*.intel.com/"
      ]
    }

一旦你规定了应用可以访问的域名，`XMLHttpRequest`便可以照常向他们发送请求：

    var xhr = new XMLHttpRequest();
    xhr.open("GET", "http://crosswalk-project.org/", true);
    xhr.onreadystatechange = function () {
      if (xhr.readyState == 4) {
        // do something with xhr.responseText
      }
    }
    xhr.send();
