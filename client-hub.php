<?php
/**
 * Plugin Name: Jocsoft Client Experience Hub
 * Plugin URI: https://jocsoft.net
 * Description: Unified Enterprise Solution Discovery Engine, Grounded AI Solution Advisor, and Client Support Portal for Jocsoft Solutions.
 * Version: 1.0.0
 * Author: Jocsoft Solutions Limited
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
        self::maybe_seed_defaults();
    }

    public function activate() {
        Jocsoft_CPT::register_post_types();
        Jocsoft_CPT::register_taxonomies();
        self::seed_defaults(true);
        flush_rewrite_rules();
    }

    public static function maybe_seed_defaults() {
        if (!get_option('jocsoft_hub_seeded_v1', false)) {
            self::seed_defaults();
            update_option('jocsoft_hub_seeded_v1', true);
        }
    }

    public static function seed_defaults($force = false) {
        // 1. Create Find My Solution page if not exists
        $discovery_page = get_page_by_path('find-my-solution');
        if (!$discovery_page) {
            wp_insert_post(array(
                'post_title'     => 'Find My Solution',
                'post_name'      => 'find-my-solution',
                'post_content'   => '[jocsoft_discovery_hub]',
                'post_status'    => 'publish',
                'post_type'      => 'page',
                'comment_status' => 'closed'
            ));
        }

        // 2. Create Client Support Hub page if not exists
        $portal_page = get_page_by_path('client-portal');
        if (!$portal_page) {
            wp_insert_post(array(
                'post_title'     => 'Client Support Hub',
                'post_name'      => 'client-portal',
                'post_content'   => '[jocsoft_client_portal]',
                'post_status'    => 'publish',
                'post_type'      => 'page',
                'comment_status' => 'closed'
            ));
        }

        // 3. Seed KB articles
        $kb_count = wp_count_posts('jocsoft_kb')->publish;
        if ($kb_count == 0 || $force) {
            $articles = Jocsoft_Support_Portal::get_default_kb_articles();
            foreach ($articles as $art) {
                $kb_id = wp_insert_post(array(
                    'post_title'   => $art['title'],
                    'post_content' => $art['excerpt'] . "\n\n<h3>Standard Operating Procedure</h3>\n<p>Follow the standard Jocsoft operational manual for system configuration and user management.</p>",
                    'post_excerpt' => $art['excerpt'],
                    'post_status'  => 'publish',
                    'post_type'    => 'jocsoft_kb'
                ));
                if ($kb_id && !is_wp_error($kb_id)) {
                    wp_set_object_terms($kb_id, $art['category'], 'jocsoft_system');
                }
            }
        }

        // 4. Update Header & Footer Template Parts in database
        $footer_content = '<div class="wp-block-group alignwide" style="border-top:1px solid #e2e8f0;padding-top:48px;padding-bottom:36px;padding-left:32px;padding-right:32px;background-color:#0f172a;color:#ffffff;">
	<div class="wp-block-columns alignwide" style="display:flex;flex-wrap:wrap;gap:32px;justify-content:space-between;">
		<div class="wp-block-column" style="flex:1 1 360px;box-sizing:border-box;">
			<h3 style="font-size:1.15rem;font-weight:800;color:#ffffff;text-transform:uppercase;margin:0 0 8px 0;letter-spacing:-0.01em;">Jocsoft Solutions Limited</h3>
			<p style="color:#94a3b8;font-size:0.875rem;line-height:1.6;margin:0 0 12px 0;">Empowering Kenyan SACCOs, TVET colleges, and enterprises with robust management systems, e-learning ecosystems, and 24/7 centralized support.</p>
			<p style="color:#cbd5e1;font-size:0.8rem;margin:0;">Court 7731, Muchai Drive &bull; Nairobi, Kenya</p>
		</div>
		<div class="wp-block-column" style="flex:0 1 180px;box-sizing:border-box;">
			<h4 style="font-size:0.75rem;font-weight:700;color:#38bdf8;text-transform:uppercase;letter-spacing:1px;margin:0 0 12px 0;">Navigation</h4>
			<p style="font-size:0.875rem;line-height:2;margin:0;">
				<a href="' . esc_url(home_url('/')) . '" style="color:#cbd5e1;text-decoration:none;">Home</a><br>
				<a href="' . esc_url(home_url('/find-my-solution/')) . '" style="color:#cbd5e1;text-decoration:none;">Find My Solution</a><br>
				<a href="' . esc_url(home_url('/client-portal/')) . '" style="color:#cbd5e1;text-decoration:none;">Client Support Hub</a><br>
				<a href="' . esc_url(home_url('/projects/')) . '" style="color:#cbd5e1;text-decoration:none;">Software Solutions</a><br>
				<a href="' . esc_url(home_url('/about/')) . '" style="color:#cbd5e1;text-decoration:none;">About Us</a><br>
				<a href="' . esc_url(home_url('/contact/')) . '" style="color:#cbd5e1;text-decoration:none;">Contact</a>
			</p>
		</div>
		<div class="wp-block-column" style="flex:0 1 240px;box-sizing:border-box;">
			<h4 style="font-size:0.75rem;font-weight:700;color:#38bdf8;text-transform:uppercase;letter-spacing:1px;margin:0 0 12px 0;">Enterprise Inquiries</h4>
			<p style="font-size:0.875rem;line-height:1.8;color:#94a3b8;margin:0;">
				Phone: <a href="tel:+254732447447" style="color:#38bdf8;text-decoration:none;">(+254) 732 447 447</a><br>
				Email: <a href="mailto:info@jocsoft.net" style="color:#38bdf8;text-decoration:none;">info@jocsoft.net</a><br>
				Website: <a href="https://jocsoft.net" target="_blank" rel="noopener" style="color:#cbd5e1;text-decoration:none;">jocsoft.net</a>
			</p>
		</div>
	</div>
	<div style="border-top:1px solid #1e293b;margin-top:36px;padding-top:20px;text-align:center;">
		<p style="color:#64748b;font-size:0.8rem;margin:0;">&copy; ' . date('Y') . ' Jocsoft Solutions Limited. All rights reserved. Future Solutions Today.</p>
	</div>
</div>';

        $header_content = '<div class="wp-block-group alignwide" style="padding-top:16px;padding-bottom:16px;padding-left:24px;padding-right:24px;border-bottom:1px solid #e2e8f0;background-color:#ffffff;">
	<style id="jocsoft-corporate-style">
	  @import url(\'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap\');
	  style, script, template, noscript { display: none !important; }
	  :root {
	    --wp--preset--color--contrast: #0f172a !important;
	    --wp--preset--color--contrast-2: #1e293b !important;
	    --wp--preset--color--contrast-3: #334155 !important;
	    --wp--preset--color--base: #ffffff !important;
	    --wp--preset--color--base-2: #f8fafc !important;
	    --wp--preset--color--accent: #0d3b66 !important;
	    --wp--preset--color--accent-2: #ea580c !important;
	    --wp--preset--color--accent-3: #1e3a8a !important;
	  }
	  html, body, .wp-site-blocks, header, footer, .wp-block-template-part {
	    background-color: #f8fafc !important;
	    color: #334155 !important;
	    font-family: \'Plus Jakarta Sans\', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
	    -webkit-font-smoothing: antialiased;
	    margin: 0;
	    padding: 0;
	  }
	  .wp-site-blocks, .entry-content, .wp-block-post-content, main {
	    max-width: 1140px !important;
	    width: 100% !important;
	    margin-left: auto !important;
	    margin-right: auto !important;
	    padding-left: 20px !important;
	    padding-right: 20px !important;
	    box-sizing: border-box !important;
	  }
	  .wp-block-post-content > * {
	    max-width: 100% !important;
	    width: 100% !important;
	    margin-left: auto !important;
	    margin-right: auto !important;
	    box-sizing: border-box !important;
	  }
	  h1, h2, h3, h4, h5, h6, .wp-block-heading, .wp-block-post-title, .wp-block-site-title {
	    font-family: \'Plus Jakarta Sans\', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
	    font-weight: 800 !important;
	    color: #0f172a !important;
	    letter-spacing: -0.02em !important;
	  }
	  p, li, span, label, input, textarea, a, button {
	    font-family: \'Plus Jakarta Sans\', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
	  }
	  .wp-block-navigation a {
	    color: #475569 !important;
	    font-size: 0.9rem !important;
	    font-weight: 600 !important;
	    text-decoration: none !important;
	    transition: color 0.15s ease !important;
	  }
	  .wp-block-navigation a:hover {
	    color: #0d3b66 !important;
	  }
	</style>
	<div class="wp-block-group alignwide" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;">
		<div class="wp-block-group" style="display:flex;align-items:center;gap:10px;">
			<a href="' . esc_url(home_url('/')) . '" style="text-decoration:none;display:inline-flex;align-items:center;gap:8px;">
				<span style="color:#0d3b66;font-weight:900;font-size:1.25rem;letter-spacing:-0.02em;text-transform:uppercase;">JOCSOFT SOLUTIONS</span>
			</a>
			<span style="color:#cbd5e1;font-size:1rem;font-weight:400;">|</span>
			<span style="color:#64748b;font-size:0.8rem;font-weight:600;letter-spacing:0.5px;text-transform:uppercase;">Future Solutions Today</span>
		</div>
		<div class="wp-block-group" style="display:flex;align-items:center;gap:20px;">
			<!-- wp:navigation {"layout":{"type":"flex","justifyContent":"right","orientation":"horizontal"},"style":{"spacing":{"margin":{"top":"0"},"blockGap":"20px"}}} /-->
			<a href="' . esc_url(home_url('/find-my-solution/')) . '" style="background-color:#ea580c;color:#ffffff;border-radius:6px;font-weight:700;font-size:0.85rem;padding:9px 18px;text-decoration:none;display:inline-block;box-shadow:0 2px 6px rgba(234,88,12,0.25);">
				Find My Solution &rarr;
			</a>
		</div>
	</div>
</div>';

        $all_parts = get_posts(array(
            'post_type'      => 'wp_template_part',
            'post_status'    => array('publish', 'inherit'),
            'posts_per_page' => -1
        ));

        foreach ($all_parts as $part) {
            if ($part->post_name === 'footer') {
                wp_update_post(array(
                    'ID'           => $part->ID,
                    'post_content' => $footer_content
                ));
            } elseif ($part->post_name === 'header') {
                wp_update_post(array(
                    'ID'           => $part->ID,
                    'post_content' => $header_content
                ));
            }
        }
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
