# Proxy setup for npm

If you are developing behind a firewall you may need to configure npm to use a proxy server.  From the command-line, run the following:

For http proxy: `npm config set proxy [proxy]:[port]`.<br>
For https proxy: `npm config set https-proxy [proxy]:[port]`.

For example, the following sets both http and https proxies to http://example.proxy.com with port 8080.

    > npm config set proxy http://example.proxy.com:8080
    > npm config set https-proxy http://example.proxy.com:8080



