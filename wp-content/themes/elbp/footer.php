<?php wp_footer(); ?>

<footer class="main_footer">
	<div class="popular_searches">
		<div class="container-fluid Aligner">
			<div class="grouped_blocks col-sm-9">
			<div class="col-sm-5">
				<div class="footer_block">
					<a href="https://learn.growsmart.business/dashboard">Return to your GrowSmart Dashboard</a>
				</div>
			</div>
				<!--<div class="col-sm-4">
					<div class="footer_block">
						<h3>Support Types</h3>
						<ul class="list-unstyled">
						<?php
  						    $terms = get_terms( array(
							    'taxonomy' => 'organisations_support',
							    'hide_empty' => false,
							    'number'=>10
							) );
  						    $index = 0;
  						    foreach($terms as $term){
  						        
  						        echo '<li><a href="'.home_url().'/organisation-search/?organisation_search=&organisations_support[]='.$term->term_id.'">'.$term->name.'</a></li>';
  						      
  						    }
  						?>
						</ul>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="footer_block">
						<h3>Support Topics</h3>
						<ul class="list-unstyled">
						<?php
  						    $terms = get_terms( array(
							    'taxonomy' => 'support_topics',
							    'hide_empty' => false,
							    'number'=>10
							) );
  						    $index = 0;
  						    foreach($terms as $term){
  						        
  						        echo '<li><a href="'.home_url().'/organisation-search/?organisation_search=&support_topics[]='.$term->term_id.'">'.$term->name.'</a></li>';
  						      
  						    }
  						?>
						</ul>
					</div>
				</div>-->
				<!--<div class="col-sm-4">
					<div class="footer_block">
						<h3>Location Searches</h3>
						<ul class="list-unstyled">
						<?php
  						//    $terms = get_terms( array(
						//	    'taxonomy' => 'organisations_locations',
						//	    'hide_empty' => false,
						//	    'number'=>10
						//	) );
  						//    $index = 0;
  						//    foreach($terms as $term){
  						//        
  						//        echo '<li><a href="'.home_url().'/organisation-search/?organisation_search=&organisations_locations[]='.$term->term_id.'">'.$term->name.'</a></li>';
  						//      
  						//    }
  						?>
						</ul>
					</div>
				</div> -->
			</div>
			<div class="single_blocks col-sm-3">
				<div class="col-sm-3">
					<div class="footer_block">
						<a href="<?php echo home_url();?>"><img class="main_logo" src="<?php echo get_theme_info('theme_url');?>/images/svg/GrowSmart-logo.svg" class="logo" alt="" width="210"></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</footer>
</div>
</body>
</html>
