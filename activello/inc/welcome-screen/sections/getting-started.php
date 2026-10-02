<?php
/**
 * Getting started template
 *
 * @package activello
 */

?>

<div class="feature-section three-col has-3-columns is-fullwidth">
	<div class="col column">
		<h3><?php esc_html_e( 'Step 1 - Add the plugins you need', 'activello' ); ?></h3>
		<p><?php esc_html_e( 'Activello works on its own. These free plugins add a contact form, image galleries, a lightbox and more.', 'activello' ); ?></p>
		<p><a href="<?php echo esc_url( admin_url( 'themes.php?page=activello-welcome&tab=recommended_plugins' ) ); ?>"><?php esc_html_e( 'See recommended plugins', 'activello' ); ?></a></p>
	</div><!--/.col-->

	<div class="col column">
		<h3><?php esc_html_e( 'Step 2 - Check our documentation', 'activello' ); ?></h3>
		<p><?php esc_html_e( 'Even if you\'re a long-time WordPress user, we still believe you should give our documentation a very quick Read.', 'activello' ); ?></p>
		<p>
			<a target="_blank" href="<?php echo esc_url( 'https://colorlib.com/wp/support/activello/' ); ?>"><?php esc_html_e( 'Full documentation', 'activello' ); ?></a>
		</p>
	</div><!--/.col-->

	<div class="col column">
		<h3><?php esc_html_e( 'Step 3 - Customize everything', 'activello' ); ?></h3>
		<p><?php esc_html_e( 'Using the WordPress Customizer you can easily customize every aspect of the theme.', 'activello' ); ?></p>
		<p><a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" class="button button-primary"><?php esc_html_e( 'Go to Customizer', 'activello' ); ?></a></p>
	</div><!--/.col-->
</div><!--/.feature-section-->
