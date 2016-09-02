<!DOCTYPE html>
<html id="top">
  <head>
    <meta charset="utf-8">
    <title>The Crosswalk Project</title>
    <link rel="shorcut icon" href="/assets/favicon.ico" type="image/x-icon" />
    <link rel="icon" href="/assets/favicon.ico" type="image/x-icon" />
    <script>
      WebFontConfig = {
        custom: {
          families: ['Clear Sans'],
          urls: ['/css/fonts.css']
        },
        google: {
          families: ['Source Code Pro:n4,n6']
        },
        timeout: 2000
      };
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script async defer src="//ajax.googleapis.com/ajax/libs/webfont/1.5.3/webfont.js"></script>
    <link rel="stylesheet" href="/css/main.css">

    <meta name="description" content="Enable the most advanced web innovations with the Crosswalk Project web runtime to develop powerful Android and Cordova apps." />
    <meta name="author" content="Crosswalk" />
    <meta name="handheldfriendly" content="true" />
    <meta name="mobileoptimized" content="320" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="cleartype" content="on" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <!-- Facebook -->
    <meta property="og:side_name" content="Crosswalk" />
    <meta property="og:title" content="Crosswalk" />
    <meta property="og:url" content="http://crosswalk-project.org/documentation/downloads_zh" />
    <meta property="og:description" content="Enable the most advanced web innovations with the Crosswalk Project web runtime to develop powerful Android and Cordova apps." />
    <meta property="og:image" content="/assets/crosswalk-og-banner.jpg" />

    <!-- Twitter -->
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:url" content="http://crosswalk-project.org/documentation/downloads_zh" />
    <meta name="twitter:title" content="Crosswalk" />
    <meta name="twitter:description" content="Enable the most advanced web innovations with the Crosswalk Project web runtime to develop powerful Android and Cordova apps." />
    <meta name="twitter:site" content="@xwalk_project" />

    <!-- Relevant original Crosswalk Project JS -->
    <script src="/js/utils.js"></script>
    <script src="/js/xwalk.js"></script>
    <script src="/js/versions.js"></script>
    <script src="/js/demos.js"></script>
    <script src="/js/testimonials.js"></script>
    <script src="/js/tools.js"></script>
    <script src="/js/qualityindicators.js"></script>
    <script src="/js/i18n.js"></script>
    <script src="/js/highlight.pack.js"></script>
    <script>hljs.initHighlightingOnLoad();</script>
  </head>
  <body>
    <!-- If curr page named 'index' and less than 2 dirs deep,
         do custom layouts. (home screen, blog, app-mgmt page)
         Else, provide doc header and nav. -->
    
    <div class="container">
      <div class="doc-header">
        


  <div class="doc-logo-div">
     <a href="/index_zh.html" class="doc-logo-link">
       <img src="/assets/identity/crosswalkproject-logo-horizontal-dark.png" class="doc-logo-img">
     </a>
  </div>
  <div class="doc-nav-div">
    <ul class="doc-nav-list">
      <li class="doc-nav-item">
        <a href="/documentation/getting_started_zh.html" class="doc-nav-link">文档</a>
      </li>
      <li class="doc-nav-item">
        <a href="/blog/index_zh.html" class="doc-nav-link">博客</a>
      </li>
      <li class="doc-nav-item hide-on-small">
        <a href="/contribute/index_zh.html" class="doc-nav-link">贡献</a>
      </li>
      <li class="doc-nav-item hide-on-small">
        <a href="https://github.com/crosswalk-project/crosswalk-website/wiki" class="doc-nav-link">维基</a>
      </li>
      <li class="doc-nav-item hide-on-small">
        <a href="/documentation/about/faq_zh.html" class="doc-nav-link">常见问题</a>
      </li>
      <li class="doc-nav-item">
	<span class="i18n-label" id="i18n-label">
	  <span id="i18n-inner">
	    <img src="/assets/i18n-globe.png" class="i18n-globe" />
	    中文版
	    <img src="/assets/i18n-arrow.png" class="i18n-arrow"/>
	  </span>
	  <div id="i18n-menu" class="i18n-menu i18n-menu-light">
            <a onclick="switchLanguage('English')">English</a>
            <a onclick="switchLanguage('Chinese')">中文版</a>
	  </div>
	</span>
      </li>
    </ul>
  </div>
  

      </div>
      <br />
      <div class="doc-main">
        <div class="row">
          
          <div id="translation-missing-toaster">
  <p style="padding:10px"> 抱歉，该网页目前还不存在中文版本，请继续浏览其他网页！ </p>
