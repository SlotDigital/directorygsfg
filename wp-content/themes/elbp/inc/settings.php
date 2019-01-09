<?php
/**
 * Custom Site Settings
 *
 * @package WordPress
 * @subpackage East London Business Portal
 * @since East London Business Portal 1.0
 */

add_action( 'admin_menu', 'elbp_add_admin_menu' );
add_action( 'admin_init', 'elbp_settings_init' );

function elbp_add_admin_menu(  ) { 

	add_options_page( 'ELBP Options', 'ELBP Options', 'manage_options', 'East London Business Portal', 'elbp_options_page' );

}

function elbp_settings_init(  ) { 

	register_setting( 'elbp_options', 'elbp_settings' );

	add_settings_section(
		'elbp_pluginPage_section', 
		__( 'Social Media', 'elbp' ), 
		'elbp_settings_section_callback', 
		'elbp_options'
	);

	add_settings_section(
		'elbp_pluginPage_section_contact', 
		__( 'Contact Information', 'elbp' ), 
		'elbp_settings_contact_section_callback', 
		'elbp_options'
	);
	
	
	// ---

	add_settings_field( 
		'elbp_facebook_url', 
		__( 'Facebook', 'elbp' ), 
		'elbp_text_field_0_render', 
		'elbp_options', 
		'elbp_pluginPage_section' 
	);

	add_settings_field( 
		'elbp_twitter_url', 
		__( 'Twitter', 'elbp' ), 
		'elbp_text_field_1_render', 
		'elbp_options', 
		'elbp_pluginPage_section' 
	);

	add_settings_field( 
		'elbp_linkedin_url', 
		__( 'LinkedIn', 'elbp' ), 
		'elbp_text_field_2_render', 
		'elbp_options', 
		'elbp_pluginPage_section' 
	);
	
	// ---
	
	add_settings_field( 
		'elbp_contact_email', 
		__( 'Email', 'elbp' ), 
		'elbp_text_field_3_render', 
		'elbp_options', 
		'elbp_pluginPage_section_contact' 
	);	

	add_settings_field( 
		'elbp_contact_phone', 
		__( 'Phone', 'elbp' ), 
		'elbp_text_field_4_render', 
		'elbp_options', 
		'elbp_pluginPage_section_contact' 
	);	



	add_settings_field( 
		'elbp_contact_address', 
		__( 'Address', 'elbp' ), 
		'elbp_text_field_address_render', 
		'elbp_options', 
		'elbp_pluginPage_section_contact' 
	);

	add_settings_field( 
		'elbp_contact_lat', 
		__( 'Latitude', 'elbp' ), 
		'elbp_text_field_5_render', 
		'elbp_options', 
		'elbp_pluginPage_section_contact' 
	);	
	
	add_settings_field( 
		'elbp_contact_lng', 
		__( 'Longitude', 'elbp' ), 
		'elbp_text_field_6_render', 
		'elbp_options', 
		'elbp_pluginPage_section_contact' 
	);		

}

function elbp_text_field_address_render(  ) { 

	$options = get_option( 'elbp_settings' );
	?>
	<input type='text' name='elbp_settings[elbp_contact_address]' value='<?php echo $options['elbp_contact_address']; ?>'>
	<?php

}

function elbp_text_field_0_render(  ) { 

	$options = get_option( 'elbp_settings' );
	?>
	<input type='text' name='elbp_settings[elbp_facebook_url]' value='<?php echo $options['elbp_facebook_url']; ?>'>
	<?php

}


function elbp_text_field_1_render(  ) { 

	$options = get_option( 'elbp_settings' );
	?>
	<input type='text' name='elbp_settings[elbp_twitter_url]' value='<?php echo $options['elbp_twitter_url']; ?>'>
	<?php

}


function elbp_text_field_2_render(  ) { 

	$options = get_option( 'elbp_settings' );
	?>
	<input type='text' name='elbp_settings[elbp_linkedin_url]' value='<?php echo $options['elbp_linkedin_url']; ?>'>
	<?php

}

function elbp_text_field_3_render(  ) { 

	$options = get_option( 'elbp_settings' );
	?>
	<input type='text' name='elbp_settings[elbp_contact_email]' value='<?php echo $options['elbp_contact_email']; ?>'>
	<?php

}

function elbp_text_field_4_render(  ) { 

	$options = get_option( 'elbp_settings' );
	?>
	<input type='text' name='elbp_settings[elbp_contact_phone]' value='<?php echo $options['elbp_contact_phone']; ?>'>
	<?php

}

function elbp_text_field_5_render(  ) { 

	$options = get_option( 'elbp_settings' );
	?>
	<input type='text' name='elbp_settings[elbp_contact_lat]' value='<?php echo $options['elbp_contact_lat']; ?>'>
	<?php

}

function elbp_text_field_6_render(  ) { 

	$options = get_option( 'elbp_settings' );
	?>
	<input type='text' name='elbp_settings[elbp_contact_lng]' value='<?php echo $options['elbp_contact_lng']; ?>'>
	<?php

}

function elbp_settings_section_callback(  ) { 

	echo __( 'Enter the full URLs to your social media channels below.', 'elbp' );

}

function elbp_settings_contact_section_callback(  ) { 
	echo __('Enter your contact information below.<br />Lat/Lng can be found <a href="https://www.doogal.co.uk/LatLong.php" target="_blank">here</a>', 'elbp');
}

function elbp_options_page(  ) { 

	?>
	<div class="wrap">
	<form action='options.php' method='post'>

		<h1>East London Business Portal Options</h1>

		<?php
		settings_fields( 'elbp_options' );
		do_settings_sections( 'elbp_options' );
		submit_button();
		?>

	</form>
	</div>
	<?php

}

function get_elbp_settings() {
	return get_option( 'elbp_settings' );
}

?>