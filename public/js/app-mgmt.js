//
// Apps Mgmt Page
//

/* jslint browser: true */
/* global $, jQuery, alert, getDateEst, getNumEst */

var selRow = null;

// 17 columns
var cols = ["appid","status","name","email","submitDate","author","publishDate","downloads","price","size","architecture",
            "category","tools", "version","notes","emailToken","storeUrl","authorUrl","image"];

var colLabels = ["ID","Status","Name","Email","Submit Date","Author","Publish Date","Downloads","Price","Size","Architecture",
                 "Category","Tools","Version","Notes","Email Token", "Store URL","Author URL","Image Name"];


function setPreviewValues (row) {
    if (row) {
        $('#pvwLabel').text (row.name);
        $('#pvwImg').attr("src", "/assets/apps/icons/" + row.image);
        if (row.storeUrl) {
            $('#pvwImg').attr("title", row.storeUrl);
            $('#pvwImg').switchClass("appImgNoLink", "appImg", 0);
            $('#pvwImgLink').attr("href", row.storeUrl);
        } else {
            $('#pvwImg').attr("title", "No store URL provided");
            $('#pvwImg').switchClass("appImg", "appImgNoLink", 0);
            $('#pvwImgLink').attr("href", "javascript:");
        }
        $('#pvwAuthor').text (row.author);
        if (row.authorUrl) {
            $('#pvwAuthor').attr("title", row.authorUrl);
            $('#pvwAuthor').switchClass("appAuthorNoLink", "appAuthor", 0);
            $('#pvwAuthorLink').attr("href", row.authorUrl);
        } else {
            $('#pvwAuthor').attr("title", "No author URL provided");
            $('#pvwAuthor').switchClass("appAuthor", "appAuthorNoLink", 0);
            $('#pvwAuthorLink').attr("href", "javascript:");
        }
        $('#pvwPublishDate').text ("Published: " + getDateEst(row.publishDate));
        $('#pvwDownloads').text ("Downloads: " + getNumEst(row.downloads));
        
        selRow = row;
    }
}

// Load preview on selection
function onRowSelected (rowArray) {
    var dbRow = [], i;
    for (i=0; i<cols.length; i++) {
        dbRow[cols[i]] = rowArray[i];
    }
    setPreviewValues (dbRow);
}

function initTable() {
    var appTable = $('#appTable').DataTable ( 
      {   "scrollX":        "100%",
          "scrollY":        "400px",
          "scrollCollapse": true,
          "paging":         false,
          "stateSave":      true,
          "select":         "single",
          "autoWidth":      true,
          "columnDefs": [{ "width": "30px", "targets": 0},
                         { "width": "60px", "targets": 1},
                         { "width": "175px", "targets": 2},
                         { "width": "160px", "targets": 3},
                         { "width": "75px", "targets": 4},
                         { "visible": false, "targets": -1 } //hide last column (image name)
                        ]
      });

    appTable.on( 'select', function (e, dt, type, indexes) {
        if (type=='row' && indexes.length > 0) {
            onRowSelected (appTable.rows(indexes[0]).data()[0]);
            //onRowSelected (appTable.row( this ).data());
        }
    });

    // select first row upon start
    appTable.row(':eq(0)', { page: 'current' }).select();
}

// Create table for Application Database Management Page (apps-mgmt.php)
function printAppTableRow (row) {
    var rowStyle = '';
    if (row.status == 'pending') {
        rowStyle = " style='color:blue'";
    } else if (row.status == 'rejected') {
        rowStyle = " style='color:gray'";
    }
    var output = '';
    output += "<tr" + rowStyle + ">";
    var i;
    for (i=0; i<cols.length; i++) {
        output += "<td title='" + row[cols[i]] + "'>" + row[cols[i]] + "</td>\n";
    }
    output += "</tr>";
    return output;
}

// Go through all apps and create divs
function loadAppTable(dbRows, where) {
    var output, i;
    output = "<table id='appTable' class='display' cellspacing='0'>";
    output += "<thead><tr>";
    for (i=0; i<colLabels.length; i++) {
        output += "<th>" + colLabels[i] + "</th>";
    }
    output += "</tr></thead><tbody>";
    if (dbRows.length > 0) {
        for (i=0; i<dbRows.length; i++) {
            output += printAppTableRow (dbRows[i]);
        }
    } else {
        output += "<tr><td colspan=10>No application records found</td></tr>";
    }
    output += "</tbody></table>";

    $('#appMgmtTableDiv').html (output);

    initTable();
}

$(document).ready (function () {
    //PHP will create a page with var dbRows; and var dbError; 
    if (dbRows) {
        loadAppTable(dbRows, null);
    } else {
        $('#appMgmtTableDiv').html ("<strong>Error:</strong> Application information is not available.<br><br>" + dbError);
    }
});

//------------------------------------------------
function onEditBtn() {
    alert ("Edit Btn");
    window.open("/documentation/community/apps/app-submit.html?id=" + selRow.emailToken, "_self");
}

function onEmailBtn() {
    alert ("Email Btn");
    if (!selRow) {
        alert ("Please select a row.");
        return;
    }
    document.location.href = "mailto:" + selRow.email + 
        "?subject=" + encodeURIComponent("Crosswalk Project Application: " + selRow.name) + 
        "&body=" + encodeURIComponent("Regarding your application '" + selRow.name + "' on the Crosswalk Project application page...");
}
