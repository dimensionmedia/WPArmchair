<?php
/**
 * WPArmchair functions and definitions
 *
 * @package WPArmchair
 */
 
/*--------------------------------------------*
 * Includes
 *--------------------------------------------*/

// require_once(get_template_directory() . '/inc/functions/user-submitted-photos.php');


/*--------------------------------------------*
 * Globals / Misc Variables
 *--------------------------------------------*/

global $last_media_id;
global $load_more_media_id;

$last_media_id = 0;
$load_media_date = null;
$load_more_media_id = 0;
$load_more_media_date = null;


/**
 *
 * wparm_media_display(): alters loop on homepage - scans for the known post types (most provided by the plugins)
 *
 */

function wparm_media_display ( $query ) {
    if ( $query->is_home() && $query->is_main_query() ) {
        $query->set( 'post_type', array ( 'wpgtp_tweets', 'wpgip_instagrams', 'wpgfp_flickrs', 'post' ) );
        $query->set( 'numberposts', 60 ); // we want them all
        $query->set( 'posts_per_page', 60 );
        $query->set( 'post_status', array ( 'publish', 'inherit' ) ); // only published (approved?) posts
        $query->set( 'post_parent', null ); // any parent
        if ( isset($_GET['allimages']) && $_GET['allimages'] == "true") { $query->set( 'meta_key', '_thumbnail_id' ); } // search only images
        $query->set( 'orderby', 'date' );
        $query->set( 'order', 'DESC' );
        // $query->set( 'offset', 5 );        
    }
    if ( is_search() && $query->is_main_query() ) {
        $query->set( 'numberposts', 200 ); // we want the first 200
        $query->set( 'posts_per_page', 200 );        
        if ( isset($_GET['allimages']) && $_GET['allimages'] == "true") { $query->set( 'meta_key', '_thumbnail_id' ); } // search only images
        $query->set( 'orderby', 'date' );
        $query->set( 'order', 'DESC' );        
    }    
    return $query;
}
add_action( 'pre_get_posts', 'wparm_media_display' );



/**
 *
 * wparm_add_post_thumbnail_column() & wparm_display_post_thumbnail_column() : 
 * add featured thumbnail when viewing posts, helps when scanning pages and
 * pages of tweets to determine which have images attached
 *
 */

add_filter('manage_posts_columns', 'wparm_add_post_thumbnail_column', 5);
add_filter('manage_pages_columns', 'wparm_add_post_thumbnail_column', 5);
 
function wparm_add_post_thumbnail_column($cols){
  $cols['wp_post_thumb'] = __('Featured');
  return $cols;
}

add_action('manage_posts_custom_column', 'wparm_display_post_thumbnail_column', 5, 2);
add_action('manage_pages_custom_column', 'wparm_display_post_thumbnail_column', 5, 2);

// Get scaled featured-thumbnail & display it.
function wparm_display_post_thumbnail_column($col, $id){
  switch($col){
    case 'wp_post_thumb':
      if( function_exists('the_post_thumbnail') )
        echo the_post_thumbnail( 'thumbnail' );
      else
        echo 'Not supported in theme';
      break;
  }
}



/**
 *
 * wparm_ajaxurl(): simply adds the ajaxurl so my js can reference it
 *
 */

function wparm_ajaxurl() {
	?>
	<script type="text/javascript">
	var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
	</script>
	<?php
}
add_action('wp_head','wparm_ajaxurl');



/**
 *
 * filter_where(): controls the output of the timeline featured post output, by date
 *
 */
 
if ( get_theme_mod('timeline_active') == "on" && function_exists('verite_timeline_shortcode') ) {

	function filter_where( $where = '' ) {
			// the data format should be like 2013-07-26 - not sure how to confirm this easily
			
			if ( get_theme_mod('timeline_active_start_date') && get_theme_mod('timeline_active_end_date') ) {

			$where .= " AND post_date >= '".get_theme_mod('timeline_active_start_date')."' AND post_date <= '".get_theme_mod('timeline_active_end_date')."'"; 
//					$where .= " AND post_date >= '2013-07-26' AND post_date <= '2013-07-29'"; 
//			echo $where; exit;
			return $where;
			
			}
	}

}



/**
 *
 * wparm_load_more(): this runs when someone clicks the load more button at the bottom
 *
 */
 
