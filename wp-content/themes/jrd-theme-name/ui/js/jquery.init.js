// jshint esversion: 6
/* ========================================================================= */
/* BE SURE TO COMMENT CODE/IDENTIFY PER PLUGIN CALL */
/* ========================================================================= */

jQuery(function($){

	// Alert
	if(Cookies.get('alert_cookie') != 'hide'){
		$('#alert').css('display','block');
	}
	$('#alert .close').on('click',function(e){
		e.preventDefault();
		$('#alert').slideUp(100);
		Cookies.set('alert_cookie', 'hide', { expires: 1, path: '/' });
	    return false;
    });

	// Toggle Menu
	$("#menu-toggle").on('click', function () {
		$('html, body').animate({
			scrollTop: 0
		}, 200);
        $("#header").toggleClass("active");
        $("body").stop().toggleClass("no-scroll");
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
$.fn.unrunt = function(){
    $(this).each(function(){
        var u = unruntify( $(this).html() );
        $(this).html( u );
    });
};

function unruntify( str ){
    var pieces = str.split(' ');
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
