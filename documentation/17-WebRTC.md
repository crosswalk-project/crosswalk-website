# WebRTC

RTC (Real-Time Communications) technology enables video and audio communication over computer networks. Historically, RTC systems have been complex, expensive, and proprietary.

Recently, WebRTC standards have made RTC more accessible, bringing it to open source and freely-available web runtimes. WebRTC enables developers to build RTC systems using standard web technologies (HTML and JavaScript), without the need for plugins or downloads.

WebRTC APIs are available in Crosswalk 5 or later on ARM; and Crosswalk 7.36.154.6 or later for x86. They are also [widely supported in modern browsers](http://www.webrtc.org/home).

The WebRTC APIs can be used in a range of situations, such as:

*   Implementing dedicated chat applications on the web (with or without audio and video).
*   Adding video chat to web applications (e.g. support desk software).
*   Passing data between clients in multi-player HTML5 games.
*   Web-based collaborative document editing.

In the following tutorial, you'll develop a simple video call client and related server infrastructure using WebRTC.

## Introduction to the tutorial

In this tutorial, you will develop two components:

*   **Client application (installed on two target devices)**

    A very simple video call client, using WebRTC to transport data between the clients. The application will enable one client to "dial" the other client and make a video call (with audio).

    This application only works between two clients, but can be extended to multiple clients fairly easily. It can be run in a Chrome or Firefox browser, or in an Android app wrapped in Crosswalk.

    For the purposes of testing, you'll install this on two targets.

    The user interface is written using standard HTML5 and JavaScript (no plugins).

*   **Server (installed on a desktop/laptop)**

    The server brokers the initial connection between the two clients. Once a connection is established between the clients, their communication continues in a peer to peer mode: none of the video data is routed through the server.

    The server is also written in JavaScript, but runs from the command-line (not in a browser).

To keep the tutorial simple, a WebRTC wrapper library is used to manage communications between clients. (WebRTC is a complex low-level protocol, and unless you are interested in writing your own WebRTC wrapper library, knowledge of the fine detail is not required.) The library used in this tutorial is [PeerJS](http://peerjs.com/).

**By the end of this tutorial**, you will understand how to write a basic WebRTC application with PeerJS and run it on Android using Crosswalk.

## How video calling works in the application

This is what the client application looks like when a call is in progress:

![Video calling with WebRTC in Crosswalk](assets/crosswalk-webrtc.png)

Here are the steps involved in setting up the server and clients to establish a call:

1.  The server is started. This should be done on a machine with a known IP address, as this has to be hard-coded into the client application.

    The PeerJS server uses [WebSockets](http://www.w3.org/TR/websockets/) to set up the initial connections between clients, but this is not mandated by the specification: if you prefer to use another mechanism to establish the connection (e.g. Bluetooth or NFC), the specification does not prevent this.

2.  The two client applications are started, one as a Crosswalk application (as shown in the image above) and one on a Chrome browser or a second Android device (either using Chrome or Crosswalk).

3.  Each client application connects to the server, identifying itself via a unique string which becomes its ID.

    At the same time as the connection is being set up, the local media streams (audio and video) are captured via the [`navigator.getUserMedia()`](http://www.w3.org/TR/mediacapture-streams/#navigatorusermedia) method. The `src` attribute of a `<video>` element is set to the URL for the local media stream, giving a preview of what the person on the other end will see when a call is initiated (the "LOCAL" video element in the screenshot above).

4.  One client initiates a call to the other, using the recipient's ID. Note that the caller needs to know the recipient's ID, as there's no way to discover it from the application. Obviously, in a more realistic application, you could provide a contacts list. (Note that PeerJS provides a mechanism for listing connected clients: see [Aside: querying connected clients](#Aside:-querying-connected-clients) for details.)

    Under the covers, the clients use the [RTCPeerConnection API](http://dev.w3.org/2011/webrtc/editor/webrtc.html#rtcpeerconnection-interface) to establish the peer to peer connection.

5.  Once the connection has been established, the application uses the media stream from the other end of the call as the source for a second `<video>` element (the "REMOTE" video element in the screenshot above).

## Pre-requisites

This section covers the pre-requisite software and hardware you'll need in this tutorial. You will need:

*   A host for developing the code and running the server.
*   Two targets to run the client application. One of these could be the same machine as the host.

To give an example of suitable devices, here is the setup used to write this tutorial:

*   Linux desktop with webcam and Google Chrome. This was used as the development host, as well as one of the targets. The client application ran directly from the filesystem (it was not deployed via Crosswalk or web server).
*   HTC OneX phone (ARM) running Android 4.0.4 (which has a user-facing camera). This was used as the second target. The client application was deployed to it via Crosswalk.
*   ZTE Geek phone (x86) running Android 4.2.2.

You could have a host machine to run the server and two Android devices to run the client applications if you prefer. Alternatively, you could use one or more emulated Android devices, providing they are able to emulate the camera.

### Host setup

The host should first be set up for Crosswalk Android development ([Windows](#documentation/getting_started/windows_host_setup), [Linux](#documentation/getting_started/linux_host_setup)). Pay particular attention to the *Installation for Crosswalk Android* section.

You will also need to install [**node**](http://nodejs.org/) to run the server. Providing you use a recent enough version of node, it should also include the **npm** package manager; if not, you will need to install that separately.

### Target setup

For Android targets, ensure that you [have access via adb](#documentation/getting_started/android_target_setup). No special setup is required beyond that.

If you are using a desktop machine as a target, no special setup is required, other than ensuring that you have a Chrome browser installed.

## Setting up the project

Make a project directory:

    $ mkdir xwalk-webrtc

The server and client code will be put into separate directories, as they have no dependencies on each other.

## Server code

1.  Make a directory for the server code:

        $ cd xwalk-webrtc
        $ mkdir server
        $ cd server

2.  Install the **peer** library via `npm` (in the `server` directory):

        $ npm install peer

    This provides the PeerJS server library.

3.  Install the **ip** library via `npm` (still in the `server` directory):

        $ npm install ip

    This library used to show the IP address of the server when it starts, to make it a bit easier to set up the clients.

4.  Create an `index.js` file containing the server code:

        var ip = require('ip');
        var PeerServer = require('peer').PeerServer;

        var port = 9000;
        var server = new PeerServer({port: port});

        server.on('connection', function (id) {
          console.log('new connection with id ' + id);
        });

        server.on('disconnect', function (id) {
          console.log('disconnect with id ' + id);
        });

        console.log('peer server running on ' +
                    ip.address() + ':' + port);

    This server shows the IDs of clients as they connect, to make debugging a bit easier.

5.  <a name="run-peerjs-server"></a>You should now be able to start the server (from the `server/` directory):

        $ node index.js
        peer server running on 192.168.0.25:9000

    Note that your IP address is likely to be different.

    Make a note of the IP address shown, as you will need to encode this into the client application.

### Aside: querying connected clients

The PeerJS server can be configured to allow querying of the list of connected clients: just set the `allow_discovery` option to `true` when starting it:

    var server = new PeerServer({port: port, allow_discovery: true});

Once this is done, you can get an array of connected clients by sending an HTTP request to:

    http://<peer server IP and port>/peerjs/peers

This functionality is not used in this tutorial, but could be used as the basis for address book functionality in a more complex application.

## Client code

The client code is bare but functional. Its purpose is to demonstrate the minimal amount of code required for a useful WebRTC application. However, note that the error checking and recovery is consequently also minimal: this means that unless you [follow the steps to make a call precisely](#Make-a-call), you may run into unrecoverable errors.  The [Troubleshooting](#Troubleshooting) section covers some of the most common issues and explains how to "reset" if things go wrong.

To add the client code, follow these steps:

1.  Make a directory for the code:

        $ mkdir client

2.  Go to the `client/` directory:

        $ cd client

3.  Download PeerJS (the client-side variant):

        $ wget https://raw.githubusercontent.com/peers/peerjs/master/dist/peer.js

    If you don't have `wget` on your system, open the above URL in a browser and use *Save as...* to download a copy into the `client/` directory.

4.  Create the HTML file `index.html`. This is the user interface for the application:

        <!DOCTYPE html>
        <html>
          <head>
            <meta name="viewport"
                  content="width=device-width, initial-scale=1.0, user-scalable=no">
            <meta charset="utf-8">
            <title>WebRTC client</title>

            <style>
            #credentials, #dialler, #messages {
              clear: both;
            }
            #remote-video {
              max-width: 300px;
            }
            #local-video {
              max-width: 150px;
            }
            </style>
          </head>

          <body>

            <div id="credentials">
              <p>
                Connect as:
                <input type="text" id="caller-id" size="15">
                <button id="connect">Connect</button>
              </p>
            </div>

            <div id="dialler" data-active="false">
              <p>
                Make call to:
                <input type="select" id="recipient-id">
                </input>
                <button id="dial">Call</button>
              </p>

              <hr>

              <p><strong>REMOTE:</strong></p>
              <video id="remote-video" autoplay></video>

              <hr>

              <p><strong>LOCAL:</strong></p>
              <video id="local-video" autoplay></video>
            </div>

            <hr>

            <div id="messages">
            </div>

            <script src="peer.js"></script>
            <script src="main.js"></script>
          </body>
        </html>

    This imports two JavaScript files: the `peer.js` file you downloaded previously, and a `main.js` file. The latter is what you'll create in the next step.

    Note that `index.html` has some inline CSS in the `<head>` element. Usually, it's best not to do this; but as there is very little CSS in this application (because it's so simple), it makes sense to avoid adding an additional file for it.

5.  Create the `main.js` JavaScript file. This wires up the user interface elements to the PeerJS library, so a user can make a call to another client and show the local and remote video streams. The steps involved are described above in [How video calling works in the application](#How-video-calling-works-in-the-application).

    Edit the `main.js` file, adding the following content:

        document.addEventListener('DOMContentLoaded', function () {
          // PeerJS server location
          var SERVER_IP = '192.168.0.25';
          var SERVER_PORT = 9000;

          // DOM elements manipulated as user interacts with the app
          var messageBox = document.querySelector('#messages');
          var callerIdEntry = document.querySelector('#caller-id');
          var connectBtn = document.querySelector('#connect');
          var recipientIdEntry = document.querySelector('#recipient-id');
          var dialBtn = document.querySelector('#dial');
          var remoteVideo = document.querySelector('#remote-video');
          var localVideo = document.querySelector('#local-video');

          // the ID set for this client
          var callerId = null;

          // PeerJS object, instantiated when this client
          // connects with its caller ID
          var peer = null;

          // the local video stream captured with getUserMedia()
          var localStream = null;

          // DOM utilities
          var makePara = function (text) {
            var p = document.createElement('p');
            p.innerText = text;
            return p;
          };

          var addMessage = function (para) {
            if (messageBox.firstChild) {
              messageBox.insertBefore(para, messageBox.firstChild);
            }
            else {
              messageBox.appendChild(para);
            }
          };

          var logError = function (text) {
            var p = makePara('ERROR: ' + text);
            p.style.color = 'red';
            addMessage(p);
          };

          var logMessage = function (text) {
            addMessage(makePara(text));
          };

          // get the local video and audio stream and show
          // a preview in the "LOCAL" video element
          // successCb: has the signature successCb(stream);
          // receives the local video stream as an argument
          var getLocalStream = function (successCb) {
            if (localStream && successCb) {
              successCb(localStream);
            }
            else {
              navigator.webkitGetUserMedia(
                {
                  audio: true,
                  video: true
                },

                function (stream) {
                  localStream = stream;

                  localVideo.src = window.URL.createObjectURL(stream);

                  if (successCb) {
                    successCb(stream);
                  }
                },

                function (err) {
                  logError('Failed to get local stream');
                  logError(err.message);
                }
              );
            }
          };

          // set the "REMOTE" video element source
          var showRemoteStream = function (stream) {
            remoteVideo.src = window.URL.createObjectURL(stream);
          };

          // set caller ID and connect to the PeerJS server
          var connect = function () {
            callerId = callerIdEntry.value;

            if (!callerId) {
              logError('please set caller ID first');
              return;
            }

            try {
              // create connection to the ID server
              peer = new Peer(
                callerId,
                {host: SERVER_IP, port: SERVER_PORT}
              );

              // get local stream ready for incoming calls
              getLocalStream();

              // handle events representing incoming calls
              peer.on('call', answer);
            }
            catch (e) {
              peer = null;
              logError('could not reach connection server');
            }
          };

          // make an outgoing call
          var dial = function () {
            if (!peer) {
              logError('please connect first');
              return;
            }

            if (!localStream) {
              logError('could not start call as there is no ' +
                       'localStream ready');
              return
            }

            var recipientId = recipientIdEntry.value;

            if (!recipientId) {
              logError('could not start call as no recipient ID ' +
                       'is set');
              return;
            }

            getLocalStream(function (stream) {
              logMessage('outgoing call initiated');

              var call = peer.call(recipientId, stream);

              call.on('stream', showRemoteStream);

              call.on('error', function (e) {
                logError('error with call');
                logError(e.message);
              });
            });
          };

          // answer an incoming call
          var answer = function (call) {
            if (!peer) {
              logError('cannot answer a call without a connection');
              return;
            }

            if (!localStream) {
              logError('could not answer call as there ' +
                       'is no localStream ready');
              return;
            }

            logMessage('incoming call answered');

            call.on('stream', showRemoteStream);

            call.answer(localStream);
          };

          // wire up button events
          connectBtn.addEventListener('click', connect);
          dialBtn.addEventListener('click', dial);
        });

6.  Near the top of the `main.js` file, there is one line which needs to be edited for your environment:

        var SERVER_IP = '192.168.0.25';

    Change the IP address to the value output by the PeerJS server when you started it.

7.  Create a minimal [Crosswalk manifest](#documentation/manifest) file, `manifest.json`, with this content:

        {
          "name": "WebRTC",
          "description": "WebRTC client",
          "version": "0.0.1",
          "app": {
            "launch": {
              "local_path": "index.html"
            }
          }
        }

    This provides the minimal metadata required for packaging by Crosswalk Android.

You are now ready to run the client application on your two targets, either on a desktop machine (Windows or Linux) or an Android device.

### Run on a desktop machine with Chrome

To run the application on a desktop machine via Chrome, do the following:

1.  Close Chrome. This is so you can start it with the correct command-line options in the next step.

2.  If you open an application in Chrome (via a `file://` URI), access to the webcam is blocked by default. Because you're going to open the application directly from the filesystem via a `file://` URI, you therefore need to explicitly enable access to the webcam.

    The solution is to start Chrome with the `--allow-file-access-from-files` option, which gives `file://` URIs access to the webcam (as well as [other](https://github.com/mrdoob/three.js/wiki/How-to-run-things-locally) [things](http://joshuamcginnis.com/2011/02/28/how-to-disable-same-origin-policy-in-chrome/)). As an example, to start the WebRTC client on Linux with Chrome, you would use the following command line:

        google-chrome --allow-file-access-from-files \
          file:///<path to xwalk-webrtc>/client/index.html

    The webcam should now be accessible.

### Run on Android with Crosswalk

During the host setup for Android ([Windows](#documentation/getting_started/windows_host_setup/Download-the-Crosswalk-Android-app-template), [Linux](#documentation/getting_started/linux_host_setup/Download-the-Crosswalk-Android-app-template)), you will have downloaded the Crosswalk Android bundle. You can use this to generate an Android package for the client application. Full details are on [this page](#documentation/getting_started/run_on_android), but here's a summary (tested on Linux):

    $ cd crosswalk-${XWALK-STABLE-ANDROID-X86}

    $ python make_apk.py --manifest=xwalk-webrtc/client/manifest.json
    ...
    An APK for the web application "WebRTC" including the
    Crosswalk Runtime built for x86 was generated successfully,
    which can be found at
    /home/me/crosswalk-6.35.131.9/WebRTC_0.0.1_x86.apk.
    An APK for the web application "WebRTC" including the
    Crosswalk Runtime built for arm was generated successfully,
    which can be found at
    /home/me/crosswalk-6.35.131.9/WebRTC_0.0.1_arm.apk.

    # install the package for your target's architecture
    $ adb install /home/me/crosswalk-6.35.131.9/WebRTC_0.0.1_arm.apk

Finally, start the application on the target by selecting its icon in the applications list.

### Make a call

Once the application is running on two targets, you should be able to make a call between them by following these steps:

1.  Make sure the [PeerJS server is running](#run-peerjs-server).

2.  In the first client, enter a string in the *Connect as* text box (I used "elliot"). Click the *Connect* button.

    If you are using Chrome, you will be prompted to allow the application to use the webcam:

    ![Chrome prompt to allow input device access](assets/crosswalk-webrtc-webcam-prompt.png)

    Click on *Allow* to grant access.

    A preview of the stream from the first client's camera should load into the "LOCAL" video element.

3.  Check the console window where the PeerJS server is running. You should see some output like this:

        new connection with id elliot

4.  In the second client, do the same: enter a name to *Connect as* and click *Connect*. Make sure you use a name which is different from the one you used for the first client (I used "chewie").

    A preview of the stream from the second client's camera should load into the "LOCAL" video element.

5.  Check the PeerJS console again for the connection message:

        new connection with id chewie

6.  Go back to the first client.

    In the *Make call to* text entry, enter the ID of the second client:

    ![Making a WebRTC call](assets/crosswalk-webrtc-making-call.png)

    Click the *Call* button.

    In the first client, you should see a message which states: "outgoing call initiated". You should eventually see the stream from the second client in the "REMOTE" video element.

    In the second client, you should see a message which states: "incoming call answered". You should eventually see the stream from the first client in the "REMOTE" video element.

### Troubleshooting

If one or both of the client applications isn't working, or you have problems making a call, there are a few things you can try:

*   Check the PeerJS server output. Make sure that connection requests are reaching the server and that it is registering the IDs correctly.

*   Make sure that you set the `SERVER_IP` variable in the `main.js` file to the correct value (the IP address of the machine running the PeerJS server).

*   Try opening two tabs in a single Chrome browser on a laptop machine, and load the client application into both. These tabs can still make calls to each other, but it's much easier to get at the logs (through the developer console: Ctrl+Shift+j).

*   To debug the client application running in Crosswalk Android, build the package with the `--enable-remote-debugging` option:

        python make_apk.py --enable-remote-debugging --manifest=...

    Once the application is launched on the Android target, open Chrome and go to the special "chrome://inspect" address. You should see the Android target listed, along with applications which can be debugged:

    ![Debugging the WebRTC client application on Android](assets/crosswalk-webrtc-debug.png)

    Click on the link to open a console for the application. Look for error messages.

*   As a last resort, you can reset the clients and server:

    <ol>
      <li><strong>Ctrl+c</strong> in the server console, then restart it with <code>node index.js</code>.</li>
      <li>Refresh the client application in the browser with <strong>Ctrl+F5</strong>.</li>
      <li>Close the client application on Android (e.g. with the task manager) and start it again.</li>
    </ol>

    Once you've followed these steps, go through the steps in [Make a call](#Make-a-call) again and the call should work.

## Useful WebRTC resources

*   [W3C specification for WebRTC](http://www.w3.org/TR/webrtc/)
*   [Google-sponsored webrtc.org website](http://www.webrtc.org/) is a good starting point
*   [HTML5 Rocks introduction to WebRTC](http://www.html5rocks.com/en/tutorials/webrtc/basics/) is also very useful.

## Acknowledgements

The application developed in this tutorial uses the [PeerJS JavaScript library](http://peerjs.com/); the [client](https://github.com/peers/peerjs) and the [server](https://github.com/peers/peerjs-server) libraries are released under the [MIT licence](https://github.com/peers/peerjs/blob/master/LICENSE).
