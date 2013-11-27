## What Are Assets?

Bonfire considers any CSS files, javascript files, and many image files to be Assets. While we understand that projects will often contain Flash files or other types of files that would be considered 'assets', Bonfire only accounts for these three types of files but does provide some helper methods to assist with other types of files.

Assets files can be stored in an application-wide repository, in your theme, or with each module. Because of the possibility of some of these files not being web-accessible, module-related assets are always combined and cached in a web-accessible folder.

Assets can be located in one of several locations:

    /bonfire/modules/my_module/assets
    /bonfire/themes/active_theme/assets
    /bonfire/themes/default_theme/assets
    /assets


Whenever you use one of the asset rendering methods, like <tt>css()</tt>, <tt>js()</tt>, and <tt>image()</tt>, it will search in each of those folders (as well as a folder named by the asset type under those folders, ie. 'css') in the order listed above. Note that it only looks within the module if it was added via <tt>add_module_css()</tt> or <tt>add_module_js()</tt>.

<a name="asset-base"></a>
### Customizing Asset Base Folder

If you need to, you can customize the folders that your assets are located in via settings in the <tt>bonfire/application/config/application.php</tt> configuration file.

To change the primary folder that all of your assets are located in, you can set the <tt>assets.base_folder</tt> setting. Note that this is not recommended as it may cause instability in the application. _Testing has not been done to ensure that this is a viable option at the moment._


    $config['assets.base_folder'] = 'assets';


<a name="asset-folders"></a>
### Customizing Asset Folders

Within the base folder (above), each asset is organized by asset type. By default, the folders are named <tt>js</tt>, <tt>css</tt>, and <tt>images</tt>. This can be changed in the application config file. Again, though this has not been tested to ensure this works. Changing this value might render your site unusable.


    $config['assets.asset_folders'] = array(
        'css'   => 'css',
        'js'    => 'js',
        'image' => 'images'
    );

<a name="css-main"></a>
## Working With CSS

Stylesheets can be hosted with the application or on other servers. However, when it comes time to tweak your site's front-end performance, only those files that are hosted locally with the site can be combined and minified successfully. It is recommended that you always use the raw stylesheets (not minified) to take full advantage of the minification abilities, as minifying a CSS file twice might corrupt the styles.

<a name="css"></a>
### <tt>css()</tt>

This method provides a simple way to render all of your CSS links in one method call. It can be used in two different fashions: to output a single link tag, or to output link tags to all stylesheets that it knows about.

Whenever a relative path is provided, it is assumed to be relative to either the main folder being searched in (ie. /assets) or the type-specific folder (ie. /assets/css).

When called with no parameters, the <tt>css()</tt> method will create links for all stylesheets that have been added to it via either the <tt>add_css()</tt> or <tt>add_module_css()</tt> methods, below.


    Assets::css();

    // Creates:
    <link rel="stylesheet" type="text/css" href="http://mysite.com/themes/default/screen.css" media="screen" />


**Media Type**

You can specify the media type for your CSS to render, by passing it as the second parameter. The default value is 'screen'.


    Assets::css(null, 'screen');


**Bypassing Inheritance**

Bonfire's Assets system is designed with a Parent/Child theme framework in mind. That means that you can quickly build a new theme that inherits all of a default (or parent) theme and only changing the files that need to be changed. This applies to both assets and layout files.

If you're using a theme that you've created called 'darthvader', it will look for files of the same name in both the _darthvader_ theme and the _default_ theme. If you do not want it to look for and include these files, you can pass in TRUE as the third parameter.


    Assets::css(null, 'screen', TRUE);


**Global Files**

A couple of different files will automatically appear within your styles, without you needing to do any work.

The Assets library will search for a file within your theme for a file named the same as the media that you're currently rendering. By default, this is the <tt>bonfire/themes/my_theme/screen.css</tt> file.

It will also look for any stylesheets within your theme that match the name of the controller that is active. So, if the **blog** controller is currently running, it will look for a file at <tt>bonfire/themes/my_theme/blog.css</tt>.

**Rendering a Single File**

When you just want to output a link to a single CSS file, you can use the <tt>css()</tt> method with a single parameter that is the name of the file to link to. The link can be either relative or absolute.


    echo Assets::css('http://mysite.com/path/to/my.css');
    // Output: <link rel="stylesheet" type="text/css" href="http://mysite.com/path/to/my.css" media="screen" />

    echo Assets::css('/path/to/my.css');
    // Output: <link rel="stylesheet" type="text/css" href="http://mysite.com/path/to/my.css" media="screen" />


**Rendering Multiple Custom Files**

If you want to render multiple links to CSS files, you can pass an array of stylesheets. These stylesheets will be merged with any styles that have been previously added via either <tt>add_css()</tt> or <tt>add_module_css()</tt>.


    $styles = array(
        'bootstrap.css',
        'markitup/sets/minimal/styles'
    );

    Assets::css($styles);


