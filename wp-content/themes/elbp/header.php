<?php
/**
 * The Header template for our theme
 *
 * @package WordPress
 * @subpackage East London Business Portal
 * @since East London Business Portal 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<link rel="icon" href="<?php echo get_theme_info('theme_url'); ?>/favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo get_theme_info('theme_url'); ?>/favicon.ico" type="image/x-icon" />
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<link href="<?php echo get_theme_info('theme_url'); ?>/style.css" rel="stylesheet">
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
	
	 <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->	
	
	<?php wp_head(); ?>
</head>
<script type="text/javascript">       
		jQuery(function($){
		    function showMenu() { $(this).children('ul').slideDown(200); }
		    function hideMenu() { $(this).children('ul').fadeOut(100); }
		    $('#main-navigation ul li').hoverIntent(showMenu, hideMenu);
		});	

</script>
<body <?php body_class(); $current_user = wp_get_current_user(); ?>>
	<div id="page">
		<header>
			<div class="container-fluid">
				<div class="row Aligner">
					<div class="col-sm-6 col-xs-10">
						<a href="<?php echo home_url();?>"><img class="main_logo" src="<?php echo get_theme_info('theme_url');?>/images/svg/elbp-logo.svg" class="logo" alt="" width="210"></a>
						<a class="contact_header" target="blank" href="tel:<?php echo ELBP_PHONE;?>"><i class="ion-ios-telephone-outline"></i><div class="hidden-sm hidden-xs inline"><?php echo ELBP_PHONE;?></div></a>
						<a class="contact_header" target="blank" href="mailto:<?php echo antispambot(ELBP_EMAIL);?>"><i class="ion-android-drafts"></i><div class="hidden-sm hidden-xs inline">Email Us</div></a>
					</div>
					<div class="col-lg-offset-3 col-lg-5 col-sm-6 col-xs-2">
						<ul class="menu list-unstyled nav_menu_top <?php if(is_user_logged_in()){ echo 'loggedin';}?>">
							<?php
		    					$menu_args = array(
		    				        'container'			=>	false,
		    				        'items_wrap'		=>	'%3$s',
		   					         'order'            =>	'DESC',
		   					         'orderby'          =>	'menu_order',
		   					         'post_type'        =>	'nav_menu_item',
		   					         'post_status'      =>	'publish',
		   					         'output_key'       =>	'menu_order',
		   					         'menu'				=>	'main' ,
		   					         'theme_location'	=>	'main'
		    					    );
		    					?>	
		    					<?php 
							
								wp_nav_menu($menu_args); 
							?>
							<?php if(!is_user_logged_in()){?><li class="hidden-sm hidden-xs"><a href="<?php echo home_url().'/login';?>" class="login_button">Login</a></li>
							<li class="hidden-sm hidden-xs"><a href="<?php echo home_url().'/register';?>" class="register_button">Register</a></li>	<?php } else { ?>
								<li class="button logged-in">
								<div class="btn-group">
									<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-user"></i> <i class="fa fa-caret-down"></i></button>
									<ul class="dropdown-menu" id="user-menu-items" role="menu">
										<li><a href="<?php echo get_permalink(216); ?>">My Account</a></li>
										<?php if($current_user->roles[0] != 'subscriber') { ?>
											<li><a href="<?php echo get_permalink(231); ?>">My Organisation</a></li>
											<li><a href="<?php echo get_permalink(221); ?>">My Events</a></li>
											<li><a href="<?php echo get_permalink(223); ?>">My Opportunities</a></li>
										<?php } ?>
										<li class="divider"></li>
										<li><a href="<?php echo wp_logout_url(home_url()); ?>">Logout</a></li>
									</ul>
								</div>								
							</li>
								
							<?php } ?>
						</ul>
						<?php if(!is_user_logged_in()){?>
						<div class="mob_menu inline-block hidden-lg hidden-md">
							<a class="hamburger hamburger--squeeze js-hamburger" href="#navigation">
					    		<span class="hamburger-box">
									<span class="hamburger-inner"></span>
								</span>
							</a>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>
			<nav id="navigation" style="display:none;">
				<a href="<?php echo home_url();?>"><img class="main_logo" src="<?php echo get_theme_info('theme_url');?>/images/svg/elbp-logo_white.svg" class="logo" alt="" width="210"></a>
				<?php if(!is_user_logged_in()){ ?>
				<div class="utils_mobile row-no-padding">
					<div class="col-xs-6">
						<a href="<?php echo get_permalink(119) ?>">login</a>
					</div>
					<div class="col-xs-6">
						<a href="<?php echo get_permalink(121) ?>">register</a>
					</div>
				</div>
				<?php } ?>
				<div class="mm-panels">
					<ul>
						<?php
		    			$menu_args = array(
		    		        'container'			=>	false,
		    		        'items_wrap'		=>	'%3$s',
		   			         'order'            =>	'DESC',
		   			         'orderby'          =>	'menu_order',
		   			         'post_type'        =>	'nav_menu_item',
		   			         'post_status'      =>	'publish',
		   			         'output_key'       =>	'menu_order',
		   			         'menu'				=>	'main' ,
		   			         'theme_location'	=>	'main'
		    			    );
		    			?>	
		    			<?php 
					
						wp_nav_menu($menu_args); 
					?>
					</ul>
				</div>
			</nav>
			<div class="nav_point"></div>
		</header>	
