# Website Uptime Monitor — System Architecture

**Developer:** Imran Ahmed  
**Email:** me@imrandev.bd  
**Official Website:** [https://imrandev.bd](https://imrandev.bd)  
**Application Version:** 1.0.0 Enterprise  
**Framework:** Laravel 13 on PHP 8.5, Filament v5  

---

## 1. Architectural Philosophy

Website Uptime Monitor is designed as a high-throughput, fault-tolerant **Modular Monolith** for the control plane and administrative interfaces, paired with distributed synthetic probe workers. The design balances enterprise modularity, multi-tenant security isolation, and sub-second operational telemetry.

```
+-----------------------------------------------------------------------------------+
|                                Client Applications                                |
|  Public Status Pages  |  Customer Portal  |  Branch Console  |  Platform SuperAdmin|
+-----------------------------------------------------------------------------------+
                                          |
                                    HTTPS / WSS / API
                                          |
+-----------------------------------------------------------------------------------+
|                                 Routing & Gateway                                 |
|   Subdomain Resolver  |  Tenant Scope Filter  |  Sanctum Auth  |  Rate Limiting   |
+-----------------------------------------------------------------------------------+
                                          |
+-----------------------------------------------------------------------------------+
|                        Domain Layer (app/Domain/)                                 |
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

## 2. Bounded Contexts & Domain Organization

The core business logic resides under `app/Domain/` in distinct, decoupled bounded contexts:

### 2.1 Identity (`app/Domain/Identity`)
- **User Model**: Multi-tenant user with Filament panel access policies (`FilamentUser`), Sanctum API token generation (`HasApiTokens`), two-factor readiness, and audit logging.
- **Role & Authorization**: Role-based access control supporting `superadmin`, `tenant_admin`, `branch_manager`, `site_reliability_engineer`, `viewer`, and `client_contact`.
- **UserPreference**: Per-user theme, timezone, locale, and notification digest configurations.
- **AuditLog**: Immutable historical logs of security-sensitive operations and tenant configuration changes.

### 2.2 Tenancy (`app/Domain/Tenancy`)
- **Tenant Model**: Company account with slug, custom domain, subdomain routing, plan assignment, and operational quotas.
- **TenantManager**: Singleton providing tenant resolution from request hostnames, headers (`X-Tenant-ID`), or authenticated session context.
- **TenantScope & BelongsToTenant**: Global query scope ensuring zero cross-tenant data leakage across all tenant-owned models.

### 2.3 Branches (`app/Domain/Branches`)
- **Hierarchy**: `Tenant` -> `Branch` -> `Department` -> `Team`.
- **BranchManager**: Resolves active operational branch context for regional data segregation and enterprise delegation.
- **BranchScope & BelongsToBranch**: Scopes monitors, incidents, and teams to specific geographical or organizational units.

### 2.4 Dynamic Modules (`app/Domain/Modules`)
- **ModuleRegistry**: Canonical registry of 60 enterprise SaaS modules with metadata, default states, and plan tier requirements.
- **ModuleManager**: Resolves feature flags dynamically at tenant and branch levels with fallbacks and mandatory module guarantees.
- **Data Integrity**: Disabling a module never destroys database records; it simply suppresses navigation, execution, and API accessibility.

### 2.5 Monitoring Infrastructure (`app/Domain/Monitoring`)
- **Monitor**: Core synthetic check target supporting 10 protocols: HTTP, HTTPS, REST API, TCP, DNS, SSL/TLS, ICMP Ping, Keyword, Heartbeat, and Server/DB ports.
- **SSRFValidator**: Strict network boundary guard preventing probe checks from reaching loopback (127.0.0.0/8), RFC1918 private subnets, cloud metadata services (169.254.169.254), or dangerous URI schemes (`file://`, `phar://`, `gopher://`).
- **CheckRunner**: Protocol-agnostic execution engine performing real TCP handshakes, DNS queries, SSL certificate expiration calculations, and HTTP header assertions.
- **MonitoringStateMachine**: Resilient 6-state lifecycle: `pending` -> `healthy` -> `degraded` -> `suspected_down` -> `down` -> `recovering` -> `healthy`. Includes confirmation thresholds to eliminate false positives.

### 2.6 Incidents & Escalation (`app/Domain/Incidents`)
- **Incident**: Outage tracking with automated severity classification, impacted service tagging, and root cause classification.
- **CorrelationEngine**: Automatically clusters simultaneous multi-monitor failures into a single root parent incident to prevent alert storms.
- **IncidentUpdate & IncidentPostmortem**: Real-time status communication timeline and post-incident review recording.

### 2.7 Alerts & Notifications (`app/Domain/Alerts`)
- **AlertDispatcher**: Evaluates alert rules, schedules, notification channel types, and cooldown windows before triggering notifications.
- **Channels**: Native dispatchers for Email (SMTP), Slack (Webhooks & Bot API), Discord, Telegram, Microsoft Teams, and Webhooks with HMAC-SHA256 signatures.
- **OnCallSchedule**: Manages rotations, primary/secondary responders, and temporary coverage overrides.

### 2.8 Public & Private Status Pages (`app/Domain/StatusPages`)
- **StatusPage**: Customizable status portal with dark/light themes, custom CSS, custom domains, and announcement banners.
- **StatusPageComponent**: Component grouping with real-time operational state calculation and 90-day uptime bars.
- **StatusPageSubscriber**: Email and webhook subscriber management with verification tokens.

### 2.9 Analytics & SLA (`app/Domain/Analytics`)
- **UptimeCalculator**: High-speed mathematical aggregation of uptime percentage (99.999%), MTTD (Mean Time to Detect), MTTA (Mean Time to Acknowledge), MTTR (Mean Time to Resolve), latency percentiles (P50, P90, P95, P99), and error budget burn.
- **PerformanceProfiler**: Native query analyzer detecting slow queries and duplicate N+1 queries during application execution.

### 2.10 AI Operations Center (`app/Domain/ArtificialIntelligence`)
- Powered by `imrandevbd/laravel-ai-hub` with fallback adapters.
- Provides real-time incident root-cause analysis, automated customer status page draft updates, and observability diagnostics.

---

## 3. Administrative Interface Architecture

The platform provides 5 isolated Filament v5 administration panels:

| Panel | Route Prefix | Primary Audience | Key Responsibilities |
| :--- | :--- | :--- | :--- |
| **Platform** | `/platform` | SaaS Superadmins | Global tenant management, plan provisioning, global probe mesh, system settings |
| **App** | `/app` | Tenant Business Owners | Monitor management, incident response, status pages, alert rules, 60-module manager, AI Copilot, reports |
| **Branch** | `/branch` | Regional Ops / Branch Leads | Branch-scoped monitors, local team schedules, regional incident response |
| **Client** | `/client` | Agency Clients | Read-only target observability, assigned monitor metrics, shared status pages |
| **Operations** | `/operations` | SREs & Support Staff | Cross-tenant incident management, probe node telemetry, dead-letter queue monitoring |

---

## 4. Multi-Tenant Isolation Strategy

```
                                [ Incoming Request ]
                                         |
                                [ Subdomain / Header ]
                                         |
                            [ TenantManager::setTenant() ]
                                         |
                +-------------------------------------------------+
                | Global Scope: TenantScope (where tenant_id = X) |
                +-------------------------------------------------+
                                         |
                                [ Model Queries ]
                  SELECT * FROM monitors WHERE tenant_id = 1;
```

1. **Database Strategy**: Shared database with schema/row-level tenant scoping (`tenant_id`).
2. **Automatic Isolation**: The `BelongsToTenant` trait automatically applies `TenantScope` to Eloquent queries and injects the active `tenant_id` on model creation.
3. **Cross-Tenant Prevention**: Platform administrators bypass tenant scoping only when explicitly accessing `/platform` resources. All business logic in `/app`, `/branch`, `/client`, and `/api/v1` remains strictly constrained.

---

## 5. Security Architecture

1. **SSRF Mitigation**:
   - `SSRFValidator` runs before any outbound network probe check.
   - Prohibits IPv4 loopback (`127.0.0.0/8`), IPv6 loopback (`::1`), link-local metadata addresses (`169.254.0.0/16`), and private RFC1918 subnets (`10.0.0.0/8`, `172.16.0.0/12`, `192.168.0.0/16`).
   - Disallows non-HTTP/TCP protocols like `file://`, `phar://`, `gopher://`, and `ftp://`.
2. **Encrypted Credentials**:
   - Authentication tokens, webhook signing secrets, and basic authentication passwords are encrypted using AES-256-CBC via Laravel's encrypter.
3. **Webhook Signatures**:
   - Outbound webhooks include an `X-Uptime-Signature` header computed as `sha256(payload, secret)` to ensure integrity and authenticity.
4. **API Token Security**:
   - Sanctum Bearer tokens are hashed with SHA-256 before storage in `personal_access_tokens`.
