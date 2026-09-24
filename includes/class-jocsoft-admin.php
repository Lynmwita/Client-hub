<?php
/**
 * Admin Panel & Lead Dossier Management
 */

if (!defined('ABSPATH')) {
    exit;
}

class Jocsoft_Admin {

    public static function init() {
        add_action('admin_menu', array(__CLASS__, 'register_admin_menus'));
        add_action('admin_init', array(__CLASS__, 'register_settings'));
        add_action('add_meta_boxes', array(__CLASS__, 'add_lead_meta_boxes'));
        add_action('add_meta_boxes', array(__CLASS__, 'add_ticket_meta_boxes'));
    }

    public static function register_admin_menus() {
        add_menu_page(
            'Jocsoft Hub',
            'Jocsoft Hub',
            'manage_options',
            'jocsoft-experience-hub',
            array(__CLASS__, 'render_dashboard_page'),
            'dashicons-cloud',
            25
        );

        add_submenu_page(
            'jocsoft-experience-hub',
            'Hub Settings',
            'Settings',
            'manage_options',
            'jocsoft-hub-settings',
            array(__CLASS__, 'render_settings_page')
        );
    }

    public static function register_settings() {
        register_setting('jocsoft_hub_settings_group', 'jocsoft_ai_api_key');
        register_setting('jocsoft_hub_settings_group', 'jocsoft_sales_email');
    }

    public static function render_dashboard_page() {
        $lead_count = wp_count_posts('jocsoft_lead')->publish;
        $ticket_count = wp_count_posts('jocsoft_ticket')->publish;

        $recent_leads = get_posts(array(
            'post_type'      => 'jocsoft_lead',
            'posts_per_page' => 5,
            'post_status'    => 'publish'
        ));
        ?>
        <div class="wrap jocsoft-admin-wrap">
            <h1 class="jocsoft-admin-title">Jocsoft Client Experience Hub</h1>
            <p class="jocsoft-admin-subtitle">Real-time control center for solution discovery, qualified leads, and institutional support.</p>

            <div class="jocsoft-stats-grid">
                <div class="jocsoft-stat-card">
                    <div class="stat-label">Active Pre-Qualified Leads</div>
                    <div class="stat-value"><?php echo esc_html($lead_count); ?></div>
                    <div class="stat-help">Prospects awaiting sales engagement</div>
                </div>
                <div class="jocsoft-stat-card">
                    <div class="stat-label">Open Support Tickets</div>
                    <div class="stat-value"><?php echo esc_html($ticket_count); ?></div>
                    <div class="stat-help">Institutional SLA tracking</div>
                </div>
                <div class="jocsoft-stat-card">
                    <div class="stat-label">Verified Systems</div>
                    <div class="stat-value">6</div>
                    <div class="stat-help">SomaSmart, S-Master, Navision, MedStar, Koha, Custom Apps</div>
                </div>
            </div>

            <div class="jocsoft-recent-section">
                <h2>Recent Pre-Qualified Leads</h2>
                <?php if (empty($recent_leads)): ?>
                    <p>No leads captured yet. Submissions from the <code>[jocsoft_discovery_hub]</code> form will appear here automatically.</p>
                <?php else: ?>
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th>Organization</th>
                                <th>Contact Person</th>
                                <th>Recommended System</th>
                                <th>Stage</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_leads as $lead): 
                                $org = get_post_meta($lead->ID, '_jocsoft_org_name', true);
                                $contact = get_post_meta($lead->ID, '_jocsoft_full_name', true);
                                $system = get_post_meta($lead->ID, '_jocsoft_primary_product', true);
                                $stage = get_post_meta($lead->ID, '_jocsoft_lead_stage', true);
                            ?>
                                <tr>
                                    <td><strong><?php echo esc_html($org ?: $lead->post_title); ?></strong></td>
                                    <td><?php echo esc_html($contact); ?></td>
                                    <td><?php echo esc_html($system); ?></td>
                                    <td><span class="jocsoft-badge"><?php echo esc_html($stage); ?></span></td>
                                    <td><?php echo esc_html(get_the_date('M j, Y', $lead->ID)); ?></td>
                                    <td><a href="<?php echo get_edit_post_link($lead->ID); ?>" class="button button-small">View Dossier</a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    public static function render_settings_page() {
        ?>
        <div class="wrap jocsoft-admin-wrap">
            <h1>Jocsoft Hub Settings</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('jocsoft_hub_settings_group');
                do_settings_sections('jocsoft_hub_settings_group');
                ?>
                <table class="form-table">
                    <tr>
                        <th scope="row">Google AI API Key (Optional)</th>
                        <td>
                            <input type="password" name="jocsoft_ai_api_key" value="<?php echo esc_attr(get_option('jocsoft_ai_api_key')); ?>" class="regular-text" />
                            <p class="description">Used for dynamic conversational scoping in the AI Advisor. Falls back to catalog rules if omitted.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Sales Team Notification Email</th>
                        <td>
                            <input type="email" name="jocsoft_sales_email" value="<?php echo esc_attr(get_option('jocsoft_sales_email', 'sales@jocsoft.net')); ?>" class="regular-text" />
                        </td>
                    </tr>
                </table>
                <?php submit_button('Save Settings'); ?>
            </form>
        </div>
        <?php
    }

