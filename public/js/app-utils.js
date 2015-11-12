
/* jslint browser: true */
/* global $, jQuery */

var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "June",
                  "July", "Aug", "Sep", "Oct", "Nov", "Dec"];

function getDateEst (inVal) {
    var outVal = "N/A";
    var d = new Date (inVal);
    if (!isNaN( d.getTime())) {
        outVal = monthNames[d.getMonth()] + " " + d.getFullYear();
    }
    return outVal;
}

function getNumEst (inVal) {
    if (!inVal || inVal === '') {
        return "N/A";
    }
    var outVal = inVal.toString().replace(/\,/g,'');
    if (isNaN(outVal) || outVal==="") {
        outVal = "N/A";
    } else if (outVal >= 1000 && outVal < 1000000) {
        outVal = ((Math.floor (outVal/1000))) + "K+";
    } else if (outVal >= 1000000 && outVal < 1000000000) {
        outVal = ((Math.floor (outVal/1000000))) + "M+";
    } else if (outVal >= 1000000000) {
        outVal = "Whoa!";
    }
    return outVal;                           
}
