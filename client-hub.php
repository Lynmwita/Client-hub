<?php
/**
 * Plugin Name: Jocsoft Client Experience Hub
 * Plugin URI: https://jocsoft.net
 * Description: Unified Enterprise Solution Discovery Engine, Grounded AI Solution Advisor, and Client Support Portal for Jocsoft Solutions.
 * Version: 1.0.0
 * Author: Jocsoft Solutions Limited & Lynmwita
 * Author URI: https://jocsoft.net
 * Text Domain: jocsoft-client-hub
 * License: Proprietary
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

define('JOCSOFT_HUB_VERSION', '1.0.0');
define('JOCSOFT_HUB_PATH', plugin_dir_path(__FILE__));
define('JOCSOFT_HUB_URL', plugin_dir_url(__FILE__));

// Require Core Modules
require_once JOCSOFT_HUB_PATH . 'includes/class-jocsoft-cpt.php';
require_once JOCSOFT_HUB_PATH . 'includes/class-jocsoft-discovery.php';
require_once JOCSOFT_HUB_PATH . 'includes/class-jocsoft-ai-advisor.php';
require_once JOCSOFT_HUB_PATH . 'includes/class-jocsoft-support-portal.php';
require_once JOCSOFT_HUB_PATH . 'includes/class-jocsoft-rest-api.php';
require_once JOCSOFT_HUB_PATH . 'includes/class-jocsoft-admin.php';

/**
 * Main Plugin Class
 */
class Jocsoft_Client_Experience_Hub {

    private static $instance = null;

    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // Activation & Deactivation
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));

        // Init hooks
        add_action('init', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));

        // Initialize components
        Jocsoft_CPT::init();
        Jocsoft_Discovery::init();
        Jocsoft_AI_Advisor::init();
        Jocsoft_Support_Portal::init();
        Jocsoft_REST_API::init();
        Jocsoft_Admin::init();
    }

    public function init() {
        load_plugin_textdomain('jocsoft-client-hub', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }

    public function activate() {
        Jocsoft_CPT::register_post_types();
        Jocsoft_CPT::register_taxonomies();
        flush_rewrite_rules();
    }

    public function deactivate() {
        flush_rewrite_rules();
    }

    public function enqueue_frontend_assets() {
        wp_register_style(
            'jocsoft-hub-styles',
            JOCSOFT_HUB_URL . 'assets/css/hub-style.css',
            array(),
            JOCSOFT_HUB_VERSION
        );

        wp_register_script(
            'jocsoft-discovery-js',
            JOCSOFT_HUB_URL . 'assets/js/discovery-engine.js',
            array('jquery'),
            JOCSOFT_HUB_VERSION,
            true
        );

        wp_register_script(
            'jocsoft-support-js',
            JOCSOFT_HUB_URL . 'assets/js/support-portal.js',
            array('jquery'),
            JOCSOFT_HUB_VERSION,
            true
        );

        wp_localize_script('jocsoft-discovery-js', 'jocsoftHubData', array(
            'rootUrl' => esc_url_raw(rest_url('jocsoft/v1/')),
            'nonce'   => wp_create_nonce('wp_rest'),
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'company' => array(
                'name'    => 'Jocsoft Solutions Limited',
                'phone'   => '(+254) 732 447 447',
                'email'   => 'info@jocsoft.net',
                'address' => 'Court 7731, Muchai Drive, Nairobi, Kenya'
            )
        ));

        wp_localize_script('jocsoft-support-js', 'jocsoftSupportData', array(
            'rootUrl' => esc_url_raw(rest_url('jocsoft/v1/')),
            'nonce'   => wp_create_nonce('wp_rest'),
            'isUserLoggedIn' => is_user_logged_in()
        ));
    }

    public function enqueue_admin_assets($hook) {
        wp_enqueue_style(
            'jocsoft-admin-styles',
            JOCSOFT_HUB_URL . 'assets/css/admin-style.css',
            array(),
            JOCSOFT_HUB_VERSION
        );
    }
}

// Instantiate plugin
function jocsoft_client_hub_init() {
    return Jocsoft_Client_Experience_Hub::get_instance();
}
add_action('plugins_loaded', 'jocsoft_client_hub_init');
