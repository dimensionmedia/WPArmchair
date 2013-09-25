<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WPAnniversary
 */

get_header(); ?>

	<div class="container">
	
		<div class="row">
		
			<div class="span8 offset2">

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

		    	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		    	
    				<?php edit_post_link( __( 'Edit', 'wpanniversary' ), '<span class="edit-link">', '</span>' ); ?>
		    	
		    		<?php wpann_process_post_display ( $post ); ?>
		    		
		    		<hr/>
		    		


			<?php
				// If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || '0' != get_comments_number() )
					comments_template();
			?>

			
				<?php endwhile; else: ?>
				
					<p><?php _e('Sorry, nothing here.'); ?></p>
					
				<?php endif; ?>

		</div><!-- #content -->
	</div><!-- #primary -->

</div>

</div>

</div>

<?php //get_sidebar(); ?>
<?php get_footer(); ?>