# Windows Host Setup

These instructions have been tested on Windows 8 Enterprise, 64 bit. This should work also in regular Home versions of Windows as well as the newer Windows 10.

The steps below will enable you to develop Crosswalk applications that run on Windows.

1.  [Install Node.js](#Install-Nodejs)
2.  [Install WiX Toolset](#Install-WiX-Toolset)
3.  [Install crosswalk-app-tools](#Install-crosswalk-app-tools)
4.  [Verify your environment](#Verify-your-environment)

## <a class="doc-anchor" id="Install-Nodejs"></a>Install Node.js
1.  Download Node.js from https://nodejs.org/en/.
2.  When the installer starts, make sure to add Node.js in the PATH as shown below:
<br><br>
<img src="/assets/win1-nodejs-setup.png" style="display: block; margin: 0 auto"/>

## <a class="doc-anchor" id="Install-WiX-Toolset"></a>Install WiX Toolset
1.  Download WiX Toolset:  http://wixtoolset.org/
2.  Install WiX Toolset.
3.  Add the Wix Toolset in your PATH. Edit environment variables for your account. In the Windows Start menu, search for "Environment variables". Alternatively, click on the System icon in the Control Panel; then go to Advanced system settings and click the Environment Variables button. You should see this dialog box:
<br><br>
<img src="/assets/win2-envvars.png" style="display: block; margin: 0 auto"/>

Edit the `Path` environment variable as follows:

   <ol type='a'>
    <li>Select the *Path* environment variable (in the top select box, *User variables...*).  If it does not exist, add it.</li>
    <li>Click *Edit*.</li>
    <li>In the *Variable value* field, add the path to *the executable* for each of the installed tools, separated with ";".  For this example, we added the following at the end of the Path variable:<br>
       `;C:\Program Files (x86)\WiX Toolset v3.10\bin`
    </li>
    <li>Click *OK*.</li>
   </ol>

The same task can be done on the command-line (note the quotes ""):
```
> setx path "%path%;C:\Program Files (x86)\WiX Toolset v3.10\bin"
```

## <a class="doc-anchor" id="Install-crosswalk-app-tools"></a>Install crosswalk-app-tools
It is the tool which will create installable packages of your Crosswalk application.

In the command line (using cmd.exe for example) type :

```
> npm install -g https://github.com/crosswalk-project/crosswalk-app-tools.git
```

Note: the version of crosswalk-app-tools currently available in the NPM package repository doesn't yet support windows.

## <a class="doc-anchor" id="Verify-your-environment"></a>Verify your environment
Check that you have installed the tools properly by running these commands:

```
> crosswalk-app check windows
  + Checking host setup for target windows
  + Checking for candle... ...ram Files (x86)\WiX Toolset v3.10\bin\candle.exe
  + Checking for light... ...ogram Files (x86)\WiX Toolset v3.10\bin\light.exe
```

Congratulations, your system is ready for Windows development with Crosswalk.

Now the host is set up, you can build a Crosswalk application.
