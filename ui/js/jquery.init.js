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

    // UNRUNT
    $.fn.unrunt = function(){
        $(this).each(function(){
            let txt = $(this).html().trim().replace('&nbsp;',' ');
            let wordArray = txt.split(" ");
            if (wordArray.length > 1) {
                wordArray[wordArray.length-2] += "&nbsp;" + wordArray[wordArray.length-1];
                wordArray.pop();
                $(this).html(wordArray.join(" "));
            }
        });
    }
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

function focusIt(){ document.getElementById("jumptocontent").focus(); }
