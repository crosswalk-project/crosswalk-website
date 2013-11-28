<?php
/*
 * make sure you populate wiki.git via:

   git clone --bare https://github.com/crosswalk-project/crosswalk-website.wiki.git wiki.git

 * And then:

   chown wwwrun: wiki.git -R

 *
 */
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
@system ('git --git-dir=wiki.git fetch -q origin master:master', $ret);
if ($ret) {
    print "git fetch failed";
    exit;
}
@system('php gfm.php wiki/pages.md >&1 >/dev/null');
@system('php gfm.php wiki/history.md 2>&1 >/dev/null');
@touch ('github-regen');
