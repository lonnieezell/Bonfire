(function($){
	$.strength = function(username, password, options, callback, email) {
		if (typeof(options) == "function") {
			callback = options;
			options = {};
		} else if (!options) {
			options = {};
		}

		var usernameField = $(username);
		var passwordField = $(password);
		var emailField = $(email);
		var strength = new PasswordStrength();

		strength.exclude = options["exclude"];
		strength.options = options;
		
		callback = callback || $.strength.callback;

		var handler = function(){
			strength.username = $(usernameField).val();

			if ($(usernameField).length == 0) {
				strength.username = username;
			}

			strength.password = $(passwordField).val();

			if ($(passwordField).length == 0) {
				strength.password = password;
			}
			
			strength.email = $(emailField).val();

			if ($(emailField).length == 0) {
				strength.email = email;
			}
			
			strength.test();
			callback(usernameField, passwordField, strength);
		};

		$(usernameField).keydown(handler);
		$(usernameField).keyup(handler);

		$(passwordField).keydown(handler);
		$(passwordField).keyup(handler);
	};

	$.extend($.strength, {
		callback: function(username, password, strength){
			var img = $(password).next("img.strength");

			if (!img.length) {
				$(password).after("<img class='strength'>");
				img = $("img.strength");
			}

			$(img)
				.removeClass("weak")
				.removeClass("good")
				.removeClass("strong")
				.addClass(strength.status)
				.attr("src", $.strength[strength.status + "Image"]);
		},
		weakImage: "/images/weak.png",
		goodImage: "/images/good.png",
		strongImage: "/images/strong.png"
	});
})(jQuery);