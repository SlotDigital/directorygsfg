<?php
 /**
 * Template Name: Search
 *
 * @package WordPress
 * @subpackage East London Business Portal
 * @since East London Business Portal 1.0
 */

get_header(); ?>
<?php
// Sort the post meta & paginate
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$showposts = (isset($_REQUEST['per_page'])) ? $_REQUEST['per_page'] : 10;
$orderby = (isset($_REQUEST['sort_by'])) ? $_REQUEST['sort_by'] : 'title-asc';
// Grab the posts
$args = array(
	'post_type'	=>	'organisations',
	'paged'		=>	$paged,
	'showposts'	=>	$showposts
	
);
// ORDERING DATA //

$per_page_ranges = array(10,50,100,200);

$orderby_ranges = array(
	'Date Posted (Oldest)'		=>	'date-asc',
	'Date Posted (Newest)'	=>	'date-desc',
	'Title (A-Z)'		=>	'title-asc',
	'Title (Z-A)'		=>	'title-desc',
);

if( $orderby ) {
	$orderby_parts = explode('-', $orderby);
	$orderbykey = $orderby_parts[0];
	$direction = $orderby_parts[1];
	switch($orderbykey):
		case 'date':
		case 'title':
			$args['orderby'] = $orderbykey;
		break;
	endswitch;
	$args['order'] = $direction;
}
// Grab the correct taxonomy in meta //



if( isset($_REQUEST['organisations_locations']) && $_REQUEST['organisations_locations'] != '0' ) {
	
	if($_REQUEST['organisations_locations'][0] != ''):
	
	$args['tax_query'][] = array(
		'taxonomy'	=>	'organisations_locations',
		'field'		=>	'term_id',
		'terms'		=>	$_REQUEST['organisations_locations']
	);
	
	endif;
	
}

if( isset($_REQUEST['organisations_support']) && $_REQUEST['organisations_support'] != '0') {
	if($_REQUEST['organisations_support'][0] != ''):
	$args['tax_query'][] = array(
		'taxonomy'	=>	'organisations_support',
		'field'		=>	'term_id',
		'terms'		=>	$_REQUEST['organisations_support']
	);
	endif;
}
if( isset($_REQUEST['support_topics']) && $_REQUEST['support_topics'] != '0') {
	
	if($_REQUEST['support_topics'][0] != ''):
	$args['tax_query'][] = array(
		'taxonomy'	=>	'support_topics',
		'field'		=>	'term_id',
		'terms'		=>	$_REQUEST['support_topics']
	);
	endif;
}

if( isset($_REQUEST['keyword_search']) && $_REQUEST['keyword_search'] != '0') {
	
	$args['tax_query'][] = array(
		'taxonomy'	=>	'keywords',
		'field'		=>	'term_id',
		'terms'		=>	$_REQUEST['keyword_search']
	);
}

if( isset($_REQUEST['locational_search']) && $_REQUEST['locational_search'] != '0') {
	
	$args['tax_query'] = array(
		'relation' => 'OR',
		array(
			'taxonomy'	=>	'organisations_locations',
			'field'		=>	'term_id',
			'terms'		=>	$_REQUEST['locational_search']
		),
		array(
			'taxonomy'	=>	'organisations_areas',
			'field'		=>	'term_id',
			'terms'		=>	$_REQUEST['locational_search']
		),
	);
	//print_r($args);
}
// get new location data //



//$csv = array_map('str_getcsv', file(ELBP_INC.'/locationstore/locations.csv'));
//array_walk($csv, function(&$a) use ($csv) {
//   $a = array_combine($csv[0], $a);
// });
// array_shift($csv); # remove column header
// $items = array();
// foreach($csv as $item) {
//     $items[] = $item['County'];
// }
// print_r(array_unique($items));




#echo '<pre>';
#var_dump($args['tax_query']);

// set the argument //
// if a user searches for a company name we should probably omit all other filters...
// as most likely get 0 results
if( isset($_REQUEST['organisation_search']) && $_REQUEST['organisation_search'] != '') {
	
	$args['s'] = $_REQUEST['organisation_search'];
	unset($args['tax_query']);
	
}
// --- 

$organisations = new WP_Query($args);
// images to load on random for backgrond //
$directory = get_template_directory()."/images/newImages"; // doesnt work using URLS!
$images = glob($directory."/*.{jpg,jpeg,png,gif}", GLOB_BRACE);
$images_root = array();

