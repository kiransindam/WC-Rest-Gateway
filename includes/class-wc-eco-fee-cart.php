<?php
/**
 * Handles adding the fee to the cart and checkout.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class WC_Eco_Fee_Cart {

    protected static $instance = null;

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // Hook into the cart calculation.
        add_action( 'woocommerce_cart_calculate_fees', array( $this, 'add_eco_fee' ), 20 );
    }

    /**
     * Add the eco-friendly fee to the cart.
     *
     * @param WC_Cart $cart The WooCommerce cart object.
     */
    public function add_eco_fee( $cart ) {
        // Security & Validation checks.
        if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
            return;
        }

        // Check if the fee is enabled in settings.
        $is_enabled = get_option( 'wc_eco_fee_enable', 'no' );
        if ( 'yes' !== $is_enabled ) {
            return;
        }

        // Get settings and sanitize them.
        $fee_title = sanitize_text_field( get_option( 'wc_eco_fee_title', 'Eco-Friendly Packaging' ) );
        $fee_amount = floatval( get_option( 'wc_eco_fee_amount', 1.00 ) );

        // Ensure amount is greater than 0.
        if ( $fee_amount <= 0 ) {
            return;
        }

        // Optional: Only apply if cart total is above a certain amount, or if cart is not empty.
        if ( $cart->is_empty() ) {
            return;
        }

        // Add the fee. 
        // Parameters: Name, Amount, Taxable (boolean), Tax Class (string)
        $cart->add_fee( $fee_title, $fee_amount, true, '' );
    }
}
