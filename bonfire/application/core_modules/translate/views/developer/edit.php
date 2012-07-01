<style>
form .controls { margin-left: 43% !important; }
form label { width: 40% !important; }
form .form-actions { padding-left: 43% !important; }
</style>

<div class="admin-box">
	<h3><?php echo lang('tr_language_chosen') .' '. ucfirst($trans_lang); ?></h3>

<?php if (isset($orig) && is_array($orig) && count($orig)) : ?>

	<?php echo form_open(current_url() .'?lang='. htmlentities($trans_lang) .'&file='. $lang_file, 'class="form-horizontal"'); ?>
		<input type="hidden" name="trans_lang" value="<?php echo $trans_lang; ?>" />
		
		<fieldset>
			<legend><?php echo lang('tr_translate_file') .": $lang_file";  ?></legend>
	
		<?php foreach ($orig as $key => $val) : ?>
		<div class="control-group">
			<label class="control-label"><?php e($val) ?></label>
			<div class="controls">
				<input type="text" class="input-xxlarge" name="lang[<?php echo $key; ?>]" value="<?php echo isset($new[$key]) ? $new[$key] : $val; ?>" />
			</div>
		</div>
		<?php endforeach; ?>
		
		<div class="form-actions">
			<input type="submit" name="submit" class="btn btn-primary" value="<?php echo lang('bf_action_save_changes')); ?>" /> <?php echo lang('bf_or'); ?> 
			<a href="<?php echo site_url(SITE_AREA .'/developer/translate?lang='. $trans_lang); ?>" class="btn btn-danger" >
				<?php echo lang('bf_action_cancel'); ?>
			</a>
		</div>
		</fieldset>
	</form>

<?php else : ?>

<?php endif; ?>
</div>