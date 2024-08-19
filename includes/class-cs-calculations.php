<?php
/**
 * Revenue Calculations file.
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
 * Class Calculations
 *
 * @package Custom_Subscription\Includes
 */
class Calculations {

	/**
	 * Variable to hold instance of Calculations class.
	 *
	 * @var $instance
	 */
	private static $instance;

	/**
	 * Get instance of Calculations class.
	 *
	 * @return Calculations  object of Calculations class
	 */
	public static function get_instance() {

		// Check if instance is already exists.
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Future revenue calculation logic.
	 * @return mixed
	 */
	public static function get_revenue_calculation( $product ){

			if( empty( $product ) ){
				global $product;
			}

			//Get stored values.
			$discount_percentage 	= get_post_meta( $product->get_id(), '_custom_subscription_discount_rate', true );
			$estimated_subscription = get_post_meta( $product->get_id(), '_custom_subscription_estimated_subscription', true );
			$regular_price 			= get_post_meta( $product->get_id(), '_custom_regular_price', true );

			//Discount calculation.
			$discount = ( $regular_price ) * ( $discount_percentage / 100 );

			//Estimated discount.
			$estimated_discount = $discount * $estimated_subscription;

			//Revenue.
			$estimated_revenue = $estimated_subscription * $regular_price;

			//Estimated revenue.
			$future_revenue = $estimated_revenue - $estimated_discount;

			return apply_filters('custom_subscription_revenue_calculation', array( 'revenue' => $future_revenue, 'discount' => $estimated_discount), $discount_percentage, $estimated_subscription, $regular_price  );
	}


}
