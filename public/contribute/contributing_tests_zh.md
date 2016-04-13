# 贡献测试
这个页面列出了为Crosswalk贡献测试用例和测试套件的过程。整个过程追随[贡献代码](/contribute/contributing-code.html)，下文中列出了相关细节。

关于如何获取源代码，请参考[Crosswalk Test Suite](/documentation/test_suite.html)。


## 概述

欢迎以及鼓励每个人贡献测试用例或者测试套件。

这些是关于贡献测试用例到Crosswalk Test Suite的推荐步骤：

* 复制[Crosswalk Test Suite 资源库](https://github.com/crosswalk-project/crosswalk-test-suite) (如果你是一段时间之前复制的，请确认你仍然保持相对同步）。
* 为你的修改创建一个新的分支。
* Develop your changes.
* 确定你的修改符合代码风格规范。check-style脚本可能会有帮助。
* 运行单元测试。
* 在你的工作目录下添加任何新的文件。
* 准备你的提交信息。
* 使用Github的pull请求系统来提交你待审查的patch。
* 执行任何审阅者建议的修改。
* 一旦审阅，patch将会分发给你。

下文是关于上述某些步骤中的细节：

    $ git clone https://github.com/crosswalk-project/crosswalk-test-suite.git
    $ git checkout -b topic
    $ git add file1 file2 ... fileN 
    $ git commit (Follow "Commit message guidelines" section below to add commit messages)
    $ git push origin topic

## 选取一个bug报告

[Jira issue tracker](https://crosswalk-project.org/jira/)是Crosswalk项目贡献者交流的中心点。为了测试套件的bug和任务，你可以选取[Crosswalk Test Suite 元素](https://crosswalk-project.org/jira/browse/XWALK/component/10303)，选取一个issue作为工作点。如果没有合适的issue存在，可以新建一个新的。为了避免重复，在创建新的issue之前一定要检索数据。

如果你需要声明一个（参见下文），你应该记录下你研究的issue的ID，所以你可以将其包含在你的测试用例PR中。ID是issue的URL后面的XWALK-N标识符后缀：例如，对于https://crosswalk-project.org/jira/browse/XWALK-4782，ID是**XWALK-4782**。


## 代码风格指导

Patches必须遵守代码风格指导。详见[Crosswalk Test Suite 代码风格](https://github.com/crosswalk-project/crosswalk-test-suite/blob/master/doc/Coding_Style_Guide_CheatSheet.md)。


## 提交信息指南

你的提交和／或PR应该引入你所研究的issue的ID。请参考[贡献代码](/contribute/contributing-code.html)页面的“代码贡献”部分。

最好遵循贡献测试用例的提交信息模板：


    [区域前缀] 大写，简短（50或者更少的字符）

    更加详细的解释性文本，如果必要。大约72个字符左右。
    在某些情景下，第一行作为邮件的主题并且其余部分作为正文内容。
    分割总结和正文的空白行至关重要（除非你完全省略正文）；
    如果你把两部分一起运行，像rebase之类的工具可能会混淆。

    这里是错误分析；描述失败可能的原因。
    如果引用一个bug，请使用完成的URL。

    受影响的测试（设计｜认证）：新生 0, 更新 5, 删除 0
    单元测试平台：Crosswalk Project for <Android|IoT|Linux|Windows>; <version>
    单元测试结果总结: 通过 5, 失败 0, 阻塞 0

    BUG=https://crosswalk-project.org/jira/browse/XWALK-4782

## 回复审阅者

当PR被审阅时，[Crosswalk Test Suite 资源库](https://github.com/crosswalk-project/crosswalk-test-suite)审阅者将会通过你的patch。一个审阅者可以在pull请求中回复"LGTM"表示赞同，也可以要求你修改你的patch。当你提交修改patch时，审阅过程可能会在你和审阅者之间进行多次迭代。


## 认证和作者姓名

除了在COPYING和／或者NOTICE文件中指出，或者头部的认证信息，Crosswalk Test Suite源代码使用一种可以在LICENSE文件中找到的BSD-style认证。 

确保在文件的开始部分，你引入的任何新的代码包含相应的许可文本。如果你是一个新的文件的作者，希望被引入的许可文本可以在LICENSE文件或者任何已经存在的文件中找到。

当你第一次完成一个patch时，你应该将你的名字添加到AUTHORS文件中。详见[Crosswalk测试模板](https://github.com/crosswalk-project/crosswalk-test-suite/blob/master/tools/template/)。

 
