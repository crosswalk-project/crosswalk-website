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
            fclose ($file);
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
        echo $result;
    }
}
?>
