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
    function HttpClient ($proxy_config_location, $url_opener = null) {
        $file = @fopen ($proxy_config_location, 'r');

        if ($file) {
            $this->proxy = trim (fgets ($file));
            @fclose ($file);
        }

        // use cURL, fopen or php in the shell
        $this->url_opener = $url_opener;

        if (!$this->url_opener) {
            // use cURL if available
            if (extension_loaded ('curl')) {
                $this->url_opener = 'curl';
            }
            // use fopen, if allowed by php.ini
            else if (ini_get ('allow_fopen_url')) {
                $this->url_opener = 'fopen';
            }
            // fallback to shelling out to php as a last resort
            else {
                $this->url_opener = 'shell';
            }
        }
    }

    // cURL
    private function get_url_curl ($url) {
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

        if (!$result) {
            throw new Exception ("could not open URL $url");
        }

        curl_close ($ch);

        return $result;
    }

    // fopen
    private function get_url_fopen ($url) {
        if ($this->proxy) {
            $opts = stream_context_create (
                Array (
                    'http' => Array (
                        'proxy' => $this->proxy,
                        'request_fulluri' => true
                    )
                )
            );

            // see http://php.net/manual/en/function.fopen.php
            $use_include_path = 0;

            $handle = fopen ($url, 'r', $use_include_path, $opts);
        }
        else {
            $handle = fopen ($url, 'r');
        }

        if (!$handle) {
            throw new Exception ("could not open URL $url");
        }

        $result = '';

        while (!feof ($handle)) {
            $result .= fgets ($handle);
        }

        @fclose ($handle);

        return $result;
    }

    // shell: fopen invoked from php cli (!)
    // only necessary where allow_fopen_url is off and cURL is
    // not installed;
    // NB this also needs the php binary to be on the PATH for the
    // user PHP is running as
    function get_url_shell ($url) {
        $output = Array ();

        // TODO make the location of php binary configurable
        @exec ("php ./http-cli.php $url", $output, $retval);

        if ($retval !== 0) {
            throw new Exception ("could not open URL $url");
        }
        else {
            return implode ('', $output);
        }
    }

    // get the content of a URL using cURL if available, or falling back
    // to fopen if not, or shell as a last resort;
    // throws an exception if the URL cannot be opened or if the
    // $this->url_opener is bad
    function get_url ($url) {
        if ($this->url_opener === 'curl') {
            return $this->get_url_curl ($url);
        }
        else if ($this->url_opener === 'fopen') {
            return $this->get_url_fopen ($url);
        }
        else if ($this->url_opener === 'shell') {
            return $this->get_url_shell ($url);
        }
        else {
            throw new Exception("I don't know how to open URLs with ".
                                $this->url_opener);
        }
    }
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

    function get_url ($url) {
        $caching_on = ($this->cache_time_secs > 0);
        $needs_fetch = true;
        $path = null;
        $content = null;

        // only check the file if the cache is turned on
        if ($caching_on) {
            $key = sha1 ($url);
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
            $content = $this->http_client->get_url ($url);

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

        return $content;
    }
}
?>
