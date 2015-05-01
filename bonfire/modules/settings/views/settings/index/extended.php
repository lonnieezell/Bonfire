<fieldset>
    <legend><?php echo lang('set_option_extended'); ?></legend>
    <?php
    foreach ($extendedSettings as $field) :
        if (empty($field['permission'])
            || $this->auth->has_permission($field['permission'])
        ) :
            $field_control = '';
            switch ($field['form_detail']['type']) {
                case 'dropdown':
                    echo form_dropdown(
                        $field['form_detail']['settings'],
                        $field['form_detail']['options'],
                        set_value($field['name'], isset($settings["ext.{$field['name']}"]) ? $settings["ext.{$field['name']}"] : ''),
                        $field['label']
                    );
                    break;
                case 'checkbox':
                    $field_control = form_checkbox(
                        $field['form_detail']['settings'],
                        $field['form_detail']['value'],
                        isset($settings["ext.{$field['name']}"]) && $field['form_detail']['value'] == $settings["ext.{$field['name']}"]
                    );
                    break;
                case 'state_select':
                    if (! is_callable('state_select')) {
                        $this->load->config('address');
                        $this->load->helper('address');
                    }
                    $stateFieldId = $field['name'];
                    $stateValue = isset($settings["ext.{$field['name']}"]) ? $settings["ext.{$field['name']}"] : $defaultState;
                    $field_control = state_select(
                        set_value($field['name'], $stateValue),
                        $defaultState,
                        $defaultCountry,
                        $field['name'],
                        'span6 chzn-select'
                    );
                    break;
                case 'country_select':
                    if (! is_callable('country_select')) {
                        $this->load->config('address');
                        $this->load->helper('address');
                    }
                    $countryFieldId = $field['name'];
                    $countryValue = isset($settings["ext.{$field['name']}"]) ? $settings["ext.{$field['name']}"] : $defaultCountry;
                    $field_control = country_select(
                        set_value($field['name'], $countryValue),
                        $defaultCountry,
                        $field['name'],
                        'span6 chzn-select'
                    );
                    break;
                default:
                    $form_method = "form_{$field['form_detail']['type']}";
                    if (is_callable($form_method)) {
                        echo $form_method(
                            $field['form_detail']['settings'],
                            set_value($field['name'], isset($settings["ext.{$field['name']}"]) ? $settings["ext.{$field['name']}"] : ''),
                            $field['label']
                        );
                    }
                    break;
            }

            if (! empty($field_control)) :
    ?>
    <div class="control-group<?php echo form_error($field['name']) ? $errorClass : ''; ?>">
        <label class="control-label" for="<?php echo $field['name']; ?>"><?php echo $field['label']; ?></label>
        <div class="controls">
            <?php echo $field_control; ?>
        </div>
    </div>
    <?php
            endif;
        endif;
    endforeach;
    if (! empty($countryFieldId) && ! empty($stateFieldId)) {
        Assets::add_js(
            $this->load->view('users/country_state_js',
                array(
                    'country_name' => $countryFieldId,
                    'country_value' => $countryValue,
                    'state_name' => $stateFieldId,
                    'state_value' => $stateValue,
                ),
                true
            ),
            'inline'
        );
    }
    ?>
</fieldset>
