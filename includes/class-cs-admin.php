<?php
/**
 * Admin file.
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
 * Class Admin
 *
 * @package Custom_Subscription\Includes
 */
class Admin {

	/**
	 * Variable to hold instance of Admin class.
	 *
	 * @var $instance
	 */
	private static $instance;

	/**
	 * Get  instance of Admin class.
	 *
	 * @return Admin  object of Admin class
	 */
	public static function get_instance() {

		// Check if instance is already exists.
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Admin hooks.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function __construct() {

		// Add admin script.
		if( is_admin() || wp_doing_ajax() ){
			add_action('admin_enqueue_scripts', array($this, 'cs_admin_scripts') );
		}

		// Add New Product Type to Select Dropdown
		add_filter( 'product_type_selector', array( $this, 'cs_add_custom_product_type' ) );

		// Load New Product Type Class
		add_action( 'init', array( $this, 'register_custom_product_type' ) );

		// Show Product Data General Tab
		add_action( 'woocommerce_product_options_general_product_data', array( $this, 'custom_product_type_options') );

		// Save data
		add_action( 'woocommerce_process_product_meta_custom', array( $this, 'save_custom_product_type_options' ) );
	}

	/**
	 * Include Custom product Type.
	 *
	 * @return array
	 */
	public function cs_add_custom_product_type( $types ){
		if( ! is_array( $types ) ){
			return $types;
		}
		$types[ 'custom' ] = 'Custom Subscription';
		return $types;
	}

	/**
	 * Get product_type
	 *
	 * @return string
	 */
	public function cs_create_custom_product_type(){
		return 'custom';
	}

	public function register_custom_product_type(){
		require_once 'class-cs-product-type.php';
	}

	/**
	 *  Adding admin fields.
	 * @return void
	 */
	public function custom_product_type_options(){

		echo '<div class="options_group">';
		wp_nonce_field( 'custom_action', 'custom_nonce' );
		woocommerce_wp_text_input(
			array(
				'id'        => '_custom_regular_price',
				'label'     => __( 'Regular price', 'custom-subscriptions' ) . ' (' . get_woocommerce_currency_symbol() . ')',
				'data_type' => 'price',
			)
		);

		// Subscription Length
		woocommerce_wp_select(
			array(
				'id'          => '_subscription_duration',
				'class'       => 'wc_input_subscription_duration select short',
				'label'       => __( 'Subscription Duration (Days)', 'custom-subscriptions' ),
				'options'     => array( 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30 ),
				'desc_tip'    => true,
				'description' => __( 'Automatically expire the subscription after this length of time. ', 'custom-subscriptions' ),
			)
		);

		// Subscription Rate
			woocommerce_wp_text_input(
				array(
					'id'          => '_custom_subscription_discount_rate',
					'label'       => __( 'Discount Rate', 'custom-subscriptions' ),
					'placeholder' => 'Enter discount % (0-100)',
					'desc_tip'    => 'true',
					'description' => __( 'Discount percentage.', 'custom-subscriptions' ),
					'type'              => 'number',
					'custom_attributes' => array(
											'step' 	=> 'any',
											'min'	=> '0',
											'max'   =>  '100'
										)
				)
			);


		// Estimated Subscription
		woocommerce_wp_text_input(
				array(
					'id'          => '_custom_subscription_estimated_subscription',
					'label'       => __( 'Estimated Subscription', 'custom-subscriptions' ),
					'desc_tip'    => 'true',
					'description' => __( 'Estimated Subscription count.', 'custom-subscriptions' ),
					'placeholder' => __( 'Enter Estimated Subscription count.', 'custom-subscriptions' ),
					'type'              => 'number',
					'custom_attributes' => array(
											'step' 	=> 'any',
											'min'	=> '0'
										)
				)
			);

		echo '</div>';

	}

	/**
	 * Save custom product type options.
	 * @param mixed $post_id
	 * @return void
	 */
	public function save_custom_product_type_options( $post_id ){
		/**
		 * We Can not perform nonce verification with in this hook.
		 */
		$subscription_duration = ( isset( $_POST['_subscription_duration'] )  ) ? wc_clean( wp_unslash( $_POST['_subscription_duration'] ) ) : null;
		$custom_subscription_discount_rate = ( isset( $_POST['_custom_subscription_discount_rate'] ) ) ? wc_clean( wp_unslash(  $_POST['_custom_subscription_discount_rate'] ) ) : null;
		$custom_subscription_estimated_subscription = ( isset( $_POST['_custom_subscription_estimated_subscription'] ) ) ? wc_clean( wp_unslash( $_POST['_custom_subscription_estimated_subscription'] ) ) : null;
		$custom_regular_price = ( isset( $_POST['_custom_regular_price'] ) ) ? wc_clean( wp_unslash( $_POST['_custom_regular_price'] ) ) : null;

		update_post_meta( $post_id, '_subscription_duration', $subscription_duration );
		update_post_meta( $post_id, '_custom_subscription_discount_rate', $custom_subscription_discount_rate );
		update_post_meta( $post_id, '_custom_subscription_estimated_subscription', $custom_subscription_estimated_subscription );
		update_post_meta( $post_id, '_custom_regular_price', $custom_regular_price );

	}

	/**
	 * Adding admin Script
	 * @return void
	 */
	public function cs_admin_scripts(){

		wp_enqueue_script( 'cs_admin_script', CS_PLUGIN_URL . 'assets/js/admin_script.js', array('jquery'), CS_VERSION );
		$params = array(
			'i18n_estimated_subscription_error'       => __( 'Estimated Subscription should be an integer.', 'custom-subscriptions' ),
			'i18n_discount_rate_error'				  => __( 'Please make sure the Discount Rate from 0 to 100.', 'custom-subscriptions' ),
			'i18n_subscription_duration_error'        => __( 'Subscription duration should be an integer.', 'custom-subscriptions' ),
			'ajax_url'                          	  => admin_url( 'admin-ajax.php' ),
			'i18n_common_error'                       =>__( 'Please check the input field value !', 'custom-subscriptions' ),
		);

		wp_localize_script('cs_admin_script', 'cs_admin', $params);
	}


}
Admin::get_instance();
