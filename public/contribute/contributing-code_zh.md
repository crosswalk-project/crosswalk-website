# 贡献代码
本页面主要列举了为Crosswalk贡献代码的过程。关于获取源码和编译Crosswalk的信息，请参见[编译Crosswalk](/contribute/building_crosswalk_zh.html)。

## 概述
下列是关于为Crosswalk贡献代码的推荐步骤：

* 选择或者创建一个用于工作的bug报告。
* 声明你的“实现计划”。
* 开发你的改变。
* 保证你的修改符合指南中的代码风格。 check-style脚本可能会有帮助。
* 运行单元测试。
* 在你的工作目录下添加所有新文件。
* 准备你的提交信息。
* 使用Github的pull请求系统来提交你的补丁，以便审查。
* 按照审阅者的修改意见进行修改。
* 一旦审查通过，你的补丁将会添加到Crosswalk代码库中。
* 请留意任何它可能造成的倒退（希望没有）！

下文是关于上述某些步骤中的细节。

### 术语

*   **PR:**

    Pull请求 - 一个将你的代码添加到Crosswalk主代码库中的请求（通过github）

*   **LGTM:**

    "Looks Good To Me" - 一个缩写词，用于项目成员赞同pull请求或者实现计划（参见下文）等。

## 选取一个bug报告

[Jira问题追踪工具](https://crosswalk-project.org/jira/)是Crosswalk项目贡献者交流中心。在那里几乎每个贡献对应一个issue。

选择一个问题进行研究，或者如果你面临的问题在上面没有类似的，可以创建一个新的问题。为了避免重复，在创建新的问题之前一定要检索数据库。

你应该记录下你研究的issue的ID，以便当您需要说明一个"实现计划"时，可以将ID号写入到里面。ID是位于问题链接尾部的标识符**XWALK-N**：例如，对于https://crosswalk-project.org/jira/browse/XWALK-898，ID是**XWALK-898**。

## 声明你的"实现计划"

为了提高代码审查的效率，我们从Blink项目汲取经验，采用了"实现计划"的方案。这个方法主要是让项目负责人和其他的开发人员了解一个开发人员（或者发开团队）计划实现什么，以及他们计划何时、如何实现。这样便可以让其他项目成员对早期的设计进行评论，避免当一个实现已经太超前时，对其取消或者重做造成的损失。

一个实现计划是一个新功能，有意义的重构，或者是针对有争议的实现。如果你不确定你的实现是否属于这些分类，可以通过使用[crosswalk-dev邮箱列表或者#crosswalk IRC频道](/documentation/community_zh.html)来咨询怎样处理。

为了声明一个"实现计划"，一个开发人员应该根据下列规则向[crosswalk-dev邮箱列表](https://lists.crosswalk-project.org/mailman/listinfo/crosswalk-dev)发送一封邮件。

**主题** (简明的总结):

<pre>
实现目标 &lt;目标总结&gt;
</pre>

**正文** (关于将要实现什么的具体细节):

<pre>
描述：
&lt;这个实现是关于什么，怎样实现以及为什么需要实现&gt;

受影响的组件：&lt;受影响的Crosswalk组件&gt;

相关特征：&lt;Jira issue ID&gt;

目标发布：&lt;Crosswalk版本号&gt;

实现细节
&lt;根据复杂度，这个可以是一行简短的讨论，或者指向一个设计文档的链接（例如，Google Docs）；然而，我们更希望在大规模设计被完成前开始讨论。&gt;

</pre>

在后续工作开始前计划应该得到[相关负责人](https://crosswalk-project.org/contribute/reviewer_policy_zh)的赞同("LGTM")。

## 开发你的补丁

在你的"实现计划"通过后，你便可以开始你的工作。

请确保在新代码的文件头部,包含有licence的说明字样.如果您是一个新文件的所有者, licence的编写可以参考LICENSE文件或者其他现存的文件。

当你第一次完成一个patch时，你应该将你的名字添加到AUTHORS文件中。

## 代码风格指南

补丁必须遵守代码风格指南。当你把patch上传到bug tracker中，你的patch将被自动检测是否符合代码风格。

详见[Crosswalk代码风格](/contribute/coding_style_zh.html)。


## 提交信息指南

你的提交和／或PR应该包含你所研究的问题的ID。你可以在提交信息或者PR中，按照下面的方式使用问题ID：

*   为了在PR上提及issue，你需要从在PR的描述信息中包含issue ID。这样会使得在相应的Crosswalk Jira issue上，Jira系统自动添加关于PR的相关评论。

    例如，如果你现在工作在issue XWALK-898上，你可以像下文一样在提交信息时引入issue：

        Append "-tests" to package name, as per XWALK-898.

    仅打开和关闭一个PR（在描述信息中包含issue ID）将会生成一条添加到Jira的评论。更新一个PR将*不会*更新相关的Jira issue。

*   为了关闭一个相关的Jira issue，向PR描述中添加一行格式为**BUG=XWALK-N**的信息（大小写敏感）。通过这种方法，当PR被合并时,JIRA上的issue状态会自动被标识位resolved状态。使用issue URL`(**BUG=https://path/to/issue/XWALK-N**)`也是可行的。

    例如，如果你的PR可以解决问题XWALK-898，那么PR的描述应该包含下面的内容：

        BUG=XWALK-898

    或者这样：

        BUG=https://crosswalk-project.org/jira/browse/XWALK-898

    如果一个PR不能完全解决一个问题，不要使用"BUG="的前缀，因为JIRA ticket可能被错误关闭。一个不同的前缀，例如"Related to: XWALK-898"，仅描述标签号（例如， "XWALK-898"）是比较合适的。还需要注意，我们在这里仅仅谈论的是_pull请求信息_。你真正的git提交信息应该使用前缀"BUG="，因为它的内容不会被用来引入或者关闭任何JIRA ticket。

    如果一个PR解决了多个issue，可以在描述信息中的不同行分别引用它们，其中每行的开始均为"BUG="。

注意虽然前缀是"BUG="，这个机制也可以应用到feature和任务中。

## 回归测试

不久，就可以使用buildbot进行测试。这些buildbot将运行单元测试和浏览器测试。

你负责保持tree是绿色的（即所有测试通过）。如果因为你的补丁 tree变红了（即有些测试失效），并且其他贡献者联系不到你或者他们自己也不能解决这个问题，那么你的提交可能被撤销。

## 回复审阅者

在Crosswalk接收你的补丁并放入源码库之前，你的补丁必须被一个Crosswalk审阅者同意。一个审阅者可以在pull请求中回复"LGTM"表示赞同，也可以要求你修改你的补丁。偶尔，一个补丁可能被永久否决，这意味着审阅者认为这个特性永远也不应该添加到tree中。当你提交修改补丁时，在你和审阅者之间，可能有多次审阅过程。

## 获取提交权限

默认情况下，贡献者没有Crosswalk源码库的push权利。在贡献一些补丁（~5-10）后，你可以向OWNER申请授予你push权限。权限意味着一些责任：committer是一群关心Crosswalk项目并且希望帮助它实现愿望的人。一个贡献者不仅仅是某个能改进项目的人，同时也已经证明他或者她有与团队合作的能力，可以让最资深的人审阅代码，贡献高质量代码并且跟进解决问题（代码或者测试）

## 获取审阅权限

我们的[审阅者守则](/contribute/reviewer_policy_zh.html)提供了关于如何获取审阅者权限的详细信息。