<a name="add-css"></a>
### <tt>add_css()</tt>

Adds additional file(s) to the links that are output with the <tt>css()</tt> method. The first parameters is the name of the file to be linked to. It should be relative to one of the paths described above. The second, optional, parameter is the media type. This defaults to 'screen'.


    Assets::add_css('blog.css');

<a name="module-css"></a>
### 2.3 <tt>add_module_css()</tt>

Adds a stylesheet that is part of a module to the styles to be output with the <tt>css()</tt> method. The first parameter is the name of the module. The second is the name of the file to be linked to. The third, optional, parameter is the media type this file belongs to. It defaults to 'screen'.


    Assets::add_module_css('blog', 'posts.css');


Because module files are often inaccessible via a web browser, all module files are combined and cached in the <tt>assets/cache</tt> folder. The name of the file is built as follows for the maximum reuse:

    {theme name}_{module name}_{controller name}_mod.css

The above blog/posts.css file would be in a file named <tt>default_blog_blog_mod.css</tt>.

<a name="js-main"></a>
## Working With Javascript

Script files can be hosted with the application or on other servers. However, when it comes time to tweak your site's front-end performance, only those files that are hosted locally with the site can be combined and minified successfully. It is recommended that you always use the raw scripts (not minified) to take full advantage of the minification abilities, as minifying a JS file twice might corrupt the script.

For the sake of the Assets library, javascript files are broken up into 3 types of scripts:

- **External** scripts are javascript files that included with a <tt>&lt;script src=""></script></tt> tag. These will typically be hosted with your site, but might be hosted on another server.
- **Inline** scripts are scripts that are intended to be written out as part of the page's HTML. You may include a file as inline for performance reasons or simply because it only affects a single page on your site and including it in a larger external file does not make sense.
- **Module** scripts are scripts that are part of a module within Bonfire. Because of security reasons, you often will not be to access the module files from the browser so these scripts are always combined and cached in the main <tt>assets/</tt> folder.


<a name="js-js"></a>
### <tt>js()</tt>

This method provides a single call to render all external, module, or inline script tags.

Whenever a relative path is provided, it is assumed to be relative to either the main folder being searched in (ie. /assets) or the type-specific folder (ie. /assets/js).

When called with no parameters, the <tt>js()</tt> method will create links/inline-code for all scripts that it knows about.


    echo Assets::js();


Might produce:
```
{% geshi "Javascript" use_classes %}
<script src="http://bonfire.dev/assets/js/bootstrap.min.js" type="text/javascript" ></script>
<script type="text/javascript">
$(document).ready(function(){
    $(".dropdown-toggle").dropdown();$(".tooltips").tooltip();$(".login-btn").click(function(e){ e.preventDefault(); $("#modal-login").modal(); });
});
</script>
```

Notice that the inline scripts is automatically wrapped with customizable open and close strings. In this case, it produced an appropriate wrapper for use with jQuery. This can be changed in the <tt>application/config/application.php</tt> file.


    $config['assets.js_opener'] = '$(document).ready(function(){'. "\n";
    $config['assets.js_closer'] = '});'. "\n";


**Single Script Tags**

If you pass the name of a script file as the first parameter, then it will output a link to only that file. Note that if it cannot find the script in any of the standard locations, it will not create a link for it.


    echo Assets::js('some_script.js');


**Adding Scripts while Rendering**

You can pass an array of script files as the first parameter of the script and they will be added to the scripts to be linked to. The function will then proceedd as if you had not passed any parameters in, creating links for all external, inline, and module scripts, including the ones that you did actually pass in. An optional second parameter allows you to specify whether the scripts are 'external' or 'inline';


    echo Assets::js( array('script1.js', 'script2.js'), 'inline' );



<a name="external-js"></a>
### <tt>external_js()</tt>

This method creates the links for all external scripts that it knows about. This is called automatically by the <tt>js()</tt> method and does not need to be called again if you are using that method.

You can pass either an array of scripts or a single script as the first parameter and only those script files will have links created for them.

