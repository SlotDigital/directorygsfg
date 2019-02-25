<?php
 /**
 * Template Name: Homepage
 *
 * @package WordPress
 * @subpackage East London Business Portal
 * @since East London Business Portal 1.0
 */
get_header(); ?>
<?php 
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
<section class="main_home_search background_cover" style="background-image:url(<?php echo $actualurl;?>);">
	<div class="table_outer home_inner_background">
		<div class="table_inner_mid">
			<div class="container">
				<form id="searchform" class="searchform" action="<?php echo get_permalink(61); ?>" method="get" >
					<div class="col-sm-12 ">
						<h1>I am looking for...</h1>
						<label class="screen-reader-text" for="s"><?php _x( 'Search for:', 'label' ); ?></label>
						
						<!--<div class="col-md-3 col-sm-12 col-xs-12 select_home">
							<select name="organisations_support[]" class="selectbox selectpicker" data-header="Support Options" multiple="multiple"  multiple title="Support Types...">
								<option value="">All Types</option>
		        				<?php 
								$terms = get_terms( array(
								    'taxonomy' => 'organisations_support',
								    'hide_empty' => false,
								) );
								foreach($terms as $t) {
									?>
									<option value="<?php echo $t->term_id; ?>"><?php echo $t->name; ?></option>
								<?php 
									
									}
								?>
							</select>
						</div> -->
						
	        			<div class="col-md-4 col-sm-12 col-xs-12 select_home">
							<select name="support_topics[]" class="selectbox selectpicker" data-header="Support Options" multiple="multiple" multiple title="Topics...">
								<option value="">All Types</option>
		        				<?php 
								$terms = get_terms( array(
								    'taxonomy' => 'support_topics',
								    'hide_empty' => false,
								) );
								foreach($terms as $t) {
									?>
									<option value="<?php echo $t->term_id; ?>"><?php echo $t->name; ?></option>
								<?php 
									}
								?>
							</select>		        			
	        			</div>
	        			<div class="col-md-4 col-sm-12 col-xs-12 selectpicker select_home in">
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
								?>
								<select name="locational_search[]" placeholder="Choose location..." class="selectLocation" multiple="multiple">
									<?php foreach($json_keywords_area as $area): 
										if(@$_REQUEST['locational_search']) {
											$item = @$_REQUEST['locational_search'];
										}else {
											$item = array();
										}
										?>
										<option value="<?php echo $area['id']; ?>" <?php if(in_array($area['id'], $item)): ?> selected="selected"<?php endif; ?>><?php echo $area['text']; ?></option>
									<?php endforeach; ?>
								</select>
								
							
								
						</div>
						<!--
	        			<select class="col-md-3 col-sm-12 col-xs-12 selectpicker select_home in" data-header="Select a location" name="organisations_locations"  multiple="multiple" multiple title="Location...">
		        			<option value="">All Locations</option>
		        			<?php 
							$terms = get_terms( array(
							    'taxonomy' => 'organisations_locations',
							    'hide_empty' => false,
							) );
							foreach($terms as $t) {
								echo '<option value="'.strtolower($t->name).'">'.$t->name.'</option>';
							}
							?>
						</select> -->
						
						<div class="col-md-4 col-sm-12 col-xs-12">
	        				<input type="submit" class="form-control btn-block search-bx FadeIn" id="searchsubmit" value="search"/>
						</div>
						
					</div>
				</form>
			</div>
		</div>
	</div>
</section>

<script>
	jQuery(document).ready(function($){
		$('.selectLocation').select2({
		placeholder: "Location...",
		minimumInputLength: 1
	});
	});
</script>

<?php get_footer(); ?>
