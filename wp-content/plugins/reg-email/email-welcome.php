<?php if ( $user->first_name != '' ) : ?>

	<p style="font-size:13px;color:#000;"><strong><?php echo $user->first_name; ?></strong>, thank you for registering at GetSet Directory.</p>

    <p>
	    <strong>Your login details:</strong><br />
	    Username: <?php echo $user->user_email; ?><br />
	    Password: <?php echo $_POST['reg_password']; ?>
	</p>
	
	<p>All organisations <u>need to be approved</u> by GetSet before appearing within the directory. Once your application has been reviewed, you will then be notified of our decision. If you are successful, your account will be unlocked and your listing will be live.</p>
	
	<p><strong>Didn't register?</strong><br />
		If you did not register to be listed on the GetSet directory then please email <a href="mailto:help@getsetforgrowth.com">help@getsetforgrowth.com</a></p>
		
		<p>----</p>
		
		<p>Thanks,<br /> The GetSet Team</p>

    
    
<?php else : ?>
    <h1>Welcome to MySite</h1>
<?php
	
	
	
	 endif; ?>
 

<?php 
	global $wpdb, $wp_hasher;
	// Generate something random for a password reset key.
	$key = wp_generate_password( 20, false );

	/** This action is documented in wp-login.php */
	do_action( 'retrieve_password_key', $user->user_login, $key );

	// Now insert the key, hashed, into the DB.
	if ( empty( $wp_hasher ) ) {
		require_once ABSPATH . WPINC . '/class-phpass.php';
		$wp_hasher = new PasswordHash( 8, true );
	}
		$hashed = time() . ':' . $wp_hasher->HashPassword( $key );
		$wpdb->update( $wpdb->users, array( 'user_activation_key' => $hashed ), array( 'user_login' => $user->user_login ) );
	//	$message = 'Hi '.$user->user_login.', <br> Please see your login credentials for below';
		//$message = sprintf(__('Username: %s'), $user->user_login) . "\r\n\r\n";
		//$message .= __('If you wish to reset your password please visit:') . "\r\n\r\n";
		//$message .= network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user->user_login), 'login');
	//echo $message;
?>