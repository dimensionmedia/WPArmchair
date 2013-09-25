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
			
			   if ( !has_post_thumbnail() ) {
			   
			   		// this tweet has no photos? let's try for videos
		
					$video_url = get_post_meta ( $post->ID, 'wpgtp_tw_video_expanded_url', true );
											
					if ( !empty($video_url) ) {
					
						$video_type = get_post_meta ( $post->ID, 'wpgtp_tw_video_type', true );
						$video_image = get_post_meta ( $post->ID, 'wpgtp_tw_video_image', true );
						$video_info = get_post_meta ( $post->ID, 'wpgtp_tw_video_info', true );			
					
					}
					
				}
				
				
				$tweet_id = get_post_meta ( $post->ID, 'wpgtp_tw_id', true );
				$username = get_post_meta ( $post->ID, 'wpgtp_tw_user_screen_name', true );
				$image_large_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'custom-500-thumbnail' );		
				$tweet = $post->post_content;
		
			    $link = 'https://twitter.com/'.$username.'/status/' . $tweet_id; 	
			    
			    ?>
			
			
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->

	<div class="entry-content">
	
	            <?php if ( has_post_thumbnail() ) { ?>
	            
	              <?php echo the_post_thumbnail( 'full', $default_image_attr ); ?>
	              
	            <?php } ?>
	
	                	<p><?php if ( !empty($video_url) ) { ?><i class="icon-facetime-video"></i><?php } else { ?><i class="icon-twitter"></i><?php } ?> Posted on <a href="<?php echo $link; ?>" target="_blank"><?php echo get_the_date ( 'M jS, Y g:ia' ); ?></a> by <a target="_blank" href="https://twitter.com/<?php echo $username; ?>">@<?php echo $username; ?></a></p>

		<?php the_content(); ?>
		
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'wpanniversary' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-meta">
		<?php
			/* translators: used between list items, there is a space after the comma */
			$category_list = get_the_category_list( __( ', ', 'wpanniversary' ) );

			/* translators: used between list items, there is a space after the comma */
			$tag_list = get_the_tag_list( '', __( ', ', 'wpanniversary' ) );

			if ( ! wpanniversary_categorized_blog() ) {
				// This blog only has 1 category so we just need to worry about tags in the meta text
				if ( '' != $tag_list ) {
					//$meta_text = __( 'This entry was tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'wpanniversary' );
				} else {
					// $meta_text = __( 'Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'wpanniversary' );
				}

			} else {
				// But this blog has loads of categories so we should probably display them here
				if ( '' != $tag_list ) {
					//$meta_text = __( 'This entry was posted in %1$s and tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'wpanniversary' );
				} else {
					// $meta_text = __( 'This entry was posted in %1$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'wpanniversary' );
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

		<?php edit_post_link( __( 'Edit', 'wpanniversary' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-meta -->
</article><!-- #post-## -->
