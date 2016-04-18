### Use Crosswalk-app-tools
  Crosswalk-app-tools is the packaging tool for creating Crosswalk applications, the old packaging tool(make-apk.py) will be deprecated. Refer to [Usage of Crosswalk-app-tools](https://github.com/crosswalk-project/crosswalk-app-tools/) for more information.

  Lite is supported in Crosswalk-app-tools. For example, you can run: 

```
$ crosswalk-pkg --android="lite" <your webapp path>
```

  Then Crosswalk-app-tools downloads Lite engine automatically in background, and finish the packaging work.


### Download Crosswalk-Lite Manually
  Alternatively you can manually download Lite engine([Download URL](https://download.01.org/crosswalk/releases/crosswalk-lite/android/canary/latest/)) into your local machine, then run:

```
$ crosswalk-pkg --android="lite" --crosswalk=<downloads path>/crosswalk-17.46.455.1.zip <your webapp path> 
```

  All history releases can be found in parent directory.

  Lite works the same way as the "embeded mode" of Crosswalk-Normal. Find more information in https://crosswalk-project.org/documentation/getting_started.html.


### Embedding Crosswalk-Lite into your project(Hybrid developing)
*  Developers can embed Crosswalk-Lite into their Android projects. There is no difference with Crosswalk-Normal in how to embed Lite into your project(the hybrid developing way) except the `onXWalkReady()`. You can find more information in [Embedding the Crosswalk Project](https://crosswalk-project.org/documentation/android/embedding_crosswalk.html) and [Developing with Crosswalk AAR](https://crosswalk-project.org/documentation/android/embedding_crosswalk/crosswalk_aar.html).

*  Since Lite-17, we added the maven server for Crosswalk-Lite. So we have 2 maven URL seperately for Crosswalk-Normal and Crosswalk-Lite:
     https://download.01.org/crosswalk/releases/crosswalk/android/maven2/ and https://download.01.org/crosswalk/releases/crosswalk-lite/android/maven2/

  Make sure the maven url in your project is for Crosswalk-Lite instead of Crosswalk-Normal.

*  The latest Lite release file can be found in:<br/>
     https://download.01.org/crosswalk/releases/crosswalk-lite/android/canary/latest/
  
  
### New API `org.xwalk.core.XWalkActivity.onXWalkReady()`
  * This API is introduced to check whether the Crosswalk environment is available to use. Crosswalk environment is not available under two scenarios:
    * Shared mode: Need download from app store like Google Play
    * Crosswalk-Lite: The libxwalkcore.so is compressed with lzma, need to decompress it first.
  * Changes needed to adapt to this new API:
    * `XWalkView` initialization code moved from `onCreate()` to `onXWalkReady()`.
    * Activity should inherit from `org.xwalk.core.XWalkActivity` instead of `android.app.Activity`.
    * In `AndroidManifest.xml`, `android:name` should change to `org.xwalk.core.XWalkApplication`.
    * Example: 
            import org.xwalk.core.XWalkActivity;
            import org.xwalk.core.XWalkResourceClient;
            import org.xwalk.core.XWalkUIClient;
            import org.xwalk.core.XWalkView;

            public class MainActivity extends XWalkActivity {
                XWalkView mXWalkView;

                @Override
                protected void onCreate(Bundle savedInstanceState) {
                    super.onCreate(savedInstanceState);
                    setContentView(R.layout.activity_main);
                    //Don't initXWalkView here!
                }

                public void initXWalkView()
                {
                    mXWalkView = new XWalkView(this);
                    RelativeLayout Ll = (RelativeLayout) findViewById(R.id.layout1);
                    Ll.addView(mXWalkView);

                    mXWalkView.setUIClient(new XWalkUIClient(mXWalkView));
                    mXWalkView.setResourceClient(new XWalkResourceClient(mXWalkView));
    
                    mXWalkView.load("http:///xxxx/test.html", null);
                }
    
                @Override
                protected void onXWalkReady() {
                    //initXWalkView in onXWalkReady().
                    initXWalkView();
                }
            }

### App Launched
  The first time the application runs, the following UI dialog is displayed:
  <p><img src='https://crosswalk-project.org/assets/crosswalk-lite-uncompress-dialog.png'></p>
  It won't pop out anymore after the initialization finished successfully.
  


