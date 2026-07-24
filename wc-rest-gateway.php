<?php
/**
 * Plugin Name: WooCommerce REST API Gateway
 * Description: Accept payments via a custom REST API provider with webhook verification.
 * Version: 1.0.0
 * Author: Your Name
 * Requires Plugins: woocommerce
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// 1. Add the gateway to WooCommerce
add_filter( 'woocommerce_payment_gateways', 'wc_rest_gateway_add_gateway' );
function wc_rest_gateway_add_gateway( $gateways ) {
    $gateways[] = 'WC_Gateway_REST_API';
    return $gateways;
}

// 2. Load classes when plugins are loaded
add_action( 'plugins_loaded', 'wc_rest_gateway_init' );
function wc_rest_gateway_init() {
    if ( ! class_exists( 'WC_Payment_Gateway' ) ) return;

    require_once plugin_dir_path( __FILE__ ) . 'includes/class-wc-gateway-rest-api.php';
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-wc-rest-gateway-api.php';
}
