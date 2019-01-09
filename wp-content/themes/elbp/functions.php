<?php
/**
 * Theme functions and definitions.
 *
 * @package WordPress
 * @subpackage East London Business Portal
 * @since East London Business Portal 1.0
 */

define( 'ATTACHMENTS_DEFAULT_INSTANCE', false );

// Setup Theme Constants
define('ELBP_INC', dirname(__FILE__).'/inc/');
define('ELBP_EMAIL_TEMPLATES', dirname(__FILE__).'/emails/');
define('ELBP_WIDGETS', ELBP_INC.'widgets/');
define('ELBP_CPT', ELBP_INC.'post-types/');
define('ELBP_SIDE', ELBP_INC.'sidebars/');
define('ELBP_ACCOUNT', ELBP_INC.'account-funcs/');
define('ELBP_LIB', ELBP_INC.'library/');

// Define Constants for Default WordPress options (optimisation)
define('ELBP_URL', get_bloginfo('url'));
define('ELBP_SITENAME', get_bloginfo('name'));
define('ELBP_DESC', get_bloginfo('description'));
define('ELBP_THEME_URI', get_template_directory_uri());
define('ELBP_RSS_URL', get_bloginfo('rss_url'));

/* Misc */
require(ELBP_INC.'settings.php');

/* Account - function pages */
require(ELBP_ACCOUNT.'add-organisation.php');
require(ELBP_ACCOUNT.'edit-organisation.php');

/* Post types */
require(ELBP_CPT.'organisations.php');
require(ELBP_CPT.'opportunities.php');
require(ELBP_INC.'user-functions.php');
require(ELBP_INC.'import-users-orgs.php');
require(ELBP_INC.'register_func.php');


/* Web user Options Begin */

add_image_size( 'med', 600, 600, array( 'center' ) );

function _init_session() {
	if(!session_id()) session_start();
}
add_action('init', '_init_session');

$settings = get_elbp_settings();

if($settings['elbp_contact_phone']) {
	define('ELBP_PHONE', $settings['elbp_contact_phone']);
} else {
	define('ELBP_PHONE', 'Phone here');
}

/*-----------------*/

if($settings['elbp_contact_email']) {
	define('ELBP_EMAIL', $settings['elbp_contact_email']);
} else {
	define('ELBP_EMAIL', 'Email here');
}

/*-----------------*/ 
 
if($settings['elbp_facebook_url']) {
	define('ELBP_FB', $settings['elbp_facebook_url']);
} else {
	define('ELBP_FB', 'Facebook here');
}

/*-----------------*/ 

if($settings['elbp_twitter_url']) {
	define('ELBP_TWITTER', $settings['elbp_twitter_url']);
} else {
	define('ELBP_TWITTER', 'Twitter here');
}

/*-----------------*/ 

if($settings['elbp_linkedin_url']) {
	define('ELBP_LINKEDIN', $settings['elbp_linkedin_url']);
} else {
	define('ELBP_LINKEDIN', 'Linkedin here');
}

/*-----------------*/ 

if($settings['elbp_contact_address']) {
	define('ELBP_ADDRESS', $settings['elbp_contact_address']);
} else {
	define('ELBP_ADDRESS', 'Address here');
}

/*-----------------*/ 
 
/* Web user Options End */
 
function get_theme_info($label) {
	$infos = array(
		'url'		=>	ELBP_URL,
		'sitename'	=>	ELBP_SITENAME,
		'desc'		=>	ELBP_DESC,
		'theme_url'	=>	ELBP_THEME_URI
	);
	return $infos[$label];
}

/**
 * Theme Setup
 */
function ELBP_setup() {

	//ELBP_scripts_css_register();
	
    add_action('wp_enqueue_scripts', 'ELBP_scripts_css_register');
    
	// show admin bar only for admins
	if (!current_user_can('manage_options')) {
		add_filter('show_admin_bar', '__return_false');
	}	
	
	
	
	add_editor_style();

	add_theme_support( 'automatic-feed-links' );	
	add_theme_support( 'post-thumbnails' );
	
	
	// ------------ THUMBNAIL SIZES ------------
	add_image_size('small-box',350,210,true);
	add_image_size('extra-small-box',128,110,true);
	add_image_size('main',740,390,true);
	add_image_size('logos',300,300,false);
	// -----------------------------------------
	register_nav_menu( 'primary', __( 'Navigation Menu', 'ELBP_' ) );
	
	remove_action('wp_head', 'wp_generator');

}
add_action( 'after_setup_theme', 'ELBP_setup' );

/**
 * Register Scripts & Styles
 */
