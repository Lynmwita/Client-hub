# Jocsoft Client Experience Hub (Client-hub)

An enterprise WordPress platform bridging potential institutional clients (TVETs, schools, SACCOs, hospitals, SMEs) and existing client operations for **Jocsoft Solutions Limited** (Nairobi, Kenya).

---

## Core System Architecture

### Side A: Digital Solution Discovery Engine (Prospect Intake)
1. **30-Second Solution Finder**:
   - Organization Profile (Schools/Colleges, Universities, SACCOs, Hospitals, Enterprises)
   - Priority Needs (SomaSmart LMS, Dynamics Navision SACCO ERP, MedStar HIS, S-Master, Koha)
   - Core Bottlenecks (Learner management, Excel spreadsheets, delayed reporting)
2. **Instant Value Preview & 4-Field Unlock Form**:
   - Delivers value before capturing contact details (Full Name, Organization, Work Email, Phone/WhatsApp).
3. **Personalized Solution Snapshot**:
   - Generates tailored system architecture blueprints with direct actionable next steps.
4. **Guardrailed AI Solution Advisor**:
   - Conversational intake coordinator strictly constrained to Jocsoft's verified catalog.
   - Automatically generates structured pre-qualified sales dossiers for account executives.

### Side B: Client Support & Knowledge Hub (Post-Sale Operations)
1. **Support Tickets & SLA Engine**:
   - Status tracking (`Submitted` &rarr; `Assigned` &rarr; `In Progress` &rarr; `Resolved`).
   - Urgency levels (`Critical`, `Medium`, `Low`) with direct routing to support engineering.
2. **Self-Help Knowledge Base**:
   - Searchable manuals and tutorials for SomaSmart, SACCO reconciliations, M-Pesa callbacks, and Koha scanners.
3. **AMC & System Health Monitor**:
   - Proactive database backup logging, uptime tracking, and SLA guarantees.

---

## Shortcodes

- `[jocsoft_discovery_hub]` — Embeds the interactive Solution Finder and AI Solution Advisor.
- `[jocsoft_client_portal]` — Embeds the institutional Support Workspace, Ticket Tracker, and Knowledge Base.

---

## Technical Stack & Standards

- **Platform**: WordPress 6.x+ (PHP 7.4+ / PHP 8.x)
- **Design Philosophy**: High-contrast corporate slate/navy palette (`#0f172a`, `#334155`, `#e2e8f0`), clean SVG icons, zero informal emojis, zero fluorescent neon effects.
- **REST API**: Secure `/wp-json/jocsoft/v1/` endpoints for discovery submission, ticket logging, and AI conversational scoping.

---

## Author & Enterprise Attribution
- **Enterprise**: Jocsoft Solutions Limited, Court 7731, Muchai Drive, Nairobi, Kenya
- **Repository**: [Lynmwita/Client-hub](https://github.com/Lynmwita/Client-hub)
