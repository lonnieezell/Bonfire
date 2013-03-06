$(document).ready(function() {
	$("#tabs").tabs({
		beforeLoad: function(event, ui) {
			ui.jqXHR.error(function() {
				ui.panel.html( "Couldn't load this tab. We'll try to fix this as soon as possible." );
			});
		}
		,load: function(event, ui) {
			$(ui.panel).delegate('.pagination a', 'click', function(event) {
				$(ui.panel).load(this.href);
				event.preventDefault();
			});
		}
	});
});