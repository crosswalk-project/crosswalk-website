<?php
/*
 * Scan 'path' for normal files.
 * For each file, return the filename and the
 * first ".*data-name=['"](.*)['"].*" match
 *
 */
function scan_dir ($path) {
    $d = @opendir ($path);
    $entries = Array ();
    if (!$d)
        return $entries;
    while (($n = readdir ($d)) !== false) {
        if ($n == '.' ||
            $n == '..' ||
            preg_match ('/\.html$/', $n))
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

function make_name ($name) {
    return preg_replace ('/_+/', ' ', 
           preg_replace ('/-+/', ' ', 
           preg_replace ('/^[0-9]*[-_]/', '', 
           $name)));
}

function sort_entries ($a, $b) {
    return strcasecmp ($a['wiki'], $b['wiki']);
}

$rebuild = false;
$php = @stat ('menus.js.php');
$menus = @stat ('menus.js');
if (!$menus)
    $rebuild = true;
if (!$rebuild) {
    $documentation = @stat ('wiki/documentation');
    $contribute = @stat ('wiki/contribute');
    $rebuild = (($documentation && $documentation['mtime'] > $menus['mtime']) ||
                ($contribute && $contribute['mtime'] > $menus['mtime']) ||
                ($php['mtime'] > $menus['mtime']));
}
if ($rebuild) {
    $json = Array ();
    $json[] = Array ('menu' => 'documentation', 
                     'items' => scan_dir ('wiki/documentation'));
    $json[] = Array ('menu' => 'contribute', 
                     'items' => scan_dir ('wiki/contribute'));
    $json[] = Array ('menu' => 'wiki',
                     'items' => Array (Array ('name' => 'Home', 'file' => 'Home' ),
                                       Array ('name' => 'Pages', 'file' => 'Pages' )));
    $f = fopen ('menus.js', 'w');
    fwrite ($f, 'var menus = '.json_encode ($json).';');
    fclose ($f);
    @chmod ('menus.js', 0644);
}

header('Content-type: text/javascript');
require ('menus.js');
