var ci_profiler_bar = {

	// current toolbar section thats open
	current: null,
	
	// current vars and config section open
	currentvar: null,
	
	// current config section open
	currentli: null,
	
	// toggle a toolbar section
	show : function(obj, el) {
		if (obj == ci_profiler_bar.current) {
			ci_profiler_bar.off(obj);
			ci_profiler_bar.current = null;
		} else {
			ci_profiler_bar.off(ci_profiler_bar.current);
			ci_profiler_bar.on(obj);
			ci_profiler_bar.remove_class(ci_profiler_bar.current, 'current');
			ci_profiler_bar.current = obj;
			//ci_profiler_bar.add_class(el, 'current');
		}
	},
	
	// turn an element on
	on : function(obj) {
		if (document.getElementById(obj) != null)
			document.getElementById(obj).style.display = '';
	},
	
	// turn an element off
	off : function(obj) {
		if (document.getElementById(obj) != null)
			document.getElementById(obj).style.display = 'none';
	},
	
	// toggle an element
	toggle : function(obj) {
		if (typeof obj == 'string')
			obj = document.getElementById(obj);
			
		if (obj)
			obj.style.display = obj.style.display == 'none' ? '' : 'none';
	},
	
	// close the toolbar
	close : function() {
		document.getElementById('codeigniter-profiler').style.display = 'none';
	},
	
	// Add class to element
	add_class : function(obj, my_class) {
		alert(obj);
		document.getElementById(obj).className += " "+ my_class;
	},
	
	// Remove class from element
	remove_class : function(obj, my_class) {
		if (obj != undefined) {
			document.getElementById(obj).className = document.getElementById(obj).className.replace(/\bmy_class\b/, '');
		}
	}
};
