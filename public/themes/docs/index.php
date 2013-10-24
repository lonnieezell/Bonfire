<!doctype html>
<html>
<head>
    <meta charset="UTF-8" />

    <title>Docs - <?php e($this->settings_lib->item('site.title')); ?></title>
    <?php echo Assets::css(); ?>
    <link rel="stylesheet" href="<?= base_url() ?>/themes/docs/css/pojoaque.css">
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

    <script type="text/javascript" src="/themes/admin/js/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="/themes/docs/js/highlight.min.js"></script>
    <script>
        $(document).ready(function() {
          $('pre code').each(function(i, e) {hljs.highlightBlock(e)});
        });
    </script>
</body>
</html>