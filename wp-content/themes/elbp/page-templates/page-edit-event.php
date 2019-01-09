<?php
 /**
 * Template Name: Edit Events
 *
 * @package WordPress
 * @subpackage East London Business Portal
 * @since East London Business Portal 1.0
 */
ini_set('display_errors', 1);
elbp_protect_page();
elbp_sub_protect_page();
$current_user = wp_get_current_user();
$organisation_id = get_user_meta($current_user->ID, 'user_organisation_id', true); 

$pid = $_REQUEST['id'];
$event = get_post($pid);


if( isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit_event' ) {
	
	$errors = false;
		
	$event_title= $_REQUEST['event_title'];

	
	
	if($event_title == '') {
		elbp_set_session_message('Event title is required', 'danger');
		$errors = true;
	}
	
	if(!$errors) {
		
		$args = array(
			'ID'			=>	$event->ID,
			'post_title'	=>	$event_title,
			'post_type'		=>	'event',
			'post_content'	=>	$event_desc,
			'post_status'   => 'publish',
		);
		
		wp_update_post($args);
		
		
		// update meta here..
		
		//update_post_meta($event->ID, 'job_school_id', $school_id);


		
		elbp_set_session_message('Your Event has been edited.', 'success');
		wp_redirect(get_permalink(221));
		exit;
		
	}

}

get_header(); ?>
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
		<div class="main-content main-content-dashboard col-sm-9">
			<div class="container-fluid">
				<div class="col-sm-12">
					<div class="main_form_area row">
					<div class="col-sm-12"><?php elbp_get_session_messages(); ?></div>
						<div class="account-wrapper">
							<div class="account-events">
								<form id="add-event-form" enctype="multipart/form-data" action="<?php echo get_permalink($post->ID); ?>" method="post" class="form-horizontal account-form">
														
									<h2 class="account-heading">Event Information</h2>	
									<div class="col-sm-12">							
										<div class="modular_bkd">							
											 <div class="grp">
											  <label class="control-label">Event Title</label>
											  <input type="text" class="form-control" name="event_title" value="<?php echo get_the_title($pid); ?>" data-bv-notempty >
											 </div>		
										</div>
									</div>
						
									
									<input type="hidden" name="action" value="edit_event" />
									<input type="hidden" name="id" value="<?php echo $pid; ?>" />
									<a href="<?php echo get_permalink(221); ?>" class="btn-lg btn btn-default pull-left">Cancel</a>
									<button type="submit" class="btn btn-primary btn-lg pull-right">Update Event</button>										
								</form>
								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	jQuery(document).ready(function($){
		
		$('#add-event-form').bootstrapValidator({
		    feedbackIcons: {
		        valid: 'fa fa-check',
		        invalid: 'fa fa-times',
		        validating: 'fa fa-refresh'
		    }
		});
	});
</script>
<?php get_footer(); ?>