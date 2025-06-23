<?php
/**
 * Plugin Name: NetScore Flexicon Connector for WooCommerce 
 * Description: This plugin integrates Flexicon with WooCommerce, enabling seamless synchronization between your WooCommerce store and the Flexicon system. It allows for automated data exchange such as product information, inventory levels, order details, and customer data. With this plugin, businesses can streamline operations, reduce manual entry, and ensure data consistency between Flexicon and WooCommerce.
 * Version: 1.0.1
 * Author: NetScoretechnologies
 * License: GPLv3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Include necessary files
require_once plugin_dir_path(__FILE__) . 'includes/form-handler.php';

// Activation Hook
function netscore_flexicon_connector_woocommerce_plugin_activate() {
    add_rewrite_rule('^nfcw-login/?$', 'index.php?nfcw_login=1', 'top');
    flush_rewrite_rules();
    add_action('admin_menu', 'netscore_flexicon_connector_woocommerce_add_admin_menu');
}
register_activation_hook(__FILE__, 'netscore_flexicon_connector_woocommerce_plugin_activate');

// Deactivation Hook
function netscore_flexicon_connector_woocommerce_plugin_deactivate() {
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'netscore_flexicon_connector_woocommerce_plugin_deactivate');

// Add a shortcode to display the custom form
function netscore_flexicon_connector_woocommerce_login_form_shortcode() {
    return netscore_flexicon_connector_woocommerce_login_form_handler();
}
add_shortcode('nfcw_login_form', 'netscore_flexicon_connector_woocommerce_login_form_shortcode');

// Add a rewrite rule for the custom login page
function netscore_flexicon_connector_woocommerce_rewrite_rule() {
    add_rewrite_rule('^nfcw-login/?$', 'index.php?nfcw_login=1', 'top');
}
add_action('init', 'netscore_flexicon_connector_woocommerce_rewrite_rule');

// Register a query variable
function netscore_flexicon_connector_woocommerce_query_vars($vars) {
    $vars[] = 'nfcw_login';
    return $vars;
}
add_filter('query_vars', 'netscore_flexicon_connector_woocommerce_query_vars');

// Display the form when visiting the custom page
function netscore_flexicon_connector_woocommerce_template_redirect() {
    if (get_query_var('nfcw_login')) {
        add_filter('body_class', function($classes) {
            $classes[] = 'nfcw-login-page';
            return $classes;
        });
        echo do_shortcode('[nfcw_login_form]');
        exit;
    }
}
add_action('template_redirect', 'netscore_flexicon_connector_woocommerce_template_redirect');

// Enqueue the plugin's stylesheet
function netscore_flexicon_connector_woocommerce_enqueue_styles() {
    wp_enqueue_style('nfcw-login-style', plugin_dir_url(__FILE__) . 'assets/css/style.css');
}
add_action('wp_enqueue_scripts', 'netscore_flexicon_connector_woocommerce_enqueue_styles');

// Add Admin Menu
function netscore_flexicon_connector_woocommerce_add_admin_menu() {
    add_menu_page(
        'Flexicon Login Settings',               // Page title
        'Flexicon Login',                        // Menu title
        'manage_options',                        // Capability required
        'nfcw-login-settings',                   // Menu slug
        'netscore_flexicon_connector_woocommerce_admin_page',  // Function
        'dashicons-admin-generic',              // Icon
        30
    );
}

// Display Admin Page
function netscore_flexicon_connector_woocommerce_admin_page() {
    ?>
    <div class="wrap">
        <h1>Flexicon Login Plugin Settings</h1>
        <p>Here you can manage the settings of the Flexicon Login Plugin. This is where form submissions can be viewed or plugin options can be adjusted.</p>
        <h2>Form Submissions</h2>
        <p>List of form submissions will be shown here...</p>
        <!-- You can implement functionality to display the form submissions here -->
    </div>
    <?php
}
