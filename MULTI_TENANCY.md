# Website Uptime Monitor — Multi-Tenancy Architecture

**Developer:** Imran Ahmed  
**Email:** me@imrandev.bd  
**Official Website:** [https://imrandev.bd](https://imrandev.bd)  
**Tenancy Model:** Single-Database, Schema-Scoped Multi-Tenancy  

---

## 1. Tenancy Model Overview

Website Uptime Monitor uses a unified, single-database multi-tenant design with automatic row-level query scoping (`tenant_id`). This delivers:
1. **High Operational Efficiency**: Minimal database connection overhead, unified backup routines, and instantaneous tenant provisioning.
2. **Strict Cryptographic & Query Boundaries**: Every tenant's data is isolated via global Eloquent scopes (`TenantScope`).
3. **Flexible Access Modes**: Supports multi-tenant SaaS, single-tenant self-hosted, agency white-label, and regional multi-branch operations.

---

## 2. Tenant Resolution Flow

Incoming requests are resolved into active tenant contexts via `App\Domain\Tenancy\TenantManager`:

```
               [ Incoming HTTP Request ]
                           |
       +-------------------+-------------------+
       |                                       |
  [ Web Subdomain ]                       [ API Request ]
  e.g. nimbuscloud.uptime.test            Header: X-Tenant-ID / Slug
       |                                       |
       +-------------------+-------------------+
                           |
                           v
        [ TenantManager::resolveFromRequest() ]
                           |
             +-------------+-------------+
             |                           |
       [ Tenant Found ]           [ No Tenant ]
             |                           |
     Sets Context in             Resolves Platform
     TenantManager::setTenant()  Admin Context
```

---

## 3. The `BelongsToTenant` Trait

Any model containing tenant-specific data implements `BelongsToTenant`:

```php
namespace App\Domain\Tenancy\Traits;

use App\Domain\Tenancy\Models\Tenant;
use App\Domain\Tenancy\Scopes\TenantScope;
use App\Domain\Tenancy\TenantManager;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToTenant
{
    protected static function bootBelongsToTenant(): void
    {
        static::addGlobalScope(new TenantScope());

        static::creating(function ($model) {
            if (!$model->tenant_id && TenantManager::getTenant()) {
                $model->tenant_id = TenantManager::getTenant()->id;
            }
        });
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
```

### Models Guarded by `BelongsToTenant`:
- `Monitor`, `MonitorCheck`, `MonitorStatusTransition`
- `Incident`, `IncidentUpdate`, `IncidentComment`, `IncidentPostmortem`
- `NotificationChannel`, `AlertRule`, `NotificationDelivery`
- `StatusPage`, `StatusPageComponent`, `StatusPageSubscriber`
- `SlaPolicy`, `SlaComplianceRecord`, `MaintenanceWindow`
- `Subscription`, `Invoice`
- `ServiceAsset`, `AutomationRule`
- `OnCallSchedule`, `OnCallRotation`, `OnCallOverride`

---

## 4. Tenant Roles & Permissions

Users can belong to multiple tenants via the pivot table `tenant_user`:
- `tenant_admin`: Complete administrative ownership over monitors, users, integrations, status pages, billing, and module toggles.
- `site_reliability_engineer`: Create/edit monitors, acknowledge incidents, manage on-call schedules, create maintenance windows.
- `viewer`: Read-only access to monitoring dashboards, incident timelines, and SLA reports.
- `client_contact`: Read-only access restricted to agency-shared monitors within the `/client` portal.

