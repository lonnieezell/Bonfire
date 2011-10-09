<?php
	$cur_url = uri_string();
	$tot = $this->uri->total_segments();
	$last_seg = $this->uri->segment( $tot);

	if( is_numeric($last_seg) ) {
		$cur_url = str_replace('/index/'.$last_seg, '', $cur_url);
	}

?>

<style>
#module_form label { font-weight: bold; }
.faded { opacity: .60; }
.faded:hover, .mb_show_advanced:hover, .mb_show_advanced_rules:hover{ opacity: 1; color: black;}
.mb_show_advanced, .mb_show_advanced_rules, .container legend { cursor: pointer; }
.mb_advanced { display: none; }
</style>

<div style="max-width: 800px;">
	<?php if (validation_errors()) : ?>
	<div class="notification error">
		<?php echo validation_errors(); ?>
	</div>
	<?php endif; ?>
	
	<div class="notification information">
		<?php echo lang('mb_form_note'); ?>
	</div>

	<?php if (!$writeable): ?>
	<div class="notification error">
		<p><?php echo lang('mb_not_writeable_note'); ?></p>
	</div>
	<?php endif;?>
	
	<?php if ($this->session->flashdata('error')): ?>
	<div class="top_error"><?php echo $this->session->flashdata('error')?></div>
	<?php endif; ?>
	
	
	<?php if ($form_error): ?>
	<div class="important">
	<h4><?php echo lang('mb_form_errors'); ?></h4>
	</div>
	<?php endif; ?>
	
	<?php echo form_open($cur_url."/index/".$field_total."/", array('id'=>"module_form",'class'=>"constrained ajax-form")); ?>
	<div>  
		<!-- Module Details -->
		<fieldset style="margin-top: 0" id="module_details">
			<legend><?php echo lang('mb_form_mod_details'); ?>
					<em class="mb_show_advanced small"><?php echo lang('mb_form_show_advanced'); ?></em>
			</legend>
			<?php echo form_error("module_name"); ?>
			<?php echo form_error("form_input_delimiters"); ?>
			<?php echo form_error("form_error_delimiters"); ?>
			
			
			
			<div>
				<label for="module_name" class="block"><?php echo lang('mb_form_mod_name'); ?></label>
				<input name="module_name" id="module_name" type="text" value="<?php echo set_value("module_name"); ?>" placeholder="<?php echo lang('mb_form_mod_name_ph'); ?>" />
			</div>
			
			<div class="mb_advanced">
				<label for="module_description" class="block"><?php echo lang('mb_form_mod_desc'); ?></label>
				<input name="module_description" id="module_description" type="text" value="<?php echo set_value("module_description",'Your module description'); ?>" placeholder="<?php echo lang('mb_form_mod_desc_ph'); ?>" />
			</div>
	
			<div class="mb_advanced">
				<label class="block"><?php echo lang('mb_form_contexts'); ?></label>
				
				<input name="contexts[]" id="contexts_public" type="checkbox" value="public" checked="checked" /> <?php echo lang('mb_form_public'); ?>
				<?php foreach (config_item('contexts') as $context) : ?>
					<input name="contexts[]" id="contexts_<?php echo $context; ?>" type="checkbox" value="<?php echo $context ?>" checked="checked" /> <?php echo ucwords($context) ?>
				<?php endforeach; ?>
			</div>
			
			<div class="mb_advanced">
			<?php echo form_error("form_action"); ?>
				<label for="form_action"><?php echo lang('mb_form_actions'); ?></label>
	
				<?php foreach($form_action_options as $action => $label): ?>
				<?php 
				$data = array(
					'name'        => 'form_action[]',
					'id'          => 'form_action_'.$action,
					'value'       => $action,
					'checked'     => 'checked'
					);
					
				echo form_checkbox($data); ?> <?php echo $label;?>
				<?php endforeach;?>
			</div>
	
			<div class="mb_advanced">
				<label for="role_id"><?php echo lang('mb_form_role_id'); ?></label>
					<?php foreach ($roles as $role) { $all_roles[$role['role_id']] = $role['role_name']; }
							echo form_dropdown("role_id", $all_roles, set_value("role_id"),'id="role_id"'); ?>
			 	</select>
			</div>
	
			<div>
				<label for="db_required"><?php echo lang('mb_form_generate'); ?></label>
				<input name="db_required" id="db_required" type="checkbox" value="1" <?php echo set_checkbox("db_required", "1"); ?> class="checkbox" />
			</div>
		</fieldset>
	
		<fieldset style="margin-top: 0" id="db_details">
			<legend><?php echo lang('mb_form_table_details'); ?>
					<em class="mb_show_advanced small"><?php echo lang('mb_form_show_advanced'); ?></em>
			</legend>
			
			<div class="mb_advanced">
				<label for="table_name" class="block"><?php echo lang('mb_form_table_name'); ?></label>
				<input name="table_name" id="table_name" type="text" value="<?php echo set_value("table_name"); ?>" placeholder="<?php echo lang('mb_form_table_name_ph'); ?>" />
			</div>
			
			<div class="mb_advanced">
				<label for="form_input_delimiters" class="block"><?php echo lang('mb_form_delims'); ?></label>
				<input name="form_input_delimiters" id="form_input_delimiters" type="text" value="<?php echo set_value("form_input_delimiters", '<div>,</div>'); ?>" />
			</div>
			
			<div class="mb_advanced">
				<label for="form_error_delimiters" class="block"><?php echo lang('mb_form_err_delims'); ?></label>
				<input name="form_error_delimiters" id="form_error_delimiters" type="text" value="<?php echo set_value("form_error_delimiters", "<span class='error'>,</span>"); ?>" />
			</div>
			
			<div class="mb_advanced">
				<label for="textarea_editor" class="block"><?php echo lang('mb_form_text_ed'); ?></label>
				<?php 
					$textarea_editors = array('' => 'None', 'ckeditor' => 'CKEditor', 'xinha' => 'Xinha');
				?>
				<?php echo form_dropdown("textarea_editor", $textarea_editors, set_value("textarea_editor"),'id="textarea_editor"'); ?>
			</div>
			
			<div class="mb_advanced">
				<label for="use_soft_deletes" class="block"><?php echo lang('mb_form_soft_deletes'); ?></label>
				<?php 
					$truefalse = array('false' => 'False', 'true' => 'True');
				?>
				<?php echo form_dropdown("use_soft_deletes", $truefalse, set_value("use_soft_deletes"),'id="use_soft_deletes"'); ?>
			</div>
			
			<div class="mb_advanced">
				<label for="use_created" class="block"><?php echo lang('mb_form_use_created'); ?></label>
				<?php echo form_dropdown("use_created", $truefalse, set_value("use_created"),'id="use_created"'); ?>
			</div>
			
			<div class="mb_advanced">
				<label for="created_field" class="block"><?php echo lang('mb_form_created_field'); ?></label>
				<input name="created_field" id="created_field" type="text" value="<?php echo set_value("created_field", "created_on"); ?>" />
			</div>
			
			<div class="mb_advanced">
				<label for="use_modified" class="block"><?php echo lang('mb_form_use_modified'); ?></label>
				<?php echo form_dropdown("use_modified", $truefalse, set_value("use_modified"),'id="use_modified"'); ?>
			</div>
			
			<div class="mb_advanced">
				<label for="modified_field" class="block"><?php echo lang('mb_form_modified_field'); ?></label>
				<input name="modified_field" id="modified_field" type="text" value="<?php echo set_value("modified_field", "modified_on"); ?>" />
			</div>
				
			<div class="notification attention">
				<?php echo lang('mb_table_note'); ?>
			</div>
			
			<div>
				<label for="primary_key_field" class="block"><?php echo lang('mb_form_primarykey'); ?></label>
				<input name="primary_key_field" id="primary_key_field" type="text" value="<?php echo set_value("primary_key_field", 'id'); ?>" />
			</div>
		
			<div id="field_numbers">
				<label class="block"><?php echo lang('mb_form_fieldnum'); ?></label> 
				<?php 
				$field_num_count = count($field_numbers);
				for($ndx=0; $ndx < $field_num_count; $ndx++): ?>
				<a href="<?php echo site_url($cur_url."/index/{$field_numbers[$ndx]}"); ?>" <?php if ($field_numbers[$ndx] == $field_total) { echo 'class="current"'; } ?>><?php echo $field_numbers[$ndx]; ?></a><?php echo $ndx < $field_num_count - 1 ? ' | ' : '';?>
				<?php endfor; ?>
			</div>
		</fieldset>
	</div>
	
	
	<div id="all_fields">
		<?php
		for ($count = 1; $count <= $field_total; $count++) : // loop to build fields ?>
		
			<div class="container <?php if ($count % 2): ?>left<?php endif;?>">  
				<fieldset id="field<?php echo $count; ?>_details">
					<legend><?php echo lang('mb_form_field_details'); ?> <?php echo $count; ?></legend>
					
					<?php if ($count == 1) : ?>
						
					<div class="notification information">
						<?php echo lang('mb_field_note'); ?>
					</div>
					
					<?php endif; ?>								
			
					<?php echo form_error("view_field_label{$count}"); ?>
					<?php echo form_error("view_field_name{$count}"); ?>
					<?php echo form_error("view_field_type{$count}"); ?>
			
					<div>
			
					<label for="view_field_label<?php echo $count; ?>"><?php echo lang('mb_form_label'); ?></label>
			
					<input name="view_field_label<?php echo $count; ?>" id="view_field_label<?php echo $count; ?>" type="text" value="<?php echo set_value("view_field_label{$count}"); ?>" placeholder="<?php echo lang('mb_form_label_ph'); ?>" />
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
					<?php echo form_dropdown("view_field_type{$count}", $view_field_types, set_value("view_field_type{$count}"),'id="view_field_type'.$count.'"'); ?>
					</div>
			
					<?php echo form_error("db_field_type{$count}"); ?>
			
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
					<?php echo form_dropdown("db_field_type{$count}", $db_field_types, set_value("db_field_type{$count}"),'id="db_field_type'.$count.'"'); ?>
			
					</div>
			
					<?php echo form_error("db_field_length_value{$count}"); ?>
					
					<div>
					<label for="db_field_length_value<?php echo $count; ?>"><?php echo lang('mb_form_length'); ?></label>
					<input name="db_field_length_value<?php echo $count; ?>" id="db_field_length_value<?php echo $count; ?>" type="text" value="<?php echo set_value("db_field_length_value{$count}"); ?>" placeholder="<?php echo lang('mb_form_length_ph'); ?>" />
					</div>
			
					<div>
					<label><?php echo lang('mb_form_rules'); ?></label>
			
					<?php echo form_error('cont_validation_rules'.$count.'[]'); ?>
					
					<?php foreach ($validation_rules as $validation_rule) : ?>
						<span class="faded"> <input name="validation_rules<?php echo $count; ?>[]" id="validation_rules_<?php echo $validation_rule; ?><?php echo $count; ?>" type="checkbox" value="<?php echo $validation_rule; ?>" <?php echo set_checkbox('validation_rules'.$count.'[]', $validation_rule); ?> /> <?php echo lang('mb_form_'.$validation_rule); ?></span> 
					<?php endforeach; ?>
						<em class="mb_show_advanced_rules small"><?php echo lang('mb_form_show_more'); ?></em>
					</div>
					
					<div class="mb_advanced">
					<label><?php echo lang('mb_form_rules_limits'); ?></label>
					<?php echo lang('mb_form_rules_limit_note'); ?>
					<?php foreach ($validation_limits as $validation_limit) : ?>
						<span class="faded"><input name="validation_rules<?php echo $count; ?>[]" id="validation_rules_<?php echo $validation_limit; ?><?php echo $count; ?>" type="radio" value="<?php echo $validation_limit; ?>" <?php echo set_radio('validation_rules'.$count.'[]', $validation_limit); ?> /> <?php echo lang('mb_form_'.$validation_limit); ?></span> 
					<?php endforeach; ?>
					</div>
				</fieldset>
			</div><!-- end container -->
		<?php endfor; ?>
	</div>
	<div class="submits">
		<br />
	<?php if ($writeable): ?>
		<?php echo form_submit('submit', 'Build the Module'); ?>
	<?php endif;?>
	</div>
	<?php echo form_close()?>
</div>	<!-- /constrained -->