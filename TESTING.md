# Website Uptime Monitor — Automated Testing Suite

**Developer:** Imran Ahmed  
**Email:** me@imrandev.bd  
**Official Website:** [https://imrandev.bd](https://imrandev.bd)  
**Test Runner:** PHPUnit 12 on Laravel 13  

---

## 1. Test Architecture & Coverage

The automated test suite provides regression verification across unit logic, domain boundaries, security controls, and end-to-end business acceptance scenarios:

- **Unit Tests (`tests/Unit`)**:
  - `SSRFValidatorTest`: Verifies blocking of IPv4 loopback (127.0.0.1), private RFC1918 subnets (10.0.0.1, 192.168.1.1), cloud metadata addresses (169.254.169.254), and dangerous URI schemes (`file://`, `phar://`, `gopher://`).
  - `ModuleManagerTest`: Tests dynamic module enabling, disabling, tenant isolation, and mandatory module protection.
  - `UptimeCalculatorTest`: Validates uptime percentage calculation, MTTD, MTTA, MTTR, error budget, and P50-P99 latency percentiles.

- **Feature Tests (`tests/Feature`)**:
  - `ApiV1Test`: Full coverage of `/api/v1` endpoints (Authentication, Monitors CRUD, Instant Check-Now, Incidents, Heartbeats, Locations, SLA).
  - `TenantIsolationTest`: Verifies that Tenant A cannot query, update, or observe Tenant B's monitors or incidents.
  - `IncidentWorkflowTest`: Tests failure detection, confirmation threshold, incident creation, timeline update, alert dispatch, and auto-recovery.
  - `MonitorManagementTest`: Validates monitor creation, pause/resume, and check recording.
  - `StatusPageTest`: Tests public status page rendering, component status calculation, and email subscriber intake.
  - `BusinessAcceptanceScenariosTest`: End-to-end simulation of 6 real-world enterprise business scenarios:
    - **Scenario A**: High-volume ecommerce outage and automated recovery.
    - **Scenario B**: Agency multi-client white-label scoping.
    - **Scenario C**: Multi-branch enterprise delegation.
    - **Scenario E**: Incident lifecycle from detection to probe-verified recovery.
    - **Scenario F**: Dynamic module toggling without telemetry data loss.
    - **Scenario G**: Subscription plan upgrades and monitor quota enforcement.

---

## 2. Running the Test Suite

Run the full automated test suite using Artisan:
```bash
php artisan test
```

Run a specific test class:
```bash
php artisan test tests/Feature/BusinessAcceptanceScenariosTest.php
```

Run tests with compact output:
```bash
php artisan test --compact
```