    public static function add_lead_meta_boxes() {
        add_meta_box('jocsoft_lead_dossier_box', 'Actionable Sales Dossier', array(__CLASS__, 'render_lead_dossier_metabox'), 'jocsoft_lead', 'normal', 'high');
    }

    public static function render_lead_dossier_metabox($post) {
        $org        = get_post_meta($post->ID, '_jocsoft_org_name', true);
        $contact    = get_post_meta($post->ID, '_jocsoft_full_name', true);
        $email      = get_post_meta($post->ID, '_jocsoft_email', true);
        $phone      = get_post_meta($post->ID, '_jocsoft_phone', true);
        $job_title  = get_post_meta($post->ID, '_jocsoft_job_title', true);
        $product    = get_post_meta($post->ID, '_jocsoft_primary_product', true);
        $briefing   = get_post_meta($post->ID, '_jocsoft_ai_briefing', true);
        $chat_logs  = get_post_meta($post->ID, '_jocsoft_ai_dialog_log', true);
        ?>
        <div class="jocsoft-dossier-card">
            <div class="dossier-header">
                <h3><?php echo esc_html($org ?: 'Organization Profile'); ?></h3>
                <span class="dossier-ref">Lead #<?php echo esc_html($post->ID + 1000); ?></span>
            </div>
            <div class="dossier-grid">
                <div><strong>Contact:</strong> <?php echo esc_html($contact); ?> (<?php echo esc_html($job_title ?: 'Representative'); ?>)</div>
                <div><strong>Email:</strong> <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a></div>
                <div><strong>Phone / WhatsApp:</strong> <a href="tel:<?php echo esc_attr($phone); ?>"><?php echo esc_html($phone); ?></a></div>
                <div><strong>Recommended System:</strong> <?php echo esc_html($product); ?></div>
            </div>

            <div class="dossier-briefing">
                <h4>AI Context & Requirements Synthesis</h4>
                <p><?php echo esc_html($briefing ?: 'No briefing available.'); ?></p>
            </div>

            <?php if (!empty($chat_logs) && is_array($chat_logs)): ?>
                <div class="dossier-chat-history">
                    <h4>Interactive Scoping Transcript</h4>
                    <ul>
                        <?php foreach ($chat_logs as $entry): ?>
                            <li>
                                <strong>Prospect:</strong> <?php echo esc_html($entry['user']); ?><br>
                                <em>Advisor:</em> <?php echo esc_html($entry['advisor']); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }

    public static function add_ticket_meta_boxes() {
        add_meta_box('jocsoft_ticket_info_box', 'Ticket Status & SLA', array(__CLASS__, 'render_ticket_metabox'), 'jocsoft_ticket', 'side', 'high');
    }

    public static function render_ticket_metabox($post) {
        $status = get_post_meta($post->ID, '_jocsoft_ticket_status', true) ?: 'Submitted';
        $urgency = get_post_meta($post->ID, '_jocsoft_ticket_urgency', true) ?: 'Medium';
        $assigned = get_post_meta($post->ID, '_jocsoft_ticket_assigned_to', true) ?: 'Unassigned';
        $client = get_post_meta($post->ID, '_jocsoft_ticket_client', true);
        ?>
        <p><strong>Client:</strong> <?php echo esc_html($client); ?></p>
        <p><strong>Urgency:</strong> <span class="jocsoft-badge urgency-<?php echo esc_attr(strtolower($urgency)); ?>"><?php echo esc_html($urgency); ?></span></p>
        <p><strong>Current Status:</strong> <strong><?php echo esc_html($status); ?></strong></p>
        <p><strong>Assigned Engineer:</strong> <?php echo esc_html($assigned); ?></p>
        <?php
    }
}
