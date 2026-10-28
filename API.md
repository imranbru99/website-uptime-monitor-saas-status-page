# Website Uptime Monitor — REST API Reference (v1)

**Developer:** Imran Ahmed  
**Email:** me@imrandev.bd  
**Official Website:** [https://imrandev.bd](https://imrandev.bd)  
**Base URL:** `https://your-domain.com/api/v1`  
**Authentication:** HTTP Bearer Token (Sanctum)  

---

## 1. Authentication & Security

All private endpoints require an `Authorization: Bearer <TOKEN>` header. Tokens are generated via the authentication endpoint or inside the web console.

### 1.1 Obtain API Token
- **Endpoint:** `POST /api/v1/auth/token`
- **Request Body:**
```json
{
  "email": "owner@nimbuscloud.test",
  "password": "password",
  "token_name": "ci-cd-pipeline"
}
```
- **Response:** `200 OK`
```json
{
  "token": "1|eX9b1p9cQ2o...",
  "user": {
    "id": 2,
    "name": "Sarah Jenkins",
    "email": "owner@nimbuscloud.test"
  },
  "tenant": {
    "id": 1,
    "name": "NimbusCloud Enterprise",
    "slug": "nimbuscloud"
  }
}
```

### 1.2 Get Authenticated User Context
- **Endpoint:** `GET /api/v1/user`
- **Response:** `200 OK`
```json
{
  "user": {
    "id": 2,
    "name": "Sarah Jenkins",
    "email": "owner@nimbuscloud.test",
    "role": "tenant_admin"
  },
  "tenant": {
    "id": 1,
    "name": "NimbusCloud Enterprise",
    "slug": "nimbuscloud"
  }
}
```

---

## 2. Monitors API

### 2.1 List Monitors
- **Endpoint:** `GET /api/v1/monitors`
- **Query Parameters:**
  - `status`: filter by status (`healthy`, `down`, `degraded`, `paused`)
  - `type`: filter by monitor type (`http`, `api`, `tcp`, `dns`, `ssl`, `ping`, `heartbeat`)
  - `per_page`: items per page (default: 25)
- **Response:** `200 OK`
```json
{
  "data": [
    {
      "id": 1,
      "name": "Nimbus Primary Gateway",
      "target": "https://api.nimbuscloud.io/health",
      "monitor_type": "http",
      "status": "healthy",
      "interval_seconds": 60,
      "timeout_seconds": 10,
      "avg_latency_ms": 18.4,
      "uptime_percentage": 99.98,
      "last_checked_at": "2026-10-28T14:00:00Z"
    }
  ],
  "links": { ... },
  "meta": { ... }
}
```

### 2.2 Create Monitor
- **Endpoint:** `POST /api/v1/monitors`
- **Request Body:**
```json
{
  "name": "Staging Auth API",
  "target": "https://staging.auth.example.com/healthz",
  "monitor_type": "api",
  "interval_seconds": 60,
  "timeout_seconds": 10,
  "confirmation_threshold": 3,
  "recovery_threshold": 2,
  "http_method": "GET",
  "expected_status_code": 200,
  "tags": ["staging", "auth", "api"]
}
```
- **Response:** `201 Created`

### 2.3 Get Monitor Details
- **Endpoint:** `GET /api/v1/monitors/{id}`
- **Response:** `200 OK`

### 2.4 Trigger Instant Check
- **Endpoint:** `POST /api/v1/monitors/{id}/check-now`
- **Response:** `200 OK`
```json
{
  "message": "Immediate check dispatched to probe queue.",
  "monitor_id": 1
}
```

### 2.5 Monitor Check History
- **Endpoint:** `GET /api/v1/monitors/{id}/checks`
- **Query Parameters:** `limit` (max 100)
- **Response:** `200 OK`
```json
{
  "data": [
    {
      "id": 1024,
      "status": "success",
      "status_code": 200,
      "latency_ms": 22.1,
      "location_code": "SIN",
      "checked_at": "2026-10-28T14:15:00Z"
    }
  ]
}
```

---

## 3. Incidents API

### 3.1 List Incidents
- **Endpoint:** `GET /api/v1/incidents`
- **Query Parameters:** `status` (`confirmed`, `acknowledged`, `investigating`, `resolved`)
- **Response:** `200 OK`

### 3.2 Acknowledge Incident
- **Endpoint:** `POST /api/v1/incidents/{id}/acknowledge`
- **Response:** `200 OK`
```json
{
  "message": "Incident acknowledged.",
  "incident": {
    "id": 1,
    "status": "acknowledged",
    "acknowledged_at": "2026-10-28T14:20:00Z"
  }
}
```

### 3.3 Resolve Incident
- **Endpoint:** `POST /api/v1/incidents/{id}/resolve`
- **Request Body:**
```json
{
  "resolution_summary": "Upstream switch reboot completed; traffic rerouted."
}
```
- **Response:** `200 OK`

---

## 4. Heartbeat (Cron / Daemon) API

- **Endpoint:** `POST /api/v1/heartbeat/{token}`
- **Headers:** Optional custom payload
- **Response:** `200 OK`
```json
{
  "status": "acknowledged",
  "monitor": "Nightly Database Backup Cron",
  "next_expected_at": "2026-10-29T02:00:00Z"
}
```

---

## 5. Probe Locations API

- **Endpoint:** `GET /api/v1/locations`
- **Response:** `200 OK`
```json
{
  "data": [
    { "code": "SIN", "name": "Singapore Primary Probe", "region": "Singapore, SG", "status": "active" },
    { "code": "TYO", "name": "Tokyo Probe Node", "region": "Tokyo, JP", "status": "active" },
    { "code": "LHR", "name": "London Probe Node", "region": "London, GB", "status": "active" },
    { "code": "FRA", "name": "Frankfurt Probe Node", "region": "Frankfurt, DE", "status": "active" },
    { "code": "NYC", "name": "New York Probe Node", "region": "New York, US", "status": "active" }
  ]
}
```

---

## 6. SLA & Compliance API

- **Endpoint:** `GET /api/v1/sla/metrics`
- **Query Parameters:** `monitor_id`, `period_days` (default 30)
- **Response:** `200 OK`
```json
{
  "uptime_percentage": 99.982,
  "target_sla": 99.95,
  "status": "compliant",
  "total_downtime_minutes": 7.8,
  "error_budget_remaining_percent": 64.2,
  "mttd_minutes": 1.2,
  "mttr_minutes": 7.8,
  "latency": {
    "p50_ms": 18.2,
    "p90_ms": 28.5,
    "p95_ms": 34.1,
    "p99_ms": 48.0
  }
}
```
