<?php
// file-based caching (mainly for use with HTTP clients)
class Cache {
    public $cache_time_secs;

    // caches for $cache_time_secs seconds in $cache_dir;
    // if $cache_time_secs is 0, caching is indefinite;
    // if $suffix is supplied, all files added
    // to the cache dir get this suffix
    function Cache ($cache_time_secs, $cache_dir, $suffix = '') {
        $this->cache_time_secs = $cache_time_secs;

        // ensure $this->cache_dir always has a trailing slash
        $cache_dir = rtrim ($cache_dir, DIRECTORY_SEPARATOR);
        $this->cache_dir = $cache_dir . DIRECTORY_SEPARATOR;

        $this->suffix = $suffix;
    }

    // normalises $str and generates a key from it using sha1();
    // suffix is appended to the generated string
    function generate_key ($str) {
        return sha1 (strtolower (urldecode ($str))) . $this->suffix;
    }

    // return the path to the file associated with $key
    function path_for_key ($key) {
        return $this->cache_dir . $key;
    }

    // returns true if current time - last modified time of file
    // associated with $key is > $this->cache_time_secs;
    // if the cache time is set to 0, this always returns false
    // (i.e. cached files never expire)
    function is_stale ($key) {
      if ($this->cache_time_secs === 0) {
          return false;
      }

      $mtime = $this->last_modified ($key);

      if ($mtime && ($mtime > (time() - $this->cache_time_secs))) {
        return false;
      }
      else {
        // we return true here if the file does not exist, as well
        // as if the $mtime lookup fails
        return true;
      }
    }

    function is_fresh ($key) {
        return !($this->is_stale ($key));
    }

    // get the last modified time of the file associated with $key;
    // returns 0 if the file doesn't exist; otherwise, you get the
    // time as a Unix timestamp
    function last_modified ($key) {
        $mtime = @filemtime ($this->path_for_key ($key));

        if (!$mtime) {
            $mtime = 0;
        }

        return $mtime;
    }

    // remove the file associated with $key
    function clear ($key) {
        unlink ($this->path_for_key ($key));
    }

    // return the content of the file associated with $key as a string;
    // if a shared lock cannot be requested for the key associated with
    // the file, returns false
    function read ($key) {
        // try to get a shared lock on the cached file;
        // this should not be possible if the cache file is being
        // written to
        $path = $this->path_for_key ($key);
        $file = @fopen ($path, 'c+');
        $filesize = filesize ($path);

        if (($filesize > 0) && flock ($file, LOCK_SH)) {
            $content = fread ($file, $filesize);
            return $content;
        }

        // couldn't get a read lock or file doesn't exist
        return false;
    }

    // write the string $content into the file associated with $key,
    // creating the file if necessary and replacing any existing content
    // if the file already exists
    function write ($key, $content) {
        $path = $this->path_for_key ($key);
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
                   'for key ' . $key . '; check that the server ' .
                   'has access to the parent directory';

            throw new Exception ($msg);
        }
    }
}
?>
