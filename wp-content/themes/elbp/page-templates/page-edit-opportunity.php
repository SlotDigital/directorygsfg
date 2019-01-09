<?php
 /**
 * Template Name: Edit Opportunity
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
$opportunity = get_post($pid);


if( isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit_opportunity' ) {
	
	$errors = false;
		
	$opportunity_title = $_REQUEST['opportunity_title'];
	$opportunity_description = $_REQUEST['opportunity_description'];
	
	// acf //
	$opportunity_value = $_REQUEST['opportunity_value'];
	$opportunity_deadline = $_REQUEST['opportunity_deadline'];
	
	$opportunity_cname = $_REQUEST['opportunity_cname'];
	$opportunity_csurname = $_REQUEST['opportunity_csurname'];
	$opportunity_cphone_number = $_REQUEST['opportunity_cphone_number'];
	$opportunity_cemail_address = $_REQUEST['opportunity_cemail_address'];

	
	
	if($opportunity_title == '') {
		elbp_set_session_message('opportunity title is required', 'danger');
		$errors = true;
	}
	
	if(!$errors) {
		
		$args = array(
			'ID'			=>	$opportunity->ID,
			'post_title'	=>	$opportunity_title,
			'post_type'		=>	'opportunities',
			'post_content'	=>	$opportunity_description,
			'post_status'   => 'publish',
		);
		
		wp_update_post($args);
		
		
		// update meta here..
		$opportunity_id = $opportunity->ID;
	
		
		update_post_meta($opportunity_id, 'opportunity_value', $opportunity_value);
		update_post_meta($opportunity_id, 'opportunity_deadline', $opportunity_deadline);
		
		update_post_meta($opportunity_id, 'opportunity_cname', $opportunity_cname);
		update_post_meta($opportunity_id, 'opportunity_csurname', $opportunity_csurname);
		update_post_meta($opportunity_id, 'opportunity_cphone_number', $opportunity_cphone_number);
		update_post_meta($opportunity_id, 'opportunity_cemail_address', $opportunity_cemail_address);

		
		elbp_set_session_message('Your opportunity has been updated.', 'success');
		wp_redirect(get_permalink(223));
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
		<div class="main-content main-content-dashboard col-md-9 col-xs-12">
			<div class="container-fluid">
				<div class="col-sm-12">
					<div class="main_form_area row">
					<div class="col-sm-12"><?php elbp_get_session_messages(); ?></div>
						<div class="account-wrapper">
							<div class="account-opportunities">
							<form id="edit-opportunity-form" enctype="multipart/form-data" action="<?php echo get_permalink($post->ID); ?>" method="post" class="form-horizontal account-form">
													
								<h2 class="account-heading">Opportunity Information</h2>								
								
								<div class="col-sm-6">							
									<div class="modular_bkd">							
										 <div class="grp">
										  <label class="control-label">Opportunity Title</label>
										  <input type="text" class="form-control" name="opportunity_title" value="<?php echo get_the_title($pid); ?>" data-bv-notempty >
										 </div>	
										  <div class="grp">
										  <label class="control-label">Opportunity Value</label>
										  <input type="text" class="form-control" name="opportunity_value" value="<?php if(get_field('opportunity_value',$pid)) { echo get_field('opportunity_value',$pid); }; ?>" data-bv-notempty >
										 </div>	
										  <div class="grp">
										  <label class="control-label">Opportunity Deadline</label>
										  <input type="text" class="form-control" name="opportunity_deadline" value="<?php if(get_field('opportunity_deadline',$pid)) { echo get_field('opportunity_deadline',$pid); }; ?>" data-bv-notempty>
										 </div>		
									</div>
						
								
									<div class="modular_bkd">
										<div class="col-sm-12 grp">
											<h3 class="account_heading">Opportunity Description</h3>
											<div class="form-box">
												<div class="form-s">
													<textarea name="opportunity_description" rows="7" class="form-control"><?php echo $opportunity->post_content; ?></textarea>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-6">		
									<div class="modular_bkd">
										<div class="grp">
										 	 <label class="control-label">Contact First name</label>
										 	 <input type="text" class="form-control" name="opportunity_cname" value="<?php if(get_field('opportunity_cname',$pid)) { echo get_field('opportunity_cname',$pid); }; ?>" >
										 </div>	
										 <div class="grp">
										 	 <label class="control-label">Contact Surname</label>
										 	 <input type="text" class="form-control" name="opportunity_csurname" value="<?php if(get_field('opportunity_csurname',$pid)) { echo get_field('opportunity_csurname',$pid); }; ?>"  >
										 </div>	
										 <div class="grp">
										 	 <label class="control-label">Contact Phone number</label>
										 	 <input type="text" class="form-control" name="opportunity_cphone_number" value="<?php if(get_field('opportunity_cphone_number',$pid)) { echo get_field('opportunity_cphone_number',$pid); }; ?>" >
										 </div>	
										 <div class="grp">
										 	 <label class="control-label">Contact Email Address</label>
										 	 <input type="text" class="form-control" name="opportunity_cemail_address" value="<?php if(get_field('opportunity_cemail_address',$pid)) { echo get_field('opportunity_cemail_address',$pid); }; ?>"  >
										 </div>	
									</div>
									<div class="add_boxes">
										<input type="hidden" name="action" value="edit_opportunity" />
										<input type="hidden" name="id" value="<?php echo $pid; ?>" />
										<a href="<?php echo get_permalink(223); ?>" class="btn-lg btn btn-default pull-left">Cancel</a>
										<button type="submit" class="general_fw_button btn btn-primary btn-lg pull-right">Update opportunity</button>		
									</div>
								</div>	
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
		
		$('#add-opportunity-form').bootstrapValidator({
		    feedbackIcons: {
		        valid: 'fa fa-check',
		        invalid: 'fa fa-times',
		        validating: 'fa fa-refresh'
		    }
		});
	});
</script>