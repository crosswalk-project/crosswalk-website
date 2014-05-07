# Write a web application

The web application for this project is a simple HTML5/CSS/Javascript application, with some additional files to enable it to be used in a Crosswalk package. Full details about adapting web applications for Crosswalk are given [in the *Getting started* pages](#documentation/getting_started/build_an_application).

1.  Create the directories for the web application:

        cd ~/<my projects directory>/xwalk-player-project

        # top-level directory for web application
        mkdir xwalk-player
        cd xwalk-player

        # directories for application components
        mkdir -p assets js

    The steps below assume you are adding files to the `xwalk-player` directory.

2.  Add a `manifest.json` file:

        {
          "name": "xwalk_player",
          "version": "0.0.0.1",
          "app": {
            "launch":{
              "local_path": "index.html"
            }
          },
          "icons": {
            "96": "icon.png"
          }
        }

3.  Add an icon file `icon.png`.

    The manifest file (above) specifies a 96x96px icon with filename `icon.png` as the application icon. Crosswalk will use this as the icon for the application in the Android target's application list/home screen etc.

    You can either use your own icon (with the right dimensions), or copy the default Crosswalk one by following the instructions on [this page](#documentation/getting_started/build_an_application/A-simple-application).

4.  Add the HTML file `index.html`:

        <!DOCTYPE html>
        <html>
        <head>
        <meta name="viewport"
              content="width=device-width, initial-scale=1.0">
        <meta charset="utf-8">
        <title>xwalk-player</title>
        <link rel="stylesheet" href="assets/base.css">
        </head>
        <body>

        <header>
          <h1>xwalk-player</h1>
        </header>

        <script src="js/main.js"></script>
        </body>
        </html>

5.  Add some simple CSS styling in `assets/base.css`:

        body {
          font-size: 1.5em;
          margin: 0;
          font-family: sans-serif;
        }

        h1 {
          width: 100%;
          text-align: center;
          margin: 0.5em 0;
        }

        header {
          border-bottom: 0.25em solid black;
        }

        button {
          width: 1.5em;
          height: 1.5em;
          font-size: 1.5em;
          display: block;
          margin-top: 0.25em;
        }

        p {
          border-bottom: 0.25em dashed #777;
          padding: 0.5em 0;
          margin: 0 0.5em;
        }

    This increases the font sizes, makes buttons a bit bigger, and adds some lines and spacing to make the layout slightly more appealing.

6.  Add the JavaScript file `js/main.js`. The code in this file uses the Crosswalk extension to fetch a list of audio files on the device; this list is then used to populate the HTML track list:

        (function () {
          var playText = '>';
          var pauseText = '||';

          // make and append an HTML <p> element
          // containing "text"
          var makePara = function (text) {
            var p = document.createElement('p');
            p.innerText = text;
            document.body.appendChild(p);
          };

          // enable all play/pause buttons
          var enableButtons = function () {
            var btns = document.querySelectorAll('button');
            for (var i = 0; i < btns.length; i += 1) {
              btns[i].removeAttribute('disabled');
            }
          };

          // disable all play/pause buttons except for btn
          var disableButtonsExcept = function (btn) {
            var btns = document.querySelectorAll('button');
            for (var i = 0; i < btns.length; i += 1) {
              if (btns[i] !== btn) {
                btns[i].setAttribute('disabled', 'disabled');
              }
            }
          };

          // very basic wrapper for HTML5 audio element;
          // fileInfo has the fields:
          // uri, title, artist
          var makeAudio = function (fileInfo) {
            var p = document.createElement('p');

            var audio = document.createElement('audio');

            audio.src = 'file://' + fileInfo.uri;

            var title = document.createElement('div');
            title.innerHTML = '<strong>' +
                              fileInfo.title +
                              '</strong>';

            var artist = document.createElement('div');
            artist.innerHTML = fileInfo.artist;

            var btn = document.createElement('button');
            btn.innerHTML = playText;

            // clicking on a button toggles play/pause
            btn.addEventListener('click', function () {
              if (audio.paused) {
                disableButtonsExcept(btn);
                audio.play();
                btn.innerText = pauseText;
              }
              else {
                enableButtons();
                audio.pause();
                btn.innerText = playText;
              }
            });

            // when playback finishes, skip back to start and
            // enable all buttons
            audio.addEventListener('ended', function () {
              audio.currentTime = 0;
              btn.innerText = playText;
              enableButtons();
            });

            p.appendChild(audio);
            p.appendChild(title);
            p.appendChild(artist);
            p.appendChild(btn);

            document.body.appendChild(p);
          };

          document.addEventListener('DOMContentLoaded', function () {
            // HERE'S THE (ASYNC) CALL TO THE EXTENSION
            // show list of audio files provided by audioFs extension
            audioFs.listFilesAsync().then(
              function (result) {
                if (result.success) {
                  for (var i = 0; i < result.files.length; i += 1) {
                    makeAudio(result.files[i]);
                  }
                }
                else {
                  makePara(result.error);
                }
              },

              function (e) {
                console.error(e);
                makePara(e.message);
              }
            );
          });
        })();

    Note that this is wrapped in an anoynmous function call to prevent the declared functions polluting the global namespace.

    The listener added to the `DOMContentLoaded` event is the core piece of code here. This is responsible for calling the extension API by invoking the asynchronous `listFilesAsync()` method, and adding handlers which deal with the returned promise:

    <ul>

    <li>

    <p>If the <code>listFilesAsync()</code> method is successful, the promise resolves to an object structured as explained <a href="#documentation/android_extensions/write_an_extension/files-data-structure">previously</a>. The <code>files</code> property of this object is an array of audio file metadata returned by the extension (via the Android <code>ContentResolver</code>). The array is iterated to produce an <a href="http://www.w3.org/wiki/HTML/Elements/audio">HTML5 &lt;audio&gt; element</a> for each audio file.</p>

    </li>

    <li>If the promise was rejected, its associated error is displayed.</li>

    </ul>

<p>Note that the Android API will actually return all audio files on the device, including files which Crosswalk may not be able to play. The code above doesn't check the returned file types to see whether they are playable, but you would need to do this check in a "real" application. Using a method like <a href="http://www.w3.org/TR/html5/embedded-content-0.html#dom-navigator-canplaytype"><code>canPlayType()</code></a> could help in this situation.</p>

Now both the web application and the extension are ready, you can create a package and run it on Android.
