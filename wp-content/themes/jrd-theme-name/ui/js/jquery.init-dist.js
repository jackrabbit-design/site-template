function unruntify(e){var n=e.trim().split(" "),t=[];return n.forEach((function(e,r){if(0==e.indexOf("<")&&-1==e.indexOf(">")){var i=e,a=[];for(++r;r<n.length;r++)if(i+=" "+n[r],a.push(r-1),-1!=n[r].indexOf(">")){a.forEach((function(e){n.splice(e,1)}));break}t.push(i)}else t.push(e)})),t.length>1&&(t[t.length-2]+="&nbsp;"+t[t.length-1],t.pop()),t.join(" ")}jQuery((function(e){"hide"!=Cookies.get("alert_cookie")&&e("#alert").css("display","block"),e("#alert .close").on("click",(function(){e("#alert").slideUp(100),Cookies.set("alert_cookie","hide",{expires:1,path:"/"})})),e("#menu-toggle").on("click",(function(){e("#header").toggleClass("active"),e(this).toggleClass("active"),e("#main-nav").focus()})),e(".orphan").unrunt(),e.fn.plax=function(e,n){this.css({webkitTransform:"translate3d("+e+"px, "+n+"px, 0)",MozTransform:"translate3d("+e+"px, "+n+"px, 0)",msTransform:"translateX("+e+"px) translateY("+n+"px)",OTransform:"translate3d("+e+"px, "+n+"px, 0)",transform:"translate3d("+e+"px, "+n+"px, 0)"})}})),jQuery.fn.unrunt=function(){jQuery(this).each((function(){var e=unruntify(jQuery(this).html());jQuery(this).html(e)}))},jQuery.fn.querify=function(){if(jQuery(this).length){var e=jQuery(this).serialize().replace(/[\w\d-]+=&/g,"");return e=(e="?"+e).replace(/[?&][\w\d-]+=$/g,"")}};