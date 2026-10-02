<?php
/**
 * Recommended Plugins
 *
 * Uses core's own plugin-card markup (the same structure wp-admin renders on
 * Plugins > Add New) so the layout, grid, buttons and responsive behaviour all
 * come from core's plugin-install stylesheet. The theme adds no CSS of its own
 * for this tab.
 */

global $activello_recommended_plugins;

wp_enqueue_style( 'plugin-install' );
wp_enqueue_script( 'plugin-install' );
wp_enqueue_script( 'updates' );
add_thickbox();

$activello_welcome = Activello_Welcome::get_instance();

?>

<div class="wp-list-table widefat plugin-install">
	<h2 class="screen-reader-text"><?php esc_html_e( 'Recommended plugins list', 'activello' ); ?></h2>

	<div id="the-list">
		<?php
		foreach ( $activello_recommended_plugins as $activello_plugin => $activello_prop ) {

			$activello_info = $activello_welcome->call_plugin_api( $activello_plugin );

			/*
			 * plugins_api() returns WP_Error when wordpress.org cannot be reached.
			 * Previously the properties below were read straight off it, so an
			 * offline site rendered a broken tab.
			 */
			if ( is_wp_error( $activello_info ) || empty( $activello_info->name ) ) {
				continue;
			}

			$activello_icon   = $activello_welcome->check_for_icon( isset( $activello_info->icons ) && is_array( $activello_info->icons ) ? $activello_info->icons : array() );
			$activello_active = $activello_welcome->check_active( $activello_plugin );
			$activello_url    = $activello_welcome->create_action_link( $activello_active['needs'], $activello_plugin );

			$activello_is_active = ( 'install' !== $activello_active['needs'] && $activello_active['status'] );

			switch ( $activello_active['needs'] ) {
				case 'install':
					$activello_class = 'install-now button';
					$activello_label = __( 'Install Now', 'activello' );
					break;
				case 'activate':
					$activello_class = 'activate-now button button-primary';
					$activello_label = __( 'Activate', 'activello' );
					break;
				default:
					$activello_class = 'button';
					$activello_label = __( 'Deactivate', 'activello' );
					break;
			}

			$activello_details_url = add_query_arg(
				array(
					'tab'       => 'plugin-information',
					'plugin'    => $activello_plugin,
					'TB_iframe' => 'true',
					'width'     => 600,
					'height'    => 550,
				),
				self_admin_url( 'plugin-install.php' )
			);
			?>
			<div class="plugin-card plugin-card-<?php echo esc_attr( sanitize_html_class( $activello_plugin ) ); ?>">
				<div class="plugin-card-top">
					<div class="name column-name">
						<h3>
							<a href="<?php echo esc_url( $activello_details_url ); ?>" class="thickbox open-plugin-details-modal">
								<?php echo esc_html( $activello_info->name ); ?>
								<img src="<?php echo esc_url( $activello_icon ); ?>" class="plugin-icon" alt="" />
							</a>
						</h3>
					</div>

					<div class="action-links">
						<ul class="plugin-action-buttons">
							<li>
								<?php if ( $activello_is_active ) : ?>
									<button type="button" class="button button-disabled" disabled="disabled"><?php esc_html_e( 'Active', 'activello' ); ?></button>
								<?php else : ?>
									<a data-slug="<?php echo esc_attr( $activello_plugin ); ?>"
										class="<?php echo esc_attr( $activello_class ); ?>"
										href="<?php echo esc_url( $activello_url ); ?>"><?php echo esc_html( $activello_label ); ?></a>
								<?php endif; ?>
							</li>
							<li>
								<a href="<?php echo esc_url( $activello_details_url ); ?>" class="thickbox open-plugin-details-modal">
									<?php esc_html_e( 'More Details', 'activello' ); ?>
								</a>
							</li>
						</ul>
					</div>

					<div class="desc column-description">
						<p><?php echo isset( $activello_info->short_description ) ? wp_kses_post( $activello_info->short_description ) : ''; ?></p>
						<p class="authors"><cite>
						<?php
							/* translators: %s: plugin author name */
							printf( esc_html__( 'By %s', 'activello' ), isset( $activello_info->author ) ? wp_kses_post( $activello_info->author ) : '' );
						?>
						</cite></p>
					</div>
				</div>

				<div class="plugin-card-bottom">
					<div class="column-updated">
						<strong><?php esc_html_e( 'Version:', 'activello' ); ?></strong>
						<?php echo isset( $activello_info->version ) ? esc_html( $activello_info->version ) : ''; ?>
					</div>
					<div class="column-compatibility">
						<?php if ( $activello_is_active ) : ?>
							<span class="compatibility-compatible"><?php esc_html_e( 'Installed and active', 'activello' ); ?></span>
						<?php elseif ( 'activate' === $activello_active['needs'] ) : ?>
							<span class="compatibility-untested"><?php esc_html_e( 'Installed, not active', 'activello' ); ?></span>
						<?php else : ?>
							<span class="compatibility-untested"><?php esc_html_e( 'Not installed', 'activello' ); ?></span>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<?php
		}
		?>
	</div>
</div>
