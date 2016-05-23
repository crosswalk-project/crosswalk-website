# 测试套件

Crosswalk项目测试套件包括可以验证项目特性的测试样例。这些测试均是开源的。源代码可以从[Crosswalk测试套件库](https://github.com/crosswalk-project/crosswalk-test-suite)中获取。

## 概述

Crosswalk项目包含若干组件。例如：

* Web API -- 供运行在Crosswalk运行时环境下的web应用使用的API
* Embedding API --  为了在Android应用中嵌入Crosswalk的Java API

针对这些组件的对应的测试套件按照如下方式组织：

* Web API测试
* Embedding API测试
* Web运行时和特性测试
* 基于Corbova的Crosswalk的测试
* 用例

工具，文档，以及一些其他方面的测试（例如，稳定性测试和BAT测试）也被包含在测试套件中。详见[测试套件wiki](https://github.com/crosswalk-project/crosswalk-website/wiki/Crosswalk-test-suite)。

## 开发指南

测试套件为测试样例开发者以及贡献者提供了一系列的[开发指南](https://github.com/crosswalk-project/crosswalk-test-suite/tree/master/doc)。这些指南涵盖了测试套件资源的布局，代码风格，测试样例命名规范，文件夹命名规范，测试样例分类，以及如何为像Web API, Embedding API, Cordova和Web运行时之类的组件，向测试套件添加测试样例。在开发测试样例前，你可能需要了解[测试样例优先级和测试覆盖率](https://github.com/crosswalk-project/crosswalk-website/wiki/Crosswalk-test-suite)。

## 分支

测试套件不仅涵盖了master分支，也涵盖了beta分支，因为通常情况下bug对它们均会产生影响并且有时候我们需要在beta分支上添加修补程序。测试套件分支的schedule与Crosswalk项目的[分支日期](https://github.com/crosswalk-project/crosswalk-website/wiki/Release-dates)一致，均遵循[Crosswalk发布规律](https://github.com/crosswalk-project/crosswalk-website/wiki/Release-methodology)。

## Web测试服务
运行测试套件最简单的方式便是使用[Web测试服务](http://wts.crosswalk-project.org/)。它是一个基于web的测试运行器，可以用于显示该浏览器或者web运行时环境是否能够很好地支持web标准以及不同平台上的相关规格。基于Crosswalk项目的[Web测试客户端](https://play.google.com/store/apps/details?id=org.xwalk.web_test_client)也可以通过Google Play获取。详见[Web测试服务wiki](https://github.com/crosswalk-project/web-testing-service/wiki)。

## 贡献测试用例

任何一个帮助验证特性或者性能的样例都是有意义的。我们鼓励大家参与到贡献测试开发中。关于详细的贡献步骤，请参见页面[贡献测试代码](/contribute/contributing_tests_zh.html)。
