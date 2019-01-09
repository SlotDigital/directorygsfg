<?php
/**
 * organisations Post Type
 *
 * @package WordPress
 * @subpackage blank
 * @since blank 1.0
 */

class elbp_organisations {

    /*
     * Startup the Class 
    */	
	public function __construct() {
	
		// Register Post Type & Taxonomy
		add_action( 'init', array( $this, 'register_organisations_post_type' ) );
		add_action( 'init', array( $this, 'register_locations' ) );
		add_action( 'init', array( $this, 'register_areas' ) );
		add_action( 'init', array( $this, 'register_organisations_support' ) );
		add_action( 'init', array( $this, 'register_support_topics' ) );
		add_action( 'init', array( $this, 'register_keywords' ) );
		// Add the Hooks for meta at top of class //
		add_action('admin_menu', array($this, 'add_meta_boxes'));
		add_action('save_post', array($this,'save_meta_box_content'));
	}
		
		
	
	
    /*
     * Register the Post Type
    */		
	public function register_organisations_post_type() {

		$labels = array(
			'name'               => _x( 'Organisations', 'post type general name' ),
			'singular_name'      => _x( 'Organisations', 'post type singular name' ),
			'add_new'            => _x( 'Add New', 'Organisations' ),
			'add_new_item'       => __( 'Add New Organisations' ),
			'edit_item'          => __( 'Edit Organisations' ),
			'new_item'           => __( 'New Organisations' ),
			'all_items'          => __( 'All Organisations' ),
			'view_item'          => __( 'View Organisations' ),
			'search_items'       => __( 'Search Organisations' ),
			'not_found'          => __( 'No Organisations found' ),
			'not_found_in_trash' => __( 'No Organisations found in the Trash' ), 
			'parent_item_colon'  => '',
			'menu_name'          => 'Organisations',
			'menu_icon'				=> ""
		);
		
		$args = array(	
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true, 
			'show_in_menu' => true, 
			'menu_icon'	=>	'dashicons-id-alt',
			'query_var' => true,
			'capability_type' => 'post',
			'taxonomies' => array('post_tag'),
			'has_archive' => false, 
			'menu_position' => 5,
			'supports'      => array( 'title', 'editor', 'thumbnail','author'),
		); 
	  
		register_post_type('organisations', $args);
		
	}
    /*
     * Register Taxonomy for organisation Locations
    */
		public function register_locations() {

		$labels = array(
        	'name' => _x( 'Locations Eligible', 'taxonomy general name' ),
        	'singular_name' => _x( 'Locations Eligible', 'taxonomy singular name' ),
        	'search_items' =>  __( 'Locations Eligible' ),
        	'all_items' => __( 'All Locations Eligible' ),
        	'parent_item' => __( 'Parent Locations Eligible' ),
        	'parent_item_colon' => __( 'Parent Locations Eligible:' ),
        	'edit_item' => __( 'Edit Location' ), 
        	'update_item' => __( 'Update Location' ),
        	'add_new_item' => __( 'Add New Location' ),
        	'new_item_name' => __( 'New Location Name' ),
        	'menu_name' => __( 'Locations Eligible' ),
        ); 	
      
        register_taxonomy('organisations_locations', array('organisations'), array(
        	'hierarchical' => true,
        	'public' => true,
        	'labels' => $labels,
        	'show_ui' => true,
        	'show_admin_column' => true,
        	'query_var' => true,
        ));
        $csv = array_map('str_getcsv', file(ELBP_INC.'/locationstore/locations.csv'));
		array_walk($csv, function(&$a) use ($csv) {
		   $a = array_combine($csv[0], $a);
		 });
		 array_shift($csv); # remove column header
		 $items = array();
		 foreach($csv as $item) {
		     $items[] = $item['County'];
		 }
		 $new = array_unique($items);
		 //print_r($new);
		//$terms = array('Hackney','Islington','Tower Hamlets','Newham','Waltham Forest');
		foreach($new as $term) {
			//echo $term;
	       wp_insert_term($term,'organisations_locations'); 
        }
	}
	
	
	 /*
     * Register Taxonomy for organisation areas
    */
		public function register_areas() {

		$labels = array(
        	'name' => _x( 'Areas Eligible', 'taxonomy general name' ),
        	'singular_name' => _x( 'Areas Eligible', 'taxonomy singular name' ),
        	'search_items' =>  __( 'Areas Eligible' ),
        	'all_items' => __( 'All Areas Eligible' ),
        	'parent_item' => __( 'Parent Areas Eligible' ),
        	'parent_item_colon' => __( 'Parent Areas Eligible:' ),
        	'edit_item' => __( 'Edit Area' ), 
        	'update_item' => __( 'Update Area' ),
        	'add_new_item' => __( 'Add New Area' ),
        	'new_item_name' => __( 'New Area Name' ),
        	'menu_name' => __( 'Areas Eligible' ),
        ); 	
      
        register_taxonomy('organisations_areas', array('organisations'), array(
        	'hierarchical' => true,
        	'public' => true,
        	'labels' => $labels,
        	'show_ui' => true,
        	'show_admin_column' => true,
        	'query_var' => true,
        ));
	   //$terms = array('Hackney','Islington','Tower Hamlets','Newham','Waltham Forest');
	   //foreach($terms as $term) {
	   //	//echo $term;
	   //   wp_insert_term($term,'organisations_areas'); 
       //}
       
       $csv = array_map('str_getcsv', file(ELBP_INC.'/locationstore/locations.csv'));
		array_walk($csv, function(&$a) use ($csv) {
		   $a = array_combine($csv[0], $a);
		 });
		 array_shift($csv); # remove column header
		 $items = array();
		 foreach($csv as $item) {
		     $items[] = $item['partof'];
		 }
		 $new = array_unique($items);
		 //print_r($new);
		 foreach($new as $term) {
	      wp_insert_term($term,'organisations_areas'); 
       }
       
	}
		//$csv = array_map('str_getcsv', file(ELBP_INC.'/locationstore/locations.csv'));
		// array_walk($csv, function(&$a) use ($csv) {
		//    $a = array_combine($csv[0], $a);
		//  });
		//  array_shift($csv); # remove column header
		//print_r($csv);
		//
	
	
    /*
     * Register Taxonomy for organisation Support
    */
	public function register_organisations_support() {

		$labels = array(
        	'name' => _x( 'Organisation Support Types', 'taxonomy general name' ),
        	'singular_name' => _x( 'Organisation Support Types', 'taxonomy singular name' ),
        	'search_items' =>  __( 'Organisations Support Types' ),
        	'all_items' => __( 'All Support Types' ),
        	'parent_item' => __( 'Parent Support Types' ),
        	'parent_item_colon' => __( 'Parent Support Types:' ),
        	'edit_item' => __( 'Edit Support Type' ), 
        	'update_item' => __( 'Update Support Type' ),
        	'add_new_item' => __( 'Add New Support Type' ),
        	'new_item_name' => __( 'New Support Type Name' ),
        	'menu_name' => __( 'Support Type' ),
        ); 	
      
        register_taxonomy('organisations_support', array('organisations'), array(
        	'hierarchical' => true,
        	'public' => false,
        	'labels' => $labels,
        	'show_ui' => true,
        	'show_admin_column' => true,
        	'query_var' => true,
        ));
	/*	$terms = array('Workshops', '1:1 Support', 'Networking Events', 'Online Learning', 'Mentoring');
	foreach($terms as $term) {
			//echo $term;
	     wp_insert_term($term,'organisations_support'); 
        }*/
	}
	
