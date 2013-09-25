<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WPAnniversary
 */

get_header(); ?>

	<div class="container">
	
		<div class="row">
		
			<div class="span8 offset2">

				<div id="primary" class="content-area">
					<div id="content" class="site-content" role="main">
			
						<?php while ( have_posts() ) : the_post(); ?>
			
							<?php get_template_part( 'content', 'page' ); ?>
			
							<?php
								// If comments are open or we have at least one comment, load up the comment template
								if ( comments_open() || '0' != get_comments_number() )
									comments_template();
							?>
			
						<?php endwhile; // end of the loop. ?>
			
					</div><!-- #content -->
				</div><!-- #primary -->
				
			</div>
			
		</div>
	
	</div>

<?php //get_sidebar(); ?>
<?php get_footer(); ?>
