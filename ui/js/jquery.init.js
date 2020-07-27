// jshint esversion: 6
/* ========================================================================= */
/* BE SURE TO COMMENT CODE/IDENTIFY PER PLUGIN CALL */
/* ========================================================================= */

jQuery(function($){

    // ORPHANIZER
    function orphanize(){
        $(".orphan").each(function() {
            let txt = $(this).html().trim().replace('&nbsp;',' ');
            let wordArray = txt.split(" ");
            if (wordArray.length > 1) {
                wordArray[wordArray.length-2] += "&nbsp;" + wordArray[wordArray.length-1];
                wordArray.pop();
                $(this).html(wordArray.join(" "));
            }
        });
    };
    orphanize();

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
