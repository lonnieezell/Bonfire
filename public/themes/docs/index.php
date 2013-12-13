<!doctype html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>Docs - <?php e($this->settings_lib->item('site.title')); ?></title>
    <?php
    $jqueryVersion = '1.7.2';
    $highlightScript = '$("pre code").each(function(i, e) {hljs.highlightBlock(e)});';

    Assets::add_js('bootstrap.min.js');
    Assets::add_js('highlight.min.js');
    Assets::add_js($highlightScript, 'inline');

    Assets::add_css('bootstrap.css');
    Assets::add_css('bootstrap-theme.css');
    Assets::add_css('github');
    echo Assets::css();
    ?>
</head>
<body style="padding-top: 70px;">

    <!-- Navbar -->
    <header class="navbar navbar-inverse navbar-fixed-top" role="banner">
        <div class="container">

            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#main-nav-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>


            <div class="collapse navbar-collapse" id="main-nav-collapse">
                <ul class="nav navbar-nav navbar-left">
                    <?php if (config_item('docs.show_app_docs')) :?>
                    <li <?php echo check_segment(2, 'application') ?>>
                        <a href="<?php echo site_url('docs/application'); ?>"><?php echo lang('docs_title_application') ?></a>
                    </li>
                    <?php endif; ?>

                    <?php if (config_item('docs.show_dev_docs')) : ?>
                    <li <?php echo check_segment(2, 'developer') ?>>
                        <a href="<?php echo site_url('docs/developer'); ?>"><?php echo lang('docs_title_bonfire') ?></a>
                    </li>
                    <?php endif; ?>
                </ul>

                <!-- Search Form -->
                <?php echo form_open( site_url('docs/search'), 'class="navbar-form navbar-right"' ); ?>
                    <div class="form-group">
                        <input type="text" class="form-control" name="search_terms" placeholder="<?php echo lang('docs_search_for') ?>"/>
                    </div>
                    <input type="submit" name="submit" class="btn btn-default" value="<?php echo lang('docs_search') ?>">
                </form>
            </div>

        </div>
    </header>

    <!-- Content Area -->
    <div class="container">

        <?php echo Template::message(); ?>

        <div class="row">

            <div class="col-md-3 sidebar">
                <div class="inner">
                    <?php if (isset($sidebar)) : ?>
                        <?php echo $sidebar; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-md-9 main">
                <div class="inner">
                    <?php echo Template::content(); ?>
                </div>
            </div>

        </div>

    </div>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/<?php echo $jqueryVersion; ?>/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="<?php echo js_path(); ?>jquery-<?php echo $jqueryVersion; ?>.min.js"><\/script>')</script>
    <?php echo Assets::js(); ?>
</body>
</html>