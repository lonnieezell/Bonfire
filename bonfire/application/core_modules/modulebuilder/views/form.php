
<?php
	$cur_url = uri_string();
	$tot = $this->uri->total_segments();
	$last_seg = $this->uri->segment( $tot);

	if( is_numeric($last_seg) ) {
		$cur_url = str_replace('/index/'.$last_seg, '', $cur_url);
	}
?>

<?php if ($this->session->flashdata('error')): ?>
<div class="top_error"><?php echo $this->session->flashdata('error')?></div>
<?php endif; ?>

<?php // echo validation_errors(); for debuggging only ?>

<?php echo form_open($cur_url."/index/".$field_total."/", 'class="constrained"'); ?>

	<br/>
	
<?php if ($form_error): ?>
<div class="important">
<h4>Please correct the errors below.</h4>
</div>
<?php endif; ?>

<p><b>Fill out the fields you would like in your module (an "id" field is created automatically).  If you want to create the SQL for a DB table check "DB Required".</b></p>

<p>This form will generate a full CodeIgniter module (controller and views) and if you choose model, Javascript and SQL files.</p>

<p>
If DB field type is "enum" or "set", please enter the values using this format: 'a','b','c'...
<br />If you ever need to put a backslash ("\") or a single quote ("'") amongst those values, precede it with a backslash (for example '\\xyz' or 'a\'b').
</p>

<div>  
	<fieldset>
		<legend>Module details</legend>
		<?php echo form_error("module_name"); ?>
		<?php echo form_error("form_input_delimiters"); ?>
		<?php echo form_error("form_error_delimiters"); ?>
		<div class="input_box">
			<label for="module_name" class="block">Module Name</label>
			<input name="module_name" id="module_name" type="text" value="<?php echo set_value("module_name"); ?>" />
		</div>

		<div class="input_box">
			<label for="contexts" class="block">Main Context</label>
			<select name="main_context" id="main_context">
			<option value="content" <?php echo set_select("main_context", "content"); ?>>Content</option>
			<option value="statistics" <?php echo set_select("main_context", "statistics"); ?>>Statistics</option>
			<option value="appearance" <?php echo set_select("main_context", "appearance"); ?>>Appearance</option>
			<option value="settings" <?php echo set_select("main_context", "settings"); ?>>Settings</option>
			<option value="developer" <?php echo set_select("main_context", "developer"); ?>>Developer</option>
			<option value="public" <?php echo set_select("main_context", "public"); ?>>Public</option>
			</select>
		</div>
		<div class="input_box">
			<label for="contexts" class="block">Contexts Required</label>
			<input name="contexts[]" id="contexts" type="checkbox" value="content" <?php echo set_checkbox("contexts[]", "content"); ?> class="checkbox" /> Content
			<input name="contexts[]" id="contexts" type="checkbox" value="statistics" <?php echo set_checkbox("contexts[]", "statistics"); ?> class="checkbox" /> Statistics
			<input name="contexts[]" id="contexts" type="checkbox" value="appearance" <?php echo set_checkbox("contexts[]", "appearance"); ?> class="checkbox" /> Appearance
			<input name="contexts[]" id="contexts" type="checkbox" value="settings" <?php echo set_checkbox("contexts[]", "settings"); ?> class="checkbox" /> Settings
			<input name="contexts[]" id="contexts" type="checkbox" value="developer" <?php echo set_checkbox("contexts[]", "developer"); ?> class="checkbox" /> Developer
			<input name="contexts[]" id="contexts" type="checkbox" value="public" <?php echo set_checkbox("contexts[]", "public"); ?> class="checkbox" /> Public
		</div>
		<div class="input_box">
		<?php echo form_error("form_action"); ?>
			<label for="form_action">Controller Actions</label>

			<?php foreach($form_action_options as $value => $label): ?>
			<?php //echo form_dropdown('form_action', $form_action_options, set_value('form_action'));
			$data = array(
				'name'        => 'form_action[]',
				'id'          => 'form_action',
				'value'       => $value,
				'checked'     => set_checkbox('form_action', $value),
				);

			echo form_checkbox($data); ?> <?php echo $label;?>
			<?php endforeach;?>
		</div>
		<br />
		<div class="input_box">
			<label for="form_input_delimiters" class="block">Form Input Delimiters</label>
			<input name="form_input_delimiters" id="form_input_delimiters" type="text" value="<?php echo set_value("form_input_delimiters", '<p>,</p>'); ?>" />
		</div>
		<div class="input_box">
			<label for="form_error_delimiters" class="block">Form Error Delimiters</label>
			<input name="form_error_delimiters" id="form_error_delimiters" type="text" value="<?php echo set_value("form_error_delimiters", "<span class='error'>,</span>"); ?>" />
		</div>

		<div class="input_box">
		<label for="db_required">Require DB SQL</label>
		<input name="db_required" id="db_required" type="checkbox" value="1" <?php echo set_checkbox("db_required", "1"); ?> class="checkbox" />
		</div>
		<div class="input_box">
		<label for="ajax_processing">Require Javascript</label>
		<input name="ajax_processing" id="ajax_processing" type="checkbox" value="1" <?php echo set_checkbox("ajax_processing", "1"); ?> class="checkbox" />
		</div>
	</fieldset>
		<div class="input_box">

		Number of fields 
		<?php 
		$field_num_count = count($field_numbers);
		for($ndx=0; $ndx < $field_num_count; $ndx++): ?>
		<a href="/<?php echo $cur_url."/{$field_numbers[$ndx]}"; ?>/" <?php if ($field_numbers[$ndx] == $field_total) { echo 'class="current"'; } ?>><?php echo $field_numbers[$ndx]; ?></a><?php echo $ndx < $field_num_count - 1 ? ' | ' : '';?>
		<?php endfor; ?>

		</div>
