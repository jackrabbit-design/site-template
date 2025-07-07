// jshint esversion: 6
/* ========================================================================= */
/* BE SURE TO COMMENT CODE/IDENTIFY PER PLUGIN CALL */
/* ========================================================================= */

jQuery(function($){

	// ALERT
	if(Cookies.get('alert_cookie') != 'hide'){
		$('#alert').css('display','block');
	}
	$('#alert .close').on('click',function(){
		$('#alert').slideUp(100);
		Cookies.set('alert_cookie', 'hide', { expires: 1, path: '/' });
    });

	// TOGGLE MENU
	$("#menu-toggle").on('click', function () {
		$("#header").toggleClass("active");
        $(this).toggleClass("active");
        $('#main-nav').focus();
    });

    $('.orphan').unrunt();

    // PARALLAX
    $.fn.plax = function(x, y){
        this.css({
            'webkitTransform' : 'translate3d('+x+'px, '+y+'px, 0)',
            'MozTransform'    : 'translate3d('+x+'px, '+y+'px, 0)',
            'msTransform'     : 'translateX('+x+'px) translateY('+y+'px)',
            'OTransform'      : 'translate3d('+x+'px, '+y+'px, 0)',
            'transform'       : 'translate3d('+x+'px, '+y+'px, 0)'
        });
    };


    // ARIA walker nav menu interactions
    $("li.menu-item-has-children .menu-toggle-button").on("keydown", function(e){
		/* Enter || Spacebar */
		if ( e.keyCode == 13 || e.keyCode == 32 ) {
			e.preventDefault();
			$(this).parent().toggleClass('open');
			if($(this).parent().hasClass('open')){
				$(this).attr('aria-expanded','true');
				$(this).parent().find('.sub-menu').attr('aria-hidden','false');
			} else {
				$(this).attr('aria-expanded','false');
				$(this).parent().find('.sub-menu').attr('aria-hidden','true');
			}
		}
        /* Esc */
		if ( e.keyCode == 27 ) {
			if($(this).parent().hasClass('open')){
				$(this).parent().toggleClass('open');
				$(this).attr('aria-expanded','false');
				$(this).parent().find('.sub-menu').attr('aria-hidden','true');
			}
		}
	});

	/* Esc */
	$("li.menu-item-has-children .sub-menu-wrap a").on("keydown", function(e){
		if ( e.keyCode == 27 ) {
			//e.preventDefault();
			$(this).parents('li.menu-item-has-children').toggleClass('open');
			$(this).attr('aria-expanded','false');
			$(this).parents('li.menu-item-has-children').find('.sub-menu-wrap').attr('aria-hidden','true');
			$(this).parents('li.menu-item-has-children').find('.menu-toggle-button').focus();
		}
	});

	$('li.menu-item-has-children .sub-menu-wrap *').on('focusout', function() {
		setTimeout(function() {
		  if ( ! $(this).find(':focus').length ) {
			$(this).parents('li.menu-item-has-children').toggleClass('open');
			$(this).attr('aria-expanded','false');
			$(this).parents('li.menu-item-has-children').find('.sub-menu-wrap').attr('aria-hidden','true');
		  }
		}, 0);
	});

	// Email address copy script
	$('a[href^="mailto:"]').filter(function() {
  		return $(this).closest('.social-nav').length === 0;
	}).append('<span class="copy-email"></span>');

	$('a .copy-email').on('click', function(e){
		e.stopPropagation();
		$span = $(this);
		let email = $span.parent('a').attr('href').replace(/^mailto:([\w\d\.\-]+@[\w\d\.\-]+\.[\w]+).*$/, '$1');
		var $temp = $("<input>");
		$("body").append($temp);
		$temp.val(email).select();
		document.execCommand("copy");
		$temp.remove();
		$span.addClass('copied');
		setTimeout(function(){
			$span.removeClass('copied');
		}, 100);
		return false;
	});

    /*
    $(document).scroll(function(){
        var nm = $("html").scrollTop();
        var nw = $("body").scrollTop();
        var n = (nm > nw ? nm : nw);

        $('#element').plax(0,n);

        // if transform3d isn't available, use top over background-position
        //$('#element').css('top', Math.ceil(n/2) + 'px');

    });
    */

    /*
    $.ajax({
        type: "POST",
        url: jrd.ajax_url,
        data: {
            action : 'my_function_name',
            foo : variableName
        },
        success:function(data) {
            // do stuff with data var
        },
        error: function(errorThrown){
            console.log("ERROR");
            console.log(errorThrown); // error
        }
    });
    */


});

// UNRUNT
jQuery.fn.unrunt = function(){
    jQuery(this).each(function(){
        var u = unruntify( jQuery(this).html() );
        jQuery(this).html( u );
    });
};

function unruntify( str ){
    var pieces = str.trim().split(' ');
    var new_pieces = [];
    pieces.forEach(function(x,i){
        if ( x.indexOf('<') == 0 && x.indexOf('>') == -1 ) {
            var element = x;
            var remove_items = [];
            for( ++i; i < pieces.length; i++ ) {
                element += ' ' + pieces[i];
                remove_items.push(i-1);
                if ( pieces[i].indexOf('>') != -1 ) {
                    remove_items.forEach(function(x){ // jshint ignore:line
                        pieces.splice(x, 1);
                    });
                    break;
                }
            }
            new_pieces.push(element);
        } else {
            new_pieces.push(x);
        }
    });
    if (new_pieces.length > 1) {
        new_pieces[new_pieces.length-2] += "&nbsp;" + new_pieces[new_pieces.length-1];
        new_pieces.pop();
    }
    return new_pieces.join(' ');
}

// QUERIFY
// usage: $('form').querify();
// returns URL query string, e.g., ?foo=bar&baz=qux
jQuery.fn.querify = function(){
    if ( jQuery(this).length ) {
        var serial = jQuery(this).serialize().replace(/[\w\d-]+=&/g, '');
        serial = '?' + serial;
        serial = serial.replace(/[?&][\w\d-]+=$/g, '');
        return serial;
    }
}

jQuery.fn.extend({showModal: function() {
    return this.each(function() {
        if(this.tagName=== "DIALOG"){
            this.showModal();
        }
    });
}});
jQuery.fn.extend({close: function() {
    return this.each(function() {
        if(this.tagName=== "DIALOG"){
            this.close();
        }
    });
}});
/*
* To use with <dialog> elements, use the following:
* $('dialog').showModal(); // to open
* $('dialog').close(); // to close
*/