foreach($images as $image) {
	//echo $image;
	$images_root[] = $image;
}
$randomImage = $images_root[array_rand($images_root)];
$end = array_slice(explode('/', $randomImage), -1)[0];
$actualurl = get_theme_info('theme_url').'/images/newImages/'.$end;

?>
<section data-parallax="scroll" data-image-src="<?php echo $actualurl; ?>" class="image_header background_cover">
	<div class="table_outer overlay_background">
		<div class="table_inner_mid">
			<div class="container">
				<h1><?php echo the_title();?></h1>
			</div>
		</div>
	</div>
</section>
<section class="search_page">
	<div class="container-fluid row-no-padding row-eq-height min_search">
		<div class="col-sm-4 filter_inner">
			<div class="container-fluid">
				<div class="filter_search">
					<div class="search_point"></div>
					<div class="filter_heading">
						<h4>Filter results</h4>
					</div>
					<form autocomplete="off" method="get" class="form" id="organisation_search_filters" class="form_results" action="">
						<div class="form-group field_style">
							<label class="search-fields">
							<i class="icon ion-search placeholder-icon"></i>
							<input type="text" name="organisation_search" placeholder="Company Name..." class="input_style form-control" value="<?php if(isset($_REQUEST['organisation_search'])) echo $_REQUEST['organisation_search']; ?>"</label>
						</div>
						
						<div class="form-group field_style">	
							<label class="main_labels" style="display:block;">Keywords:</label>	
							<?php
								$keywords = get_terms(
									array(
										'taxonomy'	=>	'keywords',
										'hide_empty'	=>	false
									)
								);	
								$json_keywords = array();
								
								foreach($keywords as $kw) {
									$json_keywords[] = array(
										'id'	=>	$kw->term_id,
										'text'	=>	$kw->name
									);
								}
							
							?>							
							<select name="keyword_search[]" placeholder="Choose keywords..." class="select2" multiple="multiple">
								<?php foreach($json_keywords as $kw): 
									if(@$_REQUEST['keyword_search']) {
										$item = @$_REQUEST['keyword_search'];
									}else {
										$item = array();
									}
									?>
								<option value="<?php echo $kw['id']; ?>" <?php if(in_array($kw['id'], $item)): ?> selected="selected"<?php endif; ?>><?php echo $kw['text']; ?></option>
								<?php endforeach; ?>
							</select>
						</div>						
						
						<div class="form-group field_style clearfix">
							<label class="main_labels" style="display:block;">By Location:</label>
		        				<?php 
			        			$count= 0;
								$terms = get_terms( array(
								    'taxonomy' => array('organisations_locations', 'organisations_areas'),
								    'hide_empty' => false,
								));
								
								$json_keywords_area = array();
								
								foreach($terms as $area) {
									$json_keywords_area[] = array(
										'id'	=>	$area->term_id,
										'text'	=>	$area->name
									);
								}

								if(@$_REQUEST['locational_search']) {
									$item = @$_REQUEST['locational_search'];
								}else {
									$item = array();
								}
								?>
								<select name="locational_search[]" placeholder="Choose location..." class="selectLocation" multiple="multiple">
									<?php foreach($json_keywords_area as $area): ?>
										<option value="<?php echo $area['id']; ?>" <?php if(in_array($area['id'], $item)): ?> selected="selected"<?php endif; ?>><?php echo $area['text']; ?></option>
									<?php endforeach; ?>
								</select>
								
							
								
						</div>
						<div class="form-group field_style">
							<label class="main_labels">Support Type:</label>
							<select name="organisations_support[]" class="selectbox selectpicker" data-header="Support Options" multiple="multiple">
								<option value="0">All Types</option>
		        				<?php 
								$terms = get_terms( array(
								    'taxonomy' => 'organisations_support',
								    'hide_empty' => false,
								) );
								foreach($terms as $t) {

									if(@$_REQUEST['organisations_support']) {
										$item = @$_REQUEST['organisations_support'];
									}else {
										$item = array();
									}
									?>
									<option value="<?php echo $t->term_id; ?>" <?php if(in_array($t->term_id, $item)): ?> selected="selected" <?php endif; ?>><?php echo $t->name; ?></option>
								<?php 
									
									}
								?>
							</select>
							
							
							
							
							
							
							
						</div>
						<div class="form-group field_style">
							<label class="main_labels">Support Topics:</label>
							<select name="support_topics[]" class="selectbox selectpicker" data-header="Support Options" multiple="multiple">
								<option value="0">All Types</option>
		        				<?php 
								$terms = get_terms( array(
								    'taxonomy' => 'support_topics',
								    'hide_empty' => false,
								) );
								foreach($terms as $t) {
									if(@$_REQUEST['support_topics']) {
										$item = @$_REQUEST['support_topics'];
									}else {
										$item = array();
									}
									?>
									<option value="<?php echo $t->term_id; ?>"  <?php if(in_array($t->term_id, $item)): ?> selected="selected" <?php endif; ?>><?php echo $t->name; ?></option>
								<?php 
									}
								?>
							</select>
						</div>
						<button type="submit" class="btn btn-primary btn-block btn-lg">Filter Results</button>
						<input type="hidden" name="sort_by" value="<?php echo $orderby; ?>" id="sort-by-clone" />
						<input type="hidden" name="per_page" value="<?php echo $showposts; ?>" id="per-page-clone" />
					</form>
				</div>
			</div>
		</div>
		<div id="search_p" class="col-sm-8 flex-col">
			<div class="inner_search">
				<div class="search_startup">
					<h3>Search Results</h3>
					<div class="Aligner">
						<div class="col-md-6">
							<strong class="result-count">
								<?php if($organisations->found_posts > 0): ?>
								Showing <?php echo (1+get_query_var('posts_per_page')*(get_query_var('paged')?get_query_var('paged')-1:0)) . '-' . ($organisations->post_count+get_query_var('posts_per_page')*(get_query_var('paged')?get_query_var('paged')-1:0)) . ' of ' . $organisations->found_posts; ?>
								<?php else: ?>
								0 results
								<?php endif; ?>
							</strong>
						</div>
						<div class="col-md-6 text-right Aligner filter_options">
							<span class="lab">Order by</span>
							<select id="sort-by" name="sort_by" class="selectbox">
								<?php foreach($orderby_ranges as $label => $r): ?>
								<option value="<?php echo $r; ?>"<?php echo selected($orderby,$r); ?>><?php echo $label; ?></option>
								<?php endforeach; ?>
							</select>
							<select id="per-page" name="per_page" class="selectbox">
								<?php foreach($per_page_ranges as $r): ?>
								<option value="<?php echo $r; ?>"<?php echo selected($showposts,$r); ?>><?php echo $r; ?></option>
								<?php endforeach; ?>
							</select>
							<span class="lab">per page</span>
							<a href="#search-filters" id="search_filter" class="btn btn-default hidden-lg hidden-md hidden-sm"><i class="fa fa-filter"></i></a>
						</div>
					</div>
				</div>
				<ul class="list-unstyled organisation_search">
					<?php while($organisations->have_posts()): $organisations->the_post(); 
					if(has_post_thumbnail()) {
						$image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
						$image_url = $image[0];
					} else {
						$image_url = 'http://placehold.it/1500x1500';
					}
					?>
						<li class="col-lg-6 " style="padding-right: 15px !important;">
							<div class="organisation_listing row-no-padding Aligner">
								<div class="col-sm-3">
									<div class="table_outer">
										<div class="table_inner_mid">
											<div class="organisation_logo background_contain" style="background-image:url('<?php echo $image_url; ?>');"></div>
										</div>
									</div>
								</div>
								<div class="col-sm-7">
									<div class="organisation_item_inner">
										<h2><?php echo the_title();?></h2>
										<?php 
											$website = get_field('organisation_website');
								
										?>
										<?php if($website) { ?><span><a href="<?php echo http_check($website); ?>" target="_blank"><i class="ion-android-cloud"></i> Visit Website</a></span><?php } ?>
										<?php if(get_field('organisation_phone_number')) { ?><span><a href="tel:<?php echo get_field('organisation_phone_number'); ?>"><i class="ion-ios-telephone-outline"></i> <?php echo get_field('organisation_phone_number'); ?></a></span></br><?php } ?>
										<?php if(get_field('organisation_email')) { ?><span><a href="mailto:<?php echo antispambot(get_field('organisation_email')); ?>"><i class="ion-android-drafts"></i> Send Email</a></span><?php } ?>
										<?php if( has_tag() ): ?>
											<div class="tag-meta">
											    <?php echo the_tags('',''); ?>
											</div>
										<?php endif; ?>
									</div>
								</div>
								<div class="col-sm-2">
									<a class="view_button" href="<?php echo the_permalink($post->ID);?>">View</a>
								</div>
							</div>
							<div class="organisation_opts Aligner row-no-padding">
								<?php $post_id = $post->post_author;
									  $counter = array();
									  $args = array(
									   'post_type'	=>	'event',
   									   'author'        =>  $post_id,
   									   'orderby'       =>  'post_date',
   									   'order'         =>  'ASC',
   									   'posts_per_page' => -1
   									   );
   									   $events = new WP_Query($args);
   									   $count=0;
   									   while($events->have_posts()):$events->the_post();
   									   	$counter[] = $count;
   									   $count++;
   									   endwhile;
   									   $counted_events = count($counter);?>
								<div class="col-xs-6">
									<div class="b_opts_button b_events <?php if($counted_events < 1) { echo 'no_posts';} ?>">
										<?php 
												
   										if($counted_events > 1) {
   											echo '<span></span><strong>'.$counted_events.'</strong> Events ';
   										} elseif ($counted_events == 1){
	   										echo '<span></span><strong>'.$counted_events.'</strong> Event ';
   										} else {
	   										echo '<span></span><strong>0</strong> Events ';
   										}
   											
   										?>
									</div>
								</div>
								<div class="col-xs-6">
									<?php $post_id = $post->post_author;
										  $counter = array();
										  $args = array(
										   'post_type'	=>	'opportunities',
   										   'author'        =>  $post_id,
   										   'orderby'       =>  'post_date',
   										   'order'         =>  'ASC',
   										   'posts_per_page' => -1
   										   );
   										   $events = new WP_Query($args);
   										   $count=0;
   										   while($events->have_posts()):$events->the_post();
   										   	$counter[] = $count;
   										   $count++;
   										   endwhile;
   										   $counted_events = count($counter);?>
   												 
									<div class="b_opts_button b_opportunities <?php if($counted_events < 1) { echo 'no_posts';} ?>">
												<?php 
												
   										if($counted_events > 1) {
   											echo '<span></span><strong>'.$counted_events.'</strong> Opportunities ';
   										} elseif ($counted_events == 1){
	   										echo '<span></span><strong>'.$counted_events.'</strong> Opportunity ';
   										} else {
	   										echo '<span></span><strong>0</strong> Opportunities ';
   										}
   											
   										?>
										
									</div>
								</div>
							</div>
					
						</li>
					<?php endwhile; ?>
				</ul>
				<?php echo _paginate($organisations); ?>
			</div>
			<?php get_footer();?>
		</div>
