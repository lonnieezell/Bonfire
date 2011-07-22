/*
* Placeholder plugin for jQuery
* ---
* Copyright 2010, Daniel Stocks (http://webcloud.se)
* Released under the MIT, BSD, and GPL Licenses.
*/
(function($) {
    function Placeholder(input) {
        this.input = input;
        if (input.attr('type') == 'password') {
            this.handlePassword();
        }
        // Prevent placeholder values from submitting
        $(input[0].form).submit(function() {
            if (input.hasClass('placeholder') && input[0].value == input.attr('placeholder')) {
                input[0].value = '';
            }
        });
    }
    Placeholder.prototype = {
        show : function(loading) {
            // FF and IE saves values when you refresh the page. If the user refreshes the page with
            // the placeholders showing they will be the default values and the input fields won't be empty.
            if (this.input[0].value === '' || (loading && this.valueIsPlaceholder())) {
                if (this.isPassword) {
                    try {
                        this.input[0].setAttribute('type', 'text');
                    } catch (e) {
                        this.input.before(this.fakePassword.show()).hide();
                    }
                }
                this.input.addClass('placeholder');
                this.input[0].value = this.input.attr('placeholder');
            }
        },
        hide : function() {
            if (this.valueIsPlaceholder() && this.input.hasClass('placeholder')) {
                this.input.removeClass('placeholder');
                this.input[0].value = '';
                if (this.isPassword) {
                    try {
                        this.input[0].setAttribute('type', 'password');
                    } catch (e) { }
                    // Restore focus for Opera and IE
                    this.input.show();
                    this.input[0].focus();
                }
            }
        },
        valueIsPlaceholder : function() {
            return this.input[0].value == this.input.attr('placeholder');
        },
        handlePassword: function() {
            var input = this.input;
            input.attr('realType', 'password');
            this.isPassword = true;
            // IE < 9 doesn't allow changing the type of password inputs
            if ($.browser.msie && input[0].outerHTML) {
                var fakeHTML = input[0].outerHTML.replace(/type=(['"])?password\1/gi, 'type=$1text$1');
                this.fakePassword = $(fakeHTML).val(input.attr('placeholder')).addClass('placeholder').focus(function() {
                    input.trigger('focus');
                    $(this).hide();
                });
            }
        }
    };
    var NATIVE_SUPPORT = !!("placeholder" in document.createElement( "input" ));
    $.fn.placeholder = function() {
        return NATIVE_SUPPORT ? this : this.each(function() {
            var input = $(this);
            var placeholder = new Placeholder(input);
            placeholder.show(true);
            input.focus(function() {
                placeholder.hide();
            });
            input.blur(function() {
                placeholder.show(false);
            });

            // On page refresh, IE doesn't re-populate user input
            // until the window.onload event is fired.
            if ($.browser.msie) {
                $(window).load(function() {
                    if(input.val()) {
                        input.removeClass("placeholder");
                    }
                    placeholder.show(true);
                });
                // What's even worse, the text cursor disappears
                // when tabbing between text inputs, here's a fix
                input.focus(function() {
                    if(this.value == "") {
                        var range = this.createTextRange();
                        range.collapse(true);
                        range.moveStart('character', 0);
                        range.select();
                    }
                });
            }
        });
    }
})(jQuery);

if (head.placeholder == false)
{
	$('input[placeholder], textarea[placeholder]').placeholder();
}


//--------------------------------------------------------------------
// !LISTVIEWS
//--------------------------------------------------------------------
$('.list-view .list-item').click(function(){
	var id = $(this).attr('data-id');
	
	// Set current class
	$(this).siblings().removeClass('current');
	$(this).addClass('current');
	
	$.publish('list-view/list-item/click', [id]);
});

//--------------------------------------------------------------------
// !LIST-VIEW SEARCHES
//--------------------------------------------------------------------
$('.panel-header a.list-search').click(function(e){
	e.preventDefault();
	
	$('#search-form').slideToggle(300).children('input').focus();
});

// Do the actual search/filtering
$('#search-form input.list-search').keyup(function(e){
	e.preventDefault();
	
	var term = $(this).val().toLowerCase();
	
	$('.list-item').css('display', 'block');
 
	$('.list-item').each(
		function(intIndex)
		{
			var field = $(this).children('p').text().toLowerCase();
			
			if (field.indexOf(term) == -1)
			{
				$(this).css('display', 'none');
			}
		}
	);

});

//--------------------------------------------------------------------
//  !MISC EFFECTS
//--------------------------------------------------------------------

/* 
	3 second fader
*/
$('.fade-me').delay(3000).fadeOut(500);


/*
	Check all functionality
*/
$(".check-all").click(function(){
	$("table input[type=checkbox]").attr('checked', $(this).is(':checked'));
});

/* Adjust split-view widths */
$('.split-view > div:last-child').css('width', ($(window).width() - 251));