</div>

          



<nav id="contents" class="article-toc nav-toggleContainer">
  <a href="#contents" id="contents-toggle" class="button button--small button--tertiary nav-toggle">Table of Contents</a>
  <a href="./#contents-toggle" class="button button--small button--tertiary nav-toggle--dummy">Table of Contents</a>
  <ul class="article-list nav-toggleHide">
    
        <li class="article-item ">
        <a class="article-link" href="/documentation/about_zh.html">关于</a>
        
        </li>
    
        <li class="article-item ">
        <a class="article-link" href="/documentation/getting_started_zh.html">开始</a>
        
        </li>
    
        <li class="article-item ">
        <a class="article-link" href="/documentation/crosswalk-app-tools_zh.html">Crosswalk App Tools</a>
        
        </li>
    
        <li class="article-item ">
        <a class="article-link" href="/documentation/android_zh.html">Android</a>
        
        </li>
    
        <li class="article-item ">
        <a class="article-link" href="/documentation/ios_zh.html">iOS</a>
        
        </li>
    
        <li class="article-item ">
        <a class="article-link" href="/documentation/windows_zh.html">Windows</a>
        
        </li>
    
        <li class="article-item ">
        <a class="article-link" href="/documentation/linux_zh.html">Linux</a>
        
        </li>
    
        <li class="article-item ">
        <a class="article-link" href="/documentation/cordova_zh.html">Cordova</a>
        
        </li>
    
        <li class="article-item ">
        <a class="article-link" href="/documentation/manifest_zh.html">Manifest</a>
        
        </li>
    
        <li class="article-item ">
        <a class="article-link" href="/documentation/apis_zh.html">API</a>
        
        </li>
    
        <li class="article-item ">
        <a class="article-link" href="/documentation/shared_mode_zh.html">共享模式</a>
        
        </li>
    
        <li class="article-item ">
        <a class="article-link" href="/documentation/download_mode_zh.html">下载模式</a>
        
        </li>
    
        <li class="article-item ">
        <a class="article-link" href="/documentation/crosswalk_lite_zh.html">Crosswalk Lite</a>
        
        </li>
    
        <li class="article-item ">
        <a class="article-link" href="/documentation/samples_zh.html">样例</a>
        
        </li>
    
        <li class="article-item ">
        <a class="article-link" href="/documentation/community_zh.html">社区</a>
        
        </li>
    
        <li class="article-item ">
        <a class="article-link" href="/documentation/qa_zh.html">质量保证</a>
        
        </li>
    
  </ul>

</nav>

<article class="article article--hasToC">
  <?php
//Update links to Crosswalk download files from
// https://download.01.org/crosswalk/releases/crosswalk/...

ini_set('display_errors', 'On');
error_reporting(E_ALL);

$stableVersion = "N/A";
$betaVersion = "N/A";
$canaryVersion = "N/A";
$baseUrl = "https://download.01.org/crosswalk/releases/crosswalk/android";


// Get the version from website content. Return val example: "16.45.421.19"
function getVersion ($subject)
{
    $matches = array();
    $pattern = "/crosswalk\-\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\.zip/i";
    $retVal = preg_match ($pattern, $subject, $matches);
    if ($retVal) {
        //return version number
        $retVal = substr($matches[0], 10, strlen($matches[0]) - 14);
    } else {
        $retVal = "N/A";
    }
    return $retVal;
}


// create 3 cURL resources (async retrieve stable,beta,canary info)
$ch1 = curl_init();
curl_setopt($ch1, CURLOPT_URL, $baseUrl . "/stable/latest");
curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch1, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch1, CURLOPT_HEADER, false);

$ch2 = curl_init();
curl_setopt($ch2, CURLOPT_URL, $baseUrl . "/beta/latest");
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch2, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch2, CURLOPT_HEADER, false);

$ch3 = curl_init();
curl_setopt($ch3, CURLOPT_URL, $baseUrl . "/canary/latest");
curl_setopt($ch3, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch3, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch3, CURLOPT_HEADER, false);

