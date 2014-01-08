<?php
require_once ('smart-match.inc');
require_once ('wiki-pages.php');
require_once ('wiki-history.php');
require_once ('http.php');

// create an HTTP client with the proxy configuration file 'proxy.config';
// if this file is not available, no proxy is used
$base_client = new HttpClient ('proxy.config');

// caching http client, used for wiki page fetches
$cache_time_secs = 5 * 60; // 5 minutes; set to 0 to disable cache
$client = new CachingHttpClient ($cache_time_secs, 'wiki', $base_client);

$file = 'Home';
if (isset($_REQUEST) && array_key_exists('f', $_REQUEST)) {
  $file = $_REQUEST['f'];
}
else if (PHP_SAPI === 'cli') {
  $file = $argv[1];
}

function missing ($f) {
    print "Missing HTML file: <span class='missing'>".$f."</span>";
    exit;
}

function ob_callback ($buffer) {
    global $d;
    fwrite ($d, $buffer);
}

$md = file_smart_match (dirname (__FILE__).'/'.$file);
$md = realpath ($md);

if (preg_match ('/.html$/', $md)) {
    require ($md);
    exit;
}

/*
 * Special case for Pages request which is dynamically built
 * from the list of pages in the main Wiki directory
 */
if (strtolower ($file) == 'wiki/pages' ||
    strtolower ($file) == 'wiki/pages.md') {

    $success = wiki_pages ();

    if ($success) {
        require('wiki/pages.md.html');
    }
    else {
        print 'could not create wiki/pages.md.html page';
    }

    exit;
}

/*
 * Special case for History request which is dynamically built
 * from the list of pages in the main Wiki directory
 */
if (strtolower ($file) == 'wiki/history' ||
    strtolower ($file) == 'wiki/history.md') {

    // generate the wiki history HTML page
    $success = wiki_history ();

    if ($success) {
        require('wiki/history.md.html');
    }
    else {
        print 'could not create wiki/history.md.html page';
    }
    exit;
}

/* If this is a simple wiki/ request (not in a sub-directory), redirect to GitHub */
if (preg_match ('#^wiki/#', $file)) {
    try {
        print $client->get_url ('https://github.com/crosswalk-project/crosswalk-website/'.$file);
    }
    catch (Exception $e) {
        print 'Error: ' . $e->getMessage();
    }
    exit;
}

if (!preg_match ('#^'.dirname (__FILE__).'/#', $md)) {
    missing (__FILE__);
}

$cache = @stat ($md.'.html');
$source = @stat ($md);

if (!$cache || $source['mtime'] > $cache['mtime']) {
    $file = preg_replace ('#^'.dirname (__FILE__).'/#', '', $md);

    $d = @fopen ($md.'.html', 'w');
    if (!$d) {
        print " !!!! Unable to create file $md.html. Check that the server " .
              "has access to the directory.\n";
        exit;
    }

    if (preg_match ('/\.php$/', $file)) {
        /* ob_callback uses $d to write the buffer to */
        ob_start ("ob_callback");
        print '<div id="wiki-content">';
        print '<div class="markdown-body">';
        require ($file);
        print '</div>';
        print '</div>';
        ob_end_flush ();
    } else {
        $file = preg_replace ('/((\.md)|(\.mediawiki)|(\.org)|(\.php))$/',
                                 '', $file);

        // use the non-caching HTTP client to fetch content from the
        // gollum server for every request
        try {
            $content = $base_client->get_url ('http://localhost:4567/'.$file);
            print $content;
            fwrite ($d, $content);
            fflush ($d);
        }
        catch (Exception $e) {
            print $e->getMessage();
        }

        fclose ($d);
    }
}

if (filesize ($md.'.html') == 0) {
    unlink ($md.'.html');
    missing ($md.'.html');
}

require ($md.'.html');
