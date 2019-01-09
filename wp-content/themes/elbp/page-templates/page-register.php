<?php
 /**
 * Template Name: Register
 *
 * @package WordPress
 * @subpackage East London Business Portal
 * @since East London Business Portal 1.0
 */

get_header(); 

?>

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

 					<?php  elbp_register_form(); ?>
					</div>
					</div>
				</div>
	 		 </div>
 		</section>
	</div>
</section>

<?php get_footer();?>