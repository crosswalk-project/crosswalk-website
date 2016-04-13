# 贡献代码
本页面主要列举了为Crosswalk贡献代码的过程。关于获取源码和构建Crosswalk的信息，请参见[Building Crosswalk](/contribute/building_crosswalk.html)。

## 概述
下列是关于为Crosswalk贡献代码的推荐步骤：

* 选择或者创建一个bug报告用于工作。
* 声明你的“实现意图”。
* 开发你的改变。
* 保证你的修改符合指南中的代码风格。 check-style脚本可能会有帮助。
* 运行单元测试。
* 在你的工作目录下添加任何新的文件。
* 准备你的提交信息。
* 使用Github的pull请求系统来提交你带审查的patch。
* 执行任何审阅者建议的修改。
* 一旦审阅，patch将会分发给你。
* 请留意任何它可能造成的regression（希望没有）！

下文是关于上述某些步骤中的细节。

### 术语

*   **PR:**

    Pull请求 - 一个将你的代码添加到主Crosswalk代码库中的请求（通过github）

*   **LGTM:**

    "Looks Good To Me" - 一个用于确定一个意图实现（参见下文）的pull请求被某个项目成员赞同的缩写。

## 选取一个bug报告

[Jira Issue Tracker](https://crosswalk-project.org/jira/)是Crosswalk项目贡献者交流的中心点。在那里几乎每个贡献对应一个issue。

选择一个issue进行研究，或者如果没有合适的issue存在创建一个新的。为了避免重复，在创建新的issue之前一定要检索数据库。

你应该记录下你研究的issue的ID，如果你需要声明一个（参见下文），你可以将它包含进你的"intent to implement"中。ID是issue的URL后面的**XWALK-N**标识符后缀：例如，对于https://crosswalk-project.org/jira/browse/XWALK-898，ID是**XWALK-898**。

## 声明你的"intent to implement"

为了提高代码审查的效率，我们从Blink项目汲取经验，采用了"intent to implement"的实践方案。这个方法主要是让项目负责人和其他的开发人员了解一个开发人员（或者发开团队）计划实现什么，以及他们计划何时、如何实现。这样便可以让社区评论早期的设计选择，避免当一个实现已经太超前时，取消或者重做造成的损失。

一个实现计划需要新功能，有意义的重构，或者某些有可能有争议的实现。如果你不确定你的实现是否属于这些分类，可以通过使用[crosswalk-dev mailing list or #crosswalk IRC channel](/documentation/community.html)来咨询怎样处理。

为了声明一个"intent to implement"，一个开发人员应该根据下列规则向[crosswalk-dev mailing list](https://lists.crosswalk-project.org/mailman/listinfo/crosswalk-dev)发送一封邮件。

**主题** (简明的总结):

<pre>
实现目标 &lt;目标总结&gt;
</pre>

**正文** (关于将要实现什么的具体细节):

<pre>
描述：
&lt;这个实现是关于什么，怎么样实现以及为什么需要实现&gt;

受影响的组件：&lt;受影响的Crosswalk组件&gt;

相关特征：&lt;Jira issue ID&gt;

目标发布：&lt;Crosswalk版本号&gt;

实现细节
&lt;根据复杂度，这个可以是一行简短的讨论，或者指向一个设计文档的链接（例如，Google Docs）；然而，我们更希望在大规模设计被完成前开始讨论。&gt;

</pre>

在后续工作开始前计划应该得到[relevant owner(s)](https://crosswalk-project.org/contribute/reviewer_policy)的赞同("LGTM")。

## 开发你的改变

在你的"intent to implement"被赞同后，你便可以开始你的工作。

确保在文件的开始部分，你引入的任何新的代码包含相应的许可文本。如果你是一个新的文件的作者，希望被引入的许可文本可以在LICENSE文件或者任何已经存在的文件中找到。

当你第一次完成一个patch时，你应该将你的名字添加到AUTHORS文件中。

## 代码风格指导

Patches必须遵守代码风格指导。当你把patch上传到bug tracker中，你的patch将被自动检测是否符合代码风格。

详见[Crosswalk代码风格](/contribute/coding_style.html)。


## 提交信息指南

你的提交和／或PR应该引入你所研究的issue的ID。你可以通过以下方式使用issue ID来提交你的信息或者PR：

*   从一个描述信息中包含issue ID的PR中引入一个issue。这样会使得在相应的Crosswalk Jira issue上提及PR。

    例如，如果你现在工作在issue XWALK-898上，你可以像下文一样在提交信息时引入issue：

        Append "-tests" to package name, as per XWALK-898.

    仅仅打开或者关闭一个PR（在描述信息中包含issue ID）将会生成一条评论添加到Jira。更新一个PR将*不会*更新相关的Jira issue。

*   为了关闭一个相关的Jira issue，向PR描述中添加一行格式为**BUG=XWALK-N**的信息（大小写敏感）。当PR合并时，这样做将解析相关的issue。使用issue URL`(**BUG=https://path/to/issue/XWALK-N**)`也是被接受的。

    例如，如果你的PR关闭了issue XWALK-898，你可以将下文信息包含仅PR描述中：

        BUG=XWALK-898

    或者这样：

        BUG=https://crosswalk-project.org/jira/browse/XWALK-898

    如果一个PR不能完全解决一个issue，不要使用"BUG="的前缀，因为JIRA ticket可能被错误关闭。一个不同的前缀，例如"Related to: XWALK-898"，是可以的并且由于仅陈述ticket号码（例如， "XWALK-898"）。还要注意我们在这里仅仅谈论的是_pull request message_。你真实的git提交信息应该使用前缀"BUG="，因为它的内容不会被用来引入或者关闭任何JIRA ticket。

    如果一个PR解决了多个issue，在描述信息的不同行分别引用它们，其中每行的开始均是"BUG="。

注意即使前缀是"BUG="，这个机制也可以应用到feature和任务中。

## 回归测试

不久buildbot基础构建便可以使用。这些buildbot将运行单元测试和浏览器测试。

你负责保持tree是绿色的。如果因为你的patch tree变红了，并且其他贡献者也不能解决这个问题，你的提交可能被撤销。

## 回复审阅者

在Crosswalk接收你的patch并放入资源控制库中前，你的patch必须被一个Crosswalk审阅者同意。一个审阅者可以在pull请求中回复"LGTM"表示赞同，也可以要求你修改你的patch。偶然情况下，一个patch可能被永久否决，这意味着审阅者认为这个feature永远也不应该添加到tree中。当你提交修改patch时，审阅过程可能会在你和审阅者之间进行多次迭代。

## 获取提交权限

默认情况下，贡献者没有Crosswalk资源库的push权利。在贡献一些patch（~5-10）后，你可以向OWNER申请授予你push权限。这个权限被授予某些责任的期待：committer是一群关心Crosswalk项目并且希望帮助它实现愿望的人。一个committer不仅仅是某个能做出改变的人，同时也可以证明他或者她与团队合作的能力，能获取最有能力的人审阅代码，贡献高质量代码并且跟进解决问题（代码或者测试）

## 获取审阅权限

我们的[Reviewer守则](/contribute/reviewer_policy.html)提供了关于如何获取审阅者权限的详细信息。
