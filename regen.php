<?php
require_once('wiki-pages.php');
require_once('wiki-history.php');
/*
 * make sure you populate wiki.git via:

   git clone --bare https://github.com/crosswalk-project/crosswalk-website.wiki.git wiki.git

 * And then:

   chown wwwrun: wiki.git -R

 *
 */
/* Extract the POST data;
   the data has this shape:

   {"pages":[{"page_name":"Home","title":"Home", ...}]}

   we need to regenerate the HTML file for each element in the pages
   array; we can locate the page based on the page_name of each element
 */
try {
  $post = '';
  if (isset ($_POST['payload'])) {
    $post = $_POST['payload'];
  }
  else {
    $i = fopen ("php://input", "r");
    while (!feof($i)) {
      $post .= fgets ($i);
    }
    fclose ($i);
  }
  $payload = json_decode ($post);

  if ($payload) {
    foreach ($payload->pages as $page) {
      // we only need to remove the cached copy for pages which
      // were edited (NB this is also used for page deletions):
      // we won't have a cached copy for a newly-added page
      if ($page->action === 'edited') {
        // locate the cached HTML file, as saved by the caching http
        // client; it would be better to invalidate the http client's
        // cache directly, but we can't easily share state between
        // different actions right now, and don't really want to
        // create the same HTTP client in multiple places;
        // so this is a quick workaround
        $normalised_url = strtolower (urldecode ($page->html_url));
        $key = sha1 ($normalised_url);
        $page_cached = 'wiki' . DIRECTORY_SEPARATOR . $key . '.html';
        unlink ($page_cached);
      }
    }
  }

  /* Rate limit to at most once every 10 seconds */
  $mtime = filemtime('github-regen');
  $now = gettimeofday();
  if ($now['sec'] - $mtime < 10) {
    exit;
  }

  $ret = 0;
  system ('git -c http.sslVerify=false --git-dir=wiki.git fetch origin master', $ret);
  if ($ret) {
    print 'git fetch failed';
    exit;
  }

  wiki_pages();
  wiki_history();
  touch('github-regen');
}
catch (Exception $e) {
  header('HTTP/1.0 500 Internal Server Error');
  print "An error occurred while refreshing the wiki Pages/History:\n ";
  print $e->getMessage();
}
?>
