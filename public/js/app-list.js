
// App array that matches search value -- temporary whenever there is a search string active
var appSearchArray = null;

// Create application div for apps.php 
/*
--- Finished app div: ----
<div class='appCube' id='cube0'>
  <div class='appLabel'>Tiny Flashlight LED</div>
  <a href='https://play.google.com/store/apps/details?id=com.devuni.flashlight&hl=en'>
    <img class='appImg' id='appImg0' src='/assets/apps/tiny-flashlight-led84.jpg'/>
  </a>
  <div class='appDetailLabel'>
    <a href='https://www.intel.com'>
      <div class='appAuthor'>Nikolay Ananiev</div>
    </a>
    <div>Published: Jan 2015</div>
    <div>Downloads: 50M+</div>
  </div>
</div>
*/
function printResultDiv (row) {
    var hasStoreUrl = (row.storeUrl && (row.storeUrl.length > 0));
    var hasAuthorUrl = (row.authorUrl && (row.authorUrl.length > 0));
    if (!hasAuthorUrl && hasStoreUrl) {
        row.authorUrl = row.storeUrl; //use store URl if no author URL
        hasAuthorUrl = true;
    }
    var output = 
        "<div class='appCube'>\n" +
        "  <div class='appLabel'>" + row.name + "</div>\n" +
        (hasStoreUrl ? 
            "  <a href='" + row.storeUrl + "'>\n" + 
            "    <img class='appImg' src='/assets/apps/icons/" + row.image + "'/>\n" +
            "  </a>\n"
        :   "  <img class='appImgNoLink' src='/assets/apps/icons/" + row.image + "'/>\n") +

        "  <div class='appDetailLabel'>\n" + 
        (hasAuthorUrl ? 
            "    <a href='" + row.authorUrl + "'>\n" + 
            "      <div class='appAuthor'>" + row.author + "</div>\n" +
            "    </a>\n"
        :   "     <div class='appAuthorNoLink'>" + row.author + "</div>\n") +
        "    <div>Published: " + (row.publishDate ? getDateEst (row.publishDate)
                                  : "N/A") + "</div>\n" +
        "    <div>Downloads: " + (row.downloads   ? getNumEst (row.downloads)
                                  : "N/A") + "</div>\n" + 
        "  </div>\n" +
        "</div>\n";
    return output;
}

// Go through all apps and create divs
function displayApps(dbRows) {
    var output = "", i;
    for (i=0; i<dbRows.length; i++) {
        var row = dbRows[i];
        output += printResultDiv (row);
    }
    output += "<br clear='all' /><br>(App count: " + dbRows.length + ")";
    return output;
}

// Sort function
var sort_by = function (field, asc, primer) {
   var key = primer ? 
       function(x) {return primer(x[field])} : 
       function(x) {return x[field]};
   asc = !asc ? -1 : 1;
   return function (a, b) {
       return a = key(a), b = key(b), asc * ((a > b) - (b > a));
   } 
}

function sortApps (sort) {
    // only sort visible (matching search) apps
    var appSortArray = (appSearchArray ? appSearchArray : dbRows);

    switch (sort) {
      case "AZ":
        appSortArray.sort (sort_by ("name", true, function(a){return a.toUpperCase()}));
        break;
      case "ZA":
        appSortArray.sort (sort_by ("name", false, function(a){return a.toUpperCase()}));
        break;
      case "DownloadsHigh":
        appSortArray.sort (sort_by ("downloads", false, parseInt));
        break;
      case "PublishedNew":
        appSortArray.sort (sort_by ("publishDate", false));
        break;
      case "PublishedOld":
        appSortArray.sort (sort_by ("publishDate", true));
        break;
    }
    $('#appListGrid').html (displayApps(appSortArray));
}

function searchApps (str) {
    if (str == "") {
        $('#appListGrid').html (displayApps(dbRows));
        appSearchArray = null;
        return;
    }
    appSearchArray = $.grep(dbRows, function (app) { 
	//case insensitive search
        return (app.author.toLowerCase().indexOf(str.toLowerCase()) > -1 ||
		app.name.toLowerCase().indexOf(str.toLowerCase()) > -1);
    });
    $('#appListGrid').html (displayApps(appSearchArray));
}


$(function() {
    $("#appListSort" ).change(function() {
        sortApps ($(this).val());
    });

    $("#appListSearch").on ('input', function () {
        searchApps($(this).val());
    });
});



