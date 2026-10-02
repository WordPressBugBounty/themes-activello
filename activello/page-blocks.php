<?php
/**
 * Template Name: Blocks (full width, no title)
 * Template Post Type: page
 *
 * A canvas for pages built from blocks and the theme's patterns: the full
 * content width, no page title, no featured image and no comments.
 *
 * @package activello
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'activello-blocks-page' ); ?>>
					<h1 class="screen-reader-text"><?php the_title(); ?></h1>
					<div class="entry-content">
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
				</article>
			<?php endwhile; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>
