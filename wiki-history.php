<?php
// functions for generating the wiki history page in wiki/history.md.html;
// note that this is actually a JSON file which is converted into
// HTML once fetched via JavaScript... (see xwalk.js)

// $start: git time specifier (e.g. "1.days")
// $end: git time specifier (e.g. "2.days")
function generate_history ($start, $end) {
    $wiki_git = '--git-dir=wiki.git';
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
                $status = 'git '.$wiki_git.' ls-tree -r HEAD --name-only "'.
                          escapeshellarg ($file) .'"';
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
                        'file' => 'wiki/'.preg_replace ('/\.[^.]*$/', '', $file),
                        'name' => make_name (preg_replace ('/\.[^.]*$/', '', $file)),
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

        $cmd = 'git --git-dir=wiki.git log -n 1 --pretty=format:"%H" '.
               $history[$i]['start_sha'].'^ -- '.
               '"'. escapeshellarg ($history[$i]['orig']) .'"';
        $f = @popen ($cmd, 'r');
        $history[$i]['end_sha'] = trim (fgets ($f));
        pclose ($f);
    }

    return $history;
}

// returns 1 if generated successfully, 0 otherwise
function wiki_history () {
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
            $history = generate_history ($i.'.'.$key, ($i+1).'.'.$key);
            if (count ($history) == 0)
                continue;
            foreach ($history as $event) {
                $events [] = $event;
            }
        }
    }

    $f = fopen ('wiki/history.md.html', 'w');
    if (!$f) {
        return 0;
    }

    if (!defined('JSON_PRETTY_PRINT'))
        fwrite ($f, json_encode ($events));
    else
        fwrite ($f, json_encode ($events, JSON_PRETTY_PRINT));
    fclose ($f);

    return 1;
}
?>
