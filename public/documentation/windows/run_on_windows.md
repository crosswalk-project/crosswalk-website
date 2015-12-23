# Run a Crosswalk App on Windows

## Run a packaged Crosswalk application

1. Double-click on the .msi produced following the [build an application](/documentation/windows/build_an_application.html) section and follow the installation instructions.
2. After the installation succeeds you will see your application in the start menu. Double-click it to launch it.

   Note : Each Crosswalk-built .msi contains its own copy of the Crosswalk runtime, it is not shared between installed Crosswalk based applications.

## <a class='doc-anchor' id='Run-using-Crosswalk-binary-directly'></a>Run using the Crosswalk binary directly

If you want faster code -> test -> debug cycles you can run Crosswalk for Windows from the command line. This enables you to test your code changes without creating an .msi every time.

1.  Download and unzip Crosswalk for Windows from https://download.01.org/crosswalk/releases/crosswalk/windows/
2.  Open a console (cmd.exe)
3.  Navigate to your project index.html
4.  Run the python script below. This creates a local webserver that Crosswalk uses.  We do not support loading from the filesystem.

   ```
   > python -m SimpleHTTPServer 8000;
   ```

5. In another console, navigate to where Crosswalk for Windows was unzipped and run:

   ```
   > xwalk.exe http://localhost:8000
   ```
   Your index.html should load.
   
   Alternatively you can also call :
   ```
   > xwalk.exe "C:\Users\path\to\my\crosswalk\app\manifest.json"
   ```
   This will not use the local webserver to serve the files of your application but rather will read the manifest.
