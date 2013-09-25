<?php get_header(); ?>


<?php 

	/* There's an option for the user to switch on a 'timeline' interface (probably post-conference)
	/* This requires the install of the timeline-verite-shortcode plugin and json-api plugin
	/* See the readme... but no worries, even if the user wants this on without the plugins it'll never happen */

	if ( get_theme_mod('timeline_active') == "on" && function_exists('verite_timeline_shortcode') ) { 
		
		
	?>

<!-- Top
    ================================================== -->
    <div id="myTop" class="myTopcontainer">
      <div class="myTopcontainer-inner">
        <div class="bigitem active">        
           <img src="<?php header_image(); ?>" alt="<?php bloginfo( 'name' ); ?>" class="jumbo-tron" />
        </div>

        <?php echo do_shortcode('[timeline width="100%" height="620" maptype="toner" src="'.home_url().'/api/wparmchair/featured_posts/?amount=100"]'); ?>

      </div>
    </div><!-- /.carousel -->
    
<?php } else { ?>
    
    <!-- Top
    ================================================== -->
    <div id="myTop" class="myTopcontainer">
      <div class="myTopcontainer-inner">
        <div class="bigitem active">        
          <img src="<?php header_image(); ?>" alt="<?php bloginfo( 'name' ); ?>" class="jumbo-tron" />
          
          <div class="container">
          
      			<div class="row">
      			
      				<div class="span6">
      				
			            <div class="carousel-caption">
			            
			            
			              <h1><?php bloginfo( 'name' ); ?></h1>
			              
			              <p class="lead"><?php bloginfo( 'description' ); ?></p>
			              
			              
						    <form method="get" action="/" class="form-inline" >
						      <a href="http://wpyall.com/" target="_blank" class="btn btn-large"><?php _e( 'Visit Official Site ', 'wparmchair' ); ?></a>
						    </form>
						    
						    
			            </div>
      				
      				</div>
      			
      				<div class="span5 offset0">
      				      				
						<div id="myCarousel" class="carousel slide">
						<ol class="carousel-indicators">
						  <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
						  <li data-target="#myCarousel" data-slide-to="1"></li>
						  <li data-target="#myCarousel" data-slide-to="2"></li>
						</ol>
						<div class="carousel-inner">
  		      			<?php
		    		
		    			// get last featured post
		    			
		    			$counter = 1;
		    			
						$args = array(
							'post_type' => array ( 'wpgtp_tweets', 'wpgip_instagrams', 'wpgfp_flickrs', 'post' ),
							'orderby'	=> 'post_date',
							'order'		=> 'DESC',
							'posts_per_page' => 3,
							'tax_query' => array(
								'relation' => 'OR',
								array(
									'taxonomy' => 'wpgtp_tweet_types',
									'field' => 'slug',
									'terms' => 'featured'
								),
								array(
									'taxonomy' => 'wpgip_instagram_types',
									'field' => 'slug',
									'terms' => 'featured'
								),
								array(
									'taxonomy' => 'wpgfp_flickr_types',
									'field' => 'slug',
									'terms' => 'featured'
								),
								array(
									'taxonomy' => 'category',
									'field' => 'slug',
									'terms' => 'featured'
								)
							)
						);
		
						$the_query = new WP_Query( $args );
						
						// The Loop
						while ( $the_query->have_posts() ) :
							$the_query->the_post();
							if ($counter == 1) { 
								echo '<div class="item active">';
							} else {
								echo '<div class="item">';								
							}
								wparm_process_post_display ( $post, 'media-item col2 featured', 'featured' );
							echo '</div>';
							$counter++;
						endwhile;
		    		
						?>
						</div>
						<a class="left carousel-control" href="#myCarousel" data-slide="prev">&lsaquo;</a>
						<a class="right carousel-control" href="#myCarousel" data-slide="next">&rsaquo;</a>
						</div>

      				     				      				

      				
      				</div>
      				
      			</div>
          

          </div>
        </div>
      </div>
    </div><!-- /.carousel -->
    
<?php 

	} // get_theme_mod('timeline_active') 
	