function wparm_load_more() {
	global $load_more_media_id;
	global $load_more_media_date;

	$allowed = array(
	    'a' => array(
	        'href' => array(),
	        'title' => array()
	    ),
	    'br' => array(),
	    'em' => array(),
	    'strong' => array()
	);	
	
	$load_more_media_id = $_POST['last_media_id'];
	$load_more_media_date = $_POST['last_media_date'];
	$s = wp_kses($_POST['s'], $allowed);
	
	if ( $load_more_media_id > 0 || $load_more_media_date ) {
			
		// get set of posts
		
		$args = array(
			'post_type' => array ( 'wpgtp_tweets', 'wpgip_instagrams', 'wpgfp_flickrs', 'post' ),
			'post_status' => 'publish',
			'posts_per_page' => 10,
			'orderby' => 'date',
			's' => $s,
			'order' => 'DESC'
		);
		
        if ($_POST['allimages'] == "true") { 
        
        	$args['meta_key'] = '_thumbnail_id' ; // search only images }
        	
        }

		$the_query = new WP_Query();
		
		add_filter('posts_where', 'wparm_filter_where_id_lessthan');
		
		$the_query->query($args); 
		
		// The Loop
		while ( $the_query->have_posts() ) :
			$the_query->the_post();
			wparm_process_post_display ( $the_query->post, null, 'older' );
		endwhile;
		
		remove_filter('posts_where', 'wparm_filter_where_id_lessthan');
		
	}
	
	die();
	
}
add_action("wp_ajax_wparm_load_more", "wparm_load_more");
add_action("wp_ajax_nopriv_wparm_load_more", "wparm_load_more");

/**
 *
 * wparm_load_more(): this runs when automatically pinged by the js
 *
 */


function wparm_update_media() {
	global $last_media_id;
	global $last_media_date;	
	

	$allowed = array(
	    'a' => array(
	        'href' => array(),
	        'title' => array()
	    ),
	    'br' => array(),
	    'em' => array(),
	    'strong' => array()
	);
	
	$last_media_id = wp_kses($_POST['last_media_id'], $allowed);
	$last_media_date = wp_kses($_POST['last_media_date'], $allowed);
	$s = wp_kses($_POST['s'], $allowed);
	
	if ( $last_media_id ) {
			
		// get set of posts
		
		$args = array(
			'post_type' => array ( 'wpgtp_tweets', 'wpgip_instagrams', 'wpgfp_flickrs', 'post' ),
			'post_status' => 'publish',
			'posts_per_page' => 10,
			'orderby' => 'date',
			's'	=> $s,
			'order' => 'DESC'
		);
		
        if ($_POST['allimages'] == "true") { 
        
        	$args['meta_key'] = '_thumbnail_id' ; // search only images }
        	
        }

		$the_query = new WP_Query();
		
		add_filter('posts_where', 'wparm_filter_where_id_greater');
		
		$the_query->query($args); 
		
		// The Loop
		while ( $the_query->have_posts() ) :
			$the_query->the_post();
			wparm_process_post_display ( $the_query->post, null, 'new' );
		endwhile;
		
		remove_filter('posts_where', 'wparm_filter_where_id_greater');
		
	}

	
	die();
	
}

add_action("wp_ajax_wparm_update_media", "wparm_update_media");
add_action("wp_ajax_nopriv_wparm_update_media", "wparm_update_media");


/**
 *
 * wparm_load_more(): gets called by wparm_update_media(), modifies search to look for older posts
 *
 */


function wparm_filter_where_id_lessthan ( $where = '' ) {
	global $load_more_media_id;
	global $load_more_media_date;
	
	if ( $load_more_media_date ) {
	    $query_string = get_search_query();
	    $where .= " AND wp_posts.post_date < '".date('Y-m-d H:i:s', (int) $load_more_media_date). "' ";
	}
		
	return $where;
	
}


/**
 *
 * wparm_filter_where_id_greater(): gets called by wparm_update_media(), modifies search to look for new posts
 *
 */


function wparm_filter_where_id_greater ( $where = '' ) {
	global $last_media_id;
	global $last_media_date;	
	
	if ( $last_media_date ) {
	    $query_string = get_search_query();
	    $where .= " AND wp_posts.post_date > '".date('Y-m-d H:i:s', (int) $last_media_date). "' ";                   
	}
	
	return $where;
	
}




