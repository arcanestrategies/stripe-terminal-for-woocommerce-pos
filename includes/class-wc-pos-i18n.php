<?php

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that its ready for translation.
 *
 * @class	WC_POS_i18n
 * @package	WooCommerce POS
 * @author	Paul Kilmurray <paul@kilbot.com.au>
 * @link	http://www.woopos.com.au
 */

class WC_POS_i18n {
	/**
	 * Constructor
	 */
	public function __construct() {
		//		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
		$this->load_plugin_textdomain();

		add_filter( 'woocommerce_pos_enqueue_scripts', array( $this, 'js_locale' ) );
		add_filter( 'woocommerce_pos_enqueue_footer_js', array( $this, 'js_locale' ) );
		add_filter( 'woocommerce_pos_admin_enqueue_scripts', array( $this, 'js_locale' ) );

		// ajax
		add_action( 'wp_ajax_wc_pos_update_translations', array( $this, 'update_translations' ) );
	}

	/**
	 * Load the plugin text domain for translation.
	 */
	public function load_plugin_textdomain() {

		$locale = apply_filters( 'plugin_locale', get_locale(), 'arcane-woocommerce-pos' );
		$dir = trailingslashit( WP_LANG_DIR );

		//
		if( is_pos() ) {
			load_textdomain( 'arcane-woocommerce-pos', $dir . 'arcane-woocommerce-pos/arcane-woocommerce-pos-' . $locale . '.mo' );
			load_textdomain( 'arcane-woocommerce-pos', $dir . 'plugins/arcane-woocommerce-pos-' . $locale . '.mo' );
			load_textdomain( 'woocommerce', $dir . 'woocommerce/woocommerce-admin-' . $locale . '.mo' );
			load_textdomain( 'woocommerce', $dir . 'plugins/woocommerce-admin-' . $locale . '.mo' );
		}

		// admin translations
		if ( is_admin() ) {
			load_textdomain( 'arcane-woocommerce-pos', $dir . 'arcane-woocommerce-pos/arcane-woocommerce-pos-admin-' . $locale . '.mo' );
			load_textdomain( 'arcane-woocommerce-pos', $dir . 'plugins/arcane-woocommerce-pos-admin-' . $locale . '.mo' );
		}

	}


	/**
	 * Load translations for js plugins
	 *
	 * @param $scripts
	 * @return string
	 */
	public function js_locale( array $scripts ) {
		$locale = apply_filters( 'plugin_locale', get_locale(), WC_POS_PLUGIN_NAME );
		$dir = WC_POS_PLUGIN_PATH . 'languages/js/';
		$url = WC_POS_PLUGIN_URL . 'languages/js/';
		list( $country ) = explode( '_', $locale );

		if ( is_readable( $dir . $locale . '.js' ) ) {
			$scripts[ 'locale' ] = $url . $locale . '.js';
		} elseif ( is_readable( $dir . $country . '.js' ) ) {
			$scripts[ 'locale' ] = $url . $country . '.js';
		}

		return $scripts;
	}

	/**
	 * Return currency denomination for a given country code
	 *
	 * @param string $code
	 * @return array
	 */
	static public function currency_denominations( $code = '' ) {
		if ( !$code ) {
			$code = get_woocommerce_currency();
		}
		$denominations = json_decode( file_get_contents( WC_POS_PLUGIN_PATH . 'includes/denominations.json' ) );

		return isset( $denominations->$code ) ? $denominations->$code : $denominations;
	}

