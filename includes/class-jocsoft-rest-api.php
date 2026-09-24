<?php
/**
 * REST API Endpoints for Jocsoft Client Hub
 */

if (!defined('ABSPATH')) {
    exit;
}

class Jocsoft_REST_API {

    public static function init() {
        add_action('rest_api_init', array(__CLASS__, 'register_routes'));
    }

    public static function register_routes() {
        // Discovery Submit & Lead Snapshot
        register_rest_route('jocsoft/v1', '/discovery/submit', array(
            'methods'             => 'POST',
            'callback'            => array(__CLASS__, 'handle_discovery_submit'),
            'permission_callback' => '__return_true'
        ));

        // AI Advisor Chat
        register_rest_route('jocsoft/v1', '/ai-advisor/chat', array(
            'methods'             => 'POST',
            'callback'            => array(__CLASS__, 'handle_ai_chat'),
            'permission_callback' => '__return_true'
        ));

        // Support Tickets
        register_rest_route('jocsoft/v1', '/tickets/create', array(
            'methods'             => 'POST',
            'callback'            => array(__CLASS__, 'handle_ticket_create'),
            'permission_callback' => '__return_true'
        ));

        register_rest_route('jocsoft/v1', '/tickets/list', array(
            'methods'             => 'GET',
            'callback'            => array(__CLASS__, 'handle_ticket_list'),
            'permission_callback' => '__return_true'
        ));

        // Knowledge Base
        register_rest_route('jocsoft/v1', '/kb/search', array(
            'methods'             => 'GET',
            'callback'            => array(__CLASS__, 'handle_kb_search'),
            'permission_callback' => '__return_true'
        ));
    }

    /**
     * Handle Discovery Questionnaire & Lead Unlock Form Submission
     */
    public static function handle_discovery_submit($request) {
        $params = $request->get_json_params();

        $org_type  = isset($params['org_type']) ? sanitize_text_field($params['org_type']) : '';
        $area      = isset($params['area_of_interest']) ? sanitize_text_field($params['area_of_interest']) : '';
        $challenge = isset($params['main_challenge']) ? sanitize_text_field($params['main_challenge']) : '';
        $full_name = isset($params['full_name']) ? sanitize_text_field($params['full_name']) : '';
        $org_name  = isset($params['organization']) ? sanitize_text_field($params['organization']) : '';
        $email     = isset($params['email']) ? sanitize_email($params['email']) : '';
        $phone     = isset($params['phone']) ? sanitize_text_field($params['phone']) : '';
        $job_title = isset($params['job_title']) ? sanitize_text_field($params['job_title']) : '';

        // Generate tailored snapshot
        $snapshot = Jocsoft_Discovery::generate_solution_snapshot(array(
            'org_type'         => $org_type,
            'area_of_interest' => $area,
            'main_challenge'   => $challenge,
            'org_name'         => $org_name
        ));

        // If contact details are provided, save as Lead Dossier in WordPress
        $lead_id = 0;
        if (!empty($full_name) && !empty($email)) {
            $post_title = $org_name . ' - ' . $full_name . ' (' . $snapshot['primary_product'] . ')';
            $lead_id = wp_insert_post(array(
                'post_type'   => 'jocsoft_lead',
                'post_title'  => $post_title,
                'post_status' => 'publish'
            ));

            if ($lead_id && !is_wp_error($lead_id)) {
                update_post_meta($lead_id, '_jocsoft_full_name', $full_name);
                update_post_meta($lead_id, '_jocsoft_org_name', $org_name);
                update_post_meta($lead_id, '_jocsoft_email', $email);
                update_post_meta($lead_id, '_jocsoft_phone', $phone);
                update_post_meta($lead_id, '_jocsoft_job_title', $job_title);
                update_post_meta($lead_id, '_jocsoft_org_type', $org_type);
                update_post_meta($lead_id, '_jocsoft_area', $area);
                update_post_meta($lead_id, '_jocsoft_challenge', $challenge);
                update_post_meta($lead_id, '_jocsoft_primary_product', $snapshot['primary_product']);
                update_post_meta($lead_id, '_jocsoft_lead_stage', 'New — Follow-up Required');

                // Generate AI Executive Briefing
                $ai_briefing = sprintf(
                    "Prospect from %s (%s). Needs: %s. Reported primary operational challenge: %s. Recommended System: %s. Contact: %s (%s, %s).",
                    $org_name,
                    $org_type,
                    $area,
                    $challenge,
                    $snapshot['primary_product'],
                    $full_name,
                    $email,
                    $phone
                );
                update_post_meta($lead_id, '_jocsoft_ai_briefing', $ai_briefing);
            }
        }

        return new WP_REST_Response(array(
            'success'   => true,
            'lead_id'   => $lead_id,
            'snapshot'  => $snapshot
        ), 200);
    }

