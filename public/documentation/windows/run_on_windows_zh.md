# 在Windows上运行一个Crosswalk应用

## 运行一个打包的Crosswalk应用

1. 双击一个在[编译一个应用](/documentation/windows/build_an_application_zh.html)部分生成的.mst文件，按照提示步骤安装。
2. 在安装成功后，你将在启动栏中看到你的应用。双击启动它。

   注意：每个打包的Crosswalk-built.msi应用均包含一份它自己的Crosswalk运行时环境的拷贝，它在已安装的基于Crosswalk的应用间并不共享。

## <a class='doc-anchor' id='Run-using-Crosswalk-binary-directly_zh'></a>直接运行Crosswalk二进制文件

如果你想加速编码->测试->调试循环，你可以从命令行运行针对Windows的Crosswalk。这样可以保证你每次不必创建一个.msi即可测试你修改的代码。

1.  从https://download.01.org/crosswalk/releases/crosswalk/windows/上下载并解压针对windows平台的Crosswalk。
2.  打开一个控制台(cmd.exe)
3.  导航到你的项目的index.html
4.  运行下列的python脚本。这个将创建一个供Crosswalk使用的本地webserver。我们不支持从文件系统中加载。

   ```
   > python -m SimpleHTTPServer 8000;
   ```

5. 在另一个终端中，导航到针对Windows平台的Crosswalk解压的地方并且运行：

   ```
   > xwalk.exe http://localhost:8000
   ```
   你的index.html应该加载。
   
   或者你可以调用：
   ```
   > xwalk.exe "C:\Users\path\to\my\crosswalk\app\manifest.json"
   ```
   这样将不会使用本地的webserver来服务你应用的文件系统，而是读取manifest。
