<?php 

/* Remove Default Wordpress Widgets */
function my_unregister_widgets() {
	unregister_widget( 'WP_Widget_Pages' );
	unregister_widget( 'WP_Widget_Calendar' );
	unregister_widget( 'WP_Widget_Archives' );
	unregister_widget( 'WP_Widget_Links' );
	unregister_widget( 'WP_Widget_Categories' );
	unregister_widget( 'WP_Widget_Recent_Posts' );
	unregister_widget( 'WP_Widget_Search' );
	unregister_widget( 'WP_Widget_Tag_Cloud' );
	unregister_widget( 'WP_Widget_Meta' );
	unregister_widget( 'WP_Widget_Recent_Comments' );
	unregister_widget( 'WP_Widget_RSS' );

}
add_action( 'widgets_init', 'my_unregister_widgets' );


/* Register Widget Sidebars */
if ( function_exists('register_sidebar') )
    register_sidebar(array(
    	'name' => 'Sidebar',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => '',
    ));




/* Register Custom Widgets */

/*
// Sample Custom Widget
class Blog_Tags extends WP_Widget {

    function Blog_Tags() {
        $widget_opts = array('classname' => 'Blog Tags', 'description' => __('Adds Blog Tag List'));
        $control_opts = array('width' => 400, 'height' => 125);
        $this->WP_Widget(__CLASS__, __('Blog Tags'), $widget_opts, $control_opts);
    }

    function widget($args, $instance) {
        extract($args);
        global $post;
                
        // Before widget
        echo $before_widget; 
        // Declare Variables
        $title = $instance['title'];
        $num = $instance['num'];
        if(Empty($num)){ $num = 50; }
        
        // Widget HTML
        ?>
            <div class="block block-tags">                    	
                <h4><?php echo $title; ?></h4>                       
                <div class="block-content">
                    <?php wp_tag_cloud( array('taxonomy' => 'blog-tags', 'format' => 'list', 'smallest' => 11, 'largest' => 11, 'separator' => '', 'unit' => 'px', 'number' => $num) ); ?>
                </div>
            </div>
        
        <?php // After widget
        
        echo $after_widget;
        
        wp_reset_query();
    }

    function update($new_instance, $old_instance) {
        return $new_instance;
    }

    function form($instance) { 
    
        global $post;
        
        
        // Widget Admin Editor Fields
        ?>
        
        <!-- Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" />
		</p>
		<!-- Number of Posts: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'num' ); ?>"><?php _e('Number of Tags to Show:'); ?></label>
			<input id="<?php echo $this->get_field_id( 'num' ); ?>" name="<?php echo $this->get_field_name( 'num' ); ?>" value="<?php echo $instance['num']; ?>" class="widefat" />
		</p>
        
   <?php }
}
add_action('widgets_init', create_function('', register_widget('Blog_Tags')));
*/