/**
 *
 * wparm_process_post_display(): function that handles the actual media type output (the box)
 *
 */


function wparm_process_post_display ( $post = false, $div_class = false, $type = "normal" ) {

	if ( !$post ) {
		return;
	}
	
	if ( !$div_class ) {
		
		$div_class = "media-item col2 ".$type." media-".$post->ID;
	}
	
	// define default image attributes
		
	$default_image_attr = array(
		'class'		=> "img-polaroid attachment",
		'alt'		=> ""
	);	
		
	// determine what kind of media item this is
		    	
	$media_types = wp_get_post_terms ( $post->ID, 'wpgip_media_categories' , array("fields" => "slugs"));
	
	// set variables up according to type, etc.
	
	if ( $type == "featured" ) {
		
		$image_width = "300x300";
		
		$header = "<h4>Featured Posts:</h4>";
		
	} else {
		
		$image_width = "280";
		
		$header = "";
		
	}
	
	$wp_timezone_setting = get_option('timezone_string');
	if ( !$wp_timezone_setting ) { $wp_timezone_setting = "America/New_York"; }
	$date = new DateTime( get_the_date ( 'Y-m-d H:i:s +00' ) ); 
	$date->setTimezone(new DateTimeZone( $wp_timezone_setting )); 

	$new_date = $date->format('M jS, Y g:ia T'); 
			    			    	
	if ( $post->post_type == "wpgip_instagrams" ) {
	
		// instagram - get metadata
	
	    $image_attributes = wp_get_attachment_image_src( $post->ID, 'custom-500-thumbnail' );
	    $link = get_post_meta ( $post->ID, 'wpgip_ip_url', true );
	    $username = get_post_meta ( $post->ID, 'wpgip_ip_username', true );
	    $image_large_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'custom-750-thumbnail' );


   		// check and see if this instagram has a video with it

		$video_url = get_post_meta ( $post->ID, 'wpgip_ip_video_expanded_url', true );
								
		if ( !empty($video_url) ) {
		
			$video_width = get_post_meta ( $post->ID, 'wpgip_ip_video_width', true );
			$video_height = get_post_meta ( $post->ID, 'wpgip_ip_video_height', true );
		
		}


		?>
    	
	          <div class="<?php echo $div_class; ?>" data-internalid="<?php echo $post->ID; ?>" data-timestamp="<?php echo strtotime($post->post_date); ?>">
	          
	          <div class="bg">
	          
	          <?php echo $header; ?>
	          
	          <div class="thumbnail thumbnail-<?php echo $post->ID; ?> thumbnail-<?php echo $type; ?>"   data-url="<?php echo get_permalink($post->ID); ?>">
	            

	            
	            <?php if ( !empty($video_url) ) { ?>
	            								
					<a href="<?php echo $link; ?>embed" title="Posted on <?php echo $new_date; ?> by @<?php echo $username; ?>" class="iframe-instagram"><i class="icon-play-sign play-video vine"></i></a>
					
					<a href="<?php echo $link; ?>embed" title="Posted on <?php echo $new_date; ?> by @<?php echo $username; ?>" class="iframe-instagram"><?php echo the_post_thumbnail( 'custom-'.$image_width.'-thumbnail', $default_image_attr ); ?></a>

	            <?php }	else if ( has_post_thumbnail() ) { ?>
	            
	              <a href="<?php echo $image_large_src[0]; ?>" title="Posted on <?php echo $new_date; ?> by @<?php echo $username; ?>" class="thumb"><?php echo the_post_thumbnail( 'custom-'.$image_width.'-thumbnail', $default_image_attr ); ?></a>
	              
	            <?php } ?>
	            

	              <div class="caption">
	                
	                <?php the_excerpt(); ?>
	                
	                <hr/>
	                
	                <div class="metadata">
	                	<p><i class="icon-camera-retro"></i>&nbsp; <?php echo _e( 'Posted on', 'wparmchair' ); ?> <a href="<?php echo $link; ?>" target="_blank"><?php echo $new_date; ?></a> <?php echo _e( 'by', 'wparmchair' ); ?> <a target="_blank" href="http://instagram.com/<?php echo $username; ?>">@<?php echo $username; ?></a></p>
	                </div>
	                
	              </div>
	              
	              <div class="caption rollover clearfix">
	              
		              <div class="left">
		              		              
			              <?php edit_post_link( __( 'Edit', 'wparmchair' ), '<span class="edit-link">', '</span>' ); ?>
			              
		              </div>
		              
		              <div class="right">

			              <?php if ( function_exists( 'lip_love_it_link' ) ) { echo lip_love_it_link( $post->ID, '<i class="icon-heart-empty"></i>', 'test <i class="icon-heart"></i>', true ); } ?>  
			              
			              <span class="makeComment"><a href="<?php echo get_permalink(); ?>" title="View And Comment On This Post"><i class="icon-comment"></i> <em>(0)</em></a> </span>
		              
		              </div>
	              
	              </div>

	              
	            </div>
	            
	          </div>
	            
	          </div> <!-- media item -->

   <?php } else if ( $post->post_type == "wpgtp_tweets" ) { 

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
		$avatar = get_post_meta ( $post->ID, 'wpgtp_tw_user_profile_avatar_url', true );
		$image_large_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'custom-750-thumbnail' );		
		$tweet = $post->post_content;

	    $link = 'https://twitter.com/'.$username.'/status/' . $tweet_id; 				   
	   
   ?>
	
	          <div class="<?php echo $div_class; ?> <?php if ( !has_post_thumbnail() ) {?>no-photo<?php } ?>" data-internalid="<?php echo $post->ID; ?>" data-timestamp="<?php echo strtotime($post->post_date); ?>">
	          
	          	<div class="bg">
	          
	          	<?php echo $header; ?>
	          
	          <div class="thumbnail thumbnail-<?php echo $post->ID; ?> thumbnail-<?php echo $type; ?>"   data-url="<?php echo get_permalink($post->ID); ?>">
	            
	            <?php if ( has_post_thumbnail() && !is_single() ) { ?>
	            
	              <a href="<?php echo $image_large_src[0]; ?>" title="Posted on <?php echo $new_date; ?> by @<?php echo $username; ?>" class="thumb"><?php echo the_post_thumbnail( 'custom-'.$image_width.'-thumbnail', $default_image_attr ); ?></a>
	              
   	            <?php } else if ( has_post_thumbnail() && is_single() ) { ?>
   	            
	              <a href="<?php echo $image_large_src[0]; ?>" title="Posted on <?php echo $new_date; ?> by @<?php echo $username; ?>" class="thumb"><?php echo the_post_thumbnail( 'full', $default_image_attr ); ?></a>   	            
	              
	            <?php } ?>
	            
	            <?php if ( !empty($video_url) ) { ?>
	            				
					<?php /* <a href="<?php echo $link; ?>" target="_blank"><img width="<?php echo ( $image_width ); ?>" height="<?php echo ( $image_width ); ?>" src="<?php echo $video_image; ?>" class="img-polaroid attachment" /></a> */ ?>
					
					<a href="<?php echo $video_url; ?>/embed/simple" title="Posted on <?php echo $new_date; ?> by @<?php echo $username; ?>" class="iframe"><i class="icon-play-sign play-video vine"></i></a>
					
	                <a href="<?php echo $video_url; ?>/embed/simple" title="Posted on <?php echo $new_date; ?> by @<?php echo $username; ?>" class="iframe"><img width="<?php echo ( $image_width ); ?>" height="<?php echo ( $image_width ); ?>" src="<?php echo $video_image; ?>" class="img-polaroid attachment" /></a>
					
	            <?php } ?>

	              <div class="caption">
	                
	                <?php echo wpautop( make_clickable( wparm_twitterify( $tweet ) ) ); ?>
	                
	                <hr/>
	                	                
	              </div>
	              
	                <div class="metadata">
	                	<?php if ( $avatar ) { ?><a target="_blank" href="http://twitter.com/<?php echo $username; ?>"><img src="<?php echo $avatar; ?>" width="30" height="30" class="avatar" /></a><?php } ?>
	                	<p><?php if ( !empty($video_url) ) { ?><i class="icon-facetime-video"></i><?php } else { ?><i class="icon-twitter"></i><?php } ?> <?php echo _e( 'Posted on', 'wparmchair' ); ?> <a href="<?php echo $link; ?>" target="_blank"><?php echo $new_date; ?></a> <?php echo _e( 'by', 'wparmchair' ); ?> <a target="_blank" href="https://twitter.com/<?php echo $username; ?>">@<?php echo $username; ?></a></p>
	                	<div class="clearfix"></div>
	                </div>
	              

	            
	              <div class="caption rollover clearfix">
	              
		              <div class="left">
		              
			              <span class="tweetButton"><a href="https://twitter.com/intent/retweet?tweet_id=<?php echo $tweet_id ;?>" target="_blank" title="Retweet" rel="nofollow"><i class="icon-retweet"></i></a></span>
		              
			              <span class="tweetFav"><a href="https://twitter.com/intent/favorite?tweet_id=<?php echo $tweet_id ;?>" target="_blank" title="Favorite" rel="nofollow"><i class="icon-star-empty"></i></span>
			              
			              <?php edit_post_link( __( 'Edit', 'wparmchair' ), '<span class="edit-link">', '</span>' ); ?>
			              
		              </div>
		              
		              <div class="right">

			              <?php if ( function_exists( 'lip_love_it_link' ) ) { echo lip_love_it_link( $post->ID, '<i class="icon-heart-empty"></i>', 'test <i class="icon-heart"></i>', true ); } ?>  
			              
			              <span class="makeComment"><a href="<?php echo get_permalink(); ?>" title="View And Comment On This Post"><i class="icon-comment"></i> <em>(0)</em></a> </span>
		              
		              </div>
	              
	              </div>

	            </div>
	            
	            </div>
	            	            
	          </div> <!-- media item -->
	

   <?php } else if ( $post->post_type == "wpgfp_flickrs" ) { 

		$link = get_post_meta($post->ID, 'wpgfp_fp_flickr_url', true);		
		$wpgfp_fp_image_url = get_post_meta($post->ID, 'wpgfp_fp_image_url', true);
		$wpgfp_fp_image_date_taken = get_post_meta($post->ID, 'wpgfp_fp_image_date_taken', true);
		$image_large_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'custom-750-thumbnail' );
		$username = get_post_meta($post->ID, 'wpgfp_ip_author_name', true);
	   
   ?>
	
	          <div class="<?php echo $div_class; ?>" data-internalid="<?php echo $post->ID; ?>" data-timestamp="<?php echo strtotime($post->post_date); ?>">
	          
	          	<?php echo $header; ?>
	          
	          <div class="thumbnail thumbnail-<?php echo $post->ID; ?> thumbnail-<?php echo $type; ?>"   data-url="<?php echo get_permalink($post->ID); ?>">
	            
	            <?php if ( has_post_thumbnail() ) { ?>
	            
	              <a href="<?php echo $image_large_src[0]; ?>" title="Posted on <?php echo $new_date; ?> by @<?php echo $username; ?>" class="thumb"><?php echo the_post_thumbnail( 'custom-'.$image_width.'-thumbnail', $default_image_attr ); ?></a>
	              
	            <?php } ?>
	            
	              <div class="caption">
	                
	                <?php the_excerpt();  ?>
	                
	                <hr/>
	                
	                <div class="metadata">
	                	<p><i class="icon-camera"></i> <?php echo _e( 'Posted on', 'wparmchair' ); ?> <a href="<?php echo $link; ?>" target="_blank"><?php echo $new_date; ?></a> <?php echo _e( 'by', 'wparmchair' ); ?> <?php echo $username; ?></p>
	                </div>
	                
	              </div>
	              	              
	            </div>
	            
	          </div> <!-- media item -->
	

  
	          
   <?php } else if ( $post->post_type == "post" ) { 
	   
	   	$image = wparm_echo_first_image ( $post->ID, $image_width );
	   	$user_info = get_userdata( $post->post_author );
	   	$username = $user_info->user_nice_name  ? $user_info->user_nice_name : $user_info->user_login;
		$link = get_permalink ( $post->ID );
	   
   ?>
   
	          <div class="<?php echo $div_class; ?>" data-internalid="<?php echo $post->ID; ?>" data-timestamp="<?php echo strtotime($post->post_date); ?>">
	          
	          	<?php echo $header; ?>
	          
	            <div class="thumbnail thumbnail-<?php echo $post->ID; ?> thumbnail-<?php echo $type; ?>"   data-url="<?php echo get_permalink($post->ID); ?>">
	            
	            <?php if ( !empty($image) ) { ?>
	            
	            	<?php if ( is_single() ) { ?>

	              <a href="<?php echo $image['full']; ?>" title="Posted on <?php echo $new_date; ?> by @<?php echo $username; ?>" class="thumb"><img src="<?php echo $image['full']; ?>" /></a>	            	
	            	
	            	<?php } else { ?>
	            
	              <a href="<?php echo $image['full']; ?>" title="Posted on <?php echo $new_date; ?> by @<?php echo $username; ?>" class="thumb"><?php echo $image['thumb']; ?></a>
	              
	              <?php } ?>
	              
	            <?php } ?>
	            
	              <div class="caption">
	                
	                <?php the_excerpt();  ?>
	                
	                <hr/>
	                
	                <div class="metadata">
	                	<?php echo get_avatar( $user_info->ID, 32 ); ?>
	                	<p><i class="icon-comment-alt"></i> <?php echo _e( 'Posted on', 'wparmchair' ); ?> <a href="<?php echo $link; ?>"><?php echo $new_date; ?></a> <?php echo _e( 'by', 'wparmchair' ); ?> <?php echo the_author_meta( 'user_nicename' , $author_id ); ?></p>
	                	<div class="clearfix"></div>
	                </div>
	                
	              <div class="caption rollover clearfix">
	              
		              <div class="left">
		              		              
			              <?php edit_post_link( __( 'Edit', 'wparmchair' ), '<span class="edit-link">', '</span>' ); ?>
			              
		              </div>
		              
		              <div class="right">
		              
			              <?php if ( function_exists( 'lip_love_it_link' ) ) { echo lip_love_it_link( $post->ID, '<i class="icon-heart-empty"></i>', 'test <i class="icon-heart"></i>', true ); } ?>  
			              
			              <span class="makeComment"><a href="<?php echo get_permalink(); ?>" title="View And Comment On This Post"><i class="icon-comment"></i> <em><?php echo get_comments_number( $post->ID ); ?></em></a> </span>
		              
		              </div>
	              
	              </div>
	                
	              </div>
	              

	              
	            </div>
	            
	          </div> <!-- media item -->
   
	          
   <?php }
	
}




