<!DOCTYPE html>
<html id="top" lang="en-us">
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
    <meta property="og:url" content="http://crosswalk-project.org/deploy" />
    <meta property="og:description" content="Enable the most advanced web innovations with the Crosswalk Project web runtime to develop powerful Android and Cordova apps." />
    <meta property="og:image" content="/assets/crosswalk-og-banner.jpg" />

    <!-- Twitter -->
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:url" content="http://crosswalk-project.org/deploy" />
    <meta name="twitter:title" content="Crosswalk" />
    <meta name="twitter:description" content="Enable the most advanced web innovations with the Crosswalk Project web runtime to develop powerful Android and Cordova apps." />
    <meta name="twitter:site" content="@xwalk_project" />

    <!-- Relevant original Crosswalk Project JS -->
    <script src="/js/utils.js"></script>
    <script src="/js/xwalk.js"></script>
    <script src="/js/versions.js"></script>
    <script src="/js/demos.js"></script>
    <script src="/js/tools.js"></script>

  </head>
  <body>
    <!-- If the current page is an index less than two
         directories deep or in the root directory,
         leave it alone so we can do custom layouts.
         Otherwise, provide the header and nav. -->
    
    <div class="container">
      <div class="doc-header">
        

  

  <div class="doc-logo-div">
     <a href="/" class="doc-logo-link">
       <img src="/assets/identity/crosswalkproject-logo-horizontal-dark.png" class="doc-logo-img">
     </a>
  </div>
  <div class="doc-nav-div">
    <ul class="doc-nav-list">
      <li class="doc-nav-item">
        <a href="/documentation/getting_started.html" class="doc-nav-link">Documentation</a>
      </li>
      <li class="doc-nav-item">
        <a href="/blog" class="doc-nav-link">Blog</a>
      </li>
      <li class="doc-nav-item">
        <a href="/contribute" class="doc-nav-link">Contribute</a>
      </li>
      <li class="doc-nav-item">
        <a href="https://github.com/crosswalk-project/crosswalk-website/wiki" class="doc-nav-link">Wiki</a>
      </li>
      <li class="doc-nav-item hide-on-small">
        <a href="/documentation/about/faq.html" class="doc-nav-link">FAQ</a>
      </li>
      <li class="doc-nav-item hide-on-small">
        <a href="/documentation/getting_started.html" class="doc-nav-link" style="border:1px solid #ec543b;padding:8px;color:#ec543b;" >get started</a>
      </li>
    </ul>
  </div>
  

      </div>
      <br />
      <div class="doc-main">
        <div class="row">
          
          <?php
	/*
	 * Copyright © 2012 by Eric Schultz.
	 *
	 * Issued under the MIT License
	 *
	 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), 
	 * to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, 
	 * and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
	 *
	 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
	 *
	 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, 
	 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER 
	 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
	 */
	 
	set_time_limit(90);

	include '../util/local-config.php';

	// Make sure the configuration is setup
	if (!isset($arrConfig) || empty($arrConfig)) {
		error_log("GitHub Webhook Error: missing local-config.php or no configuration definitions setup");
		exit;
	}

	// Check for the GitHub WebHook Payload
	if (!isset($_POST['payload'])) {
		error_log("GitHub Webhook Error: missing expected POST parameter 'payload'");
		exit;
	}
  
  // Check for the HTTP_X_HUB_SIGNATURE
  if(!isset($_SERVER['HTTP_X_HUB_SIGNATURE'])) {
  	http_response_code(401);
  	error_log("GitHub Webhook Error: Secret (X-Hub-Signature header) is missing from request. Have you set a secret in GitHub's project settings?");
  }

	// Grab the tastylious JSON payload from GitHub
	$objPayload = json_decode(stripslashes($_POST['payload']));

  // Get the request body
  $payloadBody = false;
  switch($_SERVER['CONTENT_TYPE']) {
  	case 'application/json':
  		echo "Received JSON data in body.\n";
  		$payloadBody = file_get_contents('php://input');
  		break;
  	case 'application/x-www-form-urlencoded':
  		echo "Received URL-encoded form data in body.\n";
  		$payloadBody = file_get_contents('php://input');
  		break;
  	default:
  		http_response_code(400);
  		error_log("GitHub Webhook Error: Don't know what to do with {$_SERVER['CONTENT_TYPE']} content type.");
  } 
  if(!$payloadBody) {
  	http_response_code(400);
  	error_log('GitHub Webhook Error: No POST body sent.');
  }
  
	// Loop through the configs to see which one matches the payload
	foreach ($arrConfig as $strSiteName => $arrSiteConfig) {
		
		// Merge in site config defaults
		$arrSiteConfig = array_merge(
			array(
				'repository' => '*',
        'secretkey' => '*',
				'branch' => '*',
        'server' => '*',
				'execute' => array()
			), 
			$arrSiteConfig
		);
    
    // Hashed secret key
    $secretKey = "sha1=" . hash_hmac('sha1', $payloadBody, $arrSiteConfig['secretkey'], false);
    
    // Secret key check
    if(($arrSiteConfig['secretkey'] != '*') && md5($secretKey) !== md5($_SERVER['HTTP_X_HUB_SIGNATURE'])) {
      error_log("GitHub Webhook Error: Secret (X-Hub-Signature header) is wrong or does not match request body.");
      exit();
    }

		// Repository name check
		if (($arrSiteConfig['repository'] != '*') && ($arrSiteConfig['repository'] != $objPayload->repository->name)) {
      error_log("GitHub Webhook Error: Repository sent does not match local-config.");
      exit();
		}
		   
		// Release and production check
		if (($arrSiteConfig['server'] == 'production') && (isset($objPayload->release)) && ($objPayload->release->draft != 'true') && ($objPayload->release->prerelease != 'true')) {
			$arrSiteConfig['execute'] = (array)$arrSiteConfig['execute'];

			foreach ($arrSiteConfig['execute'] as $arrCommand) {
				$arrOutput = array();
				exec($arrCommand, $arrOutput);

				if (isset($boolDebugLogging) && $boolDebugLogging) {
					error_log("GitHub Webhook Update (" . $strSiteName . "):\n" . implode("\n", $arrOutput));
				}
			}
    }
  
		// Push and non-production check
		elseif (($arrSiteConfig['server'] != 'production') && (isset($objPayload->ref)) && ($arrSiteConfig['branch'] != '*') && ('refs/heads/'.$arrSiteConfig['branch'] == $objPayload->ref)) {
			$arrSiteConfig['execute'] = (array)$arrSiteConfig['execute'];

			foreach ($arrSiteConfig['execute'] as $arrCommand) {
				$arrOutput = array();
				exec($arrCommand, $arrOutput);

				if (isset($boolDebugLogging) && $boolDebugLogging) {
					error_log("GitHub Webhook Update (" . $strSiteName . "):\n" . implode("\n", $arrOutput));
				}
			}
		}
	}
          
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
          <div  class="footer-div">
            <img src="/assets/Twitter_logo_blue.png" width="20px" /> Follow  <a href="http://twitter.com/xwalk_project">@xwalk_project on Twitter</a> for the latest developer activities and project updates.
          </div>
          <div class="footer-div">
             Latest blog post:</br>
              
              
                <b><a href="/blog">Introducing Crosswalk Project for Debian Linux</a></b><br/>
                &nbsp;(<span ><time class="js-vagueTime" datetime="Fri, 03 Jul 2015 12:00:00 GMT">2015-07-03T12:00</time></span>)
              
              <br/>
           </div>
          <div class="footer-div">
              <strong><a href="/feed.xml"><img src="/assets/rss-icon-16.gif" style="vertical-align:middle" /> RSS Feed</a></strong>
          </div>
          <div class="footer-div">
             <a href="/documentation">Documentation</a> &nbsp;
             <a href="/blog">Blog</a> &nbsp;
             <a href="/documentation/downloads.html">Downloads</a> <br />
             <a href="https://crosswalk-project.org/jira/secure/Dashboard.jspa">Issues</a> &nbsp;
             <a href="https://github.com/crosswalk-project">GitHub source</a> &nbsp;
             <a href="/sitemap.html">Sitemap</a> <br/>
          </div>
        </div>
        <div class="row">
            <small>
              The Crosswalk Project was created by the Intel Open Source Technology Center. Copyright © 2013–2015 Intel Corporation. All rights reserved. <a href="https://github.com/crosswalk-project/crosswalk-website/wiki/Privacy-Policy">Privacy policy</a>. *Other names and brands may be claimed as the property of others.
            </small>
        </div>
      </div>
    </footer>

    
    
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
