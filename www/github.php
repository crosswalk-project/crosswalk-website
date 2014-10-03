<?php
// proxy github access to get channels for channel viewer and downloads page
//
// this expects a site-config.php file in the root directory like this:
/*
<?php
$SITE_CONFIG = array (
  "GITHUB_CLIENT_ID" => "",
  "GITHUB_CLIENT_SECRET" => ""
);
?>
*/
// copy and rename site-config.php.template, and fill in values
// based on a github registration for the application;
// without this, this proxy will still work, but will be rate-limited

@include ('./site-config.php');
require ('./cache.php');
require ('./http.php');

function nameSort ($a, $b) {
    return strcmp ($a->ref, $b->ref);
}

class Github {
    private static $CHANNELS = array ('stable', 'beta', 'canary');

    function Github ($clientID, $clientSecret) {
        $baseHttpClient = new HttpClient ('proxy.config');
        $cache = new Cache (0, 'cache', '.json');
        $this->httpClient = new LastModifiedHttpClient ($baseHttpClient, $cache);

        $this->repoUrl = 'https://api.github.com/repos/crosswalk-project/crosswalk';

        if ($clientID && $clientSecret) {
            $this->query = array (
              'client_id' => $clientID,
              'client_secret' => $clientSecret
            );
        }
        else {
            $this->query = array ();
        }
    }

    // $extraVars: extra key/value pairs to include in the querystring
    private function appendQstring ($url, $extraVars = array ()) {
      $query = array_merge ($this->query, $extraVars);

      $cleaned = array ();

      foreach ($query as $key => $value) {
          $cleaned[] = urlencode ($key) . '=' . urlencode ($value);
      }

      if (sizeof ($cleaned) > 0) {
          if (!preg_match ('/\?/', $url)) {
              $url .= '?';
          }

          else if (preg_match ('/&/', $url)) {
              $url .= '&';
          }

          return $url .= join ('&', $cleaned);
      }
      else {
          return $url;
      }
    }

    // reject a response with an empty body, regardless of its
    // status code
    private function checkResponse ($response) {
        // empty response body
        if (preg_match('/^\s*$/m', $response['body'])) {
            return array (
                'status' => 500,
                'body' => 'response body was empty; status code was ' .
                          $response['status'],
                'contentType' => 'text/plain'
            );
        }
        else {
            return $response;
        }
    }

    // fetch the raw content (Base64 decoded) of the specified $item
    // from $branch
    function getContent ($branch, $item) {
        $url = $this->repoUrl . '/contents/' . $item;
        $url = $this->appendQstring ($url, array ('ref' => $branch));

        $response = $this->httpClient->get_url ($url);
        $response = $this->checkResponse($response);

        if ($response['status'] !== 200) {
            return $response;
        }
        else {
            $data = json_decode( $response['body']);
            $content = base64_decode ($data->content);

            return array (
                'status' => 200,
                'body' => $content,
                'content-type' => 'text/plain'
            );
        }
    }

    // fetch DEPS.xwalk for a specified branch;
    // the part of the original file we want looks like this:
    /*
    chromium_version = '32.0.1700.102'
    chromium_crosswalk_point = '2071e32149e7911bc378a20ef5a5363f382d6999'
    blink_crosswalk_point = 'b00d5cd307239b282e959838c1ed4d239c80ad90'
    */
    // we want the full Chromium version, plus the first 8 characters
    // of the shas for chromium_crosswalk_point and
    // blink_crosswalk_point
    function getDeps ($branch) {
        $response = $this->getContent ($branch, 'DEPS.xwalk');

        // if an error occurred, return it immediately
        if ($response['status'] !== 200) {
            return $response;
        }

        $content = $response['body'];

        $matches = array ();

        preg_match ("/chromium_version = '(.+)'\n/", $content, $matches);
        $chromiumVersion = $matches[1];

        preg_match ("/chromium_crosswalk_point = '(.+)'\n/", $content, $matches);
        $chromiumSha = substr ($matches[1], 0, 8);

        preg_match ("/blink_crosswalk_point = '(.+)'\n/", $content, $matches);
        $blinkSha = substr ($matches[1], 0, 8);

        return array (
            'status' => 200,
            'body' => json_encode (array (
                'branch' => $branch,
                'chromiumVersion' => $chromiumVersion,
                'chromiumSha' => $chromiumSha,
                'blinkSha' => $blinkSha
            )),
            'contentType' => 'application/json'
        );
    }

