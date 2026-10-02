<?php
/**
 * About Activello screen (Appearance > About Activello).
 *
 * @package activello
 */

/**
 * Welcome Screen Class
 */
class Activello_Welcome {

	/**
	 * The single instance, so the hooks are only registered once.
	 *
	 * @var Activello_Welcome|null
	 */
	private static $instance = null;

	/**
	 * The active parent theme.
	 *
	 * @var WP_Theme
	 */
	public $activello;

	/**
	 * Return the instance, creating it (and registering the hooks) once.
	 *
	 * @return Activello_Welcome
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor for the welcome screen
	 */
	public function __construct() {
		$this->activello = wp_get_theme( get_template() );

		if ( null !== self::$instance ) {
			return;
		}
		self::$instance = $this;

		add_action( 'admin_menu', array( $this, 'activello_welcome_register_menu' ) );
		add_action( 'load-themes.php', array( $this, 'activello_activation_admin_notice' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'activello_welcome_style_and_scripts' ) );
	}

	/**
	 * Creates the dashboard page
	 *
	 * @see add_theme_page()
	 */
	public function activello_welcome_register_menu() {
		add_theme_page(
			esc_html__( 'About Activello', 'activello' ),
			esc_html__( 'About Activello', 'activello' ),
			'edit_theme_options',
			'activello-welcome',
			array( $this, 'activello_welcome_screen' )
		);
	}