function wparm_echo_first_image( $postID = false, $image_width_thumb = '200', $image_width_large = '500' ) {

	if ($postID) {

		$image = array();

		$args = array(
			'numberposts' => 1,
			'order' => 'ASC',
			'post_mime_type' => 'image',
			'post_parent' => $postID,
			'post_status' => null,
			'post_type' => 'attachment',
		);
	
		$attachments = get_children( $args );
	
		if ( $attachments ) {
			foreach ( $attachments as $attachment ) {
				$image_attributes_thumb = wp_get_attachment_image_src( $attachment->ID, 'custom-'.$image_width_thumb.'-thumbnail' )  ? wp_get_attachment_image_src( $attachment->ID, 'custom-'.$image_width_thumb.'-thumbnail' ) : wp_get_attachment_image_src( $attachment->ID, 'thumbnail' );
				$image_attributes_large = wp_get_attachment_image_src( $attachment->ID, 'custom-'.$image_width_large.'-thumbnail' )  ? wp_get_attachment_image_src( $attachment->ID, 'custom-'.$image_width_large.'-thumbnail' ) : wp_get_attachment_image_src( $attachment->ID, 'full' );				
	
				$image['thumb'] = '<img src="' . $image_attributes_thumb[0] . '" class="img-polaroid attachment" />';
				$image['full'] = $image_attributes_large[0];
				
				return $image;
			}
		}
	
	}
}




