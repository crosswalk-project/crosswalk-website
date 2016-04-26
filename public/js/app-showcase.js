// Rotate app cube on home screen

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

    // Retrieve app list from server
    // If no data received, no apps will display
    $.ajax ( {
	url: '/documentation/community/apps/app-fetch.php',
	type: 'GET',
	success: function (data) {
	    dbRows = JSON.parse(data);
	    if (dbRows != null) {
		shuffle(dbRows);
		prepCubeFace (0);
		rotTimer = setInterval(rotateApp, waitTime);
	    }
	}
    });

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
	$({alpha:(alphaStart)}).animate({alpha:alphaEnd}, {
	    duration: fadeTime,
	    step: function() {
		for (var i=0; i<4; i++) {
		    var f = $("#showcase-face" + i);
		    f.css('outline-color','rgba(36,180,186,' + this.alpha + ')');
		    f.css('background-color','rgba(100,100,100,' + this.alpha + ')');
		}
	    }
	});
    }

    function rotateApp() {
	//create div contents for next cube face
        faceNum = (faceNum+1) % 4;
        prepCubeFace (faceNum);

        //show border of visible face
	bordersVisible(true, faceNum);
	//scroll next cube face into view
        rotDeg += 90;
        $("#showcase-cube").css("transform", "rotateX(-" + rotDeg + "deg)");
	//turn off borders after tranform finished
	setTimeout(bordersVisible, spinTime, false);
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
              "Downloads: " + getNumEst(app.downloads) + 
	    "</div>";
	prepDiv.html (content);

	//now erase the bottom and back of cube (so they don't show during rotate
	$("#showcase-face" + (faceNum+1)%4).html ("");
	$("#showcase-face" + (faceNum+2)%4).html ("");
    }
});
