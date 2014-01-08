<?php
// script to fetch URLs via PHP's cli; the output from the fetch
// is written to stdout;
// http.php invokes this if using an HttpClient with a 'shell'
// url opener

// exit immediately if we're not on the command line
if (PHP_SAPI !== 'cli') {
    exit(1);
}

$url = $argv[1];

// exit immediately if we don't have a URL
if (!$url) {
    exit(1);
}

// ready to fetch the URL
require_once ('http.php');

// NB we force use of fopen here; we use a plain HttpClient, rather than
// a caching one, as this script is being used internally
// as the implementation for HTTP GETs...
$base_client = new HttpClient ('proxy.config', 'fopen');

try {
    print $base_client->get_url ($url);
}
catch (Exception $e) {
    print $e->getMessage() . "\n";
}
?>
