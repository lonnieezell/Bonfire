<!doctype html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>Docs - <?php e($this->settings_lib->item('site.title')); ?></title>
    <?php
    $jqueryVersion = '1.7.2';
    $highlightScript = '$("pre code").each(function(i, e) {hljs.highlightBlock(e)});';

    Assets::add_js('highlight.min.js');
    Assets::add_js($highlightScript, 'inline');

    Assets::add_css('github');
    echo Assets::css();
    ?>
</head>
<body>
    <div class="sidebar">
        <div class="inner">
            <?php echo $sidebar; ?>
        </div>
    </div>
    <div class="main">
        <div class="inner">
            <?php echo Template::content(); ?>
        </div>
    </div>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/<?php echo $jqueryVersion; ?>/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="<?php echo js_path(); ?>jquery-<?php echo $jqueryVersion; ?>.min.js"><\/script>')</script>
    <?php echo Assets::js(); ?>
</body>
</html>