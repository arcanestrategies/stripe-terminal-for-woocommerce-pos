<?php

/**
 * Plugin Name: Arcane WooCommerce POS
 * Plugin URI: https://www.arcanestrategies.com/products/woocommerce-pos/
 * Description: A simple front-end for taking WooCommerce orders at the Point of Sale. Requires <a href="http://wordpress.org/plugins/woocommerce/">WooCommerce</a>.
 * Version: 1.0.0
 * Author: Arcane Strategies
 * Author URI: https://www.arcanestrategies.com/
 * Text Domain: arcane-woocommerce-pos
 * Domain Path: /languages
 * WC requires at least: 2.6
 * WC tested up to: 4.0.1
 * 
 * @package   Arcane WooCommerce POS
 * @author    Paul Kilmurray <paul@kilbot.com.au>
 * @link      http://woopos.com.au
 * 
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define plugin constants.
 */
define( 'WC_POS_VERSION', '1.0.0' );
define( 'WC_POS_PLUGIN_NAME', 'arcane-woocommerce-pos' );
define( 'WC_POS_PLUGIN_FILE', plugin_basename( __FILE__ ) ); // 'arcane-woocommerce-pos/woocommerce-pos.php'
define( 'WC_POS_PLUGIN_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'WC_POS_PLUGIN_URL', trailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );

/**
 * The code that runs during plugin activation.
 */
require_once WC_POS_PLUGIN_PATH . 'includes/class-wc-pos-activator.php';
new WC_POS_Activator( plugin_basename( __FILE__ ) );

/**
 * The code that runs during plugin deactivation.
 */
require_once WC_POS_PLUGIN_PATH . 'includes/class-wc-pos-deactivator.php';
new WC_POS_Deactivator( plugin_basename( __FILE__ ) );
