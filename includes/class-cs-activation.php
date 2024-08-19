<?php
/**
 * Class is called upon plugin activation.
 *
 * @link    https://github.com/balakrishnandsr
 * @since   1.0.0
 *
 * @author  Balakrishnan D
 * @package Custom_Subscription\Includes
 */

// If this file is called directly, abort.
defined( 'WPINC' ) || die;


/**
 * Class Activation
 *
 * @package Custom_Subscription\Includes
 */
final class Activation {

	/**
	 * Variable to hold instance of activation class.
	 *
	 * @var $instance
	 */
	private static $instance;

	/**
	 * Get  instance of activation class.
	 *
	 * @return Activation  object of activation class
	 */
	public static function get_instance() {

		// Check if instance is already exists.
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Activation hooks.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->check_environment_compatibility();
		do_action( 'wpcs_plugin_activated' );
	}

	/**
	 * Check the environment is compatible.
	 *
	 * @return void
	 */
	private function check_environment_compatibility(){

		if( ! $this->min_required_php() ){
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				error_log(  // phpcs:ignore
					esc_html__( 'Minimum required PHP vesion is above 8.0', 'custom-subscription' )
				);
			}

			add_action(
				'admin_notices',
				function() {
					?>
					<div class="notice notice-error">
						<p>
							<?php esc_html__( 'Minimum required PHP vesion is above 8.0', 'custom-subscription' ) ?>
						</p>
					</div>
					<?php
				}
			);

			exit();


		}

		if( ! $this->is_woocommece_active() ){
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				error_log(  // phpcs:ignore
					esc_html__( 'Please install and activate the woocommerce plugin to activate the "Custom Subscription" Plugin.', 'custom-subscription' )
				);
			}
			add_action(
				'admin_notices',
				function() {
					?>
					<div class="notice notice-error">
						<p>
							<?php esc_html__( 'Please install and activate the woocommerce plugin to activate the "Custom Subscription" Plugin.', 'custom-subscription' ) ?>
						</p>
					</div>
					<?php
				}
			);

			exit();

		}

	}

	/**
	 * Minimum required php version.
	 * @return bool
	 */
	private function min_required_php(){
		return version_compare( PHP_VERSION, 7.0, '>=' );
	}

	/**
	 * Minimum required WooCommerce version.
	 * @return bool
	 */
	private function is_woocommece_active(){
		$active_plugins = apply_filters('active_plugins', get_option('active_plugins', array()));
        if (is_multisite()) {
            $active_plugins = array_merge($active_plugins, get_site_option('active_sitewide_plugins', array()));
        }
        return in_array('woocommerce/woocommerce.php', $active_plugins, false) || array_key_exists('woocommerce/woocommerce.php', $active_plugins);
	}


}
