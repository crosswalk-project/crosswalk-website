/*
 * License
 */

/*jslint browser: true */
/*jslint white: true */
/*jslint node: true */
/*jslint plusplus: true */

/*global $, jQuery, alert*/ 

const WEB_API_ITEMS = 1;
const EXP_API_ITEMS = 2;

function createTableCell (itemText, itemLink, className, isHeader) {
    var cellContent = "";
    if (!className || className.length === 0)
        className = (itemText == 'Y' || (itemText.length > 1 && itemText.charAt(0) == 'Y') ? "can-yes" :
                         (itemText == 'N' || (itemText.length > 1 && itemText.charAt(0) == 'N') ? "can-no" :
                          (itemText == "Planning" || itemText == "Ongoing" ? "can-partial" : "")));
    var cellContent = 
        (isHeader ? "<th " : "<td ") + "class='" + className + "'>" + 
        (itemLink ? "<a href='" + itemLink + "'>" + itemText + "</a>" : itemText) + (isHeader ? "</th>" : "</td>") + "\n";
    console.log (cellContent);
    return cellContent;
}

function createApiTableRow (item) {
    var content = 
        "<tr>" + 
        createTableCell (item.feature, item.featureLink, "can-td", true) + 
        createTableCell (item.category, item.categoryLink, "can-td") + 
        createTableCell (item.android, item.androidLink) + 
        createTableCell (item.linux, item.linuxLink) + 
        createTableCell (item.ios, item.iosLink) + 
        createTableCell (item.windows, item.windowsLink) + 
        "</tr>\n";
    return content;
}

function loadApiTable(apiItemsType, apiItems) {
    var content="", index;

    content += "<table class='can-table'>\n";
    content += " <tr><th>Feature</th><th>Category</th><th class='can-center'>Android</th><th class='can-center'>Linux</th>" + 
        "<th class='can-center'>iOS</th><th class='can-center'>Windows</th></tr>\n";

    for (index = 0; index < apiItems.length; index++) {
        console.log ("creating..." + apiItems[index].feature);
        content += createApiTableRow (apiItems[index]);
    }
    content += "</table>";

    if (apiItemsType == WEB_API_ITEMS) {
        $("#webApiFeatures").html(content);
    } else {
        $("#expApiFeatures").html(content);
    }
    console.log (content);
}

function loadCanIUse() {
    var pageContent="", index, item;
    var webApiItems = [];
    var expApiItems = [];
    
    //read objects from .json file
    console.log ("Reading objects...");
    index = 0;
    $.getJSON( "/documentation/apis/caniuse.json", function( data ) {
        $.each( data, function(id, item ) {
            item.index = index;
            console.log (item.feature);
            if (item.category === "W3C") {
                webApiItems.push(item);
            } else {
                expApiItems.push(item);
            }
            index++;
        }); 
        loadApiTable(WEB_API_ITEMS, webApiItems);
        loadApiTable(EXP_API_ITEMS, expApiItems);
    });
}

