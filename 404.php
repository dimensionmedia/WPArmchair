<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package WPAnniversary
 */

get_header(); ?>

	<div class="container">
	
		<div class="row">
		
			<div class="span8 offset2">

				<div id="primary" class="content-area">
					<div id="content" class="site-content" role="main">
			
						<h1 class="entry-title"><?php _e( "The page you were looking for could not be found. It’s not your fault... most likely.", "wparmchair" ); ?></h1>
			
					</div><!-- #content -->
				</div><!-- #primary -->
				
			</div>
			
		</div>
	
	</div>
	

<?php get_footer(); ?>