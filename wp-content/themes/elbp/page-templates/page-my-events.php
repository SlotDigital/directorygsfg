<?php
 /**
 * Template Name: My Events
 *
 * @package WordPress
 * @subpackage East London Business Portal
 * @since East London Business Portal 1.0
 */
elbp_protect_page();
elbp_sub_protect_page();
$current_user = wp_get_current_user();
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

$args = array(
	'post_type'	=>	'event',
	'author'	=>	$current_user->ID,
	'paged'		=>	$paged,
	'orderby'       =>  'post_date',
   	'order'         =>  'ASC',
   	'posts_per_page' => -1
);
$events = new WP_Query($args);


get_header(); 

?>

<section class="account">
	<div class="row-no-padding row-eq-height">
		<div class="sidebar col-md-3 hidden-sm hidden-xs">
			<div class="table_outer">
				<div class="table_inner_top">
					<div class="search_point"></div>
					<?php 
						if($current_user->roles[0] == 'subscriber') {
								get_subscriber_menu();
							} else {
								get_account_menu();
							}
						?>
				</div>
			</div>
		</div>
		<div class="main-content main-content-dashboard col-md-9 col-xs-12">
			<div class="container-fluid">
				<div class="col-sm-12">
					<div class="main_form_area">
						
						<h2 class="account-heading">My Events</h2>
						<div class="modular_bkd">
							<div class="col-sm-12">
							 <div class="table-responsive events_manage">
								<?php the_content(); ?>
							</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<script>
	jQuery(document).ready(function($){
		$('select').addClass('form-control');
	});
</script>