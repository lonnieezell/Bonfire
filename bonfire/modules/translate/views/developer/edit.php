<div class="admin-box">
	<?php
	if (isset($orig) && is_array($orig) && count($orig)) :
		echo form_open(current_url(), 'class="form-horizontal" id="translate_form"');
	?>
		<input type="hidden" name="trans_lang" value="<?php e($trans_lang); ?>" />
		<fieldset>
			<legend><h3><?php echo lang('tr_translate_file') . ": <span style='letter-spacing: -0.03em;'>{$lang_file}</span>"; ?></h3>
				<div class="row-fluid">
				<?php if($orig_lang != $trans_lang) : ?>
				   <div class="span2 pull-left column-check"><label><input class="check-all-entries" type="checkbox" /></label></div>
				<?php endif; ?>
				<?php if (count($orig)>30) : ?>
				   <div class="span3 pull-right text-right"><button class="gobottom btn btn-small"><i class="icon icon-arrow-down"></i></button></div>
				<?php endif; ?>
				</div>
			</legend>

			<div class="entries">
			<?php foreach ($orig as $key => $val) : ?>
			<div class="control-group">
				<?php if($orig_lang != $trans_lang) : ?>
				<div class="column">
					<input type="checkbox" name="checked[]" value="<?php echo $key; ?>" <?php echo in_array($key,$chkd)?"checked=checked":""; ?> />
				</div><?php endif; ?><label class="control-label" for="lang<?php echo $key; ?>"><?php echo $val; ?></label>
				<div class="controls">
			<?php if (strlen($val)<80) : ?>
				<input type="text" class="input-xxlarge" name="lang[<?php echo $key; ?>]" id="lang<?php echo $key; ?>" value="<?php echo isset($new[$key]) ? $new[$key] : ''; ?>" />
			<?php else : ?>
				<textarea class="input-xxlarge" name="lang[<?php echo $key; ?>]" id="lang<?php echo $key; ?>"><?php echo isset($new[$key]) ? $new[$key] : ''; ?></textarea>
			<?php endif; ?>
				</div>
			</div>
			<?php endforeach; ?>
			</div>
			<div class="row">
			<?php if($orig_lang != $trans_lang) : ?>
				   <div class="span6">
					   <?php echo lang('bf_with_selected');	?> 
					<input type="submit" name="translate" class="btn translate-sel" value="<?php echo lang('tr_translate'); ?>" />
				   </div>
			<?php endif; ?>
			<?php if (count($orig)>30) : ?>
				   <div class="span3 pull-right text-right"><button class="gotop btn btn-small"><i class="icon icon-arrow-up"></i></button></div>
			<?php endif; ?>
			</div>
		</fieldset>
		<fieldset class="form-actions">
			<input type="submit" name="save" class="btn btn-primary" value="<?php e(lang('bf_action_save')) ?>" /> <?php e(lang('bf_or')) ?>
			<a href="<?php
				echo site_url(SITE_AREA . '/developer/translate/index') . '/';
				e($trans_lang);
			?>"><?php e(lang('bf_action_cancel'), 'class=""'); ?></a>
		</fieldset>
	<?php
		echo form_close();
	endif;
	?>
</div>