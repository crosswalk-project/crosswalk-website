
// Demos page 

/*jslint browser: true */
/*jslint white: true */
/*jslint node: true */
/*jslint plusplus: true */

/*global $, jQuery, alert*/ 

"use strict";

var webclPlaying = false;
var simdPlaying = false;
function onLoadDemoPage() {

    $(document).on("click","#demo-block-webcl",function() {
        if (!webclPlaying) {
            $("#webcl1-div").html ("<iframe class='demo-img-webcl' id='webcl1' src='https://www.youtube.com/embed/RviAF98lQOQ?vq=large&autoplay=1&controls=0&showinfo=0&rel=0&loop=1&playlist=RviAF98lQOQ&theme=light&modestbranding=0&enablejsapi=1' frameborder='0' allowfullscreen></iframe>");
            $("#webcl2-div").html ("<iframe class='demo-img-webcl' id='webcl1' src='https://www.youtube.com/embed/e1OKhTHexm8?vq=large&autoplay=1&controls=0&showinfo=0&rel=0&loop=1&playlist=e1OKhTHexm8&theme=light&modestbranding=1&enablejsapi=1' frameborder='0' allowfullscreen></iframe>");

            $("#webcl-play-btn").removeClass("demo-play-btn");
            $("#webcl-play-btn").addClass("demo-stop-btn");

            webclPlaying = true;
        } else {
            $("#webcl1-div").html ("<img class='demo-img-webcl' id='webcl1' src='/assets/demo-webcl1.jpg' />");
            $("#webcl2-div").html ("<img class='demo-img-webcl' id='webcl2' src='/assets/demo-webcl2.jpg' />");

            $("#webcl-play-btn").removeClass("demo-stop-btn");
            $("#webcl-play-btn").addClass("demo-play-btn");

            webclPlaying = false;
        }
        return false;
    });
    $(document).on("click","#demo-block-simd",function() {
        if (!simdPlaying) {
            $("#simd1-div").html ("<iframe class='demo-img-simd' src='https://www.youtube.com/embed/QwdKBHtsY7w?vq=large&autoplay=1&controls=0&showinfo=0&rel=0&loop=1&playlist=QwdKBHtsY7w' frameborder='0' allowfullscreen></iframe>");
            $("#simd2-div").html ("<iframe class='demo-img-simd' src='https://www.youtube.com/embed/dFFE_J53ueo?vq=large&autoplay=1&controls=0&showinfo=0&rel=0&loop=1&playlist=dFFE_J53ueo' frameborder='0' allowfullscreen></iframe>");

            $("#simd-play-btn").removeClass("demo-play-btn");
            $("#simd-play-btn").addClass("demo-stop-btn");

            simdPlaying = true;
        } else {
            $("#simd1-div").html ("<img class='demo-img-simd id='simd1' src='/assets/demo-simd1.jpg' />");
            $("#simd2-div").html ("<img class='demo-img-simd id='simd2' src='/assets/demo-simd2.jpg' />");

            $("#simd-play-btn").removeClass("demo-stop-btn");
            $("#simd-play-btn").addClass("demo-play-btn");

            simdPlaying = false;
        }
        return false;
    });

}
