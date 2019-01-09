<?php
/**
 * Opportunities Post Type
 *
 * @package WordPress
 * @subpackage blank
 * @since blank 1.0
 */

class elbp_opportunities {

    /*
     * Startup the Class 
    */	
	public function __construct() {
	
		// Register Post Type & Taxonomy
		add_action( 'init', array( $this, 'register_opportunities_post_type' ) );
		add_action( 'init', array( $this, 'register_opportunities_category' ) );
	
	}
    /*
     * Register the Post Type
    */		
	public function register_opportunities_post_type() {

		$labels = array(
			'name'               => _x( 'Opportunities', 'post type general name' ),
			'singular_name'      => _x( 'Opportunities', 'post type singular name' ),
			'add_new'            => _x( 'Add New', 'Opportunities' ),
			'add_new_item'       => __( 'Add New Opportunities' ),
			'edit_item'          => __( 'Edit Opportunities' ),
			'new_item'           => __( 'New Opportunities' ),
			'all_items'          => __( 'All Opportunities' ),
			'view_item'          => __( 'View Opportunities' ),
			'search_items'       => __( 'Search Opportunities' ),
			'not_found'          => __( 'No Opportunities found' ),
			'not_found_in_trash' => __( 'No Opportunities found in the Trash' ), 
			'parent_item_colon'  => '',
			'menu_name'          => 'Opportunities',
			'menu_icon'				=> ""
		);
		
		$args = array(	
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true, 
			'show_in_menu' => true, 
			'menu_icon'	=>	'dashicons-megaphone',
			'query_var' => true,
			'capability_type' => 'post',
			'has_archive' => true, 
			'menu_position' => 5,
			'supports'      => array( 'title', 'editor', 'thumbnail','author'),
		); 
	  
		register_post_type('opportunities', $args);
		
	}

    /*
     * Register Taxonomy for Business Support
    */
	public function register_opportunities_category() {

		$labels = array(
        	'name' => _x( 'Opportunities Categories', 'taxonomy general name' ),
        	'singular_name' => _x( 'Opportunities Categories', 'taxonomy singular name' ),
        	'search_items' =>  __( 'Opportunities Categories' ),
        	'all_items' => __( 'All Opportunities Categories' ),
        	'parent_item' => __( 'Parent Opportunities Categories' ),
        	'parent_item_colon' => __( 'Parent Opportunities Categories:' ),
        	'edit_item' => __( 'Edit Opportunities Categories' ), 
        	'update_item' => __( 'Update Opportunities Categories' ),
        	'add_new_item' => __( 'Add New Opportunities Categories' ),
        	'new_item_name' => __( 'New Opportunities Categories Name' ),
        	'menu_name' => __( 'Opportunities Categories' ),
        ); 	
      
        register_taxonomy('opportunities_category', array('Opportunities'), array(
        	'hierarchical' => true,
        	'public' => false,
        	'labels' => $labels,
        	'show_ui' => true,
        	'show_admin_column' => true,
        	'query_var' => true,
        ));
	}
	

}
$elbp_opportunities = new elbp_opportunities();
