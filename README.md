# Website Uptime Monitor — Enterprise SaaS Platform

[![PHP 8.3+](https://img.shields.io/badge/PHP-8.3%20%7C%208.4%20%7C%208.5-777BB4.svg?style=flat-square&logo=php)](https://php.net)
[![Laravel 13](https://img.shields.io/badge/Laravel-13.x-FF2D20.svg?style=flat-square&logo=laravel)](https://laravel.com)
[![Filament v5](https://img.shields.io/badge/Filament-v5.x-FFA500.svg?style=flat-square)](https://filamentphp.com)
[![PostgreSQL](https://img.shields.io/badge/Database-PostgreSQL%20%7C%20MySQL%20%7C%20SQLite-336791.svg?style=flat-square&logo=postgresql)](https://www.postgresql.org/)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg?style=flat-square)](LICENSE)

> **Lead Architect & Developer:** Imran Ahmed  
> **Email:** [me@imrandev.bd](mailto:me@imrandev.bd)  
> **Official Website:** [https://imrandev.bd](https://imrandev.bd)  

---

## 1. Executive Summary

**Website Uptime Monitor** is a production-grade, multi-tenant enterprise SaaS platform engineered for synthetic website monitoring, infrastructure observability, automated incident response, SLA tracking, and public status pages.

Designed with an ultra-modular architecture, the platform features a dynamic **60-Module SaaS Registry**, multi-branch enterprise operational delegation, agency white-label portals, and distributed multi-region probe verification.

---

## 2. Platform Architecture & Capabilities

```
+-----------------------------------------------------------------------------------+
|                               Client Interfaces                                   |
|   Public Status Pages  |  Customer Portal  |  Branch Console  |  Platform Admin   |
+-----------------------------------------------------------------------------------+
                                         |
                                 HTTPS / WSS / API
                                         |
+-----------------------------------------------------------------------------------+
|                             Modular Monolith Engine                               |
|   SSRF Validator  |  Tenant Scope  |  Branch Scope  |  Sanctum Auth  |  Profiler  |
+-----------------------------------------------------------------------------------+
                                         |
+-----------------------------------------------------------------------------------+
|                           Domain Layer (app/Domain/)                              |
|   Identity   | Tenancy   | Branches  | Modules (60)  | Monitoring   | Incidents   |
|   Alerts     | Status    | Analytics | Automation    | Assets       | AI Hub      |
|   Billing    | CMS Pages | Reports   | Unicode PDF   | Performance  | Audit Logs  |
+-----------------------------------------------------------------------------------+
```

### Key Architectural Pillars:
1. **Dynamic 60-Module Registry**: Every SaaS feature is represented as an independently configurable module. Modules can be enabled or disabled per tenant or per enterprise branch with zero data loss.
2. **5 Filament v5 Administration Panels**:
   - `/platform`: SaaS Superadmin governance and global probe mesh.
   - `/app`: Business Owner monitoring dashboard, alert rules, status pages, and AI copilot.
   - `/branch`: Branch and regional team operations.
   - `/client`: Agency client portal with white-label customer visibility.
   - `/operations`: SRE and operations console with dead-letter queue telemetry.
3. **Multi-Stage Finite State Machine**: Eliminates alert fatigue via confirmation thresholds, suspected down states, and probe-verified recoveries.
4. **Defense-in-Depth SSRF Validator**: Hardened network barrier disallowing synthetic probes from accessing loopback (127.0.0.0/8), private RFC1918 subnets, cloud metadata endpoints (169.254.169.254), or dangerous protocols (`file://`, `phar://`, `gopher://`).
5. **AI Operations Center**: Integrates `imrandevbd/laravel-ai-hub` (v1.7.1) for incident root cause analysis, automated executive postmortems, and public status update drafting.

---

## 3. Package Integration & Evaluation Matrix

All required packages have been evaluated and integrated or adapted:

| Package | Status | Role & Integration Details |
| :--- | :--- | :--- |
| `imrandevbd/laravel-universal-slug` | **Installed & Active** | Generates SEO and multilingual Unicode slugs for status pages (e.g. `বাংলা-স্ট্যাটাস`). |
| `imrandevbd/laravel-unicode-pdf` | **Installed & Active** | Powers multilingual executive PDF availability reports and billing invoices. |
| `imrandevbd/laravel-ai-hub` (v1.7.1) | **Installed & Active** | Centralized AI provider abstraction for root-cause analysis and incident summaries. |
| `imrandevbd/laravel-ai-catalog` | **Installed & Active** | Service catalog module for managed monitoring offerings and SEO service packages. |
| `imrandevbd/drutogoti` | **Adapted / Active** | Native high-performance query profiler & N+1 detector implemented in `App\Domain\Analytics\Services\PerformanceProfiler`. |
| `imrandevbd/laravel-admin-panel` | **Evaluated** | Filament v5 selected and verified as the primary administrative engine across all 5 panels. |
| `imrandevbd/laravel-attendance-hub` | **Adapted / Active** | On-call workforce and shift availability integrated cleanly into `App\Domain\Alerts`. |

---

## 4. Quickstart Installation

### Prerequisites
- PHP 8.3 or 8.5+ with `pdo`, `redis`, `bcmath`, `curl`, `intl`, `gd`, `zip`
- Composer 2.8+ & Node.js 20+
- PostgreSQL 15+ (or MySQL 8.0+ / SQLite 3.35+)
- Redis 6.2+

### Step-by-Step Setup
```bash
# 1. Clone repository
git clone https://github.com/imrandevbd/website-uptime-monitor.git
cd website-uptime-monitor

# 2. Configure Git Identity (Mandatory)
git config user.name "Imran Ahmed"
git config user.email "me@imrandev.bd"

# 3. Install Composer dependencies
composer install --prefer-dist --no-interaction

# 4. Environment setup
cp .env.example .env
php artisan key:generate

# 5. Run automated installer (Migrations, 60 Modules sync, 8 Probes, Superadmin)
php artisan uptime:install

# 6. Seed Enterprise Demonstration Dataset (4 Tenants, 100+ Monitors, Incidents)
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

## 5. Docker One-Click Deployment

A complete container stack with PostgreSQL 16, Redis 7, App, Workers, and Mailpit is included:

```bash
cp .env.example .env
docker compose up -d --build
docker compose exec app php artisan uptime:install
docker compose exec app php artisan uptime:demo-seed
```

Access points:
- **Web Application**: `http://localhost:8000`
- **Mailpit Email Testing UI**: `http://localhost:8025`

---

## 6. Seeded Demonstration Credentials

All seeded accounts use password: `password`

| Panel | URL | Email | Role |
| :--- | :--- | :--- | :--- |
| **Platform** | `/platform` | `superadmin@uptime.test` | Platform Superadmin |
| **App** | `/app` | `owner@nimbuscloud.test` | Tenant Admin (NimbusCloud Enterprise) |
| **App** | `/app` | `admin@shopvista.test` | Tenant Admin (ShopVista Global) |
| **App** | `/app` | `agency@pixelforge.test` | Agency Lead (PixelForge Digital Agency) |
| **Branch** | `/branch` | `branch@nimbuscloud.test` | Branch Operations Lead |
| **Client** | `/client` | `client@clientorg.test` | Client Contact (Agency Portal) |
| **Operations** | `/operations` | `sre@uptime.test` | Staff SRE / Probe Telemetry |

### Public Status Page Demos:
- NimbusCloud: `http://localhost:8000/status/nimbus-cloud`
- ShopVista Global: `http://localhost:8000/status/shopvista-status`
- Atlas Financial: `http://localhost:8000/status/atlas-status`

---

## 7. Artisan Command Suite

| Command | Description |
| :--- | :--- |
| `php artisan uptime:install` | Runs migrations, initializes 60 modules, seeds 8 probe nodes, provisions superadmin. |
| `php artisan uptime:demo-seed` | Populates 4 multi-tenant enterprise companies, 100+ monitors, check history, and status pages. |
| `php artisan uptime:diagnose` | Comprehensive self-test inspecting database, probe mesh, and SSRF security defenses. |
| `php artisan uptime:worker-status` | Displays real-time telemetry across queue workers and active probe points of presence. |
| `php artisan uptime:run-due-checks`| Dispatches scheduled monitoring checks to queue workers. |
| `php artisan uptime:retention` | Prunes raw check telemetry older than configured retention period (default: 90 days). |

---

## 8. REST API Reference (v1)

Version 1.0 of the REST API is secured with Laravel Sanctum Bearer tokens:

- `POST /api/v1/auth/token`: Authenticate and obtain API token.
- `GET /api/v1/user`: Get current user context and active tenant.
- `GET /api/v1/monitors`: List synthetic monitors with status and latency metrics.
- `POST /api/v1/monitors`: Provision a new synthetic check target.
- `POST /api/v1/monitors/{id}/check-now`: Dispatch instant probe verification.
- `GET /api/v1/monitors/{id}/checks`: Retrieve raw historical check observations.
- `GET /api/v1/incidents`: List outage incidents.
- `POST /api/v1/incidents/{id}/acknowledge`: Acknowledge open incident.
- `POST /api/v1/incidents/{id}/resolve`: Resolve open incident with RCA summary.
- `POST /api/v1/heartbeat/{token}`: Ingest cron / daemon heartbeat pings.
- `GET /api/v1/locations`: List active multi-region probe points of presence.
- `GET /api/v1/sla/metrics`: Query SLA compliance, error budgets, and P50-P99 latencies.

Detailed documentation is available in [API.md](file:///home/imran/project/website-uptime-monitor-alerts-and-status-pages/API.md).

---

## 9. Comprehensive Documentation Index

- [ARCHITECTURE.md](file:///home/imran/project/website-uptime-monitor-alerts-and-status-pages/ARCHITECTURE.md) — System design, bounded contexts, domain layer, and diagrams.
- [MODULES.md](file:///home/imran/project/website-uptime-monitor-alerts-and-status-pages/MODULES.md) — Complete 60-module SaaS capability matrix and plan gating.
- [MONITORING_ENGINE.md](file:///home/imran/project/website-uptime-monitor-alerts-and-status-pages/MONITORING_ENGINE.md) — Probe protocols, state machine, and latency percentiles.
- [SECURITY.md](file:///home/imran/project/website-uptime-monitor-alerts-and-status-pages/SECURITY.md) — SSRF defense, credential encryption, and audit logging.
- [MULTI_TENANCY.md](file:///home/imran/project/website-uptime-monitor-alerts-and-status-pages/MULTI_TENANCY.md) — Single-database multi-tenant scoping and global scopes.
- [MULTI_BRANCH.md](file:///home/imran/project/website-uptime-monitor-alerts-and-status-pages/MULTI_BRANCH.md) — Enterprise hierarchy: Tenant -> Branch -> Department -> Team.
- [INTEGRATIONS.md](file:///home/imran/project/website-uptime-monitor-alerts-and-status-pages/INTEGRATIONS.md) — Email, Slack, Discord, Telegram, Teams, Webhook HMAC.
- [AI_FEATURES.md](file:///home/imran/project/website-uptime-monitor-alerts-and-status-pages/AI_FEATURES.md) — Laravel AI Hub integration, incident copilot, root cause engine.
- [BILLING.md](file:///home/imran/project/website-uptime-monitor-alerts-and-status-pages/BILLING.md) — Subscription tiers, quota enforcement, invoices, and coupons.
- [DEMO.md](file:///home/imran/project/website-uptime-monitor-alerts-and-status-pages/DEMO.md) — Demonstration guide and credentials.
- [INSTALLATION.md](file:///home/imran/project/website-uptime-monitor-alerts-and-status-pages/INSTALLATION.md) — Bare-metal and Docker setup instructions.
- [DEPLOYMENT.md](file:///home/imran/project/website-uptime-monitor-alerts-and-status-pages/DEPLOYMENT.md) — High-availability Linux production guide.
- [TESTING.md](file:///home/imran/project/website-uptime-monitor-alerts-and-status-pages/TESTING.md) — Automated test suite and business scenario coverage.
- [CHANGELOG.md](file:///home/imran/project/website-uptime-monitor-alerts-and-status-pages/CHANGELOG.md) — Release notes and version history.
- [CONTRIBUTING.md](file:///home/imran/project/website-uptime-monitor-alerts-and-status-pages/CONTRIBUTING.md) — Contribution guidelines and coding conventions.

---

## 10. Automated Test Suite

Run the full PHPUnit test suite:
```bash
php artisan test
```

Verification status:
- **25 Passed Tests** (100% pass rate)
- **78 Assertions**
- **0 Failures, 0 Errors**

---

## 11. Author & Developer Attribution

This application was engineered by:

- **Developer:** Imran Ahmed
- **Git Author & Committer:** Imran Ahmed
- **Email:** [me@imrandev.bd](mailto:me@imrandev.bd)
- **Website:** [https://imrandev.bd](https://imrandev.bd)

Licensed under the [MIT License](LICENSE).
