<?php
function edit_organisation() {
	// get the current user //
	global $post;
	$current_user = wp_get_current_user();
	
	$args = array(
		'post_type'		=>	'organisations',
		'showposts'		=>	1,
		'author'		=>	$current_user->ID
	);
	
	$org = new WP_Query($args);
	$org = $org->posts;
	$org_id= $org[0]->ID;
	$org_cont= $org[0]->post_content;

	//$pid = $_REQUEST['id'];
	//$organisation_post = get_post($pid);
	//$organisation_title = '';
	//$pid = $_REQUEST['id'];
	//$organisation = get_post($pid);
	//print_r($organisation);
	// SET ALL THE FORM NAMES UP //

	
if( isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit_org' ) {
	
		$errors = false;
		// main //
		$organisation_title = $_REQUEST['organisation_title'];
		$organisation_description = $_REQUEST['organisation_description'];
		
		// custom fields //
		
		$organisation_location = $_REQUEST['organisation_location'];
		
		// acf //
		$organisation_website = $_REQUEST['organisation_website'];
		$organisation_email = $_REQUEST['organisation_email'];
		
		$organisation_phone_number = $_REQUEST['organisation_phone_number'];
		$organisation_facebook_url = $_REQUEST['organisation_facebook_url'];
		$organisation_twitter_url = $_REQUEST['organisation_twitter_url'];
		$organisation_linkedin_url = $_REQUEST['organisation_linkedin_url'];
		$organisation_google_plus_profile = $_REQUEST['organisation_google_plus_profile'];
		
		// taxos //
		
		$organisations_support = @$_REQUEST['organisations_support'];
		$support_topics = @$_REQUEST['support_topics'];
		$org_locations = @$_REQUEST['organisations_locations'];
		
		$org_keywords = @$_REQUEST['organisations_keywords'];
		
		
		$logo = $_FILES['logo'];

	
		
	if( $organisation_title == '') {
		$errors = true;
	}
	//print_r($errors);
	if(!$errors) {
		
		//$org_id = $organisation_post->ID;
		//print_r($org_id);
		$args = array(
			'ID'			=>	$org_id,
			'post_title'	=>	$organisation_title,
			'post_type'		=>	'organisations',
			'post_content'	=>	$organisation_description,
			'post_status'   => 	'publish',
			//'author'		=>	$current_user->ID
		);
			
		
		remove_action('publish_organisations', 'publish_org_listen', 10);
		
		$_SESSION['dont_send_approve_email'] = true;
		
		wp_update_post($args);	
		
		// keywords 
		if($org_keywords) {
		foreach($org_keywords as $kw) {
			
			// if is a number it exists else we add a new term
			if( is_numeric($kw) ) {
				$kw_terms[]=(int)$kw;
			} else {
				$new_term = wp_insert_term($kw, 'keywords');
				$kw_terms[] = (int) $new_term['term_id'];				
			}
			
		}
		}	
		
		$_org_s = array();
		if($organisations_support) {
			foreach($organisations_support as $id) {
				$_org_s[] = (int) $id;
			}
		}	

		$_org_st = array();
		if($support_topics) {
			foreach($support_topics as $id) {
				$_org_st[] = (int) $id;
			}
		}	


		$_org_l = array();
		if($org_locations) {
			foreach($org_locations as $id) {
				$_org_l[] = (int) $id;
			}
		}	

		wp_delete_object_term_relationships( $org_id, 'organisations_support' );
		wp_delete_object_term_relationships( $org_id, 'support_topics' );
		wp_delete_object_term_relationships( $org_id, 'organisations_locations' );
		wp_delete_object_term_relationships( $org_id, 'keywords' );
		
		// now set all the taxo terms from form //
		wp_set_object_terms( $org_id, $_org_s, 'organisations_support' );
		wp_set_object_terms( $org_id, $_org_st, 'support_topics' );
		wp_set_object_terms( $org_id, $_org_l, 'organisations_locations' );
		wp_set_object_terms( $org_id, @$kw_terms, 'keywords' );
		
		// addd the custom meta for map //
		
		//add_post_meta($org_id, 'location_event', $organisation_location);
		update_post_meta($org_id, 'location_event', $organisation_location);
		
		// add all the acf fields //

		update_post_meta($org_id, 'organisation_website', $organisation_website);
		update_post_meta($org_id, 'organisation_email', $organisation_email);
		
		update_post_meta($org_id, 'organisation_phone_number', $organisation_phone_number);
		update_post_meta($org_id, 'organisation_facebook_url', $organisation_facebook_url);
		update_post_meta($org_id, 'organisation_twitter_url', $organisation_twitter_url);
		update_post_meta($org_id, 'organisation_linkedin_url', $organisation_linkedin_url);
		update_post_meta($org_id, 'organisation_google_plus_profile', $organisation_google_plus_profile);
		//wp_redirect(get_permalink(231));
		//exit;
		
		if($logo) {
			if( move_uploaded_file( $logo['tmp_name'],  WP_CONTENT_DIR . '/uploads/tmp/'.$logo['name']) ) {
				Generate_Featured_Image(WP_CONTENT_DIR . '/uploads/tmp/'.$logo['name'], $org_id);
			}
		}
		
		
		?>
			
			
		<?php
	}	
}
	?>
	
	<?php $args = array(
		'post_type'		=>	'organisations',
		'showposts'		=>	1,
		'author'		=>	$current_user->ID
	);
	
	$org = new WP_Query($args);
	$org = $org->posts;
	$org_id= $org[0]->ID;
	$org_cont= $org[0]->post_content;
	$org_title= $org[0]->post_title;
	?>
	
	<form id="add_org_form" enctype="multipart/form-data" method="post" action="<?php echo get_permalink(231); ?>" class="form-horizontal account-form">
		<div class="col-sm-12"><div class="modular_head"><h2 class="account-heading"><?php echo '<b>Edit</b> '.$org_title;?></h2></div></div>						
		<div class="form-box">	
			<div class=" col-sm-6">	
				<div class="modular_bkd">					
					    <div class="col-sm-12 grp">
						    <label class="control-label">Organisation Title</label>
					    	<input value="<?php echo get_the_title($org_id);?>" type="text" class="form-control" name="organisation_title"  data-bv-notempty >
					    </div>		
					    <div class="col-sm-12 grp">
						    <label class="control-label">Email Address</label>
					    	<input value="<?php if(get_field('organisation_email',$org_id)) { echo get_field('organisation_email',$org_id); }; ?>"type="email" class="form-control" name="organisation_email"  data-bv-notempty >
					    </div>	
					    <div class="col-sm-12 grp">
					    <label class="control-label">Organisation Website</label>
				    	<input value="<?php if(get_field('organisation_website',$org_id)) { echo get_field('organisation_website',$org_id); }; ?>"type="text" class="form-control" name="organisation_website" data-bv-notempty >
				    	</div>	
					</div>	
				<div class="modular_bkd">
				   
				    <div class="col-sm-12 grp">
					     <label class="control-label">Telephone Number</label>
				    	<input value="<?php if(get_field('organisation_phone_number',$org_id)) { echo get_field('organisation_phone_number',$org_id); }; ?>" class="form-control" name="organisation_phone_number" data-bv-notempty >
				    </div>			
				    <div class="col-sm-12 grp">
					    <label class="control-label">Facebook Url</label>
				    	<input value="<?php if(get_field('organisation_facebook_url',$org_id)) { echo get_field('organisation_facebook_url',$org_id); }; ?>" type="text" class="form-control" name="organisation_facebook_url" data-bv-notempty >
				    </div>			
				    <div class="col-sm-12 grp">
					    <label class="control-label">Twitter Url</label>
				    	<input value="<?php if(get_field('organisation_twitter_url',$org_id)) { echo get_field('organisation_twitter_url',$org_id); }; ?>" type="text" class="form-control" name="organisation_twitter_url" data-bv-notempty >
				    </div>			
				   
				    <div class="col-sm-12 grp">
					     <label class="control-label">Linkedin Url</label>
				    	<input value="<?php if(get_field('organisation_linkedin_url',$org_id)) { echo get_field('organisation_linkedin_url',$org_id); }; ?>"type="text" class="form-control" name="organisation_linkedin_url" data-bv-notempty >
				    </div>			
				   
				    <div class="col-sm-12 grp">
					     <label class="control-label">Google Plus Url</label>
				    	<input type="text" value="<?php if(get_field('organisation_google_plus_profile',$org_id)) { echo get_field('organisation_google_plus_profile',$org_id); }; ?>" class="form-control" name="organisation_google_plus_profile" data-bv-notempty >
				    </div>			
				</div>	
				<div class="modular_bkd">
					<div class="col-sm-12 grp">
						<h3 class="account_heading">Organisation Description</h3>
						<div class="form-box">
							<div class="form-s">
								<textarea name="organisation_description" rows="7" class="form-control"><?php echo $org[0]->post_content;?></textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				
				
				<div class="modular_bkd">
				   <div class="col-sm-12 grp">
					   <h3 class="account-heading">Organisation Logo</h3>
					   <div class="org_logo">
						   	<label class="control-label">Change or Upload Logo</label>
						   <?php if( has_post_thumbnail($org_id)): ?>
						   <div style="margin: 15px 0">
						   		<?php echo get_the_post_thumbnail( $org_id, 'thumbnail' );  ?>
						   </div>
						   <?php endif; ?>					   
						   <input type="file" name="logo" />
					   </div>
				   </div>
				</div>			
				
				<div class="modular_bkd">
				   <div class="col-sm-12 grp">
				   <!-- take this out until further notice -->
				   	<!-- <label class="control-label">Address</label> -->
				   
				   <?php 
				      
				   	$location_opts = get_post_meta($org_id, 'location_event', true);
				   	//print_r($location_opts);
				   	if($location_opts) {
				   	    $location_address = esc_attr($location_opts['address']);
				   	    $location_lat = esc_attr($location_opts['lat']);
				   	    $location_lng = esc_attr($location_opts['lng']);
				   	    $location_zoom = esc_attr($location_opts['zoom']);
				   	} else {
				   		$location_address = 'London';
				   	    $location_lat = '';
				   	    $location_lng = '';
				   	    $location_zoom = '';
				   	}
				   	?>
        		   	<!-- take this out until further notice -->
        		   	<!-- <div style="height:300px;" id="map-canvas_event"></div> -->
        		   	<div id="panel">
	    		   	    <label>Organisation Location</label>
        		   	  <input id="address" type="text" name="organisation_location[address]" value="<?php echo $location_address;?>" class="form-control" onkeyup="codeAddress();"  >
        		   	  <?php // Add Latitude and Longitide Form Fields. These will be saved to the $location array. ?>
        		   	  <input id="address_lat" type="hidden" name="organisation_location[lat]" value="<?php echo $location_lat; ?>">
        		   	  <input id="address_lng" type="hidden" name="organisation_location[lng]" value="<?php echo $location_lng; ?>">
        		   	  <input id="address_zoom" type="hidden" name="organisation_location[zoom]" value="<?php echo $location_zoom; ?>">
        		   	 <!-- <input type="button" value="Geocode" onclick="codeAddress()" class="button-primary">-->
        		   	</div>
				   </div> 	
				</div>	
				<div class="modular_bkd">
					
					
					<div class="col-sm-12 grp">
						<h3 class="account-heading">Organisation Keywords</h3>	
						<label class="control-label" style="display:block;">Select keywords or create new ones</label>
						<select name="organisations_keywords[]" class="select2" multiple="multiple" data-bv-not-empty >
							<?php

								$keywords = get_terms(
									array(
										'taxonomy'	=>	'keywords',
										'hide_empty'	=>	false
									)
								);
								$kws = wp_get_post_terms($org_id, 'keywords', array('orderby' => 'name', 'order' => 'ASC', 'fields' => 'all'));	
								$_kws = array();
								foreach($kws as $k) $_kws[] = $k->term_id;								
							?>
							<?php foreach($keywords as $kw): ?>
							<option value="<?php echo $kw->term_id; ?>" <?php if(in_array($kw->term_id, $_kws)): ?> selected="selected"<?php endif; ?>><?php echo $kw->name; ?></option>
							<?php endforeach; ?>
						</select>
						<script>
							jQuery(document).ready(function($){
								$('.select2').select2({
									tags: true,
									placeholder: 'Start typing...',
									minimumInputLength: 1,
									tokenSeparators: [',']
								});								
							});
						</script>
					</div>					
					
					
					<div class="col-sm-12 grp">
						<h3 class="account-heading">Organisation Categories</h3>	
						<label class="control-label">Organisations Support Type</label>

						<select name="organisations_support[]" class="form-control select3"  multiple="multiple" data-bv-not-empty >
							<?php
								$args = array(
									'taxonomy'		=>	'organisations_support',
									'hide_empty'	=>	0
								);
								$organisations_support = get_categories($args);
								$org_s = wp_get_post_terms($org_id, 'organisations_support', array('orderby' => 'name', 'order' => 'ASC', 'fields' => 'all'));	
								$_org_s = array();
								foreach($org_s as $k) $_org_s[] = $k->term_id;	
						
							?>
							<?php foreach($organisations_support as $sts): ?>
							<option value="<?php echo $sts->term_id; ?>" <?php if(in_array($sts->term_id, $_org_s)): ?> selected="selected"<?php endif; ?>><?php echo $sts->name; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="col-sm-12 grp">
						<label class="control-label">Organisations Support Topics</label>
						<select name="support_topics[]" class="form-control select3" multiple="multiple" data-bv-not-empty >
							<?php
								$args = array(
									'taxonomy'		=>	'support_topics',
									'hide_empty'	=>	0
								);
								$organisations_topics = get_categories($args);
								$org_st = wp_get_post_terms($org_id, 'support_topics', array('orderby' => 'name', 'order' => 'ASC', 'fields' => 'all'));	
								$_org_st = array();
								foreach($org_st as $k) $_org_st[] = $k->term_id;	
							?>
							<?php foreach($organisations_topics as $sts): ?>
							<option value="<?php echo $sts->term_id; ?>" <?php if(in_array($sts->term_id, $_org_st)): ?> selected="selected"<?php endif; ?>><?php echo $sts->name; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					
						<script>
							jQuery(document).ready(function($){
								$('.select3').select2({

									placeholder: 'Select...'
								});								
							});
						</script>					
					
					<div class="col-sm-12 grp">
						<label class="control-label">Organisation Locations Eligible</label>
						
						<select name="organisations_locations[]" class="form-control select3" multiple="multiple" data-bv-not-empty >
							<?php
								$args = array(
									'taxonomy'		=>	'organisations_locations',
									'hide_empty'	=>	0
								);
								$organisation_locations = get_categories($args);
								$org_l = wp_get_post_terms($org_id, 'organisations_locations', array('orderby' => 'name', 'order' => 'ASC', 'fields' => 'all'));	
								$_org_l = array();
								foreach($org_l as $k) $_org_l[] = $k->term_id;	
							?>
							<?php foreach($organisation_locations as $sts): ?>
							<option value="<?php echo $sts->term_id; ?>" <?php if(in_array($sts->term_id, $_org_l)): ?> selected="selected"<?php endif; ?>><?php echo $sts->name; ?></option>
							<?php endforeach; ?>
						</select>						

					</div>
				</div>
				<div class="modular_bkd">
					<input type="hidden" name="action" value="edit_org" />
					<input type="hidden" name="id" value="<?php echo $pid; ?>" />
					<a href="<?php echo get_permalink(231); ?>" class="btn-lg btn btn-default pull-left">Cancel</a>
					<button type="submit" class="general_fw_button btn btn-primary btn-lg pull-right">Update Organisation</button>
				</div>
			</div>															
		</div>

		
			
										
	</form>
<?php if(isset($location_lat) || $location_lat != '') {
	$comma = ',';	
} else {
	$comma = '';
}
?>
	<script>
	jQuery(document).ready(function($){		
		//$('#add_org_form').bootstrapValidator({
		//    feedbackIcons: {
		//        valid: 'fa fa-check',
		//        invalid: 'fa fa-times',
		//        validating: 'fa fa-refresh'
		//    }
		//});
		
		
$('.selectpicker').selectpicker({
  size: 7
});		
		
	});
var geocoder;
var map;
function initialize() {
  geocoder = new google.maps.Geocoder();
  var latlng = new google.maps.LatLng(<?php echo $location_lat.$comma.$location_lng; ?>);
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
  console.log(address);
  geocoder.geocode( { 'address': address}, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
      map.setCenter(results[0].geometry.location);
 
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