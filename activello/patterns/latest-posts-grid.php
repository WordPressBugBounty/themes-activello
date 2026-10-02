<?php
/**
 * Title: Latest posts grid
 * Slug: activello/latest-posts-grid
 * Categories: activello, query
 * Keywords: posts, latest, grid, query, blog
 * Block Types: core/query
 * Viewport Width: 1100
 *
 * @package activello
 */

?>
<!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'Latest from the blog', 'activello' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:query {"queryId":1,"query":{"perPage":3,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"exclude","inherit":false}} -->
<div class="wp-block-query"><!-- wp:post-template {"layout":{"type":"grid","columnCount":3}} -->
<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"3/2"} /-->

<!-- wp:post-terms {"term":"category","textAlign":"center","style":{"typography":{"textTransform":"uppercase","fontSize":"11px","letterSpacing":"1px"}}} /-->

<!-- wp:post-title {"textAlign":"center","level":3,"isLink":true,"style":{"typography":{"fontSize":"20px","fontStyle":"italic","fontWeight":"400"}}} /-->

<!-- wp:post-date {"textAlign":"center","style":{"typography":{"fontSize":"12px"}}} /-->
<!-- /wp:post-template --></div>
<!-- /wp:query --></div>
<!-- /wp:group -->
