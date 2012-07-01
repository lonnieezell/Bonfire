<div class="admin-box">
	
	<h3><?php echo $toolbar_title; ?></h3>
	
	<?php echo form_open($this->uri->uri_string(), 'class="form-horizontal"'); ?>

		<fieldset>

			<div class="control-group">
				<label for="trans_lang" class="control-label"><?php echo lang('tr_current_lang'); ?></label>
				<div class="controls">
					<select name="trans_lang" id="trans_lang">
					<?php foreach ($languages as $lang) :?>
						<option value="<?php echo $lang; ?>" <?php echo isset($trans_lang) && $trans_lang == $lang ? 'selected="selected"' : '' ?>><?php echo ucfirst($lang); ?></option>
					<?php endforeach; ?>
						<option value="new"><?php echo lang('tr_new'); ?></option>
					</select>
					
					<input type="text" name="new_lang" id="new_lang" style="display: none" value="" />
				
					<input type="submit" name="select_lang" class="btn btn-small btn-primary" value="<?php echo lang('tr_action_select'); ?>" />
				</div>
			</div>

		</fieldset>

		<!-- Core -->
		<fieldset>
			<legend><?php echo lang('tr_core'); ?></legend>

			<table class="table table-striped">
				<tbody>
				<?php foreach ($lang_files as $file) :?>
					<tr>
						<td>
							<a href="<?php echo site_url(SITE_AREA .'/developer/translate/edit?lang='. $trans_lang .'&file='. $file) ?>">
								<?php echo $file; ?>
							</a>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>

		</fieldset>

		<!-- Modules -->
		<fieldset>
			<legend><?php echo lang('tr_modules'); ?></legend>

			<table class="table table-striped">
				<tbody>
				<?php if (isset($modules) && is_array($modules) && count($modules)) : ?>
				<?php foreach ($modules as $file) :?>
					<tr>
						<td>
							<a href="<?php echo site_url(SITE_AREA .'/developer/translate/edit?lang='. $trans_lang .'&file='. $file) ?>">
								<?php echo $file; ?>
							</a>
						</td>
					</tr>
				<?php endforeach; ?>
				<?php else : ?>
					<tr>
						<td>
							<div class="alert alert-info fade in">
								<a class="close" data-dismiss="alert">&times;</a>		
								<?php echo lang('tr_no_modules'); ?>
							</div>
						</td>
					</tr>
				<?php endif; ?>
				</tbody>
			</table>

		</fieldset>

	<?php echo form_close(); ?>

</div>