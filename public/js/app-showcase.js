// Rotate app cube on home screen
//
// Note: The Crosswalk Project is a BSD-licensed project.  In this file, MIT-licensed code 
// was used from "Andy E" on StackOverflow for detecting page visibility. See addVisibilityListener() below.
// http://stackoverflow.com/questions/1060008/is-there-a-way-to-detect-if-a-browser-window-is-not-currently-active
// MIT license: https://opensource.org/licenses/MIT

$(document).ready(function () { 

    var dbRows = null;
    var rotTimer = null;
    var appRow = 0;
    var faceNum = 0;
    var rotDeg = 0;
    var waitTime = 12000;
    var spinTime = 3000;
    var fadeTime = 1000;
    var imgDir = "/assets/apps/icons/";
    var isPageVisible = true;  //assume when document ready, we are visible
    var isIE = detectIEBrowser();

    function detectIEBrowser() {
	var agent = window.navigator.userAgent;
	if (agent.indexOf ('MSIE ') > 0 ||
	    agent.indexOf('Trident/') > 0 ||
	    agent.indexOf('Edge/') > 0) {
	    return true;
	}
	return false;
    }

    //Subscribe to page visibility changes
    addVisibilityListener();

    //Retrieve app list from server. Start display (if success)
    initAppList();

    // Retrieve app list from server
    // If no data received, no apps will display
    function initAppList() {
	$.ajax ( {
	    url: '/documentation/community/apps/app-fetch.php',
	    type: 'GET',
	    success: function (data) {
		dbRows = JSON.parse(data);
		if (dbRows != null) {
		    shuffle(dbRows);
		    prepCubeFace (0);
		    rotTimer = setInterval(rotateApp, waitTime);
		} else {
		    console.log ("No apps returned from server.");
		}
	    }
	});
    }

    //fisher-yates shuffle
    function shuffle (a) {
	var i=0, j=0, temp=null;
	for (i = a.length - 1; i > 0; i -= 1) {
	    j = Math.floor(Math.random() * (i + 1));
	    temp = a[i];
	    a[i] = a[j];
	    a[j] = temp;
	}
    }

    function bordersVisible(show,faceNum) {
	var alphaStart = (show===true?0:1);
	var alphaEnd =   (show===true?1:0);
	$({alpha:alphaStart}).animate({alpha:alphaEnd}, {
	    duration: fadeTime,
	    step: function() {
		for (var i=faceNum; i<=faceNum+1; i++) {
		    var f = $("#showcase-face" + (i%4));
		    f.css('outline-color','rgba(36,180,186,' + this.alpha + ')');
		    f.css('background-color','rgba(255,255,255,' + (this.alpha*0.2) + ')');
		}
	    }
	});
    }

    function rotateApp() {
	//only initiate rotation if page is visible
	if (!dbRows || !isPageVisible) {
	    return;  //no app rotation/change unless visible
	}

	if (!isIE) { //rotate not working right in IE
            //show border of visible faces (current and +1)
	    bordersVisible(true, faceNum);
	    //create div contents for next cube face
	    prepCubeFace ((faceNum+1)%4);
	    //scroll next cube face into view
            rotDeg += 90;
            $("#showcase-cube").css("transform", "rotateX(-" + rotDeg + "deg)");

	    //turn off borders after tranform finished
	    setTimeout(bordersVisible, spinTime, false, faceNum);
	    //erase bottom of cube, now just made hidden
	    setTimeout(function (oldFaceNum) {$("#showcase-face" + oldFaceNum).html ("")}, spinTime, faceNum);

	    //newly scrolled face number
            faceNum = (faceNum+1) % 4;
	} else { //rotation not working right in IE
	    prepCubeFace (0);
	}
    }

    function prepCubeFace (faceNum) {
	if (!dbRows) {
	    return;
	}
	var app = dbRows[appRow++];
	if (appRow >= dbRows.length) {
	    appRow = 0;
	}

	// tbd: var hasStoreUrl = (app.storeUrl && (app.storeUrl.length > 0));

	var prepDiv = $("#showcase-face" + faceNum);
	var content = "<a href='" + app.storeUrl + "'>" + 
	    "<img src='" + imgDir + app.image + "' class='showcase-faceImg' /></a>" + 
            "<div class='showcase-faceDiv'>" + 
              "<a href='" + app.storeUrl + "'>" + app.name + "</a> <br>" + 
              "<span class='meta'>Downloads: " + getNumEst(app.downloads) + "</span>" + 
	    "</div>";
	prepDiv.html (content);
    }

    function addVisibilityListener() {
	var hidden = "hidden";
	if (hidden in document) {
	    document.addEventListener("visibilitychange", onVisibilityChange);
	} else if ((hidden = "mozHidden") in document) {
	    document.addEventListener("mozvisibilitychange", onVisibilityChange);
	} else if ((hidden = "webkitHidden") in document) {
	    document.addEventListener("webkitvisibilitychange", onVisibilityChange);
	} else if ((hidden = "msHidden") in document) {
	    document.addEventListener("msvisibilitychange", onVisibilityChange);
	} else if ("onfocusin" in document) { 	// IE 9 and lower
	    document.onfocusin = document.onfocusout = onVisibilityChange;
	} else {  // All others
	    window.onpageshow = window.onpagehide = window.onfocus = window.onblur = onVisibilityChange;
	}

	function onVisibilityChange (evt) {
	    var v = "visible", h = "hidden";
	    var evtMap = {focus:v, focusin:v, pageshow:v, blur:h, focusout:h, pagehide:h};
	    evt = evt || window.event;
	    if (evt.type in evtMap) {
		isPageVisible = !(evtMap[evt.type] == "hidden");
	    } else {
		isPageVisible = !(this[hidden]);  //if this[hidden] is defined, page is hidden
	    }
	}

	// set the initial state (but only if browser supports the Page Visibility API)
	if (document[hidden] !== undefined) {
	    onVisibilityChange({type: document[hidden] ? "blur" : "focus"});
	}
    }

});
