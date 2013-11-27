<?php
/* Extract the POST data */
$post = '';
if (isset ($_POST['payload'])) {
        $post = $_POST['payload'];
} else {
        $i = fopen ("php://input", "r");
        while (!feof($i))
                $post .= fgets ($i);
        fclose ($i);
}
$payload = json_decode ($post);

/* Rate limit to at most once every 10 seconds */
$mtime = @filemtime ('github-regen');
$now = gettimeofday ();
if ($now['sec'] - $mtime < 10)
    exit;

$ret = 0;
@system('git --git-dir=wiki.git fetch -q --all >/dev/null 2>&1', $ret);
if ($ret) {
    print "git fetch failed";
    exit;
}
@system('php gfm.php wiki/pages.md.html 2>&1 >/dev/null', $ret);
if ($ret) {
    print "Generation of pages.md.html";
    exit;
}
@system('php gfm.php wiki/history.md.html 2>&1 >/dev/null', $ret);
if ($ret) {
    print "Generation of history.md.html failed";
    exit;
}
touch ('github-regen');