function ELBP_scripts_css_register() {
	
	// External Libs
	wp_enqueue_script( 'GoogleMaps', 'https://maps.google.com/maps/api/js?sensor=false', array('jquery'));
	
	// Local and default Libs
	wp_enqueue_script( 'mmenu', ELBP_THEME_URI.'/js/mmenu.js', array('jquery'));
	wp_enqueue_script( 'ELBP_hover-int', ELBP_THEME_URI.'/js/hoverintent.js', array('jquery'));	
	wp_enqueue_script( 'global', ELBP_THEME_URI.'/js/global.js', array('jquery'));	
	wp_enqueue_script( 'Bootstrap', ELBP_THEME_URI.'/js/bootstrap.min.js', array('jquery'));
	wp_enqueue_script( 'BootstrapValidator', ELBP_THEME_URI.'/js/bootstrap.validator.min.js', array('jquery'));
	wp_enqueue_script( 'Bootstrap_select', ELBP_THEME_URI.'/js/bootstrap-select.js', array('jquery'));
	wp_enqueue_script( 'Paralax', ELBP_THEME_URI.'/js/jquery.paralax.min.js', array('jquery'));
	wp_enqueue_script( 'widgets', ELBP_THEME_URI.'/js/jquery-ui.widgets.min.js', array('jquery'));
	wp_enqueue_script( 'select', ELBP_THEME_URI.'/js/selectBox.js', array('jquery'));
	wp_enqueue_script( 'raphael', ELBP_THEME_URI.'/js/raphael-min.js', array('jquery'));
	wp_enqueue_script( 'morris', ELBP_THEME_URI.'/js/morris_min.js', array('jquery'));
	wp_enqueue_script( 'select2', ELBP_THEME_URI.'/js/select2.full.min.js', array('jquery'));
		
}
/**
 * Show Home Link in Nav Menus
 */
function ELBP_nav_menu_args( $args ) {
	if ( ! isset( $args['show_home'] ) )
		$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'ELBP_nav_menu_args' );

/**
 * Register Theme Sidebars
 */
function ELBP_widgets_init() {

if ( function_exists('register_sidebar') )
register_sidebar(array(
	'name'          => 'main-sidebar',
	'id'            => 'main-sidebar',
	'description'   => 'This is the sidebar for standard pages...inc widgets',
    'class'         => '',
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget'  => '</div><div class="full-divide small-divide"></div>',
	'before_title'  => '<h3 class="widget-title">',
	'after_title'   => '</h3>' ));

}
add_action( 'widgets_init', 'ELBP_widgets_init' );


/**
 * Set wp_mail content type to HTML
 */
function set_html_content_type() {
	return 'text/html';
}

// Add custom post types count action to WP Dashboard
add_action('dashboard_glance_items', 'custom_posttype_glance_items');

// Showing all custom posts count
function custom_posttype_glance_items()
{
	$glances = array();

	$args = array(
		'public'   => true,  // Showing public post types only
		'_builtin' => false  // Except the build-in wp post types (page, post, attachments)
	);

	// Getting your custom post types
	$post_types = get_post_types($args, 'object', 'and');

	foreach ($post_types as $post_type)
	{
		// Counting each post
		$num_posts = wp_count_posts($post_type->name);

		// Number format
		$num   = number_format_i18n($num_posts->publish);
		// Text format
		$text  = _n($post_type->labels->singular_name, $post_type->labels->name, intval($num_posts->publish));

		// If use capable to edit the post type
		if (current_user_can('edit_posts'))
		{
			// Show with link
			$glance = '<a class="'.$post_type->name.'-count" href="'.admin_url('edit.php?post_type='.$post_type->name).'">'.$num.' '.$text.'</a>';
		}
		else
		{
			// Show without link
			$glance = '<span class="'.$post_type->name.'-count">'.$num.' '.$text.'</span>';
		}

		// Save in array
		$glances[] = $glance;
	}

	// return them
	return $glances;
}
add_image_size( 'example', 150, 150 );

/*-----------------------------------------------------------------------------------*/
/* Remove Unwanted Admin Menu Items */
/*-----------------------------------------------------------------------------------*/

function remove_admin_menu_items() {
	$remove_menu_items = array(__('Comments'));
	global $menu;
	end ($menu);
	while (prev($menu)){
		$item = explode(' ',$menu[key($menu)][0]);
		if(in_array($item[0] != NULL?$item[0]:"" , $remove_menu_items)){
		unset($menu[key($menu)]);}
	}
}

add_action('admin_menu', 'remove_admin_menu_items');

