<?php
/**
 * Title: Call to action
 * Slug: activello/call-to-action
 * Categories: activello, call-to-action
 * Keywords: call to action, newsletter, subscribe, button
 * Viewport Width: 1100
 *
 * @package activello
 */

?>
<!-- wp:group {"backgroundColor":"accent","textColor":"white","style":{"spacing":{"margin":{"top":"48px","bottom":"48px"},"padding":{"top":"48px","bottom":"48px","left":"24px","right":"24px"}}},"layout":{"type":"constrained","contentSize":"640px"}} -->
<div class="wp-block-group has-white-color has-accent-background-color has-text-color has-background" style="margin-top:48px;margin-bottom:48px;padding-top:48px;padding-right:24px;padding-bottom:48px;padding-left:24px"><!-- wp:heading {"textAlign":"center","level":2,"textColor":"white"} -->
<h2 class="wp-block-heading has-text-align-center has-white-color has-text-color"><?php esc_html_e( 'Never miss a new post', 'activello' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center"><?php esc_html_e( 'One email when something new is published. No spam, and you can unsubscribe at any time.', 'activello' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"white","textColor":"dark-grey"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-dark-grey-color has-white-background-color has-text-color has-background wp-element-button"><?php esc_html_e( 'Subscribe', 'activello' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->
