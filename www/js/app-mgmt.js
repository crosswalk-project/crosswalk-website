//
// Apps Mgmt Page
//

/* jslint browser: true */
/* global $, jQuery, alert, getDateEst, getNumEst */

var selRow = null;

// 17 columns
var cols = ["appid","status","name","email","submitDate","author","publishDate","downloads",
            "price","size","architecture","category","tools", "version","notes","emailToken",
            "storeUrl","authorUrl","image"];

var colLabels = ["ID","Status","Name","Email","Submit Date","Author","Publish Date","Downloads",
                 "Price","Size","Architecture","Category","Tools","Version","Notes","Email Token", 
                 "Store URL","Author URL","Image Name"];

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

    // Set hidden field 'appid' in "Update status" form to selected row's appid
    $('#appid').val(selRow.appid);
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
    var output = '';
    output += "<tr id='appid" + row.appid + "' class='" + row.status + "'>";
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

//--------------------------------------------------

function updateStatus (type, msg) {
    alert (msg);
    if (type == "success") {
        var appid = "#appid" + selRow.appid;
        var table = $('#appTable').DataTable();
        var row = table.row(appid);
        table.cell(row, 1).data( selRow.status );

        //update style class w/jquery because I don't know how to do it with datatables
        $(appid).removeClass();
        $(appid).addClass(selRow.status);

        //re-select the changed row
        row.select();
    }
}

function onStatusChangeSubmit(e) {
    e.stopPropagation(); // Stop stuff happening
    e.preventDefault(); //Prevent Default action

    // Make sure we have an appid
    if ($('#appid').val() == '') {
        alert ("No valid application selected.  (appid not set). Exiting.");
        return;
    }
    selRow.status = $('#status').val(); //just used to update local table on success (see updateStatus fn)

    // We will send an email on 'accepted' status.
    if (selRow.status == "accepted" && !confirm("Changing the status to 'accepted' will send an email to the application author.\nDo you want to continue?")) {
        return;
    }

    // spinner: $("#multi-msg").html("<img src='loading.gif'/>");
    var formObj = $(this);
    var formURL = formObj.attr("action");

    if (window.FormData === undefined) {  // for HTML5 browsers
        alert ("This browser does not support the form upload feature. Please use a newer browser.");
        return;
    }

    var formData = new FormData(this);
    $userMsg = "";
    $.ajax({
        url: formURL,
        type: 'POST',
        data:  formData,
        mimeType:"multipart/form-data",
        cache: false,
        contentType: false,
        processData:false,
        success: function(data, textStatus, jqXHR) {
            if (typeof data.error == 'undefined') {
                updateStatus ("success", data);
            } else {
                updateStatus ("error", "An error occured during form submission. " + data.error);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            updateStatus ("error", "The AJAX Request Failed. " + errorThrown);
        }
    });
}

//------------------------------------------------------

$(document).ready (function () {
    //PHP will create a page with var dbRows; and var dbError; 
    if (dbRows) {
        loadAppTable(dbRows, null);
    } else {
        $('#appMgmtTableDiv').html ("<strong>Error:</strong> Application information is not available.<br><br>" + dbError);
    }

    //Callback handler for form submit event (status changed)
    $(".appStatusForm").submit(onStatusChangeSubmit);
});


//------------------------------------------------
function onEditBtn() {
    window.open("/documentation/community/apps/app-submit.php?id=" + selRow.emailToken, "_self");
}

function onEmailBtn() {
    if (!selRow) {
        alert ("Please select a row.");
        return;
    }
    document.location.href = "mailto:" + selRow.email + 
        "?subject=" + encodeURIComponent("Crosswalk Project Application: " + selRow.name) + 
        "&body=" + encodeURIComponent("Regarding your application '" + selRow.name + "' on the Crosswalk Project application page...");
}
