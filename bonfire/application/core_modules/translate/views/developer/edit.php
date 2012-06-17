<style>
form .controls { margin-left: 43% !important; }
form label { width: 40% !important; }
form .form-actions { padding-left: 43% !important; }
</style>

<div class="admin-box">
	<h3><?php echo lang('tr_language') .': '. ucfirst($trans_lang) ?></h3>

<?php if (isset($orig) && is_array($orig) && count($orig)) : ?>

	<?php echo form_open(current_url() .'?lang='. htmlentities($trans_lang) .'&file='. $lang_file, 'class="form-horizontal"'); ?>
		<input type="hidden" name="trans_lang" value="<?php echo $trans_lang; ?>" />
		
		<fieldset>
			<legend><?php echo lang('tr_translate_file') .": $lang_file"  ?></legend>
	
		<?php foreach ($orig as $key => $val) : ?>
		<div class="control-group">
			<?php echo form_simple_label('lang['.$key.']', $val); ?>
			<div class="controls">
				<input type="text" class="input-xxlarge" name="lang[<?php echo $key ?>]" value="<?php echo isset($new[$key]) ? $new[$key] : $val ?>" />
			</div>
		</div>
		<?php endforeach; ?>
		
		<div class="form-actions">
			<input type="submit" name="submit" class="btn btn-primary" value="<?php e(lang('bf_action_save_edit')) ?>" /> <?php echo lang('bf_or'); ?> 
			<?php echo anchor(SITE_AREA .'/developer/translate?lang='. $trans_lang, '<i class="icon-refresh icon-white">&nbsp;</i>&nbsp;' . lang('bf_action_cancel'), 'class="btn btn-warning"'); ?>
		</div>
		</fieldset>
	</form>

<?php else : ?>

<?php endif; ?>
</div>