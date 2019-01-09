<?php
function add_organisation() {
	// get the current user //
	global $post;
	$current_user = wp_get_current_user();
	$organisation_title = '';
	// SET ALL THE FORM NAMES UP //
if( isset($_REQUEST['action']) && $_REQUEST['action'] == 'add_org' ) {
	
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
		
		$organisations_support = $_REQUEST['organisations_support'];
		$support_topics = $_REQUEST['support_topics'];
		$org_locations = $_REQUEST['organisations_locations'];
	
		
	if( $organisation_title == '') {
		$errors = true;
	}
	//print_r($errors);
	if(!$errors) {
		$args = array(
			'post_title'	=>	$organisation_title,
			'post_type'		=>	'organisations',
			'post_content'	=>	$organisation_description,
			'post_status'   => 	'publish',
			'author'		=>	$current_user->ID
		);
		
		$org_id = wp_insert_post($args);	
		
		// now set all the taxo terms from form //
		wp_set_post_terms( $org_id, array($organisations_support), 'organisations_support' );
		wp_set_post_terms( $org_id, array($support_topics), 'support_topics' );
		wp_set_post_terms( $org_id, array($org_locations), 'organisations_locations' );
		
		// addd the custom meta for map //
		
		//add_post_meta($org_id, 'location_event', $organisation_location);
		add_post_meta($org_id, 'location_event', $organisation_location);
		
		// add all the acf fields //

		add_post_meta($org_id, 'organisation_website', $organisation_website);
		add_post_meta($org_id, 'organisation_email', $organisation_email);
		add_post_meta($org_id, 'organisation_phone_number', $organisation_phone_number);
		add_post_meta($org_id, 'organisation_facebook_url', $organisation_facebook_url);
		add_post_meta($org_id, 'organisation_twitter_url', $organisation_twitter_url);
		add_post_meta($org_id, 'organisation_linkedin_url', $organisation_linkedin_url);
		add_post_meta($org_id, 'organisation_google_plus_profile', $organisation_google_plus_profile);

		//exit;
		?>
			
			<script type="text/javascript">
			      document.location.href="<?php echo get_permalink(231); ?>";
			</script>
		<?php
	}	
}
	?>
	<form id="add_org_form" enctype="multipart/form-data" method="post" action="<?php echo get_permalink(231); ?>" class="form-horizontal account-form">
		<div class="col-sm-12"><div class="modular_head"><h2 class="account-heading">Add Organisation</h2></div></div>						
		<div class="form-box">	
			<div class=" col-sm-6">	
				<div class="modular_bkd">					
					    <div class="col-sm-12 grp">
						    <label class="control-label">Organisation Title</label>
					    	<input type="text" class="form-control" name="organisation_title"  data-bv-notempty >
					    </div>		
					    <div class="col-sm-12 grp">
						    <label class="control-label">Email Address</label>
					    	<input type="email" class="form-control" name="organisation_email"  data-bv-notempty >
					    </div>	
					    <div class="col-sm-12 grp">
					    <label class="control-label">Organisation Website</label>
				    	<input type="text" class="form-control" name="organisation_website" data-bv-notempty >
				    	</div>	
					</div>	
				<div class="modular_bkd">
				   
				    <div class="col-sm-12 grp">
					     <label class="control-label">Telephone Number</label>
				    	<input  class="form-control" name="organisation_phone_number" data-bv-notempty >
				    </div>			
				    <div class="col-sm-12 grp">
					    <label class="control-label">Facebook Url</label>
				    	<input  type="text" class="form-control" name="organisation_facebook_url" data-bv-notempty >
				    </div>			
				    <div class="col-sm-12 grp">
					    <label class="control-label">Twitter Url</label>
				    	<input  type="text" class="form-control" name="organisation_twitter_url" data-bv-notempty >
				    </div>			
				   
				    <div class="col-sm-12 grp">
					     <label class="control-label">Linkedin Url</label>
				    	<input type="text" class="form-control" name="organisation_linkedin_url" data-bv-notempty >
				    </div>			
				   
				    <div class="col-sm-12 grp">
					     <label class="control-label">Google Plus Url</label>
				    	<input type="text"  class="form-control" name="organisation_google_plus_profile" data-bv-notempty >
				    </div>			
				</div>	
				<div class="modular_bkd">
					<div class="col-sm-12 grp">
						<h3 class="account_heading">Organisation Description</h3>
						<div class="form-box">
							<div class="form-s">
								<textarea name="organisation_description" rows="7" class="form-control"></textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="modular_bkd">
				   <div class="col-sm-12 grp">
				   <!-- take this out until further notice -->
				   	<!-- <label class="control-label">Address</label> -->
				    <?php 
				    
			   		 global $post;
					$location_address = 'Peterborough';
					$location_lat = '52.5833';
					$location_lng = '0.2500';
					$location_zoom = '13';
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
						<h3 class="account-heading">Organisation Categories</h3>	
						<label class="control-label">Organisations Support Type</label>
						<select name="organisations_support" class="form-control" data-bv-not-empty >
							<?php
								$args = array(
									'taxonomy'		=>	'organisations_support',
									'hide_empty'	=>	0
								);
								$organisations_support = get_categories($args);
								$current_support = get_organisations_support($org_id);
							?>
							<?php foreach($organisations_support as $sts): ?>
							<option value="<?php echo $sts->cat_ID; ?>"><?php echo $sts->name; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="col-sm-12 grp">
						<label class="control-label">Organisations Support Topics</label>
						<select name="support_topics" class="form-control" data-bv-not-empty >
							<?php
								$args = array(
									'taxonomy'		=>	'support_topics',
									'hide_empty'	=>	0
								);
								$organisations_topics = get_categories($args);
								$current_topic = get_support_topics($org_id);
							?>
							<?php foreach($organisations_topics as $sts): ?>
							<option value="<?php echo $sts->cat_ID; ?>"><?php echo $sts->name; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="col-sm-12 grp">
						<label class="control-label">Organisation Locations Eligible</label>
						<select name="organisations_locations" class="form-control" data-bv-not-empty >
							<?php
								$args = array(
									'taxonomy'		=>	'organisations_locations',
									'hide_empty'	=>	0
								);
								$organisations_locations = get_categories($args);
								$current_location = get_organisation_locations($org_id);
							?>
							<?php foreach($organisations_locations as $sts): ?>
							<option value="<?php echo $sts->cat_ID; ?>"><?php echo $sts->name; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
				<div class="modular_bkd">
					<input type="hidden" name="action" value="add_org" />
					<a href="<?php echo get_permalink(231); ?>" class="btn-lg btn btn-default pull-left ">Cancel</a>
					<button type="submit" class="btn btn-primary btn-lg pull-right add_new">Add Organisation</button>
				</div>
			</div>															
		</div>

		
			
										
	</form>

	<script>
	jQuery(document).ready(function($){		
		$('#add_org_form').bootstrapValidator({
		    feedbackIcons: {
		        valid: 'fa fa-check',
		        invalid: 'fa fa-times',
		        validating: 'fa fa-refresh'
		    }
		});
	});
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