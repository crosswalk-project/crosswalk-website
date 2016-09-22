# Prerequisites

This page lists some initial setup that must be done on all platforms before being able to check out and build Crosswalk.

## Proxy Setup

Crosswalk needs to be able to fetch code and tools from GitHub and Google's
servers. If you are behind a network proxy, you must make sure your environment
is properly set up and the appropriate variables are set.

### Linux

1. Set the `http_proxy` and `https_proxy` environment variables.

    ```
    export http_proxy=http://example-host:port
    export https_proxy=http://example-host:port
    ```

1. Create a Boto configuration file somewhere with the following contents:

    ```
    [Boto]
    proxy = <example-host>
    proxy_port = <port-number>
    ```

    After that, point the `NO_AUTH_BOTO_CONFIG` environment variable to the file
    you created:

    ```
    export NO_AUTH_BOTO_CONFIG=/path/to/boto-file
    ```

### Windows

The proxy setup on Windows is a bit more intricate and require some additional
steps compared to Linux.

Any environment variable described below can be set in two different ways:

1. Using the CMD prompt.

   ```
   # Set the value only for the duration of this CMD session.
   setx myvar=value
   # Set the value for all future sessions, but NOT this one.
   setx myvar value
   ```

1. Using Windows' GUI.

   In the Windows Start menu, search for "Environment variables".
   Alternatively, click on the System icon in the Control Panel; then go to
   Advanced system settings and click the Environment Variables button. You
   should see this dialog box:

   ![environment settings](/assets/win8.png)

   You can then set all the environment variables you need. Remember existing
   command prompt sessions will NOT be able to see the new environment
   variables.

#### Proxy variables

1. Set the `http_proxy` and `https_proxy` environment variables by following
   the steps described above.

1. Create a Boto configuration file somewhere with the following contents:

    ```
    [Boto]
    proxy = <example-host>
    proxy_port = <port-number>
    ```

    After that, point the `NO_AUTH_BOTO_CONFIG` environment variable to the file
    you created by following the steps described above.

#### Windows proxy settings

Also known as "Internet Explorer proxy settings". If you are already able to
browse the internet using IE, there's nothing to do.

Otherwise, you need to launch IE and go to Settings, Internet options,
Connections, LAN and set everything up accordingly.

## depot_tools bootstrap

`depot_tools` is responsible for installing build dependencies (such as git and
Python on Windows), tools such as ninja and managing source code.

The instructions for downloading depot_tools are available
[here](https://commondatastorage.googleapis.com/chrome-infra-docs/flat/depot_tools/docs/html/depot_tools_tutorial.html#_setting_up).
In particular, remember to adjust your `PATH` environment variable accordingly
and, on Windows, make sure git and Python are correctly downloaded.
