<?php
	$cur_url = uri_string();
	$tot = $this->uri->total_segments();
	$last_seg = $this->uri->segment( $tot);

	if( is_numeric($last_seg) ) {
		$cur_url = str_replace('/index/'.$last_seg, '', $cur_url);
	}

?>

<?php if (validation_errors()) : ?>
<div class="notification error">
	<?php echo validation_errors(); ?>
</div>
<?php endif; ?>

<div class="notification information">
	<?php echo lang('mb_form_note'); ?>
</div>

<?php if ($this->session->flashdata('error')): ?>
<div class="top_error"><?php echo $this->session->flashdata('error')?></div>
<?php endif; ?>


<?php if ($form_error): ?>
<div class="important">
<h4><?php echo lang('mb_form_errors'); ?></h4>
</div>
<?php endif; ?>

<?php echo form_open($cur_url."/index/".$field_total."/", 'class="constrained ajax-form"'); ?>
<div>  
	<!-- Module Details -->
	<fieldset style="margin-top: 0">
		<legend><?php echo lang('mb_form_mod_details'); ?></legend>
		<?php echo form_error("module_name"); ?>
		<?php echo form_error("form_input_delimiters"); ?>
		<?php echo form_error("form_error_delimiters"); ?>
		<div>
			<label for="module_name" class="block"><?php echo lang('mb_form_mod_name'); ?></label>
			<input name="module_name" id="module_name" type="text" value="<?php echo set_value("module_name"); ?>" placeholder="<?php echo lang('mb_form_mod_name_ph'); ?>" />
		</div>
		
		<div>
			<label for="module_description" class="block"><?php echo lang('mb_form_mod_desc'); ?></label>
			<input name="module_description" id="module_description" type="text" value="<?php echo set_value("module_description"); ?>" placeholder="<?php echo lang('mb_form_mod_desc_ph'); ?>" />
		</div>

		<div>
			<label for="contexts" class="block"><?php echo lang('mb_form_contexts'); ?></label>
			
			<input name="contexts[]" type="checkbox" value="public" <?php echo set_checkbox("contexts", 'public', true); ?> /> <?php echo lang('mb_form_public'); ?>
			<?php foreach (config_item('contexts') as $context) : ?>
				<input name="contexts[]" type="checkbox" value="<?php echo $context ?>" <?php echo set_checkbox("contexts", $context, true); ?> /> <?php echo ucwords($context) ?>
			<?php endforeach; ?>
		</div>
		<div>
		<?php echo form_error("form_action"); ?>
			<label for="form_action"><?php echo lang('mb_form_actions'); ?></label>

			<?php foreach($form_action_options as $value => $label): ?>
			<?php 
			$data = array(
				'name'        => 'form_action[]',
				'id'          => 'form_action',
				'value'       => $value,
				'checked'     => set_checkbox('form_action', $value, true),
				);

			echo form_checkbox($data); ?> <?php echo $label;?>
			<?php endforeach;?>
		</div>
		<br />
		<div>
			<label for="primary_key_field" class="block"><?php echo lang('mb_form_primarykey'); ?></label>
			<input name="primary_key_field" id="primary_key_field" type="text" value="<?php echo set_value("primary_key_field", 'id'); ?>" />
		</div>
		<div>
			<label for="form_input_delimiters" class="block"><?php echo lang('mb_form_delims'); ?></label>
			<input name="form_input_delimiters" id="form_input_delimiters" type="text" value="<?php echo set_value("form_input_delimiters", '<div>,</div>'); ?>" />
		</div>
		<div>
			<label for="form_error_delimiters" class="block"><?php echo lang('mb_form_err_delims'); ?></label>
			<input name="form_error_delimiters" id="form_error_delimiters" type="text" value="<?php echo set_value("form_error_delimiters", "<span class='error'>,</span>"); ?>" />
		</div>
		<div>
			<label for="textarea_editor" class="block"><?php echo lang('mb_form_text_ed'); ?></label>
			<?php 
				$textarea_editors = array('' => 'None', 'ckeditor' => 'CKEditor', 'xinha' => 'Xinha');
			?>
			<?php echo form_dropdown("textarea_editor", $textarea_editors, set_value("textarea_editor")); ?>
		</div>

		<div>
			<label for="db_required"><?php echo lang('mb_form_generate'); ?></label>
			<input name="db_required" id="db_required" type="checkbox" value="1" <?php echo set_checkbox("db_required", "1", true); ?> class="checkbox" />
		</div>

		<div>
			<label for="role_id"><?php echo lang('mb_form_role_id'); ?></label>
			<select name="role_id">
				<?php foreach ($roles as $role) : ?>
				<option value="<?php echo $role['role_id']; ?>"><?php echo $role['role_name']; ?></option>
				<?php endforeach; ?>
		 	</select>
		</div>
	</fieldset>
	
		<div>

		<?php echo lang('mb_form_fieldnum'); ?> 
		<?php 
		$field_num_count = count($field_numbers);
		for($ndx=0; $ndx < $field_num_count; $ndx++): ?>
		<a href="<?php echo site_url($cur_url."/index/{$field_numbers[$ndx]}"); ?>" <?php if ($field_numbers[$ndx] == $field_total) { echo 'class="current"'; } ?>><?php echo $field_numbers[$ndx]; ?></a><?php echo $ndx < $field_num_count - 1 ? ' | ' : '';?>
		<?php endfor; ?>

		</div>
