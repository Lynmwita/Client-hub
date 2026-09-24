<?php
/**
 * Custom Post Types and Taxonomies for Jocsoft Client Hub
 */

if (!defined('ABSPATH')) {
    exit;
}

class Jocsoft_CPT {

    public static function init() {
        add_action('init', array(__CLASS__, 'register_post_types'));
        add_action('init', array(__CLASS__, 'register_taxonomies'));
    }

    public static function register_post_types() {
        // 1. Qualified Leads Post Type
        $lead_labels = array(
            'name'               => _x('Pre-Qualified Leads', 'post type general name', 'jocsoft-client-hub'),
            'singular_name'      => _x('Lead Dossier', 'post type singular name', 'jocsoft-client-hub'),
            'menu_name'          => _x('Hub Leads', 'admin menu', 'jocsoft-client-hub'),
            'name_admin_bar'     => _x('Hub Lead', 'add new on admin bar', 'jocsoft-client-hub'),
            'add_new'            => _x('Add New Lead', 'lead', 'jocsoft-client-hub'),
            'add_new_item'       => __('Add New Lead Dossier', 'jocsoft-client-hub'),
            'new_item'           => __('New Lead', 'jocsoft-client-hub'),
            'edit_item'          => __('Edit Lead Dossier', 'jocsoft-client-hub'),
            'view_item'          => __('View Lead Dossier', 'jocsoft-client-hub'),
            'all_items'          => __('All Leads & Dossiers', 'jocsoft-client-hub'),
            'search_items'       => __('Search Leads', 'jocsoft-client-hub'),
            'not_found'          => __('No leads found.', 'jocsoft-client-hub'),
            'not_found_in_trash' => __('No leads found in Trash.', 'jocsoft-client-hub')
        );

        $lead_args = array(
            'labels'             => $lead_labels,
            'public'             => false,
            'show_ui'            => true,
            'show_in_menu'       => 'jocsoft-experience-hub',
            'query_var'          => true,
            'rewrite'            => false,
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array('title', 'custom-fields')
        );
        register_post_type('jocsoft_lead', $lead_args);

        // 2. Support Tickets Post Type
        $ticket_labels = array(
            'name'               => _x('Support Tickets', 'post type general name', 'jocsoft-client-hub'),
            'singular_name'      => _x('Support Ticket', 'post type singular name', 'jocsoft-client-hub'),
            'menu_name'          => _x('Tickets & SLAs', 'admin menu', 'jocsoft-client-hub'),
            'add_new'            => _x('New Ticket', 'ticket', 'jocsoft-client-hub'),
            'add_new_item'       => __('Add New Support Ticket', 'jocsoft-client-hub'),
            'edit_item'          => __('Edit Ticket', 'jocsoft-client-hub'),
            'view_item'          => __('View Ticket', 'jocsoft-client-hub'),
            'all_items'          => __('All Support Tickets', 'jocsoft-client-hub'),
            'search_items'       => __('Search Tickets', 'jocsoft-client-hub'),
            'not_found'          => __('No tickets found.', 'jocsoft-client-hub')
        );

        $ticket_args = array(
            'labels'             => $ticket_labels,
            'public'             => false,
            'show_ui'            => true,
            'show_in_menu'       => 'jocsoft-experience-hub',
            'query_var'          => true,
            'rewrite'            => false,
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'supports'           => array('title', 'editor', 'comments', 'author', 'custom-fields')
        );
        register_post_type('jocsoft_ticket', $ticket_args);

        // 3. Knowledge Base Post Type
        $kb_labels = array(
            'name'               => _x('Knowledge Base', 'post type general name', 'jocsoft-client-hub'),
            'singular_name'      => _x('KB Article', 'post type singular name', 'jocsoft-client-hub'),
            'menu_name'          => _x('Knowledge Base', 'admin menu', 'jocsoft-client-hub'),
            'add_new'            => _x('Add Article', 'kb', 'jocsoft-client-hub'),
            'add_new_item'       => __('Add Knowledge Base Article', 'jocsoft-client-hub'),
            'edit_item'          => __('Edit Article', 'jocsoft-client-hub'),
            'view_item'          => __('View Article', 'jocsoft-client-hub'),
            'all_items'          => __('All KB Articles', 'jocsoft-client-hub')
        );

        $kb_args = array(
            'labels'             => $kb_labels,
            'public'             => true,
            'show_ui'            => true,
            'show_in_menu'       => 'jocsoft-experience-hub',
            'query_var'          => true,
            'rewrite'            => array('slug' => 'knowledge-base'),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'supports'           => array('title', 'editor', 'thumbnail', 'excerpt')
        );
        register_post_type('jocsoft_kb', $kb_args);
    }

    public static function register_taxonomies() {
        // System Category (SomaSmart, Navision SACCO ERP, MedStar HIS, S-Master, Koha, Custom Web/Mobile)
        $system_labels = array(
            'name'              => _x('Jocsoft Systems', 'taxonomy general name', 'jocsoft-client-hub'),
            'singular_name'     => _x('System', 'taxonomy singular name', 'jocsoft-client-hub'),
            'search_items'      => __('Search Systems', 'jocsoft-client-hub'),
            'all_items'         => __('All Systems', 'jocsoft-client-hub'),
            'edit_item'         => __('Edit System', 'jocsoft-client-hub'),
            'update_item'       => __('Update System', 'jocsoft-client-hub'),
            'add_new_item'      => __('Add New System', 'jocsoft-client-hub'),
            'menu_name'         => __('Systems & Products', 'jocsoft-client-hub'),
        );

        register_taxonomy('jocsoft_system', array('jocsoft_ticket', 'jocsoft_kb'), array(
            'hierarchical'      => true,
            'labels'            => $system_labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'system'),
        ));
    }
}
