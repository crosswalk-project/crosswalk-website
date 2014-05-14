<?php
$base_url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['CONTEXT_PREFIX'] .
            '/apis/embeddingapidocs/';

$docs_url = $base_url . 'reference/org/xwalk/core/package-summary.html';
$sample_url = $base_url . 'reference/org/xwalk/core/XWalkView.html';
?>
<h1>Embedding API</h1>

<p>The embedding API enables Crosswalk to be used as a web view for Android applications, as an alternative to the stock <a href="http://developer.android.com/guide/webapps/webview.html" target="_blank">WebView</a>. You can use it to load HTML pages or whole web applications inside a Java application running on Android.</p>

<p>The following resources covering the embedding API are available:</p>

<ul>
  <li><a href="#wiki/How-to-use-Crosswalk-Embedding-API-on-Android">Getting started with the embedding API</a></li>
  <li><a href="<?php echo $docs_url ?>" target="_blank">Javadocs</a></li>
  <li><a href="<?php echo $sample_url ?>" target="_blank">Sample code</a></li>
  <li><a href="https://github.com/crosswalk-project/crosswalk/tree/master/runtime/android/sample" target="_blank">Sample project</a></li>
</ul>
