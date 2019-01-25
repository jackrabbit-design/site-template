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



    /* ====== Twitter API Call =============================================
        This script automatically adds <li> before and after template. Don't forget to setup Auth info in /twitter/index.php */
    /*
    $('#tweets-loading').tweet({
        modpath: '/path/to/twitter/', // only needed if twitter folder is not in root
        username: 'jackrabbits',
        count: 1,
	template: '<p>{text}</p><p class="tweetlink">{time}</p>'
    });
    $('.tweet_text a').each(function(){
        $(this).attr('target','_blank');
    });
    */

});
