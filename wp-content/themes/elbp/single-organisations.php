<?php 
	 /**
 * Single organisation page
 *
 * @package WordPress
 * @subpackage East London organisation Portal
 * @since East London organisation Portal 1.0
 */

get_header();	

$location_opts = get_post_meta($post->ID, 'location_event', true);
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
if(has_post_thumbnail()) {
	$thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
	$thumbnail_url = $thumbnail['0'];
} else {
	$thumbnail_url = "http://www.placehold.it/300x300";
}
//get_field('organisation_phone_number');
//echo $location_address; 
#var_dump($location_opts);
?>
<section data-parallax="scroll" data-image-src="<?php echo get_theme_info('theme_url').'/images/el_cityscape_v.jpg'; ?>" class="organisation_header background_cover">
	<div class="table_outer overlay_background">
		<div class="table_inner_mid">
			<div class="container Aligner">
				<div class="ft_image col-sm-3 col-sm-3 col-xs-4 col-sm-offset-0 col-xs-offset-4">
					<div class="featured_logo background_contain" style="background-image:url('<?php echo $thumbnail_url; ?>')"></div>	
				</div>
				<div class="col-sm-9 col-xs-12">
					<h1><?php echo the_title();?></h1>
					<div class="b_address"><i class="ion-ios-location"></i><?php echo $location_address; ?> </div>
					<div class="post_tags">
						<?php $terms = wp_get_post_terms($post->ID, 'keywords', array('orderby' => 'name', 'order' => 'ASC', 'fields' => 'all'));
								if($terms) {
									foreach($terms as $t) {
										echo '<span>'.$t->name.'</span>';
									}
								}
							?>
					</div>
					<h5><?php echo elbp_breadcrumbs(); ?></h5>
				</div>
			</div>
		</div>
	</div>
</section>
<section class="main_organisation">
	<div class="button_row">
		<div class="container">
			<div class="profile_buttons">
				<a href="<?php echo home_url().'/organisation-search'; ?>">Back to Search</a>
				<?php next_post_link( '%link', 'Next Organisation' ); ?>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row single_page">
			<div class="col-sm-8 col-sm-push-4">
				<div class="organisation_overview modular_bkd">
					<h2>Organisation Overview</h2>
					<div class="organisation_content page_content">
						<?php echo apply_filters('the_content',$post->post_content); ?>
					</div>
				</div>
			
				<?php 
					$post_id = $post->post_author;
					$args = array(
					 'post_type'	=>	'opportunities',
					 'author'        =>  $post_id,
					 'orderby'       =>  'post_date',
					 'order'         =>  'ASC',
					 'posts_per_page' => -1
					 );
					 $ops = new WP_Query($args);
					 if($ops->post_count >= 1) {
				?>
				<div class="modular_bkd mod_table">
					<h2>Available Opportunities</h2>
						<div class="row">
							<ul class="col-sm-12 list-unstyled">
								
									<?php 
									 while($ops->have_posts()):$ops->the_post();
									 	if(get_field('job_title')) {
										 	$jobtitle = get_field('job_title');
										 	$value = '£'.get_field('opportunity_value');
									 	} else {
										 	$jobtitle = '';
										 	$value = '';
									 	}
									 	
									 	echo '<li class="col-sm-12">
									 			<div class="op_information Aligner">
									 				<div class="col-sm-8">
									 					
									 					<h3>'.$post->post_title.'</h3>
									 				</div>
									 				<div class="col-sm-2">
									 					<span class="value_event">'.$value.'</span>
									 				</div>
									 				<div class="col-sm-2">
									 					<a class="view_opp _button"  href="'.get_the_permalink($post->ID).'">View</a>
									 				</div>
									 			</div>
									 		  </li>';
									 	
									 endwhile;
									 ?>
							
							</ul>
						</div>
					</div>
				<?php } wp_reset_postdata();?>
				
			</div>
			<div class="col-sm-4 col-sm-pull-8">
				<div class="modular_bkd">
					<?php
	$website = get_field('organisation_website');
						
					?>
					<h2>Contact Information</h2>
										<div class="organisation_contact">
						<span><i></i>Phone: <a href="<?php if(get_field('organisation_phone_number')) { echo 'tel:'.get_field('organisation_phone_number'); }; ?>"><?php if(get_field('organisation_phone_number')) { echo get_field('organisation_phone_number'); }; ?></a></span>
						<span><i></i>Email: <a href="<?php if(get_field('organisation_email')) { echo 'mailto:'.get_field('organisation_email'); }; ?>"><?php if(get_field('organisation_email')) { echo get_field('organisation_email'); }; ?></a></span>
						<?php if($website) { ?>
							<a class="view_website _button"target="_blank" href="<?php echo http_check($website) ?>">View Website</a>
							<?php } ?>
					</div>
				</div>
				
				<?php
					if(
						get_field('organisation_facebook_url') ||
						get_field('organisation_twitter_url') ||
						get_field('organisation_linkedin_url') ||
						get_field('organisation_google_plus_profile')
					):
				?>
				<div class="modular_bkd">
					<h2>Social Media</h2>
					<div class="organisation_social">
						<span><a target="_blank" href="<?php if(get_field('organisation_facebook_url')) { echo get_field('organisation_facebook_url'); }; ?>"><i class="ion-social-facebook"></i></a></span>
						<span><a target="_blank" href="<?php if(get_field('organisation_twitter_url')) { echo get_field('organisation_twitter_url'); }; ?>"><i class="ion-social-twitter"></i></a></span>
						<span><a target="_blank" href="<?php if(get_field('organisation_linkedin_url')) { echo get_field('organisation_linkedin_url'); }; ?>"><i class="ion-social-linkedin"></i></a></span>
						<span><a target="_blank" href="<?php if(get_field('organisation_google_plus_profile')) { echo get_field('organisation_google_plus_profile'); }; ?>"><i class="ion-social-googleplus"></i></a></span>
					</div>
				</div>
				<?php endif; ?>
				
				<div class="modular_bkd">
					<!-- <h2>Directional Map</h2> -->
					<h2>Address</h2>
					<span><i></i>Address: <p><?php echo $location_address; ?></p></span>
					<!-- <div id="organisation_map" style="height:300px;"></div> -->
				</div>
			</div>
		</div>
		<?php 
			$post_id = $post->post_author;
			$args = array(
			 'post_type'	=>	'event',
   			 'author'        =>  $post_id,
   			 'orderby'       =>  'post_date',
   			 'order'         =>  'ASC',
   			 'posts_per_page' => -1
   			 );
   			 $events = new WP_Query($args);
   			 if($events->post_count >= 1) {
   		?>
				<div class="row event_row">
					<div class="col-sm-12">
						<div class="modular_bkd mod_table">
							<h2>Events</h2>
							<?php 

   							 while($events->have_posts()):$events->the_post();
   							 	
   							 	echo '<li class="col-sm-4">
   							 			<div class="event_information">
   							 				<div class="event_date">'.do_shortcode('[event event='.$post->ID.'] #_EVENTDATES @ #_EVENTTIMES[/event]').'</div>
   							 				<div class="title_event">
   							 					<h3>'.$post->post_title.'</h3>
   							 				</div>
   							 				
   							 				<a class="view_event _button"  href="'.get_the_permalink($post->ID).'">View</a>
   							 				
   							 			</div>
   							 		  </li>';
   							 	
   							 endwhile;
   							 ?>
						</div>
					</div>
				</div>
		<?php } ?>
	</div>
