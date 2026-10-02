<?php
/**
 * The template for displaying image attachments.
 *
 * @package activello
 */

get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<div class="post-inner-content">
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<header class="entry-header">
							<h1 class="entry-title"><?php the_title(); ?></h1>

							<div class="entry-meta">
								<?php activello_posted_on(); ?>
							</div><!-- .entry-meta -->

							<nav id="image-navigation" class="navigation-image nav-links" aria-label="<?php esc_attr_e( 'Image navigation', 'activello' ); ?>">
								<div class="nav-previous"><?php previous_image_link( false, __( '<i class="fa-solid fa-chevron-left"></i> Previous', 'activello' ) ); ?></div>
								<div class="nav-next"><?php next_image_link( false, __( 'Next <i class="fa-solid fa-chevron-right"></i>', 'activello' ) ); ?></div>
							</nav><!-- #image-navigation -->
						</header><!-- .entry-header -->

						<div class="entry-content">

							<div class="entry-attachment">
								<div class="attachment">
									<?php
										/**
										 * Grab the IDs of all the image attachments in a gallery so we can get the URL of the next adjacent image in a gallery,
										 * or the first image (if we're looking at the last image in a gallery), or, in a gallery of one, just the link to that image file
										 */
										$activello_attachments = array_values(
											get_children(
												array(
													'post_parent' => $post->post_parent,
													'post_status' => 'inherit',
													'post_type' => 'attachment',
													'post_mime_type' => 'image',
													'order'   => 'ASC',
													'orderby' => 'menu_order ID',
												)
											)
										);
									foreach ( $activello_attachments as $activello_k => $activello_attachment ) {
										if ( (int) $activello_attachment->ID === (int) $post->ID ) {
											break;
										}
									}
										++$activello_k;
										// If there is more than 1 attachment in a gallery
									if ( count( $activello_attachments ) > 1 ) {
										if ( isset( $activello_attachments[ $activello_k ] ) ) {
											// get the URL of the next image attachment
											$activello_next_attachment_url = get_attachment_link( $activello_attachments[ $activello_k ]->ID );
										} else {
											$activello_next_attachment_url = get_attachment_link( $activello_attachments[0]->ID );
										}
									} else {
										// or, if there's only 1 image, get the URL of the image
										$activello_next_attachment_url = wp_get_attachment_url();
									}
									?>

									<a href="<?php echo esc_url( $activello_next_attachment_url ); ?>" title="<?php the_title_attribute(); ?>" rel="attachment">
									<?php
										$activello_attachment_size = apply_filters( 'activello_attachment_size', array( 1200, 1200 ) ); // Filterable image size.
										echo wp_get_attachment_image( $post->ID, $activello_attachment_size );
									?>
									</a>
								</div><!-- .attachment -->

								<?php if ( ! empty( $post->post_excerpt ) ) : ?>
								<div class="entry-caption">
									<?php the_excerpt(); ?>
								</div><!-- .entry-caption -->
								<?php endif; ?>
							</div><!-- .entry-attachment -->

							<?php the_content(); ?>
							<?php
								wp_link_pages(
									array(
										'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'activello' ),
										'after'  => '</div>',
									)
								);
							?>

						</div><!-- .entry-content -->

						<footer class="entry-meta">
						</footer><!-- .entry-meta -->
					</article><!-- #post-<?php the_ID(); ?> -->
				</div>
				<?php
				/*
				 * Like a single post. This was gated on "Display Comments on
				 * Static Pages", read without its default, so attachment
				 * comments were hidden unless that page option had been saved.
				 */
				if ( comments_open() || 0 < (int) get_comments_number() ) :
					comments_template();
				endif;
				?>

			<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
