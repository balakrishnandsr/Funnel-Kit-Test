<?php
/**
 * Custom Product Class.
 *
 * The custom product.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Custom product class.
 */
class WC_Product_Custom extends WC_Product {

	public function __construct( $product ) {
		$this->product_type = 'custom';
		parent::__construct( $product );
	}

	/**
 	 * Get internal type.
 	 *
 	 * @return string
 	 */
 	public function get_type() {
 		return 'custom';
 	}
}
