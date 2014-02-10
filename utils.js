// simple templating function;
// str: a string like "Hello {name}";
// data: object like {"name": "Elliot"}
function tpl(str, data) {
  return str.replace(/\{([^\}]+)\}/g, function (sub, prop) {
    return data[prop];
  });
}

// async GET for JSON responses only;
// path: path to a file on the domain serving this script;
// cb: function with signature cb(error, responseText)
function asyncJsonGet(path, cb) {
  var xhr = new XMLHttpRequest();

  xhr.onreadystatechange = function () {
    if (this.readyState == 4 && this.status === 200) {
      cb(null, JSON.parse(this.responseText));
    }
    else if (this.status >= 400) {
      cb(new Error('request for ' + path + ' failed; status was ' + this.status));
    }
  }

  xhr.open('GET', path, true);
  xhr.send();
}
