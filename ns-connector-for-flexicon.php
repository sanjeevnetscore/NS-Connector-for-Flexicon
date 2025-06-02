<?php
/**
 * Plugin Name: NS Connector for Flexicon by NetScore
 * Description: This plugin integrates Flexicon with WooCommerce.
 * Version: 1.0.0
 * Author: NetScore
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
function custom_login_plugin_activate() {
    add_rewrite_rule('^custom-login/?$', 'index.php?custom_login=1', 'top');
    flush_rewrite_rules();
    // Add admin menu on plugin activation
    add_action('admin_menu', 'custom_login_add_admin_menu');
}
register_activation_hook(__FILE__, 'custom_login_plugin_activate');

// Deactivation Hook
function custom_login_plugin_deactivate() {
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'custom_login_plugin_deactivate');

// Add a shortcode to display the custom form
function custom_login_form_shortcode() {
    return custom_login_form_handler();
}
add_shortcode('custom_login_form', 'custom_login_form_shortcode');

// Add a rewrite rule for the custom login page
function custom_login_rewrite_rule() {
    add_rewrite_rule('^custom-login/?$', 'index.php?custom_login=1', 'top');
}
add_action('init', 'custom_login_rewrite_rule');

// Register a query variable
function custom_login_query_vars($vars) {
    $vars[] = 'custom_login';
    return $vars;
}
add_filter('query_vars', 'custom_login_query_vars');

// Display the form when visiting the custom page
function custom_login_template_redirect() {
    if (get_query_var('custom_login')) {
        add_filter('body_class', function($classes) {
            $classes[] = 'custom-login-page';
            return $classes;
        });
        echo do_shortcode('[custom_login_form]');
        exit;
    }
}
add_action('template_redirect', 'custom_login_template_redirect');

// Enqueue the plugin's stylesheet
function custom_login_enqueue_styles() {
    wp_enqueue_style('custom-login-style', plugin_dir_url(__FILE__) . 'assets/css/style.css');
}
add_action('wp_enqueue_scripts', 'custom_login_enqueue_styles');

// Add Admin Menu
function custom_login_add_admin_menu() {
    add_menu_page(
        'Custom Login Settings',             // Page title
        'Custom Login',                      // Menu title
        'manage_options',                    // Capability required
        'custom-login-settings',             // Menu slug
        'custom_login_admin_page',           // Function to display the page
        'dashicons-admin-generic',           // Icon (Dashicons icon)
        30                                    // Position in menu
    );
}

// Display Admin Page
function custom_login_admin_page() {
    ?>
    <div class="wrap">
        <h1>Custom Login Plugin Settings</h1>
        <p>Here you can manage the settings of the Custom Login Plugin. This is where form submissions can be viewed or plugin options can be adjusted.</p>
        <h2>Form Submissions</h2>
        <p>List of form submissions will be shown here...</p>
        <!-- You can implement functionality to display the form submissions here -->
    </div>
    <?php
}
