<?php
require ('./http.php');

function nameSort ($a, $b) {
  return strcmp ($a->ref, $b->ref);
}

// github access to get channels for channel viewer and downloads page
class Github {
  function Github ($clientID, $clientSecret) {
    $this->httpClient = new HttpClient ('proxy.config');

    $this->repoUrl = 'https://api.github.com/repos/crosswalk-project/crosswalk';

    $this->query = array (
      'client_id' => $clientID,
      'client_secret' => $clientSecret
    );
  }

  private function appendQstring ($url) {
    $cleaned = array ();

    foreach ($this->query as $key => $value) {
      $cleaned[] = urlencode ($key) . '=' . urlencode ($value);
    }

    return $url .= '?' . join ('&', $cleaned);
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
    $result = json_decode ($this->httpClient->get_url ($url));

    // filter result so we just have the crosswalk-* branches
    $crosswalkBranches = array ();

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
    // for master and the two most recent branches
    $data = array ();

    // the $branches array is in the same order as the $channels array,
    // so we can just add a channel for each ref
    $channels = array ('stable', 'beta', 'canary');

    foreach ($branches as $index => $branch) {
      $data[] = array (
        'channel' => $channels[$index],
        'branch' => str_replace ('refs/heads/', '', $branch->ref),
        'sha' => substr ($branch->object->sha, 0, 8)
      );
    }

    return $data;
  }
}

$clientID = '';
$clientSecret = '';

$rawConfig = @file_get_contents ('./site-config.json');
if ($rawConfig) {
  $githubConfig = json_decode ($rawConfig);
  $clientID = $githubConfig->GITHUB_CLIENT_ID;
  $clientSecret = $githubConfig->GITHUB_CLIENT_SECRET;
}

$github = new Github ($clientID, $clientSecret);

$data = $github->getBranches ();

// set JSON header
header ('Content-Type: application/json');

print json_encode ($data);
?>