?>
          
      <div class="container how-to">
      
      	<div class="row">
      	
      		<div class="span12">
      		
				  <div class="row">
				  
				  <?php if ( function_exists( 'lip_love_it_link' ) ) { ?>
				  	<div class="span1">&nbsp;</div>
				  <?php } else { ?>
				  	<div class="span2">&nbsp;</div>				  
				  <?php } ?>
				  
				  
				  
			        <div class="span2">
			        	<div class="well well-small">
			          <h3><i class="icon-picture"></i> <?php _e( 'Photos', 'wparmchair' ); ?></h3>
			          <p><?php printf( __( 'Post a public picture to <i class="icon-camera-retro"></i> Instagram, <i class="icon-twitter"></i> Twitter, or <i class="icon-camera"></i> Flickr &amp; tag it <strong>%1$s</strong>.', 'wparmchair' ), get_theme_mod('misc_hashtag') ); ?></p>
			        </div>
			        </div>
			        <div class="span2">
			        	<div class="well well-small">

			          <h3><i class="icon-twitter"></i> <?php _e( 'Tweets', 'wparmchair' ); ?></h3>
				      <p><?php printf( __(  'Use the hashtag <strong>%1$s</strong> on <i class="icon-twitter"></i> Twitter and we\'ll grab your tweets.', 'wparmchair' ), get_theme_mod('misc_hashtag') ); ?></p>
			       </div>
			        </div>
			        <div class="span2">
			        	<div class="well well-small">

			          <h3><i class="icon-facetime-video"></i> <?php _e( 'Video', 'wparmchair' ); ?></h3>
			          <p><?php printf( __(  'Share your <i class="icon-twitter"></i> Vine &amp; <i class="icon-camera-retro"></i> Instagram videos with the hashtag <strong>%1$s</strong>.', 'wparmchair' ), get_theme_mod('misc_hashtag') ); ?></p>
			        </div>
			        </div>
			        <div class="span2">
			        	<div class="well well-small">

			          <h3><i class="icon-upload"></i> <?php _e( 'Submit', 'wparmchair' ); ?></h3>
			          <p><?php printf( __(  'Submit your own photos directly via the <a href="%1$s">WordPress mobile app</a>.', 'wparmchair' ), home_url('submit-photo') ); ?></p>
			        </div>
			        </div>
			        <div class="span2">
	                 <?php if ( function_exists( 'lip_love_it_link' ) ) { ?>
		        	<div class="well well-small">
			          <h3><i class="icon-heart"></i> <?php _e( 'Love', 'wparmchair' ); ?></h3>
			          <p><?php _e( 'Love your favorite posts by clicking on <i class="icon-heart-empty"></i>. Most loved posters might get a treat.', 'wparmchair' ); ?></p>
			        </div>
			        <?php } ?>
			        </div>
			        
				  	<div class="span2">&nbsp;</div>
			
			      </div>
      		
      		</div>
      		
      	</div>
      	
      </div>
      		
    <div class="container video-container"></div>
	    
    <div id="fullscreen_container">
      
	    <div class="container no-padding" id="notice_div">
			<p style="text-align: center;">
			  <a class="has-spinner">
			    <span class="spinner"><i class="icon-spin icon-refresh"></i></span>
			    <span class="notice-message"><?php _e('Just chilling out for a few seconds.', 'wparmchair' ); ?></span>
			  </a>
			</p>
			<p class="message"> <?php _e( 'Why not go ', 'wparmchair' ); ?><a href="#" id="fullscreen_trigger"><?php _e( 'fullscreen ', 'wparmchair' ); ?></a> <?php _e( 'or ', 'wparmchair' ); ?> 
			
			<?php if ( isset($_GET['allimages']) && $_GET['allimages'] != "true") { ?>
			
			<a href="<?php home_url(); ?>/?allimages=true"><?php _e( 'view only photos ', 'wparmchair' ); ?></a>
			
			<?php } else { ?>

			<a href="<?php home_url(); ?>/"><?php _e( 'see everything ', 'wparmchair' ); ?></a>			
			
			<?php } ?>
			?</p>
	    </div>
	
	    <div class="container" id="media-container">
	        	
		    	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		    	
		    		<?php wparm_process_post_display ( $post ); ?>
			
				<?php endwhile; else: ?>
				
					<p><?php _e('Sorry, no media posts exist.', 'wparmchair' ); ?></p>
					
				<?php endif; ?>
	
		  </div> <!-- #container -->
	
	      <div id="push"></div>
	      
	    </div>
    
    </div> <!-- fullscreen container -->


    <div class="container" id="loadmore_div">
		<p style="text-align: center;">
		  <a class="btn btn-large has-spinner">
		    <span class="spinner"><i class="icon-spin icon-refresh"></i></span>
		    <span class="loadmore-message"><?php _e( 'Load More ', 'wparmchair' ); ?></span>
		  </a>
		</p>
    </div>
    
    
    <p>&nbsp;</p>


<?php get_footer(); ?>