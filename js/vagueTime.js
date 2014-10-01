if(document.querySelectorAll) {
  var timeStamps = document.querySelectorAll('.js-vagueTime');
  !function(e){"use strict";function n(e){var n,u=t(e.units),o=Date.now(),a=r(e.from,u,o),f=r(e.to,u,o),s=a-f;return s>0?n="past":(n="future",s=-s),i(s,n,e.lang)}function t(e){if("undefined"==typeof e)return"ms";if("s"===e||"ms"===e)return e;throw new Error("Invalid units")}function r(e,n,t){if("undefined"==typeof e)return t;if("string"==typeof e&&(e=parseInt(e,10)),u(e)&&o(e))throw new Error("Invalid time");return"number"==typeof e&&"s"===n&&(e*=1e3),e}function u(e){return"[object Date]"!==Object.prototype.toString.call(e)||isNaN(e.getTime())}function o(e){return"number"!=typeof e||isNaN(e)}function i(e,n,t){var r,u,o=s[t]||s.en;for(r in f)if(f.hasOwnProperty(r)&&e>=f[r])return u=Math.floor(e/f[r]),o[n](u,o[r][(u>1)+0]);return o.defaults[n]}function a(){"function"==typeof define&&define.amd?define(function(){return d}):"undefined"!=typeof module&&null!==module?module.exports=d:e.vagueTime=d}var f={year:315576e5,month:26298e5,week:6048e5,day:864e5,hour:36e5,minute:6e4},s={en:{year:["year","years"],month:["month","months"],week:["week","weeks"],day:["day","days"],hour:["hour","hours"],minute:["minute","minutes"],past:function(e,n){return e+" "+n+" ago"},future:function(e,n){return"in "+e+" "+n},defaults:{past:"just now",future:"soon"}},de:{year:["Jahr","Jahren"],month:["Monat","Monaten"],week:["Woche","Wochen"],day:["Tag","Tagen"],hour:["Stunde","Stunden"],minute:["Minute","Minuten"],past:function(e,n){return"vor "+e+" "+n},future:function(e,n){return"in "+e+" "+n},defaults:{past:"jetzt gerade",future:"bald"}}},d={get:n};a()}(this);

  for (var i = timeStamps.length - 1; i >= 0; i--) {
    var timeStamp = timeStamps[i];
    timeStamp.innerHTML = vagueTime.get({
      to: new Date(timeStamp.getAttribute('datetime')),
      from: Date.now()
    })
  };
}