	/*
     * Register Taxonomy for organisation Support Topics
    */
	public function register_support_topics() {

		$labels = array(
        	'name' => _x( 'Organisation Support Topics', 'taxonomy general name' ),
        	'singular_name' => _x( 'Organisation Support Topics', 'taxonomy singular name' ),
        	'search_items' =>  __( 'Organisation Support Topics' ),
        	'all_items' => __( 'All Support Topics' ),
        	'parent_item' => __( 'Parent Support Topics' ),
        	'parent_item_colon' => __( 'Parent Support Topics:' ),
        	'edit_item' => __( 'Edit Support Topics' ), 
        	'update_item' => __( 'Update Support Topics' ),
        	'add_new_item' => __( 'Add New Support Topics' ),
        	'new_item_name' => __( 'New Support Topics Name' ),
        	'menu_name' => __( 'Support Topics' ),
        ); 	
      
        register_taxonomy('support_topics', array('organisations'), array(
        	'hierarchical' => true,
        	'public' => false,
        	'labels' => $labels,
        	'show_ui' => true,
        	'show_admin_column' => true,
        	'query_var' => true,
        ));
	/*	$terms = array('Legal','Marketing','Sales','HR','Finance','Workspace','Intellectual Property','Health & Safety','Personal Development','Leadership & Management');
		foreach($terms as $term) {
			//echo $term;
	       wp_insert_term($term,'support_topics'); 
       }*/
	}
	
	
	/*
     * Register Taxonomy for organisation keys
    */
	public function register_keywords() {

		$labels = array(
        	'name' => _x( 'Keywords', 'taxonomy general name' ),
        	'singular_name' => _x( 'Keyword', 'taxonomy singular name' ),
        	'search_items' =>  __( 'Keywords' ),
        	'all_items' => __( 'All Keywords' ),
        	'parent_item' => __( 'Parent Keywords' ),
        	'parent_item_colon' => __( 'Parent Keywords:' ),
        	'edit_item' => __( 'Edit Keyword' ), 
        	'update_item' => __( 'Update Keyword' ),
        	'add_new_item' => __( 'Add New Keyword' ),
        	'new_item_name' => __( 'New Keyword' ),
        	'menu_name' => __( 'Keywords' ),
        ); 	
      
        register_taxonomy('keywords', array('organisations'), array(
        	'hierarchical' => true,
        	'public' => false,
        	'labels' => $labels,
        	'show_ui' => true,
        	'show_admin_column' => true,
        	'query_var' => true,
        ));


	}	
	
	
		public function add_meta_boxes() {
		add_meta_box('meta_box_organisation_map', 'organisation Map', array($this,'meta_box_organisation_map'), 'organisations', 'normal', 'high');
	}

			
		/*
	  * Save Meta Box Custom Fields *
	*/	
		public function meta_box_organisation_map() {
		global $post;
		$location_address = 'Peterborough';
		$location_lat = '52.5833';
		$location_lng = '0.2500';
		$location_zoom = '13';
		
			
		$meta_deets = get_post_custom($post->ID);
		?>
		<div class="form-group">
            <?php 
                $location_opts = get_post_meta($post->ID, 'location_event', true);
                if($location_opts) {
	                $location_address = esc_attr($location_opts['address']);
	                $location_lat = esc_attr($location_opts['lat']);
	                $location_lng = esc_attr($location_opts['lng']);
	                $location_zoom = esc_attr($location_opts['zoom']);
                }
               // print_r($location_opts);
            ?>
        </div>
        <div style="height:300px;" id="map-canvas_event"></div>
        <div id="panel">
	        <label>organisation Location</label>
          <input id="address" type="text" name="organisation_location[address]" value="<?php echo $location_address;?>" class="form-control" onkeyup="codeAddress();">
            
          <?php // Add Latitude and Longitide Form Fields. These will be saved to the $location array. ?>
          <input id="address_lat" type="hidden" name="organisation_location[lat]" value="<?php echo $location_lat; ?>">
          <input id="address_lng" type="hidden" name="organisation_location[lng]" value="<?php echo $location_lng; ?>">
          <input id="address_zoom" type="hidden" name="organisation_location[zoom]" value="<?php echo $location_zoom; ?>">
         <!-- <input type="button" value="Geocode" onclick="codeAddress()" class="button-primary">-->
        </div>
      <input type="submit" name="submit" id="submit" class="button button-primary save-button" value="Save Changes"  />
        
        
        
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCJAIpWnvPjgzs40B2MoZc5dTDYtQi9cuM&callback"></script>
<script>
var geocoder;
var map;
function initialize() {
  geocoder = new google.maps.Geocoder();
  var latlng = new google.maps.LatLng(<?php echo $location_lat.', '. $location_lng; ?>);
  var mapOptions = {
    zoom: <?php echo $location_zoom; ?>,
    center: latlng,
    styles: [{"featureType":"landscape","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"poi","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"stylers":[{"hue":"#00aaff"},{"saturation":-100},{"gamma":2.15},{"lightness":12}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"visibility":"on"},{"lightness":24}]},{"featureType":"road","elementType":"geometry","stylers":[{"lightness":57}]}]
  }
  map = new google.maps.Map(document.getElementById('map-canvas_event'), mapOptions);
    
 <?php if($location_lng){ ?>
 		var image = '<?php bloginfo( 'stylesheet_directory' ); ?>/images/map-marker.png';
 		<?php
        echo 'var marker = new google.maps.Marker({
          map: map,
          position: latlng,
          icon: image
      });'; 
   }?>
    
    google.maps.event.addListener(map, 'zoom_changed', function(){
   
    document.getElementById("address_zoom").value = map.getZoom();
    
});

}

