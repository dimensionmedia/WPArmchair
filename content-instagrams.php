<?php
/**
 * @package WPAnniversary
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>

		<div class="entry-meta">
			
			<?php
			
				// define default image attributes
					
				$default_image_attr = array(
					'class'		=> "img-polaroid attachment",
					'alt'		=> ""
				);	
			
			   $image_width = 500;
			
				// instagram - get metadata
			
			    $image_attributes = wp_get_attachment_image_src( $post->ID, 'custom-500-thumbnail' );
			    $link = get_post_meta ( $post->ID, 'wpgip_ip_url', true );
			    $username = get_post_meta ( $post->ID, 'wpgip_ip_username', true );
			    $image_large_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'custom-500-thumbnail' );
	
			    
			    ?>
			
			
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->

	<div class="entry-content">
	
	            <?php if ( has_post_thumbnail() ) { ?>
	            
	              <?php echo the_post_thumbnail( 'full', $default_image_attr ); ?>
	              
	            <?php } ?>
	
	            <p><i class="icon-camera-retro"></i> <?php _e( 'Posted on', 'wparmchair' ); ?> <a href="<?php echo $link; ?>" target="_blank"><?php echo get_the_date ( 'M jS, Y g:ia' ); ?></a> <?php _e( 'by', 'wparmchair' ); ?> <a target="_blank" href="http://instagram.com/<?php echo $username; ?>">@<?php echo $username; ?></a></p>

		<?php the_content(); ?>
		
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'wparmchair' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-meta">
		<?php
			/* translators: used between list items, there is a space after the comma */
			$category_list = get_the_category_list( __( ', ', 'wparmchair' ) );

			/* translators: used between list items, there is a space after the comma */
			$tag_list = get_the_tag_list( '', __( ', ', 'wparmchair' ) );

			if ( ! wpanniversary_categorized_blog() ) {
				// This blog only has 1 category so we just need to worry about tags in the meta text
				if ( '' != $tag_list ) {
				//	$meta_text = __( 'This entry was tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'wparmchair' );
				} else {
					// $meta_text = __( 'Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'wparmchair' );
				}

			} else {
				// But this blog has loads of categories so we should probably display them here
				if ( '' != $tag_list ) {
				//	$meta_text = __( 'This entry was posted in %1$s and tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'wparmchair' );
				} else {
					// $meta_text = __( 'This entry was posted in %1$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'wparmchair' );
				}

			} // end check for categories on this blog

			printf(
				$meta_text,
				$category_list,
				$tag_list,
				get_permalink(),
				the_title_attribute( 'echo=0' )
			);
		?>

		<?php edit_post_link( __( 'Edit', 'wparmchair' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-meta -->
</article><!-- #post-## -->
