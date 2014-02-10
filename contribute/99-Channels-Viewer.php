<!DOCTYPE html>
<html>
<head>
</head>
    <h1> Crosswalk Channels Viewer</h1>
    <table id="table" style="font-size:11px; width: 100%;">
        <thead id="columns" style="display:none">
            <tr>
                <td style="padding:0; text-align:center;"> OS </td>
                <td style="padding:0; text-align:center;"> Channel </td>
                <td style="padding:0; text-align:center;"> Git Branch </td>
                <td style="padding:0; text-align:center;"> Latest Commit </td>
                <td style="padding:0; text-align:center;"> Version </td>
                <td style="padding:0; text-align:center;"> Chromium Version </td>
                <td style="padding:0; text-align:center;"> Chromium Crosswalk SHA </td>
                <td style="padding:0; text-align:center;"> Blink Crosswalk SHA </td>
            </tr>
        </thead>
        <tbody id="rows">
            <tr><td colspan="8" style="text-align:center" id="message"><img src="assets/loading.gif"/></td></tr>
        </tbody>
    </table>
    <script>
    var showError = function (err) {
        var columns = document.getElementById("columns");
        columns.style.display = "none";
        var message = document.getElementById("message");
        message.style.display = "table-cell";
        message.innerHTML = "Couldn't fetch the content because of the " +
                            "following error : " + err.message;
    };

    var rowTemplate = '' +
      // operating system
      '<td class="nowrap">{OS}</td>' +

      // channel
      '<td>{channel}</td>' +

      // branch
      '<td class="nowrap">{branch}</td>' +

      // crosswalk commit
      '<td><a href="{crosswalk_commit_url}" ' +
      'target="_blank">{sha}</a></td>' +

      // version with download link
      '<td><a target="_blank" href="{download_url}">' +
      '{version}</a></td>' +

      // chromium version
      '<td>{chromium_version}</td>' +

      // chromium commit
      '<td><a href="{chromium_commit_url}" ' +
      'target="_blank">{chromium_sha}</a></td>' +

      // blink commit
      '<td><a href="{blink_commit_url}" ' +
      'target="_blank">{blink_sha}</a></td>';

    // insert one row in the table for a particular OS
    var insertOSRow = function (OS, channel, branch, sha, version,
    chromium_version, chromium_sha, blink_sha) {
        var download_url = 'https://download.01.org/crosswalk/releases/' +
                           OS + "/" + channel + "/crosswalk-" + version;

        if (OS === "android-x86") {
            download_url += "-x86.zip";
        }
        else {
            download_url += "-0.i586.rpm";
        }

        var chromium_commit_url = 'https://github.com/crosswalk-project/' +
                                  'chromium-crosswalk/commit/' + chromium_sha;
        var crosswalk_commit_url = 'https://github.com/crosswalk-project/' +
                                   'crosswalk/commit/' + sha;
        var blink_commit_url = 'https://github.com/crosswalk-project/' +
                               'blink-crosswalk/commit/' + blink_sha

        var data = {
            OS: OS,
            channel: channel,
            branch: branch,
            sha: sha,
            version: version,
            chromium_version: chromium_version,
            chromium_sha: chromium_sha,
            blink_sha: blink_sha,
            download_url: download_url,
            chromium_commit_url: chromium_commit_url,
            crosswalk_commit_url: crosswalk_commit_url,
            blink_commit_url: blink_commit_url
        };

        var table = document.getElementById("rows");
        var rowCount = table.rows.length;
        var row = table.insertRow(rowCount);

        // generate the row HTML content
        row.innerHTML = tpl(rowTemplate, data);

        if (rowCount == 6) {
            var message = document.getElementById("message");
            message.style.display = "none";
            var columns = document.getElementById("columns");
            columns.style.display = "table-header-group";
        }
    };

    var createRows = function (channel, branch, sha, version, chromium_version, chromium_sha, blink_sha) {
        insertOSRow("android-x86", channel, branch, sha, version, chromium_version, chromium_sha, blink_sha);
        insertOSRow("tizen-mobile", channel, branch, sha, version, chromium_version, chromium_sha, blink_sha);
    };

    // do ajax requests to the github proxy on this server to
    // get the DEPS.xwalk and VERSION file for a branch
    var fetchRowContent = function (branch, channel, sha) {
        var version;
        var chromium_version;
        var chromium_sha;
        var blink_sha;

        var onDone = function () {
          createRows(channel, branch, sha, version, chromium_version, chromium_sha, blink_sha);
        };

        // get VERSION
        var url = './github.php?fetch=version&branch=' + branch;
        asyncJsonGet(url, function (err, contents) {
          if (err != null) {
              showError(err);
          }
          else {
              version = contents.version;

              if (chromium_version != undefined) {
                onDone();
              }
          }
        });

        // get DEPS.xwalk
        url = './github.php?fetch=deps&branch=' + branch;
        asyncJsonGet(url, function (err, contents) {
            if (err != null) {
                showError(err);
            }
            else {
                chromium_version = contents.chromiumVersion;
                chromium_sha = contents.chromiumSha;
                blink_sha = contents.blinkSha;

                if (version != undefined) {
                    onDone();
                }
            }
        });
    };

    // get list of branches
    asyncJsonGet('./github.php', function (err, branches) {
        for (var i = 0; i < branches.length; i += 1) {
            fetchRowContent(
                branches[i].branch,
                branches[i].channel,
                branches[i].sha
            );
        }
    });
    </script>
</html>
