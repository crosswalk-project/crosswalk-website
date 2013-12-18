<?php
if (PHP_SAPI !== 'cli') {
    /* CLI supports allow_url_fopen */
    @fpassthru (@popen ('php '.__FILE__.' '.$_REQUEST['f'], 'r'));
    exit;
}
require_once ('smart-match.inc');

function missing () {
    global $_REQUEST;
    print "Missing wiki leaf: <span class='missing'>".$_REQUEST['f']."</span>";
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
    while (!feof ($p)) {
        $line = fgets ($p);
        if (!preg_match ('/^[^ ]+ blob[ ]+([^[:space:]]+)[[:space:]]+(.*)$/',
                         $line, $matches))
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

function generateHistory ($path, $start, $end) {
    $wiki_git = '--git-dir='.$path.'.git';
    $cmd = 'git '.$wiki_git.' log '.
        '--since='.$end.' --until='.$start.' '.
        '--name-only '.
        '--no-merges '.
        '--pretty=format:">>> %s|%an|%ct|%H"';
    $f = @popen ($cmd, 'r');
    $history = Array ();
    $tracking = Array ();

    while (!feof ($f)) {
        $line = trim (fgets ($f));
        $skip = trim (fgets ($f));
        /* Skip log entries that don't contain a file list */
        while (preg_match ('/^>>> /', $skip)) {
            $line = $skip;
            $skip = trim (fgets ($f));
        }

        $files = Array ();
        $event = null;

        while ($skip != '') {
            $file = $skip;
            /* Only add if:
             * + this file does not contain a path (/)
             * + this file is a recognized markdown type
             */
            if (!preg_match ('/\//', $file) &&
                preg_match ('/((\.md)|(\.mediawiki)|(\.org)|(\.php))$/', $file)) {

                /* If this file is not currently in the tip of GIT, then skip it */
                $status = 'git '.$wiki_git.' ls-tree -r HEAD --name-only "'.$file.'"';
                $p = @popen ($status, 'r');
                $match = false;
                while (!feof ($p)) {
                    $match = strlen (trim (fgets ($p))) != 0;
                    if ($match)
                        break;
                }
                pclose ($p);
                if (!$match) {
                    $skip = trim (fgets ($f));
                    continue;
                }

                $parts = explode ('|', preg_replace ('/^>>> /', '', $line));

                if (($key = array_search ($file, $tracking)) !== false) {
                    $history [$key]['end_sha'] = $parts[3];
                } else {
                    $event = Array (
                        'orig' => $file,
                        'file' => $path.'/'.preg_replace ('/\.[^.]*$/', '', $file),
                        'name' => make_name (preg_replace ('/\.[^.]*$/', '', $file)),
//                        'subject' => $parts[0],
//                        'author' => $parts[1],
                        'date' => preg_replace ('/-[^-]*$/', '', $parts[2]),
                        'start_sha' => $parts[3],
                        'end_sha' => ''
                    );
                    $tracking [] = $file;
                    $history [] = $event;
                }
            }
            $skip = trim (fgets ($f));
        }
    }
    pclose ($f);

    for ($i = 0; $i < count ($history); $i++) {
        if ($history[$i]['end_sha'] != '')
            continue;

        $cmd = 'git --git-dir='.$path.'.git log -n 1 --pretty=format:"%H" '.
                     $history[$i]['start_sha'].'^ -- '.
                     '"'.$history[$i]['orig'].'"';
        $f = @popen ($cmd, 'r');
        $history[$i]['end_sha'] = trim (fgets ($f));
        pclose ($f);
    }

    return $history;

}

function ob_callback ($buffer) {
    global $d;
    fwrite ($d, $buffer);
}

if (isset($argv[1]))
    $_REQUEST['f'] = $argv[1];

$request = isset ($_REQUEST['f']) ? $_REQUEST['f'] : 'Home';

$md = file_smart_match (dirname (__FILE__).'/'.$request);
$md = realpath ($md);
if (preg_match ('/.html$/', $md)) {
    require ($md);
    exit;
}

/*
 * Special case for Pages request which is dynamically built
 * from the list of pages in the main Wiki directory
 */
if (strtolower ($request) == 'wiki/pages' ||
    strtolower ($request) == 'wiki/pages.md') {
    $pages = generatePageList ('wiki');
    $f = fopen ('wiki/pages.md.html', 'w');
    if (!$f) {
        missing ();
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
if (strtolower ($request) == 'wiki/history' ||
    strtolower ($request) == 'wiki/history.md') {
    $spans = Array ('days' => Array ('show_date' => 1,
                                     'start' => 0,
                                     'end' => 6),
                    //'today', 'yesterday', ' days ago')),
                    'weeks' => Array ('show_date' => 0,
                                      'start' => 1,
                                      'end' => 3),
                    'months' => Array ('show_date' => 0,
                                       'start' => 1,
                                       'end' => 12));
    $events = Array ();

    foreach ($spans as $key => $value) {
        for ($i = $value['start']; $i <= $value['end']; $i++) {
            $history = generateHistory ('wiki', $i.'.'.$key, ($i+1).'.'.$key);
            if (count ($history) == 0)
                continue;
            foreach ($history as $event) {
                $events [] = $event;
            }
        }
    }

    $f = fopen ('wiki/history.md.html', 'w');
    if (!$f) {
        missing ();
    }

    if (!defined('JSON_PRETTY_PRINT'))
        fwrite ($f, json_encode ($events));
    else
        fwrite ($f, json_encode ($events, JSON_PRETTY_PRINT));
    fclose ($f);

    require('wiki/history.md.html');
    exit;
}

/* If this is a simple wiki/ request (not in a sub-directory), redirect to GitHub */
if (preg_match ('#^wiki/#', $request)) {
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
        $f = @fopen ('https://github.com/crosswalk-project/crosswalk-website/'.$request,
                     'r', false, $opts);
    } else {
        $f = @fopen ('https://github.com/crosswalk-project/crosswalk-website/'.$request,
                     'r');
    }
    if (!$f) {
        missing ();
    }
    fpassthru ($f);
    fclose ($f);
    exit;
}

if (!preg_match ('#^'.dirname (__FILE__).'/#', $md)) {
    missing ();
}

$cache = @stat ($md.'.html');
$source = @stat ($md);

if (!$cache || $source['mtime'] > $cache['mtime']) {
    $request = preg_replace ('#^'.dirname (__FILE__).'/#', '', $md);

    $d = @fopen ($md.'.html', 'w');
    if (!$d) {
        print 'Unable to create file. Check that the server has access '.
            'to this directory.'."\n";
        exit;
    }

    if (preg_match ('/\.php$/', $request)) {
        /* ob_callback uses $d to write the buffer to */
        ob_start ("ob_callback");
	print '<div id="wiki-content">';
	print '<div class="markdown-body">';
        require ($request);
	print '</div>';
	print '</div>';
        ob_end_flush ();
    } else {
        $request = preg_replace ('/((\.md)|(\.mediawiki)|(\.org)|(\.php))$/',
                                 '', $request);
        $f = @fopen ('http://localhost:4567/'.$request, 'r');
        if (!$f) {
            missing ();
        }
        while ($f && !feof ($f)) {
	    $line = fgets ($f);
            fwrite ($d, $line);
            /* Sometimes the connection doesn't close after the </html>, so
             * watch for it, and if we see it, close the read. */
            if (preg_match ('/<\/html>/', $line))
                break;
        }
        fclose ($f);
    }

    fclose ($d);
}

if (filesize ($md.'.html') == 0) {
    unlink ($md.'.html');
    missing ();
}

require ($md.'.html');