</section>
<script>
<?php 
	$title = $post->post_title;
	//print_r($post);
	$website = get_field('organisation_website');
	if( is_array($website) ) {
		$web = $website['scheme'].$website['host'];
	} else {
		$web = $website;
	}
	


?>
var address = '<?php echo $location_address ?>';
var title = '<?php echo $title; ?>';
var website = '<?php echo $web; ?>';

var locations = [
    [title, address, website],
];

var geocoder;
var map;
var bounds = new google.maps.LatLngBounds();

function initialize() {
    map = new google.maps.Map(
    document.getElementById("organisation_map"), {
        center: new google.maps.LatLng(37.4419, -122.1419),
        zoom: 5,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        styles: [{"featureType":"landscape","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"poi","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"stylers":[{"hue":"#00aaff"},{"saturation":-100},{"gamma":2.15},{"lightness":12}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"visibility":"on"},{"lightness":24}]},{"featureType":"road","elementType":"geometry","stylers":[{"lightness":57}]}]
    });
    //geocoder = new google.maps.Geocoder();

  /*  for (i = 0; i < locations.length; i++) {


        geocodeAddress(locations, i);
    }*/
    
    
	var image = '<?php bloginfo( 'stylesheet_directory' ); ?>/images/map-marker.png';
    var marker = new google.maps.Marker({
        icon: image,
        map: map,
        position: {lat: <?php echo $location_lat; ?>, lng: <?php echo $location_lng; ?>},
        title: title,
        animation: google.maps.Animation.DROP,
        address: address,
        url: website
    })
    //infoWindow(marker, map, title, address, url);
    bounds.extend(marker.getPosition());
    map.fitBounds(bounds);    
    
    
    
}
google.maps.event.addDomListener(window, "load", initialize);

function geocodeAddress(locations, i) {
    var title = locations[i][0];
    var address = locations[i][1];
    var url = locations[i][2];
    geocoder.geocode({
        'address': locations[i][1]
    },

    function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
	        var image = '<?php bloginfo( 'stylesheet_directory' ); ?>/images/map-marker.png';
            var marker = new google.maps.Marker({
                icon: image,
                map: map,
                position: results[0].geometry.location,
                title: title,
                animation: google.maps.Animation.DROP,
                address: address,
                url: url
            })
            infoWindow(marker, map, title, address, url);
            bounds.extend(marker.getPosition());
            map.fitBounds(bounds);
        } else {
           // alert("geocode of " + address + " failed:" + status);
        }
    });
}

function infoWindow(marker, map, title, address, url) {
    google.maps.event.addListener(marker, 'click', function () {
        var html = "<div><h3>" + title + "</h3><p>" + address + "<br></div><a target='blank' href='" + url + "'>View location</a></p></div>";
        iw = new google.maps.InfoWindow({
            content: html,
            maxWidth: 350
        });
        iw.open(map, marker);
    });
     google.maps.event.addListener(marker, 'mouseover', function () {
        var html = "<div><h3>" + title + "</h3><p>" + address + "<br></div><a target='blank' href='" + url + "'>View location</a></p></div>";
        iw = new google.maps.InfoWindow({
            content: html,
            maxWidth: 350
        });
        iw.open(map, marker);
    });
    google.maps.event.addListener(marker, 'mouseout', function(){
          iw.close(map, marker);
       });
}

function createMarker(results) {
    var marker = new google.maps.Marker({
        icon: image,
        map: map,
        position: results[0].geometry.location,
        title: title,
        animation: google.maps.Animation.DROP,
        address: address,
        url: url
    })
    bounds.extend(marker.getPosition());
    map.fitBounds(bounds);
    infoWindow(marker, map, title, address, url);
    return marker;
}



</script>
<?php
get_footer();
?>