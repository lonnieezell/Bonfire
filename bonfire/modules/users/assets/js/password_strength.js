var PasswordStrength = function() {
	var MULTIPLE_NUMBERS_RE = /\d.*?\d.*?\d/;
	var MULTIPLE_SYMBOLS_RE = /[!@#$%^&*?_~].*?[!@#$%^&*?_~]/;
	var UPPERCASE_LOWERCASE_RE = /([a-z].*[A-Z])|([A-Z].*[a-z])/;
	var SYMBOL_RE = /[!@#\$%^&*?_~]/;

	this.username = null;
	this.password = null;
	this.options = null;
	this.min_password_len = 4;	
	this.score = 0;
	this.status = null;

	this.test = function() {
		this.score = 0;

		if (this.containInvalidMatches()) {
			this.status = "invalid";
		} else {
			
			if (this.options.min_password_len != null) this.min_password_len = this.options.min_password_len;
			
			this.score += this.scoreFor("password_size");
			this.score += this.scoreFor("number");
			this.score += this.scoreFor("numbers");
			this.score += this.scoreFor("symbol");
			this.score += this.scoreFor("symbols");
			this.score += this.scoreFor("uppercase_lowercase");
			this.score += this.scoreFor("numbers_chars");
			this.score += this.scoreFor("numbers_symbols");
			this.score += this.scoreFor("symbols_chars");
			this.score += this.scoreFor("only_chars");
			this.score += this.scoreFor("only_numbers");
			this.score += this.scoreFor("username");
			this.score += this.scoreFor("sequences");
			this.score += this.scoreFor("repetitions");

			if (this.score < 0) {
				this.score = 0;
			}

			if (this.score > 100) {
				this.score = 100;
			}

			if (this.score < 35) {
				this.status = "weak";
			}

			if (this.score >= 35 && this.score < 70) {
				this.status = "good";
			}

			if (this.score >= 70) {
				this.status = "strong";
			}
		}

		return this.score;
	};

	this.scoreFor = function(name) {
		score = 0;

		switch (name) {
			case "password_size":
				if (this.password.length < this.min_password_len) {
					score = -100;
				} else {
					score = this.password.length * this.min_password_len;
				}
				break;
			
			case "number":
				if (this.options.force_numbers != null && this.options.force_numbers === true) {
					if (this.password.match(/\d.*?\d/)) {
						score = 10;
					} else {
						score = -50;
					}
				}
			
				break;

			case "numbers":
				if (this.password.match(MULTIPLE_NUMBERS_RE)) {
					score = 5;
				}
				break;

			case "symbol":
				if (this.options.force_symbols != null && this.options.force_symbols === true) {
					if (this.password.match(/[!@#$%^&*?_~].*?/)) {
						score = 10;
					} else {
						score = -50;
					}
				}
			
				break;

			case "symbols":
				if (this.password.match(MULTIPLE_SYMBOLS_RE)) {
					score = 5;
				}
				break;

			case "uppercase_lowercase":
				if (this.password.match(UPPERCASE_LOWERCASE_RE)) {
					score = 10;
				} else {
					if (this.options.force_mixed_case != null && this.options.force_mixed_case === true)
						score = -50;
				}
				break;

			case "numbers_chars":
				if (this.password.match(/[a-z]/i) && this.password.match(/[0-9]/)) {
					score = 15;
				} else {
					if (this.options.force_numbers != null && this.options.force_numbers === true)
						score = -50;
				}
				break;

			case "numbers_symbols":
				if (this.password.match(/[0-9]/) && this.password.match(SYMBOL_RE)) {
					score = 15;
				} else {
					if ((this.options.force_numbers != null && this.options.force_numbers === true) || 
					 (this.options.force_symbols != null && this.options.force_symbols === true))
						score = -50;
				}
				break;

			case "symbols_chars":
				if (this.password.match(/[a-z]/i) && this.password.match(SYMBOL_RE)) {
					score = 15;
				} else {
					if (this.options.force_symbols != null && this.options.force_symbols === true)
						score = -50;
				}
				
				break;

			case "only_chars":
				if (this.password.match(/^[a-z]+$/i)) {
					score = -15;
				}
				break;

			case "only_numbers":
				if (this.password.match(/^\d+$/i)) {
					score = -15;
				}
				break;

			case "username":
				if (((this.options.use_username == null) || (this.options.use_username != null && this.options.use_username === true))
				&& (this.username && this.username != '')) {
					if (this.password == this.username) {
						score = -100;
					} else if (this.password.indexOf(this.username) != -1) {
						score = -15;
					}
				}
				break;

			case "email":
				if ((this.options.use_email != null && this.options.use_email === true)
				&& (this.email && this.email != '')) {	
					if (this.password == this.email) {
						score = -100;
					} else if (this.password.indexOf(this.email) != -1) {
						score = -15;
					}
				}
				break;

			case "sequences":
				score += -15 * this.sequences(this.password);
				score += -15 * this.sequences(this.reversed(this.password));
				break;

			case "repetitions":
				score += -(this.repetitions(this.password, 2) * 4);
        		score += -(this.repetitions(this.password, 3) * 3);
        		score += -(this.repetitions(this.password, 4) * 2);
				break;
		};
		return score;
	};

	this.isGood = function() {
		return this.status == "good";
	};

	this.isWeak = function() {
		return this.status == "weak";
	};

	this.isStrong = function() {
		return this.status == "strong";
	};

	this.isInvalid = function() {
	  return this.status == "invalid";
	};

	this.isValid = function(level) {
		if(level == "strong") {
			return this.isStrong();
		} else if (level == "good") {
			return this.isStrong() || this.isGood();
		} else {
			return !this.containInvalidMatches();
		}
	};

	this.containInvalidMatches = function() {
		if (!this.exclude) {
			return false;
		}

		if (!this.exclude.test) {
			return false;
		}

		return this.exclude.test(this.password.toString());
	};

	this.sequences = function(text) {
		var matches = 0;
		var sequenceSize = 0;
		var codes = [];
		var len = text.length;
		var previousCode, currentCode;

		for (var i = 0; i < len; i++) {
			currentCode = text.charCodeAt(i);
			previousCode = codes[codes.length - 1];
			codes.push(currentCode);

			if (previousCode) {
				if (currentCode == previousCode + 1 || previousCode == currentCode) {
					sequenceSize += 1;
				} else {
					sequenceSize = 0;
				}
			}

			if (sequenceSize == 2) {
				matches += 1;
			}
		}

		return matches;
	};

	this.repetitions = function(text, size) {
		var count = 0;
  		var matches = {};
		var len = text.length;
		var substring;
		var occurrences;
		var tmpText;

		for (var i = 0; i < len; i++) {
			substring = text.substr(i, size);
			occurrences = 0;
			tmpText = text;

			if (matches[substring] || substring.length < size) {
				continue;
			}

			matches[substring] = true;

			while ((i = tmpText.indexOf(substring)) != -1) {
				occurrences += 1;
				tmpText = tmpText.substr(i + 1);
			};

			if (occurrences > 1) {
				count += 1;
			}
		}

		return count;
	};

	this.reversed = function(text) {
		var newText = "";
		var len = text.length;

		for (var i = len -1; i >= 0; i--) {
			newText += text.charAt(i);
		}

		return newText;
	};
};

PasswordStrength.test = function(username, password) {
	strength = new PasswordStrength();
	strength.username = username;
	strength.password = password;
	strength.test();
	return strength;
};