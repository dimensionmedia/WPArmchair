<?php

add_action( "init", 'wpann_register_cpt' );

function wpann_register_cpt() {
		
    $labels = array( 
        'name' => _x( 'User Submitted Posts', 'faq' ),
        'singular_name' => _x( 'User Submitted Post', 'faq' ),
        'add_new' => _x( 'Add New', 'faq' ),
        'add_new_item' => _x( 'Add New User Submitted Post', 'faq' ),
        'edit_item' => _x( 'Edit User Submitted Post', 'faq' ),
        'new_item' => _x( 'New User Submitted Post', 'faq' ),
        'view_item' => _x( 'View User Submitted Post', 'faq' ),
        'search_items' => _x( 'Search User Submitted Posts', 'faq' ),
        'not_found' => _x( 'No User Submitted Posts found', 'faq' ),
        'not_found_in_trash' => _x( 'No User Submitted Posts found in Trash', 'faq' ),
        'parent_item_colon' => _x( 'Parent User Submitted Post:', 'faq' ),
        'menu_name' => _x( 'User Submitted Posts', 'faq' ),
    );
    
    //set up the rewrite rules
    $rewrite = array(
        'slug' => 'user-submitted-posts'
    );

    $args = array( 
        'labels' => $labels,
        'hierarchical' => false,
        'description' => 'Uploaded posts from registered users.',
        'supports' => array( 'title', 'page-attributes', 'editor', 'thumbnail', 'author' ),        
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => false,
        'publicly_queryable' => true,
        'exclude_from_search' => true,
        'has_archive' => false,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => $rewrite,
        'capability_type' => 'post',
        'register_meta_box_cb' => 'wpann_add_user_submitted_metabox'
    );

    $test = register_post_type( 'wpann_user_submitted', $args );
        
}


/*
 * Add Meta Box For This Post Type
 */

function wpann_add_user_submitted_metabox() {
	
	add_meta_box('wpann_user_submitted_information', 'Additional Information', 'wpann_user_submitted_meta' , 'wpann_user_submitted', 'normal', 'default');
	
}


/*
 * Add Fields For Meta Box
 */

function wpann_user_submitted_meta() {
	global $post;
	
	// Noncename needed to verify where the data originated
	echo '<input type="hidden" name="user_submit_meta_noncename" id="user_submit_meta_noncename" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	
	$wpann_us_location = get_post_meta($post->ID, 'wpann_us_location', true);
	
	// Echo out the fields
	echo '<label>Location:</label> <input type="text" name="wpann_us_location" value="' . $wpann_us_location  . '" class="widefat" />';
	
}



/*
 * Saving Metabox Data
 */

add_action( "save_post", 'wpann_save_events_meta' , 1, 2);

function wpann_save_events_meta($post_id, $post) {

	if ( isset( $_POST['user_submit_meta_noncename'] ) ) {
	
		// verify this came from the our screen and with proper authorization,
		// because save_post can be triggered at other times
				
		if ( !wp_verify_nonce( $_POST['user_submit_meta_noncename'], plugin_basename(__FILE__) )) {
			return $post->ID;
		}
	
		// Is the user allowed to edit the post or page?
		
		if ( !current_user_can( 'edit_post', $post->ID ))
			return $post->ID;
	
		// OK, we're authenticated: we need to find and save the data
		// We'll put it into an array to make it easier to loop though.
		
		$tweets_meta['wpann_us_location'] = $_POST['wpann_us_location'];

		
		// Add values of $events_meta as custom fields
		
		foreach ($tweets_meta as $key => $value) { // Cycle through the $tweets_meta array
		
			if( $post->post_type == 'revision' ) return; // Don't store custom data twice
			
			$value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
			
			if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
				update_post_meta($post->ID, $key, $value);
			} else { // If the custom field doesn't have a value
				add_post_meta($post->ID, $key, $value);
			}
			
			if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
		}
	
	}

}



/*
 * Register Tax Term
 */

add_action( "init", 'wpann_register_tax' );         

function wpann_register_tax() {


	// Add new taxonomy, make it hierarchical (like categories)
	$labels = array(
	    'name' => _x( 'Submission Types', 'taxonomy general name' ),
	    'singular_name' => _x( 'Submission Types', 'taxonomy singular name' ),
	    'search_items' =>  __( 'Search Submission Types' ),
	    'all_items' => __( 'All Submission Types' ),
	    'parent_item' => __( 'Parent Submission Type' ),
	    'parent_item_colon' => __( 'Parent Submission Type:' ),
	    'edit_item' => __( 'Edit Submission Type' ), 
	    'update_item' => __( 'Update Submission Type' ),
	    'add_new_item' => __( 'Add New Submission Type' ),
	    'new_item_name' => __( 'New Submission Type Name' ),
	    'menu_name' => __( 'Submission Types' ),
	); 	
	
	register_taxonomy('wpann_submission_types',array('wpann_user_submitted'), array(
	    'hierarchical' => true,
	    'labels' => $labels,
	    'show_ui' => true,
	    'query_var' => true
	));
	

	// add the media category option, if it exits	

	if ( taxonomy_exists('wpann_media_categories') ) {

		$term = term_exists('User Submitted', 'wpann_media_categories');
		
		if ($term !== 0 && $term !== null) {
		
			// this exists, do nothing
			
		} else {

			$parent_term_id = 0; // there's no parent (yet)
			
			wp_insert_term(
			  'User Submitted', // the term 
			  'wpann_media_categories', // the taxonomy
			  array(
			    'description'=> 'Tweets from the Twitter social network.',
			    'slug' => 'twitter',
			    'parent'=> $parent_term_id
			  )
			);
			
		} // if term isn't null
		
	} // if tax exists
	
} // wpann_register_tax
