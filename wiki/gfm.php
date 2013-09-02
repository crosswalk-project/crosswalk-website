<?php
function file_smart_match ($filepath) {
    if (file_exists ($filepath))
        return $filepath;
    $lower = strtolower (basename ($filepath));
    $files = glob (dirname ($filepath).'/*');
    foreach ($files as $f) {
        if (preg_match ('/(([0-9]*-)?(.*))((\.md)|(\.mediawiki)|(\.org)|(\.php))$/i', 
                        basename ($f), $matches)) {
            if (strtolower ($matches[3]) == $lower)
                return $f;
        }
        if (preg_match ('/(([0-9]*-)?(.*))((\.md)|(\.mediawiki)|(\.org)|(\.php))(.html)$/i', 
                        basename ($f), $matches)) {
            if (strtolower ($matches[3]) == $lower)
		if (!file_exists (dirname ($filepath).'/'.$matches[1].$matches[3]))
	                return $f;
        }
    }
    return false;
}

function missing () {
?>
Missing wiki leaf: <span class='missing'><?= $_REQUEST['f'] ?>
<?php
    exit;
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
