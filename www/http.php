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
        curl_setopt ($ch, CURLINFO_HEADER_OUT, true);

        if ($this->proxy) {
          curl_setopt ($ch, CURLOPT_HTTPPROXYTUNNEL, true);
          curl_setopt ($ch, CURLOPT_PROXY, $this->proxy);
        }

        return $ch;
    }

    // fetch the URL $url and return a result object;
    // headers is an array of raw headers, e.g.
    // array ('Content-Type: application/json')
    // array (body => <response body>, 'status' => <status_code>);
    // any response which returns a 400+ status code is considered an
    // exception
    function get_url ($url, $headers = array ()) {
        $ch = $this->init_curl ($url, $headers);

        $result = curl_exec ($ch);

        $responseInfo = curl_getinfo ($ch);

        if ($responseInfo['http_code'] >= 400) {
            $json = json_encode ($responseInfo);
            throw new Exception ("could not open URL $url; response info: $json");
        }

        curl_close ($ch);

        return array (
          'body' => $result,
          'from_local_cache' => false,
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
    function LastModifiedHttpClient ($http_client, $cache) {
        $this->http_client = $http_client;

        // ensure that the $cache never automatically invalidates
        // any of its files
        $this->cache = $cache;
        $this->cache->cache_time_secs = 0;
    }

    function get_url ($url, $headers = array ()) {
        $needs_fetch = true;

        $status = 0;
        $content = null;
        $from_local_cache = false;

        // get the last modified time of the existing cached file
        $key = $this->cache->generate_key ($url);
        $last_modified = $this->cache->last_modified ($key);

        // add an "If-Modified-Since" header, formatted like
        // "Sat, 29 Oct 1994 19:43:31 GMT"
        $formatted_date = date (DateTime::RFC1123, $last_modified);
        $headers[] = "If-Modified-Since: $formatted_date";

        // fetch the remote page
        $response = $this->http_client->get_url ($url, $headers);

        // if the response is 304 (Not Modified), return the cached copy;
        // NB this will return a 200 status to the caller
        if ($response['status'] === 304) {
            $status = 200;
            $content = $this->cache->read ($key);

            // set $from_local_cache to true, providing we could read
            // $content from the cache
            $from_local_cache = ($content !== false);
        }
        else {
            // set the status and body to whatever the server returned in
            // its response
            $status = $response['status'];
            $content = $response['body'];
        }

        // if status === 200 and we didn't read from the local cache,
        // write (good) content to cache
        if (!$from_local_cache && $status === 200) {
            try {
                $this->cache->write ($key, $content);
            }
            catch (Exception $e) {
                // NB we still return the response, as
                // it's only writing to the cache which failed
                error_log ($e);
            }
        }

        return array (
            'status' => $status,
            'from_local_cache' => $from_local_cache,
            'body' => $content
        );
    }
}

// decorate an HttpClient so that it caches pages it fetches
// in $cache
class CachingHttpClient {
    function CachingHttpClient ($http_client, $cache) {
        $this->http_client = $http_client;
        $this->cache = $cache;
    }

    private function get_key ($url) {
        return $this->cache->generate_key ($url);
    }

    function get_url ($url, $headers = array ()) {
        $needs_fetch = true;

        // to build up the response object
        $status = 200;
        $content = null;

        // only check the file if the cache is turned on
        $key = $this->get_key ($url);

        if ($this->cache->is_fresh ($key)) {
            $content = $this->cache->read ($key);

            // we will need to fetch if the cache read failed
            $needs_fetch = ($content === false);
        }

        // $needs_fetch is true if the cached file is invalid or stale
        if ($needs_fetch) {
            $result = $this->http_client->get_url ($url, $headers);

            $content = $result['body'];
            $status = $result['status'];

            $key = $this->get_key ($url);
            $this->cache->write ($key, $content);
        }

        return array (
          'body' => $content,
          'from_local_cache' => !$needs_fetch,
          'status' => $status
        );
    }
}
?>
