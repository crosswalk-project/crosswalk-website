Crosswalk-Lite disable some features on Crosswalk-Normal for size reducing purpose.
We suggest you check whether or not your app has dependency on some of the disabled features. You can contact us for release which is built with customized flags, or turn to Crosswalk-Normal build.
  

| Feature                             | Description                    | Reduced size (arm) |
| ----------------------------------- |:------------------------------:|:-------------------|
| ICU                                 | Replace with Java Impl         |  3.5M  |
| Minimum_Resources                   | Remove useless resources       |  212K  |
| use_optimize_for_size_compile_option| Change building some flags     |  677K  |
| LZMA compressing                    | compress lib by default        |  6M    |
| WebRTC                              | Disabled                       |  1832K |
| XSLT                                | Disabled                       |  92K   |
| WebP                                | Disabled                       |  96K   |
| ANGLE                               | Disabled                       |  140K  |
| QUIC protocol                       | Disabled                       |  176K  |
| Sync Compositor                     | Disabled                       |  28K   |
| Built-in Extensions                 | Disabled                       |  40K   |
| Devtools                            | Enabled                        |  424K  |
| Web Video                           | Enabled                        |  180K  |
| Web Audio                           | Enabled                        |  300K  |
| Speech                              | Disabled                       |  120K  |
| Web Notifications                   | Disabled                       |  68K   |
| Web CL                              | Disabled                       |  44K   |
| Indexed DB                          | Disabled                       |  230K  |
| Web Accessibility                   | Disabled                       |  72K   |
| Geolocation & Geofencing            | Disabled                       |  26K   |
| Web Bluetooth                       | Disabled                       |  20K   |
| Web Database                        | Disabled                       |  38K   |
| Web MIDI                            | Disabled                       |  26K   |
| Plugin                              | Disabled                       |  440K  |

Known issue:<br/>
ICU: with this feature, some languages(Hindi for example) needs complexShaper might not be displayed correctly. Some Text operation by JS such like "Range" "Selections" are not well supported.

For Crosswalk-Lite-10, refer to the legacy document ([Crosswalk Lite-10 Disabled feature list](/documentation/crosswalk_lite/lite_10_disabled_feature_list.html))
