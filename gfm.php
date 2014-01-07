<?php
require_once ('smart-match.inc');
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
  $file = escapeshellarg ($argv[1]);
}

function missing ($f) {
    print "Missing HTML file: <span class='missing'>".$f."</span>";
    exit;
}

function make_name ($name) {
    return preg_replace ('/_+/', ' ',
           preg_replace ('/-+/', ' ',
           preg_replace ('/^[0-9]*[-_]/', '',
           $name)));
}

function sort_entries ($a, $b) {
    return strcasecmp ($a['wiki'], $b['wiki']);
}

function generatePageList ($path) {
    $wiki_git = '--git-dir='.$path.'.git';
    $cmd = 'git '.$wiki_git.' ls-tree -r HEAD';
    $p = popen ($cmd, 'r');
    $regex = '/^[^ ]+ blob[ ]+([^[:space:]]+)[[:space:]]+(.*)$/';
    while (!feof ($p)) {
        $line = fgets ($p);
        if (!preg_match ($regex, $line, $matches))
            continue;
        $file = $matches[2];
        $sha = $matches[1];
        if (preg_match ('/\.(html|php|htaccess|js|log|git)$/', $file) ||
            preg_match ('/^assets\/.*/', $file))
            continue;
        $entries [] = Array ('wiki' => $file,
                             'name' => make_name (
                                 pathinfo ($path.'/'.$file, PATHINFO_FILENAME)));
    }
    pclose ($p);
    usort ($entries, "sort_entries");
    for ($i = 0; $i < count ($entries); $i++) {
        $name = preg_replace ('/^[0-9]*[-_]/', '', $entries[$i]['wiki']);
        $name = preg_replace ('/\.[^.]*$/', '', $name);
        $entries[$i]['file'] = $path.'/'.$name;
        $entries[$i]['wiki'] = preg_replace ('/\.[^.]*$/', '', $entries[$i]['wiki']);
    }
    return $entries;
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
    $pages = generatePageList ('wiki');
    $f = fopen ('wiki/pages.md.html', 'w');
    if (!$f) {
        missing ($f);
    }
    fwrite ($f, '<h1>Crosswalk Wiki Pages</h1>'."\n");
    fwrite ($f, '<ul class="pages-list">'."\n");
    foreach ($pages as $page) {
        if (strlen (trim ($page['name'])) == 0 ||
            strlen (trim ($page['file'])) == 0)
            continue;
        fwrite ($f, '<li><a href="'.$page['file'].'">'.$page['name'].'</a></li>'."\n");
    }
    fwrite ($f, '</ul>'."\n\n");
    fclose ($f);
    require('wiki/pages.md.html');
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
    print $client->get_url ('https://github.com/crosswalk-project/crosswalk-website/'.$file);
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
        $content = $base_client->get_url ('http://localhost:4567/'.$file);
        print $content;
        fwrite ($d, $content);
        fflush ($d);
        fclose ($d);
    }
}

if (filesize ($md.'.html') == 0) {
    unlink ($md.'.html');
    missing ($md.'.html');
}

require ($md.'.html');
