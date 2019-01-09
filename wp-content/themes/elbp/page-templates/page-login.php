<?php
/**
 * Template Name: Login Page
 *
 * @package WordPress
 * @subpackage East London Business Portal
 * @since East London Business Portal 1.0
 */


is_elbp_user_loggedin_redirect();

if( isset($_REQUEST['action']) && $_REQUEST['action'] == 'login_form_send' ) {
	
	$username = $_REQUEST['log'];
	$password = $_REQUEST['pwd'];
	$remember = $_REQUEST['rememberme'];	
	
	if( $remember == 'forever') {
		$remember = true;
	} else {
		$remember = false;
	}
	
	$_user = get_user_by_email($username);
	if($_user) $username = $_user->user_login;
	
	$return_url = get_login_url();
	
	// check if approved here.
	$approved = get_user_meta($_user->ID, 'approved', true);

	if( isset($approved) ) {
		if( $approved == 0 ) {
			elbp_set_session_message('Your account is not currently active.', 'danger');	
			wp_redirect($return_url);
			die();			
		}
	}
	
	$auth = wp_authenticate($username, $password);
	
	
	
	if( is_wp_error($auth) ) {
				
		elbp_set_session_message('Invalid username or password, please try again.', 'danger');	
		wp_redirect($return_url);
		die();
		
	} else { 
		
		if(isset($_REQUEST['redirect']) && $_REQUEST['redirect'] != '') {
			$redirect = $_REQUEST['redirect'];
		} else {
			$redirect = get_permalink(216);
		}
		
        $user = get_userdatabylogin( $username );
        $user_id = $user->ID;
        wp_set_current_user( $user_id, $username );
        wp_set_auth_cookie( $user_id, $remember );
        do_action( 'wp_login', $username );      
        elbp_set_session_message('You have been logged in successfully.', 'success'); 
		wp_redirect($redirect);	
		die();
		
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
							<form id="login-form" method="post" autocomplete="off" >
							    <div class="form-group field_style">
									<label class="text-fields">
										<i class="icon ion-android-drafts placeholder-icon"></i>
										<input autocomplete="off" type="email" name="log" placeholder="Your Email Address..." class="input_style form-control" data-bv-notempty data-bv-emailaddress="true" />
									</label>
							    </div>
							    <div class="form-group">
							    	<label class="text-fields">
										<i class="icon  ion-lock-combination placeholder-icon"></i>
										<input autocomplete="off" type="password" name="pwd" placeholder="Password" class="input_style form-control" data-bv-notempty />
									</label>
							    	
							    </div>	
							    <div class="rememberme">
									<label for="rememberme">
										<input type="checkbox" name="rememberme" value="forever" checked="checked" id="rememberme" tabindex="13" /> Remember me
									</label>
								</div>
							    <input type="hidden" name="action" value="login_form_send" />
							    <?php if( isset($_REQUEST['redirect_to']) && $_REQUEST['redirect_to']!=''):  ?>
							    <input type="hidden" name="redirect" value="<?php echo $_REQUEST['redirect_to']; ?>" />
							    <?php endif; ?>
							    <input type="hidden" name="wp_nonce" value="<?php echo wp_create_nonce('login_form_nonce'); ?>" />
							    <button type="submit" class="form-control search-bx FadeIn" id="searchsubmit" value="login">Login</button>	
							</form>
							<div class="col-sm-12">
								<div class="forgot">
									<a href="<?php echo get_forgot_url(); ?>" class="">Forgot Password</a>
								</div>	
								<a class="forgot cr_account" href="<?php echo home_url().'/register';?>">Create an account</a> 
							</div>						
						</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	jQuery(document).ready(function($){
	  $('#login-form').bootstrapValidator({
	      feedbackIcons: {
	          valid: 'fa fa-check',
	          invalid: 'fa fa-times',
	          validating: 'fa fa-refresh'
	      }
	  });
	});
</script>
<?php get_footer(); ?>