document.addEventListener ('DOMContentLoaded', function () {
    Array.prototype.forEach.call (
        document.querySelectorAll ('a:not([href^="/"]'), 
        function (link) {
            var href = link.getAttribute ('href');
            if (href) {
                link.href = href.replace (/^wiki\//, '');
            }
    });
});