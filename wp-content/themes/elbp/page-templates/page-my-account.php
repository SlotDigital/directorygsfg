<?php
 /**
 * Template Name: Account
 *
 * @package WordPress
 * @subpackage East London Business Portal
 * @since East London Business Portal 1.0
 */
elbp_protect_page();
get_header(); 

$current_user = wp_get_current_user();
//print_r($current_user);


// COUNT EVENTS
$counter = array();
$args = array(
'post_type'	=>	'event',
'author'        =>  $current_user->ID,
'orderby'       =>  'post_date',
'order'         =>  'ASC',
'posts_per_page' => -1
);
$events = new WP_Query($args);
$count=0;
while($events->have_posts()):$events->the_post();
   $counter[] = $count;
$count++;
endwhile;
$counted_events = count($counter);
if($counted_events != 0 ) {
	$evs = $counted_events;
}else {
	$evs = 1;
}

// COUNT OPPORTUNITIES
$counter = array();
$args = array(
 'post_type'	=>	'opportunities',
 'author'        =>  $current_user->ID,
 'orderby'       =>  'post_date',
 'order'         =>  'ASC',
 'posts_per_page' => -1
 );
 $events = new WP_Query($args);
 $count=0;
 while($events->have_posts()):$events->the_post();
 	$counter[] = $count;
 $count++;
 endwhile;
$counted_ops = count($counter);
if($counted_ops != 0 ) {
	$os = $counted_ops;
}else {
	$os = 1;
}
if($current_user->roles[0] == 'subscriber') {
	$message = 'From here you can update your account information or request to add an Organisation.';
}else {
	$message = 'From here you can update your Organisation Information, manage Events and Opportunities, update your media and change your password.';
}	
?>


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
		<div class="main-content main-content-dashboard col-md-9 col-xs-12">
			<div class="dashboard_image background_cover" style="background-image:url(<?php echo get_theme_info('theme_url');?>/images/el_cityscape_v.jpg);">
				<div class="table_outer inner_background">
					<div class="table_inner_mid">
						<div class="welcome col-sm-8 col-sm-offset-2">
							
							<h2><?php echo 'Welcome '.$current_user->display_name; ?></h2>
							<?php elbp_get_session_messages(); ?>
							<div class="more_info"><?php echo $message; ?></div>
						</div>
						<?php if($current_user->roles[0] != 'subscriber') { ?>
						<div class="panel-body col-sm-8 col-sm-offset-2">
        				    <div class="col-sm-6 chart">
        				        <div id="donut_events" style="height: 250px;"><div class="result"><?php echo $counted_events;?></div></div>
        				        <div class="caption">
        				            Events
        				        </div>
        				    </div>        
        				    <div class="col-sm-6 chart">
        				        <div id="donut_opportunities" style="height: 250px;"><div class="result"><?php echo $counted_ops; ?></div></div>
        				         <div class="caption">
        				            Opportunities
        				        </div>
        				    </div>                   
        				</div>
        				<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<?php if($current_user->roles[0] != 'subscriber') { ?>
<script>
jQuery(document).ready(function($){
	 Morris.Donut({
      element: 'donut_events',
      hideHover: 'auto',
      resize: true,
      data: [
        {label: ' ', value: (<?php echo $evs;?>)},
      ],
      colors: ['#f4e539'],
      formatter: function (y) { return y; }
    });

    Morris.Donut({
      element: 'donut_opportunities',
      hideHover: 'auto',
      resize: true,
      data: [
        {label: ' ',value: (<?php echo $os; ?>) },
      ],
      colors: ['#a4d7db'],
      formatter: function (y) { return y; }
    });

  });
</script>
<?php } ?>