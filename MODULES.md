# Website Uptime Monitor — 60 Modules Matrix

**Developer:** Imran Ahmed  
**Email:** me@imrandev.bd  
**Official Website:** [https://imrandev.bd](https://imrandev.bd)  
**System Version:** 1.0.0 Enterprise  

The platform is constructed around a dynamic 60-module modular architecture. Each module can be toggled per tenant or overridden per enterprise branch without deleting historical telemetry or causing schema regressions.

---

## 1. Module Management Overview

- **Core / Mandatory Modules**: Cannot be disabled by tenants (essential for operational stability).
- **Plan Tier Gating**: Automatically limits access based on active subscription (Free, Starter, Professional, Business, Enterprise).
- **Branch Level Overrides**: Multi-branch organizations can toggle regional modules independently.
- **Zero-Loss Deactivation**: Toggling a module to disabled only suppresses its UI resources, jobs, and endpoints; underlying monitor records and configuration are preserved intact.

---

## 2. Complete 60-Module Matrix

| # | Slug | Module Name | Category | Plan Tier | Mandatory | Description |
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

