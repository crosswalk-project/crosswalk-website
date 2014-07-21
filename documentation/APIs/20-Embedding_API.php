<?php
$base_url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['CONTEXT_PREFIX']. '/apis/';

$docs_url_v1 = $base_url . 'embeddingapidocs/reference/org/xwalk/core/package-summary.html';
$docs_url_v2 = $base_url . 'embeddingapidocs_v2/reference/org/xwalk/core/package-summary.html';
$sample_url = $base_url . 'reference/org/xwalk/core/XWalkView.html';
?>
<h1>Embedding API</h1>

<p>The embedding API enables Crosswalk to be used as a web view for Android applications, as an alternative to the stock <a href="http://developer.android.com/guide/webapps/webview.html" target="_blank">WebView</a>. You can use it to load HTML pages or whole web applications inside a Java application running on Android.</p>

<p>The following Java API docs are available:</p>

<ul>
  <li><a href="<?php echo $docs_url_v1 ?>" target="_blank">Javadocs (version 1)</a></li>
  <li><a href="<?php echo $docs_url_v2 ?>" target="_blank">Javadocs (version 2)</a></li>
</ul>

<p>The following resources about the embedding API are also available:</p>

<ul>
  <li><a href="#documentation/embedding_crosswalk">Getting started with the embedding API</a></li>
  <li><a href="<?php echo $sample_url ?>" target="_blank">Sample code</a></li>
  <li><a href="https://github.com/crosswalk-project/crosswalk/tree/master/runtime/android/sample" target="_blank">Sample project</a></li>
</ul>
