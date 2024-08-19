<?php
/**
 * Main file.
 *
 * @link    https://github.com/balakrishnandsr
 * @since   1.0.0
 *
 * @author  Balakrishnan D
 * @package Custom_Subscription\includes
 */

// If this file is called directly, abort.
defined( 'WPINC' ) || die;


/**
 * Class Activation
 *
 * @package Custom_Subscription\Includes
 */
class Custom_Subscription {

	/**
	 * Variable to hold instance of activation class.
	 *
	 * @var $instance
	 */
	private static $instance;

	/**
	 * Get  instance of activation class.
	 *
	 * @return Custom_Subscription  object of activation class
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
		require_once "class-cs-admin.php";
		require_once "class-cs-frontend.php";
	}

}
