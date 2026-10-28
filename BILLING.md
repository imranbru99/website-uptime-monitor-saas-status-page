# Website Uptime Monitor — Billing & Subscription Architecture

**Developer:** Imran Ahmed  
**Email:** me@imrandev.bd  
**Official Website:** [https://imrandev.bd](https://imrandev.bd)  
**Package:** `App\Domain\Billing`  

---

## 1. Subscription Tiers & Quota Limits

Website Uptime Monitor ships with 5 pre-configured enterprise subscription plans:

| Tier | Price / Month | Check Interval | Monitor Quota | Status Pages | Probe Regions | On-Call & SLA |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **Free** | $0 / mo | 5 minutes (300s) | 5 monitors | 1 page | 1 region | No |
| **Starter** | $19 / mo | 3 minutes (180s) | 25 monitors | 3 pages | 2 regions | No |
| **Professional** | $49 / mo | 1 minute (60s) | 100 monitors | 10 pages | 5 regions | Yes |
| **Business** | $149 / mo | 30 seconds | 500 monitors | 25 pages | 8 regions | Yes + AI Copilot |
| **Enterprise** | $499 / mo | 10 seconds | Unlimited | Unlimited | All + Private Agents | Multi-Branch + White-Label |

---

## 2. Quota Enforcement Logic

Quota checks are performed synchronously in `App\Domain\Billing\Services\BillingManager`:
- **Monitor Quota**: Before creating a new monitor, the system verifies `tenant->monitors()->count() < plan->max_monitors`.
- **Interval Enforcement**: Prevents configuring check intervals faster than the plan's minimum allowable frequency (`min_interval_seconds`).
- **Feature Gating**: Certain modules (e.g. `real_browser_synthetic`, `private_probe_agents`, `ai_root_cause_analysis`) require minimum plan tiers.

---

## 3. Invoices & Coupons

- **Invoices**: Automatically generated for subscription renewals with unique sequential numbering (e.g., `INV-2026-0001`), tax rates, and PDF download capability via `imrandevbd/laravel-unicode-pdf`.
- **Promotional Coupons**: Supports percentage discounts (e.g. `SUMMER20`) and fixed currency deductions, with maximum redemption counts and expiration dates.