$mh = curl_multi_init();
curl_multi_add_handle($mh,$ch1);
curl_multi_add_handle($mh,$ch2);
curl_multi_add_handle($mh,$ch3);

do {
        curl_multi_exec($mh, $running);
            curl_multi_select($mh);
} while ($running > 0);

$r1 = curl_multi_getcontent($ch1);
$r2 = curl_multi_getcontent($ch2);
$r3 = curl_multi_getcontent($ch3);
$stableVersion = getVersion ($r1);
$betaVersion   = getVersion ($r2);
$canaryVersion = getVersion ($r3);

//close handles
curl_multi_remove_handle($mh, $ch1);
curl_multi_remove_handle($mh, $ch2);
curl_multi_remove_handle($mh, $ch3);
curl_multi_close($mh);

?>

<style>
.downloads-table {
        text-align:center;
}
</style>

<h1>下载</h1>

<p>Crosswalk为多种操作系统和平台提供二进制文件。</p>

<p>通过Crosswalk编译web应用时，推荐使用基于NPM的<a href="/documentation/crosswalk-app-tools.      html">Crosswalk App Tools</a>，详细信息请参见<a href="/documentation/getting_started.html">开始</a>页面。该页面主要用于下载非稳定或者共享库版本。</p>

<p>推荐Cordova开发者使用<a href="https://crosswalk-project.org/documentation/cordova.html">Crosswalk Webview插件</a>, 它将自动下载相关的Crosswalk库。</p>

  <table class="downloads-table">
    <tr>
        <th>&nbsp;</th>
        <th style="text-align:center"><a href="#Stable" style="color:black">Stable</a><br><?php echo "($stableVersion)"?></th>
        <th style="text-align:center"><a href="#Beta"   style="color:black">Beta</a><br><?php echo "($betaVersion)"?></th>
        <th style="text-align:center"><a href="#Canary" style="color:black">Canary</a><br><?php echo "($canaryVersion)"?></th>
    </tr>

    <tr>
        <th>Android<br/>(ARM + x86)</th>

<?php
if ($stableVersion == "N/A") {
    echo '<td>Not available</td>';
} else {
    echo '      <td><a href="' . $baseUrl . '/stable/latest/crosswalk-' . $stableVersion . '.zip">32-bit</a> / ' .
                '<a href="' . $baseUrl . '/stable/latest/crosswalk-' . $stableVersion . '-64bit.zip">64-bit</a></td>';
}
if ($betaVersion == "N/A") {
    echo '<td>Not available</td>';
} else { 
    echo '      <td><a href="' . $baseUrl . '/beta/latest/crosswalk-'   . $betaVersion   . '.zip">32-bit</a> / ' .
                    '<a href="' . $baseUrl . '/beta/latest/crosswalk-'   . $betaVersion   . '-64bit.zip">64-bit</a></td>';
}
if ($canaryVersion == "N/A") {
    echo '<td>Not available</td>';
} else {
    echo '      <td><a href="' . $baseUrl . '/canary/latest/crosswalk-' . $canaryVersion . '.zip">32-bit</a> / ' .
                '<a href="' . $baseUrl . '/canary/latest/crosswalk-' . $canaryVersion . '-64bit.zip">64-bit</a></td>';
}
?>
    </tr>

    <tr>
       <th>Android webview<br/>(x86)</th>
<?php
//    x86/crosswalk-webview-16.45.421.19-x86.zip
// x86_64/crosswalk-webview-16.45.421.19-x86_64.zip

if ($stableVersion == "N/A") {
    echo '<td>Not available</td>';
} else {
    echo '      <td><a href="' . $baseUrl . '/stable/latest/x86/crosswalk-webview-'    . $stableVersion . '-x86.zip">32-bit</a> / ' .
                                '<a href="' . $baseUrl . '/stable/latest/x86_64/crosswalk-webview-' . $stableVersion . '-x86_64.zip">64-bit</a></td>';
}
if ($betaVersion == "N/A") {
    echo '<td>Not available</td>';
} else { 
    echo '      <td><a href="' . $baseUrl . '/beta/latest/x86/crosswalk-webview-'      . $betaVersion   . '-x86.zip">32-bit</a> / ' .
    '<a href="' . $baseUrl . '/beta/latest/x86_64/crosswalk-webview-'   . $betaVersion   . '-x86_64.zip">64-bit</a></td>';
}
if ($canaryVersion == "N/A") {
    echo '<td>Not available</td>';
} else {
    echo '      <td><a href="' . $baseUrl . '/canary/latest/x86/crosswalk-webview-'    . $canaryVersion . '-x86.zip">32-bit</a> / ' .
                        '<a href="' . $baseUrl . '/canary/latest/x86_64/crosswalk-webview-' . $canaryVersion . '-x86_64.zip">64-bit</a></td>';
}

