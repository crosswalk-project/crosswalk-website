/*
 * License
 */

/*jslint browser: true */
/*jslint white: true */
/*jslint node: true */
/*jslint plusplus: true */

/*global $, jQuery, alert*/ 

function createTestimonialHtml (item) {
    var content = 
        "<div class='t-block'>" + 
        "<div class='t-cube' id='t-cube" + item.index + "'>" + 
          (item.faceLink ? "<a href='" + item.faceLink + "'>" : "") +  
          "<img class='t-img' id='t-img" + item.index + "'" + 
              "src='/assets/testimonials/" + item.faceImg + "'/>" + 
          (item.faceLink ? "</a>" : "") + "<br>" +
        "<div id='t-label" + item.index + "'>" + 
          "<strong>" + item.name + "</strong><br>" +
          "<div class='t-title'>" + 
          (item.title ? item.title + "<br>" : "") + 
          (item.link ? "<a href='" + item.link + "'>" : "") +  
          (item.company ? item.company + "<br>" : "") + 
          (item.companyLogo ? "<img class='t-logo' src='/assets/testimonials/" + item.companyLogo + "'/>" : "") + 
          (item.link ? "</a>" : "") + 
          "</div>" + 
        "</div></div>" + 
        "<div class='t-quote' id='t-quote'>" + 
        (item.videoLink ? "<a href='" + item.videoLink + "'>(Watch video)</a> " : "") + 
        "\"" + item.quote + "\"<br>" + 
        "<div class='t-date'>" + item.date + "</div>" + 
        "</div></div>\n\n";
    return content;
}

function loadTestimonialsOnPage(itemsToLoad) {
    var content="", index;

    //alert ("loadTOnPage: " + itemsToLoad.length);
    
    //create elements
    for (index = 0; index < itemsToLoad.length; index++) {
        console.log ("creating..." + itemsToLoad[index].name);
        content += createTestimonialHtml (itemsToLoad[index]);
    }
    if (content.length === 0) {
        content = "<br><br><h3 style='text-align: center;'>No testimonials to display</h3>";
    }

    $("#iconGrid").html(content);
    console.log (content);
}

function loadTestimonials() {
    var gridContent="", index, item;
    var items = [];
    //read objects from .json file
    index = 0;
    $.getJSON( "/documentation/community/testimonials.json", function( data ) {
        $.each( data, function(id, item ) {
            item.index = index;
            console.log (item.name);
            item.name = item.name || "*Missing Name*";
            item.faceImg = item.faceImg || "missing-image.png";
            items.push(item);
            index++;
        }); 
        loadTestimonialsOnPage(items);
    });
}
