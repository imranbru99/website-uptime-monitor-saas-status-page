# Website Uptime Monitor — Demonstration Guide

**Developer:** Imran Ahmed  
**Email:** me@imrandev.bd  
**Official Website:** [https://imrandev.bd](https://imrandev.bd)  
**Command:** `php artisan uptime:demo-seed`  

---

## 1. Seeded Multi-Tenant Organizations

The demo seeder constructs 4 distinct enterprise organizations representing different business models and monitoring operational tiers:

### 1. NimbusCloud Enterprise
- **Slug:** `nimbuscloud`
- **Domain:** `nimbuscloud.test`
- **Industry:** Cloud Infrastructure & SaaS Provider
- **Subscription Tier:** Enterprise Plan ($499/mo)
- **Monitors:** 8 Enterprise targets (API gateways, PostgreSQL clusters, Redis sentinel, Auth microservices, DNS, SSL)
- **Status Page:** `http://localhost:8000/status/nimbus-cloud`
- **Primary Persona:** Sarah Jenkins (`owner@nimbuscloud.test`)

### 2. ShopVista Global
- **Slug:** `shopvista`
- **Domain:** `shopvista.test`
- **Industry:** High-Volume Ecommerce Platform
- **Subscription Tier:** Business Plan ($149/mo)
- **Monitors:** 5 Global Ecommerce targets (Storefront checkout, Payment webhook receiver, ElasticSearch cluster, Inventory sync, CDN)
- **Status Page:** `http://localhost:8000/status/shopvista-status`
- **Primary Persona:** Marcus Vance (`admin@shopvista.test`)

### 3. PixelForge Digital Agency
- **Slug:** `pixelforge`
- **Domain:** `pixelforge.test`
- **Industry:** Creative Digital Agency & Managed Hosting
- **Subscription Tier:** Professional Plan ($49/mo)
- **Monitors:** 5 Client production websites & SSL certificates
- **Features:** Client Organization Portal (`/client`) demonstration
- **Primary Persona:** Elena Rostova (`agency@pixelforge.test`)

### 4. Atlas Financial Technologies
- **Slug:** `atlas`
- **Domain:** `atlasfinancial.test`
- **Industry:** FinTech & Banking APIs
- **Subscription Tier:** Enterprise Plan ($499/mo)
- **Monitors:** 5 High-compliance financial services (SWIFT gateway, Core ledger DB, KYC API, Multi-region probes)
- **Status Page:** `http://localhost:8000/status/atlas-status`
- **Primary Persona:** David Sterling (`lead@atlasfinancial.test`)

---

## 2. Seeded User Accounts & Roles

All accounts use the standard password: `password`

| Panel | Email | Role | Scope |
| :--- | :--- | :--- | :--- |
| **Platform** (`/platform`) | `superadmin@uptime.test` | Platform Superadmin | Global System Administration |
| **App** (`/app`) | `owner@nimbuscloud.test` | Tenant Admin | NimbusCloud Enterprise |
| **App** (`/app`) | `admin@shopvista.test` | Tenant Admin | ShopVista Global |
| **App** (`/app`) | `agency@pixelforge.test` | Agency Lead | PixelForge Digital Agency |
| **App** (`/app`) | `lead@atlasfinancial.test` | Tenant Admin | Atlas Financial |
| **Branch** (`/branch`) | `branch@nimbuscloud.test` | Branch Operations Lead | US East Branch |
| **Client** (`/client`) | `client@clientorg.test` | Client Contact | PixelForge Shared Monitors |
| **Operations** (`/operations`)| `sre@uptime.test` | Staff SRE / Ops | Global Infrastructure & Probes |
| **App** (`/app`) | `engineer@nimbuscloud.test`| Site Reliability Engineer | NimbusCloud Response Team |

---

## 3. Demonstration Walkthrough

1. **Log in to `/platform`** with `superadmin@uptime.test`:
   - Inspect all 4 tenants, the 60 registered SaaS modules, 8 global probe locations, and active subscriptions.
2. **Log in to `/app`** with `owner@nimbuscloud.test`:
   - Inspect the real-time monitor dashboard with active latency charts.
   - Navigate to `/app/module-manager-page` to test dynamic module toggling.
   - Navigate to `/app/ai-assistant-page` to test the AI Operations Center.
   - Navigate to `/app/reports-page` to generate HTML, CSV, and Unicode PDF uptime reports.
3. **Visit Public Status Pages**:
   - `http://localhost:8000/status/nimbus-cloud`
   - `http://localhost:8000/status/shopvista-status`
   - Test dark mode toggle, inspect 90-day uptime bars, and test email subscriber registration.
4. **Log in to `/client`** with `client@clientorg.test`:
   - Observe the agency white-label client experience showing only client-assigned monitors.
5. **Log in to `/branch`** with `branch@nimbuscloud.test`:
   - Observe regional branch monitoring scoped to US East infrastructure.

