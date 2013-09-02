/*
 * The site is made up of
 */
(function () {
var context = this,
    home, page, footer,
    viewHeight = 0,
    top_menu, column, column_name, xhr = null,
    slider, active_uri = '',
    active_target = '', sub_link = '',
    anchor_scroll_timer = 0, samples_background = null, samples_table = null;
    
var debug = {
    navigation: true,
    history: false,
    scroll: false
};    

window.requestAnimationFrame = (function () {
    return window.requestAnimationFrame ||
        window.webkitRequestAnimationFrame ||
        window.mozRequestAnimationFrame ||
        window.oRequestAnimationFrame ||
        window.msRequestAnimationFrame ||
        function (callback) {
            window.setTimeout(callback, 1000 / 60);
        };
})();

/*
 * popstate is fired when the URL is manually entered with
 * an anchor target as well as if the user navigates through
 * the history.
 *
 * If a state is provided with an href, use that to load
 * the appropriate page state, otherwise load from the
 * window.location.href (if it contains a target)
 */
function onPopState (e) {
    var href;
    
    if (debug.history) {
        console.log ('popstate: ' + history.state);
    }
    sub_link = '';
    if (!e.state || !e.state.href) {
        href = window.location.href.replace(/^.*#/, '#').toLowerCase ();
        if (!href.match (/^#/))
            href = '';
        
        if (document.querySelector ('.column[id^="'+ 
                                    href.replace (/^#([^\/]*).*$/, '$1')+'"]')) {
            navigateTo (href);
        } else {
            activateColumn ('home');
            if (href != '') {
                scrollTo (href.replace (/^#/, ''));
            }
        }
    } else {
        navigateTo (e.state.href);
    }
}

    
var hide_delay_timeout = 0;
    
/*
 * Hide all columns, and show the one selected
 */
function activateColumn (name) {
    var tmp = document.getElementById (name + '-column'), item;
    
    if (!tmp) {
        console.warn ('Request to activate non-existent column: ' + name);
        return;
    }
    
    column = tmp;
    column_name = name;

    if (column.id == 'home-column') {
        if (hide_delay_timeout) {
            window.clearTimeout (hide_delay_timeout);
        } else {
            top_menu.classList.add ('hide-delay');
        }
        hide_delay_timeout = window.setTimeout (function () {
            top_menu.classList.remove ('hide-delay');
            hide_delay_timeout = 0;
        }, 1000);

        page.style.removeProperty ('padding-top');
    } else {
        if (hide_delay_timeout) {
            window.clearTimeout (hide_delay_timeout);
            hide_delay_timeout = 0;
            top_menu.classList.remove ('hide-delay');
        }
        page.style.paddingTop = top_menu.offsetHeight + 'px';
    }

    if (name != 'home') {
        top_menu.style.removeProperty ('opacity');
        top_menu.style.top = '0px';
    }
    
    /* Only activate if this column isn't already active... */
    if (!column.classList.contains ('hidden')) {
        return;
    }

    if (debug.scroll) {
        console.log ('Hard scroll to top.');
    }
    window.scrollTo (0, 0);

    Array.prototype.forEach.call (top_menu.querySelectorAll ('#top-menu .active'), 
                                  function (el) {
        el.classList.remove ('active');
    });

    item = top_menu.querySelector ('a[href^="#' + column_name + '"]');
    item.classList.add ('active');

    Array.prototype.forEach.call (
        document.querySelectorAll ('.column:not(.hidden)'), function (el) {
        page.removeChild (el);
        el.classList.add ('hidden');
        document.body.appendChild (el);
    });
    page.appendChild (column);

    column.classList.remove ('hidden');
    column.style.position.left = '0px';
    column.style.position.top = top_menu.offsetHeight + 'px';

    onResize ();
}

var pending = 0;
function loadingGraphicStart () {
    if (pending++ != 0)
        return;
    Array.prototype.forEach.call (
        document.querySelectorAll ('a[href^="' + active_target + '"]'), function (el) {
            el.classList.add ('loading');
    });
}

function loadingGraphicStop () {
    if (!pending)
        return;
    pending--;
    Array.prototype.forEach.call (
        document.querySelectorAll ('.loading'), function (el) {
            el.classList.remove ('loading');
    });
}
    
function content_response (e) {
    if (xhr.readyState != XMLHttpRequest.DONE) {
        console.log (xhr.status);
        return;
    }

    loadingGraphicStop ();

    if (xhr.status != 200) {
        var tmp = 'Unable to fetch content:<br><div class="error">' + 
            xhr.status + ' ' + xhr.statusText + '</div>';
        
        if (column.hasAttribute ('referring_page')) {
            var referring_page = column.getAttribute ('referring_page');
            tmp += '<br>' +
                'Reffering page: ' +
                '<a href="#' + column_name + '/' + 
                referring_page + '">' +
                referring_page + '</a>';
        }

        column.querySelector ('.sub-content').innerHTML = tmp;
        xhr = null;
        return;
    }
    
    xhr = null;

    /* Replace the currently visible content with the newly received 
     * content */
    activateColumn (column_name);
    
    /* This is Wiki content generated from Gollum, which means its a full
     * HTML page. Load it into a context free div element and then extract
     * the node we care about.
     * Then insert that node into the current column's sub-content */
    var div = document.createElement ('div'), content, href;

    div.innerHTML = e.currentTarget.response ?
        e.currentTarget.response :
        e.currentTarget.responseText;
    content = div.querySelector ('#wiki-content');
    if (!content)
        content = div;
    div = column.querySelector ('.sub-content');
    /* If this was a delayed load, it may have finished after a switch
     * to the #home column, in which case there is no sub-content field */
    if (!div) {
        return;
    }
    while (div.firstChild)
        div.removeChild (div.firstChild);

    /* If the content contains the 'missing' class then the Wiki 
     * request hit a 404 */
    if (content.querySelector ('.missing')) {
        var referring_page = column.hasAttribute ('referring_page') ?
            column.getAttribute ('referring_page') : 'Internal link',
            tmp = document.createElement ('div');
        tmp.innerHTML = 'Reffering page: ' +
                        '<a href="#' + column_name + '/' + 
                        referring_page + '">' +
                        referring_page + '</a><br>';
        content.appendChild (tmp);
    }

    div.appendChild (content);

    /*
     * Wiki link rewriting magic... the Gollum system returns links 
     * relative to the paths inside of GitHub and Gollum. Some of 
     * those links have been hand code by Wiki editors.
     *
     * Our task is to find those URLs and rewrite them to be relative 
     * to this #wiki system.
     */
    var sub_page = column.hasAttribute ('loading_page') ? 
        column.getAttribute ('loading_page') : 'Home', 
        c;
    column.removeAttribute ('loading_page');
    column.setAttribute ('active_page', sub_page);
    Array.prototype.forEach.call (
        content.querySelectorAll ('a:not([href^="http"])'), function (link) {
            if (!link.hasAttribute ('href'))
                return;
            /* Remove any prefix / and convert to lower case */
            href = link.getAttribute ('href').replace (/^\//, '').toLowerCase ();
            
            /* If the URL starts with #, then check if it is a valid column request. 
             *
             * If not, assume it is a local anchor to the current page (eg., a
             * header tag in a wiki page)
             */
            if (href.match (/^#/)) {
                c = href.replace(/^#([^\/]*).*$/, '$1');
                if (document.querySelector ('.column[id="'+c+'-column"]')) {
                    link.href = href;
                } else {
                    link.href = '#' + column_name + '/' + sub_page + '/' + 
                        href.replace (/#/, '');
                }
            } else if (!href.match (/^wiki/)) {
                link.href = '#wiki/' + href;
            } else {
                link.href = '#' + href;
            }

            link.addEventListener ('click', subMenuClick);
    });

    if (column.hasAttribute ('requested_anchor')) {
        if (anchor_scroll_timer)
            window.clearTimeout (anchor_scroll_timer);
        anchor_scroll_timer = window.setTimeout (function () {
            scrollTo (column.getAttribute ('requested_anchor'));
        }, 250);
    } else {
        scrollTo (page);
    }
    
    onResize ();
}

function subMenuClick (e) {
    var href = this.getAttribute ('href');
    e.preventDefault ();

    navigateTo (href);
    
    /* When navigating to the Home column, we want the relative 
     * part of the URL to be empty, so set the href to .
     */
    if (column_name == 'home')
        href = '.';

    history.pushState ({ href: href }, '', href);
    if (debug.history) {
        console.log ('pushState: ' + history.state);
    }
}
    
function navigateTo (href) {    
    var requested_column, requested_page, requested_anchor,
        new_content, content, url, column_changed;

    if (debug.navigation) {
        console.log ('Navigate to: ' + href);
    }
    
    /*
     * Incoming requests are of the forum:
     * #[COLUMN-NAME](/[PAGE](/[ANCHOR]))
     *
     * or '.' to load #home
     */
    if (href == '.' || !href.match (/^#/)) {
        requested_column = 'home';
        requested_page = '';
        requested_anchor = '';
    } else {
        requested_column = href.replace(/^#([^\/]*)(\/[^\/]*(\/[^\/]*)?)?/, '$1')
        requested_page = href.replace(/^#([^\/]*)(\/([^\/]*)(\/([^\/]*))?)?/, '$3')
        requested_anchor = href.replace(/^#([^\/]*)(\/([^\/]*)(\/([^\/]*))?)?/, '$5')
    }
    tmp_request = '#'+ requested_column + '/' + requested_page + '/' + requested_anchor;
    if (active_uri == tmp_request) {
        if (debug.navigation) {
            console.log ('Requested active URI: ' + active_uri);
        }
        return;
    }
    active_uri = tmp_request;
    
    
    if (debug.navigation) {
        console.log ('Column: ' + requested_column);
        console.log ('Page: ' + requested_page);
        console.log ('Anchor: ' + requested_anchor);
    }
    
    /* The "Loading..." animation is a class added to any <a> with an href
     * that begins with the 'active_target' string.
     */
    active_target = href;

    if (xhr != null) {
        loadingGraphicStop ();
        console.log ('Aborting active XHR request.');
        xhr.abort ();
        xhr = null;
    }

    column_changed = requested_column != column_name;
    
    /* If no column has been activated yet (initial page load) then
     * turn this column active now (vs. waiting for the XHR to
     * complete
     */
    activateColumn (requested_column);

    if (requested_column == 'home') {
        return;
    }
    
    active_page = column.hasAttribute ('active_page') ? 
        column.getAttribute ('active_page') : '';
    if (requested_page == '') {
        switch (requested_column) {
        case 'documentation':
            requested_page = 'Getting_Started';
            break;
        default:
            requested_page = 'Home';
            break;
        }
    }
                    
    if (column_changed || active_page != requested_page) {
        if (active_page != '')
            column.setAttribute ('referring_page', active_page);
        else
            column.removeAttribute ('referring_page');
        if (requested_anchor == '')
            column.removeAttribute ('requested_anchor');
        else
            column.setAttribute ('requested_anchor', requested_anchor);
        
        xhr = new XMLHttpRequest;
        xhr.onload = content_response;
        loadingGraphicStart ();
        column.setAttribute ('loading_page', requested_page);
        url = column_name + '/' + requested_page;
        if (column_name != 'wiki') {
            url = 'wiki/' + url;
        }
        xhr.open ('GET', url);
        if (debug.navigation) {
            console.log ('Fetching: ' + url);
        }
        xhr.send ();
    } else {
        if (requested_anchor != '') {
            if (anchor_scroll_timer)
                window.clearTimeout (anchor_scroll_timer);
            anchor_scroll_timer = window.setTimeout (function () {
                scrollTo (requested_anchor);
            }, 250);
        }
    }

    /* Remove the 'active' class from everything */
    Array.prototype.forEach.call (
        document.querySelectorAll ('#page .active'), function (el) {
        el.classList.remove ('active');
    });

    /* Add the 'active' class to any menu items that reference this href */
    Array.prototype.forEach.call (
        document.querySelectorAll ('#page a[href="#' + 
                                   requested_column + '/' + 
                                   requested_page + '"]'), function (el) {
        el.classList.add ('active');
    });
}

function buildSubMenu () {
    var el, link, href;

    menus.forEach (function (sub_menu) {
        el = document.querySelector ('#' + sub_menu.menu + '-column .sub-menu');
        while (el.firstChild)
            el.removeChild (el.firstChild);
        sub_menu.items.forEach (function (item) {
            link = document.createElement ('a');
            href = '#' + sub_menu.menu + '/' + item.file;
            link.href = href.toLowerCase ();
            link.textContent = item.name;
            link.addEventListener ('click', subMenuClick);
            el.appendChild (link);
        })
    });

    /* Connect to the top level menu items... */
    Array.prototype.forEach.call (document.querySelectorAll ('#top-menu a'),
                                  function (link) {
        link.addEventListener ('click', subMenuClick);
    });
    /* Connect to the #home entry buttons... */
    Array.prototype.forEach.call (document.querySelectorAll ('#home .button a'),
                                  function (link) {
        link.addEventListener ('click', subMenuClick);
    });
    
    /* Connect to any local anchor links that directing to one of our 
     * columns... */
    Array.prototype.forEach.call (document.querySelectorAll ('.column'), function (el) {
        var name = el.id.replace (/-column$/, '');
        Array.prototype.forEach.call (
            document.querySelectorAll ('a[href^="#' + name + '"]'), function (link) {
                link.href = link.getAttribute ('href').toLowerCase ();
                link.addEventListener ('click', subMenuClick);
            });
    });
}

function onScroll () {
    var scroll = window.scrollY || window.pageYOffset, sub_menu;

    /* Animate in menu when top of home hides too much */
    if ((column.id != 'home-column') ||
        (home.offsetHeight - scroll <= 
         document.getElementById ('download-button').offsetTop)) {
        top_menu.style.removeProperty ('opacity');
        top_menu.style.top = '0px';
        sub_menu = column.querySelector ('.sub-menu');
        if (sub_menu) {
            sub_menu.style.top = top_menu.offsetHeight + 'px';
        }
    } else {
        top_menu.style.opacity = 0;
        top_menu.style.top = '-' + top_menu.offsetHeight + 'px';
    }
}

var resize_timer = 0;
function _onResize () {
    var y = 0, z_index = 10, button,
        scroll = window.scrollY || window.pageYOffset, item;

    // Clear out the timer flag
    resize_timer = 0;

    // Cache the window height dimension
    viewHeight = window.innerHeight;

    /* Set the home height to be a minimum of 80% of the window.innerHeight
     * There is probably a CSS way to do this (it broke when I switched away
     * from absolute positioning on #home), but user's don't care if 
     * the implementation is a hack, so long as it works... */
    home.style.minHeight = Math.round (0.8 * viewHeight) + 'px';

    /* Calculate the size of the page so we can resize the footer-padding
     * to fill any bottom part of the page w/ the tile overlay */
    
    y = page.offsetHeight;
    y += footer.offsetHeight;
    if (y < viewHeight) {
        var delta = viewHeight - y;
        document.getElementById ('footer-padding').style.height = 
            delta + 'px';
        y += delta;
    } else {
        document.getElementById ('footer-padding').style.height = '32px';
    }

    button = home.querySelector ('.more-button-box');
    button.style.top = (home.offsetHeight - button.offsetHeight) + 'px';
    
    /* Set the window height based on content */
    document.body.style.height = y + 'px';

    item = top_menu.querySelector ('a[href^="#' + column_name + '"]');
    slider.style.width = (item.offsetWidth) + 'px';
    slider.style.left = (item.offsetLeft - slider.clientLeft) + 'px';
    
    /* The sub-content isn't resizing correctly with CSS, so hack the width 
     * here based on the column width minus the sub-menu width */
    var sub_menu = column.querySelector ('.sub-menu');
    if (sub_menu) {
        var width = sub_menu.parentElement.offsetWidth - sub_menu.offsetWidth,
            height = sub_menu.parentElement.clientHeight,
            sub_content = column.querySelector ('.sub-content');
        sub_content.style.width = width + 'px';
//        sub_content.style.minHeight = height + 'px';
        sub_content.style.minHeight = (viewHeight - top_menu.offsetHeight) + 'px';
    }
    
    samples_background.style.top = getAbsolutePos (samples_table, document.getElementById ('samples-overview')).y + 'px';
    samples_background.style.height = samples_table.offsetHeight + 'px';
    
    /* The resize may have adjusted the scroll position or need for the
     * top-menu, which is scroll dependent, so trigger a scroll calculation */
    onScroll ();
}

function onResize () {
    if (resize_timer)
        clearTimeout (resize_timer);
    resize_timer = setTimeout (_onResize, 50);
}

var scroll_target = 0, scroll_start = 0, scroll_start_pos = 0;
function smoothScroll () {
    if (!scroll_start)
        return;

    var alpha = (Date.now () - scroll_start) / 500, alpha2;
    if (alpha > 1)
        alpha = 1;

    alpha2 = alpha * alpha;
    alpha = alpha2 / (alpha2 + ((1 - alpha) * (1 - alpha)));
    window.scrollTo (0, scroll_start_pos + scroll_delta * alpha);

    if (alpha < 1)
        requestAnimationFrame (smoothScroll);
    else
        scroll_start = 0;
}

function getAbsolutePos (el, parent) {
    var pos = { x: 0, y: 0 };
    while (el) {
        pos.y += el.offsetTop;
        pos.x += el.offsetLeft;
        el = el.offsetParent;
        if (parent && el == parent)
            break;
    }
    return pos;
}

function scrollTo (e) {
    var el;

    /* Force a resize check to make sure there is no pending
     * hiding or insertion of the top-level menu which could muck
     * with the scroll offset calculation */
    _onResize ();

    if (e instanceof Event) {
        el = document.getElementById (
            e.currentTarget.getAttribute ('href').replace (/^#/, ''));
        e.preventDefault ();
    } else if (typeof e === 'string') {
        /* We want to support lazy-URL writers--people that ignore case
         * Since this sub-component of scrollTo () isn't called very frequently,
         * we'll just do our own DOM search... */
        e = e.toLowerCase ();
        Array.prototype.forEach.call (document.querySelectorAll ('*[id]'), 
                                      function (item) {
            if (el != null)
                return;
            if (item.id.toLowerCase () == e)
                el = item;
        });
        if (!el) {
            console.log ('Requested anchor not found: ' + e);
        }
    } else {
        el = e;
    }

    scroll_start = Date.now ();
    scroll_start_pos = window.scrollY || window.pageYOffset;
    y = getAbsolutePos (el).y;
    if (y != 0)
        y -= top_menu.offsetHeight;
    scroll_delta = y - scroll_start_pos;

    if (scroll_delta == 0) {
        if (debug.scroll) {
            if (el)
                console.log ('Scrolling not necessary for "' + el.id + '"');
            else
                console.log ('Scrolling not necessary for "' + 
                             e.currentTarget.getAttribute ('href') + '"');
        }
        return;
    }

    if (debug.scroll) {
        console.log ('Scrolling ' + scroll_delta + ' to ' + y + ' for "' + el.id + '"');
    }
    
    requestAnimationFrame (smoothScroll);
}

function init () {
    var name, href, use_default = true;

    top_menu = document.getElementById ('top-menu');
    home = document.getElementById ('home');
    page = document.getElementById ('page');
    footer = document.getElementById ('footer');
    slider = top_menu.querySelector ('.slider');
    samples_background = document.getElementById ('crossing-tile');
    samples_table = document.getElementById ('samples-body');
    
    Array.prototype.forEach.call (
        document.querySelectorAll ('#home a[href^="#"]'), function (el) {
        el.addEventListener ('click', scrollTo)
    });

    Array.prototype.forEach.call (
        document.querySelectorAll ('#footer a[href^="#"]'), function (el) {
        el.addEventListener ('click', scrollTo)
    });

    buildSubMenu ();

    document.addEventListener ('scroll', onScroll);
    window.addEventListener ('resize', onResize);
    window.addEventListener ('popstate', onPopState);

    href = window.location.href.replace(/^.*#/, '#').toLowerCase ();
    if (!href.match (/^#/))
        href = '';
    
    if (history.state) {
//        onPopState (history);
    }
        
    if (document.querySelector ('.column[id^="'+href.replace (/^#([^\/]*).*$/, '$1')+'"]')) {
        navigateTo (href);
        use_default = false;
    }

    if (use_default) {
        activateColumn ('home');
        if (href != '') {
            scrollTo (href.replace (/^#/, ''));
        }
    }
    
    onResize ();
}

document.addEventListener ('DOMContentLoaded', init);
}) ();
