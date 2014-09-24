<?php
$base_url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['CONTEXT_PREFIX']. '/apis/';

$docs_url_v1 = $base_url . 'embeddingapidocs/reference/org/xwalk/core/package-summary.html';
$sample_url_v1 = $base_url . 'embeddingapidocs/reference/org/xwalk/core/XWalkView.html';
$docs_url_v2 = $base_url . 'embeddingapidocs_v2/reference/org/xwalk/core/package-summary.html';
$sample_url_v2 = $base_url . 'embeddingapidocs_v2/reference/org/xwalk/core/XWalkView.html';
$docs_url_v3 = $base_url . 'embeddingapidocs_v3/index.html';
$sample_url_v3 = $base_url . 'embeddingapidocs_v3/org/xwalk/core/XWalkView.html';
?>
<h1>Embedding API</h1>

<p>The embedding API enables Crosswalk to be used as a web view for Android applications, as an alternative to the stock <a href="http://developer.android.com/guide/webapps/webview.html" target="_blank">WebView</a>. You can use it to load HTML pages or whole web applications inside a Java application running on Android.</p>

<p>The <a href="#documentation/embedding_crosswalk">Getting started with the embedding API</a> article covers basic use of the API.</p>

<p>Links to more references and resources are given below.</p>

<h2>Version 3 - Crosswalk 9</h2>

<ul>
  <li><a href="<?php echo $docs_url_v3 ?>" target="_blank">Javadocs v3</a></li>
  <li><a href="<?php echo $sample_url_v3 ?>" target="_blank">Sample code for v3</a></li>
</ul>

<h2>Version 2 - Crosswalk 8</h2>

<ul>
  <li><a href="<?php echo $docs_url_v2 ?>" target="_blank">Javadocs v2</a></li>
  <li><a href="<?php echo $sample_url_v2 ?>" target="_blank">Sample code for v2</a></li>
  <li><a href="https://github.com/crosswalk-project/crosswalk/tree/master/runtime/android/sample" target="_blank">Sample project</a></li>
</ul>

<h2>Version 1 - Crosswalk 6 and 7</h1>

<ul>
  <li><a href="<?php echo $docs_url_v1 ?>" target="_blank">Javadocs v1</a></li>
  <li><a href="<?php echo $sample_url_v1 ?>" target="_blank">Sample code for v1</a></li>
</ul>
