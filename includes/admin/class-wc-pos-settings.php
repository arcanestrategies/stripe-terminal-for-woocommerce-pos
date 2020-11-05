<?php

/**
 * WP Settings Class
 *
 * @class	WC_POS_Admin_Settings
 * @package	WooCommerce POS
 * @author	Paul Kilmurray <paul@kilbot.com.au>
 * @link	http://www.woopos.com.au
 */

class WC_POS_Admin_Settings {

	/* @var string The db prefix for WP Options table */
	const DB_PREFIX = 'woocommerce_pos_settings_';

	/* @var string The settings screen id */
	static public $screen_id;
	static private $initialized = false;

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'current_screen', array( $this, 'conditional_init' ) );
	}

	/**
	 * Add Settings page to admin menu
	 */
	public function admin_menu() {
		remove_submenu_page( WC_POS_PLUGIN_NAME, WC_POS_PLUGIN_NAME );
		self::$screen_id = add_submenu_page(
			WC_POS_PLUGIN_NAME,
			/* translators: wordpress */
			__( 'Settings' ),
			/* translators: wordpress */
			__( 'Settings' ),
			'manage_woocommerce_pos',
			'wc_pos_settings',
			array( $this, 'display_settings_page' ),
			0
		);
	}

	/**
	 *
	 *
	 * @param $current_screen
	 */
	public function conditional_init( $current_screen ) {
		if ( $current_screen->id == self::$screen_id && !WC_POS_Admin_Settings::$initialized ) {

			// Enqueue scripts for the settings page
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ), 99 );
			WC_POS_Admin_Settings::$initialized = true;

		}
	}

	/**
	 * Output the settings pages
	 */
	public function display_settings_page() {
		include 'views/settings.php';
	}

	/**
	 * Delete settings in WP options table
	 *
	 * @param $id
	 * @return bool
	 */
	static function delete_settings( $id ) {
		return delete_option( self::DB_PREFIX . $id );
	}

	/**
	 * Delete all settings in WP options table
	 */
	static function delete_all_settings() {
		global $wpdb;
		$wpdb->query(
			$wpdb->prepare( "
				DELETE FROM {$wpdb->options}
				WHERE option_name
				LIKE '%s'",
				self::DB_PREFIX . '%'
			)
		);
	}

	/**
	 * Settings styles
	 */
	public function enqueue_admin_styles() {
		wp_enqueue_style(
			WC_POS_PLUGIN_NAME . '-admin',
			WC_POS_PLUGIN_URL . 'assets/css/admin.min.css',
			null,
			WC_POS_VERSION
		);
	}

	/**
	 * Settings scripts
	 */
	public function enqueue_admin_scripts() {

		global $wp_scripts;
		$wp_scripts->queue = array();

		// deregister scripts
		wp_deregister_script( 'underscore' );
		wp_deregister_script( 'select2' );
		wp_deregister_script( 'backbone' );

		// register
		$external_libs = WC_POS_Template::get_external_js_libraries();
		wp_register_script( 'wcpos-lodash', $external_libs[ 'lodash' ], array( 'jquery' ), null, true );
		wp_register_script( 'backbone', $external_libs[ 'backbone' ], array( 'jquery', 'wcpos-lodash' ), null, true );
		wp_register_script( 'backbone.radio', $external_libs[ 'radio' ], array( 'jquery', 'backbone', 'wcpos-lodash' ), null, true );
		wp_register_script( 'marionette', $external_libs[ 'marionette' ], array( 'jquery', 'backbone', 'wcpos-lodash' ), null, true );
		wp_register_script( 'handlebars', $external_libs[ 'handlebars' ], false, null, true );
		wp_register_script( 'moment', $external_libs[ 'moment' ], false, null, true );
		wp_register_script( 'accounting', $external_libs[ 'accounting' ], false, null, true );
		wp_register_script( 'select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js', array( 'jquery' ), null, true );
		wp_register_script( 'idb-wrapper', $external_libs[ 'idb-wrapper' ], false, null, true );

		// enqueue
		wp_enqueue_script( 'jquery-ui-sortable' );

		$build = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG && file_exists( WC_POS_PLUGIN_PATH . 'assets/js/admin.build.js' ) ? 'build' : 'min';

		wp_enqueue_script(
			WC_POS_PLUGIN_NAME . '-admin-app',
			WC_POS_PLUGIN_URL . 'assets/js/admin.' . $build . '.js',
			array( 'backbone', 'backbone.radio', 'marionette', 'handlebars', 'accounting', 'moment', 'select2', 'idb-wrapper' ),
			WC_POS_VERSION,
			true
		);

		$admin_options = array(
			'action'	=> 'wc_pos_admin_settings_payload',
			'ajaxurl' => admin_url( 'admin-ajax.php', 'relative' ),
			'nonce'	 => wp_create_nonce( WC_POS_PLUGIN_NAME )
		);
		wp_localize_script(WC_POS_PLUGIN_NAME . '-admin-app', str_ireplace('-', '_', WC_POS_PLUGIN_NAME . '-admin-app-params'), apply_filters(WC_POS_PLUGIN_NAME . '-admin-params', $admin_options));

		wp_enqueue_script(
			'eventsource-polyfill',
			WC_POS_PLUGIN_URL . 'assets/js/vendor/eventsource.min.js',
			array(),
			null,
			true
		);

		$scripts = apply_filters( 'woocommerce_pos_admin_enqueue_scripts', array() );
		if ( isset( $scripts[ 'locale' ] ) ) {
			wp_enqueue_script(
				WC_POS_PLUGIN_NAME . '-js-locale',
				$scripts[ 'locale' ],
				array( WC_POS_PLUGIN_NAME . '-admin-app' ),
				WC_POS_VERSION,
				true
			);
		}
	}
}
