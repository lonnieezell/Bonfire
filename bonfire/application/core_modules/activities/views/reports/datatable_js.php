$.fn.dataTableExt.oPagination.listbox = {
	/*
	 * Function: oPagination.listbox.fnInit
	 * Purpose:  Initalise dom elements required for pagination with listbox input
	 * Returns:  -
	 * Inputs:   object:oSettings - dataTables settings object
	 *		       node:nPaging - the DIV which contains this pagination control
	 *		       function:fnCallbackDraw - draw function which must be called on update
	 */
	"fnInit": function (oSettings, nPaging, fnCallbackDraw) {
		var nInput = document.createElement('select');
		var nPage = document.createElement('span');
		var nOf = document.createElement('span');
		nOf.className = "paginate_of";
		nPage.className = "paginate_page";
		if (oSettings.sTableId !== '') {
			nPaging.setAttribute('id', oSettings.sTableId + '_paginate');
		}
		nInput.style.display = "inline";
		nPage.innerHTML ="<?php echo lang('datatable_page')?>";
		nPaging.appendChild(nPage);
		nPaging.appendChild(nInput);
		nPaging.appendChild(nOf);
		$(nInput).change(function (e) { // Set DataTables page property and redraw the grid on listbox change event.
			window.scroll(0,0); //scroll to top of page
			if (this.value === "" || this.value.match(/[^0-9]/)) { /* Nothing entered or non-numeric character */
				return;
			}
			var iNewStart = oSettings._iDisplayLength * (this.value - 1);
			if (iNewStart > oSettings.fnRecordsDisplay()) { /* Display overrun */
				oSettings._iDisplayStart = (Math.ceil((oSettings.fnRecordsDisplay() - 1) / oSettings._iDisplayLength) - 1) * oSettings._iDisplayLength;
				fnCallbackDraw(oSettings);
				return;
			}
			oSettings._iDisplayStart = iNewStart;
			fnCallbackDraw(oSettings);
		}); /* Take the brutal approach to cancelling text selection */
		$('span', nPaging).bind('mousedown', function () {
			return false;
		});
		$('span', nPaging).bind('selectstart', function () {
			return false;
		});
	},
	
	/*
	 * Function: oPagination.listbox.fnUpdate
	 * Purpose:  Update the listbox element
	 * Returns:  -
	 * Inputs:   object:oSettings - dataTables settings object
	 *		       function:fnCallbackDraw - draw function which must be called on update
	 */
	"fnUpdate": function (oSettings, fnCallbackDraw) {
		if (!oSettings.aanFeatures.p) {
			return;
		}
		var iPages = Math.ceil((oSettings.fnRecordsDisplay()) / oSettings._iDisplayLength);
		var iCurrentPage = Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength) + 1; /* Loop over each instance of the pager */
		var an = oSettings.aanFeatures.p;
		for (var i = 0, iLen = an.length; i < iLen; i++) {
			var spans = an[i].getElementsByTagName('span');
			var inputs = an[i].getElementsByTagName('select');
			var elSel = inputs[0];
			if(elSel.options.length != iPages) {
				elSel.options.length = 0; //clear the listbox contents
				for (var j = 0; j < iPages; j++) { //add the pages
					var oOption = document.createElement('option');
					oOption.text = j + 1;
					oOption.value = j + 1;
					try {
						elSel.add(oOption, null); // standards compliant; doesn't work in IE
					} catch (ex) {
						elSel.add(oOption); // IE only
					}
				}
				spans[1].innerHTML = "&nbsp;&nbsp;<?php echo lang('datatable_of') ?>&nbsp;&nbsp;" + iPages;
			}
		  elSel.value = iCurrentPage;
		}
	}
};

$("#flex_table").dataTable({
		"sDom": 'rt<"top"fpi>',
		"sPaginationType": "listbox",
		"bProcessing": true,
		"bLengthChange": false,
		"iDisplayLength": <?php echo config_item('site.list_limit') ? config_item('site.list_limit') : 15; ?>,
		"aaSorting": [[3,'desc']],
		"bAutoWidth": false,
		"aoColumns": [
			{ "sWidth": "10%" },
			null,
			{ "sWidth": "8em" },
			{ "sWidth": "12em" }
		],
                "oLanguage": {
                    "sProcessing":   "<?php echo lang('sProcessing') ?>",
                    "sLengthMenu":   "<?php echo lang('sLengthMenu') ?>",
                    "sZeroRecords":  "<?php echo lang('sZeroRecords') ?>",
                    "sInfo":         "<?php echo lang('sInfo') ?>",
                    "sInfoEmpty":    "<?php echo lang('sInfoEmpty') ?>",
                    "sInfoFiltered": "<?php echo lang('sInfoFiltered') ?>",
                    "sInfoPostFix":  "<?php echo lang('sInfoPostFix') ?>",
                    "sSearch":       "<?php echo lang('sSearch') ?>",
                    "sUrl":          "<?php echo lang('sUrl') ?>",
                    "oPaginate": {
                        "sFirst":    "<?php echo lang('sFirst') ?>",
                        "sPrevious": "<?php echo lang('sPrevious') ?>",
                        "sNext":     "<?php echo lang('sNext') ?>",
                        "sLast":     "<?php echo lang('sLast') ?>"
                    }
                }             
});