function wparm_twitterify($ret) {
		$ret = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t< ]*)#", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>", $ret);
		$ret = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r< ]*)#", "\\1<a href=\"//\\2\" target=\"_blank\">\\2</a>", $ret);
		$ret = preg_replace("/@(\w+)/", "<a href=\"//twitter.com/\\1\" target=\"_blank\">@\\1</a>", $ret);
		$ret = preg_replace("/#(\w+)/", "<a href=\"//twitter.com/search?q=\\1\" target=\"_blank\">#\\1</a>", $ret);
		return $ret;
	}

































if ( ! function_exists( 'wparmchair_setup' ) ) :
	function wparmchair_setup() {
	
		/**
		 * Make theme available for translation
		 * Translations can be filed in the /languages/ directory
		 */
		load_theme_textdomain( 'wparmchair', get_template_directory() . '/languages' );
	
		/**
		 * Add default posts and comments RSS feed links to head
		 */
		add_theme_support( 'automatic-feed-links' );
	
		/**
		 * Enable support for Post Thumbnails on posts and pages
		 *
		 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
		 */
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 250, 9999 ); // Normal post thumbnails
		
		// Note: Played and expeirmented alot with image thumbnail sizes. The commented out items below
		// shold be ones not currently in use.
		
		// add_image_size( 'custom-150x150-thumbnail', 150, 150, true );
		// add_image_size( 'custom-200x200-thumbnail', 200, 200, true );
		add_image_size( 'custom-300x300-thumbnail', 300, 300, true );
		add_image_size( 'custom-200-thumbnail', 200, 9999 ); // Unlimited height, soft crop	
		// add_image_size( 'custom-300-thumbnail', 300, 9999 ); // Unlimited height, soft crop
		// add_image_size( 'custom-400-thumbnail', 400, 9999 ); // Unlimited height, soft crop
		add_image_size( 'custom-500-thumbnail', 500, 9999 ); // Unlimited height, soft crop
		add_image_size( 'custom-750-thumbnail', 500, 9999 ); // Unlimited height, soft crop
	
	
		/**
		 * This theme uses wp_nav_menu() in one location.
		 */
		register_nav_menus( array(
			'primary' => __( 'Primary Menu', 'wparmchair' ),
		) );
	
		/**
		 * Enable support for Post Formats
		 */
		add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );
		
		/**
		 * Custom Header
		 */
		
		define( 'HEADER_IMAGE_WIDTH', apply_filters( 'wparm_header_image_width', 1700 ) );
		define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'wparm_header_image_height',	733 ) );
		
		// Don't support text inside the header image.
		define( 'NO_HEADER_TEXT', true );
		
		// Add a way for the custom header to be styled in the admin panel that controls
		// custom headers. See yourtheme_admin_header_style(), below.
		add_custom_image_header( '', 'wparm_admin_header_style' );
		
		$args = array(
			'width'         => HEADER_IMAGE_WIDTH,
			'height'        => HEADER_IMAGE_HEIGHT,
			'default-image' => get_template_directory_uri() . '/img/default/default-header-image.jpg',
			'uploads'       => true,
		);
		add_theme_support( 'custom-header', $args );


		
	}
	endif; // wparmchair_setup
