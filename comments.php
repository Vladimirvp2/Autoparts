<?php
/**
 * The template for displaying comments. Comments are fitted with pagination.
 *
 * @package WordPress
 * @subpackage AC Repair
 * @since AC Repair 1.0
 */
 
if ( post_password_required() )
    return;
?>
 
<div id="comments" class="comments-area">
 
    <?php if ( have_comments() ) : ?>
        <h3 class="comments-title">
            <?php
				echo _e( 'Comments', 'ac' );
            ?>
        </h3>
 
        <ol class="comment-list">
            <?php
                wp_list_comments( array(
                    'style'       => 'ol',
                    'short_ping'  => true,
                    'avatar_size' => 50,
					'callback' => 'ac_comment',
					'max_depth' => 5
                ) );
            ?>
        </ol><!-- .comment-list -->
		
		<div class="commentPagination-wrapper">
			<div class="commentPagination">
				<?php paginate_comments_links(   array ( 'prev_text' => '&laquo; ' . __( 'PREV' , 'ac' ), 'next_text' => __( 'NEXT' , 'ac' ) . ' &raquo;' )); ?>
			</div>
		</div>
		
		<br>
		<br>
 

        <?php if ( ! comments_open() && get_comments_number() ) : ?>
        <p class="no-comments"><?php _e( 'Comments are closed.' , 'ac' ); ?></p>
        <?php endif; ?>
 
    <?php endif; // have_comments() ?>
	
	<!--  Specify comment form -->
	<?php
		$comment_form_args = array( 
			// remove all fields. They are added in functions.php
			'fields' => array(),
			'class_submit' => "base-button",
			'label_submit' => __( 'SUBMIT' , 'ac' ),
			'logged_in_as' => '',
		);
	?>
	
	<!-- Show comments form -->
    <?php comment_form($comment_form_args); ?>
 
</div><!-- #comments -->
