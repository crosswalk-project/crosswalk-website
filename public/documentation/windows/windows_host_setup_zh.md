# Windows环境搭建

以下教程已经在Windows 8 Enterprise版64位环境下进行了测试。它们应该同样适用于常规的Windows Home版本以及最新的Windows 10。

下列的这些步骤可以帮助你开发运行在Windows上的Crosswalk应用。

1.  [安装Node.js](#Install-Nodejs)
2.  [安装WiX工具集](#Install-WiX-Toolset)
3.  [安装crosswalk-app-tools](#Install-crosswalk-app-tools)
4.  [验证你的环境](#Verify-your-environment)

## <a class="doc-anchor" id="Install-Nodejs"></a>安装Node.js
1.  从https://nodejs.org/en/上下载node.js。
2.  当开始安装时，确定在路径中添加Node.js，如下所示：
<br><br>
<img src="/assets/win1-nodejs-setup.png" style="display: block; margin: 0 auto"/>

## <a class="doc-anchor" id="Install-WiX-Toolset"></a>安装WiX工具集
1.  下载WiX工具集：http://wixtoolset.org/。
2.  安装WiX工具集。
3.  在你的路径中添加Wix工具集。编辑你账号中的环境变量。在Windows的开始菜单中，检索"Environment variables"。或者，点击控制面板中的系统图标；然后进入Advanced系统设置并且编辑你的用户环境变量。你可以看到下面的对话框：
<br><br>
<img src="/assets/win2-envvars.png" style="display: block; margin: 0 auto"/>

如下编辑`Path`环境变量：

   <ol type='a'>
    <li>选取*Path*环境变量（在选取框的顶部，*User variables...*）。如果它不存在，添加它。</li>
    <li>点击*Edit*。</li>
    <li>在*Variable value*域，为每个已经安装的工具添加指向其*可执行文件*的路径，以";"分割；例如，我们添加下文到Path变量的最后：<br>
       `;C:\Program Files (x86)\WiX Toolset v3.10\bin`
    </li>
    <li>点击*OK*.</li>
   </ol>

同样，这个任务也可以在命令行下完成（注意引号""）：
```
> setx path "%path%;C:\Program Files (x86)\WiX Toolset v3.10\bin"
```

## <a class="doc-anchor" id="Install-crosswalk-app-tools"></a>安装crosswalk-app-tools
Crosswalk-app-tools是一个简单的基于NPM，为你的Crosswalk应用创建可安装包的工具。

在一个命令行shell中输入如下命令（例如使用cmd.exe）：

```
> npm install -g crosswalk-app-tools
```

注意：如果你在代理后开发，请参见[这个页面](/documentation/npm-proxy-setup_zh.html)。

## <a class="doc-anchor" id="Verify-your-environment"></a>验证你的环境
通过运行下列命令确认你是否已经正确地安装了工具：

```
> crosswalk-app check windows
  + Checking host setup for target windows
  + Checking for candle... ...ram Files (x86)\WiX Toolset v3.10\bin\candle.exe
  + Checking for light... ...ogram Files (x86)\WiX Toolset v3.10\bin\light.exe
```

恭喜，你的系统已经可以用于Windows环境下的Crosswalk开发了。

现在相关环境已经创建，你可以编译一个Crosswalk应用了。