add_action( 'after_setup_theme', 'wparmchair_setup' );




function wparmchair_customize_css()
{
    ?>
         <style type="text/css">
	         html, body, #wrap { background-color:<?php echo get_theme_mod('bg_color'); ?>; }
	         body, #notice_div p.message { color: <?php echo get_theme_mod('text_primary_color'); ?> }
             #masthead { background-color:<?php echo get_theme_mod('top_nav_bgcolor'); ?>; }
             .main-navigation ul li.current_page_item:after { border-color:<?php echo get_theme_mod('top_nav_bgcolor'); ?> transparent -moz-use-text-color; }
             .main-navigation .current-menu-item > a, .main-navigation .current-menu-ancestor > a, .main-navigation .current_page_item > a, .main-navigation .current_page_ancestor > a { color: <?php echo get_theme_mod('top_nav_active_textcolor'); ?> }
             .main-navigation li a { color: <?php echo get_theme_mod('top_nav_textcolor'); ?> }
             .myTopcontainer-inner { background-color:<?php echo get_theme_mod('top_header_bgcolor'); ?>; }
             .carousel-caption h1, .carousel-caption .lead { color: <?php echo get_theme_mod('top_header_text'); ?> }
             #myTop .myTopcontainer-inner .btn { background-color:<?php echo get_theme_mod('top_header_button_background_color'); ?>; background-image: none; }
             .myTopcontainer-inner .btn { color:<?php echo get_theme_mod('top_header_button_text_color'); ?>; border-color: <?php echo get_theme_mod('top_header_button_border_color'); ?>; }
             #notice_div .has-spinner { color: <?php echo get_theme_mod('text_status_color'); ?> }
             #notice_div p.message a { color: <?php echo get_theme_mod('text_link_color'); ?> }
             .thumbnail { background-color: <?php echo get_theme_mod('panel_bg'); ?> }
             .thumbnail .caption { color: <?php echo get_theme_mod('panel_text_color'); ?> }
             #media-container .media-item { border-color: <?php echo get_theme_mod('panel_dot_color'); ?> }
             .metadata a, .caption.rollover a { color: <?php echo get_theme_mod('panel_metadata_link_color'); ?> }            
             #footer { background-color: <?php echo get_theme_mod('footer_bg'); ?>; color: <?php echo get_theme_mod('footer_text_color'); ?>; }
             #footer a  { color: <?php echo get_theme_mod('footer_link_color'); ?> }
             <?php if ( get_theme_mod('timeline_active') == "on" && function_exists('verite_timeline_shortcode') ) { ?>
             #wrap > .container.how-to { display: none; }
             <?php } ?>
         </style>
    <?php
}
add_action( 'wp_head', 'wparmchair_customize_css');


