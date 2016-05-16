# 贡献测试代码
这个页面列出了为Crosswalk贡献测试用例和测试套件的过程。整个过程遵循[贡献代码](/contribute/contributing-code_zh.html)，下文中列出了更多相关细节。

关于如何获取源代码，请参考[Crosswalk测试套件](/documentation/test_suite_zh.html)。


## 概述

欢迎以及鼓励每个人贡献测试用例或者测试套件。

关于贡献测试用例到Crosswalk测试套件，请参考下面的步骤：

* 复制[Crosswalk测试套件资源库](https://github.com/crosswalk-project/crosswalk-test-suite) (如果你是一段时间之前复制的，请确认你仍然保持相对同步）。
* 为你的修改创建一个新的分支。
* 编写你的代码。
* 确定你的修改符合代码风格规范。check-style脚本可能会有帮助。
* 运行单元测试。
* 在你的工作目录下添加所有新的文件。
* 准备你的提交信息。
* 使用Github的pull请求系统来提交你的补丁，以便审查。
* 按照审阅者建议的建议进行修改。
* 一旦审查通过，你的补丁将会合并到代码库中。

下文是关于上述某些步骤中的细节：

    $ git clone https://github.com/crosswalk-project/crosswalk-test-suite.git
    $ git checkout -b topic
    $ git add file1 file2 ... fileN 
    $ git commit (Follow "Commit message guidelines" section below to add commit messages)
    $ git push origin topic

## 选取一个bug报告

[Jira问题追踪工具](https://crosswalk-project.org/jira/)是Crosswalk项目贡献者的交流中心。为了测试套件的bug和任务，你可以选取[Crosswalk测试套件元素](https://crosswalk-project.org/jira/browse/XWALK/component/10303)，选取一个issue作为工作研究点。如果你面临的问题在上面没有类似的，可以新建一个新的问题。为了避免重复，在创建新的issue之前一定要检索数据。

如果你需要声明一个新的测试用例（参见下文），你应该记录下你研究的issue的ID，以便将其包含在你的测试用例PR中。ID是位于问题链接尾部的标识符**XWALK-N**：例如，对于https://crosswalk-project.org/jira/browse/XWALK-4782，ID是**XWALK-4782**。


## 代码风格指南

补丁必须遵循代码风格指南。详见[Crosswalk测试套件代码风格](https://github.com/crosswalk-project/crosswalk-test-suite/blob/master/doc/Coding_Style_Guide_CheatSheet.md)。


## 提交信息指南

你的提交和／或PR应该包含你所研究的issue的ID。请参考[贡献代码](/contribute/contributing-code_zh.html)页面的“代码贡献”部分。

测试用例最好遵循下面的提交信息模板：


    [区域前缀] 大写，简短（50或者更少的字符）

    更加详细的解释性文本，如果必要。大约72个字符左右。
    在某些情景下，第一行作为邮件的主题并且其余部分作为正文内容。
    用空白行分开总结和空白行至关重要（除非你完全没有正文）；
    如果你把两部分放在一起（即没有空白行分开），那么像rebase之类的工具就会混淆。

    这里是错误分析；描述失败可能的原因。
    如果引用一个bug，请使用完整的URL。

    受影响的测试（设计｜认证）：新生 0, 更新 5, 删除 0
    单元测试平台：Crosswalk Project for <Android|IoT|Linux|Windows>; <version>
    单元测试结果总结: 通过 5, 失败 0, 阻塞 0

    BUG=https://crosswalk-project.org/jira/browse/XWALK-4782

## 回复审阅者

当PR被审阅后，[Crosswalk测试套件代码库](https://github.com/crosswalk-project/crosswalk-test-suite)审阅者将会通过你的补丁。一个审阅者可以在pull请求中回复"LGTM"表示赞同，也可以请求你修改补丁。当你提交修改补丁时，在你和审阅者之间，可能会有多次审阅流程。


## 认证和作者姓名

除了在COPYING和／或者NOTICE文件中指出，或者包含在头部的认证信息中，Crosswalk测试套件源代码遵循BSD许可证,许可证信息可以在LICENSE文件中找到。

请确保在新代码的文件头部，包含有licence的说明字样。如果你是一个新的文件的作者，licence的编写可以参考LICENSE文件或者其他现存的文件。

当你第一次完成一个patch时，你应该将你的名字添加到AUTHORS文件中。详见[Crosswalk测试模板](https://github.com/crosswalk-project/crosswalk-test-suite/blob/master/tools/template/)。
