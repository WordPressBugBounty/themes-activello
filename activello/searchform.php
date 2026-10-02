<?php
/**
 * The template for displaying search forms in activello
 *
 * @package activello
 */

$activello_search_id = wp_unique_id( 'activello-search-' );
?>

<form role="search" method="get" class="form-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<div class="input-group">
		<label class="screen-reader-text" for="<?php echo esc_attr( $activello_search_id ); ?>"><?php esc_html_e( 'Search for:', 'activello' ); ?></label>
		<input type="text" id="<?php echo esc_attr( $activello_search_id ); ?>" class="form-control search-query" placeholder="<?php echo esc_attr_x( 'Search&hellip;', 'placeholder', 'activello' ); ?>" value="<?php echo esc_attr( get_search_query( false ) ); ?>" name="s" />
		<span class="input-group-btn">
			<button type="submit" class="btn btn-default"><?php echo esc_html_x( 'Search', 'submit button', 'activello' ); ?></button>
		</span>
	</div>
</form>