If you are using a JS loader, like [head.js](http://headjs.com/), you might need a list of files instead of links created. You can get this by passing <tt>TRUE</tt> as the second parameter.


    echo Assets::external_js( array('script1.js', 'script2.js'), TRUE );

    // Produces
    "http://mysite.com/assets/js/script1.js", "http://mysite.com/assets/js/script2.js"


By default, the Assets library will ensure that all files have a <tt>.js</tt> file extension. If you have some files have a different extension, you can pass FALSE as the third parameter.



<a name="inline-js"></a>
### <tt>inline_js()</tt>

Handles the inline script creation for the <tt>js()</tt> method. You do not need to call this script by itself when using that method.


<a name="module-js"></a>
### <tt>module_js()</tt>

Returns a link to a combined file that includes any scripts added with the <tt>add_module_js()</tt> method, below. When this method is called, all files are combined into a single file that is cached in the primary <tt>assets/cache</tt> folder.

You can affect how the file is named as well as minification of the new file, via options that are discussed in Section 5, below.


<a name="img-main"></a>
## Working With Images

With mobile/desktop development becoming so prevalent, you often need a simple method to get an image that is only as big as you need it to be, smaller for mobile browsers and larger for desktops. With responsive design, you can fine-tune the bandwidth that your site's images are consuming even further based on the width of the browser window.

Bonfire's image controller provides simple, on-the-fly creation of resized images. This is available at:

    http://mysite.com/images

### Image Size and Cropping

To return a square thumbnail of a large photo, you can choose to let the images library automatically crop the photo for you, while creating a thumbnail image. Pass along a <tt>size</tt> parameter that will define how wide and how tall the image should be.


    <a href="http://mysite.com/images?size=80">My Thumbnail</a>


If you prefer to set a specific height and width, in pixels, for the image, you can use the <tt>height</tt> and <tt>width</tt> params to the url.


    <a href="http://mysite.com/images?height=60&width=100">My Thumbnail</a>

You can also simply pass a <tt>width</tt> or <tt>height</tt> params to intelligently resize the image. Whichever param is passed will determine which is the "master" param. ex. If <tt>height</tt> is passed, the image will resize to a height of 60 and whatever width maintains aspect-ratio.

    <a href="http://mysite.com/images?height=60">
    

By default, Bonfire will scale your image to fit within the new image size. To resize the image and disregard aspect-ratio, pass along a <tt>ratio=no</tt> parameter.


    <a href="http://mysite.com/images?size=80&ratio=no">My Thumbnail</a>

### Image File Path

Bonfire will look for the images within the <tt>assets/images</tt> folder in your web root. If you need to pull files from a different folder, you can use the <tt>assets</tt> parameter and pass in a folder relative to the webroot.

    <a href="http://mysite.com/images?size=80&assets=assets/images">My Thumbnail</a>

### Module Images

If you have images stored in a specific module you can access those images by passing the module name to the <tt>module</tt> parameter.

    <a href="http://mysite.com/images/image=name.png?module=blog">Blog Image</a>
    
This will load an image in the `assets/images` folder in the blog module: `.../bonfire/modules/blog/assets/images/image-name.png`

<a name="performance"></a>
## Boosting Performance

Large strides have been made over the last few years in recognizing techniques to help boost your client-side performance, or apparent performance to make it feel to the user like your site is loading faster. Bonfire provides a couple of different tools to help you with that.

<a name="combining"></a>
### Combining Assets Into Fewer Files

The first step to take usually involves combining your CSS and javascript files into as few files as possible to reduce the number of HTTP connections needed, since these are relatively slow.

You can turn on combing files in your <tt>application.php</tt> configuration file. It is recommended to turn off combining in your development environment but turn it on in testing and production environments.


    $config['assets.combine'] = TRUE;


This will create 2 files that are cached within the <tt>assets/cache</tt> folder. The first file combines all external files that are hosted within this site into a single file. The second file contains all of your module-specific files. This is the same file that is always generated for module js and css files.

Combining works for both CSS and Javascript files.

<a name="minimizing"></a>
### Minimizing Assets

In addition to just combining the files, you can minimize your CSS and Javascript files, reducing the amount of bandwidth used for every page. You can turn on minizing files in your <tt>application.php</tt> configuration file. It is recommended to turn off minimizing in your development environment but turn it on in testing and production environments.

    $config['assets.js_minify'] = TRUE;
    $config['assets.css_minify'] = TRUE;




<a name="encrypting"></a>
### Encrypting File Names

While not a performance booster, those more security-conscious developers might dislike the typical naming convention of your cached asset files which display limited amount of information about the controller and module running. To obscure this information you can turn on file name encryption. This creates an md5 hash of the normal file name to cache the file as.



    $config['assets.encrypt_name'] = TRUE;




<a name="cache"></a>
### Clearing the Asset Cache

You can delete all of your asset's cached files by calling the <tt>clear_cache()</tt> method. ALL cached assets will be deleted.


    Assets::clear_cache();


<a name="helpers"></a>
## Helper Functions

Three helper functions are provided to help create manual links to assets within your view files.

<a name="assets_path"></a>
### <tt>assets_path()</tt>

This returns a full URI to the main asset folder.

    echo assets_path();

    // Produces
    http://mysite.com/assets/


<a name="css_path"></a>
### <tt>css_path()</tt>

This returns a full URI to the CSS folder.


    echo css_path();

    // Produces
    http://mysite.com/assets/css/


<a name="js_path"></a>
### <tt>js_path()</tt>

This returns a full URI to the scripts folder.


    echo js_path();

    // Produces
    http://mysite.com/assets/js/


<a name="img_path"></a>
### <tt>img_path()</tt>

This returns a full URI to the images folder.

    echo img_path();

    // Produces
    http://mysite.com/assets/images/
