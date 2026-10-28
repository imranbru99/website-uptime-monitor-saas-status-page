# Website Uptime Monitor — Monitoring Engine Deep Dive

**Developer:** Imran Ahmed  
**Email:** me@imrandev.bd  
**Official Website:** [https://imrandev.bd](https://imrandev.bd)  
**Architecture:** Synthetic Check Probes, State Machine & Distributed Runner  

---

## 1. Engine Core Mechanics

The monitoring engine executes high-frequency synthetic checks across multiple global probe locations, records raw observation metrics, evaluates false-positive prevention thresholds, updates uptime statistics, and triggers automated incident workflows.

```
[ Scheduler ] (Every Minute)
      |
      v
[ MonitoringScheduler::dispatchDueChecks() ]
      |
      +---> Dispatches PerformMonitorCheckJob (Redis Queue)
                 |
                 v
      [ CheckRunner::run($monitor, $location) ]
                 |
                 +---> 1. SSRFValidator::validateTarget($target)
                 +---> 2. Protocol Check (HTTP / API / TCP / DNS / SSL / Ping / etc.)
                 +---> 3. Measure Latency & Status Code
                 |
                 v
      [ Record MonitorCheck Result ]
                 |
                 v
      [ MonitoringStateMachine::processCheck($monitor, $check) ]
                 |
                 +---> Evaluate Thresholds (confirmation_threshold, recovery_threshold)
                 +---> State Transitions (Healthy <-> Suspected <-> Down <-> Recovering)
                 +---> Calculate Rolling Average Latency
                 |
                 v (If State Changed)
      [ IncidentManager & AlertDispatcher ]
```

---

## 2. Supported Monitoring Protocols & Checks

### 2.1 HTTP & HTTPS (`http`, `https`)
- Measures DNS lookup time, TCP handshake, TLS handshake, and Time to First Byte (TTFB).
- Follows redirects (up to configurable `max_redirects`, default 5).
- Compares HTTP response status code with `expected_status_code` (e.g., 200, 201, 204).
- Matches required or forbidden regex / keyword strings in response payload.

### 2.2 REST API (`api`)
- Supports GET, POST, PUT, PATCH, DELETE, HEAD, and OPTIONS.
- Passes custom request headers, Bearer tokens, or Basic Auth credentials.
- Injects JSON request bodies.
- Validates response payload structure and status codes.

### 2.3 TCP Port (`tcp`)
- Opens a raw non-blocking socket stream to specified port (e.g., 22, 25, 3306, 5432, 6379, 8080).
- Calculates connection latency in milliseconds.
- Catches connection refused, timeout, or network unreachable states.

### 2.4 DNS Records (`dns`)
- Resolves DNS records via system resolver or specified nameserver.
- Verifies record availability for A, AAAA, CNAME, MX, TXT, and NS types.
- Detects DNS hijacking, poisoning, or propagation delays.

### 2.5 SSL / TLS Certificate (`ssl`)
- Establishes TLS stream context to extract peer certificate metadata.
- Reads `validTo_time_t`, issuer CN, subject CN, and SAN (Subject Alternative Names).
- Calculates exact days until expiration.
- Flags expired certificates, self-signed certificates, or domain mismatches.

### 2.6 Domain Expiration (`domain`)
- Queries domain registry WHOIS records.
- Extracts expiration date and calculates days remaining.
- Emits proactive alerts at 30, 14, 7, and 1 days before expiration.

### 2.7 ICMP Ping (`ping`)
- Sends ICMP echo requests or UDP ping probes.
- Records round-trip time (RTT) and packet loss percentage.

### 2.8 Heartbeat / Cron Job Snitch (`heartbeat`)
- Passive monitoring target listening for incoming HTTP pings at `/api/v1/heartbeat/{token}`.
- Tracks `interval_seconds` and `grace_period_seconds`.
- Marks monitor as `down` if expected ping fails to arrive within the allowable window.

### 2.9 Database & Server Resources (`database`, `server`)
- Tests connectivity to remote PostgreSQL, MySQL, Redis, or Elasticsearch instances.
- Reports connection time and service availability.

---

## 3. Finite State Machine (FSM)

The engine implements a multi-stage state machine designed to completely eliminate alert fatigue from transient network blips:

```
                  +------------+
                  |  Pending   |
                  +------------+
                        |
                        v
                  +------------+ <---------------------+
      +---------> |  Healthy   |                       |
      |           +------------+                       |
      |                 | (1st failure)                |
      |                 v                              |
      |           +----------------+                   |
      |           | Suspected Down |                   |
      |           +----------------+                   |
      |                 |                              |
      |                 | (failures >= threshold)      |
      |                 v                              |
      |           +----------------+                   |
      |           | Confirmed Down |                   |
      |           +----------------+                   |
      |                 |                              |
      |                 | (1st success)                |
      |                 v                              |
      |           +----------------+                   |
      |           |   Recovering   | ------------------+
      |           +----------------+ (confirmed healthy)
      |                 |
      +-----------------+
```

### State Definitions:
1. **Pending**: Target created, awaiting initial probe verification.
2. **Healthy**: Target responding normally within latency thresholds.
3. **Degraded**: Target is responding, but latency exceeds acceptable limits or partial errors are observed.
4. **Suspected Down**: Target failed 1 check. Probe verification queued from a second geographical location.
5. **Confirmed Down**: Target reached `confirmation_threshold` consecutive failures (default 2 or 3). Incident created, alerts dispatched.
6. **Recovering**: Target succeeded 1 check after an outage. Awaiting confirmation before auto-resolving incident.
7. **Paused**: Check scheduling suspended by administrator.
8. **Maintenance**: Monitoring active, but alerting suppressed due to scheduled maintenance window.

---

## 4. Latency Aggregations & Percentiles

The `UptimeCalculator` computes high-precision operational percentiles:
- **P50 (Median)**: Half of checks were faster than this value.
- **P90**: 90% of requests faster than this value.
- **P95 & P99**: Captures tail latency and extreme edge cases.
- **Rolling Average**: Latency is updated on each check using an exponential moving average:
  $$\text{New Average} = (\text{Current Average} \times 0.8) + (\text{Check Latency} \times 0.2)$$
