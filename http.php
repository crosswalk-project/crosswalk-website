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
