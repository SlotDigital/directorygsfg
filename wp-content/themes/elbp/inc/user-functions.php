<?php
/**
 * User Functions
 *
 * @package WordPress
 * @subpackage East London Business Portal
 * @since East London Business Portal 1.0
 */


class elbp_Users {
		
    /*
     * Start her up
    */		
	public function __construct() {
		
		// Remove Admin Bar for All except Admins
		add_action('after_setup_theme', array($this,'remove_admin_bar') );
		
		// Admin Menu
		//add_action('admin_menu' , array($this,'add_csv_import_page')); 
		
		add_action( 'admin_init', array($this,'_redirect_admin') );

		// User Profile Fields
		add_action( 'show_user_profile', array( $this, '_custom_user_profile_fields') );
		add_action( 'edit_user_profile', array( $this, '_custom_user_profile_fields') );
		add_action( 'personal_options_update', array( $this, '_save_user_profile_fields') );
		add_action( 'edit_user_profile_update', array( $this, '_save_user_profile_fields') );	
			
	}


	
    /*
     * Add additional user meta
    */		
	public function _custom_user_profile_fields($user) {
	?>
	<h3>Organisation</h3>

	<table class="form-table">
		<tr>
			<th><label>Associated Organisation</label></th>
			<td>
			    <?php $sid = get_the_author_meta( 'user_organisation_id', $user->ID ) ; 
				    //print_r(user_id);
			    ?>
				<select name="user_organisation_id">
					<option value="">Select a Organisation</option>
					<?php
						$args = array(
							'post_type'	=>	'organisations',
							'showposts'	=>	-1
						);
						$organisations = get_posts($args);
					?>
					<?php foreach($organisations as $org): ?>
					<option value="<?php echo $org->ID; ?>"<?php selected($org->ID, $sid); ?>><?php echo $org->post_title; ?></option>
					<?php endforeach; ?>
				</select>					
			</td>
		</tr>
	</table>	
	<?php
		//$user_id = get_current_user_id();
	}
	
    /*
     * Save the additional meta on update
    */		
    
	public function _save_user_profile_fields($user_id) {
	
		if ( !current_user_can( 'edit_user', $user_id ) )
			return false;		

		// Optional Information
		//print_r($sid);
		$sid = $_REQUEST['user_organisation_id'];
		update_user_meta( $user_id, 'user_organisation_id', $sid);
		
				
	}
	
	
    /*
     * Remove Admin Bar Function
    */	
	public function remove_admin_bar() {
		if (!current_user_can('administrator') && !is_admin()) {
			show_admin_bar(false);
		}
	}
	
	function _redirect_admin(){
		if ( ! current_user_can( 'edit_posts' ) && !defined( 'DOING_AJAX' ) ){
			wp_redirect( get_account_url() );
			exit;		
		}
	}	
	
}
$ELBP_Users = new ELBP_Users();



/* THEME FUNCTIONS */

/**
 * Check if User is Logged In i.e redirect if they try to access login page etc
 */
function is_elbp_user_loggedin_redirect($redirect = '') {
	if( is_user_logged_in() ) {
		if($redirect == '') $redirect = get_theme_info('url');
		wp_redirect($redirect);
		exit;	
	}
}

/**
 * Protect Pages and Forward to login if there is no session
 */
function elbp_protect_page($redirect = '') {
	global $wp;
	$current_url = home_url(add_query_arg(array(),$wp->request));
	if($redirect != '') $current_url = $redirect;
	if( !is_user_logged_in() ) {
		elbp_set_session_message('You need to be a registered user and logged in to view that page.', 'info');
		wp_redirect(add_query_arg('redirect_to', $current_url, get_home_url()));
		exit;
	}
}

function elbp_sub_protect_page($redirect = '') {
	global $wp;
	$current_url = home_url(add_query_arg(array(),$wp->request));
	if($redirect != '') $current_url = $redirect;
	
	$current_user = wp_get_current_user();
	if($current_user->roles[0] == 'subscriber'){
		elbp_set_session_message('You need to be a registered user and logged in to view that page.', 'info');
		wp_redirect(add_query_arg('redirect_to', $current_url, get_home_url()));
		exit;
	}
}

/**
 * Get Login Page URL
 */
function get_login_url($redirect='') {
	$login_url = get_permalink(119);
	if( $redirect != '') {
		$login_url = add_query_arg('redirect_to', $redirect, $login_url);
	}
	return $login_url;	
}

/**
 * Get Forgot Password Page URL
 */
