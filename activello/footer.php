<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package activello
 */

/*
 * sidebar.php closes .main-content-inner before it prints the sidebar, so
 * only close it here on templates that never called get_sidebar(). Closing
 * it unconditionally left one </div> too many on every page with a
 * sidebar, which closed #page early and put the footer outside it.
 */
if ( ! did_action( 'get_sidebar' ) ) :
	?>
				</div><!-- close .main-content-inner -->
<?php endif; ?>
			</div><!-- close .row -->
		</div><!-- close .container -->
	</div><!-- close .site-content -->

	<div id="footer-area">
		<footer id="colophon" class="site-footer" role="contentinfo">
			<div class="site-info container">
				<div class="row">
					<?php
					if ( ! get_theme_mod( 'footer_social' ) ) {
						activello_social_icons();}
					?>
					<div class="copyright col-md-12">
						<?php echo wp_kses_post( get_theme_mod( 'activello_footer_copyright', 'Activello' ) ); ?>
						<?php activello_footer_info(); ?>
					</div>
				</div>
			</div><!-- .site-info -->
			<button type="button" class="scroll-to-top"><i class="fa-solid fa-angle-up" aria-hidden="true"></i><span class="screen-reader-text"><?php esc_html_e( 'Back to top', 'activello' ); ?></span></button><!-- .scroll-to-top -->
		</footer><!-- #colophon -->
	</div>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
