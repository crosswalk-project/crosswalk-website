(function () {
var context = this,
    home, page, footer,
    viewHeight = 0,
    top_menu, column, column_name, xhr = null,
    slider, active_uri = '', active_page = '',
    active_target = '', sub_link = '',
    anchor_scroll_timer = 0, samples_background = null, samples_table = null,
    requested_column, requested_page, requested_anchor;

var debug = {
    navigation: false,
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

    _onResize ();
}

var pending = 0;
function loadingGraphicStart () {
    var indicator = false;
    if (pending++ != 0)
        return;
    
    /* Add the loading class to all anchors that directly reference this exact target */
    Array.prototype.forEach.call (
        document.querySelectorAll ('#page a[href^="' + active_target + '"]'),
        function (el) {
            el.classList.add ('loading');
            indicator = true;
    });
    
    /* If no on-page indicators are showing the loading indicator, then add
     * the indicator to the top-menu item that will host the active_target */
    if (!indicator) {
        Array.prototype.forEach.call (
            document.querySelectorAll (
                '#top-menu a[href^="' + 
                active_target.replace(/^(#[^\/]*).*$/, '$1') + '"]'),
            function (el) {
                el.classList.add ('loading');
        });
    }
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
    
function generate_wiki_page (page, contents) {
    var content = document.createElement ('div'),
        wiki_body = document.createElement ('div'),
        div = document.createElement ('div');
    
    div.innerHTML = contents;
    content = div.querySelector ('#wiki-content');
    
    if (!content) {
        content = document.createElement ('div');
        content.id = 'wiki-content';
        wiki_body = document.createElement ('div');
        wiki_body.id = 'wiki-body';
        div.classList.add ('markdown-body');
        wiki_body.appendChild (div);
        content.appendChild (wiki_body);
    } else {
        var last_edit, title = null;
        wiki_body = div.querySelector ('#wiki-body .markdown-body');
        
        /* If title found in header, move it into the body as an H1
         * BUT only if this isn't Home, which is special cased to always
         * pull the title from the page content */
        
        if (column_name != 'wiki' || !page.match (/home/i)) {
            title = div.querySelector ('#head h1:first-child');
            if (title) {
                /* Strip any path prefix from the title */
                title.innerHTML = title.innerHTML.replace (
                    /^.*\/([^\/]*)$/, '$1');
                wiki_body.insertBefore (title, wiki_body.firstChild);
            }
        }
        /* Inject title in header if not found */
        if (!title)
            title = wiki_body.querySelector ('h1:first-child');
        if (!title) {
            /* If there isn't an H1 title, check for an H2 and convert
             * to an H2 if found */
            title = wiki_body.querySelector ('h2:first-child');
            if (title) {
                var h1 = document.createElement ('h1');
                while (title.firstChild) {
                    h1.appendChild (title.firstChild);
                }
                wiki_body.removeChild (title);
                title = h1;
                wiki_body.insertBefore (title, wiki_body.firstChild);
            }
        }
        /* If wiki entry does not start with a header, add a title */
        if (title == null) { 
            title = document.createElement ('h1');
            title.textContent = div.querySelector ('#head h1').textContent;
            wiki_body.insertBefore (title, wiki_body.firstChild);
        }
        /* Inject Last Edit in footer */
        last_edit = div.querySelector ('#last-edit');
        if (last_edit && column_name == 'wiki') {
            /* If the only edit was the initial import from 2013-09-01 21:04:08, don't show it.. */
            if (last_edit.textContent.search (/2013-09-01 21:04:08/) != -1) {
                last_edit.textContent = '';
            }

            var github_link = div.querySelector ('a.action-edit-page');
            if (github_link) {
                /* GitHub URL syntax for starting the editing; GitHub flattens
                 * the wiki structure, so no path structure (vs. Gollum which
                 * does support a directory structure) */
                github_link.href = 
                    'http://github.com/crosswalk-project/crosswalk-website/wiki/' + 
                    github_link.getAttribute ('href').replace (/^.*\//, '');
                github_link.textContent = 'GitHub';
                github_link.target = '_blank';
                github_link.className = '';
                last_edit.appendChild (github_link);
            }

            last_edit.innerHTML = last_edit.innerHTML.replace (
                /Last edited/, 'Content last edited');
            content.appendChild (last_edit);
        }
    }
    
    /* If the content contains the 'missing' class then the Wiki
     * request hit a 404 */
    if (content.querySelector ('.missing')) {
        var referring_page = column.hasAttribute ('referring_page') ?
            column.getAttribute ('referring_page') : 'Internal link',
            tmp = document.createElement ('div');
        tmp.innerHTML = '<h1>Missing Page</h1>' +
                        'Reffering page: ' +
                        '<a href="#' + column_name + '/' +
                        referring_page + '">' +
                        '#' + column_name + '/' + referring_page + '</a><br>';
        wiki_body.insertBefore (tmp, wiki_body.firstChild);
    }

    return content;
}

function generate_history_page (page, contents) {
    var content = document.createElement ('div'),
        wiki_body = document.createElement ('div'),
        div = document.createElement ('div');

    content.id = 'wiki-content';
    wiki_body.id = 'wiki-body';
    div.classList.add ('markdown-body');
    
    wiki_body.appendChild (div);
    content.appendChild (wiki_body);
    
    try {
        var html, events, spans, i, j, time,
            date = new Date (),
            now = Date.now ();
        
        now += (60 * 60 * 24 * 1000) - now % (60 * 60 * 24 * 1000); // Set to 12:00am tonight
        
        events = JSON.parse (contents);
        
        html = '<h1>Crosswalk Wiki History</h1>';
        
        spans = new Array (
            { /* days */
                length: 60 * 60 * 24 * 1000, /* 60s * 60m * 24h = 1 day */
                start: now,
                end: now - (60 * 60 * 24) * date.getDay () * 1000,
                names: null 
            }, { /* weeks */
                length: 60 * 60 * 24 * 7 * 1000, /* 60s * 60m * 24h * 7d = 1 week */
                start: now - (60 * 60 * 24) * date.getDay () * 1000,
                end: now - (60 * 60 * 24 * 7) * 4 * 1000,
                names: new Array ('Last week', ' weeks ago') 
            }, { /* months */
                length: 60 * 60 * 24 * 30 * 1000, /* 60s * 60m * 24h * 30d = ~1 month */
                start: now - (60 * 60 * 24 * 7) * 4 * 1000 * date.getDate (),
                end: now - (60 * 60 * 24) * 30 * 12 * 1000,
                names: new Array ('Last month', ' months ago')
            }
        );


        j = 0;
        spans.forEach (function (span) {
            var period = null, period_index = 0, last_period_index = 0, 
                tracked = [], k;
                                    
            while (j < events.length) {
                event = events[j];
                event.date = parseInt (event.date) * 1000;
                
                /* Events are delivered chronologically, with future events 
                 * (due to timezone) being processed by the first span "Today"
                 * 
                 * If this events happened farther back in time than this span 
                 * handles exit the while () loop to process the event under 
                 * the next span */
                if (event.date <= span.end) {
                    break;
                }
                period_index = Math.max (
                    0, Math.floor ((span.start - event.date) / span.length));
                if (period == null || period_index != last_period_index) {
                    tracked = [];
                    
                }
                
                /* Check if this particular file is already listed as being 
                 * edited in this time period, and if so, so skip it... */
                for (k = 0; k < tracked.length; k++) {
                    if (event.file == tracked[k])
                        break;
                }
                
                if (k != tracked.length) {
                    j++;
                    continue;
                }
                
                if (period == null || period_index != last_period_index) {
                    /* Reset the tracked list */
                    if (period != null)
                        html += '</ul>';
                    if (span.names) {
                        if (period_index >= span.names.length - 1) {
                            period = '' + (period_index + 1) + 
                                span.names[span.names.length - 1];
                        } else {
                            period = span.names[period_index];
                        }
                    } else {
                        period = new Date(event.date).toDateString ();
                    }
                    html += '<h3>' + period + '</h3>';
                    html += '<ul class="history-list">';
                    last_period_index = period_index;
                }

                html += '<li><a href="' + event.file + '">' + event.name + '</a> ';
                if (event.end_sha != '') {
                    html += '<a target="_blank" href="' + 
                        'https://github.com/crosswalk-project/' + 
                        'crosswalk-website/wiki/' + event.file + 
                        '/_compare/' + event.end_sha + '..' + event.start_sha + 
                        '"">View changes on GitHub</a>';
                } else {
                    html += '<span>New page</span>';
                }

                html += '</li>';
                
                tracked.push (event.file);
                j++;
            }
            
            if (period != null) {
                html += '</ul>';
            }
            
            
        });

        div.innerHTML = html;

    } catch (e) {
        div.textContent = 'Error parsing Wiki History';
    }
    
    return content;
}    
    
function content_response (e) {
    if (xhr.readyState != XMLHttpRequest.DONE) {
        console.log (xhr.status);
        return;
    }

    loadingGraphicStop ();

    /* Replace the currently visible content with the newly received
     * content */
    activateColumn (column_name);
    
    if (xhr.status != 200) {
        var tmp = '<div id="wiki-content"><div id="wiki-body">' +
            '<div class="markdown-body"><h1>Unable to fetch content</h1>' +
            'Reason: <span class="error">' + xhr.status + ' ' + xhr.statusText + '</span>';
           
        if (column.hasAttribute ('referring_page')) {
            var referring_page = column.getAttribute ('referring_page');
            tmp += '<br>' +
                'Reffering page: ' +
                '<a href="#' + column_name + '/' +
                referring_page + '">' +
                '#' + column_name + '/' + referring_page + '</a><br>';
        }

        tmp += '</div></div>',
        column.querySelector ('.sub-content').innerHTML = tmp;
        xhr = null;
        return;
    }

    xhr = null;

    /* This is Wiki content generated from Gollum, which means its a full
     * HTML page. Load it into a context free div element and then extract
     * the node we care about.
     * Then insert that node into the current column's sub-content */
    var content, href,
        sub_page = column.hasAttribute ('loading_page') ?
            column.getAttribute ('loading_page') : 'Home';

    /* The Wiki History is a dynamically generated JSON list of page edits
     * clustered chronologically. However the content on the server may not
     * be updated every day, which would result in stale time indicators.
     * 
     * So, the server returns the collected/clustered JSON data, and the
     * client then post-filteres it into "current" time periods for display
     * to the user.
     */
    if (column_name == 'wiki' && sub_page == 'history') {
        content = generate_history_page (sub_page, e.currentTarget.response ?
                e.currentTarget.response :
                e.currentTarget.responseText);
    } else {
        content = generate_wiki_page (sub_page, e.currentTarget.response ?
            e.currentTarget.response :
            e.currentTarget.responseText);
    }
                                        
    div = column.querySelector ('.sub-content');
    /* If this was a delayed load, it may have finished after a switch
     * to the #home column, in which case there is no sub-content field */
    if (!div) {
        return;
    }
    while (div.firstChild)
        div.removeChild (div.firstChild);

    div.appendChild (content);

    /*
     * Wiki link rewriting magic... the Gollum system returns links
     * relative to the paths inside of GitHub and Gollum. Some of
     * those links have been hand code by Wiki editors.
     *
     * Our task is to find those URLs and rewrite them to be relative
     * to this #wiki system.
     */
    var c;
    column.removeAttribute ('loading_page');
    column.setAttribute ('active_page', sub_page);
    Array.prototype.forEach.call (
        content.querySelectorAll ('a:not([href^="http"])'), function (link) {
            if (!link.hasAttribute ('href'))
                return;
            /* Remove any prefix / and convert to lower case */
            href = link.getAttribute ('href').replace (/^\//, '').toLowerCase ();
            
            /* Discard any URLs with a protocol:// prefix (for example irc://) */
            if (href.match (/^.*:\/\//))
                return;
            
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

    /* For all external links, intercept the click and log the event with
     * GA prior to invoking the request... */
    Array.prototype.forEach.call (
        content.querySelectorAll ('a[href^="http"]'), function (link) {
            link.addEventListener ('click', trackAbandonLink);
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

    _onResize ();
}
    
function addEventOffset (e) {
    if ('offsetX' in e)
        return;
    var el, ofsX = e.clientX, ofsY = e.clientY;
    console.log (e);
    el = e.currentTarget;
    while (el) {
        ofsX -= el.offsetLeft;
        ofsY -= el.offsetTop;
        el = el.offsetParent;
    }
    console.log (ofsX);
    console.log (ofsY);
    e.offsetX = ofsX;
    e.offsetY = ofsY;
}

function subMenuClick (e) {
    var href = this.getAttribute ('href'), open;
    e.preventDefault ();
    addEventOffset (e);
    
    /* If the item being clicked is an item of a sub-menu, or 
     * the parent of a sub-menu, then toggle the visibility
     * of the sub-menu */
    if (this.nextSibling && this.nextSibling.classList && 
        this.nextSibling.classList.contains ('menu-sub-pages')) {

        if (e.offsetX >= this.offsetWidth - this.offsetHeight)
            this.nextSibling.classList.toggle ('off');
        else
            this.nextSibling.classList.remove ('off');
            
        if (this.nextSibling.classList.contains ('off')) {
            open = false;
            this.classList.add ('menu-closed');
            this.classList.remove ('menu-open');
        } else {
            open = true;
            this.classList.add ('menu-open');
            this.classList.remove ('menu-closed');
        }

        if (e.offsetX >= this.offsetWidth - this.offsetHeight)
            return;
        
        /* If the current page is not child of this page, then don't change focus
         * to the item if the item is now closed (just return) */
        if (!open) {
            if (href.replace (/#[^\/]*\//, '') != 
                requested_page.replace(/\/[^\/]*/, '')) {
                console.log ('Closed ' + requested_page);
                return;
            }
        }
    }
    
    navigateTo (href);

    /* When navigating to the Home column, we want the relative
     * part of the URL to be empty, so set the href to .
     */
    if (column_name == 'home')
        href = '.';

    if (history.pushState) {
        history.pushState ({ href: href }, '', href);
        if (debug.history) {
            console.log ('pushState: ' + history.state);
        }
    } else {
        window.location.href = href;
    }
}

function trackAbandonLink (e) {
    var href = e.currentTarget.getAttribute ('href');
    e.preventDefault ();
//        target = e.currentTarget.getAttribute ('target');
    if (ga) {
        ga ('send', 'event', {
            'eventCategory': 'wiki-anchor',
            'eventAction': 'click',
            'eventLabel': href,
            'metric0': 0,
            'hitCallback': function () {
                window.location.href = href;              
            }
        });
    }
}    
    
function navigateTo (href) {
    var new_content, content, url, column_changed;

    if (debug.navigation) {
        console.log ('Navigate to: ' + href);
    }

    /*
     * Incoming requests are of the form:
     * #COLUMN-NAME(/PAGE(/ANCHOR))
     * or, if a ANCHOR is a sub-page to PAGE, then the form is:
     * #COLUMN-NAME/PAGE/SUB-PAGE(/ANCHOR)
     *
     * or '.' to load #home
     */
    if (href == '.' || !href.match (/^#/)) {
        requested_column = 'home';
        requested_page = '';
        requested_anchor = '';
    } else {
        requested_column = href.replace(/^#(([^\/]*))?.*/, '$2');
        requested_page = href.replace(/^#([^\/]*\/([^\/]*))?.*/, '$2');
        requested_anchor = href.replace(/^#([^\/]*\/[^\/]*\/([^\/]*))?.*/, '$2');
        if (requested_anchor != '') {
            menus.forEach (function (menu) {
                if (menu.menu != requested_column.toLowerCase ())
                    return;
                menu.items.forEach (function (item) {
                    if (!('subpages' in item))
                        return;
                    if (item.file.toLowerCase () != requested_page.toLowerCase ())
                        return;
                    item.subpages.forEach (function (page) {
                        if (page.file.toLowerCase () != requested_anchor.toLowerCase ())
                            return;
                        requested_page += '/' + requested_anchor;
                        requested_anchor =
                            href.replace(/^([^\/]*\/[^\/]*\/[^\/]*\/([^\/]*))?.*/, '$2');
                    });
                });
            });
            
        }
    }
    tmp_request = '#'+ requested_column + '/' + requested_page + '/' + requested_anchor;
    if (active_uri == tmp_request) {
        if (debug.navigation) {
            console.log ('Requested active URI: ' + active_uri);
        }
        return;
    }
    active_uri = tmp_request;

    if (ga) {
        var from_top_menu = false;
        /* If this originated from a click event on an anchor,
         * then walk the parent chain to see if this was from
         * the top-menu and log that information in the GA 
         * event */
        if (window.event && window.event.currentTarget) {
            var p = window.event.currentTarget;
            while (p) {
                if (p.id && p.id == 'top-menu') {
                    from_top_menu = true;
                    break;
                }
                p = p.parentElement;
            }
        }
        
        ga ('send', {
            'metric0': from_top_menu,
            'hitType': 'pageview',
            'page': href,
            'title': requested_page
        });
    }

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
            requested_page = 'getting_started';
            break;
        case 'contribute':
            requested_page = 'overview';
            break;
        default:
            requested_page = 'home';
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
        /* If this is a subpage item then open the parent menu */
        if (el.parentElement.tagName.match (/div/i) &&
            el.parentElement.classList.contains ('menu-sub-pages')) {
            el.parentElement.classList.remove ('off');
            el.parentElement.previousSibling.classList.remove ('menu-closed');
            el.parentElement.previousSibling.classList.add ('menu-open');
        }
            
        if (el.nextSibling && el.nextSibling.classList && 
            el.nextSibling.classList.contains ('menu-sub-pages')) {
            el.nextSibling.classList.remove ('off');
            el.classList.remove ('menu-closed');
            el.classList.add ('menu-open');
        }

    });
    
    if (column_name == 'wiki' && 
        requested_page != 'home' && 
        requested_page != 'history')
        document.querySelector (
            '.sub-menu a[href="#wiki/pages"]').classList.add ('active');
}

function appendMenu (parent, menu) {
    var link, href, div;
    menu.items.forEach (function (item) {
        link = document.createElement ('a');
        href = '#' + menu.menu + '/' + item.file;
        link.href = href.toLowerCase ();
        link.textContent = item.name;
        link.addEventListener ('click', subMenuClick);
        parent.appendChild (link);
        if ('subpages' in item) {
            div = document.createElement ('div');
            div.classList.add ('menu-sub-pages');
            div.classList.add ('off');
            link.classList.add ('menu-closed');
            appendMenu (div, { menu: menu.menu + '/' + item.file, 
                              items: item.subpages});
            parent.appendChild (div);
        }
    });
 }    
    
function buildSubMenu () {
    var el, link, href;

    menus.forEach (function (sub_menu) {
        el = document.querySelector ('#' + sub_menu.menu + '-column .sub-menu');
        while (el.firstChild)
            el.removeChild (el.firstChild);
        appendMenu (el, sub_menu);
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
/*    if ((column.id != 'home-column') ||
        (home.offsetHeight - scroll <=
         document.getElementById ('download-button').offsetTop)) {*/
        top_menu.style.removeProperty ('opacity');
        top_menu.style.top = '0px';
        sub_menu = column.querySelector ('.sub-menu');
        if (sub_menu) {
            sub_menu.style.top = top_menu.offsetHeight + 'px';
        }
/*    } else {
        top_menu.style.opacity = 0;
        top_menu.style.top = '-' + top_menu.offsetHeight + 'px';
    }*/
}

function _onResize (from_resize_event) {
    var y = 0, z_index = 10, button,
        scroll = window.scrollY || window.pageYOffset, item;

    // Cache the window height dimension
    viewHeight = window.innerHeight;

    /* Set the home height to be a minimum of 64px shorter than the window.innerHeight
     * There is probably a CSS way to do this (it broke when I switched away
     * from absolute positioning on #home), but user's don't care if
     * the implementation is a hack, so long as it works... */
    home.style.minHeight = Math.round (viewHeight - 64) + 'px';
    
    /* Since we're JS hacking anyway, we'll also vertically center the #home .content
     * to its area. We subtract the 64px from viewHeight per above. The .more-button-box
     * spacing is accounted for with the #home .content padding-bottom */
    var content = home.querySelector ('.content'),
        contentTop = Math.round ((viewHeight - 64 - content.offsetHeight) * 0.5);
    contentTop = Math.max (contentTop, 0);
    content.style.top =  contentTop + 'px';

    /* And then vertically align the more-button to the bottom of the home content with
     * the 50px padding... (the more-button is a child of content, so we determine the
     * full height of home and subtract the top of the content. */
    button = home.querySelector ('.more-button-box');
    button.style.top = (home.offsetHeight - contentTop - button.offsetHeight) + 'px';

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

    /* Set the window height based on content */
    document.body.style.height = y + 'px';

    item = top_menu.querySelector ('a[href^="#' + column_name + '"]');
    if (from_resize_event)
        slider.classList.remove ('slide-around');
    slider.style.width = (item.offsetWidth) + 'px';
    slider.style.left = (item.offsetLeft - slider.clientLeft) + 'px';
    if (from_resize_event)
        slider.classList.add ('slide-around');

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
    _onResize (true);
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

    /* To keep the home-column from flashing away on IE8 prior to the "You need to upgrade"
     * page being shown, we start with the home-column hidden and only show it in
     * the DOMContentLoaded event handler */
    document.getElementById ('home-column').classList.remove ('hidden');
    
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
    
    if (history.pushState)
        window.addEventListener ('popstate', onPopState);

    href = window.location.href.replace(/^.*#/, '#').toLowerCase ();
    if (!href.match (/^#/))
        href = '';

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

    setTimeout (_onResize, 50);
}

if (!document.addEventListener) {
    document.attachEvent ('onreadystatechange', function () {
        var page = document.getElementById ('page'),
            error = document.getElementById ('error-column');
        while (page.firstChild)
            page.removeChild (page.firstChild);
        error.className = 'column';
        page.appendChild (error);
    });
} else {
    document.addEventListener ('DOMContentLoaded', init);
}
}) ();

/* google analystics */
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o), m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
ga('create', 'UA-42894071-1', 'crosswalk-project.org');
ga('send', 'pageview');