	/**
	 * Adds an admin notice upon successful activation.
	 */
	public function activello_activation_admin_notice() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- core's own redirect flag after switching themes; nothing is changed.
		if ( isset( $_GET['activated'] ) && current_user_can( 'edit_theme_options' ) ) {
			add_action( 'admin_notices', array( $this, 'activello_welcome_admin_notice' ), 99 );
		}
	}

	/**
	 * Display an admin notice linking to the welcome screen
	 */
	public function activello_welcome_admin_notice() {
		?>
		<div class="updated notice is-dismissible">
			<p>
				<?php
				printf(
					/* translators: 1: opening link tag to the welcome page, 2: closing link tag */
					esc_html__( 'Welcome! Thank you for choosing Activello! To fully take advantage of the best our theme can offer please make sure you visit our %1$swelcome page%2$s.', 'activello' ),
					'<a href="' . esc_url( admin_url( 'themes.php?page=activello-welcome' ) ) . '">',
					'</a>'
				);
				?>
			</p>
			<p><a href="<?php echo esc_url( admin_url( 'themes.php?page=activello-welcome' ) ); ?>" class="button" style="text-decoration: none;"><?php esc_html_e( 'Get started with Activello', 'activello' ); ?></a></p>
		</div>
		<?php
	}

	/**
	 * Load the welcome screen's stylesheet, on that screen only.
	 *
	 * @param string $hook_suffix The current admin page.
	 */
	public function activello_welcome_style_and_scripts( $hook_suffix ) {
		if ( 'appearance_page_activello-welcome' !== $hook_suffix ) {
			return;
		}

		wp_enqueue_style( 'activello-welcome-screen-css', get_template_directory_uri() . '/inc/welcome-screen/css/welcome.css', array(), ACTIVELLO_VERSION );
	}

	/**
	 * Plugin information from wordpress.org, cached for 30 minutes.
	 *
	 * @param string $slug Plugin slug.
	 * @return object|WP_Error
	 */
	public function call_plugin_api( $slug ) {
		include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
		$call_api = get_transient( 'activello_plugin_information_transient_' . $slug );
		if ( false === $call_api ) {
			$call_api = plugins_api(
				'plugin_information',
				array(
					'slug'   => $slug,
					'fields' => array(
						'downloaded'        => false,
						'rating'            => false,
						'description'       => false,
						'short_description' => true,
						'donate_link'       => false,
						'tags'              => false,
						'sections'          => true,
						'homepage'          => true,
						'added'             => false,
						'last_updated'      => false,
						'compatibility'     => false,
						'tested'            => false,
						'requires'          => false,
						'downloadlink'      => false,
						'icons'             => true,
					),
				)
			);

			if ( ! is_wp_error( $call_api ) ) {
				set_transient( 'activello_plugin_information_transient_' . $slug, $call_api, 30 * MINUTE_IN_SECONDS );
			}
		}

		return $call_api;
	}

	/**
	 * The installed plugin file (folder/main-file.php) for a slug, or ''.
	 *
	 * The main file is looked up rather than assumed to be {slug}/{slug}.php:
	 * several recommended plugins use another name (fancybox-for-wordpress
	 * ships fancybox.php), and those were offered for installation while
	 * already installed, which then failed with "Destination folder already
	 * exists". get_plugins() also honours a relocated plugins directory.
	 *
	 * @param string $slug Plugin slug.
	 * @return string
	 */
	public function get_plugin_file( $slug ) {
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$plugins = get_plugins( '/' . $slug );

		if ( empty( $plugins ) ) {
			return '';
		}

		$files = array_keys( $plugins );
		$file  = in_array( $slug . '.php', $files, true ) ? $slug . '.php' : $files[0];

		return $slug . '/' . $file;
	}

	/**
	 * Whether a plugin is installed and active, and what it needs next.
	 *
	 * @param string $slug Plugin slug.
	 * @return array{status:bool,needs:string}
	 */
	public function check_active( $slug ) {
		$file = $this->get_plugin_file( $slug );

		if ( '' === $file ) {
			return array(
				'status' => false,
				'needs'  => 'install',
			);
		}

		$active = is_plugin_active( $file );

		return array(
			'status' => $active,
			'needs'  => $active ? 'deactivate' : 'activate',
		);
	}

	/**
	 * Best available icon URL from a plugins_api() icons array.
	 *
	 * @param array $arr Icons keyed by size.
	 * @return string
	 */
	public function check_for_icon( $arr ) {
		if ( ! $arr || ! is_array( $arr ) ) {
			return '';
		}

		if ( ! empty( $arr['svg'] ) ) {
			$plugin_icon_url = $arr['svg'];
		} elseif ( ! empty( $arr['2x'] ) ) {
			$plugin_icon_url = $arr['2x'];
		} elseif ( ! empty( $arr['1x'] ) ) {
			$plugin_icon_url = $arr['1x'];
		} else {
			$plugin_icon_url = isset( $arr['default'] ) ? $arr['default'] : '';
		}

		return $plugin_icon_url;
	}

	/**
	 * Core install/activate/deactivate URL, with core's own nonce.
	 *
	 * @param string $state install, activate or deactivate.
	 * @param string $slug  Plugin slug.
	 * @return string
	 */
	public function create_action_link( $state, $slug ) {
		if ( 'install' === $state ) {
			return wp_nonce_url(
				add_query_arg(
					array(
						'action' => 'install-plugin',
						'plugin' => $slug,
					),
					network_admin_url( 'update.php' )
				),
				'install-plugin_' . $slug
			);
		}

		if ( ! in_array( $state, array( 'activate', 'deactivate' ), true ) ) {
			return '';
		}

		$file = $this->get_plugin_file( $slug );

		return add_query_arg(
			array(
				'action'        => $state,
				'plugin'        => rawurlencode( $file ),
				'plugin_status' => 'all',
				'paged'         => '1',
				'_wpnonce'      => wp_create_nonce( $state . '-plugin_' . $file ),
			),
			network_admin_url( 'plugins.php' )
		);
	}

	/**
	 * Welcome screen content
	 */
	public function activello_welcome_screen() {
		if ( ! current_user_can( 'edit_theme_options' ) ) {
			wp_die( esc_html__( 'You are not allowed to access this page.', 'activello' ) );
		}

		$tabs = array(
			'getting_started'     => __( 'Getting Started', 'activello' ),
			'recommended_plugins' => __( 'Recommended Plugins', 'activello' ),
			'support'             => __( 'Support', 'activello' ),
		);

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only tab switch, checked against an allowlist.
		$active_tab = isset( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : 'getting_started';

		if ( ! isset( $tabs[ $active_tab ] ) ) {
			$active_tab = 'getting_started';
		}

		?>

		<div class="wrap about-wrap activello-welcome">

			<h1>
				<?php
				/* translators: %s: theme version */
				printf( esc_html__( 'Welcome to Activello! - Version %s', 'activello' ), esc_html( $this->activello->get( 'Version' ) ) );
				?>
			</h1>

			<div class="about-text"><?php esc_html_e( 'Activello is now installed and ready to use! Get ready to build something beautiful. We hope you enjoy it! We want to make sure you have the best experience using Activello and that is why we gathered here all the necessary information for you. We hope you will enjoy using Activello, as much as we enjoy creating great products.', 'activello' ); ?></div>

			<div class="wp-badge activello-welcome-logo"></div>

			<nav class="nav-tab-wrapper wp-clearfix" aria-label="<?php esc_attr_e( 'About Activello', 'activello' ); ?>">
				<?php foreach ( $tabs as $tab => $label ) : ?>
					<a href="<?php echo esc_url( admin_url( 'themes.php?page=activello-welcome&tab=' . $tab ) ); ?>" class="nav-tab<?php echo $tab === $active_tab ? ' nav-tab-active' : ''; ?>"<?php echo $tab === $active_tab ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $label ); ?></a>
				<?php endforeach; ?>
			</nav>

			<?php
			/*
			 * The plugin tab renders core's plugin-card markup, which core styles
			 * for a plain .wrap page. about.css restyles p, h3 and img for
			 * everything inside .about-wrap and loads after list-tables.css, so
			 * nesting the cards here inflates their type and blows the card
			 * heights out. Close .about-wrap after the tab nav and let that tab
			 * render in the context core designed the component for.
			 */
			if ( 'recommended_plugins' === $active_tab ) {
				echo '</div><div class="wrap activello-welcome-plugins">';
			}

			get_template_part( 'inc/welcome-screen/sections/' . str_replace( '_', '-', $active_tab ) );
			?>

		</div><!--/.wrap.about-wrap-->

		<?php
	}
}
