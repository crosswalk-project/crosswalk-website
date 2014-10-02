# Write an extension in C++ (2)

This is a modified version of the [tutorial](/documentation/Tizen_IVI_extensions/write_an_extension_in_c++.md) to provide more robust handling of asynchronous callbacks. The mechanism illustrated below is inspired by real production code.

## Modified Javascript File

These modifications are transparent to users of your extension as they don't change the API.

    /**
     * Javascript API file for extension
     */
    var echoListener = null;
    var _callbacks = {};
    var _next_reply_id = 0;

    var getNextReplyId = function() {
      return _next_reply_id++;
    };

    extension.setMessageListener(function(json) {
      var message = JSON.parse(json);
      var reply_id = message.reply_id;
      var msg = message.msg;
      var callback = _callbacks[reply_id];
      if (typeof(callback) === 'function') {
        callback(msg);
        delete msg.reply_id;
        delete _callbacks[reply_id];
      } else {
        console.log('Invalid reply_id: ' + reply_id);
      }
    });

    exports.echoAsync = function (msg, callback) {
      var reply_id = getNextReplyId();
      _callbacks[reply_id] = callback;
      var resp = {"msg": msg};
      resp.reply_id = reply_id;
      extension.postMessage(JSON.stringify(resp));
    };

    exports.echoSync = function (msg) {
      return extension.internal.sendSyncMessage(msg);
    };

As you can see, we associate a unique ID to each callback and record it in a `_callbacks` object. The ID is passed with the message to the C++ part of the extension in a JSON string. The C++ part is includes the same ID in its response, so the event listener can call the right callback.

As the callbacks should only fire once, they are removed from the `_callbacks` object as soon as they return.

## Modified C++ Instance class

We just modify the implementation (.cc file):

    // Copyright (c) 2014 Intel Corporation. All rights reserved.
    // Use of this source code is governed by a BSD-style license that can be
    // found in the LICENSE file.

    #include "extension/echo_instance.h"
    #include "common/picojson.h"

    EchoInstance::EchoInstance() {
    }

    EchoInstance::~EchoInstance() {
    }

    void EchoInstance::HandleMessage(const char* message) {
      picojson::value v;
      std::string err;
      picojson::parse(v, message, message + strlen(message), &err);
      if (!err.empty()) {
        std::cout << "Ignoring message.\n";
        return;
      }
      std::string msg = v.get("msg").to_str();
      double reply_id = v.get("reply_id").get<double>();

      std::string resp = PrepareMessage(msg);

      picojson::object o;
      o["msg"] = picojson::value(resp);
      o["reply_id"] = picojson::value(reply_id);
      picojson::value rv(o);
      PostMessage(rv.serialize().c_str());
    }

    void EchoInstance::HandleSyncMessage(const char* message) {
      std::string resp = PrepareMessage(message);
      SendSyncReply(resp.c_str());
    }

    std::string EchoInstance::PrepareMessage(std::string msg) const {
      return "You said: " + msg;
    }


Here the `picojson` library is used to decode and encode the JSON messages: `picojson` is already in the `common/` directory, included earlier in the tutorial.
