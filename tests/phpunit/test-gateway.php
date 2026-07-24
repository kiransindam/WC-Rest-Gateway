<?php
/**
 * Basic PHPUnit test for the REST API Gateway.
 * Requires the WordPress PHPUnit test framework to be bootstrapped.
 */
class Test_WC_REST_Gateway extends WP_UnitTestCase {

    /**
     * Test if the gateway is successfully registered in WooCommerce.
     */
    public function test_gateway_is_registered() {
        $gateways = WC()->payment_gateways->get_available_payment_gateways();
        $this->assertArrayHasKey( 'rest_api_gateway', $gateways, 'Gateway was not registered properly.' );
    }

    /**
     * Test if the payment process returns a success array.
     */
    public function test_process_payment_success() {
        // Create a mock order
        $order = wc_create_order();
        $product = WC_Helper_Product::create_simple_product();
        $order->add_product( $product, 1 );
        $order->calculate_totals();

        $gateway = new WC_Gateway_REST_API();
        $result = $gateway->process_payment( $order->get_id() );

        $this->assertEquals( 'success', $result['result'], 'Payment process did not return success.' );
        $this->assertEquals( 'completed', $order->get_status(), 'Order status was not updated to completed.' );
    }
}
