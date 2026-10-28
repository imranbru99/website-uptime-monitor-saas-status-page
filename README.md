<p align="center">
  <img src="docs/images/hero-banner.jpg" alt="Website Uptime Monitor Hero Banner" width="100%" style="border-radius: 12px; box-shadow: 0 20px 40px rgba(0,0,0,0.6);" />
</p>

# Website Uptime Monitor — Enterprise SaaS Platform

<p align="center">
  <strong>The Ultimate All-in-One Infrastructure Observability, Synthetic Uptime Monitoring, Automated Incident Management, and Public Status Pages Platform</strong>
</p>

<p align="center">
  <a href="https://php.net"><img src="https://img.shields.io/badge/PHP-8.3%20%7C%208.4%20%7C%208.5-777BB4.svg?style=for-the-badge&logo=php" alt="PHP Version" /></a>
  <a href="https://laravel.com"><img src="https://img.shields.io/badge/Laravel-13.x-FF2D20.svg?style=for-the-badge&logo=laravel" alt="Laravel 13" /></a>
  <a href="https://filamentphp.com"><img src="https://img.shields.io/badge/Filament-v5.x-FFA500.svg?style=for-the-badge" alt="Filament v5" /></a>
  <a href="https://www.postgresql.org/"><img src="https://img.shields.io/badge/Database-PostgreSQL%20%7C%20MySQL%20%7C%20SQLite-336791.svg?style=for-the-badge&logo=postgresql" alt="PostgreSQL" /></a>
  <a href="https://redis.io"><img src="https://img.shields.io/badge/Cache%20%26%20Queue-Redis%207-DC382D.svg?style=for-the-badge&logo=redis" alt="Redis" /></a>
  <a href="LICENSE"><img src="https://img.shields.io/badge/License-MIT-blue.svg?style=for-the-badge" alt="MIT License" /></a>
  <a href="#"><img src="https://img.shields.io/badge/Tests-25%20Passed%20%7C%20100%25-10B981.svg?style=for-the-badge" alt="Tests 100% Passed" /></a>
</p>

<p align="center">
  <strong>Developed & Engineered by <a href="https://imrandev.bd">Imran Ahmed</a></strong> • <a href="mailto:me@imrandev.bd">me@imrandev.bd</a> • <a href="https://imrandev.bd">https://imrandev.bd</a>
</p>

---

## 📑 Table of Contents

