<?php
class HttpClient {
    var $proxy;

    // $proxy_config_location: location of the file containing
    // proxy configuration; it should contain a single line, e.g.
    // tcp://proxy.server.com:3128
    function HttpClient ($proxy_config_location) {
        $file = @fopen ($proxy_config_location, 'r');

        if ($file) {
            $this->proxy = fgets ($file);
            @fclose ($file);
        }
    }

    function get_url ($url) {
        $ch = curl_init();

        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, true);

        if ($this->proxy) {
          curl_setopt ($ch, CURLOPT_HTTPPROXYTUNNEL, true);
          curl_setopt ($ch, CURLOPT_PROXY, $this->proxy);
        }

        $result = curl_exec ($ch);
        curl_close ($ch);

        return $result;
    }
}

class CachingHttpClient {
    function CachingHttpClient ($cache_time_secs, $cache_dir, $http_client) {
        $this->cache_time_secs = $cache_time_secs;
        $this->cache_dir = $cache_dir;
        $this->http_client = $http_client;
    }

    function get_url ($url) {
        $key = sha1 ($url);
        $path = $this->cache_dir . DIRECTORY_SEPARATOR . $key . '.html';

        if (file_exists ($path)) {
            $content = file_get_contents ($path);
            return $content;
        }
        else {
            $content = $this->http_client->get_url ($url);
            file_put_contents ($path, $content);
            return $content;
        }
    }
}
?>
