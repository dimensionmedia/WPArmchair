<?php
/**
 * The template for displaying the footer.
 *
 * Contains Social Media, Copyright, and/or Credits
 *
 * @package WPArmchair
 */
?>

</div>

	<div class="clearfix"></div>

    <div id="footer">
      <div class="container">
      	<?php if ( get_theme_mod('misc_twitter_username') ) { ?>
        <p>Follow <a href="http://twitter.com/<?php echo get_theme_mod('misc_twitter_username') ; ?>">@<?php echo get_theme_mod('misc_twitter_username') ; ?></a> on Twitter.</p>
        <?php } ?>
        <p>Made With <a href="http://wordpress.org">WordPress</a>.</p>
      </div>
    </div>

<?php wp_footer(); ?>

</body>
</html>