function get_forgot_url() {
	return get_permalink(218);		
}


/**
 * Get My Acc Page URL
 */
function get_account_url() {
	return get_permalink(216);		
}

// account side bar menu

function get_account_menu() {
	?>
	<ul class="nav">
		<?php
		    $menu_args = array(
		        'container'			=>	false,
		        'items_wrap'		=>	'%3$s',
		        'menu'				=>	'user-account'
		    );
		?>
		<?php wp_nav_menu($menu_args); ?>
	<li class="dash_feedback">
		<a href="mailto:<?php echo antispambot('feedback@domain.com'); ?>" class="btn btn-primary general_fw_button" style="margin-top:15px;"><i class="fa fa-comment-o"></i> Request Feedback</a>
	</li>
	</ul>
	<script>
		jQuery(document).ready(function($){
			var bottom = $('.search_point').offset().top ;
			$(window).scroll(function(){    
			    if ($(this).scrollTop() > bottom - 70){ 
			        $('.nav').addClass('fixed'); 
			    }
			    else{
			        $('.nav').removeClass('fixed');
			    }
			});	
		});
	</script>
	<?php
}

function get_subscriber_menu() {
	?>
	<ul class="nav subscriber_menu">
		<?php
		    $menu_args = array(
		        'container'			=>	false,
		        'items_wrap'		=>	'%3$s',
		        'menu'				=>	'user-sub'
		    );
		?>
		<?php wp_nav_menu($menu_args); ?>
		<li class="dash_feedback">
		<a href="mailto:<?php echo antispambot('feedback@domain.com'); ?>" class="btn btn-primary general_fw_button" style="margin-top:15px;"><i class="fa fa-comment-o"></i> Request Feedback</a>
		</li>
	</ul>
		<script>
		jQuery(document).ready(function($){
			var bottom = $('.search_point').offset().top ;
			$(window).scroll(function(){    
			    if ($(this).scrollTop() > bottom - 70){ 
			        $('.nav').addClass('fixed'); 
			    }
			    else{
			        $('.nav').removeClass('fixed');
			    }
			});	
		});
	</script>
	<?php
}

/**
 * Get Session Messages
 */
function elbp_get_session_messages() {
	//print_r($_SESSION);
	if($_SESSION) {
	$message = @$_SESSION['elbp_session_message'];
	$context = @$_SESSION['elbp_session_message_context'];
	} else {
		$message = '';
		$context = '';
	}

	if($message == '' || $context == '') return;
	
	$icon = '';
	switch($context) {
		case 'danger':
			$icon = 'icon-exclamation-sign';
		break;
		case 'success':
			$icon = 'icon-ok-sign';
		break;
		case 'info':
			$icon = 'icon-info-sign';
		break;
	}
	
?>
<div class="alert alert-<?php echo $context; ?>">
	<i class="<?php echo $icon; ?>"></i> <?php echo $message; ?>
</div>
<?php

	// Clear After 
	unset($_SESSION['elbp_session_message']);
	unset($_SESSION['elbp_session_message_context']);

}

/**
 * Set Session Messages
 */
function elbp_set_session_message($message, $context) {
	
	$_SESSION['elbp_session_message'] = $message;
	$_SESSION['elbp_session_message_context'] = $context;
	
}
/* Add Logout Message */
function elbp_add_logout_message() {
	elbp_set_session_message('You have been logged out.', 'info');
}
add_action('wp_logout', 'bcb_add_logout_message');

///

function get_organisation_locations($post_id=false) {
	global $post;
	$pid=$post->ID;
	if($post_id) $pid = $post_id;
	$types=wp_get_post_terms($pid, 'organisations_locations', array("fields" => "names"));
	return (object) array(
		'type'			=>	$types[0],
		'type_label' 	=> 	strtolower($types[0])
	);
}

function get_organisations_support($post_id=false) {
	global $post;
	$pid=$post->ID;
	if($post_id) $pid = $post_id;
	$types=wp_get_post_terms($pid, 'organisations_support', array("fields" => "names"));
	return (object) array(
		'type'			=>	$types[0],
		'type_label' 	=> 	strtolower($types[0])
	);
}

function get_support_topics($post_id=false) {
	global $post;
	$pid=$post->ID;
	if($post_id) $pid = $post_id;
	$types=wp_get_post_terms($pid, 'support_topics', array("fields" => "names"));
	return (object) array(
		'type'			=>	$types[0],
		'type_label' 	=> 	strtolower($types[0])
	);
}

