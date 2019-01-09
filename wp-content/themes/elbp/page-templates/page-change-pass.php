<?php
/**
 * Template Name: Change Password
 * @package WordPress
 * @subpackage East London Business Portal
 * @since East London Business Portal 1.0
 */
elbp_protect_page();
elbp_sub_protect_page();
$user = wp_get_current_user();
$user = get_user_by('id', $user->ID);
$default_roles = array('subscriber', 'administrator');

if( isset($_REQUEST['action']) && $_REQUEST['action'] == 'change_pass' ) {

	$current_pass = $_REQUEST['current_pass'];
	$pass1 = $_REQUEST['pass1'];
	$pass2 = $_REQUEST['pass2'];

	$errors = false;

	if( $current_pass == '' || $pass1 == '' || $pass2 == '') {
		elbp_set_session_message('Please fill out all of the fields', 'danger');
		$errors = true;
	}

	if(  !wp_check_password( $current_pass, $user->data->user_pass, $user->ID) ) {
		elbp_set_session_message('Current password is incorrect', 'danger');
		$errors = true;
	}

	if($pass1 != $pass2) {
		elbp_set_session_message('New passwords do not match', 'danger');
		$errors = true;
	}

	if(!$errors) {

		wp_set_password($pass1, $user->ID);
		elbp_set_session_message('Password updated', 'success');

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
		<div class="main-content main-content-dashboard col-md-9 col-xs-12 change_pass">
			<div class="container-fluid">
				<div class="table_outer">
					<div class="table_inner_mid">
							<div class=" col-sm-8 col-sm-offset-2 login-container">
								<?php if(array_intersect($default_roles, $user->roles)) elbp_get_session_messages(); ?>
								<div class="account-wrapper">
									<div class="account-password clearfix">
										<h2 class="reset_message">Change password.</h2>
										<form id="change-pass-form" action="<?php echo get_permalink($post->ID); ?>" method="post" class=" account-form">
											
											<div class="form-group field_style">
												<label class="text-fields">
													<i class="icon  ion-lock-combination placeholder-icon"></i>
													<input autocomplete="off" type="password"  name="current_pass" placeholder="Current Password" class="input_style form-control" data-bv-notempty />
												</label>
								    		</div>	
											<div class="form-group field_style">
												<label class="text-fields">
													<i class="icon  ion-lock-combination placeholder-icon"></i>
													<input autocomplete="off" type="password" id="password1" name="pass1"  placeholder="New Password" class="input_style form-control" data-bv-notempty />
												</label>
								    		</div>	
								    		<div class="form-group field_style">
												<label class="text-fields">
													<i class="icon  ion-lock-combination placeholder-icon"></i>
													<input autocomplete="off" type="password" id="password2" name="pass2"  placeholder="and again..." class="input_style form-control" data-bv-notempty />
												</label>
								    		</div>
											
											<input type="hidden" name="action" value="change_pass" value="1" />
											<div class="text-center">
												<button type="submit" class="btn btn-primary btn-lg general_fw_button">Update Password</button>
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
</div>
<script>
	jQuery(document).ready(function($){
		$('#change-pass-form').bootstrapValidator({
		    feedbackIcons: {
		        valid: 'fa fa-check',
		        invalid: 'fa fa-times',
		        validating: 'fa fa-refresh'
		    }
		});
	});
</script>
