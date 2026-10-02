<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package activello
 */
?>
</div>

	<?php if ( activello_show_sidebar() ) : ?>
		<div id="secondary" class="widget-area col-sm-12 col-md-4" role="complementary">
			<div class="inner">
				<?php do_action( 'before_sidebar' ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Underscores' hook, kept for child themes. ?>
				<?php if ( ! dynamic_sidebar( 'sidebar-1' ) ) : ?>
					<aside id="search" class="widget widget_search">
						<?php get_search_form(); ?>
					</aside>

					<aside id="archives" class="widget">
						<h3 class="widget-title"><?php esc_html_e( 'Archives', 'activello' ); ?></h3>
						<ul>
							<?php
								$activello_archive_args = array(
									'type' => 'monthly',
								);
								wp_get_archives( $activello_archive_args );
								?>
						</ul>
					</aside>

					<aside id="meta" class="widget">
						<h3 class="widget-title"><?php esc_html_e( 'Meta', 'activello' ); ?></h3>
						<ul>
							<?php wp_register(); ?>
							<li><?php wp_loginout(); ?></li>
							<?php wp_meta(); ?>
						</ul>
					</aside>

				<?php endif; // end sidebar widget area ?>
			</div>
		</div><!-- #secondary -->
	<?php endif; ?>
