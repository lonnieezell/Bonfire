<?php

if ( ! empty($meta_fields)) :
    $defaultCountry = 'US';
    $defaultState   = '';
    $countryFieldId = false;
    $stateFieldId   = false;

    $displayFrontend    = isset($frontend_only) ? $frontend_only : false;
    $userIsAdmin        = isset($current_user) ? ($current_user->role_id == 1) : false;

    foreach ($meta_fields as $field) :
        $adminField = isset($field['admin_only']) ? $field['admin_only'] : false;
        // if this is an admin field and the user is not an admin, skip it
        if ($adminField && ! $userIsAdmin) {
            continue;
        }

        // Unlike the other values, assume true if $field['frontend'] is not set
        $frontField = isset($field['frontend']) ? $field['frontend'] : true;

        // if displaying the front end and this is not a frontend field, skip it
        if ($displayFrontend && ! $frontField) {
            continue;
        }

        if ($field['form_detail']['type'] == 'dropdown') :
            echo form_dropdown($field['form_detail']['settings'], $field['form_detail']['options'], set_value($field['name'], isset($user->$field['name']) ? $user->$field['name'] : ''), $field['label']);

        elseif ($field['form_detail']['type'] == 'checkbox') :
?>
<div class="form-group <?php echo iif( form_error($field['name']) , 'error'); ?>">
    <label class="control-label" for="<?php echo $field['name'] ?>"><?php echo $field['label'];?></label>
    <div class="controls">
        <?php
        $checked = (isset($user->$field['name']) && $field['form_detail']['value'] == set_value($field['name'], isset($user->$field['name']) ? $user->$field['name'] : '')) ? TRUE : FALSE;
        echo form_checkbox($field['form_detail']['settings'], $field['form_detail']['value'], $checked);
        ?>
    </div>
</div>
<?php
        elseif ($field['form_detail']['type'] == 'state_select' && is_callable('state_select')) :
            $stateFieldId = $field['name'];
            $stateValue = isset($user->$field['name']) ? $user->$field['name'] : $defaultState;
?>
<div class="form-group <?php echo iif( form_error($field['name']) , 'error'); ?>">
    <label class="control-label" for="<?php echo $field['name'] ?>"><?php echo lang('user_meta_state'); ?></label>
    <div class="controls col-md-6">
        <?php echo state_select(set_value($field['name'], $stateValue), $defaultState, $defaultCountry, $field['name'], 'form-control chzn-select'); ?>
    </div>
</div>
<?php
        elseif ($field['form_detail']['type'] == 'country_select' && is_callable('country_select')) :
            $countryFieldId = $field['name'];
            $countryValue = isset($user->$field['name']) ? $user->$field['name'] : $defaultCountry;
?>
<div class="form-group <?php echo iif( form_error('country') , 'error'); ?>">
    <label class="control-label" for="country"><?php echo lang('user_meta_country'); ?></label>
    <div class="controls col-md-6">
        <?php echo country_select(set_value($field['name'], isset($user->$field['name']) ? $user->$field['name'] : $defaultCountry), $defaultCountry, 'country', 'form-control chzn-select'); ?>
    </div>
</div>
<?php
        else:
            $form_method = 'form_' . $field['form_detail']['type'];
            if (is_callable($form_method)) {
                echo $form_method($field['form_detail']['settings'], set_value($field['name'], isset($user->$field['name']) ? $user->$field['name'] : ''), $field['label']);
            }
        endif;
    endforeach;
    if ( ! empty($countryFieldId) && ! empty($stateFieldId)) {
        Assets::add_js($this->load->view('country_state_js', array('country_name' => $countryFieldId, 'state_name' => $stateFieldId, 'state_value' => $stateValue, 'country_value' => $countryValue), true), 'inline');
    }
endif;
?>