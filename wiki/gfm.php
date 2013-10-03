<?php
function file_smart_match ($filepath) {
    if (file_exists ($filepath))
        return $filepath;
    $lower = strtolower (basename ($filepath));
    $files = glob (dirname ($filepath).'/*');
    foreach ($files as $f) {
        if (preg_match (
            '/(([0-9]*-)?(.*))((\.md)|(\.mediawiki)|(\.org)|(\.php))$/i', 
                        basename ($f), $matches)) {
            if (strtolower ($matches[3]) == $lower)
                return $f;
        }
        if (preg_match (
            '/(([0-9]*-)?(.*))((\.md)|(\.mediawiki)|(\.org)|(\.php))(.html)$/i', 
                        basename ($f), $matches)) {
            if (strtolower ($matches[3]) == $lower)
		if (!file_exists (dirname ($filepath).'/'.$matches[1].$matches[3]))
	                return $f;
        }
    }
    return false;
}

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
    $d = @opendir ($path);
    $entries = Array ();
    if (!$d)
        return;
    
    while (($n = readdir ($d)) !== false) {
        if (is_dir ($path.'/'.$n) || 
            preg_match ('/\.(html|php|htaccess|js|log|git)$/', $n))
            continue;
        $entry = null;
        $f = fopen ($path.'/'.$n, 'r');
        while (!feof ($f)) {
            $l = trim (fgets ($f));
            if (preg_match ('/^.*data-name=[\'"]([^"\']*)["\']/', $l, $matches)) {
                $entry = Array ('wiki' => $n, 'name' => $matches[1]);
                break;
            }
        }
        fclose ($f);
        /* A title wasn't found with the above preg_match, so use the filename */
        if (!$entry) {
            $entry = Array ('wiki' => $n,
                            'name' => make_name (
                                pathinfo ($path.'/'.$n, PATHINFO_FILENAME)));
        }
        if ($entry != null) {
            $entries [] = $entry;
        }
    }
    closedir ($d);
    usort ($entries, "sort_entries");
    for ($i = 0; $i < count ($entries); $i++) {
        $name = preg_replace ('/^[0-9]*[-_]/', '', $entries[$i]['wiki']);
        $name = preg_replace ('/\.[^.]*$/', '', $name);
        $entries[$i]['file'] = $name;
        $entries[$i]['wiki'] = preg_replace ('/\.[^.]*$/', '', $entries[$i]['wiki']);
    }
    return $entries;
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
if (strtolower ($request) == 'pages' || 
    strtolower ($request) == 'pages.md') {
    $pages = generatePageList ('.');
    $f = fopen ('pages.md.html', 'w');
    if (!$f) {
        missing ();
    }
    fwrite ($f, '<h2>Crosswalk Wiki Pages</h2>');
    fwrite ($f, '<ul class="pages-list">');
    foreach ($pages as $page) {
        if (strlen (trim ($page['name'])) == 0 ||
            strlen (trim ($page['file'])) == 0)
            continue;
        fwrite ($f, '<li><a href="'.$page['file'].'">'.$page['name'].'</a></li>');
    }
    fwrite ($f, '</ul>');
    fclose ($f);
    require('pages.md.html');
    exit;
}

function generateHistory ($path, $start, $end) {
    $cmd = 'git --git-dir='.$path.' log '.
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
             * + this file exists on the file system
             * + this file is a recognized markdown type
             */
            if (!preg_match ('/\//', $file) && file_exists ($file) &&
                preg_match ('/((\.md)|(\.mediawiki)|(\.org)|(\.php))$/', $file)) {
                
                $parts = explode ('|', preg_replace ('/^>>> /', '', $line));

                if (($key = array_search ($file, $tracking)) !== false) {
                    $history [$key]['end_sha'] = $parts[3];
                } else {
                    $event = Array (
                        'orig' => $file,
                        'file' => preg_replace ('/\.[^.]*$/', '', $file),
                        'name' => make_name (preg_replace ('/\.[^.]*$/', '', $file)),
                        'subject' => $parts[0],
                        'author' => $parts[1],
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
        
        $cmd = 'git --git-dir='.$path.' log -n 1 --pretty=format:"%H" '.
                     $history[$i]['start_sha'].'^ -- '.
                     '"'.$history[$i]['orig'].'"';
        $f = @popen ($cmd, 'r');
        $history[$i]['end_sha'] = trim (fgets ($f));
        pclose ($f);
    }
    
    return $history;
    
}

/*
 * Special case for History request which is dynamically built
 * from the list of pages in the main Wiki directory
 */
if (strtolower ($request) == 'history' || 
    strtolower ($request) == 'history.md') {

    $f = fopen ('history.md.html', 'w');
    if (!$f) {
        missing ();
    }
    fwrite ($f, '<h2>Crosswalk Wiki History</h2>');
    
    $spans = Array ('days' => Array ('show_date' => 1,
                                     'start' => 0, 
                                     'end' => 6, 
                                     'names' => Array ()),
                    //'today', 'yesterday', ' days ago')), 
                    'weeks' => Array ('show_date' => 0,
                                      'start' => 1, 
                                      'end' => 3,
                                      'names' => Array ('This week', 'Last week', 
                                                        ' weeks ago')),
                    'months' => Array ('show_date' => 0,
                                       'start' => 1, 
                                       'end' => 12,
                                       'names' => Array ('This month', 'Last month', 
                                                        ' months ago')));
    foreach ($spans as $key => $value) {
        for ($i = $value['start']; $i <= $value['end']; $i++) {
            $history = generateHistory ('.git', $i.'.'.$key, ($i+1).'.'.$key);
            if (count ($history) == 0)
                continue;
            if (count ($value['names'])) {
                if ($i >= count ($value['names']) - 1) {
                    $period = ''.($i+1).''.$value['names'][count($value['names'])-1];
                } else {
                    $period = $value['names'][$i];
                }
                if ($value['show_date']) {
                    $period .= ' &ndash; '.strftime ('%A, %B %e', $history[0]['date']);
                }
                fwrite ($f, '<h3>'.$period.'</h3>');
            } else {
                fwrite ($f, '<h3>'.strftime ('%A, %B %e', $history[0]['date']).'</h3>');
            }
            fwrite ($f, '<ul class="history-list">');
            foreach ($history as $event) {
                $str = '<li><a href="'.$event['file'].'">'.$event['name'].'</a> ';
                if ($event['end_sha'] != '')
                    $str .= '<a target="_blank" href="'.
                    'https://github.com/crosswalk-project/'.
                    'crosswalk-website/wiki/'.$event['file'].
                    '/_compare/'.$event['start_sha'].'..'.$event['end_sha'].
                    '"">View changes on GitHub</a>';
                else
                    $str .= '<span>New page</span>';
                $str .= '</li>';
                fwrite ($f, $str);
            }
            fwrite ($f, '</ul>');
        }
    }
    fclose ($f);
    require('history.md.html');
    exit;
}

if (!preg_match ('#^'.dirname (__FILE__).'/#', $md)) {
    missing ();
}

function ob_callback ($buffer) {
    global $d;
    fwrite ($d, $buffer);
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
        $f = @fopen ('http://localhost:4567/wiki/'.$request, 'r');
        if (!$f) {
            missing ();
        }
        while ($f && !feof ($f)) {
            fwrite ($d, fgets ($f));
        }
        fclose ($f);
    }

    fclose ($d);
}

if (filesize ($md.'.html') == 0) {
    unlink ($md.'.html');
    missing ();
}

touch ($md.'.html', $source['mtime']);

require ($md.'.html');
