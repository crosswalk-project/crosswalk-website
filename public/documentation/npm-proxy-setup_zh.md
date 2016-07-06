# NPM代理配置

如果您工作的环境中有防火墙，那么可能需要为npm配置代理。在命令行模式下，运行下面命令：

配置http代理： `npm config set proxy [proxy]:[port]`.<br>
配置https代理： `npm config set https-proxy [proxy]:[port]`.

例如，下面为http和https设置了相同的代理http://example.proxy.com和代理端口8080。

    > npm config set proxy http://example.proxy.com:8080
    > npm config set https-proxy http://example.proxy.com:8080



