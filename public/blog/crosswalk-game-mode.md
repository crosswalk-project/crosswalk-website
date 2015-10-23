Introducing Crosswalk Game Mode:  Optimizations for HTML5 Games
 
Performance is one of the key challenges for web and hybrid games and there is still a gap between HTML5 and native applications, especially for games with complex scenarios, stunning effects, and other advanced features. In order to address this challenge, we are pleased to introduce some optimizations we are calling "Crosswalk Game Mode."

<div class="cosmicdiv"><br><br><a href="https://play.google.com/store/apps/details?id=org.cocos.CosmicCrash.googleplay&hl=en"><img class="cosmicimg" src="/assets/cosmiccrash-icon.jpg" /><br>
CosmicCrash</a></div>

The basic idea is to hook native processing capabilities into the HTML5 rendering pipeline to achieve similar performance as native, while keeping all the HTML5 features. This effort started with a collaboration with the engineers of [Cocos2d](http://xwalk.com/documentation/community/tools.html?tool=8), an open-source, cross-platform, game-development tool suite. Working with Cocos, we successfully optimized a typical [Cocos2d HTML5](https://github.com/cocos2d/cocos2d-html5) game called CosmicCrash. With the optimizations, the game achieves native-like performance with an impressive 5X speedup over Chrome for complex scenarios. You can check CosmicCrash out on [Google Play](https://play.google.com/store/apps/details?id=org.cocos.CosmicCrash.googleplay&hl=en).

At present, this optimization is mainly targeted at Cocos2D-HTML5 developers. The source code is in the [Crosswalk lite branch](https://download.01.org/crosswalk/releases/crosswalk-lite/android/), guarded by a compilation option. We would like to get your feedback on our approach as we continue to enhance it. We would also love to collaborate with more HTML5 game engine and framework providers, to apply similar ideas and optimization techniques that can benefit a wider game developer audience.
 
For workflow details on how to build an optimized Cocos2d-HTML5 game with  Crosswalk Game Mode, please refer to [Cocos2d-Cordova-Crosswalk](https://github.com/crosswalk-project/crosswalk-cordova-android/tree/cocos2d-crosswalk-cordova).

<br>
<hr>
<h2>Crosswalk Game Mode 介绍：HTML5游戏优化</h2>
 
<p>HTML5游戏的性能是开发者们最关心的问题之一，特别是在移动平台上，HTML5游戏的性能相比原生应用还存在着较大差距，尤其是在游戏中逻辑复杂，使用了粒子系统，光影效果等特效的场景，性能差距最为明显，为了弥补这些性能的差距，我们提出了Crosswalk Game Mode。

<p>优化的基本思想是将原生游戏引擎的性能优势引入到Crosswalk渲染引擎中，以使HTML5游戏的性能达到原生的体验，并保留了HTML5原有的所有优势。作为优化的开始，我们与业界领先的游戏引擎和开发工具提供商Cocos合作，针对[Cocos2D HTML5](https://github.com/cocos2d/cocos2d-html5)进行了优化，并将旗下一款流行的HTML5游戏--[CosmicCrash](https://play.google.com/store/apps/details?id=org.cocos.CosmicCrash.googleplay) 成功地运行在我们优化后的Crosswalk Game Mode中，其性能已经基本达到了原生的体验，在某些场景下比在Chrome浏览器上运行提升了近5倍。

<p>对于游戏中必不可少的登陆，支付和社交分享等功能，我们提供了AnySDK Cordova Plugin机制，可以轻松的使用JS来调用这些功能。

<p>如何使用Crosswak Game Mode来打包您的Cocos2d-HTML5游戏，请到github下载 [Cocos2d-Cordova-Crosswalk](https://github.com/crosswalk-project/crosswalk-cordova-android/tree/cocos2d-crosswalk-cordova) 抢先体验。

<style>
  .cosmicdiv {
    float: right;
    text-align: center;
    width: 120px;
  }
  .cosmicimg {
     border-radius: 4px;
  }
</style>