?>
    </tr>

    <tr>
       <th>Android webview<br/>(ARM)</th>
<?php
//   arm/crosswalk-webview-16.45.421.19-arm.zip
//arm64/crosswalk-webview-16.45.421.19-arm64.zip

if ($stableVersion == "N/A") {
    echo '<td>Not available</td>';
} else {
    echo '      <td><a href="' . $baseUrl . '/stable/latest/arm/crosswalk-webview-'    . $stableVersion . '-arm.zip">32-bit</a> / ' .
                                '<a href="' . $baseUrl . '/stable/latest/arm64/crosswalk-webview-' . $stableVersion . '-arm64.zip">64-bit</a></td>';
}
if ($betaVersion == "N/A") {
    echo '<td>Not available</td>';
} else { 
    echo '      <td><a href="' . $baseUrl . '/beta/latest/arm/crosswalk-webview-'      . $betaVersion   . '-arm.zip">32-bit</a> / ' .
                                    '<a href="' . $baseUrl . '/beta/latest/arm64/crosswalk-webview-'   . $betaVersion   . '-arm64.zip">64-bit</a></td>';
}
if ($canaryVersion == "N/A") {
    echo '<td>Not available</td>';
} else {
    echo '      <td><a href="' . $baseUrl . '/canary/latest/arm/crosswalk-webview-'    . $canaryVersion . '-arm.zip">32-bit</a> / ' .
                                '<a href="' . $baseUrl . '/canary/latest/arm64/crosswalk-webview-' . $canaryVersion . '-arm64.zip">64-bit</a></td>';
}

?>      
    </tr>

</table>

<p><a href="https://download.01.org/crosswalk/releases/crosswalk/">所有版本...</a></p>

<p>另请参见：
   <ul><li><a href="/documentation/getting_started_zh.html">开始</a>: 怎样使用这些下载链接。</li>
      <li><a href="/documentation/qa/quality_dashboard_zh.html">质量Dashboard</a>: 每个版本的测试覆盖率和结果。</li>
      <li><a href="https://github.com/crosswalk-project/crosswalk-website/wiki/release-methodology/version-numbers">发行方式</a>: 版本号的意义。</li>
   </ul>
                                                                                          
<h2>发行说明</h2>

<ul>
   <li><a href="/blog/crosswalk-14-beta.html">Crosswalk-14</a></li>
   <li><a href="/blog/crosswalk-12-beta.html">Crosswalk-12</a></li>
   <li><a href="/blog/crosswalk-11-beta.html">Crosswalk-11</a></li>
   <li><a href="/blog/crosswalk-10-beta.html">Crosswalk-10</a></li>
   <li><a href="https://github.com/crosswalk-project/crosswalk-website/wiki/Crosswalk-9-release-notes">Crosswalk-9</a></li>
   <li><a href="https://github.com/crosswalk-project/crosswalk-website/wiki/Crosswalk-8-release-notes">Crosswalk-8</a></li>
   <li><a href="https://github.com/crosswalk-project/crosswalk-website/wiki/Release-dates">更多... (旧版本和新的规划)</a></li>
</ul>

<p>注意发行说明仅针对stable版本和beta版本。</p>

<h2>质量总结</h2>

<p>关于详细的质量总结，请参见页面<a href="/documentation/quality_dashboard_zh.html">质量Dashboard</a>。 </p>

<h2><a class="doc-anchor" id="Release-channels"></a>发布频道</h2>

<p>这里有三个发行频道(为了增加不稳定性)：</p>

