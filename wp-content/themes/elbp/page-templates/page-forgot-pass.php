<?php
/**
 * Template Name: Forgot Password
 * @package WordPress
 * @subpackage East London Business Portal
 * @since East London Business Portal 1.0
 */
is_elbp_user_loggedin_redirect();

if( isset($_REQUEST['action']) && $_REQUEST['action'] == 'forgot_form_send' ) {
	
	$email = $_REQUEST['log'];
	$user_exists = false;
	
	if(!is_email($email)) {
		elbp_set_session_message('Email address is not valid.', 'danger');
	}
	
	if(email_exists($email) ) {
    	$user_exists = true;
        $user = get_user_by_email($email);
    } else{
        elbp_set_session_message('Username or Email was not found, please try again.', 'danger');
    }
	
	if($user_exists) {

		$newpass = wp_generate_password();

		$headers = 'From: no-reply <no-reply@getsetforgrowth.co.uk>' . "\r\n";
    	$message = __('You reset your password on East London Business Portal:') . "\r\n\r\n";
    	$message .= get_theme_info('url') . "\r\n\r\n";
    	$message .= sprintf(__('Email: %s'), $user->user_email) . "\r\n\r\n";
    	$message .= sprintf(__('New Password: %s'), $newpass) . "\r\n\r\n";
    	
    	wp_set_password($newpass, $user->ID);
    	wp_mail($user->user_email, '[East London Business Portal] Password Reset', $message, $headers);
		
		elbp_set_session_message('A new password has been emailed to you, please check your inbox.', 'success');
		
		wp_redirect(get_login_url());
		exit;
		
	}
	
}

get_header(); ?>
<section data-parallax="scroll" data-image-src="<?php echo get_theme_info('theme_url').'/images/el_cityscape_v.jpg'; ?>" class="organisation_header background_cover">
	<div class="table_outer overlay_background">
		<div class="table_inner_mid">
			<div class="container">
				<div class="col-sm-8 col-sm-offset-2 center_header">
					<h1><?php echo the_title();?></h1>
					<h5><?php echo elbp_breadcrumbs(); ?></h5>
				</div>
			</div>
		</div>
	</div>
</section>
<div id="account" class="content-container normal">
	<div class="container">
		<div class="row">
			<div class="content-wrap clearfix">		
				<div class="main-content main-content-login">
					<div class=" col-md-6 col-sm-offset-3 login-container">
							<?php elbp_get_session_messages(); ?>
							<form id="forgot-form" method="post">
								<h2 class="reset_message">Reset your password.</h2>
								
								
								 <div class="form-group field_style">
									<label class="text-fields">
										<i class="icon ion-android-drafts placeholder-icon"></i>
										<input autocomplete="off" type="email" name="log" placeholder="Your Email Address..." class="input_style form-control" data-bv-notempty data-bv-emailaddress="true" />
									</label>
							    </div>
							   
							    <input type="hidden" name="action" value="forgot_form_send" />
							    <input type="hidden" name="wp_nonce" value="<?php echo wp_create_nonce('forgot_form_nonce'); ?>" />
							    <a href="<?php echo get_login_url(); ?>" class="btn btn-default btn-lg">Back</a>
							    <button id="forgot-button" type="submit" class="btn btn-primary btn-lg">Reset Password</button>			
							</form>						
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	//jQuery(document).ready(function($){
	//	$('#forgot-form').bootstrapValidator({
	//	    feedbackIcons: {
	//	        valid: 'fa fa-check',
	//	        invalid: 'fa fa-times',
	//	        validating: 'fa fa-refresh'
	//	    }
	//	});
	//});
</script>

<?php get_footer(); ?>