/*
 * Paginate for Bootstrap 3
*/
function _paginate($query) {
	if($query->max_num_pages == 0) return;

	if ( !$current_page = get_query_var( 'paged' ) ) $current_page = 1;

	$permalinks = get_option( 'permalink_structure' );
	if( is_front_page() ) {
    	$format = empty( $permalinks ) ? '?paged=%#%' : 'page/%#%/';
    } else {
    	$format = empty( $permalinks ) || is_search() ? '&paged=%#%' : 'page/%#%/';
    }

    $big = 999999999; // need an unlikely integer

    $pagination = paginate_links( array(
    	'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
    	'format' => $format,
    	'current' => $current_page,
    	'total' => $query->max_num_pages,
    	'mid_size' => '4',
    	'type' => 'list',
		'prev_text' => __('<i class="fa fa-angle-left"></i> Previous'),
		'next_text' => __('Next <i class="fa fa-angle-right"></i>')
    ));

    $pagination = explode( "\n", $pagination );
    $pagination_mod = array();

    foreach ( $pagination as $item ) {
    	( preg_match( '/<ul class=\'page-numbers\'>/i', $item ) ) ? $item = str_replace( '<ul class=\'page-numbers\'>', '<ul class=\'pagination pagination-lg\'>', $item ) : $item;
    	( preg_match( '/class="prev/i', $item ) ) ? $item = str_replace( '<li', '<li class="pagination-prev"', $item ) : $item;
    	( preg_match( '/class="next/i', $item ) ) ? $item = str_replace( '<li', '<li class="pagination-next"', $item ) : $item;
    	( preg_match( '/current/i', $item ) ) ? $item = str_replace( '<li', '<li class="active"', $item ) : $item;
    	( preg_match( '/page-numbers/i', $item ) ) ? $item = str_replace( 'page-numbers', 'page-numbers pagenav', $item ) : $item;
    	$pagination_mod[] .= $item;
    }

?>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 pagination">
        <?php foreach( $pagination_mod as $page ): ?>
            <?php echo $page; ?>
        <?php endforeach; ?>
    </div>
</div>
<?php
}

/* BREADCRUMBS - Dependant on SEO yoast */
function elbp_breadcrumbs() {
	if ( function_exists('yoast_breadcrumb') ) {
		yoast_breadcrumb('<p id="breadcrumbs">','</p>');
	}	
}

function google_reverse_geocode_address(array $address) {
    
    $cords = array();
    
    $address = implode(', ', $address);
    $address = urlencode($address);		
    
    $google_api_geocode_url ="http://maps.googleapis.com/maps/api/geocode/xml?address=$address&sensor=false";
    
    $xml = simplexml_load_file($google_api_geocode_url);

    $lat = $xml->xpath("result/geometry/location/lat");
    $lng = $xml->xpath("result/geometry/location/lng");		
    
    $cords['lat'] = (string) $lat[0];
    $cords['lng'] = (string) $lng[0];
    
    return $cords;
    	
}

       function alter_search_wpse_news($search,$qry)
        {
            global $wpdb;
            $add = $wpdb->prepare("({$wpdb->postmeta}.meta_key = '_et_builder_settings' AND CAST({$wpdb->postmeta}.meta_value AS CHAR) LIKE '%%%s%%')",$qry->get('s'));
            $pat = '|\(\((.+)\)\)|';
            $search = preg_replace($pat,'(($1 OR '.$add.'))',$search);
            return $search;
        }

 function add_join_wpse_news($joins)
 {
     global $wpdb;
     $joins = $joins . " INNER JOIN {$wpdb->postmeta} ON ({$wpdb->posts}.ID = {$wpdb->postmeta}.post_id)" ;
     $joins .= " inner join  {$wpdb->term_relationships}  as wrel on {$wpdb->posts}.ID = wrel.object_id";
     $joins .= " inner join {$wpdb->term_taxonomy} as wtax on wtax.term_taxonomy_id = wrel.term_taxonomy_id";
     $joins .= " inner join {$wpdb->terms} as wter on wter.term_id = wtax.term_id";

     return $joins;

 }
 function add_where_wpse_news($where) {
	 if(@$_REQUEST['organisation_search'] != ''):
     $getname = $_REQUEST['organisation_search'];
     $where .= "(AND $wpdb->post_type = 'organisations' AND $wpdb->post_status = 'publish')";
     $where .= "OR wter.slug like '%$getname%'";
     return $where;
     endif;
 }
 
         function alter_groupby_wpse_news($groupby)
        {
            global $wpdb;

            $mygroupby = "{$wpdb->posts}.ID";
            if( preg_match( "/$mygroupby/", $groupby )) {
                // grouping we need is already there
                return $groupby;
            }

            if( !strlen(trim($groupby))) {
                // groupby was empty, use ours
                return $mygroupby;
            }

            // wasn't empty, append ours
            return $groupby . ", " . $mygroupby;
        }
        
function Generate_Featured_Image( $image_url, $post_id  ){
    $upload_dir = wp_upload_dir();
    $image_data = file_get_contents($image_url);
    $filename = basename($image_url);
    if(wp_mkdir_p($upload_dir['path']))     $file = $upload_dir['path'] . '/' . $filename;
    else                                    $file = $upload_dir['basedir'] . '/' . $filename;
    file_put_contents($file, $image_data);

    $wp_filetype = wp_check_filetype($filename, null );
    $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title' => sanitize_file_name($filename),
        'post_content' => '',
        'post_status' => 'inherit'
    );
    $attach_id = wp_insert_attachment( $attachment, $file, $post_id );
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
    $res1= wp_update_attachment_metadata( $attach_id, $attach_data );
    $res2= set_post_thumbnail( $post_id, $attach_id );
}        

function http_check($url) {
$return = $url;
if ((!(substr($url, 0, 7) == 'http://')) && (!(substr($url, 0, 8) == 'https://'))) { $return = 'http://' . $url; }
return $return;
} 