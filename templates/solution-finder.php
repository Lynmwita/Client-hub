<?php
/**
 * Frontend Template: Digital Solution Discovery Engine & AI Advisor
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<div id="jocsoft-discovery-container" class="jocsoft-hub-wrapper">
    <!-- Header -->
    <div class="jocsoft-header">
        <div class="jocsoft-badge-label">Jocsoft Discovery Engine</div>
        <h2 class="jocsoft-main-title">Find the Right Digital Solution for Your Organization</h2>
        <p class="jocsoft-subtitle">Tell us what you are trying to achieve in plain language. We'll match your operational goals directly to Jocsoft enterprise systems.</p>
    </div>

    <!-- Step Progress Bar -->
    <div class="jocsoft-progress-track">
        <div class="progress-step active" data-step="1"><span>1</span> Organization</div>
        <div class="progress-divider"></div>
        <div class="progress-step" data-step="2"><span>2</span> Area of Need</div>
        <div class="progress-divider"></div>
        <div class="progress-step" data-step="3"><span>3</span> Core Challenge</div>
        <div class="progress-divider"></div>
        <div class="progress-step" data-step="4"><span>4</span> Solution Snapshot</div>
    </div>

    <!-- Step 1: Organization Type -->
    <div class="jocsoft-step-card" id="step-1">
        <h3 class="step-question-title">Step 1: Who are you representing?</h3>
        <p class="step-question-desc">Select the category that best describes your institution.</p>
        
        <div class="jocsoft-options-grid">
            <button type="button" class="option-card" data-key="org_type" data-value="school">
                <div class="option-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                </div>
                <div class="option-text">
                    <strong>School / College / TVET</strong>
                    <span>Certificate, diploma, vocational or secondary institution</span>
                </div>
            </button>

            <button type="button" class="option-card" data-key="org_type" data-value="university">
                <div class="option-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                </div>
                <div class="option-text">
                    <strong>University / Higher Learning</strong>
                    <span>Multi-faculty degree programs and campus libraries</span>
                </div>
            </button>

            <button type="button" class="option-card" data-key="org_type" data-value="sacco">
                <div class="option-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
                </div>
                <div class="option-text">
                    <strong>SACCO / Microfinance / SME</strong>
                    <span>Savings, loan appraisals, dividends and membership</span>
                </div>
            </button>

            <button type="button" class="option-card" data-key="org_type" data-value="hospital">
                <div class="option-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                </div>
                <div class="option-text">
                    <strong>Hospital / Healthcare Facility</strong>
                    <span>Clinics, triage, EMR, pharmacy, and patient billing</span>
                </div>
            </button>
        </div>
    </div>

    <!-- Step 2: Area of Need -->
    <div class="jocsoft-step-card" id="step-2" style="display: none;">
        <h3 class="step-question-title">Step 2: What is on your priority roadmap?</h3>
        <p class="step-question-desc">Choose the main operational area you want to modernize.</p>
        
        <div class="jocsoft-options-grid">
            <button type="button" class="option-card" data-key="area_of_interest" data-value="elearning">
                <div class="option-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                </div>
                <div class="option-text">
                    <strong>E-Learning & Course Delivery</strong>
                    <span>Digital materials, student exams, and tutor dashboards</span>
                </div>
            </button>

            <button type="button" class="option-card" data-key="area_of_interest" data-value="sacco_erp">
                <div class="option-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
                <div class="option-text">
                    <strong>Financial ERP & SACCO Software</strong>
                    <span>General ledger, member accounts, and automated payroll</span>
                </div>
            </button>

            <button type="button" class="option-card" data-key="area_of_interest" data-value="custom_apps">
                <div class="option-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="2" width="14" height="20" rx="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
                </div>
                <div class="option-text">
                    <strong>Web Portals & Mobile Apps</strong>
                    <span>Custom client portals, Android/iOS apps, and M-Pesa APIs</span>
                </div>
            </button>

            <button type="button" class="option-card" data-key="area_of_interest" data-value="consulting">
                <div class="option-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                </div>
                <div class="option-text">
                    <strong>ISO Certification & ICT Training</strong>
                    <span>Standards compliance (9001/27001) and staff upskilling</span>
                </div>
            </button>
        </div>
        <button type="button" class="jocsoft-btn btn-secondary back-btn" data-back="1">Back</button>
    </div>

    <!-- Step 3: Main Challenge -->
    <div class="jocsoft-step-card" id="step-3" style="display: none;">
        <h3 class="step-question-title">Step 3: What is the primary bottleneck today?</h3>
        <p class="step-question-desc">Understanding your current operational pain points.</p>
        
        <div class="jocsoft-options-grid">
            <button type="button" class="option-card" data-key="main_challenge" data-value="managing_learners">
                <div class="option-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                <div class="option-text">
                    <strong>Managing Learners & Records</strong>
                    <span>Disorganized communications across WhatsApp and email</span>
                </div>
            </button>

            <button type="button" class="option-card" data-key="main_challenge" data-value="manual_excel">
                <div class="option-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                </div>
                <div class="option-text">
                    <strong>Manual Spreadsheets & Excel Overload</strong>
                    <span>Time wasted on repetitive data entry and calculation errors</span>
                </div>
            </button>

            <button type="button" class="option-card" data-key="main_challenge" data-value="reporting">
                <div class="option-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                </div>
                <div class="option-text">
                    <strong>Delayed Analytics & Progress Reports</strong>
                    <span>Difficulty obtaining real-time performance and financial data</span>
                </div>
            </button>

            <button type="button" class="option-card" data-key="main_challenge" data-value="building_platform">
                <div class="option-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>
                </div>
                <div class="option-text">
                    <strong>Scaling to a Modern Digital Platform</strong>
                    <span>Outgrowing legacy systems and needing an integrated solution</span>
                </div>
            </button>
        </div>
        <button type="button" class="jocsoft-btn btn-secondary back-btn" data-back="2">Back</button>
    </div>

    <!-- Step 4: Instant Value Preview & 4-Field Unlock -->
    <div class="jocsoft-step-card" id="step-4" style="display: none;">
        <div class="preview-unlock-container">
            <div class="preview-box">
                <div class="preview-tag">Instant Value Preview</div>
                <h3 id="preview-system-title">Tailored Enterprise Solution Identified</h3>
                <p id="preview-system-desc">Based on your selections, Jocsoft has mapped an optimized solution designed to eliminate manual administration and centralize your operations.</p>
                
                <ul class="preview-feature-list" id="preview-features">
                    <li>Role-Based Access Control & Secure Dashboards</li>
                    <li>Automated Reporting & Data Export Engine</li>
                    <li>Full M-Pesa & Bank Transaction Integration</li>
                </ul>
            </div>

            <div class="unlock-form-box">
                <h3>Unlock Detailed Recommendation</h3>
                <p>Provide your contact details to access the full technical scope and consultation options.</p>

                <form id="jocsoft-unlock-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="joc_name">Full Name *</label>
                            <input type="text" id="joc_name" name="full_name" required placeholder="e.g. Jane Wanjiku" />
                        </div>
                        <div class="form-group">
                            <label for="joc_org">Organization Name *</label>
                            <input type="text" id="joc_org" name="organization" required placeholder="e.g. Premier College" />
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="joc_email">Work Email *</label>
                            <input type="email" id="joc_email" name="email" required placeholder="name@institution.co.ke" />
                        </div>
                        <div class="form-group">
                            <label for="joc_phone">Phone / WhatsApp *</label>
                            <input type="tel" id="joc_phone" name="phone" required placeholder="+254 7XX XXX XXX" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="joc_role">Job Role / Title (Optional)</label>
                        <input type="text" id="joc_role" name="job_title" placeholder="e.g. ICT Director, Administrator" />
                    </div>

                    <button type="submit" class="jocsoft-btn btn-primary btn-block" id="unlock-submit-btn">
                        View Solution Snapshot &amp; Advisory Options
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Step 5: Personalized Solution Snapshot & AI Dialogue -->
    <div class="jocsoft-step-card" id="step-5" style="display: none;">
        <div class="snapshot-header">
            <div class="snapshot-badge">Recommended Direction</div>
            <h3 id="snapshot-title">Jocsoft Solution Blueprint</h3>
            <p id="snapshot-tagline" class="snapshot-lead"></p>
        </div>

        <div class="snapshot-content-grid">
            <div class="snapshot-modules-card">
                <h4>Core Modules Included:</h4>
                <ul id="snapshot-modules" class="snapshot-modules-list"></ul>

                <div class="snapshot-impact-card">
                    <strong>Operational Impact:</strong>
                    <p id="snapshot-impact"></p>
                </div>
            </div>

            <div class="snapshot-actions-card">
                <h4>Next Steps</h4>
                <p>Choose how you would like to proceed with the Jocsoft team:</p>

                <div class="action-choice-box">
                    <div class="action-item">
                        <strong>Option 1: Direct Human Specialist</strong>
                        <p>Speak directly with a Jocsoft technical sales engineer.</p>
                        <a href="tel:+254732447447" class="jocsoft-btn btn-secondary btn-sm">Call (+254) 732 447 447</a>
                    </div>

                    <div class="action-divider"><span>OR</span></div>

                    <div class="action-item">
                        <strong>Option 2: Interactive AI Scoping Advisor</strong>
                        <p>Discuss specific parameters (student count, legacy data, timelines) with our grounded AI advisor.</p>
                        <button type="button" class="jocsoft-btn btn-primary btn-sm" id="launch-ai-advisor-btn">Launch AI Advisor</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- AI Solution Advisor Conversation Window -->
        <div id="jocsoft-ai-chat-section" class="ai-chat-section" style="display: none;">
            <div class="ai-chat-header">
                <div class="ai-status-indicator"></div>
                <div>
                    <strong>Jocsoft Senior Solution Advisor (AI Guided Intake)</strong>
                    <span>Grounded in verified Jocsoft enterprise products &amp; implementations</span>
                </div>
            </div>

            <div id="ai-messages-container" class="ai-messages-container">
                <div class="chat-msg msg-advisor">
                    <div class="msg-bubble">
                        Hello! I am your Jocsoft Solution Advisor. I have reviewed your organization profile. To ensure our engineering team prepares the most accurate scope for you, what is the approximate scale of users (e.g. students, members, or staff) you are planning for?
                    </div>
                </div>
            </div>

            <div id="ai-quick-options" class="ai-quick-options">
                <button type="button" class="quick-reply-btn">Under 500 users</button>
                <button type="button" class="quick-reply-btn">500 - 2,000 users</button>
                <button type="button" class="quick-reply-btn">2,000+ users</button>
            </div>

            <form id="ai-chat-form" class="ai-chat-input-bar">
                <input type="text" id="ai-user-input" placeholder="Type your response or question..." autocomplete="off" />
                <button type="submit" class="jocsoft-btn btn-primary">Send</button>
            </form>
        </div>
    </div>
</div>
