function parseParams () {
    var urlParams = {},
        match, pl = /\+/g,
        search = /([^&=]+)=?([^&]*)/g,
        decode = function (s) { return decodeURIComponent (s.replace (pl, ' ')); },
        query = window.location.search.substring (1), packages, base;
    while (match = search.exec (query))
        urlParams[decode(match[1])] = decode(match[2]);
    return urlParams;
}

document.addEventListener ('DOMContentLoaded', function () {
    var OS, CPU,
        content = document.querySelector ('body'),
        packages = document.getElementById ('packages'),
        urlParams = parseParams ();
    
    if ('os' in urlParams) {
        OS = urlParams.os;
    } else {
        OS = navigator.platform.replace(/^.*(win|mac|linux).*$/i, '$1');
        if (OS.match (/linux/i)) {
            if (navigator.userAgent.match (/android/i)) {
                OS = 'android';
            } else if (navigator.userAgent.match (/tizen/i)) {
                OS = 'tizen';
            }
        }
    }
    if ('cpu' in urlParams) {
        CPU = urlParams.cpu;
    } else {
        CPU = navigator.platform.match (/^.*(x86_64|x86|win64|wow64|arm).*$/i);
        if (CPU) {
            CPU = CPU[1];
        } else {
            CPU = navigator.userAgent.match (/^.*(x86_64|x86|win64|wow64|arm).*$/i);
            if (CPU) {
                CPU = CPU[1];
            } else {
                CPU = navigator.cpuClass === 'x64' ? 'x64' : 'x86';
            }
        }
    }

    OS = OS.toLowerCase ();
    CPU = CPU.toLowerCase ();
        
    content = document.createElement ('div');
    content.innerHTML = 'OS: ' + OS + '<br>' + 'CPU: ' + CPU + '<br>';
    
    base = '';
    if (OS.match (/android/)) {
        if (CPU.match (/^arm/)) {
            base = 'android/arm/';
        } else if (CPU.match (/x86$/)) {
            base = 'android/x86/';
        }
    } else if (OS.match (/tizen/)) {
        if (CPU.match (/x86$/)) {
            base = 'tizen/x86/';
        }
    }
    
    if (base != '') {
        Array.prototype.forEach.call (packages.querySelectorAll ('a.apk'), function (link) {
            var span = document.createElement ('text'),
                href = link.getAttribute ('href'),
                basename = href.replace (/\.[^.]*$/, ''),
                img = link.querySelector ('img');
            span.textContent = basename;
            link.insertBefore (span, link.firstChild);
            if (img) {
                img.style.backgroundImage = 'url(assets/' + basename + '-icon.png)';
            }
            link.href = base + href;
        });
    } else {
        document.getElementById ('no-packages').classList.remove ('hidden');
        packages.classList.add ('hidden');
    }
    
    var parent = document.getElementById ('content');
    parent.appendChild (content);
});