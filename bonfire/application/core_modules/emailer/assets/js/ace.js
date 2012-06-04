var header = {};

header.editor = ace.edit("header-editor");
header.textarea = $('textarea[name="header"]').hide();

header.editor.setTheme("ace/theme/monokai");
var htmlMode = require("ace/mode/html").Mode;
header.editor.getSession().setMode(new htmlMode());

header.editor.getSession().setValue(header.textarea.val());
header.editor.getSession().on('change', function(){
	header.textarea.val(header.editor.getSession().getValue());
});

var footer = {}

footer.editor = ace.edit("footer-editor");

footer.editor.setTheme("ace/theme/monokai");
footer.editor.getSession().setMode(new htmlMode());

footer.textarea = $('textarea[name="footer"]').hide();

footer.editor.getSession().setValue(footer.textarea.val());
footer.editor.getSession().on('change', function(){
	footer.textarea.val(footer.editor.getSession().getValue());
});
