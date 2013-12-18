<?php
function get_url ($url) {
    /* Support a proxy; put the proxy URI in the file proxy.config, eg:
    tcp://proxy.server.com:3128
    */
    $proxy = @fopen ('proxy.config', 'r');
    if ($proxy) {
        $opts = stream_context_create (
            Array (
                'http' => Array (
                    'proxy' => fgets ($proxy),
                    'request_fulluri' => true
                )
            )
        );
        fclose ($proxy);
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
?>
