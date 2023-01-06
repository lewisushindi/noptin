<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Fires when a product is purchase.
 *
 * @since       1.2.8
 */
class Noptin_WooCommerce_Product_Purchase_Trigger extends Noptin_WooCommerce_Trigger {

	/**
	 * Constructor.
	 *
	 * @since 1.3.0
	 */
	public function __construct() {
		add_action( 'woocommerce_order_refunded', array( $this, 'init_refund_trigger' ) );
		add_action( 'woocommerce_order_status_completed', array( $this, 'init_buy_trigger' ) );
	}

	/**
	 * @inheritdoc
	 */
	public function get_id() {
		return 'woocommerce_product_purchase';
	}

	/**
	 * @inheritdoc
	 */
	public function get_name() {
		return __( 'WooCommerce Product Purchase', 'newsletter-optin-box' );
	}

	/**
	 * @inheritdoc
	 */
	public function get_description() {
		return __( 'When a WooCommerce Product is bought or refunded', 'newsletter-optin-box' );
	}

	/**
	 * @inheritdoc
	 */
	public function get_rule_description( $rule ) {

		$settings = $rule->trigger_settings;

		if ( empty( $settings['product_id'] ) || empty( $settings['action'] ) ) {
			return $this->get_description();
		}

		$product = wc_get_product( $settings['product_id'] );
		$product = $product ? $product->get_name() : __( 'Unknown Product', 'newsletter-optin-box' );

		if ( 'buy' === $settings['action'] ) {
			// translators: %s is the product name.
			return sprintf( __( 'When someone buys %s', 'newsletter-optin-box' ), $product );
		}

		// translators: %s is the product name.
		return sprintf( __( 'When someone is refunded for %s', 'newsletter-optin-box' ), $product );

	}

	/**
	 * @inheritdoc
	 */
	public function get_settings() {

		$products = wc_get_products(
			array(
				'limit'  => -1,
				'status' => 'publish',
				'parent' => 0,
			)
		);

		$prepared = array();

		foreach ( $products as $product ) {
			$prepared[ $product->get_id() ] = $product->get_name();
		}

		return array(

			'product_id' => array(
				'el'          => 'select',
				'options'     => $prepared,
				'label'       => __( 'Product', 'newsletter-optin-box' ),
				'placeholder' => __( 'Select a WooCommerce product', 'newsletter-optin-box' ),
				'default'     => current( array_keys( $prepared ) ),
			),

			'action'     => array(
				'el'          => 'select',
				'options'     => array(
					'buy'    => __( 'The product is bought', 'newsletter-optin-box' ),
					'refund' => __( 'The product is refunded', 'newsletter-optin-box' ),
				),
				'label'       => __( 'Action', 'newsletter-optin-box' ),
				'placeholder' => __( 'Select the product action', 'newsletter-optin-box' ),
				'default'     => 'buy',
			),

		);
	}

	/**
	 * @inheritdoc
	 */
	public function is_rule_valid_for_args( $rule, $args, $subscriber, $action ) {
		$settings = $rule->trigger_settings;

		// Ensure that we have an action for this event.
		if ( empty( $settings['action'] ) || $settings['action'] !== $args['action'] ) {
			return false;
		}

		// Confirm the products match.
		if ( empty( $settings['product_id'] ) || (int) $settings['product_id'] !== (int) $args['product_id'] ) {
			return false;
		}

		return true;

	}

	/**
     * Returns an array of known smart tags.
     *
     * @since 1.9.0
     * @return array
     */
    public function get_known_smart_tags() {

		return array_merge(
			parent::get_known_smart_tags(),
			$this->get_product_smart_tags(),
			$this->get_order_smart_tags(),
			$this->get_customer_smart_tags()
		);
    }

	/**
	 * Fires when a product is refunded.
	 *
	 * @param int|WC_Order $order_id The order being acted on.
	 * @since 1.9.0
	 */
	public function init_refund_trigger( $order_id ) {
		$this->maybe_trigger( $order_id, 'refund' );
	}

	/**
	 * Fires when a product is bought.
	 *
	 * @param int|WC_Order $order_id The order being acted on.
	 * @since 1.9.0
	 */
	public function init_buy_trigger( $order_id ) {
		$this->maybe_trigger( $order_id, 'buy' );
	}

	/**
	 * Fires when a product is bought or refunded.
	 *
	 * @param int|WC_Order $order_id The order being acted on.
	 * @param string       $action   The action being performed.
	 * @since 1.9.0
	 */
	protected function maybe_trigger( $order_id, $action ) {

		if ( is_numeric( $order_id ) ) {
			$order = wc_get_order( $order_id );
		} else {
			$order = $order_id;
		}

		// Ensure we have an order.
		if ( empty( $order ) || ! is_a( $order, 'WC_Order' ) ) {
			return;
		}

		// Prepare the order customer.
		if ( $order->get_customer_id() ) {
			$customer = new WC_Customer( $order->get_customer_id() );
		} else {
			$customer = WC()->customer;
		}

		// Loop through the order items.
		foreach ( $order->get_items() as $item ) {

			// Ensure we have a product.
			/** @var WC_Order_Item_Product $item */
			$product = $item->get_product();
			if ( empty( $product ) ) {
				continue;
			}

			// Ensure we have a product id.
			$product_id = $product->get_id();
			if ( empty( $product_id ) ) {
				continue;
			}

			// Attach WC hooks.
			$args = array_merge(
				$this->before_trigger_wc( $order, $customer, $product ),
				array(
					'order_id'    => $order->get_id(),
					'product_id'  => $product_id,
					'product_sku' => $product->get_sku(),
					'product_qty' => $item->get_quantity(),
					'action'      => $action,
				)
			);

			// Trigger the event.
			$this->trigger( $customer, $args );

			// Detach WC hooks.
			$this->after_trigger_wc( $args );
		}
	}

}
