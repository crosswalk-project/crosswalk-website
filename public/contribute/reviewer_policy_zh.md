## OWNERS文件

`OWNERS`文件用于指定一组审阅者，以及每个人所负责修改的领域。

当贡献代码时，对于每一个在PR中被修改的文件，该领域的审阅者，或者更高领域的审阅者，需要做出审阅意见"LGTM"。这意味着赞成者必须在PR中输入"lgtm"（大小写均可以）。

### 样例 

你想修改`xwalk/extensions/browser/xwalk_extension_service.h`中的代码。

你或者某个修改的审阅者之一必须是`xwalk/extensions/browser/`的所有者。

谁拥有`xwalk/extensions/browser`？ 每一位在`xwalk/extensions/browser/OWNERS`文件中记录的人。除此之外，每一个在`xwalk/extensions/OWNERS`中记录的人也可以审阅这个代码，以及所有的上层目录。如果被修改文件所在的目录中没有`OWNERS`文件，则向上层寻找最近的一个。

另一个样例：Another

你想重构并对`XWalkExtension`进行一些修改，由于一个方法被重命名，这需要你对多个不同目录下的文件做出修改。你对`xwalk/extensions/common/xwalk_extension.h`进行一些修改，以及`xwalk/experimental`中的调用点，`xwalk/application`和别处，跨越多个不同的子目录。你可以获取某个人的LGTM，这个人或者在这20个子目录的OWNERS文件中，或者在`xwalk/OWNERS`文件中。

## 为什么是OWNERS？

OWNERS是一些对特定领域代码特别熟悉的人。他们深刻地了解代码如何工作，编译方式的原因以及如果为了提高它需要做些什么。

OWNERS负责保证他们目录下代码保持高质量以及后期的提高。

## 谁应该在OWNERS文件中？

只有对该目录积极贡献力量的人，才会被列为OWNER。

OWNER需要具备非凡的判断力，团队能力和维护Crosswalk开发规则的能力。他们必须理解开发过程。

除此之外，如果某人被列为一个目录下的OWNER，他必须经过其他相关目录的OWNER的同意。成为OWNER的一些原则：

  * 已经表现的像一个OWNER，提供高质量的审阅和设计反馈
  * 已经提交了很多对于相关目录的重要修改
  * 在过去的90天内已经为相关目录提交或者审阅了大量的工作
  * 可以跟其他OWNER一起为相关目录审阅代码
  * 已经证明有能力理解Crosswalk各部分之间的关系

## 如何修改OWNERS文件？

由一个目录的OWNERS（和它的父级目录）负责更新列表。为了更好地促进代码模块化，OWNERS列表应该尽可能小。当一个目录拥有上百个文件时，了解哪些OWNER熟悉哪些文件就会变得困难。

在Crosswalk中有一个邮箱列表致力于维护OWNERS文件，其中所有的OWNER均参与。在改变一个目录下的OWNERS文件前，一个OWNER必须首先向这个邮箱列表发送一封关于修改的邮件，并根据上文的指导原则清楚地阐述做出修改的原因。

为了实际进行修改，它必须获得至少其他两个OWNER的正面支持。如果关于修改有否定的反馈，则父级目录的OWNER拥有最终权利（最高到`xwalk/OWNERS`）。

邮件和其他OWNER的赞成必须保证遵循上文的原则，并且通知其他OWNER。在至少两个支持者和没有否决反馈的７天后，应该创建一个pull请求的patch，如果是一个新的OWNER，他／她应该合并pull请求来确保代码仓库权限的有效性。
