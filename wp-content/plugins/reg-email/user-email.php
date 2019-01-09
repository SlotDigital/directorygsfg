<?php
	
// wp-content/plugins/user-emails/user-emails.php
 /*
Plugin Name: User Emails
Description: Changes the default user emails
Version: 1.0
Author: Ryan Davis
Author URI: http://www.mold.agency
*/
// wp-content/plugins/user-emails/user-emails.php
 

if ( !function_exists( 'wp_new_user_notification' ) ) {
    function wp_new_user_notification( $user_id, $plaintext_pass = '' ) {
 
        // set content type to html
        add_filter( 'wp_mail_content_type', 'wpmail_content_type' );
 
        // user
        $user = new WP_User( $user_id );
        $userEmail = stripslashes( $user->user_email );
        $siteUrl = get_site_url();
        $logoUrl = plugin_dir_url( __FILE__ ).'/sitelogo.gif';
 
        $subject = 'Welcome to the GetSet Directory';
        $headers = 'From:GetSet Directory <noreply@getsetforgrowth.com>';
 
        // admin email
        $message  = "A new organsisation has been created with the"."\r\n\r\n";
        $message .= 'email: '.$userEmail."\r\n";
        @wp_mail( get_option( 'admin_email' ), 'New User Created', $message, $headers );
 
        ob_start();
        include plugin_dir_path( __FILE__ ).'/email-welcome.php';
        $message = ob_get_contents();
        ob_end_clean();
 
        @wp_mail( $userEmail, $subject, $message, $headers );
 
        // remove html content type
        remove_filter ( 'wp_mail_content_type', 'wpmail_content_type' );
    }
}





function wpmail_content_type() {
 
    return 'text/html';
}