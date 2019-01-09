<?php
 /**
 * Template Name: My Opportunities
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
	'post_type'	=>	'opportunities',
	'author'	=>	$current_user->ID,
	'paged'		=>	$paged,
	'orderby'       =>  'post_date',
   	'order'         =>  'ASC',
   	'posts_per_page' => -1
);
$opportunities = new WP_Query($args);
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
						<h2 class="account-heading">Manage Opportunites</h2>
						<div class="text-right add_new_bar"><a href="<?php echo get_permalink(304); ?>" class="btn btn-primary add_new">Add New</a></div>
						<div class="modular_bkd">
							<div class="col-sm-12">
								<?php elbp_get_session_messages(); ?>
							 <div class="table-responsive">
								<table class="table table-striped Event-list-table opportunity_table" width="100%">
									<thead>
										<th>Opportunity Title</th>
									</thead>
									<tbody>
										<?php 
											//print_r();
											if($opportunities->post_count >= 1) {
													while($opportunities->have_posts()): $opportunities->the_post(); ?>
												<tr>
													<td><a href="<?php echo get_permalink(299); ?>?id=<?php the_ID(); ?>"><strong><?php the_title(); ?></strong></a><td><a href="<?php echo get_permalink(299); ?>?id=<?php the_ID(); ?>" class="btn btn-primary add_new">View</a></td></td>
												</tr>
												<?php endwhile; 
											} else {
												echo '<tr><td>No opportunities to display.</td></tr>';
											}
												?>
										<?php echo _paginate($opportunities); ?>
									</tbody>
								</table>
							</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>