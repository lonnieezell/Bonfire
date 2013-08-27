<?php

$truefalse = array(
	'false' => 'False',
	'true' => 'True',
);

// textarea_editor seems to have been removed...
/*
$textarea_editors = array(
	''          => 'None',
	'ckeditor'  => 'CKEditor',
	'xinha'     => 'Xinha',
	'tinymce'   => 'TinyMCE',
	'markitup'  => 'MarkitUp!',
);
 */

$session_error = $this->session->flashdata('error');
$validation_errors = validation_errors();

?>
<style>
.faded {
	opacity: .60;
}
.faded:hover,
.faded.faded-focus,
.mb_show_advanced:focus,
.mb_show_advanced:hover,
.mb_show_advanced_rules:focus,
.mb_show_advanced_rules:hover {
	opacity: 1;
	color: black;
}
a.mb_show_advanced_rules:hover {
	text-decoration: none;
}
.body legend {
	cursor: pointer;
}
.mb_advanced {
	display: none;
}
fieldset {
	margin-top: 0;
}
.alert span.error {
    display: block;
}
</style>
<p class="intro">
	<?php e(lang('mb_create_note')); ?>
</p>
<div class="alert alert-info fade in">
	<a class="close" data-dismiss="alert">&times;</a>
	<?php echo lang('mb_form_note'); ?>
</div>
<?php if ( ! $writeable) : ?>
<div class="alert alert-error fade in">
	<a class="close" data-dismiss="alert">&times;</a>
	<p><?php echo lang('mb_not_writeable_note'); ?></p>
</div>
<?php
endif;

if ($validation_errors) :
?>
<div class="alert alert-error fade in">
    <a data-dismiss="alert" class="close">&times;</a>
    <h4 class="alert-heading"><?php echo lang('mb_form_errors'); ?></h4>
    <?php echo $validation_errors; ?>
</div>
<?php
endif;

if ($session_error):
?>
<div class="top_error">
	<?php echo $session_error; ?>
