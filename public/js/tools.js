/*
 * License
 */

/*jslint browser: true */
/*jslint white: true */
/*jslint node: true */
/*jslint plusplus: true */

/*global $, jQuery, alert*/ 

const TOOLS_PAGE = 1;
const HOME_PAGE = 2;
const LOCAL_LANG_COOKIE = "local_lang";

var imgSize = 130;
var hoverImgSize = 150;

function onHomePageImgClick (event) {
    window.open("/documentation/community/tools.html?tool=" + event.data.value, "_self");
}

function addToolImgEventHandlers(index) {
    var img = $("#hp-img" + index);
    img.on('mouseenter mouseleave', function(e) {
        if ($(window).width() < 600) {
            imgSize = 115;
            hoverImgSize = 125;
        }
        $(this).stop().animate({  
            height: (e.type === 'mouseenter' ? hoverImgSize + "px" : imgSize + "px"),
            width:  (e.type === 'mouseenter' ? hoverImgSize + "px" : imgSize + "px")
        }, "fast", "swing");
    });
    img.on('click', {value:index}, onHomePageImgClick);
    img.css('cursor', 'pointer');
}

function createHtmlFromItem (item, pageType, itemWidth) {
    var content;
    if (pageType === TOOLS_PAGE) {
        content = 
        "<div class='tool-cube' id='cube" + item.index + "'>" + 
          (item.link ? "<a href='" + item.link + "'>" : "") + 
          "<img class='tool-img' id='appImg" + item.index + "'" + 
            "src='/assets/tools/" + item.appImg + "'/>" + 
          (item.link ? "</a>" : "") + 
          "<div class='tool-text'>" + 
            "<div class='tool-label'>" + item.name + "</div>" +
            "<div class='tool-description'>"  + item.description + "<br>" + 
              (item.link ? "<div class='tool-link'><a href='" + item.link + "'>" : "") + 
              (item.company ? item.company : "") + 
              (item.link ? "</a></div>" : "") + 
          "</div></div></div>";
         console.log (content + "\n\n");
    } else if (pageType === HOME_PAGE) {
        content = 
        "<div class='tools-hp-cube' style='width:" + itemWidth + "%;' >" + 
          "<a href='/documentation/community/tools.html?tool=" + item.index + "'>" + 
            "<img class='tools-hp-img' id='hp-img" + item.index + "' src='assets/tools/" + item.appImg + "' />" + 
          "</a>" + 
          //"<div class='tools-hp-label'>" + item.name + "</div>" + 
        "</div>";
    }
    return content;
}

function loadToolsOnPage(itemsToLoad, pageType, sortOrder) {
    var divContent="", index, divRowNeeded;
    
    //create elements
    var itemWidth = Math.floor(100 / Math.ceil(itemsToLoad.length/2));
    for (index = 0; index < itemsToLoad.length; index++) {
        divContent += createHtmlFromItem (itemsToLoad[index], pageType, itemWidth);
    }
    if (divContent.length === 0) {
        divContent = "<br><br><h3 style='text-align: center;'>No tools to display</h3>";
    }
    if (pageType === HOME_PAGE) {
        divContent += "<br style='clear: left;' />";
    }
    //console.log (divContent);
    $("#tool-div").html(divContent);
    
    //add event handlers    
    /*if (pageType === HOME_PAGE) {
        for (index = 0; index < itemsToLoad.length; index++) {
            addToolImgEventHandlers(index);
        }
    }*/
}

function loadToolsFromFile(pageType, toolNum) {
    var index, item;
    var items = [];

    //read objects from .json file
    index = 0;
    $.getJSON( "/documentation/community/tools.json", function( data ) {
        $.each( data, function(id, item ) {
            item.index = index;
            item.name = item.name || "*Missing Name*";
            item.appImg = item.appImg || "missing-image.png";
            items.push(item);
            index++;
        });
        // swap toolNum with 0
        if (toolNum != 0 && toolNum < items.length) {
            var toolsRemoved = items.splice(toolNum, 1);
            items.splice(0,0,toolsRemoved[0]);
        }
        loadToolsOnPage (items, pageType);
    });
}

function onToolsPage() {
    // if a parameter is passed into the URL
    var toolNum = getToolURLParameters();
    loadToolsFromFile(TOOLS_PAGE, toolNum);
}
function onHomePageTools() {
    loadToolsFromFile(HOME_PAGE);
}

function readLocalLang() {
    var lang = readCookie(LOCAL_LANG_COOKIE);
    if (lang == undefined) {
        lang = "en";
        createCookie(LOCAL_LANG_COOKIE, lang);
    }
    document.body.className = lang;
}

// -- Get tool # passed to tools page ----------------

function getToolURLParameters() {
    var toolNum = 0;
    //get the values passed in with URL (e.g. ?tool=5 --means the 5th tool in the tool.json file)
    var args = location.search.substring(1).split("&");
    if (args.length === 1) {
        var toolArg = args[0].split("=");
        if (toolArg.length === 2) {
            if ($.isNumeric(toolArg[1])) {
                toolNum = toolArg[1];
            }
        }
    }
    return toolNum;
}

function createCookie(name, value, days) {
    var expires;

    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    } else {
        expires = "";
    }
    document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires + "; path=/";
}

function readCookie(name) {
    var nameEQ = encodeURIComponent(name) + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) return decodeURIComponent(c.substring(nameEQ.length, c.length));
    }
    return null;
}

function eraseCookie(name) {
    createCookie(name, "", -1);
}

function switchToChinese() {
    document.body.className = "zh";
    createCookie(LOCAL_LANG_COOKIE, "zh");
};
function switchToEnglish() {
    document.body.className = "en";
    createCookie(LOCAL_LANG_COOKIE, "en");
};