</div>

		<?php
for ($count = 1; $count <= $field_total; $count++) // loop to build 10 form boxes
{
?>

<div class="container <?php if ($count % 2): ?>left<?php endif;?>">  
	<fieldset>
		<legend>Field details <?php echo $count; ?></legend>

		<?php echo form_error("view_field_label{$count}"); ?>
		<?php echo form_error("view_field_name{$count}"); ?>
		<?php echo form_error("view_field_type{$count}"); ?>

		<div class="input_box">

		<label for="view_field_label<?php echo $count; ?>">Label</label>

		<input name="view_field_label<?php echo $count; ?>" id="view_field_label<?php echo $count; ?>" type="text" value="<?php echo set_value("view_field_label{$count}"); ?>" onkeyup="liveUrlTitle(<?php echo $count; ?>);" />
		</div>

		<div class="input_box">
		<label for="view_field_name">Name (no spaces)</label>
		<input name="view_field_name<?php echo $count; ?>" id="view_field_name<?php echo $count; ?>" type="text" value="<?php echo set_value("view_field_name{$count}"); ?>" maxlength="30" />
		</div>

		<div class="input_box">
		<label for="view_field_type<?php echo $count; ?>">Type</label>

		<?php
		$view_field_types = array(
								'input' 	=> 'INPUT',
								'textarea' 	=> 'TEXTAREA',
								'select' 	=> 'SELECT',
								'radio' 	=> 'RADIO',
								'checkbox' 	=> 'CHECKBOX',
								'password' 	=> 'PASSWORD'
								);
		?>
		<?php echo form_dropdown("view_field_type{$count}", $view_field_types, set_value("view_field_type{$count}")); ?>
		</div>

		<div class="input_box">
		<label for="db_field_length_value<?php echo $count; ?>">Length/Values</label>
		<input name="db_field_length_value<?php echo $count; ?>" type="text" value="<?php echo set_value("db_field_length_value{$count}"); ?>" />
		</div>

		<?php echo form_error("db_field_type{$count}"); ?>
		<?php echo form_error("db_field_length_value{$count}"); ?>

		<div class="input_box">
		<label for="db_field_type<?php echo $count; ?>">Database Type</label>

		<?php
		$db_field_types = array(
								'VARCHAR' 		=> 'VARCHAR',
								'TINYINT' 		=> 'TINYINT',
								'TEXT' 			=> 'TEXT',
								'DATE' 			=> 'DATE',
								'SMALLINT' 		=> 'SMALLINT',
								'MEDIUMINT' 	=> 'MEDIUMINT',
								'INT' 			=> 'INT',
								'BIGINT' 		=> 'BIGINT',
								'FLOAT' 		=> 'FLOAT',
								'DOUBLE' 		=> 'DOUBLE',
								'DECIMAL' 		=> 'DECIMAL',
								'DATETIME' 		=> 'DATETIME',
								'TIMESTAMP' 	=> 'TIMESTAMP',
								'TIME' 			=> 'TIME',
								'YEAR' 			=> 'YEAR',
								'CHAR' 			=> 'CHAR',
								'TINYBLOB' 		=> 'TINYBLOB',
								'TINYTEXT' 		=> 'TINYTEXT',
								'BLOB' 			=> 'BLOB',
								'MEDIUMBLOB' 	=> 'MEDIUMBLOB',
								'MEDIUMTEXT' 	=> 'MEDIUMTEXT',
								'LONGBLOB' 		=> 'LONGBLOB',
								'LONGTEXT' 		=> 'LONGTEXT',
								'ENUM' 			=> 'ENUM',
								'SET' 			=> 'SET',
								'BIT' 			=> 'BIT',
								'BOOL' 			=> 'BOOL',
								'BINARY' 		=> 'BINARY',
								'VARBINARY' 	=> 'VARBINARY'
								);
		?>
		<?php echo form_dropdown("db_field_type{$count}", $db_field_types, set_value("db_field_type{$count}")); ?>

		</div>

		<label for="db_field_length_value<?php echo $count; ?>">Validation Rules</label>

		<?php echo form_error('cont_validation_rules'.$count.'[]'); ?>

		<input type="checkbox" name="validation_rules<?php echo $count; ?>[]" id="validation_rules<?php echo $count; ?>[]" value="required" <?php echo set_checkbox('validation_rules'.$count.'[]', 'required'); ?>  /> required

		<input type="checkbox" name="validation_rules<?php echo $count; ?>[]" id="validation_rules<?php echo $count; ?>[]" value="trim" <?php echo set_checkbox('validation_rules'.$count.'[]', 'trim'); ?> /> trim

		<input type="checkbox" name="validation_rules<?php echo $count; ?>[]" id="validation_rules<?php echo $count; ?>[]" value="xss_clean" <?php echo set_checkbox('validation_rules'.$count.'[]', 'xss_clean'); ?> /> xss_clean

		<input type="checkbox" name="validation_rules<?php echo $count; ?>[]" id="validation_rules<?php echo $count; ?>[]" value="valid_email" <?php echo set_checkbox('validation_rules'.$count.'[]', 'valid_email'); ?> /> valid_email

		<input type="checkbox" name="validation_rules<?php echo $count; ?>[]" id="validation_rules<?php echo $count; ?>[]" value="is_numeric" <?php echo set_checkbox('validation_rules'.$count.'[]', 'is_numeric'); ?> /> is_numeric
	</fieldset>
</div><!-- end container -->
<?php
} // end loop
?>

<div class="submits">
	<br />
	<?php echo form_submit('submit', 'Build the Module'); ?>
</div>
<?php echo form_close()?>
