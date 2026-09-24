<?php
/**
 * Guardrailed AI Solution Advisor for Jocsoft
 */

if (!defined('ABSPATH')) {
    exit;
}

class Jocsoft_AI_Advisor {

    public static function init() {
        // Initialization hooks if needed
    }

    /**
     * System Prompt Guardrails for Jocsoft Catalog
     */
    public static function get_system_prompt() {
        return "You are the Jocsoft Senior Solution Advisor representing Jocsoft Solutions Limited, Nairobi, Kenya.
Your role is to help prospective institutional clients (schools, TVETs, SACCOs, hospitals, and corporate organizations) articulate their operational needs and match them accurately to Jocsoft's verified catalog.

VERIFIED JOCSOFT CATALOG:
1. SomaSmart (somasmart.co.ke): E-Learning platform, video/PDF curriculum delivery, student progress tracking, assignments, M-Pesa fee payments.
2. S-Master School Management: Student information system, fee collections, automated SMS notices, academic report cards.
3. Koha Library Management: Integrated library system, MARC21/Z39.50 cataloging, RFID & barcode tracking.
4. Microsoft Dynamics Navision SACCO & ERP Suite: BOSA/FOSA operations, member management, loan appraisal & automated repayments, SASRA reports, General Ledger.
5. MedStar Hospital Management (HIS): OPD/IPD, Electronic Medical Records (EMR), Pharmacy, Laboratory, Insurance/TPA billing.
6. Custom Software & Mobile Apps: Bespoke PHP/Node/React/Flutter solutions, M-Pesa callbacks, REST APIs.
7. ISO Consulting & Corporate ICT Training: ISO 9001/27001 readiness, system administrator certifications.

RULES & BOUNDARIES:
- Maintain a professional, consultative, and reassuring tone.
- Do NOT invent fictional pricing or make legal promises.
- Explain technical capabilities in plain, accessible language without jargon.
- Ask 1 or 2 targeted clarifying questions to understand scale (e.g. number of students, members, branches) and current bottlenecks (e.g. manual spreadsheets, delayed reports).
- Conclude consultations with a clear summary and invite them to connect with Jocsoft sales engineers.";
    }

    /**
     * Process conversational message
     */
    public static function process_chat($conversation_history, $user_message, $context = array()) {
        $user_message_clean = sanitize_text_field($user_message);
        
        // Check if external AI provider is configured in WordPress options
        $api_key = get_option('jocsoft_ai_api_key', '');

        if (!empty($api_key)) {
            $response = self::call_external_ai($api_key, $conversation_history, $user_message_clean, $context);
            if ($response) {
                return $response;
            }
        }

        // High quality intelligent grounded rule-based engine fallback
        return self::grounded_catalog_response($user_message_clean, $context);
    }

    /**
     * Grounded catalog response matching
     */
    private static function grounded_catalog_response($message, $context) {
        $msg_lower = strtolower($message);
        $org_type  = isset($context['org_type']) ? $context['org_type'] : '';
        $org_name  = !empty($context['org_name']) ? $context['org_name'] : 'your institution';

        // Check topics
        if (strpos($msg_lower, 'student') !== false || strpos($msg_lower, 'learner') !== false || strpos($msg_lower, 'course') !== false || strpos($msg_lower, 'school') !== false || strpos($msg_lower, 'college') !== false || $org_type === 'school') {
            return array(
                'reply' => "For " . esc_html($org_name) . ", Jocsoft offers SomaSmart (for online course delivery and student progress monitoring) integrated with S-Master (for student records, tuition fee tracking, and instant SMS alerts). Could you tell me roughly how many active learners or students you are planning to support?",
                'suggested_options' => array('Under 500 learners', '500 - 2,000 learners', '2,000+ learners', 'Need M-Pesa integration')
            );
        }

        if (strpos($msg_lower, 'sacco') !== false || strpos($msg_lower, 'loan') !== false || strpos($msg_lower, 'member') !== false || strpos($msg_lower, 'fosa') !== false || strpos($msg_lower, 'bosa') !== false || $org_type === 'sacco') {
            return array(
                'reply' => "Our Microsoft Dynamics Navision SACCO Suite handles full Front Office (FOSA) and Back Office (BOSA) operations, automated loan appraisal, SASRA-compliant reporting, and M-Pesa payment gateways. What is your primary priority right now: streamlining loan approvals or automating financial reporting?",
                'suggested_options' => array('Loan appraisals & repayments', 'Member registration & shares', 'SASRA compliance & reporting', 'Mobile banking integration')
            );
        }

        if (strpos($msg_lower, 'hospital') !== false || strpos($msg_lower, 'patient') !== false || strpos($msg_lower, 'clinic') !== false || strpos($msg_lower, 'doctor') !== false || $org_type === 'hospital') {
            return array(
                'reply' => "Jocsoft's MedStar Hospital Management System connects your entire facility: Outpatient triage, EMR doctor notes, pharmacy stock control, laboratory analyzers, and insurance claims. Which department currently experiences the most operational delay?",
                'suggested_options' => array('Outpatient & queue management', 'Pharmacy inventory & billing', 'Laboratory test results', 'Insurance & claims processing')
            );
        }

        // Generic intelligent enterprise response
        return array(
            'reply' => "Thank you for sharing those details. Based on your goals for " . esc_html($org_name) . ", our technical team can implement a tailored solution with role-based security, automated reporting, and M-Pesa payment workflows. Would you like our technical sales engineers to prepare a tailored demonstration for you?",
            'suggested_options' => array('Yes, schedule a live demo', 'I have a few more questions', 'Request preliminary proposal')
        );
    }

    /**
     * Call External AI endpoint (e.g. Gemini / Claude / OpenAI) if key is present
     */
    private static function call_external_ai($api_key, $history, $message, $context) {
        $endpoint = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=' . urlencode($api_key);

        $contents = array();
        $contents[] = array(
            'role' => 'user',
            'parts' => array(
                array('text' => self::get_system_prompt() . "\nClient Context: " . wp_json_encode($context))
            )
        );

        foreach ($history as $h) {
            $role = ($h['sender'] === 'user') ? 'user' : 'model';
            $contents[] = array(
                'role' => $role,
                'parts' => array(array('text' => $h['text']))
            );
        }

        $contents[] = array(
            'role' => 'user',
            'parts' => array(array('text' => $message))
        );

        $response = wp_remote_post($endpoint, array(
            'headers' => array('Content-Type' => 'application/json'),
            'body'    => wp_json_encode(array('contents' => $contents)),
            'timeout' => 15
        ));

        if (!is_wp_error($response)) {
            $body = wp_remote_retrieve_body($response);
            $json = json_decode($body, true);
            if (!empty($json['candidates'][0]['content']['parts'][0]['text'])) {
                return array(
                    'reply' => $json['candidates'][0]['content']['parts'][0]['text'],
                    'suggested_options' => array('Schedule Demo', 'Discuss Implementation Timeline', 'Ask About Integrations')
                );
            }
        }

        return false;
    }
}
