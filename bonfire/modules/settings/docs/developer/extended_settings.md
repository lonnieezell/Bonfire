## Extended Settings

Bonfire allows site administrators and/or developers to add extended settings to their site by editing the <tt>extended_settings_fields</tt> in a config file.
The file is located at /application/config/extended_settings.php.

When the site finds settings in this file, it will add a fourth tab to the Site Settings page, titled <b>Extended Settings</b> (the title can be modified in the settings_lang language file under <tt>set_tab_extended</tt>).
The file allows configuration of the settings included on this tab as well as permissions required to view them, the valid values, and the controls used to render them.
When saving the site settings, the extended settings are saved as well in the same location, and the extended settings can be accessed the same way the other settings are accessed.
If the <tt>extended_settings_fields</tt> config array is empty, the tab will not be displayed on the Site Settings page.

### Defining the Fields

#### Example:

The configuration below will add 5 fields to the Extended Settings tab, 3 of which have permission requirements.
Each field is defined in its own array within the <tt>extended_settings_fields</tt> array.

    $config['extended_settings_fields'] = array(
        array(
            'name'	=> 'extended_settings_test',
            'label'	=> 'Test Label',
            'rules'	=> 'trim',
            'form_detail'	=> array(
                'type'	=> 'dropdown',
                'settings'	=> array(
                    'name'	=> 'extended_settings_test',
                    'id'	=> 'extended_settings_test',
                ),
                'options'	=> array(
                    '0'	=> 'Passed',
                    '1'	=> 'Failed',
                ),
            ),
            'permission' => 'This.Shouldnt.ShowUp',
        ),
        array(
            'name'   => 'street_name',
            'label'   => lang('user_meta_street_name'),
            'rules'   => 'trim|max_length[100]',
            'form_detail' => array(
                'type' => 'input',
                'settings' => array(
                    'name'		=> 'street_name',
                    'id'		=> 'street_name',
                    'maxlength'	=> '100',
                    'class'		=> 'span6',
                ),
            ),
            'permission' => 'Site.Settings.View',
        ),
        array(
            'name'   => 'state',
            'label'   => lang('user_meta_state'),
            'rules'   => 'required|trim|max_length[2]',
            'form_detail' => array(
                'type' => 'state_select',
                'settings' => array(
                    'name'		=> 'state',
                    'id'		=> 'state',
                    'maxlength'	=> '2',
                    'class'		=> 'span1'
                ),
            ),
            'permission' => 'Site.Content.View',
        ),
        array(
            'name'   => 'country',
            'label'   => lang('user_meta_country'),
            'rules'   => 'required|trim|max_length[100]',
            'form_detail' => array(
                'type' => 'country_select',
                'settings' => array(
                    'name'		=> 'country',
                    'id'		=> 'country',
                    'maxlength'	=> '100',
                    'class'		=> 'span6'
                ),
            ),
        ),
        array(
            'name'   => 'type',
            'label'   => lang('user_meta_type'),
            'rules'   => 'required',
            'form_detail' => array(
                'type' => 'dropdown',
                'settings' => array(
                    'name'		=> 'type',
                    'id'		=> 'type',
                    'class'		=> 'span6',
                ),
                'options' =>  array(
                    'small'  => 'Small Shirt',
                    'med'    => 'Medium Shirt',
                    'large'   => 'Large Shirt',
                    'xlarge' => 'Extra Large Shirt',
                  ),
            ),
        ),
    );

#### Keys

The valid keys for the array are <tt>name</tt>, <tt>label</tt>, <tt>rules</tt>, <tt>form_detail</tt>, and <tt>permission</tt>.

##### <tt>name</tt>

The name of the setting being defined.

##### <tt>label</tt>

The text displayed in the field's label .

##### <tt>rules</tt>

Validation rules to be applied to this field before saving the data.

##### <tt>form_detail</tt>

This key allows you to setup the control used to display the setting on the form.

##### <tt>permission</tt>

This key allows you to require a specific permission to view and modify the setting.