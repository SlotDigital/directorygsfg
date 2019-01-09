<?php 
class CP_blogs extends WP_Widget {
function CP_blogs() {
    $widget_CPs = array('classname' => 'CP_blogs', 'description' => 'Shows blogs posts in sidebar' );
    $this->WP_Widget('CP_blogs', 'CP blogs', $widget_CPs);
}
function widget($args, $instance) { 

$args = array('posts_per_page' => 4, 'orderby' => 'sort_order', 'order' => 'ASC','post_type' => 'blog');	
$blogs = new WP_Query($args);
?>
<?php

// INSERT LOOP HERE //




	} // END OF FUNCTION //
}// END OF CLASS //

add_action('widgets_init', 'Register_blogs_wig');
function Register_blogs_wig() {
    register_widget('CP_blogs');
}
?>