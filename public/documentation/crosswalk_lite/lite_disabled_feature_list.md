# Crosswalk Lite Features

Crosswalk Lite disables features to reduce the library size. Before using Crosswalk Lite, please ensure that your application does not have a dependency on a disabled feature. You can also contact us for a release which is built with customized flags or use the full Crosswalk build.

This is the list used for Crosswalk Lite v17 (previous build version was based on Crosswalk 10)

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
| Web Bluetooth                       | Disabled                       |  20K   |
| Web Database                        | Disabled                       |  38K   |
| Web MIDI                            | Disabled                       |  26K   |
| Plugin                              | Disabled                       |  440K  |

### Known issue
ICU: with this feature, some languages (e.g. Hindi) needs complexShaper and might not be displayed correctly. Some Text operation by JS such as `Range` and `Selections` are not well supported.

For Crosswalk-Lite-10, refer to the legacy document ([Crosswalk Lite-10 Disabled feature list](/documentation/crosswalk_lite/lite_10_disabled_feature_list.html))
