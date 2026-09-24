<?php
/**
 * Client Support & Knowledge Hub (Side B)
 */

if (!defined('ABSPATH')) {
    exit;
}

class Jocsoft_Support_Portal {

    public static function init() {
        add_shortcode('jocsoft_client_portal', array(__CLASS__, 'render_support_portal_shortcode'));
    }

    public static function render_support_portal_shortcode($atts) {
        wp_enqueue_style('jocsoft-hub-styles');
        wp_enqueue_script('jocsoft-support-js');

        ob_start();
        include JOCSOFT_HUB_PATH . 'templates/client-portal.php';
        return ob_get_clean();
    }

    /**
     * Get verified sample knowledge base articles for instant out-of-the-box support
     */
    public static function get_default_kb_articles() {
        return array(
            array(
                'title' => 'SomaSmart LMS: How to Bulk Enroll Students via CSV',
                'category' => 'E-Learning & LMS',
                'excerpt' => 'Step-by-step procedure for preparing and uploading class rosters to SomaSmart.',
                'link' => '#',
                'type' => 'Tutorial'
            ),
            array(
                'title' => 'SACCO ERP: Reconciling Daily M-Pesa C2B / B2C Transactions',
                'category' => 'Dynamics Navision SACCO',
                'excerpt' => 'Audit steps for clearing automated loan repayments and member deposits against Safaricom statements.',
                'link' => '#',
                'type' => 'Admin Guide'
            ),
            array(
                'title' => 'S-Master: Generating End-of-Term Student Report Cards & Fee Balances',
                'category' => 'School Management',
                'excerpt' => 'Configure grading scales, compile exam averages, and trigger automated parent SMS alerts.',
                'link' => '#',
                'type' => 'Manual'
            ),
            array(
                'title' => 'MedStar HIS: Troubleshooting Outpatient Prescription Routing',
                'category' => 'Healthcare Systems',
                'excerpt' => 'Verifying doctor workbench orders and syncing with pharmacy dispensary inventory.',
                'link' => '#',
                'type' => 'Troubleshooting'
            ),
            array(
                'title' => 'Koha ILS: Barcode Scanner Calibration & Patron Check-out Procedure',
                'category' => 'Library Management',
                'excerpt' => 'Hardware setup and circulation rules for loan renewals and book return security.',
                'link' => '#',
                'type' => 'Hardware Setup'
            ),
            array(
                'title' => 'Annual Maintenance Contracts (AMC) & Cloud Backup Best Practices',
                'category' => 'Infrastructure & Support',
                'excerpt' => 'Understanding your institution SLA response times, offsite data replication, and scheduled updates.',
                'link' => '#',
                'type' => 'SLA Policy'
            )
        );
    }
}
