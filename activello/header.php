<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package activello
 */
?><!doctype html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="https://gmpg.org/xfn/11">
<?php if ( is_singular() && pings_open() ) : ?>
<link rel="pingback" href="<?php echo esc_url( get_bloginfo( 'pingback_url' ) ); ?>">
<?php endif; ?>

<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'activello' ); ?></a>

	<header id="masthead" class="site-header" role="banner">
		<nav class="navbar navbar-default" aria-label="<?php esc_attr_e( 'Primary', 'activello' ); ?>">
			<div class="container">
				<div class="row">
					<div class="site-navigation-inner col-sm-12">
						<div class="navbar-header">
							<button type="button" class="btn navbar-toggle" data-toggle="collapse" data-target="#activello-primary-menu" aria-controls="activello-primary-menu" aria-expanded="false">
								<span class="sr-only"><?php esc_html_e( 'Toggle navigation', 'activello' ); ?></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
						</div>
						<?php activello_header_menu(); // main navigation ?>

						<div class="nav-search">
						<?php
							add_filter( 'get_search_form', 'activello_header_search_filter', 10, 3 );
							get_search_form();
							remove_filter( 'get_search_form', 'activello_header_search_filter' );
						?>
						</div>
					</div>
				</div>
			</div>
		</nav><!-- .site-navigation -->

		<?php
		$activello_show_tagline = ! in_array( get_theme_mod( 'header_show', 'logo-text' ), array( 'logo-only', 'title-only' ), true );
		?>

		<div class="container">
			<div id="logo">
				<?php echo is_home() ? '<h1 class="site-name">' : '<span class="site-name">'; ?>
					<?php activello_site_name(); ?>
				<?php echo is_home() ? '</h1>' : '</span>'; ?><!-- end of .site-name -->

				<?php if ( $activello_show_tagline && '' !== get_bloginfo( 'description' ) ) : ?>
					<div class="tagline"><?php bloginfo( 'description' ); ?></div>
				<?php endif; ?>
			</div><!-- end of #logo -->

			<?php if ( ! is_front_page() || ! is_home() ) : ?>
			<div id="line"></div>
			<?php endif; ?>
		</div>

	</header><!-- #masthead -->


	<div id="content" class="site-content">

		<div class="top-section">
			<?php activello_featured_slider(); ?>
		</div>

		<div class="container main-content-area">

			<?php if ( is_single() && has_category() ) : ?>
			<div class="cat-title">
				<?php echo get_the_category_list(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- core category list markup. ?>
			</div>
			<?php endif; ?>

			<div class="row">
				<div class="main-content-inner <?php echo esc_attr( activello_main_content_bootstrap_classes() ); ?> <?php echo esc_attr( activello_get_layout() ); ?>">
