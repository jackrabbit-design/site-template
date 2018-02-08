<?php
/*
    Add helper functions, methods, and classes here. Helphers are for templating and not for adjusting body_class and functionaility.  
    
    TABLE OF CONTENTS
    ******************
    EASY PRINTR()
    GET POST SLUG
    EXCERPT LIMITER 
    TAG WRAP
    CLEAN FUNCTION
    JRD_IMG

*/

/* ========================================================================= */
/* EASY PRINTR() */
/* ========================================================================= */

function printr($var){ echo '<pre>'; print_r($var); echo '</pre>'; };


/* ========================================================================= */
/*  GET POST SLUG */
/* ========================================================================= */

function get_the_slug(){
    global $post;
    $slug = $post->post_name;
    return $slug;
}
function the_slug(){
    echo get_the_slug();
}

/* Use the tag below when querying the slug of a post.

<?php the_slug(); ?> */


/* ========================================================================= */
/*  EXCERPT LIMITER */
/* ========================================================================= */

function limit_excerpt($string, $word_limit) {
    $words = explode(' ', $string);
    return implode(' ', array_slice($words, 0, $word_limit));
}

/* Example Usage:

    Solution 1:
    <?php $excerpt = limit_excerpt(get_the_excerpt(), '50'); ?>
    <?php echo $excerpt . '...' ?>

    Solution 2:
    <?php echo limit_excerpt(get_the_excerpt(), '50'); ?>

*/


/* ========================================================================= */
/* TAG WRAP - No more empty tags*/
/* ========================================================================= */

function tag_wrap($f,$t){
    if($f){
        $r = "<{$t}>{$f}";
        $e = explode(' ',$t);
        $e = $e[0];
        $r .= "</{$e}>";
        return $r;
    }
}

/* Example Usage:

    echo tag_wrap(get_field('feild_name'), 'h3 class="something"');
    output: <h3 class="something">[contents]</h3>

*/



/* REPLACE CLEAN */

/* ========================================================================= */
/* CLEAN FUNCTION - Helpful making better hash links out of repeating fields. */
/* ========================================================================= */

function clean($string) {
    $string = strip_tags($string);
    $string = strtolower($string);
    $string = str_replace(' ', '-', $string);
    return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
}

/* REPLACE CLEAN */


/* ========================================================================= */
/* JRD_IMG  Prints out all the things.
/* ========================================================================= */
/*
/*
    $field          = field name
    $size           = size of image from image array; leave blank to retrieve full url
    $sub            = if true it will use get_sub_field();
    $classes        = string of class/es; default blank 
    $id             = string of an id; default blank
    $data           = array of data attributes. 

    echo jrd_img('img', 'large', false, '', 'this')
*/

function jrd_img( $field, $size, $sub = false, $classes, $id, $data = array()) {
    $img_src = ($sub == true) ? get_sub_field( $field ) : get_field( $field );
    if($img_src) {
        $my_classes = ($classes != '') ? ' class="'.$classes.'" ' : ' ';
        $my_id = ($id != '') ? ' id="'.$id.'" ' : ' ';
        //fields from images
        $img_url = ($size != '') ? $img_src['sizes'][$size] : $img_src['url'];
        $img_alt = $img_src['alt'];
        $img_title = $img_src['title'];
        //data attributes
        $my_data = '';
        if($data) {
            foreach($data as $key => $value) {
                $my_data .= 'data-'.$key.'="'.$value.'" ';
            }
        }

        $img = '<img'.$my_id.$my_classes.'src="'.$img_url.'" alt="'.img_alt.'" '.$my_data.'/>';

        return $img; 
    }
}