<ol>
　<li>
    <p><a class="doc-anchor" id="Stable"></a><strong>Stable</strong></p>
   <p>Stable版本是针对最终用户发行的。一旦一个Crosswalk发行版本被提升到Stable频道，便只能在该版本中看到针对关键bug和安全问题的新二进制文件。</p>
  </li>

  <li>
    <p><a class="doc-anchor" id="Beta"></a><strong>Beta</strong></p>

    <p>Beta版本主要用于应用开发人员测试Crosswalk新的变化部分，或者是即将要发布为下一个Stable版本的特性。Beta版本的发布是基于自动的基础验收测试(ABAT),人工测试结果和功能变化。Beta版本的发布需要满足一个预期的稳定性水平的要求；但是，它仍然只是一个Beta版，可能包含大量的bug。</p>
</li>

<li>
  <p><a class="doc-anchor" id="Canary"></a><strong>Canary</strong></p>

  <p>Canary版本的发布很频繁（有时候可以达到每天）。It is based on a recent tip of master that passes a full build and automatic basic acceptance test.对于那些只关心Crosswalk最新特性而不想自己编译的开发者而言，Canary版本是一个不错的选择。</p>
  </li>
</ul>

<p>更多的信息请参见<a href="https://github.com/crosswalk-project/crosswalk-website/wiki/Release-methodology">发布频道页面</a>。</p>

<p><a href="https://github.com/crosswalk-project/crosswalk-website/wiki/release-methodology/version-numbers">Crosswalk版本编号页面</a>描述了Crosswalk版本号是如何分配的。</p>

  <footer class="article-next">
    




  </footer>
</article>

          
        </div>
      </div>
    </div>
    

    
 
  <hr class="footer-divider" style="margin-top:125px; margin-bottom:0px" />
    <div style="position:relative; top:-30px;">
       <a href="/"><img src="/assets/cw-logo-circle.png" width="60px" style="display:block; margin: 0 auto;" /></a>
    </div>
    <footer class="footer footer--documentation" >
      <div class="container" >
        <div class="row">
          <div  class="footer-div" language="en">
          <img src="/assets/Twitter_logo_blue.png" width="20px" /> Follow  <a href="http://twitter.com/xwalk_project">@xwalk_project on Twitter</a> for the latest developer activities and project updates.
          </div>
           <div  class="footer-div" language="zh" style="display:none">
           <img src="/assets/Sina_Weibo_Blue.png" width="60px" /> 参考<a href="http://weibo.com/crosswalk">微博</a> 最新的开发者活动或者更新。
          </div>
          <div class="footer-div">
             Latest blog post:</br>
              
              
                <b><a href="/blog">WebCL APIs removed from Crosswalk 22</a></b><br/>
                &nbsp;(<span ><time class="js-vagueTime" datetime="Tue, 30 Aug 2016 09:30:00 GMT">2016-08-30T09:30</time></span>)
              
              <br/>
           </div>
          <div class="footer-div">
              <strong><a href="/feed.xml"><img src="/assets/rss-icon-16.gif" style="vertical-align:middle" /> RSS Feed</a></strong>
          </div>
          <div class="footer-div">
             <a href="/documentation/getting_started_zh.html"> 文档</a> &nbsp;
             <a href="/blog/index_zh.html"> 博客</a> &nbsp;
             <a href="/documentation/downloads_zh.html"> 下载</a> <br />
             <a href="https://crosswalk-project.org/jira/secure/Dashboard.jspa"> 问题</a> &nbsp;
             <a href="https://github.com/crosswalk-project"> GitHub资源</a> &nbsp;
             <a href="/sitemap.html">Sitemap</a> <br/>
          </div>
        </div>
        <div class="row">
            <small>
              Crosswalk项目由Intel Open Source Technology Center开发。版权为 © 2013–2016 Intel Corporation。保留所有权利。 <a href="https://github.com/crosswalk-project/crosswalk-website/wiki/Privacy-Policy">  Privacy策略 </a>  *其他名称和商标可能被声明为他人财产
            </small>
        </div>
      </div>
    </footer>
<script>
    $("[language=en]").css("display","none");
    $("[language=zh]").css("display","block");
</script>

    
    
      <!-- Google Tag Manager -->
      <noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-WC843Q"
      height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
      <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
      new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
      j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
      '//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
      })(window,document,'script','dataLayer','GTM-WC843Q');</script>
      <!-- End Google Tag Manager -->
    
    <script src="/js/smoothScroll.js"></script>
    <script src="/js/vagueTime.js"></script>
    <!-- <script async defer src="/js/trmix.js"></script> -->
  </body>
</html>
