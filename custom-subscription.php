<?php
/**
 * Plugin Name:     Custom Subscription
 * Plugin URI:      https://funnelkit.com/
 * Description:     Adds a new product type, Custom Subscription to the WooCommerce store.
 * Author:          Balakrishnan D
 * Author URI:      https://balakrishnandsr.wordpress.com/
 * Text Domain:     custom-subscription
 * Domain Path:     /languages
 * Version:         1.0.0
 *
 * @package         Custom_Subscription
 */

 // If this file is called directly, abort.
 defined( 'WPINC' ) || die;

 // Plugin directory.
 if ( ! defined( 'WP_CS_DIR' ) ) {
	define( 'WP_CS_DIR', plugin_dir_path( __FILE__ ) );
 }

 //Plugin URL.
if (!defined('CS_PLUGIN_URL')) {
    define('CS_PLUGIN_URL', plugin_dir_url(__FILE__) );
}

 //Plugin Version.
if (!defined('CS_VERSION')) {
    define('CS_VERSION','1.0.0' );
}

require_once WP_CS_DIR . 'includes/class-cs-activation.php';
require_once WP_CS_DIR . 'includes/class-custom-subscription.php';

/**
 * Run plugin activation hook to setup plugin.
 *
 * @since 1.0.0
 */


 register_activation_hook(
	__FILE__,
	function() {
		Activation::get_instance();
	}
);

// Load the plugin.
 add_action(
 	'plugins_loaded',
 	function() {
		Custom_Subscription::get_instance();
 	},
 	11
 );
