<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Welcome to CI-Bonfire</title>
        <base target="_blank">
        <link rel="stylesheet" type="text/css" href="public/assets/css/bootstrap.min.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="public/assets/css/bootstrap-responsive.min.css" media="screen" />
        <style>
            body {
                font-family:sans-serif;
            }
            #intro {
                width:700px;
                margin-left:-390px;
                position:fixed;
                left:50%;
                top:60px;
                padding:10px 30px;
            }
            h1 {
                text-align:center;
            }
            .continue {
                padding:10px 0;
                text-align:center;
            }
        </style>
    </head>
    <body>
        <div id="intro" class="well">
            <h1>Welcome to Bonfire v0.7-dev</h1>
            <p>Some things have changed since the last version, specifically pertaining to the installer. Here is the new way <strong>Bonfire v0.7-dev</strong> handles the installation.</p>
            <p>Before continuing:</p>
            <ol>
                <li>Create your database manually</li>
                <li>Edit your <strong>application/config/database.php</strong> file accordingly</li>
                <li>Also, you may want to rename 1.htaccess to .htaccess if you wish to use mod_rewrite. =D</li>
            </ol>
            <div class="alert alert-error">
                <h3>Oops!</h3>
                <p>Your Web Root should be set to the <strong>public</strong> folder, but it's <strong>not</strong>. It's pointing to the <strong>Bonfire Root</strong> folder.</p>
                <p>See below for an example of how your site should be set up in Apache:</p>
<pre>&lt;VirtualHost *:80&gt;
    DocumentRoot "[...]/htdocs/Bonfire_Root/public"
    ServerName Bonfire.Root
    ServerAlias Bonfire.Root.local
&lt;/VirtualHost&gt;</pre>
            </div>
            <p><em>Please Note:</em> Since this is a developmental release there <em>will</em> be bugs. If you uncover any please <a href="https://github.com/ci-bonfire/Bonfire/wiki/Issue-Tracking-and-Pull-Requests">read this</a> before submitting your detailed bug report <a href="https://github.com/ci-bonfire/Bonfire/issues">here</a>.</p>
            <p>If you feel like you can contribute either by <a href="https://trello.com/b/I54dfqR4/bonfire-roadmap">adding features</a> or <a href="https://github.com/ci-bonfire/Bonfire/issues?state=open">fixing issues</a> please fork <a href="https://github.com/ci-bonfire/Bonfire">the repo</a>, start your work in a new branch, and submit pull requests for review.</p>
            <p><em>"Let's make this the best kick-start to any CodeIgniter project."</em> ~ The CI-Bonfire Team</p>
        </div>
        <!-- Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if offline -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="public/assets/js/jquery-1.7.2.min.js"><\/script>');
        </script>
        <!-- This would be a good place to use a CDN version of jQueryUI if needed -->
        <script type="text/javascript" src="public/assets/js/bootstrap.min.js" ></script>
    </body>
</html>