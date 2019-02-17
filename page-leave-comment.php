<?php
/**
 * The template for displaying the comments page. Comments are fitted with pagination.
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
					<div class="comments-wrapper">
						<?php if (comments_open()) {comments_template();} ?>
					</div>
				</div>
			</div>
		
		</div>
		
<?php get_footer(); ?>