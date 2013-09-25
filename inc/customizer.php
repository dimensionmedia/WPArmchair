<?php
/**
 * WPAnniversary Theme Customizer
 *
 * @package WPArmchair
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function wparmchair_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
	
	$wp_customize->add_setting( 'top_nav_bgcolor' , array(
	    'default'     => '#FFFFFF',
	    'transport'   => 'refresh',
	) );
	
	$wp_customize->add_setting( 'top_nav_active_textcolor' , array(
	    'default'     => '#000000',
	    'transport'   => 'refresh',
	) );
	
	$wp_customize->add_setting( 'top_nav_textcolor' , array(
	    'default'     => '#000000',
	    'transport'   => 'refresh',
	) );
	
	$wp_customize->add_section( 'wparmchair_top_nav' , array(
	    'title'      => __( 'Top Navigation Menu', 'wparmchair' ),
	    'priority'   => 35,
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'wparmchair_top_nav_bgcolor', array(
		'label'        => __( 'Background Color', 'wparmchair' ),
		'section'    => 'wparmchair_top_nav',
		'settings'   => 'top_nav_bgcolor',
	) ) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'wparmchair_top_nav_active_textcolor', array(
		'label'        => __( 'Active Text Color', 'wparmchair' ),
		'section'    => 'wparmchair_top_nav',
		'settings'   => 'top_nav_active_textcolor',
	) ) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'wparmchair_top_nav_textcolor', array(
		'label'        => __( 'Text Color', 'wparmchair' ),
		'section'    => 'wparmchair_top_nav',
		'settings'   => 'top_nav_textcolor',
	) ) );
	
	
	
	$wp_customize->add_setting( 'top_header_bgcolor' , array(
	    'default'     => '#000000',
	    'transport'   => 'refresh',
	) );	
	$wp_customize->add_setting( 'top_header_text' , array(
	    'default'     => '#FFFFFF',
	    'transport'   => 'refresh',
	) );
	$wp_customize->add_setting( 'top_header_button_background_color' , array(
	    'default'     => '#FFFFFF',
	    'transport'   => 'refresh',
	) );
	$wp_customize->add_setting( 'top_header_button_text_color' , array(
	    'default'     => '#000000',
	    'transport'   => 'refresh',
	) );
	$wp_customize->add_setting( 'top_header_button_border_color' , array(
	    'default'     => '#CCCCCC',
	    'transport'   => 'refresh',
	) );
	
	$wp_customize->add_section( 'wparmchair_top_header' , array(
	    'title'      => __( 'Top Header', 'wparmchair' ),
	    'priority'   => 35,
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'wparmchair_top_header_bgcolor', array(
		'label'        => __( 'Background Color', 'wparmchair' ),
		'section'    => 'wparmchair_top_header',
		'settings'   => 'top_header_bgcolor',
	) ) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'wparmchair_top_header_text', array(
		'label'        => __( 'Header Text', 'wparmchair' ),
		'section'    => 'wparmchair_top_header',
		'settings'   => 'top_header_text',
	) ) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'wparmchair_top_header_button_background_color', array(
		'label'        => __( 'Button Background Color', 'wparmchair' ),
		'section'    => 'wparmchair_top_header',
		'settings'   => 'top_header_button_background_color',
	) ) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'wparmchair_top_header_button_text_color', array(
		'label'        => __( 'Button Text Color', 'wparmchair' ),
		'section'    => 'wparmchair_top_header',
		'settings'   => 'top_header_button_text_color',
	) ) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'wparmchair_top_header_button_border_color', array(
		'label'        => __( 'Button Border Color', 'wparmchair' ),
		'section'    => 'wparmchair_top_header',
		'settings'   => 'top_header_button_border_color',
	) ) );
	
	
	
	$wp_customize->add_setting( 'bg_color' , array(
	    'default'     => '#585858',
	    'transport'   => 'refresh',
	) );
	
	$wp_customize->add_section( 'wparmchair_background' , array(
	    'title'      => __( 'Background', 'wparmchair' ),
	    'priority'   => 20,
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'wparmchair_bg_color', array(
		'label'        => __( 'Background Color', 'wparmchair' ),
		'section'    => 'wparmchair_background',
		'settings'   => 'bg_color',
	) ) );	
	
	
	
	$wp_customize->add_setting( 'text_primary_color' , array(
	    'default'     => '#FFFFFF',
	    'transport'   => 'refresh',
	) );
	$wp_customize->add_setting( 'text_status_color' , array(
	    'default'     => '#FFFFFF',
	    'transport'   => 'refresh',
	) );
	$wp_customize->add_setting( 'text_link_color' , array(
	    'default'     => '#CCCCCC',
	    'transport'   => 'refresh',
	) );
	
	$wp_customize->add_section( 'wparmchair_text_links' , array(
	    'title'      => __( 'Text &amp; Links', 'wparmchair' ),
	    'priority'   => 30,
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'wparmchair_text_primary_color', array(
		'label'        => __( 'Primary Text Color', 'wparmchair' ),
		'section'    => 'wparmchair_text_links',
		'settings'   => 'text_primary_color',
	) ) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'wparmchair_text_status_color', array(
		'label'        => __( 'Status Text Color', 'wparmchair' ),
		'section'    => 'wparmchair_text_links',
		'settings'   => 'text_status_color',
	) ) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'wparmchair_text_link_color', array(
		'label'        => __( 'Primary Link Color', 'wparmchair' ),
		'section'    => 'wparmchair_text_links',
		'settings'   => 'text_link_color',
	) ) );
	
	
	
	$wp_customize->add_setting( 'panel_bg' , array(
	    'default'     => '#FFFFFF',
	    'transport'   => 'refresh',
	) );
	$wp_customize->add_setting( 'panel_text_color' , array(
	    'default'     => '#555555',
	    'transport'   => 'refresh',
	) );
	$wp_customize->add_setting( 'panel_dot_color' , array(
	    'default'     => '#FFFFFF',
	    'transport'   => 'refresh',
	) );
	$wp_customize->add_setting( 'panel_metadata_link_color' , array(
	    'default'     => '#0088CC',
	    'transport'   => 'refresh',
	) );
	
	$wp_customize->add_section( 'wparmchair_panels' , array(
	    'title'      => __( 'Panels', 'wparmchair' ),
	    'priority'   => 40,
	) );
	
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'wparmchair_panel_bg', array(
		'label'        => __( 'Panel Background Color', 'wparmchair' ),
		'section'    => 'wparmchair_panels',
		'settings'   => 'panel_bg',
	) ) );	
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'wparmchair_panel_text_color', array(
		'label'        => __( 'Panel Text Color', 'wparmchair' ),
		'section'    => 'wparmchair_panels',
		'settings'   => 'panel_text_color',
	) ) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'wparmchair_panel_dot_color', array(
		'label'        => __( 'Panel Dots Color', 'wparmchair' ),
		'section'    => 'wparmchair_panels',
		'settings'   => 'panel_dot_color',
	) ) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'wparmchair_panel_metadata_link_color', array(
		'label'        => __( 'Metadata Link Color', 'wparmchair' ),
		'section'    => 'wparmchair_panels',
		'settings'   => 'panel_metadata_link_color',
	) ) );
	
	
	
	$wp_customize->add_setting( 'footer_bg' , array(
	    'default'     => '#F5F5F5',
	    'transport'   => 'refresh',
	) );
	$wp_customize->add_setting( 'footer_text_color' , array(
	    'default'     => '#000000',
	    'transport'   => 'refresh',
	) );
	$wp_customize->add_setting( 'footer_link_color' , array(
	    'default'     => '#0088CC',
	    'transport'   => 'refresh',
	) );
	
	$wp_customize->add_section( 'wparmchair_footer' , array(
	    'title'      => __( 'Footer', 'wparmchair' ),
	    'priority'   => 60,
	) );
	
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'wparmchair_footer_bg', array(
		'label'        => __( 'Footer Background Color', 'wparmchair' ),
		'section'    => 'wparmchair_footer',
		'settings'   => 'footer_bg',
	) ) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'wparmchair_panel_footer_text_color', array(
		'label'        => __( 'Footer Text Color', 'wparmchair' ),
		'section'    => 'wparmchair_footer',
		'settings'   => 'footer_text_color',
	) ) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'wparmchair_panel_footer_link_color', array(
		'label'        => __( 'Footer Link Color', 'wparmchair' ),
		'section'    => 'wparmchair_footer',
		'settings'   => 'footer_link_color',
	) ) );
	
	
	
	$wp_customize->add_setting( 'misc_hashtag' , array(
	    'default'     => '',
	    'transport'   => 'refresh',
	) );
	$wp_customize->add_setting( 'misc_twitter_username' , array(
	    'default'     => '',
	    'transport'   => 'refresh',
	) );


	$wp_customize->add_section( 'wparmchair_misc' , array(
	    'title'      => __( 'Misc (Hashtags/Social)', 'wparmchair' ),
	    'priority'   => 60,
	) );
	
	
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'wparmchair_misc_hashtag', array(
		'label'        => __( 'Hashtag (include #)', 'wparmchair' ),
		'section'    => 'wparmchair_misc',
		'settings'   => 'misc_hashtag',
	) ) );
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'wparmchair_misc_twitter_username', array(
		'label'        => __( 'Official Twitter Username', 'wparmchair' ),
		'section'    => 'wparmchair_misc',
		'settings'   => 'misc_twitter_username',
	) ) );


	$wp_customize->add_setting( 'timeline_active' , array(
	    'default'     => 'off',
	    'transport'   => 'refresh',
	) );

	$wp_customize->add_setting( 'timeline_active_start_date' , array(
	    'default'     => '',
	    'transport'   => 'refresh',
	) );

	$wp_customize->add_setting( 'timeline_active_end_date' , array(
	    'default'     => '',
	    'transport'   => 'refresh',
	) );

	$wp_customize->add_section( 'wparm_timeline' , array(
	    'title'      => __( 'Timeline', 'wparmchair' ),
	    'priority'   => 80,
	) );
	
	
	$wp_customize->add_control($wp_customize->add_control( 'wparmchair_timeline_active', array(
    'label'      => __( 'Show Timeline On Homepage (See README file)', 'wparmchair' ),
    'section'    => 'wparm_timeline',
    'settings'   => 'timeline_active',
    'type'       => 'radio',
    'choices'    => array(
        'off' => 'Off',
        'on' => 'On',
        ),
    ) ) );
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'wparmchair_timeline_active_start_date', array(
		'label'        => __( 'Start Date of Time (MUST BE IN YYYY-MM-DD)', 'wparmchair' ),
		'section'    => 'wparm_timeline',
		'settings'   => 'timeline_active_start_date',
	) ) );
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'wparmchair_timeline_active_end_date', array(
		'label'        => __( 'End Date of Time (MUST BE IN YYYY-MM-DD)', 'wparmchair' ),
		'section'    => 'wparm_timeline',
		'settings'   => 'timeline_active_end_date',
	) ) );	
	
		
}
add_action( 'customize_register', 'wparmchair_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function wparmchair_customize_preview_js() {
	wp_enqueue_script( 'wparmchair_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130304', true );
}
add_action( 'customize_preview_init', 'wparmchair_customize_preview_js' );



























