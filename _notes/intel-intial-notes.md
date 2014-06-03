The opposite problem of the XDK: once I do decide I want to download it, I have to decide what version. It’s okay to offer many different options, but they should be somewhere else, less prominent. The download page show have one “blessed” options: this is the download. If you know what you’re looking for, the other options are over here.

# Gains worth mentioning:

- “The Crosswalk HTML5 <video> implementation is hardware accelerated on IA (x86) mobile platforms.” [Source](https://software.intel.com/en-us/html5/articles/crosswalk-application-runtime)
- “The Crosswalk runtime provides access to the WebAudio API on a mobile platform and, like the <video> tag, includes optimizations for IA (x86) mobile platforms.” [Source](https://software.intel.com/en-us/html5/articles/crosswalk-application-runtime)
- Performance optimisations for WebGL and WebRTC are under development, which make a big difference on mobile [Source](https://software.intel.com/en-us/html5/articles/crosswalk-application-runtime)
- Flexbox
- Responsive Images: picture element is just getting support in Chrome Canary, but it is already in Intel’s Crosswalk
- Position fixed or position sticky? Need to look into this still

youcanuse: marketing campaign built upon caniuse that shows how Crosswalk makes “tomorrow’s web” ready for Android today

## Android

Remember: the Android branding is (relatively) open source. You can use the Android character and modify it to your hearts content.

## Existing confusions

> Is it a replacement of Cordova? Is it an extension of Cordova?

[Nice Q&A here that I’m sure mirrors others thoughts.](https://forums.html5dev-software.intel.com/viewtopic.php?f=34&t=4904)

> The Chrome Webview is *way* slower than the actual Chrome Browser. So, always test on the Chrome-Webview. Testing on the Browser will get you false positive performance. [Source](https://groups.google.com/forum/#!msg/phonegap/1ZxXe6chHZc/2igVilKRPUYJ)

I forget about this often. True of testing in Mobile Safari as well: the browsers are faster than the in-app web views. Also a nice performance angle you don’t necessarily have to quantify. It just doesn’t have this restriction. Remember, not as many people realise these are different however, so you’d also have to explain the current state of affairs. Maybe that’s the (very minor) shock value: your app is fast in the browsers but slow in the web view

This article shows lots of potential pain points: in-webview performance of 

## As a fork

One major benefit of presenting Intel Crosswalk as what it is—a fork of Chromium— is that it instantly provides context that is lacing in the marketing materials now. What is Crosswalk? Where did it come from? Why is Intel doing this? Suggesting it it branches from a comparatively well known Google endeavour suggests answers to some of these questions.

Right now, you can find questions in the Intel XDK forms asking if Crosswalk is a replacement for Cordova. I don’t think these questions would as readily appear if Chromium was explicitly mentioned.
