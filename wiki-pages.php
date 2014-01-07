<?php
require_once ('strings.php');

// functions for generating wiki page list HTML in wiki/pages.md.html

function sort_entries ($a, $b) {
    return strcasecmp ($a['wiki'], $b['wiki']);
}

function generate_page_list () {
    $path = 'wiki';
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
        $normalised_name = make_name (pathinfo ($file, PATHINFO_FILENAME));
        $entries [] = Array ('wiki' => $file,
                             'name' => $normalised_name);
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

// returns 1 if HTML created successfully, 0 otherwise
function wiki_pages () {
    $pages = generate_page_list ();
    $f = fopen ('wiki/pages.md.html', 'w');
    if (!$f) {
        return 0;
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

    return 1;
}
?>
