<?php
 /**
 * Template Name: My Organisation
 *
 * @package WordPress
 * @subpackage East London Business Portal
 * @since East London Business Portal 1.0
 */

elbp_protect_page();
elbp_sub_protect_page();
$current_user = wp_get_current_user();
$organisation_id = get_user_meta($current_user->ID, 'user_organisation_id', true); 
$args = array(
   'post_type'	=>	'organisations',
   'author'        =>  $current_user->ID,
   'orderby'       =>  'post_date',
   'order'         =>  'ASC',
   'posts_per_page' => -1
   );
$orgs = new WP_Query($args);
$errors = false;

//print_r($orgs);
$count=0;

$check_orgs = array();
while($orgs->have_posts()):$orgs->the_post();
   $check_orgs[] = $count;
   $count++;
endwhile;

if($organisation_id == '' && empty($check_orgs)) {
	if( isset($_REQUEST['action']) && $_REQUEST['action'] == 'add_org' ) {
		$organisation_title = $_REQUEST['organisation_title'];
		if($organisation_title == '') {
			elbp_set_session_message('Organisation title is required', 'danger');
			$errors = true;
		}
		if(!$errors) {
			elbp_set_session_message('Your Organisation has been posted.', 'success');
			//wp_redirect(get_permalink(231));
			//exit;
		}
	}
} else {
	if( isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit_org' ) {
		$organisation_title = $_REQUEST['organisation_title'];
		if($organisation_title == '') {
			elbp_set_session_message('Organisation title is required', 'danger');
			$errors = true;
		}
		if(!$errors) {
			elbp_set_session_message('Your Organisation has been updated.', 'success');
			//wp_redirect(get_permalink(231));
			//exit;
		}
	}
}

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
					<?php elbp_get_session_messages(); ?>
					<div class="main_form_area row">
						<?php 
							
							
							  if($organisation_id == '' && empty($check_orgs)) {
								 add_organisation();
							  } else {
							  	
							  	edit_organisation();
							  }
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>