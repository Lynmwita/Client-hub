<?php
/**
 * Jocsoft Solution Discovery Engine
 */

if (!defined('ABSPATH')) {
    exit;
}

class Jocsoft_Discovery {

    public static function init() {
        add_shortcode('jocsoft_discovery_hub', array(__CLASS__, 'render_discovery_hub_shortcode'));
    }

    public static function render_discovery_hub_shortcode($atts) {
        wp_enqueue_style('jocsoft-hub-styles');
        wp_enqueue_script('jocsoft-discovery-js');

        ob_start();
        include JOCSOFT_HUB_PATH . 'templates/solution-finder.php';
        return ob_get_clean();
    }

    /**
     * Map survey choices to tailored Jocsoft Product Snapshot
     */
    public static function generate_solution_snapshot($data) {
        $org_type  = isset($data['org_type']) ? sanitize_text_field($data['org_type']) : '';
        $area      = isset($data['area_of_interest']) ? sanitize_text_field($data['area_of_interest']) : '';
        $challenge = isset($data['main_challenge']) ? sanitize_text_field($data['main_challenge']) : '';
        $org_name  = !empty($data['org_name']) ? sanitize_text_field($data['org_name']) : 'Your Organization';

        $recommendation = array(
            'primary_product'  => 'Jocsoft Custom Enterprise Solutions',
            'product_tagline'  => 'Tailored digital systems and automated operational workflows.',
            'modules'          => array(),
            'business_impact'  => 'Eliminates manual bottlenecks and provides structured reporting.',
            'next_step'        => 'Schedule a tailored architecture session with Jocsoft technical engineers.'
        );

        if ($org_type === 'school' || $org_type === 'university') {
            if ($area === 'elearning' || $challenge === 'managing_learners') {
                $recommendation['primary_product'] = 'SomaSmart E-Learning & LMS Platform';
                $recommendation['product_tagline'] = 'Centralized digital learning, interactive coursework, and automated student progress analytics.';
                $recommendation['modules'] = array(
                    'Online Curriculum & Course Delivery (Video, PDF, Assignments)',
                    'Automated Student Gradebooks & Performance Tracking',
                    'M-Pesa Integrated Tuition & Course Fees Processing',
                    'Parent & Student Self-Service Portals'
                );
                $recommendation['business_impact'] = 'Replaces manual WhatsApp/Excel communication with structured learning management.';
            } else {
                $recommendation['primary_product'] = 'S-Master School Management & Koha Library Suite';
                $recommendation['product_tagline'] = 'Comprehensive student information, fees collection, instant SMS alerts, and cataloging.';
                $recommendation['modules'] = array(
                    'Student Admission & Records Management',
                    'Fee Tracking & Automated SMS Payment Receipts',
                    'Koha Integrated Library & RFID Asset Tracking',
                    'Staff & Departmental Scheduling'
                );
                $recommendation['business_impact'] = 'Streamlines institutional administration and fee compliance across all departments.';
            }
        } elseif ($org_type === 'sacco' || $area === 'sacco_erp') {
            $recommendation['primary_product'] = 'Microsoft Dynamics Navision SACCO & ERP Suite';
            $recommendation['product_tagline'] = 'Fully compliant Back-Office (BOSA) & Front-Office (FOSA) SACCO financial management.';
            $recommendation['modules'] = array(
                'Member Registry & Share Capital Tracking',
                'Automated Loan Processing, Appraisals & Repayments',
                'Core Accounting, General Ledger & SASRA Reporting',
                'Integrated M-Pesa & Bank Reconciliations'
            );
            $recommendation['business_impact'] = 'Reduces loan approval turnaround times and enforces strict regulatory compliance.';
        } elseif ($org_type === 'hospital') {
            $recommendation['primary_product'] = 'MedStar Hospital Information & Management System';
            $recommendation['product_tagline'] = 'End-to-end clinical workflow, patient records, pharmacy inventory, and billing.';
            $recommendation['modules'] = array(
                'Centralized Outpatient (OPD) & Inpatient (IPD) Portals',
                'Electronic Medical Records (EMR) & Doctor Workbench',
                'Pharmacy Point of Sale & Lab Analyzer Integration',
                'Insurance / TPA Claims Processing & Billing'
            );
            $recommendation['business_impact'] = 'Digitizes patient journey from reception to triage, doctor, lab, and pharmacy.';
        } else {
            $recommendation['primary_product'] = 'Jocsoft Custom Business System & Digital Portal';
            $recommendation['product_tagline'] = 'Tailored web portals, mobile applications, and backend workflow automation.';
            $recommendation['modules'] = array(
                'Custom Database Architecture & Secure Role-Based Access',
                'Mobile Applications (iOS & Android) with Offline Support',
                'Third-Party API & Payment Gateway Integrations (M-Pesa, Banks)',
                'ISO 9001 / ISO 27001 Data Security & Compliance'
            );
            $recommendation['business_impact'] = 'Replaces fragmented spreadsheets with automated, centralized enterprise operations.';
        }

        return $recommendation;
    }
}
