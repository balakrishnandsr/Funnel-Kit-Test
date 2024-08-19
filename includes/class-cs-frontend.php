<?php
/**
 * Front-end file.
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
 * Class Frontend
 *
 * @package Custom_Subscription\Includes
 */
class Frontend {

	/**
	 * Variable to hold instance of Frontend class.
	 *
	 * @var $instance
	 */
	private static $instance;

	/**
	 * Get  instance of Frontend class.
	 *
	 * @return Frontend  object of Frontend class
	 */
	public static function get_instance() {

		// Check if instance is already exists.
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Frontend hooks.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function __construct() {

		// Show product details on front end.
		add_action( 'woocommerce_single_product_summary', array( $this, 'display_custom_product_field' ) );
	}

	/**
	 * Display the values to the front end.
	 * @return void
	 */
	public function display_custom_product_field(){
		global $product;

		//Including required files.
		require_once "class-cs-calculations.php";

		//Display section.
		if ( $product->get_type() == 'custom' ) {
			$subscription_duration 		= get_post_meta( $product->get_id(), '_subscription_duration', true );
			$discount_rate 		   		= get_post_meta( $product->get_id(), '_custom_subscription_discount_rate', true );
			$estimated_subscription 	= get_post_meta( $product->get_id(), '_custom_subscription_estimated_subscription', true );
			$regular_price 				= get_post_meta( $product->get_id(), '_custom_regular_price', true );
			$revenue 					= Calculations::get_revenue_calculation( $product );

			// Format the prices.
			$formatted_revenue 			=  !empty($revenue['revenue']) && is_numeric( $revenue['revenue'] ) ? wc_price( $revenue['revenue'] ) : $revenue['revenue'];
			$formatted_discount 		=  !empty($revenue['discount']) && is_numeric( $revenue['discount'] ) ? wc_price( $revenue['discount'] ) : $revenue['discount'];
			$formatted_regular_price	= is_numeric( $regular_price ) ? wc_price( $regular_price ) : $regular_price;

			echo '<p>' . __( 'Subscription Duration: ', 'custom-subscriptions' ) . esc_html( $subscription_duration ) . '</p>';
			echo '<p>' . __( 'Discount Rate: ', 'custom-subscriptions' ) . esc_html( $discount_rate ) . ' % </p>';
			echo '<p>' . __( 'Estimated Subscription: ', 'custom-subscriptions' ) . esc_html( $estimated_subscription ) . '</p>';
			echo '<p>' . __( 'Regular Price: ', 'custom-subscriptions' ) . __( $formatted_regular_price ) . '</p>';
			echo '<p>' . __( 'Future Estimated Revenue: ', 'custom-subscriptions' ) . __( $formatted_revenue ) . '</p>';
			echo '<p>' . __( 'Discounted Revenue: ', 'custom-subscriptions' ) . __( $formatted_discount ) . '</p>';
		}
	}


}

Frontend::get_instance();
