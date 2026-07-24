<?php
/**
 * Plugin Name: WooCommerce Eco-Friendly Fee
 * Plugin URI: https://github.com/yourusername/wc-eco-fee
 * Description: Adds a configurable eco-friendly packaging fee to the WooCommerce checkout.
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://yourwebsite.com
 * Text Domain: wc-eco-fee
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Requires Plugins: woocommerce
 * WC requires at least: 8.0
 * WC tested up to: 9.0
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define plugin constants.
define( 'WC_ECO_FEE_VERSION', '1.0.0' );
define( 'WC_ECO_FEE_PATH', plugin_dir_path( __FILE__ ) );
define( 'WC_ECO_FEE_URL', plugin_dir_url( __FILE__ ) );

/**
 * Declare compatibility with WooCommerce High-Performance Order Storage (HPOS).
 */
add_action( 'before_woocommerce_init', function() {
    if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
    }
} );

/**
 * Main Plugin Class.
 */
class WC_Eco_Fee_Plugin {

    /**
     * Instance of this class.
     */
    protected static $instance = null;

    /**
     * Return an instance of this class.
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Initialize the plugin.
     */
    private function __construct() {
        // Check if WooCommerce is active.
        if ( ! $this->check_woocommerce_active() ) {
            add_action( 'admin_notices', array( $this, 'woocommerce_missing_notice' ) );
            return;
        }

        // Load includes.
        $this->includes();

        // Initialize hooks.
        add_action( 'plugins_loaded', array( $this, 'init' ) );
        add_action( 'woocommerce_init', array( $this, 'woocommerce_loaded' ) );
    }

    /**
     * Check if WooCommerce is active.
     */
    private function check_woocommerce_active() {
        return in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) );
    }

    /**
     * Show a notice if WooCommerce is not active.
     */
    public function woocommerce_missing_notice() {
        echo '<div class="error"><p><strong>WooCommerce Eco-Friendly Fee</strong> requires WooCommerce to be installed and active.</p></div>';
    }

    /**
     * Include required files.
     */
    private function includes() {
        require_once WC_ECO_FEE_PATH . 'includes/class-wc-eco-fee-cart.php';
    }

    /**
     * Init the plugin after WordPress is loaded.
     */
    public function init() {
        // Load text domain for translations.
        load_plugin_textdomain( 'wc-eco-fee', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
        
        // Initialize Cart/Fee logic.
        WC_Eco_Fee_Cart::get_instance();
    }

    /**
     * Add settings to WooCommerce Admin.
     */
    public function woocommerce_loaded() {
        // Add a new tab to WooCommerce Settings.
        add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_tab' ), 50 );
        add_action( 'woocommerce_settings_tabs_eco_fee', array( $this, 'settings_tab_content' ) );
        add_action( 'woocommerce_update_options_eco_fee', array( $this, 'update_settings' ) );
    }

    /**
     * Add a new settings tab to the WooCommerce settings tabs array.
     */
    public function add_settings_tab( $settings_tabs ) {
        $settings_tabs['eco_fee'] = __( 'Eco-Friendly Fee', 'wc-eco-fee' );
        return $settings_tabs;
    }

    /**
     * Output the settings tab content.
     */
    public function settings_tab_content() {
        woocommerce_admin_fields( $this->get_settings() );
    }

    /**
     * Update the settings.
     */
    public function update_settings() {
        woocommerce_update_options( $this->get_settings() );
    }

    /**
     * Define the settings fields.
     */
    public function get_settings() {
        $settings = array(
            'section_title' => array(
                'name' => __( 'Eco-Friendly Packaging Fee Settings', 'wc-eco-fee' ),
                'type' => 'title',
                'desc' => __( 'Configure the fee applied at checkout for eco-friendly packaging.', 'wc-eco-fee' ),
                'id'   => 'wc_eco_fee_settings',
            ),
            'enable' => array(
                'name'    => __( 'Enable Fee', 'wc-eco-fee' ),
                'type'    => 'checkbox',
                'desc'    => __( 'Enable the eco-friendly packaging fee at checkout.', 'wc-eco-fee' ),
                'default' => 'no',
                'id'      => 'wc_eco_fee_enable',
            ),
            'title' => array(
                'name'    => __( 'Fee Title', 'wc-eco-fee' ),
                'type'    => 'text',
                'desc'    => __( 'The text shown to the customer in the cart/checkout.', 'wc-eco-fee' ),
                'default' => __( 'Eco-Friendly Packaging', 'wc-eco-fee' ),
                'id'      => 'wc_eco_fee_title',
            ),
            'amount' => array(
                'name'    => __( 'Fee Amount', 'wc-eco-fee' ),
                'type'    => 'text',
                'desc'    => __( 'The amount to charge (e.g., 1.50).', 'wc-eco-fee' ),
                'default' => '1.00',
                'id'      => 'wc_eco_fee_amount',
                'css'     => 'width: 100px;',
                'desc_tip' => true,
            ),
            'section_end' => array(
                'type' => 'sectionend',
                'id'   => 'wc_eco_fee_settings',
            ),
        );

        return apply_filters( 'wc_eco_fee_settings', $settings );
    }
}

// Initialize the plugin.
WC_Eco_Fee_Plugin::get_instance();
