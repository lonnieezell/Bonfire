<!DOCTYPE html>
<html>
<head>
<title>Bonfire Tests</title>

<style>
	body { font: 0.75em "Helvetica Neue", Arial, Helvetica, sans-serif;	color: #000; margin: 0;	padding: 50px 0 0 0; }
	code, pre {	background-color: #f9f9f9; border: 1px solid #D0D0D0; color: #002166; display: block; font-family: "Bitstream Vera Sans Mono", monospace; font-size: 12px; margin: 14px 5px 14px 5px; padding: 12px 10px 12px 10px; }
	#header { background: #1a1a1a; border-bottom: 1px solid #0f0f0f; padding: 10px;	position: fixed; top: 0; left: 0; right: 0; z-index: 999; }
	#header h2 { color: #eee; margin: 0; display: inline; }
	#nav { margin: 10px 20px; display: inline; }
	#footer { margin-top: 30px;	border-top: 1px solid #999;	padding: 20px;}
	#report { padding: 50px 20px 0 20px; position: relative; }
	.test {	border-bottom: 1px solid #ddd; padding: 5px 0; }
	.test h3 { display: inline;	font-weight: normal; }
	.test.pass h3 { color: #090; }
	.test.fail h3 { color: #e00; }
	.test div.result { color: #fff; float: left; font-weight: bold; margin-right: 8px; text-align: center; width: 55px; }
	.test.fail div.result, .summary.fail { background: #c00; border: 1px solid #900; }
	.test.pass div.result, .summary.pass { background: #0a0; border: 1px solid #080; }
	.summary { color: #fff; font-weight: bold; font-size: 1.5em; padding: 10px; margin: 10px 0; position: absolute; top: 0; left: 0; right: 0; }
	.test div.time { background-color: #f9f9f9; border: 1px solid #D0D0D0; color: #666 !important; float: left; font: 1.15em 'Courier New', monospace; margin-left: -65px; text-align: center; width: 55px; }
	.test .details { padding-top: 5px; padding-left: 65px; }
	.test.pass .details { color: #999; }
	.test.fail .details { color: #444; }
	.test.fail .details strong { color: #900; }
	form { margin: 0; padding: 0; display: inline; }
	#running { display:none; border: #999 1px solid; line-height: 30px; vertical-align: middle;background-color: #fff; padding:	20px 20px 12px 20px; }
	#running img { margin:10px;	width:15px;	height:15px; vertical-align: middle; }
</style>

</head>
<body>
	<div id="header">
		<h2>Bonfire Tests</h2>

		<div id="nav">
			<form action="<?php echo $form_url; ?>" method="post">
				<input type="hidden" name="all" value="1" />
				<input type="submit" value="Run All" />
			</form>
			<form action="<?php echo $form_url; ?>" method="post">
				<input type="hidden" name="app_only" value="1" />
				<input type="submit" value="All App" />
			</form>
			<form action="<?php echo $form_url; ?>" method="post">
				<input type="hidden" name="bf_only" value="1" />
				<input type="submit" value="All Bonfire" />
			</form>

			<?php
			// RT Wolf's addition: HTML select the test you just ran in drop down list in case you want to rerun it.
			// www.mind-manual.com
			if (isset($_POST['test']) && trim($_POST['test']) != "") {
				$testName = explode('/', $_POST['test']);
				$testName = $testName[1];
			}
			else {
				$testName = "";
			}
			?>

			<form action="<?php echo $form_url; ?>" method="post">
				<select name="test">
					<?php foreach ($all_tests as $file) :?>
						<option value="<?php echo str_replace(TESTS_DIR, '', $file) ?>"><?php echo str_replace(TESTS_DIR, '', $file) ?></option>
					<?php endforeach; ?>
				</select>
				<input type="submit" value="Run" />
			</form>
		</div>
	</div>


	<div id="report">
		<?php $test_suite->run(new MyReporter()); ?>
	</div>

	<div id="footer">
		Tests ran in <?php echo $elapse_time ?> seconds, using <?php echo memory_usage(); ?>
	</div>

</body>
</html>