function codeAddress() {
  var address = document.getElementById('address').value;
  geocoder.geocode( { 'address': address}, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
      map.setCenter(results[0].geometry.location);
        
      <?php // Get the value of Lat and Lng for the form fields. ?>   
      document.getElementById("address_zoom").value = map.getZoom();
       document.getElementById("address_lat").value = results[0].geometry.location.lat();
      document.getElementById("address_lng").value = results[0].geometry.location.lng();
        
      var image = '<?php bloginfo( 'stylesheet_directory' ); ?>/images/map-marker.png';
      var marker = new google.maps.Marker({
          map: map,
          icon: image,
          position: results[0].geometry.location
          
      });
    } else {
      console.log('Geocode was not successful for the following reason: ' + status);
    }
  });
}


google.maps.event.addDomListener(window, 'load', initialize);
</script>
<?php
	}

	public function save_meta_box_content($post_id) {   

		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}
		elseif ('organisation' == isset( $_POST['post_type'] ) && $_POST['post_type']) {
			if (!current_user_can('edit_page', $post_id)) {
				return $post_id;
			} else {
			if(isset($_POST['organisation_location'])) {	
					update_post_meta($post_id, 'location_event', $_POST['organisation_location']);
			}	
			}			
		}
		elseif (!current_user_can('edit_post', $post_id)) {
			return $post_id;
		}
	
	}

}
$elbp_organisations = new elbp_organisations();

