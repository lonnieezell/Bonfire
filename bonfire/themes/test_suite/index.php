<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	
	<title>Bonfire Unit Tests</title>
	
	<?php echo Assets::css(); ?>
</head>
<body>

	<div class="head">
		<h1>Bonfire Unit Tests</h1>
		
		<form accept="<?php echo $this->uri->uri_string(); ?>" method="post" />

			<label>Bonfire Core</label>
			<select name="tests[]">
				<option value="0"></option>
				<?php foreach ($core_tests as $file) : ?>
					<option><?php echo str_replace('.php', '', $file); ?></option>
				<?php endforeach; ?>
			</select>
			
			<input type="submit" name="submit" value="Run Tests" />
			
		</form>
	</div>
	
	<div class="main">
		<?php echo Template::yield(); ?>
	</div>

</body>
</html> 