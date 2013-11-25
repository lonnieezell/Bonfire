<?php

$country_name = FALSE;
$state_name = FALSE;
$defaultCountry = 'US';
$defaultState = '';

?>
		<?php foreach ($meta_fields as $field):?>
			<?php if ((isset($field['admin_only']) && $field['admin_only'] === TRUE && isset($current_user) && $current_user->role_id == 1)
						|| !isset($field['admin_only']) || $field['admin_only'] === FALSE): ?>
			<?php
			if (!isset($frontend_only) || ($frontend_only === TRUE && (!isset($field['frontend']) || $field['frontend'] === TRUE))):

				if ($field['form_detail']['type'] == 'dropdown'):

					echo form_dropdown($field['form_detail']['settings'], $field['form_detail']['options'], set_value($field['name'], isset($user->$field['name']) ? $user->$field['name'] : ''), $field['label']);


			elseif ($field['form_detail']['type'] == 'checkbox'): ?>

					<div class="control-group <?php echo iif( form_error($field['name']) , 'error'); ?>">
					<label class="control-label" for="<?php echo $field['name'] ?>"><?php echo $field['label'];?></label>
					<div class="controls">
						<?php
						$form_method = 'form_' . $field['form_detail']['type'];
						$checked = (isset($user->$field['name']) && $field['form_detail']['value'] == set_value($field['name'], isset($user->$field['name']) ? $user->$field['name'] : '')) ? TRUE : FALSE;
						echo form_checkbox($field['form_detail']['settings'], $field['form_detail']['value'], $checked);
						?>
					</div>
				</div>


			<?php elseif ($field['form_detail']['type'] == 'state_select' && is_callable('state_select')) :
                $state_name = $field['name'];
                $stateValue = isset($user->$field['name']) ? $user->$field['name'] : $defaultState;
            ?>
				<div class="control-group <?php echo iif( form_error($field['name']) , 'error'); ?>">
						<label class="control-label" for="<?php echo $field['name'] ?>"><?php echo lang('user_meta_state'); ?></label>
						<div class="controls">
							<?php echo state_select(set_value($field['name'], $stateValue), $defaultState, $defaultCountry, $field['name'], 'span6 chzn-select'); ?>
						</div>
					</div>

            <?php elseif ($field['form_detail']['type'] == 'country_select' && is_callable('country_select')) :
                $country_name = $field['name'];
                $countryValue = isset($user->$field['name']) ? $user->$field['name'] : $defaultCountry;
            ?>

					<div class="control-group <?php echo iif( form_error('country') , 'error'); ?>">
						<label class="control-label" for="country"><?php echo lang('user_meta_country'); ?></label>
						<div class="controls">
							<?php echo country_select(set_value($field['name'], isset($user->$field['name']) ? $user->$field['name'] : $defaultCountry), $defaultCountry, 'country', 'span6 chzn-select'); ?>
						</div>
					</div>

				<?php else:


					$form_method = 'form_' . $field['form_detail']['type'];
					if (is_callable($form_method))
					{
						echo $form_method($field['form_detail']['settings'], set_value($field['name'], isset($user->$field['name']) ? $user->$field['name'] : ''), $field['label']);
					}


				endif;
			endif;
		endif;
    endforeach;
    if ( ! empty($country_name) && ! empty($state_name)) {
        Assets::add_js($this->load->view('country_state_js', array('country_name' => $country_name, 'state_name' => $state_name, 'state_value' => $stateValue, 'country_value' => $countryValue), true), 'inline');
    }
?>