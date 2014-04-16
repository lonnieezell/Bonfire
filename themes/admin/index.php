<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-COMPATIBLE" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title><?= config_item('site_name') ?></title>

    <link rel="stylesheet" href="/assets/bootstrap3/css/bootstrap.min.css" />
</head>
<body>
    <?= $this->insert($this->tpl_theme .'::_main_nav') ?>

    <div class="container-fluid">
        <div class="row">

            <!-- SideBar -->
            <div class="col-sm-3 col-md-2 sidebar">
                <ul class="nav nav-sidebar">
                    <li><a href="#">Test 1</a></li>
                    <li><a href="#">Test 2</a></li>
                    <li><a href="#">Test 3</a></li>
                </ul>
            </div>

            <!-- Main Page -->
            <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

                <?= $this->content() ?>

            </div>
        </div>
    </div>

    Content Goes Here

    <?= $this->insert($this->tpl_theme .'::_footer') ?>
</body>
