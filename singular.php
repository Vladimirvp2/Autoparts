<?php
/**
 * The template for displaying the service pages
 *
 * @package WordPress
 * @subpackage AC Repair
 * @since AC Repair 1.0
 */
 
?>


<?php get_header('without-menu'); ?>

<div class="site-content">
		<div class="container">
			<!-- white spacer to fill space under fixed header -->
			<div class="row white-background">
			  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 site-empty-header">
			  </div>

			</div>
		</div>
		
		<!-- Comments -->
		<div class="container contact-form-container full-width white-background">
		
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

			<?php	
				//$args = array( 'post_type'=>'testimonials', 'showposts' => 3, 'orderby'=> 'date', 'order' => 'ASC' );  
				//$header_query = new WP_Query( ); 
				//if ( $header_query->have_posts() ) : while ( $header_query->have_posts() ) : $header_query->the_post();
				if ( have_posts() ) : while ( have_posts() ) : the_post();
			?>
			
				<?php  
					
					echo "<div class='service-page'>";
					echo the_content();
					echo '</div>';
 						
				?>
							 
			<?php
				// slider buttons loop end
				endwhile; else:
			?>
			<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
			<?php 
				endif;
				wp_reset_postdata();
			?>					
				
				
				
				</div>
			</div>
		
		</div>
		
<?php get_footer(); ?>