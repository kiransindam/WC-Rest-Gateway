<?php
class WC_REST_Gateway_API extends WP_REST_Controller {

    public function __construct() {
        $this->namespace = 'wc-gateway/v1';
        $this->rest_base = 'webhook';
    }

    public function register_routes() {
        register_rest_route( $this->namespace, '/' . $this->rest_base, array(
            'methods'             => 'POST',
            'callback'            => array( $this, 'handle_webhook' ),
            'permission_callback' => '__return_true', // Security handled via HMAC signature
        ) );
    }

    /**
     * Handle incoming webhook from payment provider
     */
    public function handle_webhook( $request ) {
        $payload   = $request->get_body();
        $signature = $request->get_header( 'x-webhook-signature' );
        
        $gateway = new WC_Gateway_REST_API();
        $secret  = $gateway->get_option( 'secret' );
        
        // Verify HMAC Signature for security
        $expected_signature = hash_hmac( 'sha256', $payload, $secret );
        
        if ( ! hash_equals( $expected_signature, $signature ) ) {
            return new WP_Error( 'invalid_signature', 'Invalid webhook signature', array( 'status' => 403 ) );
        }

        $data     = json_decode( $payload, true );
        $order_id = isset( $data['order_id'] ) ? absint( $data['order_id'] ) : 0;
        $status   = isset( $data['status'] ) ? sanitize_text_field( $data['status'] ) : '';
        $txn_id   = isset( $data['transaction_id'] ) ? sanitize_text_field( $data['transaction_id'] ) : '';

        $order = wc_get_order( $order_id );
        if ( ! $order ) {
            return new WP_Error( 'invalid_order', 'Order not found', array( 'status' => 404 ) );
        }

        // Update order based on webhook status
        if ( $status === 'completed' ) {
            $order->payment_complete( $txn_id );
        } elseif ( $status === 'failed' ) {
            $order->update_status( 'failed', 'Payment failed via REST API webhook.' );
        }

        // Return JSON response to the payment provider
        return rest_ensure_response( array( 'status' => 'success', 'message' => 'Webhook processed' ) );
    }
}

// Register the REST API route
add_action( 'rest_api_init', function() {
    $api = new WC_REST_Gateway_API();
    $api->register_routes();
} );
