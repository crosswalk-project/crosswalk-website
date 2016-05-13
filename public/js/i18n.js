// This is Javascript for i18n related activities:
//  - Set/read a cookie with the current language setting, currently English ("en") or Chinese ("zh").
//  - Handle language menu dropdown
/*
 * License
 */
/*jslint browser: true */
/*jslint white: true */
/*jslint node: true */
/*jslint plusplus: true */

/*global $, jQuery, alert*/ 

function readLocalLang() {
    var lang = readCookie(LOCAL_LANG_COOKIE);
    if (lang == undefined) {
        lang = "English";
        createCookie(LOCAL_LANG_COOKIE, lang);
    }
    return lang;
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

function switchLanguage(lang) {
    $("body").css("cursor", "progress");

    var url_list, fileName, newfileName;
    //get the url of current page
    var cur_url = window.location.href;
    url_list = cur_url.split('/');
    fileName = url_list[url_list.length - 1];
    //default file is index
    if (fileName == "") {
	fileName = "index";
	cur_url = cur_url + "/index";
    }
    //check if the current page is of Chinese version
    var flag = fileName.lastIndexOf("_zh");
    if (lang === "English") {
	createCookie(LOCAL_LANG_COOKIE, "English");
	if ( flag > 0 ){
            newfileName = fileName.substring(0,flag) + fileName.substring(flag+3);
            if (newfileName.lastIndexOf(".html") == -1) {
		newfileName = newfileName + ".html"
	    }
            window.location.replace(newfileName);
	}
    } else {
	createCookie(LOCAL_LANG_COOKIE, "Chinese");
	if (flag <= 0 ) {
            var tmp = fileName.split('.');
            newfileName = tmp[0] + "_zh";
            if (tmp.length > 1) {
		for( var i = 1; i < tmp.length; i++ ) {
		    newfileName = newfileName + "." +  tmp[i];
		}
            }
            if (newfileName.lastIndexOf(".html") == -1) {
		newfileName = newfileName + ".html";
	    }
            xmlhttp = new XMLHttpRequest();
            file_url = cur_url.replace(fileName, newfileName);
            xmlhttp.onreadystatechange=state_Change;
            xmlhttp.open("GET",file_url,true);
            xmlhttp.send();
            //window.location.replace(file_url);
	}
    }
    $("body").css("cursor", "default");
}

function state_Change()
{
    if (xmlhttp.readyState == 4) { 
	if(xmlhttp.status == 404) {
            document.getElementById('404_bar').style.display = "block";
	} else {
            window.location.replace(file_url);
	}
    }
}

// Setup menu dropdown events to show and hide, also to position correctly
$(function() {
    $("#i18n-menu").appendTo("body");
    $("body").on('click', '#i18n-label', function(e) {
	console.log("clicked");
	e.stopPropagation();  //don't send to other listeners
	if (!$("#i18n-menu").is(":visible")) {
	    var pos   =  $("#i18n-inner").offset();
	    var width =  $("#i18n-inner").width();
	    var height = $("#i18n-inner").height();
	    $("#i18n-menu").css({ "width": (width + 5) + "px", "left": (pos.left + 2) + "px", "top": (pos.top + height*1.2) + "px" });

	    //all scenarios to hide menu: mouseleave, click outside, esc key, resize
	    $("#i18n-menu").mouseleave (function() {
		$("#i18n-menu").slideUp("fast");
		$(document).off("resize click keyup");		
	    });
	    $(document).on("click keyup", function() {
		$("#i18n-menu").hide();
		$(document).off("click keyup");
	    });
	    $(window).on("resize", function() {
		$("#i18n-menu").hide();
		$(window).off("resize");
	    });
	}
	//show or hide menu
	$("#i18n-menu").slideToggle("fast", function() {
	    //when done, if hidden, remove listeners
	    if (!$("#i18n-menu").is(":visible")) {
		$(document).off('click keyup');
		$(window).off('resize');
	    }
	});
    });
});

