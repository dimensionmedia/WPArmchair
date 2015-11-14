<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.ico" />
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->

<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>
   
    <div id="wrap">

		<header role="banner" class="site-header" id="masthead">
		    
				<nav role="navigation" class="main-navigation" id="site-navigation">
					<div class="menu-main-menu-container">
						<ul class="nav-menu" id="menu-main-menu">
							<li class="menu-item<?php if( is_home() ) echo ' current_page_item'; ?>"><a href="<?php echo esc_url( home_url(); ?>">Live</a>
							<li class="menu-item"><a href="<?php echo esc_url( home_url('how-to-submit-photos') ); ?>">Upload!</a></li>
							<li class="menu-item logo"><a href="<?php echo esc_url( home_url() ); ?>"><img src="<?php echo get_template_directory_uri(); ?>/img/wordcamp_2013_logo-small.png" alt="<?php bloginfo( 'name' ); ?>" width="40" height="40" /></a></li>
							<?php if ( !is_user_logged_in() ) : ?>
								<li class="menu-item"><a href="<?php echo esc_url( home_url( 'register' ) ); ?>">Register</a> / <a href="<?php echo wp_login_url( home_url('upload-photo') ); ?>">Log In</a></li>
							<?php else: $current_user = wp_get_current_user(); ?>
								<li class="menu-item"><a href="<?php echo esc_url( home_url('submit-photo') ); ?>">Upload Photo</a></li>
								<li class="menu-item"><a href="<?php echo esc_url( wp_logout_url() ); ?>">Log Out</a></li>
							<?php endif; ?>
						</ul>
					</div>		
				</nav><!-- #site-navigation -->
	
		</header>
