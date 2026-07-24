<?php
class WC_Gateway_REST_API extends WC_Payment_Gateway {

    public function __construct() {
        $this->id                 = 'rest_api_gateway';
        $this->icon               = apply_filters( 'woocommerce_rest_api_gateway_icon', '' );
        $this->has_fields         = true;
        $this->method_title       = __( 'REST API Gateway', 'wc-rest-gateway' );
        $this->method_description = __( 'Accept payments via our custom REST API provider.', 'wc-rest-gateway' );
        $this->supports           = array( 'products' );

        $this->init_form_fields();
        $this->init_settings();

        $this->title     = $this->get_option( 'title' );
        $this->api_key   = $this->get_option( 'api_key' );
        $this->secret    = $this->get_option( 'secret' );
        $this->test_mode = 'yes' === $this->get_option( 'test_mode' );

        // Save settings in admin
        add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
    }

    public function init_form_fields() {
        $this->form_fields = array(
            'enabled' => array(
                'title'   => __( 'Enable/Disable', 'wc-rest-gateway' ),
                'type'    => 'checkbox',
                'label'   => __( 'Enable REST API Gateway', 'wc-rest-gateway' ),
                'default' => 'no'
            ),
            'title' => array(
                'title'       => __( 'Title', 'wc-rest-gateway' ),
                'type'        => 'text',
                'description' => __( 'This controls the title which the user sees during checkout.', 'wc-rest-gateway' ),
                'default'     => __( 'Credit Card (REST API)', 'wc-rest-gateway' ),
                'desc_tip'    => true,
            ),
            'api_key' => array(
                'title' => __( 'API Key', 'wc-rest-gateway' ),
                'type'  => 'text'
            ),
            'secret' => array(
                'title' => __( 'Webhook Secret', 'wc-rest-gateway' ),
                'type'  => 'password'
            ),
            'test_mode' => array(
                'title'   => __( 'Test Mode', 'wc-rest-gateway' ),
                'type'    => 'checkbox',
                'label'   => __( 'Enable Test Mode', 'wc-rest-gateway' ),
                'default' => 'yes'
            )
        );
    }

    /**
     * Process the payment when the user clicks "Place Order"
     */
    public function process_payment( $order_id ) {
        $order = wc_get_order( $order_id );

        // Mocking a REST API call to the external payment provider
        $api_response = $this->call_external_payment_api( $order );

        if ( $api_response['success'] ) {
            // Payment successful, mark order as processing
            $order->payment_complete( $api_response['transaction_id'] );
            $order->add_order_note( 'Payment successful via REST API. Txn ID: ' . $api_response['transaction_id'] );
            
            // Empty cart and return success
            WC()->cart->empty_cart();
            return array(
                'result'   => 'success',
                'redirect' => $this->get_return_url( $order )
            );
        }

        // Payment failed
        wc_add_notice( __( 'Payment error: ', 'wc-rest-gateway' ) . $api_response['message'], 'error' );
        return array( 'result' => 'failure' );
    }

    /**
     * Mock function to call external REST API
     */
    private function call_external_payment_api( $order ) {
        // In a real scenario, you would use wp_remote_post() here
        return array(
            'success'        => true,
            'transaction_id' => 'txn_' . wp_generate_password( 10, false ),
            'message'        => ''
        );
    }
}
