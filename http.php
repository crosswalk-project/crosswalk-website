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
        if ($this->proxy) {
            $opts = stream_context_create (
                Array (
                    'http' => Array (
                        'proxy' => $this->proxy,
                        'request_fulluri' => true
                    )
                )
            );

            $f = @fopen ($url, 'r', false, $opts);
        } else {
            $f = @fopen ($url, 'r');
        }
        if (!$f) {
            missing ($f);
        }
        fpassthru ($f);
        fclose ($f);
    }
}
?>
