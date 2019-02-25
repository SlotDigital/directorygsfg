<?php
	
/**
 * Single Opportunity
 *
 * @package WordPress
 * @subpackage East London Business Portal
 * @since East London Business Portal 1.0
 */
 get_header();
 
 ?>
 <section data-parallax="scroll" data-image-src="<?php echo get_theme_info('theme_url').'/images/el_cityscape_v.jpg'; ?>" class="event_heading organisation_header background_cover">
	<div class="table_outer overlay_background">
		<div class="table_inner_mid">
			<div class="container Aligner">
				<div class="col-sm-9 col-xs-12">
					<h1><?php echo the_title();?></h1>
					<!--<h5><?php echo elbp_breadcrumbs(); ?></h5>-->
				</div>
			</div>
		</div>
	</div>
</section>
<section class="main_organisation page_layout">
 <div class="container">
	 <div class="col-sm-8 event_content_page">
		 <?php if(get_field('opportunity_value')){ ?>
		 	<div class="modular_bkd <?php if(get_field('bid_deadline')){ echo 'col-sm-6';}?>">
			 	<h2>Opportunity Value</h2>
		 		<div class=""><?php echo get_field('opportunity_value'); ?></div>
		 	</div>
		 	<?php if(!get_field('bid_deadline')){ echo '<div class="col-sm-6"></div>';}?>
		 <?php } ?>
		  <?php if(get_field('bid_deadline')){ ?>
		 	<div class="modular_bkd col-sm-6">
			 	<h2>Opportunity Deadline</h2>
		 		<div class=""><?php echo get_field('bid_deadline'); ?></div>
		 	</div>
		 <?php } ?>
		 <div class="modular_bkd col-sm-12">
			 <h2>Opportunity Information</h2>
		 	<?php the_content();?>
		 </div>
		 <?php if(get_field('name')){ ?>
		 	<div class="modular_bkd col-sm-12 Aligner op_contact_info">
			 	<div class="col-sm-1 no_pad">
				 	<div class="user_icon"><i class="ion-person"></i></div>
			 	</div>
			 	<div class="col-sm-11">
				 	<h2>Contact Information</h2>
				 	<?php echo get_field('name'); ?>
				 	<?php echo get_field('surname'); ?>
				 	<div class="contact_details">
					 	<a href="tel:<?php echo get_field('phone_number'); ?>"><i class="ion-ios-telephone"></i><?php echo get_field('phone_number'); ?></a>
					 	<a href="mailto:<?php echo get_field('email_address'); ?>"><i class="ion-ios-email"></i><?php echo get_field('email_address'); ?></a>
				 	</div>
			 	</div>
		 		
		 	</div>
		 <?php } ?>
	 </div>
	 <div class="col-sm-4 filter_inner quicksearch_page">
			<div class="container-fluid">
				<div class="filter_search">
					<div class="search_point"></div>
					<div class="filter_heading">
						<h4>Quick Search</h4>
					</div>
					<form autocomplete="off" method="get" class="form" id="organisation_search_filters" class="form_results" action="<?php echo get_permalink(61); ?>">
						<div class="form-group field_style">
							<label class="search-fields">
							<i class="icon ion-search placeholder-icon"></i>
							<input type="text" name="organisation_search" placeholder="Searching for..." class="input_style form-control" value="<?php if(isset($_REQUEST['organisation_search'])) echo $_REQUEST['organisation_search']; ?>"</label>
						</div>
						<div class="form-group field_style">
							<label class="main_labels">By Location:</label>
		        				<?php 
			        			$count= 0;
								$terms = get_terms( array(
								    'taxonomy' => 'organisations_locations',
								    'hide_empty' => false,
								) );
								
								foreach($terms as $t) {
									//print_r($_REQUEST['organisations_locations[]']);
									$checked = null;
								
									//print_r($_REQUEST['organisations_locations']);
									//print_r($t->slug);
									if(isset($_REQUEST['organisations_locations']) && $_REQUEST['organisations_locations'] == $t->slug ) {
										
											$checked = ' checked="checked"';
									}
									elseif(isset($_REQUEST['organisations_locations']) && is_array($_REQUEST['organisations_locations']) && in_array(strtolower($t->name), $_REQUEST['organisations_locations'])	) {
										$checked = ' checked="checked"';
									}
									
								?>
								<div class="checkbox checkbox-info checkbox-circle">
								    <input id="check<?php echo $count;?>"type="checkbox" name="organisations_locations[]" class="others styled" value="<?php echo strtolower($t->name); ?>"<?php echo $checked; ?>> <label for="check<?php echo $count;?>"> <?php echo $t->name; ?> </label>
								</div> <?php
								$count++;
								}
								?>
						</div>
						<div class="form-group field_style">
							<label class="main_labels">Support Type:</label>
							<select name="organisations_support" class="selectbox selectpicker" data-header="Support Options">
								<option value="0">All Types</option>
		        				<?php 
								$terms = get_terms( array(
								    'taxonomy' => 'organisations_support',
								    'hide_empty' => false,
								) );
								foreach($terms as $t) {
									?>
									<option value="<?php echo $t->slug; ?>" <?php if(isset($_REQUEST['organisations_support'])) selected($_REQUEST['organisations_support'],$t->slug); ?>><?php echo $t->name; ?></option>
								<?php }
								?>
							</select>
						</div>
						<div class="form-group field_style">
							<label class="main_labels">Support Topics:</label>
							<select name="support_topics" class="selectbox selectpicker" data-header="Support Topics">
								<option value="0">All Topics</option>
		        				<?php 
								$terms = get_terms( array(
								    'taxonomy' => 'support_topics',
								    'hide_empty' => false,
								) );
								foreach($terms as $t) { ?>
									<option value="<?php echo $t->slug; ?>" <?php if(isset($_REQUEST['support_topics'])) selected($_REQUEST['support_topics'],$t->slug); ?>><?php echo $t->name; ?></option> <?php

								}
								?>
							</select>
						</div>
						<button type="submit" class="btn btn-primary btn-block btn-lg">Search</button>
					</form>
				</div>
			</div>
		</div>

 </div>
</section>
 <?php get_footer();?>