    /**
     * Handle AI Advisor Chat Messages
     */
    public static function handle_ai_chat($request) {
        $params = $request->get_json_params();

        $message = isset($params['message']) ? sanitize_text_field($params['message']) : '';
        $history = isset($params['history']) && is_array($params['history']) ? $params['history'] : array();
        $context = isset($params['context']) && is_array($params['context']) ? $params['context'] : array();
        $lead_id = isset($params['lead_id']) ? intval($params['lead_id']) : 0;

        if (empty($message)) {
            return new WP_REST_Response(array('error' => 'Message is required'), 400);
        }

        $response = Jocsoft_AI_Advisor::process_chat($history, $message, $context);

        // If lead exists, update ongoing notes
        if ($lead_id > 0) {
            $existing_notes = get_post_meta($lead_id, '_jocsoft_ai_dialog_log', true);
            if (!is_array($existing_notes)) {
                $existing_notes = array();
            }
            $existing_notes[] = array(
                'time'    => current_time('mysql'),
                'user'    => $message,
                'advisor' => $response['reply']
            );
            update_post_meta($lead_id, '_jocsoft_ai_dialog_log', $existing_notes);
        }

        return new WP_REST_Response(array(
            'success' => true,
            'data'    => $response
        ), 200);
    }

    /**
     * Handle Ticket Creation from Client Portal
     */
    public static function handle_ticket_create($request) {
        $params = $request->get_json_params();

        $system   = isset($params['system']) ? sanitize_text_field($params['system']) : 'General';
        $urgency  = isset($params['urgency']) ? sanitize_text_field($params['urgency']) : 'Medium';
        $subject  = isset($params['subject']) ? sanitize_text_field($params['subject']) : '';
        $details  = isset($params['details']) ? sanitize_textarea_field($params['details']) : '';
        $client   = isset($params['client_name']) ? sanitize_text_field($params['client_name']) : 'Institutional Client';
        $email    = isset($params['client_email']) ? sanitize_email($params['client_email']) : '';

        if (empty($subject) || empty($details)) {
            return new WP_REST_Response(array('error' => 'Subject and details are required'), 400);
        }

        $ticket_id = wp_insert_post(array(
            'post_type'    => 'jocsoft_ticket',
            'post_title'   => '[' . strtoupper($urgency) . '] ' . $subject . ' (' . $system . ')',
            'post_content' => $details,
            'post_status'  => 'publish'
        ));

        if ($ticket_id && !is_wp_error($ticket_id)) {
            update_post_meta($ticket_id, '_jocsoft_ticket_system', $system);
            update_post_meta($ticket_id, '_jocsoft_ticket_urgency', $urgency);
            update_post_meta($ticket_id, '_jocsoft_ticket_status', 'Submitted');
            update_post_meta($ticket_id, '_jocsoft_ticket_client', $client);
            update_post_meta($ticket_id, '_jocsoft_ticket_email', $email);
            update_post_meta($ticket_id, '_jocsoft_ticket_submitted_at', current_time('mysql'));
            update_post_meta($ticket_id, '_jocsoft_ticket_assigned_to', 'Support Queue (Auto-Routing)');

            return new WP_REST_Response(array(
                'success'   => true,
                'ticket_id' => $ticket_id,
                'ticket_ref'=> 'JOC-' . (1000 + $ticket_id),
                'message'   => 'Ticket submitted successfully and routed to support engineering.'
            ), 200);
        }

        return new WP_REST_Response(array('error' => 'Failed to create ticket'), 500);
    }

    /**
     * List Tickets for Client Portal
     */
    public static function handle_ticket_list($request) {
        $tickets = get_posts(array(
            'post_type'      => 'jocsoft_ticket',
            'post_status'    => 'publish',
            'posts_per_page' => 10,
            'orderby'        => 'date',
            'order'          => 'DESC'
        ));

        $output = array();
        foreach ($tickets as $t) {
            $output[] = array(
                'id'          => $t->ID,
                'ref'         => 'JOC-' . (1000 + $t->ID),
                'subject'     => $t->post_title,
                'system'      => get_post_meta($t->ID, '_jocsoft_ticket_system', true) ?: 'SomaSmart LMS',
                'urgency'     => get_post_meta($t->ID, '_jocsoft_ticket_urgency', true) ?: 'Medium',
                'status'      => get_post_meta($t->ID, '_jocsoft_ticket_status', true) ?: 'In Progress',
                'assigned_to' => get_post_meta($t->ID, '_jocsoft_ticket_assigned_to', true) ?: 'Jocsoft Engineer',
                'created_at'  => get_the_date('M j, Y H:i', $t->ID)
            );
        }

        return new WP_REST_Response(array('tickets' => $output), 200);
    }

    /**
     * Search KB
     */
    public static function handle_kb_search($request) {
        $query = sanitize_text_field($request->get_param('q') ?: '');
        $all = Jocsoft_Support_Portal::get_default_kb_articles();

        if (empty($query)) {
            return new WP_REST_Response(array('articles' => $all), 200);
        }

        $filtered = array();
        foreach ($all as $item) {
            if (stripos($item['title'], $query) !== false || stripos($item['category'], $query) !== false || stripos($item['excerpt'], $query) !== false) {
                $filtered[] = $item;
            }
        }

        return new WP_REST_Response(array('articles' => $filtered), 200);
    }
}
