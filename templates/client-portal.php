<?php
/**
 * Frontend Template: Client Support & Knowledge Hub (Side B)
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<div id="jocsoft-support-container" class="jocsoft-hub-wrapper">
    <div class="jocsoft-header">
        <div class="jocsoft-badge-label">Jocsoft Client Workspace</div>
        <h2 class="jocsoft-main-title">Institutional Client Support &amp; Knowledge Hub</h2>
        <p class="jocsoft-subtitle">Direct SLA tracking, engineer routing, and 24/7 technical documentation for SACCOs, TVETs, schools, hospitals, and enterprise clients.</p>
    </div>

    <!-- Hub Tabs -->
    <div class="jocsoft-tab-nav">
        <button type="button" class="tab-btn active" data-tab="tickets-tab">Support Tickets &amp; SLAs</button>
        <button type="button" class="tab-btn" data-tab="new-ticket-tab">Log New Ticket</button>
        <button type="button" class="tab-btn" data-tab="kb-tab">Self-Help Knowledge Base</button>
        <button type="button" class="tab-btn" data-tab="maintenance-tab">AMC &amp; System Health</button>
    </div>

    <!-- Tab 1: Support Tickets List -->
    <div class="tab-pane active" id="tickets-tab">
        <div class="panel-card">
            <div class="panel-header-flex">
                <div>
                    <h3>Tracked Support Requests</h3>
                    <p>Real-time SLA status and assigned engineers for your contracted systems.</p>
                </div>
                <button type="button" class="jocsoft-btn btn-primary btn-sm switch-tab-btn" data-target="new-ticket-tab">+ Open New Ticket</button>
            </div>

            <div class="table-responsive">
                <table class="jocsoft-table">
                    <thead>
                        <tr>
                            <th>Ticket Ref</th>
                            <th>Subject &amp; System</th>
                            <th>Urgency</th>
                            <th>Current Status</th>
                            <th>Assigned Engineer</th>
                            <th>Logged Date</th>
                        </tr>
                    </thead>
                    <tbody id="tickets-table-body">
                        <!-- Loaded via JS -->
                        <tr>
                            <td><strong>JOC-1042</strong></td>
                            <td>M-Pesa Callback Timeout on Weekend Batches<br><small class="text-muted">Dynamics Navision SACCO</small></td>
                            <td><span class="jocsoft-badge urgency-critical">Critical</span></td>
                            <td><span class="status-pill status-in-progress">In Progress</span></td>
                            <td>K. Mwangi (Senior Backend)</td>
                            <td>Sep 24, 2026</td>
                        </tr>
                        <tr>
                            <td><strong>JOC-1039</strong></td>
                            <td>Term 3 Gradebook Export Formatting<br><small class="text-muted">SomaSmart LMS</small></td>
                            <td><span class="jocsoft-badge urgency-medium">Medium</span></td>
                            <td><span class="status-pill status-assigned">Assigned</span></td>
                            <td>E. Omondi (LMS Support)</td>
                            <td>Sep 23, 2026</td>
                        </tr>
                        <tr>
                            <td><strong>JOC-1025</strong></td>
                            <td>SSL Certificate Renewal on Cloud Node<br><small class="text-muted">Web Hosting / AMC</small></td>
                            <td><span class="jocsoft-badge urgency-low">Low</span></td>
                            <td><span class="status-pill status-resolved">Resolved</span></td>
                            <td>DevOps Desk</td>
                            <td>Sep 20, 2026</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tab 2: New Ticket Submission Form -->
    <div class="tab-pane" id="new-ticket-tab" style="display: none;">
        <div class="panel-card">
            <h3>Submit Support Request</h3>
            <p>Tickets are automatically routed to the designated system engineer according to SLA terms.</p>

            <form id="jocsoft-ticket-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="ticket_client">Institution / Client Name *</label>
                        <input type="text" id="ticket_client" name="client_name" required placeholder="e.g. ABC SACCO Society" />
                    </div>
                    <div class="form-group">
                        <label for="ticket_email">Contact Email *</label>
                        <input type="email" id="ticket_email" name="client_email" required placeholder="admin@abcsacco.co.ke" />
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="ticket_system">Contracted System *</label>
                        <select id="ticket_system" name="system" required>
                            <option value="SomaSmart LMS">SomaSmart E-Learning Platform</option>
                            <option value="Dynamics Navision SACCO">Microsoft Dynamics Navision SACCO ERP</option>
                            <option value="S-Master School System">S-Master School Management</option>
                            <option value="MedStar HIS">MedStar Hospital System</option>
                            <option value="Koha Library System">Koha Integrated Library System</option>
                            <option value="Custom Web / Mobile">Custom Web Portal / Mobile App</option>
                            <option value="Hosting & AMC">Hosting Server &amp; Annual Maintenance</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="ticket_urgency">Severity / Urgency *</label>
                        <select id="ticket_urgency" name="urgency" required>
                            <option value="Low">Low - General query or non-blocking issue</option>
                            <option value="Medium" selected>Medium - Functional problem with work-around</option>
                            <option value="Critical">Critical - System down or core transactions blocked</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="ticket_subject">Issue Summary *</label>
                    <input type="text" id="ticket_subject" name="subject" required placeholder="Brief description of the problem" />
                </div>

                <div class="form-group">
                    <label for="ticket_details">Detailed Steps &amp; Error Message *</label>
                    <textarea id="ticket_details" name="details" rows="5" required placeholder="Describe what actions led to the issue, error codes, and affected users..."></textarea>
                </div>

                <button type="submit" class="jocsoft-btn btn-primary" id="submit-ticket-btn">Submit Ticket to Engineering Queue</button>
            </form>
            <div id="ticket-feedback" class="ticket-feedback" style="display: none;"></div>
        </div>
    </div>

    <!-- Tab 3: Knowledge Base -->
    <div class="tab-pane" id="kb-tab" style="display: none;">
        <div class="panel-card">
            <div class="kb-search-bar">
                <input type="text" id="kb-search-input" placeholder="Search manuals, tutorials, error codes, and configuration guides..." />
                <button type="button" class="jocsoft-btn btn-primary" id="kb-search-btn">Search</button>
            </div>

            <div class="kb-grid" id="kb-results-grid">
                <?php
                $articles = Jocsoft_Support_Portal::get_default_kb_articles();
                foreach ($articles as $art): ?>
                    <div class="kb-article-card">
                        <div class="kb-tag"><?php echo esc_html($art['type']); ?></div>
                        <h4><?php echo esc_html($art['title']); ?></h4>
                        <div class="kb-category"><?php echo esc_html($art['category']); ?></div>
                        <p><?php echo esc_html($art['excerpt']); ?></p>
                        <a href="<?php echo esc_url($art['link']); ?>" class="kb-read-link">Read Standard Procedure &rarr;</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Tab 4: AMC & Maintenance -->
    <div class="tab-pane" id="maintenance-tab" style="display: none;">
        <div class="panel-card">
            <h3>Annual Maintenance Contract (AMC) &amp; Health Checks</h3>
            <p>Jocsoft proactive maintenance schedules, backup logs, and server health checks.</p>

            <div class="amc-status-grid">
                <div class="amc-card">
                    <h4>Server &amp; Database Health</h4>
                    <div class="amc-badge badge-active">99.9% Uptime Verified</div>
                    <p>Automated offsite database replication runs daily at 02:00 EAT with 30-day retention.</p>
                </div>

                <div class="amc-card">
                    <h4>SLA Guarantee Response</h4>
                    <div class="amc-badge badge-info">15 Mins (Critical) / 2 Hrs (Standard)</div>
                    <p>Dedicated on-call engineering escalation team available 24/7 for institutional clients.</p>
                </div>

                <div class="amc-card">
                    <h4>Upcoming Security Patches</h4>
                    <div class="amc-badge badge-scheduled">Scheduled: Oct 15, 2026</div>
                    <p>Quarterly security audit, PHP engine optimization, and SSL certificate verification.</p>
                </div>
            </div>
        </div>
    </div>
</div>
