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
function displayApps(dbRows, where) {
    var output = "", i;
    for (i=0; i<dbRows.length; i++) {
        var row = dbRows[i];
        output += printResultDiv (row);
    }
    output += "<br clear='all' /><br>(App count: " + dbRows.length + ")";
    return output;
}

