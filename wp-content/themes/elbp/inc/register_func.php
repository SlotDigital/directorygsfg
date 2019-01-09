<?php

// CUSTOM REGISTRATION FORM //

class elbp_registration_form
{
 
    // form properties
    private $username;
    private $email;
    private $password;
    //private $website;
    private $first_name;
    private $last_name;
    
    private $orgname;
    private $orgwebsite;
    //private $nickname;
    //private $bio;
    private $support_type_term_id;
    private $support_topic_term_id;

	function __construct()
	{
	    add_shortcode('dm_registration_form', array($this, 'shortcode'));
	    //add_action('wp_enqueue_scripts', array($this, 'flat_ui_kit'));
	    
	    
	  
	  
	    
	}
	

	public function registration_form()
    {
		
			function my_password_filter() {
			
			    $words = explode(' ', "apple arm banana bike bird book chin clam class clover club corn crayon crow crown crowd crib desk dime dirt dress fang field flag flower fog game heat hill home horn hose joke juice kite lake maid mask mice milk mint meal meat moon mother morning name nest nose pear pen pencil plant rain river road rock room rose seed shape shoe shop show sink snail snake snow soda sofa star step stew stove straw string summer swing table tank team tent test toes tree vest water wing winter woman women");
			
			    $num = rand(100, 999);
			
			    return $words[array_rand($words)] . $num . $words[array_rand($words)];
			
			}
			
			add_filter('random_password', 'my_password_filter', 10, 1);
			
			
			
        ?>
		
        <form id="#reg_form" method="post" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>">
            <div class="register_frm">
	            
	            <p style="font-size:20px;font-size: 20px; color: #058e98; margin-bottom: 40px !important;">Does your business offer support services to growing SMEs? Would you like to appear within the directory? Register your details below.</p>
	       
	            <div class="col-sm-6 no_pad_right">
		    	    <div class="form-group field_style">
					 <label class="text-fields">
					 	<i class="icon ion-ios-personadd placeholder-icon"></i>
					 	<input  value="<?php echo(isset($_POST['reg_fname']) ? $_POST['reg_fname'] : null); ?>" name="reg_fname" autocomplete="off" type="text" id="reg-fname" placeholder="First Name" class="input_style form-control login-field" required/>
					 </label>
					</div>
            	</div>
            	<div class="col-sm-6">
		    	    <div class="form-group field_style">
					 <label class="text-fields">
					 	<input value="<?php echo(isset($_POST['reg_lname']) ? $_POST['reg_lname'] : null); ?>" name="reg_lname" autocomplete="off" type="text" id="reg-name" placeholder="Last Name" class="input_style form-control login-field" required/>
					 </label>
					</div>
            	</div>
 
 
 	            <div class="col-sm-6 no_pad_right">
		    	    <div class="form-group field_style">
					 <label class="text-fields">
					 	
					 	<input  value="<?php echo(isset($_POST['reg_orgname']) ? $_POST['reg_orgname'] : null); ?>" name="reg_orgname" autocomplete="off" type="text" id="reg-orgname" placeholder="Organisation Name" class="input_style form-control login-field" required/>
					 </label>
					</div>
            	</div>
            	<div class="col-sm-6">
		    	    <div class="form-group field_style">
					 <label class="text-fields">
					 	<input value="<?php echo(isset($_POST['reg_orgweb']) ? $_POST['reg_orgweb'] : null); ?>" name="reg_orgweb" autocomplete="off" type="text" id="reg-orgweb" placeholder="Organisation Website" class="input_style form-control login-field" required/>
					 </label>
					</div>
            	</div>	
				<div class="col-sm-6 no_pad_right">
		    	    <div class="form-group field_style">
					 
					 	
					 	<select name="reg_supporttype" autocomplete="off" id="reg-supporttype" placeholder="Support Type" class="selectpicker" required>
						 	<option value="">Support Type</option>
						<?php
  						    $terms = get_terms( array(
							    'taxonomy' => 'organisations_support',
							    'hide_empty' => false,
							    'number'=>10
							) );
  						    $index = 0;
  						    foreach($terms as $term){
  						        
  						        echo '<option value="'.$term->name.'" '.selected(@$_POST['reg_supporttype'], $term->name).'>'.$term->name.'</option>';
  						      
  						    }
  						?>						 	
					 	</select>
					
					</div>
            	</div>
            	<div class="col-sm-6">
		    	    <div class="form-group field_style">
					 	<select name="reg_supporttopic" autocomplete="off" id="reg-supporttopic" placeholder="Support Topic" class="selectpicker" required>
						 	<option value="">Support Topic</option>
						<?php
  						    $terms = get_terms( array(
							    'taxonomy' => 'support_topics',
							    'hide_empty' => false,
							    'number'=>10
							) );
  						    $index = 0;
  						    foreach($terms as $term){
  						        
  						        echo '<option value="'.$term->name.'" '.selected(@$_POST['reg_supporttopic'], $term->name).'>'.$term->name.'</option>';
  						      
  						    }
  						?>						 	
					 	</select>
					</div>
            	</div>	
				<div class="col-sm-12">
		    	    <div class="form-group field_style">
					 <label class="text-fields">
					 	<i class="icon ion-android-drafts placeholder-icon"></i>
					 	<input value="<?php echo(isset($_POST['reg_email']) ? $_POST['reg_email'] : null); ?>"autocomplete="off" type="email" name="reg_email" placeholder="Your Email Address..." class="input_style form-control login-field" data-bv-notempty data-bv-emailaddress="true" />
					 </label>
					</div>
            	</div>


	           	<div class="col-sm-12">
		           	<input name="org_type" type="hidden" value="1" />
                    <input name="reg_password" type="hidden" class="form-control login-field"
                           value="<?php echo my_password_filter();?>"
                           placeholder="Password" id="reg-pass" required/>
                    <label class="login-field-icon fui-lock" for="reg-pass"></label>
					<div class="text-center"><input class="btn btn-primary btn-lg btn-block button_register" type="submit" name="reg_submit" value="Submit"/></div>
	           	</div>
			   	<p class="small">NB: All organisations need to be approved by GetSet before appearing within the directory. Having registered, you will be sent an email confirming your application. Once reviewed, you will be notified that your registration is complete and your organisation profile is live. 
			   	</p>
        	</div>
        </form>
    
    <?php
    }
    function validation()
    {
 
        if (empty($this->username) || empty($this->email) || empty($this->orgname) || empty($this->orgwebsite) ) {
            return new WP_Error('field', 'Required form field is missing');
        }
 
        if (strlen($this->username) < 4) {
            return new WP_Error('username_length', 'Username too short. At least 4 characters is required');
        }
 
        //if (strlen($this->password) < 5) {
        //    return new WP_Error('password', 'Password length must be greater than 5');
        //}
 
        if (!is_email($this->email)) {
            return new WP_Error('email_invalid', 'Email is not valid');
        }
 
        if (email_exists($this->email)) {
            return new WP_Error('email', 'Email Already in use');
        }
 
        if (!empty($website)) {
            if (!filter_var($this->website, FILTER_VALelbpTE_URL)) {
                return new WP_Error('website', 'Website is not a valid URL');
            }
        }
 
        $details = array('Username' => $this->username,
            'First Name' => trim($this->first_name),
            'Last Name' => trim($this->last_name),
            //'Nickname' => $this->nickname,
            //'bio' => $this->bio
        );
 
        foreach ($details as $field => $detail) {
            if (!validate_username($detail)) {
                return new WP_Error('name_invalid', 'Sorry, the "' . $field . '" you entered is not valid');
            }
        }
 
    }
    function registration()
{
 	if($_REQUEST['org_type'] == 1) {
	 	$user = 'organisation_owner';
 	} else {
	 	$user = 'subscriber';
 	}
    $userdata = array(
        'user_login' => esc_attr($this->username),
        'user_email' => esc_attr($this->email),
        'user_pass' => esc_attr($this->password),
        'role' => $user,
        //'user_url' => esc_attr($this->website),
        'first_name' => esc_attr($this->first_name),
        'last_name' => esc_attr($this->last_name),
        //'nickname' => esc_attr($this->nickname),
        //'description' => esc_attr($this->bio),
    );
 
    if (is_wp_error($this->validation())) {
       
        echo '<div class="alert alert-danger">' . $this->validation()->get_error_message() . '</div>';
       
    } else {
        $register_user = wp_insert_user($userdata);
          
        if (!is_wp_error($register_user)) {
	        //$test = $userdata['user_pass'];
	        
			
			
			add_user_meta($register_user, 'approved', 0);
			
			/// ----------
			// insert org
					
			$post_id = wp_insert_post(array(
  				'post_title'    => $this->orgname,
  				'post_content'  => '',
  				'post_status'   => 'pending',
  				'post_author'   => $register_user,
  				'post_type'		=>	'organisations'						
			));
			
			add_user_meta($register_user, 'user_organisation_id', $post_id);
			
			// add meta
			add_post_meta($post_id, 'organisation_website', $this->orgwebsite);
			add_post_meta($post_id, 'organisation_email', $this->email);
			add_post_meta( $post_id, 'approved', 0 );			

			wp_set_object_terms( $post_id, array($this->support_type_term_id), 'organisations_support' );
			wp_set_object_terms( $post_id, array($this->support_topic_term_id), 'support_topics' );			
			
			// send emails

			$this->send_admin_new_user_notification($register_user);
			
			
			/// ----------
            
            //echo '<div class="alert alert-success">Please check your email for a password.</div>';
            echo '
<p style="font-size:20px;font-size: 20px; color: #058e98; margin-bottom: 20px; font-weight: bold;">Thank you for registering your organisation with the GetSet Directory.</p>

<p>All organisations need to be approved by GetSet before appearing within the directory. Having registered, you will be sent an email confirming your application. Once reviewed, you will be notified that your registration is complete and your organisation profile is live.</p>
            
            
            ';
            
            wp_new_user_notification( $register_user, $deprecated = null, $this->email );
          
           
            ?>
				<script type="text/javascript">
			      //document.location.href="<?php echo get_permalink(119); ?>";
			</script>
			<?php
        } else {
     
            echo '<div class="alert alert-danger">' . $register_user->get_error_message() . '</div>';
          
        }
    }
 
}
function shortcode()
    {
 
        ob_start();
        if (isset($_POST['reg_submit'])) {
	        if($_REQUEST['org_type'] == 1) {
			 	$user = 'organisation_owner';
 			} else {
			 	$user = 'subscriber';
 			}
 			
 			$this->role  = $user;
            $this->username = $_POST['reg_email'];
            $this->email = $_POST['reg_email'];
            $this->password = $_POST['reg_password'];
           // $this->website = $_POST['reg_website'];
            $this->first_name = $_POST['reg_fname'];
            $this->last_name = $_POST['reg_lname'];
            
            $this->orgname = $_POST['reg_orgname'];
            $this->orgwebsite = $_POST['reg_orgweb'];
            
            $this->support_topic_term_id = $_POST['reg_supporttopic'];
            $this->support_type_term_id = $_POST['reg_supporttype'];
            
           // $this->nickname = $_POST['reg_nickname'];
           // $this->bio = $_POST['reg_bio'];
 
            $this->validation();
            $this->registration();
        } else {
 
        $this->registration_form();
        }
        return ob_get_clean();
    }
    

    
    function send_admin_new_user_notification($user_id) {
	   
		// emailings to adminings
	   
	   
    }

}
new elbp_registration_form();

/* REGISTER THE FUNCTION */

function elbp_register_form() {
	echo do_shortcode('[dm_registration_form]');
}