/**
 * Script to populate State select based on selection in Country select
 */
var addressStates = <?php echo json_encode(config_item('address.states')); ?>,
    countrySelect = '#<?php echo $country_name; ?>',
    setStates = function() {
        var selectedCountry = $(countrySelect).val(),
            selectedOption = '<?php echo $state_value; ?>',
            selectedStates,
            options = '<option value="">Select State</option>';

        if (typeof addressStates[selectedCountry] != 'undefined') {
            selectedStates = addressStates[selectedCountry];
            for (var i in selectedStates) {
                options += '<option value="' + i + '"';
                if (i == selectedOption) {
                    options += ' selected="selected"';
                }
                options += '>' + selectedStates[i] + '</option>';
            }
        } else {
            options = '<option value="-">No State Available</option>';
        }

        $('#<?php echo $state_name; ?>').html(options);
    };

$(countrySelect).change(setStates);

/* Make sure the State select is in sync with the Country select
 * when the page loads, especially for existing users
 */
$(function() {
    setStates();
});