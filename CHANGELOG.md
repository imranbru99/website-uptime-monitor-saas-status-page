# Changelog

All notable changes to the **Website Uptime Monitor** SaaS platform are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [1.0.0] - 2026-10-28

**Developer & Lead Architect:** Imran Ahmed <me@imrandev.bd>  
**Official Website:** [https://imrandev.bd](https://imrandev.bd)  

### Added
- **Core Architecture**:
  - Full modular monolith architecture with bounded contexts in `app/Domain/` on Laravel 13 & PHP 8.5.
  - 5 independent Filament v5 administrative panels: `/platform`, `/app`, `/branch`, `/client`, `/operations`.
  - Single-database multi-tenant scoping via `TenantScope` and `BelongsToTenant` trait.
  - Multi-branch enterprise hierarchy (`Tenant` -> `Branch` -> `Department` -> `Team`).
  - Dynamic 60-Module SaaS registry with tenant and branch toggles and zero-data-loss architecture.
- **Monitoring & Observability**:
  - Synthetic Check Runner supporting 10 protocols: HTTP, HTTPS, REST API, TCP Port, DNS, SSL/TLS, ICMP Ping, Keyword, Heartbeat, and Server/DB ports.
  - Finite State Machine with confirmation thresholds, suspected down states, and recovery verification.
  - Global probe mesh initialized across 8 global points of presence (Singapore, Tokyo, London, Frankfurt, New York, Mumbai, Sydney, São Paulo).
  - High-precision SLA/SLO engine calculating 99.999% uptime, MTTD, MTTA, MTTR, error budgets, and P50-P99 latency percentiles.
  - SSRF Security Validator blocking loopback, private subnets (RFC1918), cloud metadata endpoints, and unsafe URI schemes.
- **Incidents & Alerts**:
  - Automated incident lifecycle with severity auto-classification and incident correlation engine.
  - Multi-channel notification dispatchers for Email (SMTP), Slack, Discord, Telegram, Microsoft Teams, and Webhooks with HMAC-SHA256 signatures.
  - On-call schedule manager with daily/weekly rotations and coverage overrides.
- **Status Pages**:
  - Public status page builder with custom slugs, dark/light modes, 90-day uptime bars, and active incident timelines.
  - Email subscriber management with verification tokens.
- **AI Operations Center**:
  - Integrated `imrandevbd/laravel-ai-hub` (v1.7.1) for automated incident summaries, root cause suggestions, and public status update drafting.
  - Interactive AI Assistant console in Filament.
- **Reporting & Analytics**:
  - Native performance profiler detecting slow queries and duplicate N+1 queries.
  - Multilingual uptime reporting with HTML, CSV, and Unicode PDF export via `imrandevbd/laravel-unicode-pdf`.
- **Public Marketing & CMS**:
  - Marketing landing page, interactive live demo showcase, feature catalog, dynamic pricing table, and lead capture forms.
- **Artisan Automation Suite**:
  - `uptime:install`: automated database installer and 60-module sync.
  - `uptime:demo-seed`: seeds 4 enterprise tenants (NimbusCloud, ShopVista, PixelForge, Atlas), 9 user accounts, 100+ monitors.
  - `uptime:diagnose`: automated health check inspecting database, probe mesh, and SSRF defenses.
  - `uptime:worker-status`: real-time queue telemetry and probe node status table.
  - `uptime:retention`: prunes old check records past retention threshold.
- **Infrastructure & Testing**:
  - Production `Dockerfile`, `docker-compose.yml`, Nginx, and Supervisor configurations.
  - Comprehensive automated test suite with 25 unit and feature tests covering real-world business scenarios.