</div>

		<?php
for ($count = 1; $count <= $field_total; $count++) // loop to build 10 form boxes
{
?>

<div class="container <?php if ($count % 2): ?>left<?php endif;?>">  
	<fieldset>
		<legend><?php echo lang('mb_form_field_details'); ?> <?php echo $count; ?></legend>

		<?php echo form_error("view_field_label{$count}"); ?>
		<?php echo form_error("view_field_name{$count}"); ?>
		<?php echo form_error("view_field_type{$count}"); ?>

		<div>

		<label for="view_field_label<?php echo $count; ?>"><?php echo lang('mb_form_label'); ?></label>

		<input name="view_field_label<?php echo $count; ?>" id="view_field_label<?php echo $count; ?>" type="text" value="<?php echo set_value("view_field_label{$count}"); ?>" onkeyup="liveUrlTitle(<?php echo $count; ?>);" placeholder="<?php echo lang('mb_form_label_ph'); ?>" />
		</div>

		<div>
		<label for="view_field_name"><?php echo lang('mb_form_fieldname'); ?></label>
		<input name="view_field_name<?php echo $count; ?>" id="view_field_name<?php echo $count; ?>" type="text" value="<?php echo set_value("view_field_name{$count}"); ?>" maxlength="30" placeholder="<?php echo lang('mb_form_fieldname_ph'); ?>" />
		</div>

		<div>
		<label for="view_field_type<?php echo $count; ?>"><?php echo lang('mb_form_type'); ?></label>

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

		<div>
		<label for="db_field_length_value<?php echo $count; ?>"><?php echo lang('mb_form_length'); ?></label>
		<input name="db_field_length_value<?php echo $count; ?>" type="text" value="<?php echo set_value("db_field_length_value{$count}"); ?>" placeholder="<?php echo lang('mb_form_length_ph'); ?>" />
		</div>

		<?php echo form_error("db_field_type{$count}"); ?>
		<?php echo form_error("db_field_length_value{$count}"); ?>

		<div>
		<label for="db_field_type<?php echo $count; ?>"><?php echo lang('mb_form_dbtype'); ?></label>

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

		<label for="db_field_length_value<?php echo $count; ?>"><?php echo lang('mb_form_rules'); ?></label>

		<?php echo form_error('cont_validation_rules'.$count.'[]'); ?>

		<input type="checkbox" name="validation_rules<?php echo $count; ?>[]" id="validation_rules<?php echo $count; ?>[]" value="required" <?php echo set_checkbox('validation_rules'.$count.'[]', 'required'); ?>  /> <?php echo lang('mb_form_required'); ?>

		<input type="checkbox" name="validation_rules<?php echo $count; ?>[]" id="validation_rules<?php echo $count; ?>[]" value="trim" <?php echo set_checkbox('validation_rules'.$count.'[]', 'trim'); ?> checked="checked" /> <?php echo lang('mb_form_trim'); ?>

		<input type="checkbox" name="validation_rules<?php echo $count; ?>[]" id="validation_rules<?php echo $count; ?>[]" value="xss_clean" <?php echo set_checkbox('validation_rules'.$count.'[]', 'xss_clean'); ?> checked="checked" /> <?php echo lang('mb_form_xss'); ?>

		<input type="checkbox" name="validation_rules<?php echo $count; ?>[]" id="validation_rules<?php echo $count; ?>[]" value="valid_email" <?php echo set_checkbox('validation_rules'.$count.'[]', 'valid_email'); ?> /> <?php echo lang('mb_form_valid_email'); ?>

		<input type="checkbox" name="validation_rules<?php echo $count; ?>[]" id="validation_rules<?php echo $count; ?>[]" value="is_numeric" <?php echo set_checkbox('validation_rules'.$count.'[]', 'is_numeric'); ?> /> <?php echo lang('mb_form_is_numeric'); ?>
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