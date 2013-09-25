<?php get_header(); ?>

    <!-- Top
    ================================================== -->
    <div id="myTop" class="myTopcontainer">
      <div class="myTopcontainer-inner">
        <div class="bigitem active">        
          <img src="<?php header_image(); ?>" alt="" class="jumbo-tron" />
          
          <div class="container">
          
      			<div class="row">
      			
      				<div class="span6">
      				
			            <div class="carousel-caption">
			            
			            
			              <h1>WordCamp San Francisco</h1>
			              
			              <p class="lead">Watch the action on July 26-28th, 2013.</p>
			              
			              
						    <form method="get" action="/" class="form-inline" >
						      <a href="http://2013.sf.wordcamp.org/" target="_blank" class="btn btn-large">Visit Official Site</a>
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
								wpann_process_post_display ( $post, 'media-item col2 featured', 'featured' );
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
    
    		 
      
      <div class="container how-to">
      
      	<div class="row">
      	
      		<div class="span12">
      		
				  <div class="row">
				  
				  	<div class="span2">&nbsp;</div>
				  
			        <div class="span2">
			        	<div class="well well-small">
			          <h3><i class="icon-picture"></i> Photos</h3>
			      	  <p>Post a public picture to <i class="icon-camera-retro"></i> Instagram, <i class="icon-twitter"></i> Twitter, or <i class="icon-camera"></i> Flickr &amp; tag it <strong>#wcsf</strong>.</p>
			        </div>
			        </div>
			        <div class="span2">
			        	<div class="well well-small">

			          <h3><i class="icon-twitter"></i> Tweets</h3>
				      <p>Use the hashtag <strong>#wcsf</strong> on Twitter and we'll grab your tweets.</p>
			       </div>
			        </div>
			        <div class="span2">
			        	<div class="well well-small">

			          <h3><i class="icon-facetime-video"></i> Video</h3>
			          <p>Share your <i class="icon-twitter"></i> Vine &amp; <i class="icon-camera-retro"></i> Instagram <span class="label label-success">new</span> videos with the hashtag <strong>#wcsf</strong>. </p>
			        </div>
			        </div>
			        <div class="span2">
			        	<div class="well well-small">

			          <h3><i class="icon-upload"></i> Submit</h3>
			          <p>Submit your own photo to us directly via the <a href="<?php echo home_url('submit-photo'); ?>">WordPress mobile app</a>. </p>
			        </div>
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
			    <span class="notice-message">Just chilling out for a few seconds.</span>
			  </a>
			</p>
			<p class="message"> Why not go <a href="#" id="fullscreen_trigger">fullscreen</a> or 
			
			<?php if ($_GET['allimages'] != "true") { ?>
			
			<a href="<?php home_url(); ?>/?allimages=true">view only photos</a>
			
			<?php } else { ?>

			<a href="<?php home_url(); ?>/">see everything</a>			
			
			<?php } ?>
			?</p>
	    </div>
	
	    <div class="container" id="media-container">
	        	
		    	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		    	
		    		<?php wpann_process_post_display ( $post ); ?>
			
				<?php endwhile; else: ?>
				
					<p><?php _e('Sorry, no media posts exist.'); ?></p>
					
				<?php endif; ?>
	
		  </div> <!-- #container -->
	
	      <div id="push"></div>
	      
	    </div>
    
    </div> <!-- fullscreen container -->


    <div class="container" id="loadmore_div">
		<p style="text-align: center;">
		  <a class="btn btn-large has-spinner">
		    <span class="spinner"><i class="icon-spin icon-refresh"></i></span>
		    <span class="loadmore-message">Load More</span>
		  </a>
		</p>
    </div>
    
    
    <p>&nbsp;</p>


<?php get_footer(); ?>