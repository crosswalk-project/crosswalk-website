<!DOCTYPE html>
<html>
  <body>
    <h1>Downloads</h1>

    <p>The Crosswalk project provides binaries for multiple
    operating systems and platforms.</p>

    <p>For instructions on how to use these downloads, see the
    <a href="#documentation/getting_started">Getting started</a> pages.</p>

    <p>For more information about the release channels, see
    <a href="#Release-channels">the <em>Release channels</em> section</a>.</p>

    <table class="downloads-table">

    <tr>
    <th>&nbsp;</th>
    <th>Stable</th>
    <th>Beta</th>
    <th>Canary</th>
    </tr>

    <tr>
    <th>Android (x86)</th>
    <td data-role="download-cell" data-loading="true">
    <a data-role="download-link" data-channel="stable" data-os="android-x86"></a>
    </td>
    <td data-role="download-cell" data-loading="true">
    <a data-role="download-link" data-channel="beta" data-os="android-x86"></a>
    </td>
    <td data-role="download-cell" data-loading="true">
    <a data-role="download-link" data-channel="canary" data-os="android-x86"></a>
    </td>
    </tr>

    <tr>
    <th>Android (ARM)</th>
    <td>-</td>
    <td data-role="download-cell" data-loading="true">
    <a data-role="download-link" data-channel="beta" data-os="android-arm"></a>
    </td>
    <td data-role="download-cell" data-loading="true">
    <a data-role="download-link" data-channel="canary" data-os="android-arm"></a>
    </td>
    </tr>

    <tr>
    <th>Tizen 2.x Mobile (x86)</th>
    <td data-role="download-cell" data-loading="true">
    <a data-role="download-link" data-channel="stable" data-os="tizen-mobile"></a>
    </td>
    <td data-role="download-cell" data-loading="true">
    <a data-role="download-link" data-channel="beta" data-os="tizen-mobile"></a>
    </td>
    <td>-</td>
    </tr>

    <tr>
    <th>Tizen 2.x Emulator (x86)</th>
    <td data-role="download-cell" data-loading="true">
    <a data-role="download-link" data-channel="stable" data-os="tizen-emulator"></a>
    </td>
    <td data-role="download-cell" data-loading="true">
    <a data-role="download-link" data-channel="beta" data-os="tizen-emulator"></a>
    </td>
    <td>-</td>
    </tr>

    <tr>
    <th>Tizen 3.0 Mobile (x86)</th>
    <td>-</td>
    <td>-</td>
    <td data-role="download-cell" data-loading="true">
    <a data-role="download-link" data-channel="canary" data-os="tizen-mobile"></a>
    </td>
    </tr>

    <tr>
    <th>Tizen 3.0 IVI (x86)</th>
    <td>-</td>
    <td>-</td>
    <td data-role="download-cell" data-loading="true">
    <a data-role="download-link" data-channel="canary" data-os="tizen-ivi"></a>
    </td>
    </tr>

    <tr class="downloads-row release-notes-row">
    <th class="release-notes-heading">Release notes</th>
    <td data-role="download-cell" data-loading="true">
    <a data-role="release-notes-link" data-channel="stable"></a>
    </td>
    <td data-role="download-cell" data-loading="true">
    <a data-role="release-notes-link" data-channel="beta"></a>
    </td>
    <td>*</td>
    </tr>

    </table>

    <p>* - note that release notes are only produced for stable and beta
    versions.</p>

    <p><a href="https://download.01.org/crosswalk/releases/">More downloads...</a></p>

    <h2>Release channels</h2>

    <p>There are three release channels (in order of increasing
    instability):</p>

    <ol>
      <li>
        <p><strong>Stable</strong></p>

        <p>A Stable release is a release intended for end users. Once a
        Crosswalk release is promoted to the Stable channel, that
        release will only see new binaries for critical bugs and
        security issues.</p>
      </li>

      <li>
        <p><strong>Beta</strong></p>

        <p>A Beta release is intended primarily for application
        developers looking to test their application against
        any new changes to Crosswalk, or use new features due
        to land in the next Stable release. Beta builds are
        published based on automated basic acceptance tests (ABAT),
        manual testing results, and functionality changes. There is
        an expected level of stability with Beta releases; however,
        it is still Beta, and may contain significant bugs.</p>
      </li>

      <li>
        <p><strong>Canary</strong></p>

        <p>The Canary release is generated on a frequent basis
        (sometimes daily). It is based on a recent tip of master that
        passes a full build and automatic basic acceptance test. The
        Canary build is an easy option for developers who are interested
        in the absolute latest features, but don't want to build
        Crosswalk themselves.</p>
      </li>
    </ul>

    <p>More information is available on the
    <a href="#wiki/Release-methodology">Release Channels page</a>.</p>

    <p>The <a href="#contribute/channels-viewer">Channel Viewer</a>
    shows detailed information about the status of each release channel.</p>

    <p>The <a href="#wiki/release-methodology/version-numbers">Crosswalk
    version numbers page</a> describes how Crosswalk versions are assigned.</p>

    <script>
    // populate the download links and release notes links in the table
    var sel = 'td[data-role="download-cell"]'
    var dlCells = document.querySelectorAll(sel);

    // these are release note links; to avoid making unnecessary calls
    // to github, we populate these when we retrieve the versions for
    // the stable and beta channels
    sel = 'a[data-role="release-notes-link"]';
    var releaseNotesLinks = document.querySelectorAll(sel);

    // make a callback to populate a download link once the version has
    // been retrieved; note that (for the sake of efficiency) we also
    // check through the release note links and populate those at the
    // same time, if the channel of the release note link matches the
    // channel download link we are populating (to avoid an extra fetch
    // from the github API)
    var makeCb = function (cell, link, channel) {
      var os = link.getAttribute('data-os');

      return function (err, response) {
          // remove the spinner
          cell.setAttribute('data-loading', 'false');

          if (err) {
              link.innerHTML = 'github error';
              console.error(err);
              return;
          }

          // get the URL for the download
          var url = getXwalkDownloadUrl(os, channel, response.version);

          // set the URL and text for the download link
          link.setAttribute('href', url);
          link.innerHTML = response.version;

          // fill in any of the release notes links which apply
          // for the downloaded version
          for (var j = 0; j < releaseNotesLinks.length; j += 1) {
              var rnLink = releaseNotesLinks.item(j);

              if (rnLink.getAttribute('data-channel') === channel) {
                  var majorVersion = getXwalkMajorVersion(response.version);
                  rnLink.setAttribute('href', getReleaseNotesUrl(majorVersion));
                  rnLink.innerHTML = 'Crosswalk-' + majorVersion;

                  // if the parent node has data-loading="true", remove it
                  // to turn off the spinner
                  if (rnLink.parentNode.getAttribute('data-loading') === 'true') {
                      rnLink.parentNode.setAttribute('data-loading', 'false');
                  }
              }
          }
      }
    };

    // get the current branches
    asyncJsonGet('./github.php', function (err, branches) {
        var j;

        // clear spinners on release note links on error
        if (err) {
            for (j = 0; j < releaseNotesLinks.length; j += 1) {
                var rnLink = releaseNotesLinks.item(j);
                rnLink.innerHTML = 'github error';
                rnLink.parentNode.setAttribute('data-loading', 'false');
            }
        }

        // populate download links
        for (j = 0; j < dlCells.length; j += 1) {
            var cell = dlCells.item(j);
            var link = cell.querySelector('a[data-role="download-link"]');

            // if this cell has no download link, ignore it
            // (it's a release notes cell)
            if (!link) {
                continue;
            }
            // if branches could not be retrieved, fill all cells with
            // an error message
            else if (err) {
                link.innerHTML = 'github error';
                cell.setAttribute('data-loading', 'false');
            }
            // branches retrieved OK
            else {
                var channel = link.getAttribute('data-channel');

                // get the branch for the channel
                var branch;
                for (var i = 0; i < branches.length; i += 1) {
                    if (branches[i].channel === channel) {
                        branch = branches[i].branch;
                        break;
                    }
                }

                // get the version for the branch; we pass a callback
                // to the async JSON download, which fills the link text
                // and URL when the JSON is returned
                var path = './github.php?fetch=version&branch=' + branch;
                asyncJsonGet(path, makeCb(cell, link, channel));
            }
        }
    });
    </script>
  </body>
</html>