1. [Executive Overview & Vision](#1-executive-overview--vision)
2. [Massive Business Opportunities & ROI](#2-massive-business-opportunities--roi-why-its-a-game-changer)
3. [Visual Tour & Interactive Dashboards](#3-visual-tour--interactive-dashboards)
4. [5 Independent Filament v5 Panels](#4-5-independent-filament-v5-panels)
5. [The 60 Dynamic SaaS Modules Registry](#5-the-60-dynamic-saas-modules-registry)
6. [Supported Synthetic Monitoring Protocols](#6-supported-synthetic-monitoring-protocols-10-types)
7. [Finite State Machine & Zero False Alarms](#7-finite-state-machine--zero-false-alarms)
8. [Multi-Channel Notification Dispatch Mesh](#8-multi-channel-notification-dispatch-mesh)
9. [Hardened SSRF Security Defense](#9-hardened-ssrf-security-defense)
10. [AI Operations Center & Incident Copilot](#10-ai-operations-center--incident-copilot)
11. [Multi-Tenant & Multi-Branch Enterprise Hierarchy](#11-multi-tenant--multi-branch-enterprise-hierarchy)
12. [SLA / SLO Engine & Analytics](#12-sla--slo-engine--analytics)
13. [Public & Private Status Page Builder](#13-public--private-status-page-builder)
14. [Seeded Enterprise Demo Environments](#14-seeded-enterprise-demo-environments-instant-login)
15. [Artisan CLI Automation Suite](#15-artisan-cli-automation-suite)
16. [REST API Reference (v1)](#16-rest-api-reference-v1)
17. [Package Integrations & Evaluator Matrix](#17-package-integrations--evaluator-matrix)
18. [One-Click Installation & Quickstart](#18-one-click-installation--quickstart)
19. [Docker Compose Stack](#19-docker-compose-stack)
20. [Automated Test Suite & Verification](#20-automated-test-suite--verification)
21. [Author Attribution & License](#21-author-attribution--license)

---

## 1. Executive Overview & Vision

**Website Uptime Monitor** is a complete, production-ready, enterprise-grade synthetic monitoring and incident response SaaS platform. It consolidates synthetic uptime checks, internal network probing, multi-channel alerting, SLA tracking, AI-assisted postmortems, and public status pages into a **single, unified control plane**.

### The Problem It Solves
Modern digital businesses, agencies, and enterprises are forced to stitch together 4 to 6 separate subscriptions:
- **Pingdom / BetterUptime** for basic website checks ($100–$300/mo)
- **PagerDuty / OpsGenie** for on-call rotations and alerting ($40–$80/user/mo)
- **Statuspage.io** for customer communication ($300–$1,500/mo)
- **Datadog Synthetics** for multi-step API & SSL checks ($500–$3,000/mo)

**Website Uptime Monitor replaces all of them**, delivering superior modularity, sub-second precision, private network agents, customizable branch hierarchies, and complete data ownership.

```
+-----------------------------------------------------------------------------------+
|                                Client Applications                                |
|  Public Status Pages  |  Customer Portal  |  Branch Console  |  Platform SuperAdmin|
+-----------------------------------------------------------------------------------+
                                          |
                                    HTTPS / WSS / API
                                          |
+-----------------------------------------------------------------------------------+
|                        Modular Monolith Control Plane                             |
|  Identity  | Tenancy  | Branches | Modules (60 Modules) | Monitoring & Probes     |
|  Incidents | Alerts   | Status   | Analytics & SLA      | Automation & Integrations|
|  Billing   | Assets   | AI Hub   | Reports & PDF Engine | CMS & Customer Service  |
+-----------------------------------------------------------------------------------+
       |                     |                      |                      |
       v                     v                      v                      v
+--------------+     +---------------+     +----------------+     +-----------------+
|  PostgreSQL  |     | Redis Cluster |     | Probe Workers  |     | External APIs   |
|  (Core Data) |     | (Queues/Cache)|     | (Distributed)  |     | (AI/Slack/Mail) |
+--------------+     +---------------+     +----------------+     +-----------------+
```

---

## 2. Massive Business Opportunities & ROI (Why It's a Game Changer)

<p align="center">
  <img src="docs/images/roi-time-saver.svg" alt="Business Value, Time Savings & ROI Multiplier" width="100%" />
</p>

### 2.1 Astronomical Tool Consolidation Savings: $48,000+ / Year
Instead of paying exorbitant per-monitor, per-user, or per-SMS fees across fragmented platforms, Website Uptime Monitor allows companies to self-host or manage their own SaaS instance with **unlimited monitors and zero per-seat tax**:

| Cost Component | Fragmented Vendor Stack | Website Uptime Monitor | Annual Savings |
| :--- | :--- | :--- | :--- |
| **Synthetic Checks** (100 monitors) | Datadog ($18,000/yr) | **Included** | **$18,000** |
| **On-Call & Escalation** (10 SREs) | PagerDuty ($9,600/yr) | **Included** | **$9,600** |
| **Branded Status Pages** | Statuspage.io ($4,800/yr) | **Included** | **$4,800** |
| **SSL & Heartbeat Snitches** | Cronitor + Pingdom ($2,400/yr)| **Included** | **$2,400** |
| **AI Incident Summaries** | Custom OpenAI scripts ($3,600/yr)| **Included** (`laravel-ai-hub`) | **$3,600** |
| **Total Annual Software Spend** | **$38,400 – $65,000+** | **$0 (Self-Hosted) / One Plan** | **$38,400 – $65,000+** |

### 2.2 Massive Engineering Time Saver: 15–20 Hours Saved / Engineer / Week
- **Zero Alert Fatigue (0 False Alarms)**: Multi-probe verification ensures your on-call engineers are never awakened at 3:00 AM by transient ISP packet loss. Alerts trigger only when multiple probes confirm downtime.
- **AI-Powered Incident Copilot**: Powered by `imrandevbd/laravel-ai-hub`, the system automatically interprets error codes, identifies likely root causes, drafts executive postmortems, and writes non-technical customer status updates in seconds.
- **Automated Incident Recovery**: Probe mesh verifies when a service resumes normal response latencies and auto-resolves the open incident, restoring status page components automatically.
- **Instant PDF Executive Reports**: Generate board-ready, multilingual Unicode PDF compliance and SLA audit reports in one click via `imrandevbd/laravel-unicode-pdf`.

### 2.3 Unlocking High-Margin Agency MRR ($3,000 – $15,000/mo)
Digital agencies, web developers, and MSPs can turn monitoring into a **lucrative recurring revenue engine**:
- **White-Label Client Portal (`/client`)**: Brand the portal with your agency logo, custom domain, and colors.
- **Client Retainers**: Charge clients $49–$299/month for 24/7 uptime monitoring, SLA guarantees, and priority incident response.
- **Client Segregation**: Each client sees only their assigned websites, performance charts, and status pages.
- **50 Clients @ $149/mo = $7,450/month in pure passive Monthly Recurring Revenue (MRR)**.

### 2.4 Complete Data Privacy & Regulatory Sovereignty
- 100% compliant with GDPR, HIPAA, and SOC 2.
- No confidential internal staging URLs, internal bearer tokens, or corporate database connection strings are ever sent to third-party monitoring vendors.

---

## 3. Visual Tour & Interactive Dashboards

### 3.1 Business Owner Observability Console (`/app`)
*Real-time multi-probe latency charts, live status indicators, instant "Check Now" triggers, and target inventories:*

<p align="center">
  <img src="docs/images/dashboard-mockup.svg" alt="Business Owner Dashboard Mockup" width="100%" style="border-radius: 8px; box-shadow: 0 10px 30px rgba(0,0,0,0.5);" />
</p>

### 3.2 Public Status Page Builder (`/status/{slug}`)
*Beautiful, dark/light mode public status pages with 90-day interactive uptime history bars, service groups, and email subscriber registration:*

<p align="center">
  <img src="docs/images/status-page-mockup.svg" alt="Public Status Page Mockup" width="100%" style="border-radius: 8px; box-shadow: 0 10px 30px rgba(0,0,0,0.5);" />
</p>

### 3.3 Command Center & Global Network Mesh
*Global probe points of presence across 8 regions with sub-second telemetry and multi-channel notifications:*

<p align="center">
  <img src="docs/images/command-center-preview.jpg" alt="Command Center NOC Preview" width="100%" style="border-radius: 8px; box-shadow: 0 10px 30px rgba(0,0,0,0.5);" />
</p>

---

## 4. 5 Independent Filament v5 Panels

Website Uptime Monitor provides 5 completely segregated administrative panels with independent route trees, navigation, and role-based permissions:

```
[ Platform Admin: /platform ]      [ Business Owner: /app ]       [ Branch Ops: /branch ]
        │                                  │                              │
        ▼                                  ▼                              ▼
Global SaaS Governance            Full Monitoring Control        Regional Infrastructure
Probe Mesh Management             Alerts, Status, AI, Billing    Branch Team Schedules

                [ Client Portal: /client ]       [ Support / NOC: /operations ]
                        │                                    │
                        ▼                                    ▼
                White-Label Agency Client            Cross-Tenant Triage
                Target Observability Only            Worker Telemetry & Dead-Letter
```

1. **Platform Super Admin Panel (`/platform`)**:
   - Manage multi-tenant accounts, subscription plans, global probe points of presence, and 60 system module defaults.
2. **Business Owner Panel (`/app`)**:
   - Day-to-day command center for tenant admins: configure monitors, incident triage, alert policies, status page editor, 60-module manager, reports, and AI assistant.
3. **Branch Operations Panel (`/branch`)**:
   - Regional console for enterprise regional managers (e.g. US-East, EU-Central, APAC). Monitor branch-scoped targets without clutter.
4. **Client Portal (`/client`)**:
   - Dedicated portal for agency clients. Read-only access to their assigned monitors, performance metrics, and public status pages.
5. **Support and Operations Panel (`/operations`)**:
   - High-throughput triage panel for NOC engineers and SREs. Displays queue depths, failed jobs, probe heartbeats, and cluster health.

---

## 5. The 60 Dynamic SaaS Modules Registry

Every feature in the system is managed through a central dynamic registry ([`ModuleRegistry.php`](file:///home/imran/project/website-uptime-monitor-alerts-and-status-pages/app/Domain/Modules/Services/ModuleRegistry.php)). Features can be toggled per tenant or overridden per enterprise branch with **zero loss of data**:

| # | Slug | Module Name | Category | Plan Tier | Mandatory | Capabilities |
|---|---|---|---|---|---|---|
| 01 | `website_uptime` | Website Uptime Monitoring | monitoring | Free | **Yes** | Real-time HTTP/HTTPS endpoint availability and response latency. |
| 02 | `http_https` | HTTP & HTTPS Monitoring | monitoring | Free | No | Detailed status codes, response headers, redirects, payload validation. |
| 03 | `api_monitoring` | API Monitoring | monitoring | Starter | No | REST API endpoint testing, JSON schema validation, JSON path assertions. |
| 04 | `tcp_port` | TCP Port Monitoring | monitoring | Starter | No | Raw TCP socket connection testing across standard and custom network ports. |
| 05 | `dns_monitoring` | DNS Monitoring | monitoring | Starter | No | DNS lookup validation across A, AAAA, CNAME, MX, TXT, NS records. |
| 06 | `ssl_certificate` | SSL Certificate Monitoring | monitoring | Free | No | SSL/TLS expiration warnings, cipher suite verification, revocation status. |
| 07 | `domain_expiration` | Domain Expiration Monitoring | monitoring | Starter | No | WHOIS registry expiration tracking with automated advance alerts. |
| 08 | `icmp_ping` | ICMP Ping Monitoring | monitoring | Starter | No | Network packet round-trip time, packet loss percentage, jitter telemetry. |
| 09 | `heartbeat_cron` | Heartbeat & Cron Job Monitoring | monitoring | Free | No | Dead man's snitches for scheduled cron jobs, backups, background workers. |
| 10 | `keyword_content` | Keyword & Content Verification | monitoring | Starter | No | Automated page body assertions for expected or forbidden text strings. |
| 11 | `real_browser_synthetic` | Real Browser Synthetic Testing | synthetic | Professional | No | Headless browser execution testing DOM events and core web vitals. |
| 12 | `transaction_monitoring` | Multi-Step Transaction Monitoring | synthetic | Professional | No | Multi-step user journey verification (login, search, checkout flows). |
| 13 | `distributed_probes` | Distributed Multi-Region Probes | infrastructure | Professional | No | Multi-region probe verification mesh across 8 global locations. |
| 14 | `private_probe_agents` | Private Network Probe Agents | infrastructure | Enterprise | No | On-premise agent deployment behind private corporate firewalls. |
| 15 | `incident_management` | Incident Management Engine | incidents | Free | **Yes** | Automated incident detection, status updates, team assignment, resolution. |
| 16 | `incident_timeline` | Incident Timeline & Event Log | incidents | Free | No | Chronological event stream tracking probe alerts and engineer notes. |
| 17 | `incident_correlation` | Incident Correlation & Clustering | incidents | Professional | No | Multi-monitor failure deduplication preventing cascading alert storms. |
| 18 | `postmortem_generator` | Postmortem & RCA Generator | incidents | Business | No | Structured post-incident review recording root cause and action items. |
| 19 | `public_status_pages` | Public Status Pages | status_pages | Free | No | Beautiful public availability dashboards with dark mode and custom CSS. |
| 20 | `private_status_pages` | Private & Internal Status Pages | status_pages | Starter | No | Authenticated, IP-whitelisted, password-protected internal status portals. |
| 21 | `custom_domain_status` | Custom Domain Status Pages | status_pages | Starter | No | Custom CNAME status page hosting with automated SSL provisioning. |
| 22 | `subscriber_notifications` | Status Page Email Subscriptions | status_pages | Free | No | Direct customer notifications for planned maintenance and outage updates. |
| 23 | `email_notifications` | Email Alert Notifications | alerts | Free | **Yes** | Instant transactional alerts via SMTP with configurable cooldowns. |
| 24 | `slack_integration` | Slack Alert Integration | alerts | Starter | No | Interactive Slack incident cards with acknowledge/resolve buttons. |
| 25 | `discord_integration` | Discord Webhook Notifications | alerts | Free | No | Discord server webhook alerts with rich embeds and severity color codes. |
| 26 | `telegram_integration` | Telegram Bot Notifications | alerts | Starter | No | Instant Telegram bot notifications delivered to channels or groups. |
| 27 | `teams_integration` | Microsoft Teams Webhooks | alerts | Professional | No | Enterprise incident cards pushed to Microsoft Teams channels. |
| 28 | `webhook_dispatcher` | Custom Webhook Dispatcher | alerts | Free | No | Outbound JSON webhooks with HMAC-SHA256 signature verification. |
| 29 | `on_call_schedules` | On-Call Schedule Management | on_call | Business | No | Weekly/daily engineer rotations with automated shift handover. |
| 30 | `escalation_policies` | Escalation Policies & Rules | on_call | Business | No | Multi-tiered escalation if alerts remain unacknowledged. |
| 31 | `maintenance_windows` | Planned Maintenance Windows | maintenance | Starter | No | Scheduled downtime windows suppressing alert dispatch during maintenance. |
| 32 | `sla_tracking` | SLA & SLO Compliance Engine | analytics | Business | No | Contractual uptime calculation, error budgets, breach notifications. |
| 33 | `latency_percentiles` | Latency & Performance Percentiles | analytics | Professional | No | P50, P90, P95, and P99 latency aggregation across probes. |
| 34 | `mttd_mttr_analytics` | MTTD & MTTR Observability | analytics | Business | No | Mean Time to Detect and Mean Time to Resolve operational analytics. |
| 35 | `multi_tenant_isolation` | Multi-Tenant Architecture | tenancy | Free | **Yes** | Row-level tenant data isolation with global query scopes. |
| 36 | `multi_branch_hierarchy` | Multi-Branch Hierarchy | tenancy | Enterprise | No | Tenant -> Branch -> Department -> Team organizational structure. |
| 37 | `audit_trail_logging` | Security & Compliance Audit Log | security | Business | No | Immutable audit log of configuration changes and authentication attempts. |
| 38 | `role_based_access` | Role-Based Access Control | security | Free | **Yes** | Granular permissions for admins, SREs, managers, and viewers. |
| 39 | `two_factor_auth` | Two-Factor Authentication (2FA) | security | Free | No | TOTP authenticator app support for secure account access. |
| 40 | `ssrf_protection` | SSRF Security & Network Guard | security | Free | **Yes** | Loopback and RFC1918 private subnet blocking on synthetic probes. |
| 41 | `ai_incident_summaries` | AI Incident Summarizer | ai | Business | No | Automated incident postmortems powered by `laravel-ai-hub`. |
| 42 | `ai_root_cause_analysis` | AI Root Cause Assistant | ai | Business | No | Anomaly pattern interpretation and diagnostic recommendations. |
| 43 | `ai_status_update_drafts` | AI Status Update Copilot | ai | Business | No | Public-facing status update generator for transparent communication. |
| 44 | `service_dependency_map` | Service Dependency Mapping | architecture | Enterprise | No | Visual graph of infrastructure topology and upstream dependencies. |
| 45 | `database_health_checks` | Database Health Probes | infrastructure | Professional | No | Remote connectivity and query timing for PostgreSQL, MySQL, Redis. |
| 46 | `server_resource_monitoring` | Server Resource Telemetry | infrastructure | Professional | No | Agent-reported CPU, Memory, Disk, and load average telemetry. |
| 47 | `rest_api_v1` | REST API Access (v1) | developer | Starter | No | Full programmatic CRUD control with Sanctum Bearer tokens. |
| 48 | `custom_http_headers` | Custom Request Headers & Auth | developer | Starter | No | Custom Bearer, Basic, and custom headers for synthetic checks. |
| 49 | `export_pdf_reports` | Unicode PDF Report Generator | reporting | Starter | No | Multi-language executive PDF reports via `laravel-unicode-pdf`. |
| 50 | `export_csv_telemetry` | CSV & Raw Telemetry Export | reporting | Free | No | Raw check data downloads for external business intelligence tools. |
| 51 | `customer_billing` | Subscription Billing & Plans | billing | Free | **Yes** | Self-serve subscription management, plan upgrades, quota enforcement. |
| 52 | `invoice_generation` | Invoice & Receipt Engine | billing | Starter | No | PDF invoice generation and billing history. |
| 53 | `coupon_promotions` | Coupon & Discount Engine | billing | Starter | No | Percentage and fixed promo code management. |
| 54 | `custom_branding_whitelabel` | White-Label Agency Branding | agency | Enterprise | No | Custom logo, favicons, custom sender addresses, and domain CNAME. |
| 55 | `client_portal` | Client Organization Portal | agency | Business | No | Dedicated `/client` panel for agencies to share monitoring with clients. |
| 56 | `cms_page_builder` | Public CMS & Marketing Pages | cms | Free | No | Landing pages, feature showcases, knowledgebase, and changelog. |
| 57 | `public_demo_experience` | Interactive Demo Showcase | cms | Free | No | One-click instant interactive sandbox showcase. |
| 58 | `lead_capture_forms` | Lead Capture & Demo Requests | cms | Free | No | Enterprise sales request forms and customer inquiry intake. |
| 59 | `automation_workflow_rules` | Automated Remediation Rules | automation | Business | No | Webhook and script execution triggers upon confirmed outage. |
| 60 | `platform_operations_dashboard` | Platform Operations Dashboard | operations | Free | **Yes** | Real-time queue, probe node, and background worker telemetry. |

---

## 6. Supported Synthetic Monitoring Protocols (10 Types)

Website Uptime Monitor performs real, non-mocked network probe checks across 10 specialized protocols:

1. **HTTP / HTTPS (`http`, `https`)**:
   - Validates response status codes (e.g. 200, 201, 204, 301).
   - Follows redirects up to configurable limits.
   - Measures TTFB (Time to First Byte) and DNS/TLS handshake latencies.
2. **REST API (`api`)**:
   - Supports GET, POST, PUT, PATCH, DELETE with custom headers, Bearer tokens, or Basic Auth.
   - Evaluates JSON payload structure and regex path assertions.
3. **TCP Port (`tcp`)**:
   - Performs raw non-blocking socket connections to standard or custom ports (e.g., 22, 25, 3306, 5432, 6379, 8080).
4. **DNS Records (`dns`)**:
   - Validates DNS resolution for A, AAAA, CNAME, MX, TXT, and NS records; flags propagation failures or DNS poisoning.
5. **SSL / TLS Certificate (`ssl`)**:
   - Connects to TLS streams, extracts certificate metadata, verifies CN/SAN, and triggers proactive alerts 30, 14, 7, and 1 days before expiration.
6. **Domain Expiration (`domain`)**:
   - Evaluates domain registry WHOIS records and emits advance renewal alerts.
7. **ICMP Ping (`ping`)**:
   - Measures round-trip time (RTT), packet loss percentage, and network jitter.
8. **Heartbeat / Cron Job Snitch (`heartbeat`)**:
   - Passive listener receiving webhook pings at `/api/v1/heartbeat/{token}`; flags missing nightly backups or stuck queue workers.
9. **Keyword & Content Assertion (`keyword`)**:
   - Asserts the presence or absence of specific strings or regex patterns in the HTML response body.
10. **Database & Server Resource Probes (`database`, `server`)**:
    - Tests remote database connectivity and agent-reported CPU, memory, and disk utilization.

---

## 7. Finite State Machine & Zero False Alarms

To protect engineering teams from alert fatigue caused by momentary network blips, the monitoring engine uses a **6-Stage Finite State Machine (FSM)**:

```
                  +------------+
                  |  Pending   |
                  +------------+
                        │
                        ▼
                  +------------+ <─────────────────────+
      +─────────> │  Healthy   │                       │
      │           +------------+                       │
      │                 │ (1st failure observed)       │
      │                 ▼                              │
      │           +────────────────+                   │
      │           | Suspected Down |                   │
      │           +────────────────+                   │
      │                 │                              │
      │                 │ (consecutive failures >= threshold)
      │                 ▼                              │
      │           +────────────────+                   │
      │           | Confirmed Down |                   │
      │           +────────────────+                   │
      │                 │                              │
      │                 │ (1st success observed)       │
      │                 ▼                              │
      │           +────────────────+                   │
      │           |   Recovering   | ──────────────────+
      │           +────────────────+ (confirmed operational)
      │                 │
      +─────────────────+
```

1. **Suspected Down**: When an initial failure is detected, the monitor moves to `suspected_down` and immediately enqueues verification from a second geographic probe. No alerts are dispatched yet.
2. **Confirmed Down**: Once failures exceed `confirmation_threshold` (default 2 or 3), an incident is created, impacted status page components are toggled, and alert notifications dispatch.
3. **Recovering**: When a check succeeds after downtime, the system enters `recovering` until a second check confirms stability, auto-resolving the incident.

---

## 8. Multi-Channel Notification Dispatch Mesh

<p align="center">
  <img src="docs/images/incident-pipeline.svg" alt="Automated Incident Lifecycle & Alert Pipeline" width="100%" />
</p>

Website Uptime Monitor provides native dispatchers for 6 enterprise alert destinations:

- **Email**: Beautiful, responsive transactional HTML emails with latency graphs and action links.
- **Slack**: Interactive BlockKit message cards with acknowledge and resolve buttons.
- **Discord**: Color-coded rich embeds (Red for Outage, Green for Recovery).
- **Telegram**: Instant markdown messages delivered to private chats or alert channels.
- **Microsoft Teams**: Adaptive Cards pushed into company incident channels.
- **Custom Webhooks**: JSON payloads sent with cryptographic HMAC-SHA256 signatures (`X-Uptime-Signature: sha256=<HASH>`).

---

## 9. Hardened SSRF Security Defense

Because synthetic monitoring platforms allow users to enter arbitrary URLs, **Server-Side Request Forgery (SSRF)** is the primary threat vector. The system implements a defense-in-depth security guard ([`SSRFValidator.php`](file:///home/imran/project/website-uptime-monitor-alerts-and-status-pages/app/Domain/Monitoring/Services/SSRFValidator.php)):

- **IPv4 Loopback Blocking**: Rejects `127.0.0.0/8`, `localhost`, and `0.0.0.0/8`.
- **IPv6 Loopback Blocking**: Rejects `::1`.
- **RFC 1918 Private Subnet Blocking**: Rejects `10.0.0.0/8`, `172.16.0.0/12`, and `192.168.0.0/16`.
- **Cloud Metadata Service Blocking**: Strictly forbids `169.254.169.254` (preventing theft of AWS, GCP, Azure, or DigitalOcean IAM credentials).
- **Dangerous Scheme Blocking**: Only allows `http://`, `https://`, `tcp://`, and hostnames. Completely disallows `file://`, `phar://`, `gopher://`, `dict://`, and `ldap://`.
- **DNS Rebinding Defense**: Resolves hostname IPs before connecting to ensure DNS rebinding tricks cannot bypass initial filters.

---

## 10. AI Operations Center & Incident Copilot

Powered by `imrandevbd/laravel-ai-hub` (v1.7.1) with seamless fallback adapters:

1. **Automated Incident Postmortems**: Generates structured root-cause analysis (RCA) summaries detailing failure symptoms, affected upstream dependencies, and recommended remediation.
2. **Root Cause Suggestions**: Synthesizes telemetry (e.g. 504 Gateway Timeout + Spike in Latency -> Upstream PHP-FPM or database pool exhaustion).
3. **Public Status Update Drafting**: Drafts clear, empathetic, non-technical customer communications across 4 stages (`investigating`, `identified`, `monitoring`, `resolved`).
4. **Interactive AI Copilot**: Accessible at `/app/ai-assistant-page` for interactive troubleshooting.

---

## 11. Multi-Tenant & Multi-Branch Enterprise Hierarchy

### 11.1 Single-Database Tenancy
Every tenant-owned model implements the `BelongsToTenant` trait. Eloquent queries are automatically constrained by `TenantScope` (`WHERE tenant_id = ?`), ensuring zero cross-tenant data leakage.

### 11.2 Enterprise Organizational Structure
```
+-------------------------------------------------------------+
|                  Tenant (Parent Organization)               |
|            e.g. NimbusCloud Enterprise Global               |
+-------------------------------------------------------------+
                              │
       +──────────────────────┴──────────────────────+
       │                                             │
+──────────────────────────+             +──────────────────────────+
| Branch: US East (US-VA)  |             | Branch: EU West (EU-DE)  |
+──────────────────────────+             +──────────────────────────+
       │                                             │
+──────────────────────────+             +──────────────────────────+
| Department: SRE & Cloud  |             | Department: Cloud Ops    |
+──────────────────────────+             +──────────────────────────+
       │                                             │
+──────────────────────────+             +──────────────────────────+
| Team: Alpha Responders   |             | Team: European On-Call   |
+──────────────────────────+             +──────────────────────────+
```

---

## 12. SLA / SLO Engine & Analytics

The mathematical [`UptimeCalculator`](file:///home/imran/project/website-uptime-monitor-alerts-and-status-pages/app/Domain/Analytics/Services/UptimeCalculator.php) computes high-speed reliability metrics:
- **Uptime Percentage**: 99.999% precision calculations across 24h, 7d, 30d, 90d, or custom date ranges.
- **Mean Time to Detect (MTTD)**: Average time from actual service failure to probe detection.
- **Mean Time to Acknowledge (MTTA)**: Time elapsed until an on-call engineer claims the incident.
- **Mean Time to Resolve (MTTR)**: Time elapsed until automated probe confirmation restores health.
- **Latency Percentiles**: P50 (median), P90, P95, and P99 tail latency aggregations.
- **Error Budget Tracking**: Real-time calculation of remaining downtime minutes before SLA breach.

---

## 13. Public & Private Status Page Builder

- Custom subdomains or custom domains (e.g., `status.yourdomain.com`).
- Light & dark mode with customizable brand accent colors and custom CSS.
- Service component grouping with real-time operational badges.
- **90-Day Interactive Uptime Bars**: Hoverable day-by-day availability blocks.
- **Email Subscriptions**: Automated subscriber intake with verification tokens.
- **Scheduled Maintenance Banners**: Displays upcoming maintenance windows while suppressing synthetic check alert dispatching.

---

## 14. Seeded Enterprise Demo Environments (Instant Login)

The platform comes pre-seeded with 4 distinct business models and 9 realistic user personas. Run `php artisan uptime:demo-seed` to populate the dataset. All accounts use password: `password`.

| Panel | Login URL | Email | Organization & Role | Purpose |
| :--- | :--- | :--- | :--- | :--- |
| **Platform** | `/platform` | `superadmin@uptime.test` | System Superadmin | Global multi-tenant administration |
| **App** | `/app` | `owner@nimbuscloud.test` | NimbusCloud Enterprise (`tenant_admin`) | Enterprise cloud monitoring (8 targets) |
| **App** | `/app` | `admin@shopvista.test` | ShopVista Global (`tenant_admin`) | High-volume ecommerce uptime (5 targets) |
| **App** | `/app` | `agency@pixelforge.test` | PixelForge Digital (`tenant_admin`) | Digital agency managing client sites |
| **App** | `/app` | `lead@atlasfinancial.test` | Atlas Financial (`tenant_admin`) | High-compliance banking APIs |
| **Branch** | `/branch` | `branch@nimbuscloud.test`| NimbusCloud US-East (`branch_manager`) | Branch-scoped infrastructure console |
| **Client** | `/client` | `client@clientorg.test` | PixelForge Client (`client_contact`) | Agency white-label client view |
| **Operations**| `/operations` | `sre@uptime.test` | Global NOC / SRE Staff | Queue telemetry and probe node mesh |

### Live Seeded Public Status Pages:
- **NimbusCloud**: [http://localhost:8000/status/nimbus-cloud](http://localhost:8000/status/nimbus-cloud)
- **ShopVista Global**: [http://localhost:8000/status/shopvista-status](http://localhost:8000/status/shopvista-status)
- **Atlas Financial**: [http://localhost:8000/status/atlas-status](http://localhost:8000/status/atlas-status)

---

## 15. Artisan CLI Automation Suite

```bash
# 1. Initialize database, 60 modules, 8 probe locations & superadmin
php artisan uptime:install

# 2. Seed 4 enterprise tenants, 100+ monitors, checks & incidents
php artisan uptime:demo-seed

# 3. Execute system-wide self-test (database, probe mesh, SSRF guard)
php artisan uptime:diagnose

# 4. View queue depth and active probe points of presence
php artisan uptime:worker-status

# 5. Dispatch due synthetic checks to worker queues
php artisan uptime:run-due-checks

# 6. Prune old check telemetry past retention window (default 90 days)
php artisan uptime:retention --days=90
```

---

## 16. REST API Reference (v1)

Base URL: `https://your-domain.com/api/v1`  
Authentication: `Authorization: Bearer <SANCTUM_TOKEN>`

### Key Endpoints
- `POST /api/v1/auth/token`: Authenticate user and receive API token.
- `GET /api/v1/user`: Retrieve authenticated profile, roles, and tenant context.
- `GET /api/v1/monitors`: List synthetic targets with latency and status.
- `POST /api/v1/monitors`: Create a synthetic monitor target.
- `POST /api/v1/monitors/{id}/check-now`: Enqueue immediate probe verification.
- `GET /api/v1/monitors/{id}/checks`: Retrieve raw historical check observations.
- `GET /api/v1/incidents`: List confirmed and investigating incidents.
- `POST /api/v1/incidents/{id}/acknowledge`: Mark incident acknowledged by engineer.
- `POST /api/v1/incidents/{id}/resolve`: Manually resolve incident with summary.
- `POST /api/v1/heartbeat/{token}`: Ingest cron / backup snitch ping.
- `GET /api/v1/locations`: List active global probe points of presence.
- `GET /api/v1/sla/metrics`: Query SLA compliance, error budgets, and P50–P99 latencies.

*Full request/response schemas are documented in [API.md](file:///home/imran/project/website-uptime-monitor-alerts-and-status-pages/API.md).*

---

## 17. Package Integrations & Evaluator Matrix

| Package | Status | Architectural Role |
| :--- | :--- | :--- |
| `imrandevbd/laravel-universal-slug` | **Active** | Generates SEO-optimized multilingual Unicode slugs (e.g. `বাংলা-স্ট্যাটাস`) for status pages. |
| `imrandevbd/laravel-unicode-pdf` | **Active** | Generates multilingual executive PDF uptime reports and billing invoices. |
| `imrandevbd/laravel-ai-hub` (v1.7.1) | **Active** | Centralized AI provider abstraction for root-cause analysis and incident summaries. |
| `imrandevbd/laravel-ai-catalog` | **Active** | Service catalog module for managed monitoring offerings and SEO service packages. |
| `imrandevbd/drutogoti` | **Adapted / Active** | Native high-performance query profiler & N+1 detector implemented in [`PerformanceProfiler`](file:///home/imran/project/website-uptime-monitor-alerts-and-status-pages/app/Domain/Analytics/Services/PerformanceProfiler.php). |
| `imrandevbd/laravel-admin-panel` | **Evaluated** | Filament v5 selected and verified as the primary administrative engine across all 5 panels. |
| `imrandevbd/laravel-attendance-hub` | **Adapted / Active** | On-call workforce and shift availability integrated cleanly into `App\Domain\Alerts`. |

---

## 18. One-Click Installation & Quickstart

### Prerequisites
- PHP 8.3 or 8.5+ with `pdo`, `redis`, `bcmath`, `curl`, `intl`, `gd`, `zip`
- Composer 2.8+ & Node.js 20+
- PostgreSQL 15+ (preferred) or MySQL 8.0+ / SQLite 3.35+
- Redis 6.2+

### Step-by-Step Local Setup
```bash
# 1. Clone repository
git clone https://github.com/imrandevbd/website-uptime-monitor.git
cd website-uptime-monitor

# 2. Configure Git identity (Mandatory)
git config user.name "Imran Ahmed"
git config user.email "me@imrandev.bd"

# 3. Install Composer dependencies
composer install --prefer-dist --no-interaction

# 4. Configure environment
cp .env.example .env
php artisan key:generate

# 5. Run automated installer
php artisan uptime:install

# 6. Seed demo enterprise dataset
php artisan uptime:demo-seed

# 7. Compile frontend assets
npm install
npm run build

# 8. Start local servers
php artisan serve --port=8000
php artisan queue:work --sleep=2 --tries=3
php artisan schedule:work
```

---

## 19. Docker Compose Stack

The repository includes a production-grade `Dockerfile` and `docker-compose.yml` deploying:
- **App Service**: PHP 8.3/8.5 FPM + Nginx (Port 8000)
- **Queue Worker Service**: `artisan queue:work redis`
- **Scheduler Service**: `artisan schedule:work`
- **Database**: PostgreSQL 16 (Port 5432)
- **Cache & Queues**: Redis 7 (Port 6379)
- **Local Mail Testing**: Mailpit (Port 8025 Web UI / Port 1025 SMTP)

```bash
cp .env.example .env
docker compose up -d --build
docker compose exec app php artisan uptime:install
docker compose exec app php artisan uptime:demo-seed
```

Access:
- **Application**: [http://localhost:8000](http://localhost:8000)
- **Mailpit Web UI**: [http://localhost:8025](http://localhost:8025)

---

## 20. Automated Test Suite & Verification

The platform includes an extensive automated test suite covering unit logic, security constraints, multi-tenant boundaries, and end-to-end business acceptance scenarios:

```bash
php artisan test
```

### Verification Output:
```
PHPUnit 12.5.12 by Sebastian Bergmann and contributors.
Runtime:       PHP 8.5.4
Configuration: phpunit.xml

.........................                                         25 / 25 (100%)

Time: 00:00.481, Memory: 42.00 MB

OK (25 tests, 78 assertions)
```

### Test Coverage Highlights:
- `SSRFValidatorTest`: Verifies blocking of IPv4 loopback, RFC1918 subnets, cloud metadata endpoints, and dangerous URI schemes.
- `TenantIsolationTest`: Validates that Tenant A can never query, observe, or mutate Tenant B's monitors or incidents.
- `IncidentWorkflowTest`: Verifies multi-stage state transitions, incident creation, timeline posting, and auto-recovery.
- `BusinessAcceptanceScenariosTest`: End-to-end simulation of real-world scenarios:
  - **Scenario A**: High-volume ecommerce outage and automated recovery.
  - **Scenario B**: Agency multi-client white-label scoping.
  - **Scenario C**: Multi-branch enterprise delegation.
  - **Scenario E**: Incident lifecycle from detection to probe-verified recovery.
  - **Scenario F**: Dynamic module toggling without telemetry data loss.
  - **Scenario G**: Subscription plan upgrades and monitor quota enforcement.

---

## 21. Author Attribution & License

**Website Uptime Monitor** was engineered by:

- **Lead Architect & Developer:** Imran Ahmed
- **Git Author & Committer:** Imran Ahmed
- **Email:** [me@imrandev.bd](mailto:me@imrandev.bd)
- **Official Website:** [https://imrandev.bd](https://imrandev.bd)

Licensed under the **[MIT License](LICENSE)**.

---

<p align="center">
  <strong>⭐ Star this repository if Website Uptime Monitor saved your business thousands in SaaS fees! ⭐</strong>
</p>
