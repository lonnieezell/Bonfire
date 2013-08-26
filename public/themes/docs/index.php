<!doctype html>
<html>
<head>
    <meta charset="UTF-8" />

    <title>Docs - <?php e($this->settings_lib->item('site.title')); ?></title>
    <?php echo Assets::css(); ?>
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

</body>
</html>