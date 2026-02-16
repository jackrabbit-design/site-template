// jshint esversion: 6

/* ========================================================================= */
/* BE SURE TO COMMENT CODE/IDENTIFY PER PLUGIN CALL */
/* ========================================================================= */

jQuery(($) =>{

	// ALERT
	if(Cookies.get('alert_cookie') !== 'hide'){
		$('#alert').css('display','block');
	}
	$('#alert .close').on('click',() => {
		$('#alert').slideUp(100);
		Cookies.set('alert_cookie', 'hide', { expires: 1, path: '/' });
    });

	// ADA Fix for Honeypot fields
	$(".gfield--type-honeypot").attr('aria-hidden','true');
	$(".gfield--type-honeypot input").attr('tabindex','-1');

	// SIMPLE TOGGLE MENU
	$("#menu-toggle").on('click', function () {
		$("#header").toggleClass("active");
        $(this).toggleClass("active");
        $('#main-nav').trigger('focus');
    });

	// ADVANCED TOGGLE MENU (Works with ADA NAV WALKER)
	// $("#menu-toggle").on('click tap', function (e) {
	// 	$("#header").toggleClass("active");
    //     $(this).toggleClass("active");
	// 	if($(this).hasClass('active')){
	// 		$(this).attr('aria-expanded','true');
	// 		$("#main-nav *").on('keydown', function( event ) {
	// 			if (event.keyCode === 27) {
	// 				$("#header").removeClass("active");
	// 				$("#menu-toggle").removeClass("active");
	// 				$("#menu-toggle").attr('aria-expanded','false');
	// 				$("#menu-toggle").trigger('focus');
	// 			}
	// 		});
	//     } else {
	// 		$(this).attr('aria-expanded','false');
	// 		$("#main-nav li").removeClass('active');
	// 		$("#main-nav li button").attr('aria-expanded','false');
	// 		$("#main-nav li .sub-menu").attr('aria-hidden','true');
	//     }
    // });

    /* ADA MAIN NAV MENU SCRIPTS ============================== */

    $("#header #main-nav a[data-link=nonactive]").on('click', (event) => {
        event.preventDefault();
    });
	$("#header #main-nav .menu-item-has-children").on('click', (event) => {
        if( $("#header").hasClass("active") ) {
            if( !$(event.target).is("a") ) {
                $(this).toggleClass("active");
                $(this).children(".sub-menu").slideToggle();
            }
        }
    });

    $("li.menu-item-has-children .menu-toggle-button").on("keydown", function(event){
		/* Enter || Spacebar */
		if ( event.key === ' ' || event.key === 'Enter' ) {
			event.preventDefault();
			$(this).parent().toggleClass('active');
			if($(this).parent().hasClass('active')){
				$(this).attr('aria-expanded','true');
				$(this).parent().find('.sub-menu').attr('aria-hidden','false');
			} else {
				$(this).attr('aria-expanded','false');
				$(this).parent().find('.sub-menu').attr('aria-hidden','true');
			}
		}
		if ( event.key === 'Escape' ) {
			if($(this).parent().hasClass('active')){
				$(this).parent().toggleClass('active');
				$(this).attr('aria-expanded','false');
				$(this).parent().find('.sub-menu').attr('aria-hidden','true');
			}
		}
	});

	/* Esc */
	$("li.menu-item-has-children .sub-menu-wrap a").on("keydown", function(event){
		if ( event.key === 'Escape' ) {
			//e.preventDefault();
			$(this).parents('li.menu-item-has-children').toggleClass('active');
			$(this).attr('aria-expanded','false');
			$(this).parents('li.menu-item-has-children').find('.sub-menu').attr('aria-hidden','true');
			$(this).parents('li.menu-item-has-children').find('.menu-toggle-button').trigger('focus');
		}
	});

	$('li.menu-item-has-children .sub-menu-wrap').on('focusout', function() {
		setTimeout(() => {
		  if (!$(this).find(':focus').length) {
			$(this).parents('li.menu-item-has-children').toggleClass('active');
			//$(this).attr('aria-expanded','false');
			$(this).parents('li.menu-item-has-children').find('.sub-menu').attr('aria-hidden','true');
		  }
		}, 0);
	});

    $('.orphan').unrunt();

    // PARALLAX
    $.fn.plax = function(x, y){
        this.css({
            'webkitTransform' : `translate3d(${x}px, ${y}px, 0)`,
            'MozTransform'    : `translate3d(${x}px, ${y}px, 0)`,
            'msTransform'     : `translateX(${x}px) translateY(${y}px)`,
            'OTransform'      : `translate3d(${x}px, ${y}px, 0)`,
            'transform'       : `translate3d(${x}px, ${y}px, 0)`
        });
    };

    // Email address copy script
    $('a[href^="mailto:"]').filter((_, el) => {
        return $(el).closest('.social-nav').length === 0;
    }).append('<span class="copy-email"></span>');

    $('a .copy-email').on('click', (e) => {
        e.stopPropagation();
        const $span = $(e.currentTarget).addClass('copied');
        const email = $span.closest('a').attr('href').replace(/^mailto:([\w\d.-]+@[\w\d.-]+\.[\w]+).*$/, '$1');
        navigator.clipboard.writeText(email);
        setTimeout(() => {
            $span.removeClass('copied');
        }, 450);
        return false;
    });

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
    pieces.forEach((x,i) => {
        if ( x.indexOf('<') === 0 && x.indexOf('>') === -1 ) {
            let element = x;
            const remove_items = [];
            for( ++i; i < pieces.length; i++ ) {
                element += ` ${pieces[i]}`;
                remove_items.push(i-1);
                if ( pieces[i].indexOf('>') !== -1 ) {
                    remove_items.forEach((x) => { // jshint ignore:line
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
        new_pieces[new_pieces.length-2] += `&nbsp;${new_pieces[new_pieces.length-1]}`;
        new_pieces.pop();
    }
    return new_pieces.join(' ');
}

// QUERIFY
// usage: $('form').querify();
// returns URL query string, e.g., ?foo=bar&baz=qux
jQuery.fn.querify = function(){
    if ( jQuery(this).length ) {
        let serial = jQuery(this).serialize().replace(/[\w\d-]+=&/g, '');
        serial = `?${serial}`;
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

// Initialize Lenis on non-touch devices
if ( ! jQuery('body').hasClass('wp-admin') ) {
    if ( !('ontouchstart' in window || navigator.maxTouchPoints) ) {
        new Lenis({
            autoRaf: true,
        });
    }
}