function wpdocs_set_html_mail_content_type() {
    return 'text/html';
}

	 function publish_org_listen($post_id, $post) {   
		 
		 if($_SESSION['dont_send_approve_email']) {
			 unset($_SESSION['dont_send_approve_email']);
			 return false;
		 }

    	$user_id = $post->post_author; /* Post author ID. */
    	$user = new WP_User( $user_id );
    	
    	$approved = get_user_meta($user_id, 'approved', true);
    	if($approved == 1) return false;

    	$title = $post->post_title;

		
		$to = $user->user_email;
    	$message = '
<p>'.$user->first_name.' Thank you for registering <Org Name> with the GetSet Directory. Your organisation profile is now live! </p>

<p>Your log in details were sent to you when you first registered, if you have lost your password you can <a href="'.get_permalink(218).'">reset it here</a>.</p>

<p>On logging in to the directory, you will be directed to your organisation’s dashboard. Here you will be able to monitor how many events and opportunities you have listed on the site. You will also be able to update your profile information, add an organisation description, events and opportunities.</p>

<p>If you require any assistance, please get in touch with the directory support team: <a href="mailto:help@getsetforgrowth.com">help@getsetforgrowth.com</a></p>

    	';
    	
    	$headers[] = 'From: GetSet Directory <noreply@getsetforgrowth.comt>';
    	
    	update_user_meta($user_id, 'approved', 1);
    	add_filter( 'wp_mail_content_type', 'wpdocs_set_html_mail_content_type' );
    	wp_mail( $to, 'Your organisation has been approved', $message, $headers );		
    	
    	
    	
    	
	}
	add_action('publish_organisations',  'publish_org_listen', 10, 2 );