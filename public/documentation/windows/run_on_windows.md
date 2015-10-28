# Run a Crosswalk App on Windows

## Run using Crosswalk packaging tool

1. Download latest Crosswalk binary from: https://download.01.org/crosswalk/releases/crosswalk/windows/

   (Note that this step will not be required when Crosswalk For Windows binaries are published on download.01.org)

2. In your command line, inside the directory of the application you want to build run:

   ```
    > crosswalk-pkg \
        --platforms=windows \
        --crosswalk=C:\Users\alexisme\Downloads\crosswalk-15.44.374.0.zip \
        xwalk-simple
   ```

   This will package the application defined in the specified manifest.json file and produce an .msi (in our example `com.app.simple-0.1.0.0.msi`). The .msi is currently a 32-bit build of Crosswalk for Windows and should run fine on both 32-bit and 64-bit Windows.

3. Click on the .msi and follow the installation instructions.
4. After the installation successfully finishes you should be able to see your application in the start menu. Click on it and launch the application.

   Note : Each .msi of Crosswalk applications contains its own copy of the Crosswalk runtime, it is not shared between installed Crosswalk based applications.

## <a class='doc-anchor' id='Run-using-Crosswalk-binary-directly'></a>Run using Crosswalk binary directly

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