    // fetch VERSION for a specified branch;
    // the original VERSION file looks like this:
    /*
    MAJOR=5
    MINOR=32
    BUILD=85
    PATCH=0
    */
    // we convert it into a MAJOR.MINOR.BUILD.PATCH string
    function getVersion ($branch) {
        $response = $this->getContent ($branch, 'VERSION');

        // if an error occurred, return it immediately
        // if an error occurred, return it immediately
        if ($response['status'] !== 200) {
            return $response;
        }

        $content = $response['body'];

        // convert to a version number
        $content = preg_replace ('/^.+?=/m', '', $content);
        $content = str_replace ("\n", '.', $content);
        $content = preg_replace ("/\.$/", '', $content);

        return array (
            'status' => 200,
            'body' => json_encode (array (
                'branch' => $branch,
                'version' => $content
            )),
            'contentType' => 'application/json'
        );
    }

    // get branches; the branches map to channels as follows:
    // master = canary
    // crosswalk-N = beta , where N is the highest-numbered branch
    // crosswalk-N-1 = stable
    // returns an array with 3 items, one for each channel:
    // [{branch: 'name', channel: 'beta', sha: '73646352'}, ...]
    function getBranches () {
        // these will be the branches we care about
        $branches = array ();

        $url = $this->repoUrl . '/git/refs/heads';
        $url = $this->appendQstring ($url);

        $response = $this->httpClient->get_url ($url);

        // check for empty body
        $response = $this->checkResponse($response);

        if ($response['status'] !== 200) {
            return $response;
        }
        else {
            // filter result so we just have the crosswalk-* branches
            $crosswalkBranches = array ();

            $result = json_decode ($response['body']);

            foreach ($result as $branch) {
                // include any crosswalk-N branches for sorting
                if (preg_match ('/crosswalk-/', $branch->ref)) {
                    $crosswalkBranches[] = $branch;
                }
                // always keep master
                else if (preg_match ('/master/', $branch->ref)) {
                    $branches[] = $branch;
                }
            }

            // sort them so they're in numerical order
            usort ($crosswalkBranches, 'nameSort');

            // get the last two elements
            $crosswalkBranches = array_slice ($crosswalkBranches, -2);

            // add these to the branches we're interested in
            $branches = array_merge ($crosswalkBranches, $branches);

            // now we get the branch, channel and <branch>.object.sha property
            // for master and the two most recent branches as our output
            $data = array ();

            // the $branches array is in the same order as the $channels array,
            // so we can just add a channel for each ref
            foreach ($branches as $index => $branch) {
              $data[] = array (
                  'channel' => self::$CHANNELS[$index],
                  'branch' => str_replace ('refs/heads/', '', $branch->ref),
                  'sha' => substr ($branch->object->sha, 0, 8)
              );
            }

            return array (
                'status' => 200,
                'body' => json_encode ($data),
                'contentType' => 'application/json'
            );
        }
    }
}

$clientID = '';
$clientSecret = '';

if (isset($SITE_CONFIG)) {
    $clientID = $SITE_CONFIG['GITHUB_CLIENT_ID'];
    $clientSecret = $SITE_CONFIG['GITHUB_CLIENT_SECRET'];
}

$github = new Github ($clientID, $clientSecret);
$response = null;

try {
    if (isset($_GET['branch'])) {
        if (isset($_GET['item'])) {
            $response = $github->getContent ($_GET['branch'], $_GET['item']);
        }
        else if (isset($_GET['fetch']) && $_GET['fetch'] === 'deps') {
            $response = $github->getDeps ($_GET['branch']);
        }
        else if (isset($_GET['fetch']) && $_GET['fetch'] === 'version') {
            $response = $github->getVersion ($_GET['branch']);
        }
    }
    else {
        $response = $github->getBranches ();
    }
}
catch (Exception $e) {
    error_log (print_r ($e, true));
}

if (!$response) {
    // default message if none of the above match
    $response = array (
        'body' => 'Invalid query: please use ?branch=X&item=Y, or ' .
                  '?branch=X&fetch=deps, or ?branch=X&fetch=version, ' .
                  'or no querystring to fetch branches',
        'status' => 400, // bad request
        'contentType' => 'text/plain'
    );
}

header(' ', true, $response['status']);
header ('Content-Type: ' . $response['contentType']);
print $response['body'];
?>
