function confirm_delete(event) {
	var action = event.target.id.replace('delete-', ''),
        which = $('#' + action + '_select option:selected').text(),
        dateFor = 'for';

	if (action.indexOf('date') != -1) {
        dateFor = 'before';
    }

	return confirm('Are you sure you wish to delete the activity logs ' + dateFor + ' "' + which + '"?');
}

/*
 * For a button which has been placed outside the form (due to limitations in
 * supported CSS).
 * No attempt is made to distinguish the submission from e.g. a real submit
 * button on the form.
 * That would probably be acheived by DOM manipulation, but it wouldn't be safe,
 * because some browsers' history mechanisms (quite understandably) preserve the
 * DOM.
 */
function submit_delete(event) {
	var action = event.target.id.replace('delete-', ''),
        form = $('#' + action + '_form');

	if (confirm_delete(event)) {
		form.submit();
	}
}

$('.btn').filter('[id^="delete-"][type="submit"]').click(confirm_delete);
$('.btn').filter('[id^="delete-"][type="button"]').click(submit_delete);

$("#flex_table").dataTable({
    "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
    "sPaginationType": "bootstrap",
    "iDisplayLength": <?php echo ($this->settings_lib->item('site.list_limit')) ? $this->settings_lib->item('site.list_limit') : 15; ?>,
    "bInfo": false,
    "bPaginate": false,
    "bProcessing": true,
    "bServerSide": false,
    "bLengthChange": false,
    "aaSorting": [[3,'desc']],
    "bAutoWidth": false,
    "aoColumns": [
        { "sWidth": "10%" },
        null,
        { "sWidth": "8em" },
        { "sWidth": "12em" }
    ],
    "oLanguage": {
        "sProcessing":   "<?php echo lang('sProcessing'); ?>",
        "sLengthMenu":   "<?php echo lang('sLengthMenu'); ?>",
        "sZeroRecords":  "<?php echo lang('sZeroRecords'); ?>",
        "sInfo":         "<?php echo lang('sInfo'); ?>",
        "sInfoEmpty":    "<?php echo lang('sInfoEmpty'); ?>",
        "sInfoFiltered": "<?php echo lang('sInfoFiltered'); ?>",
        "sInfoPostFix":  "<?php echo lang('sInfoPostFix'); ?>",
        "sSearch":       "<?php echo lang('sSearch'); ?>",
        "sUrl":          "<?php echo lang('sUrl'); ?>",
        "oPaginate": {
            "sFirst":    "<?php echo lang('sFirst'); ?>",
            "sPrevious": "<?php echo lang('sPrevious'); ?>",
            "sNext":     "<?php echo lang('sNext'); ?>",
            "sLast":     "<?php echo lang('sLast'); ?>"
        }
    }
});