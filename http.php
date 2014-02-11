<?php
class HttpClient {
    var $proxy;

    // $proxy_config_location: location of the file containing
    // proxy configuration; it should contain a single line, e.g.
    // tcp://proxy.server.com:3128
    //
    // $url_opener: the implementation used to open URLs; one of
    // 'curl', 'fopen', or 'shell';
    // 'curl' uses the cURL extension, which must be switched
    // on in php.ini;
    // 'fopen' uses fopen, which needs allow_fopen_url to be "on"
    // in php.ini;
    // 'shell' invokes php with the http-cli.php script, which will
    // do an fopen from command line PHP (which is not restricted
    // by the allow_fopen_url setting in php.ini); NB this requires
    // the php binary to be on the PATH for the user under which the
    // script is invoked (e.g. the Apache user if using a 'shell'
    // HttpClient instance from inside a PHP script on a website)
    //
    // if not set, 'curl' is tried, then 'fopen', and finally 'shell' if
    // the other two are not available
    function HttpClient ($proxy_config_location) {
        $file = @fopen ($proxy_config_location, 'r');

        if ($file) {
            $this->proxy = trim (fgets ($file));
            @fclose ($file);
        }
    }

    // get an initialised cURL request
    function init_curl ($url, $headers) {
        $ch = curl_init();

        array_push ($headers, 'User-Agent: Crosswalk-website-townxelliot');

        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt ($ch, CURLOPT_HTTPHEADER, $headers);

        if ($this->proxy) {
          curl_setopt ($ch, CURLOPT_HTTPPROXYTUNNEL, true);
          curl_setopt ($ch, CURLOPT_PROXY, $this->proxy);
        }

        return $ch;
    }

    // fetch the URL $url and return a result object;
    // headers is an array of raw headers, e.g.
    // array ('Content-Type: application/json')
    // array (body => <response body>, 'status' => <status_code>)
    function get_url ($url, $headers = array ()) {
        $ch = $this->init_curl ($url, $headers);

        $result = curl_exec ($ch);

        if (!$result) {
            throw new Exception ("could not open URL $url");
        }

        $responseInfo = curl_getinfo ($ch);

        curl_close ($ch);

        return array (
          'body' => $result,
          'status' => $responseInfo['http_code']
        );
    }
}

// specialised client which keeps a record of when it fetches each URL;
// on subsequent requests for the same URL, an "If-Modified-Since"
// header is sent with the request, set from the timestamp on the
// file (written to the cache from the last successful request); if the
// current request receives a 304 response, the already-cached
// response is used instead
class LastModifiedHttpClient {

}

// decorate an HttpClient so that it caches pages it fetches
// for $cache_time_secs seconds in $cache_dir; note that this
// sha1 hashes the fetched URL to produce the cache key
class CachingHttpClient {
    function CachingHttpClient ($cache_time_secs, $cache_dir, $http_client) {
        $this->cache_time_secs = $cache_time_secs;

        $cache_dir = rtrim ($cache_dir, DIRECTORY_SEPARATOR);
        $this->cache_dir = $cache_dir . DIRECTORY_SEPARATOR;

        $this->http_client = $http_client;
    }

    function get_url ($url, $headers = array ()) {
        $caching_on = ($this->cache_time_secs > 0);
        $needs_fetch = true;
        $path = null;

        // to build up the response object
        $status = 200;
        $content = null;

        // only check the file if the cache is turned on
        if ($caching_on) {
            $key = sha1 (strtolower (urldecode ($url)));
            $path = $this->cache_dir . $key . '.html';

            $mtime = @filemtime ($path);

            if ($mtime && ($mtime > (time() - $this->cache_time_secs))) {
                // try to get a shared lock on the cached file;
                // this should not be possible if the cache file is being
                // written to
                $file = @fopen ($path, 'c+');
                $filesize = filesize ($path);

                if (($filesize > 0) && flock ($file, LOCK_SH)) {
                    $content = fread ($file, $filesize);
                    $needs_fetch = false;
                }
            }
        }

        // $needs_fetch is true if the cached file is invalid or
        // caching is off altogether
        if ($needs_fetch) {
            $result = $this->http_client->get_url ($url, $headers);

            $content = $result['body'];
            $status = $result['status'];

            // write to cache file only if caching is turned on,
            // which implies that $path is set
            if ($caching_on) {
                $file = @fopen ($path, 'c');

                // try to get an exclusive lock; this fails if
                // the cached file is already being written to
                if ($file && flock ($file, LOCK_EX | LOCK_NB)) {
                    ftruncate ($file, 0);
                    fwrite ($file, $content);
                    fflush ($file);
                    flock ($file, LOCK_UN);
                    fclose ($file);
                }
                else if (!$file) {
                    $msg = 'could not write to cache file ' . $path .
                           'for url ' . $url . '; check that the server ' .
                           'has access to the parent directory';

                    error_log ($msg);
                }
            }
        }

        return array (
          'body' => $content,
          'status' => $status
        );
    }
}
?>