</section>


<script>
    jQuery(document).ready(function($){
	    // filter toggle //
	    
	    $( "#search_filter" ).click(function() {
		  $( ".filter_search" ).slideToggle( "slow" );
		});
	    
	    
    	$('#sort-by, #per-page').selectBoxIt();
		$('.selectbox').selectBoxIt();
		var SearchFiltersForm = $('#organisation_search_filters');

    	$('#sort-by').bind({
	    	'changed': function(ev, obj) {
	    		$('#sort-by-clone').val(this.value);
	    		SearchFiltersForm.submit();
	    	}
    	});
    	$('#per-page').bind({
	    	'changed': function(ev, obj) {
	    		$('#per-page-clone').val(this.value);
		    	SearchFiltersForm.submit();
	    	}
    	});
   
 
$('.selectpicker').selectpicker({

  size: 7
});


	$('.select2').select2({
		placeholder: "Start typing...",
		minimumInputLength: 1
	});
	
	
	$('.selectLocation').select2({
		placeholder: "Location...",
		minimumInputLength: 1
	});
 
    
    	// fix the filter elements //
	var bottom = $('.search_point').offset().top ;
	$(window).scroll(function(){    
		
		var h = window.innerHeight;
		
		//if( h > 750) {
		
	    if ($(this).scrollTop() > bottom - 80){ 
	        $('.filter_search').addClass('fixed'); 
	    }
	    else{
	        $('.filter_search').removeClass('fixed');
	    }
	    //}
	    
	    
	});	
	});
</script>