</div>
<?php endif; ?>
<div class="admin-box">
	<?php echo form_open(current_url() . ($field_total > 0 ? ('/' . $field_total) : ''), array('id' => "module_form", 'class' => "form-horizontal")); ?>
		<div>
			<!-- Module Details -->
			<fieldset id="module_details">
				<legend><?php echo lang('mb_form_mod_details'); ?></legend>

				<div class="control-group<?php echo form_has_error('module_name') ? ' error' : ''; ?>">
					<label for="module_name" class="control-label block"><?php echo lang('mb_form_mod_name'); ?></label>
					<div class="controls">
						<input name="module_name" id="module_name" type="text" value="<?php echo set_value("module_name"); ?>" placeholder="<?php echo lang('mb_form_mod_name_ph'); ?>" />
						<span class="help-inline"><?php echo form_error('module_name'); ?></span>
						<div><a href="#" class="mb_show_advanced small"><?php echo lang('mb_form_show_advanced'); ?></a></div>
					</div>
				</div>

				<div class="control-group mb_advanced<?php echo form_has_error('module_description') ? ' error' : ''; ?>">
					<label for="module_description" class="control-label block"><?php echo lang('mb_form_mod_desc'); ?></label>
					<div class="controls">
						<input name="module_description" id="module_description" type="text" value="<?php echo set_value("module_description", lang('mb_form_mod_desc_ph')); ?>" placeholder="<?php echo lang('mb_form_mod_desc_ph'); ?>" />
						<span class="help-inline"><?php echo form_error('module_description'); ?></span>
					</div>
				</div>

				<div class="control-group mb_advanced">
					<label class="control-label block" id="contexts_label"><?php echo lang('mb_form_contexts'); ?></label>
					<div class="controls" aria-labelledby="contexts_label" role="group">
						<label class="checkbox" for="contexts_public">
							<input name="contexts[]" id="contexts_public" type="checkbox" value="public" checked="checked" />
							<?php echo lang('mb_form_public'); ?>
						</label>

						<?php foreach (config_item('contexts') as $context) : ?>
						<label class="checkbox" for="contexts_<?php echo $context; ?>">
							<input name="contexts[]" id="contexts_<?php echo $context; ?>" type="checkbox" value="<?php echo $context; ?>" checked="checked" />
							<?php echo ucwords($context); ?>
						</label>
						<?php endforeach; ?>
					</div>
				</div>

				<div class="control-group mb_advanced<?php echo form_has_error('form_action') ? ' error' : ''; ?>">
					<?php echo form_error("form_action"); ?>
					<label class="control-label" id="form_action_label"><?php echo lang('mb_form_actions'); ?></label>
					<div class="controls" aria-labelledby="form_action_label" role="group">
						<?php foreach($form_action_options as $action => $label) : ?>
						<label class="checkbox" for="form_action_<?php echo $action; ?>">
							<?php
							$data = array(
								'name'        => 'form_action[]',
								'id'          => 'form_action_' . $action,
								'value'       => $action,
								'checked'     => 'checked',
							);

							echo form_checkbox($data);
							echo $label;
							?>
						</label>
						<?php endforeach;?>
					</div>
				</div>

				<div class="control-group mb_advanced">
					<label class="control-label" for="role_id"><?php echo lang('mb_form_role_id'); ?></label>
					<div class="controls">
						<select name="role_id" id="role_id">
							<?php foreach ($roles as $role):?>
							<option value="<?php echo $role['role_id']; ?>"><?php e($role['role_name']); ?></option>
							<?php endforeach;?>
						</select>
					 </div>
				</div>

				<div class="control-group">
                    <label class='control-label' for='mb_module_db'><?php echo lang('mb_module_db'); ?></label>
					<div class="controls" id='mb_module_db'>
						<label class="inline radio" for="db_no">
							<input name="module_db" id="db_no" type="radio" value="" <?php echo set_checkbox('module_db', '', TRUE); ?> class="radio" />
							<?php echo lang('mb_form_module_db_no'); ?>
						</label>
						<label class="inline radio" for="db_create">
							<input name="module_db" id="db_create" type="radio" value="new" <?php echo set_checkbox('module_db', 'new'); ?> class="radio" />
							<?php echo lang('mb_form_module_db_create'); ?>
						</label>
						<label class="inline radio" for="db_exists">
							<input name="module_db" id="db_exists" type="radio" value="existing" <?php echo set_checkbox('module_db', 'existing'); ?> class="radio" />
							<?php echo lang('mb_form_module_db_exists'); ?>
						</label>
					</div>
				</div>
			</fieldset>

			<fieldset id="db_details">
				<legend><?php echo lang('mb_form_table_details'); ?></legend>

				<div class="control-group">
					<div class="controls">
						<a href="#" class="mb_show_advanced small"><?php echo lang('mb_form_show_advanced'); ?></a>
					</div>
				</div>

				<div class="control-group mb_advanced<?php echo form_has_error('table_name') ? ' error' : ''; ?>">
					<label for="table_name" class="control-label block"><?php echo lang('mb_form_table_name'); ?></label>
					<div class="controls">
						<input name="table_name" id="table_name" type="text" value="<?php echo set_value("table_name"); ?>" placeholder="<?php echo lang('mb_form_table_name_ph'); ?>" />
						<span class="help-inline"><?php echo form_error('table_name'); ?></span>
					</div>
				</div>

				<div class="control-group mb_advanced<?php echo form_has_error('form_error_delimiters') ? ' error' : ''; ?>">
					<label for="form_error_delimiters" class="control-label block"><?php echo lang('mb_form_err_delims'); ?></label>
					<div class="controls">
						<input name="form_error_delimiters" id="form_error_delimiters" type="text" value="<?php echo set_value("form_error_delimiters", "<span class='error'>,</span>"); ?>" />
						<span class="help-inline"><?php echo form_error('form_error_delimiters'); ?></span>
					</div>
				</div>

				<div class="control-group mb_advanced">
					<label for="use_soft_deletes" class="control-label block"><?php echo lang('mb_form_soft_deletes'); ?></label>
					<div class="controls">
						<select name="use_soft_deletes" id="use_soft_deletes">
							<?php foreach ($truefalse as $val => $label) : ?>
							<option value="<?php echo $val; ?>"><?php echo $label; ?></option>
							<?php endforeach;?>
						</select>
						<input type="hidden" name="soft_delete_field" id="soft_delete_field" value="<?php echo lang('mb_soft_delete_field_ph'); ?>" />
					</div>
				</div>

				<div class="control-group mb_advanced">
					<label for="use_created" class="control-label block"><?php echo lang('mb_form_use_created'); ?></label>
					<div class="controls">
						<select name="use_created" id="use_created">
							<?php foreach ($truefalse as $val => $label) : ?>
							<option value="<?php echo $val; ?>"><?php echo $label; ?></option>
							<?php endforeach;?>
						</select>
					</div>
				</div>

				<div class="control-group mb_advanced<?php echo form_has_error('created_field') ? ' error' : ''; ?>">
					<label for="created_field" class="control-label block"><?php echo lang('mb_form_created_field'); ?></label>
					<div class="controls">
						<input name="created_field" id="created_field" type="text" value="<?php echo set_value("created_field", lang('mb_form_created_field_ph')); ?>" />
						<span class="help-inline match-existing-notes"><?php echo lang('mb_form_match_existing'); ?></span>
						<span class="help-inline"><?php echo form_error('created_field'); ?></span>
					</div>
				</div>

				<div class="control-group mb_advanced">
					<label for="use_modified" class="control-label block"><?php echo lang('mb_form_use_modified'); ?></label>
					<div class="controls">
						<select name="use_modified" id="use_modified">
							<?php foreach ($truefalse as $val => $label) : ?>
							<option value="<?php echo $val; ?>"><?php echo $label; ?></option>
							<?php endforeach;?>
						</select>
					</div>
				</div>

				<div class="control-group mb_advanced<?php echo form_has_error('modified_field') ? ' error' : ''; ?>">
					<label for="modified_field" class="control-label block"><?php echo lang('mb_form_modified_field'); ?></label>
					<div class="controls">
						<input name="modified_field" id="modified_field" type="text" value="<?php echo set_value("modified_field", lang('mb_form_modified_field_ph')); ?>" />
						<span class="help-inline match-existing-notes"><?php echo lang('mb_form_match_existing'); ?></span>
						<span class="help-inline"><?php echo form_error('modified_field'); ?></span>
					</div>
				</div>

				<div class="alert alert-info fade in mb_new_table" style="width:90%; margin:5px auto;">
					<a class="close" data-dismiss="alert">&times;</a>
					<?php echo lang('mb_table_note'); ?>
				</div>

				<div class="control-group mb_new_table<?php echo form_has_error('primary_key_field') ? ' error' : ''; ?>">
					<label for="primary_key_field" class="control-label block"><?php echo lang('mb_form_primarykey'); ?></label>
					<div class="controls">
						<input name="primary_key_field" id="primary_key_field" type="text" value="<?php echo set_value("primary_key_field", (isset($existing_table_fields[0]) && $existing_table_fields[0]['primary_key']) ? $existing_table_fields[0]['name'] : 'id'); ?>" />
						<span class="help-inline"><?php echo form_error('primary_key_field'); ?></span>
					</div>
				</div>

				<div id="field_numbers" class="control-group">
					<label class="control-label"><?php echo lang('mb_form_fieldnum'); ?></label>
					<div class="controls" style="padding-top: 5px;">
						<?php
						$field_num_count = count($field_numbers);
						for ($ndx = 0; $ndx < $field_num_count; $ndx++) :
						?>
						<a href="<?php echo site_url(SITE_AREA . '/developer/builder/create_module/' . $field_numbers[$ndx]); ?>"<?php echo $field_numbers[$ndx] == $field_total ? ' class="current"' : ''; ?>>
							<?php echo $field_numbers[$ndx]; ?>
						</a><?php echo $ndx < $field_num_count - 1 ? ' | ' : ''; ?>
						<?php endfor; ?>
					</div>
				</div>
			</fieldset>
		</div>

		<div id="all_fields">
			<?php
			// set the arrays outside the loop
			$view_field_types = array(
				'input' 	=> 'INPUT',
				'checkbox' 	=> 'CHECKBOX',
				'password' 	=> 'PASSWORD',
				'radio' 	=> 'RADIO',
				'select' 	=> 'SELECT',
				'textarea' 	=> 'TEXTAREA',
			);

			$db_field_types = array(
				'VARCHAR' 		=> 'VARCHAR',
				'BIGINT' 		=> 'BIGINT',
				'BINARY' 		=> 'BINARY',
				'BIT' 			=> 'BIT',
				'BLOB' 			=> 'BLOB',
				'BOOL' 			=> 'BOOL',
				'CHAR' 			=> 'CHAR',
				'DATE' 			=> 'DATE',
				'DATETIME' 		=> 'DATETIME',
				'DECIMAL' 		=> 'DECIMAL',
				'DOUBLE' 		=> 'DOUBLE',
				'ENUM' 			=> 'ENUM',
				'FLOAT' 		=> 'FLOAT',
				'INT' 			=> 'INT',
				'LONGBLOB' 		=> 'LONGBLOB',
				'LONGTEXT' 		=> 'LONGTEXT',
				'MEDIUMBLOB' 	=> 'MEDIUMBLOB',
				'MEDIUMINT' 	=> 'MEDIUMINT',
				'MEDIUMTEXT' 	=> 'MEDIUMTEXT',
				'SET' 			=> 'SET',
				'SMALLINT' 		=> 'SMALLINT',
				'TEXT' 			=> 'TEXT',
				'TIME' 			=> 'TIME',
				'TIMESTAMP' 	=> 'TIMESTAMP',
				'TINYBLOB' 		=> 'TINYBLOB',
				'TINYINT' 		=> 'TINYINT',
				'TINYTEXT' 		=> 'TINYTEXT',
				'VARBINARY' 	=> 'VARBINARY',
				'YEAR' 			=> 'YEAR',
			);

			// loop to build fields
			for ($count = 1; $count <= $field_total; $count++) :
			?>
			<div<?php echo $count % 2 ? ' class="left"' : ''; ?>>
				<fieldset id="field<?php echo $count; ?>_details">
					<legend><?php echo lang('mb_form_field_details'); ?> <?php echo $count; ?></legend>

					<?php if ($count == 1) : ?>
					<div class="alert alert-info fade in" style="width:80%; margin: 0 auto;">
						<a class="close" data-dismiss="alert">&times;</a>
						<?php echo lang('mb_field_note'); ?>
					</div>
					<?php endif; ?>

					<div class="control-group <?php echo form_has_error("view_field_label{$count}") ? 'error' : ''; ?>">
						<label class="control-label" for="view_field_label<?php echo $count; ?>"><?php echo lang('mb_form_label'); ?></label>
						<div class="controls">
							<input name="view_field_label<?php echo $count; ?>" id="view_field_label<?php echo $count; ?>" type="text" value="<?php echo set_value("view_field_label{$count}", isset($existing_table_fields[$count]) ? ucwords(str_replace("_", " ", $existing_table_fields[$count]['name'])) : ''); ?>" placeholder="<?php echo lang('mb_form_label_ph'); ?>" />
							<span class="help-inline"><?php echo form_error("view_field_label{$count}"); ?></span>
						</div>
					</div>

					<div class="control-group <?php echo form_has_error("view_field_name{$count}") ? 'error' : ''; ?>">
						<label class="control-label" for="view_field_name<?php echo $count; ?>"><?php echo lang('mb_form_fieldname'); ?></label>
						<div class="controls">
							<input name="view_field_name<?php echo $count; ?>" id="view_field_name<?php echo $count; ?>" type="text" value="<?php echo set_value("view_field_name{$count}", isset($existing_table_fields[$count]) ? $existing_table_fields[$count]['name'] : ''); ?>" maxlength="30" placeholder="<?php echo lang('mb_form_fieldname_ph'); ?>" />
							<span class="help-inline"><?php
                                echo form_has_error("view_field_name{$count}") ? form_error("view_field_name{$count}") . '<br />' : '';
                                echo lang('mb_form_fieldname_help');
                            ?></span>
						</div>
					</div>

					<?php
					$default_field_type = 'INPUT';

					if (isset($existing_table_fields[$count]))
					{
						switch ($existing_table_fields[$count]['type'])
						{
							case 'TEXT':
								$default_field_type = 'textarea';
								break;

							case 'ENUM':
							case 'SET':
								$default_field_type = 'select';
								break;

							case 'TINYINT':
								$default_field_type = 'checkbox';
								break;

							default:
								break;
						}
					}

					echo form_dropdown("view_field_type{$count}", $view_field_types, set_value("view_field_type{$count}", $default_field_type), lang('mb_form_type'), '', '<span class="help-inline">' . form_error("view_field_type{$count}") . '</span>');
					echo form_dropdown("db_field_type{$count}", $db_field_types, set_value("db_field_type{$count}", isset($existing_table_fields[$count]) ? $existing_table_fields[$count]['type'] : ''), lang('mb_form_dbtype'), '', '<span class="help-inline">' . form_error("db_field_type{$count}") . '</span>');

					$default_max_len = '';
					if (isset($existing_table_fields[$count])
						&& $existing_table_fields[$count]['type'] != 'TEXT'
						&& $existing_table_fields[$count]['type'] != 'MEDIUMTEXT'
						&& $existing_table_fields[$count]['type'] != 'LONGTEXT'
						)
					{
						$default_max_len = ($existing_table_fields[$count]['type'] == 'ENUM' || $existing_table_fields[$count]['type'] == 'SET') ? $existing_table_fields[$count]['values'] : $existing_table_fields[$count]['max_length'];
					}
					?>

					<div class="control-group <?php echo form_has_error("db_field_length_value{$count}") ? 'error' : ''; ?>">
						<label class="control-label" for="db_field_length_value<?php echo $count; ?>"><?php echo lang('mb_form_length'); ?></label>
						<div class="controls">
							<input name="db_field_length_value<?php echo $count; ?>" id="db_field_length_value<?php echo $count; ?>" type="text" value="<?php echo set_value("db_field_length_value{$count}", $default_max_len); ?>" placeholder="<?php echo lang('mb_form_length_ph'); ?>" />
							<span class="help-inline"><?php echo form_error("db_field_length_value{$count}"); ?></span>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label" id="validation_label<?php echo $count; ?>"><?php echo lang('mb_form_rules'); ?></label>
						<div class="controls" aria-labelledby="validation_label<?php echo $count; ?>" role="group">
							<?php echo form_error('cont_validation_rules' . $count . '[]'); ?>

							<?php foreach ($validation_rules as $validation_rule) : ?>
							<span class="faded">
								<label class="inline checkbox" for="validation_rules_<?php echo $validation_rule . $count; ?>">
									<input name="validation_rules<?php echo $count; ?>[]" id="validation_rules_<?php echo $validation_rule . $count; ?>" type="checkbox" value="<?php echo $validation_rule; ?>" <?php echo set_checkbox('validation_rules' . $count . '[]', $validation_rule); ?> />
									<?php echo lang('mb_form_' . $validation_rule); ?>
								</label>
							</span>
							<?php endforeach; ?>
							<a class="small mb_show_advanced_rules" href="#"><em><?php echo lang('mb_form_show_more'); ?></em></a>
						</div>
					</div>

					<div class="control-group mb_advanced">
						<label class="control-label" id="validation_limit_label<?php echo $count; ?>"><?php echo lang('mb_form_rules_limits'); ?></label>
						<div class="controls" aria-labelledby="validation_limit_label<?php echo $count; ?>" role="group">
							<?php foreach ($validation_limits as $validation_limit) : ?>
							<span class="faded">
								<label class="inline radio" for="validation_rules_<?php echo $validation_limit . $count; ?>">
									<input name="validation_rules<?php echo $count; ?>[]" id="validation_rules_<?php echo $validation_limit . $count; ?>" type="radio" value="<?php echo $validation_limit; ?>" <?php echo set_radio('validation_rules' . $count . '[]', $validation_limit); ?> />
									<?php echo lang('mb_form_' . $validation_limit); ?>
								</label>
							</span>
							<?php endforeach; ?>
						</div>
					</div>

				</fieldset>
			</div>
		<?php endfor; ?>
		</div><!-- /#all_fields -->
		<div class="form-actions">
			<?php
			if ($writeable):
				echo form_submit('build', lang('mb_form_build'), 'class="btn btn-primary"');
			endif;
			?>
		</div>
	<?php echo form_close(); ?>
</div><!-- /.admin-box -->