	/**
	 * i18n payload to init POS app
	 *
	 * @return mixed
	 */
	static public function payload() {

		return apply_filters( 'woocommerce_pos_i18n', array(
			'titles'	 => array(
				'browser'			 => _x( 'Browser', 'system status: browser capabilities', 'arcane-woocommerce-pos' ),
				/* translators: woocommerce */
				'cart'					=> __( 'Cart', 'woocommerce' ),
				/* translators: woocommerce */
				'checkout'			=> __( 'Checkout', 'woocommerce' ),
				/* translators: woocommerce */
				'coupons'			 => __( 'Coupons', 'woocommerce' ),
				/* translators: woocommerce */
				'customers'		 => __( 'Customers', 'woocommerce' ),
				/* translators: woocommerce */
				'fee'					 => __( 'Fee', 'woocommerce' ),
				'hotkeys'			 => _x( 'HotKeys', 'keyboard shortcuts', 'arcane-woocommerce-pos' ),
				/* translators: woocommerce */
				'order'				 => __( 'Order', 'woocommerce' ),
				/* translators: woocommerce */
				'orders'				=> __( 'Orders', 'woocommerce' ),
				/* translators: woocommerce */
				'products'			=> __( 'Products', 'woocommerce' ),
				/* translators: woocommerce */
				'receipt'			 => __( 'Receipt', 'woocommerce' ),
				/* translators: woocommerce */
				'shipping'			=> __( 'Shipping', 'woocommerce' ),
				'to-pay'				=> __( 'To Pay', 'arcane-woocommerce-pos' ),
				'paid'					=> __( 'Paid', 'arcane-woocommerce-pos' ),
				'unpaid'				=> __( 'Unpaid', 'arcane-woocommerce-pos' ),
				'email-receipt' => __( 'Email Receipt', 'arcane-woocommerce-pos' ),
				'open'					=> _x( 'Open', 'order status, ie: open order in cart', 'arcane-woocommerce-pos' ),
				'change'				=> _x( 'Change', 'Money returned from cash sale', 'arcane-woocommerce-pos' ),
				'support-form'	=> __( 'Support Form', 'arcane-woocommerce-pos' ),
				/* translators: woocommerce */
				'system-status' => __( 'System Status', 'woocommerce' ),
			),
			'buttons'	=> array(
				/* translators: woocommerce */
				'checkout'				=>	__( 'Checkout', 'woocommerce' ),
				'clear'					 => _x( 'Clear', 'system status: delete local records', 'arcane-woocommerce-pos' ),
				/* translators: woocommerce */
				'close'					 => __( 'Close' ),
				/* translators: woocommerce */
				'coupon'					=> __( 'Coupon', 'woocommerce' ),
				'discount'				=> __( 'Discount', 'arcane-woocommerce-pos' ),
				/* translators: wordpress */
				'email'					 => __( 'Email' ),
				/* translators: woocommerce */
				'fee'						 => __( 'Fee', 'woocommerce' ),
				/* translators: woocommerce */
				'new-order'			 => __( 'New Order', 'woocommerce' ),
				/* translators: woocommerce */
				'note'						=> __( 'Note', 'woocommerce' ),
				/* translators: wordpress */
				'print'					 => __( 'Print' ),
				'process-payment' => __( 'Process Payment', 'arcane-woocommerce-pos' ),
				/* translators: wordpress */
				'refresh'				 => __( 'Refresh' ),
				'restore'				 => _x( 'Restore defaults', 'restore default settings', 'arcane-woocommerce-pos' ),
				'return'					=> _x( 'return', 'Numpad return key', 'arcane-woocommerce-pos' ),
				'return-to-sale'	=> __( 'Return to Sale', 'arcane-woocommerce-pos' ),
				/* translators: woocommerce */
				'save'						=> __( 'Save Changes', 'woocommerce' ),
				'send'						=> __( 'Send', 'arcane-woocommerce-pos' ),
				/* translators: woocommerce */
				'shipping'				=> __( 'Shipping', 'woocommerce' ),
				'void'						=> __( 'Void', 'arcane-woocommerce-pos' ),
				/* translators: woocommerce */
				'expand-all'			=> __( 'Expand all', 'woocommerce' ),
				/* translators: woocommerce */
				'close-all'			 => __( 'Close all', 'woocommerce' ),
				'legacy'					=> __( 'Enable legacy server support', 'arcane-woocommerce-pos' ),
			),
			'messages' => array(
				/* translators: woocommerce */
				'choose'			=> __( 'Choose an option', 'woocommerce' ),
				/* translators: woocommerce */
				'error'			 => __( 'Sorry, there has been an error.', 'woocommerce' ),
				/* translators: woocommerce */
				'loading'		 => __( 'Loading...' ),
				/* translators: woocommerce */
				'success'		 => __( 'Your changes have been saved.', 'woocommerce' ),
				'browser'		 => __( 'Your browser is not supported!', 'arcane-woocommerce-pos' ),
				'legacy'			=> __( 'Unable to use RESTful HTTP methods', 'arcane-woocommerce-pos' ),
				/* translators: woocommerce */
				'no-products' => __( 'No products found', 'woocommerce' ),
				/* translators: woocommerce */
				'cart-empty'	=> __( 'Your cart is currently empty.', 'woocommerce' ),
				'no-gateway'	=> __( 'No payment gateways enabled.', 'arcane-woocommerce-pos' ),
				/* translators: woocommerce */
				'no-customer' => __( 'Customer not found', 'woocommerce' ),
				'bad-gateway' => __( 'This gateway failed to load.', 'arcane-woocommerce-pos' ),
				'no-terminal' => __( 'You must select a terminal before processing payment.', 'arcane-woocommerce-pos' ),
			),
			'plural'	 => array(
				'records' => _x( 'record |||| records', 'eg: 23 records', 'arcane-woocommerce-pos' ),
			)
		) );

	}

}