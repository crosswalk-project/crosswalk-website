# 内容安全策略

内容安全策略域规定了应用可以加载并执行哪些类型的内容。

如果这个字段没有被设定，则没有内容安全策略被实施。默认情况下，这意味着一个Crosswalk应用可以从任何站点加载脚本和对象（通过`<script>`, `<object>`, `<embed>`和`<applet>`元素）。

提供最小保护的建议默认值是：
    "csp": "script-src 'self'; object-src 'self'"

这个限制了该应用只能使用来自它自身（例如，在Crosswalk中，作为应用的一部分分配）的JavaScript（通过`<script>`元素加载）和对象（通过`<object>`, `<embed>`, 和`<applet>`元素加载）。

参见Google的[Content Security Policy (CSP)](https://developer.chrome.com/extensions/contentSecurityPolicy)页面中某些关于可以被设置的策略类型的样例；也可以查看[完整的规范](http://www.w3.org/TR/CSP/)，尽管仅对于CSP的HTTP头部变量。

## 何时改变内容安全策略

只有当你想要加强你应用的安全性时才需要改变内容安全策略：如上文所述，除非你明确地设置了`csp`字段，否则Crosswalk不会强制执行内容安全策略。

如果你决定加强你应用的安全性并设置一种内容安全策略，你可能发现这个策略*太*严格了。在这种情况下，你可能需要放宽一些限制。下文给出了一些关于设置内容安全策略的样例，但是需要在某些特定条件下才能放宽它。

*   **确保应用仅仅从一个限制范围内的远程主机上下载脚本**

    在某些情况下，你可能需要加载一个远程资源到Crosswalk应用中：例如，在一个HTML页面中从内容传递网络加载资源：

        <script src="http://cdn.crosswalk-project.org/utils.js"></script>

    同时，你可能想要加强Crosswalk的安全性所以限制应用*仅仅*从特定域或者应用中加载脚本。（默认的Crosswalk配置允许从任何远程主机加载脚本。）

    在这种情况下，你可以明确地规定CSP，在应用本身和已知域的白名单资源：

        "csp": "script-src 'self' https://cdn.crosswalk-project.org/; object-src 'self'"

    这样便只允许从应用本身或者从`https:\/\/cdn.crosswalk-project.org/`中加载脚本。注意你仅仅可以使用任意"https://"模式的URL；或者代表"localhost"或者"127.0.0.1"。如果你有自己的并且和应用运行在相同机器上的服务器，则后者可能会更有用。

*   **确保能使用脚本内部的`eval()`**

    下文的CSP (上文推荐为最低策略):

        "csp": "script-src 'self'; object-src 'self'"

    禁用了“不安全”的JavaScript。这意味着任何对`eval()`的调用都不被允许。

    在大多数情况下，推荐**尽量**避免使用`eval()`，因为它可能成为一个跨站点脚本攻击的载体。在一个拥有特定权限访问系统资源的应用中（Crosswalk应用采用的方式），这些可能尤其危险。

    然而，你可能发现你必须使用一个依赖于`eval()`的第三方库；或者在某些情景下，使用`eval()`是最明智和实际的解决方案。在这些情况下，你可能决定适当放宽内容安全策略来允许调用`eval()`。

    你可以通过向CSP中添加`'unsafe-eval'`来允许使用`eval()`，如下所示：

        "csp": "script-src 'self' 'unsafe-eval'; object-src 'self'"

*   **支持内联JavaScript**

    在一些情景下，你可能需要通过`<script>`元素在一个HTML文件中引入内联JavaScript。例如：

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

    这个通常不是必须的，因为你经常可以把脚本放到一个外部文件中，并且通过`src`属性引用它：

        <script src="myscript.js"></script>

    但是也存在偶尔需要内联一段脚本的情况（例如，为了性能原因）。

    假设你有一段内联JavaScript并且已经规范化了一个`csp`，你可能发现你收到这个警告：

        Refused to execute inline script because it violates the following
        Content Security Policy directive: "script-src 'self'". Either
        the 'unsafe-inline' keyword, a hash ('sha256-...'), or a
        nonce ('nonce-...') is required to enable inline execution.

    最快速的解决方案便是添加`'unsafe-inline'`关键字：

        "csp": "script-src 'self' 'unsafe-inline'; object-src 'self'"

    现在内联脚本现在应该可以运行了。
