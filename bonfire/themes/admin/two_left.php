<?php
Assets::add_js(array(
                    'bootstrap.js',
                    Template::theme_url('js/jwerty.js')
               ),
               'external',
               true
);
?>
<?php echo theme_view('partials/_header'); ?>

<div class="container-fluid body">
	<div class="row-fluid">
		<div class="span12">
			<?php echo breadcrumb( NULL, TRUE, TRUE); ?>
		</div>
	</div>

	<div class="row-fluid">
		<div class="span2">
			<?php echo Template::block('sidebar'); ?>
		</div>

		<div class="span10">
			<?php echo Template::message(); ?>

			<?php echo Template::yield(); ?>

		</div>
	</div>

</div>

<?php echo theme_view('partials/_footer'); ?>