/**
 * Register widgetized area and update sidebar with default widgets
 */
function wparmchair_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'wparmchair' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'wparmchair_widgets_init' );

/**
 * Enqueue scripts and styles
 */
function wparmchair_scripts() {

	wp_register_style( 'twitter-bootstrap', get_template_directory_uri() . '/css/bootstrap.css', array() );		
	wp_register_style( 'font-awesome', get_template_directory_uri() . '/css/font-awesome.min.css', array() );		
	wp_register_style( 'wparm-screen-css', get_template_directory_uri() . '/css/screen.css', array() );		
	wp_register_style( 'colorbox-css', get_template_directory_uri() . '/css/colorbox.css', array() );			

	// Enqueue ALL THE stylesheets (kidding)
	wp_enqueue_style( 'twitter-bootstrap' );	
	wp_enqueue_style( 'font-awesome' );
	wp_enqueue_style( 'wparm-screen-css' );
	wp_enqueue_style( 'colorbox-css' );

	// The javascript scripts
	wp_enqueue_script( 'twitter-bootstrap', get_template_directory_uri() . '/js/bootstrap.js', array( 'jquery' ), '20120205', true );
	wp_enqueue_script( 'js-masonry', get_template_directory_uri() . '/js/jquery.masonry.min.js', array( 'jquery' ), '20120205', true );
	wp_enqueue_script( 'modernizr-transitions', get_template_directory_uri() . '/js/modernizr-transitions.js', array( 'jquery' ), '20120205', true );
	// wp_enqueue_script( 'jquery-video-js', get_template_directory_uri() . "/js/video.js", array( 'jquery' ), '20120205', true );
	wp_enqueue_script( 'js-colorbox', get_template_directory_uri() . '/js/jquery.colorbox-min.js', array( 'jquery' ), '20120205', true );	
	wp_enqueue_script( 'js-notifications', get_template_directory_uri() . '/js/jquery.jwNotify.js', array( 'jquery' ), '20120205', true );	
	wp_enqueue_script( 'wparmchair-global', get_template_directory_uri() . '/js/global.js', array( 'jquery' ), '20120205', true );
	
	wp_enqueue_script( 'jquery-effects-core' );	
	wp_enqueue_script( 'jquery-effects-highlight' );
	
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'wparmchair-keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
	}
}
add_action( 'wp_enqueue_scripts', 'wparmchair_scripts' );

/**
 * Custom template tags for this theme.
 */
require( get_template_directory() . '/inc/template-tags.php' );

/**
 * Custom functions that act independently of the theme templates.
 */
require( get_template_directory() . '/inc/extras.php' );

/**
 * Customizer additions.
 */
require( get_template_directory() . '/inc/customizer.php' );

/**
 * Load Jetpack compatibility file.
 */
require( get_template_directory() . '/inc/jetpack.php' );


function print_menu_shortcode($atts, $content = null) {
    extract( shortcode_atts( array( 'name' => null ), $atts ) );
    return wp_nav_menu( array( 'menu' => $name, 'echo' => false ) );
}
add_shortcode('menu', 'print_menu_shortcode');