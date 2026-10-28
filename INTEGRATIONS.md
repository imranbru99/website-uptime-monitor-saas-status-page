# Website Uptime Monitor — Alert Integrations & Webhooks

**Developer:** Imran Ahmed  
**Email:** me@imrandev.bd  
**Official Website:** [https://imrandev.bd](https://imrandev.bd)  
**Package:** `App\Domain\Alerts`  

---

## 1. Supported Notification Channels

Website Uptime Monitor provides native, robust delivery handlers for 6 enterprise alert destinations:

| Channel | Identifier | Payload Format | Authentication | Capabilities |
| :--- | :--- | :--- | :--- | :--- |
| **Email** | `email` | HTML / Plaintext | SMTP / SES / Mailgun | Rich transactional HTML with latency graphs |
| **Slack** | `slack` | BlockKit JSON | Webhook URL / Bot Token | Interactive action buttons (Acknowledge / View) |
| **Discord** | `discord` | Discord Embeds | Webhook URL | Color-coded status embeds (Red/Green/Yellow) |
| **Telegram** | `telegram` | Telegram Markdown | Bot Token + Chat ID | Instant push alerts to private chats or channels |
| **Microsoft Teams** | `teams` | Adaptive Cards | Webhook Connector | Enterprise cards with incident metrics |
| **Custom Webhooks** | `webhook` | Standard JSON | HMAC-SHA256 Header | Programmatic triggers for PagerDuty, OpsGenie, CI/CD |

---

## 2. Webhook Signature Verification

All outbound HTTP webhooks include cryptographic verification headers:
- `X-Uptime-Event`: e.g. `incident.down`, `incident.recovered`, `incident.acknowledged`
- `X-Uptime-Delivery-ID`: Unique UUID of delivery attempt
- `X-Uptime-Timestamp`: Unix epoch timestamp
- `X-Uptime-Signature`: `sha256=<HASH>` computed as:
  $$HMAC\text{-}SHA256(\text{raw\_payload}, \text{channel\_secret})$$

### Sample Webhook Payload
```json
{
  "event": "incident.down",
  "incident_id": 42,
  "title": "Service Outage: Production API Gateway",
  "severity": "critical",
  "status": "confirmed",
  "monitor": {
    "id": 1,
    "name": "Production API Gateway",
    "target": "https://api.example.com/v1/health",
    "type": "api"
  },
  "failure_details": {
    "error_type": "http_status_mismatch",
    "status_code": 503,
    "latency_ms": 3520.4,
    "probe_location": "FRA",
    "detected_at": "2026-10-28T14:30:00Z"
  }
}
```

---

## 3. Cooldown & Flapping Protection

To prevent alert fatigue when a flapping target bounces between up and down:
1. **Alert Cooldown Window**: Configurable on `AlertRule` (default: 30 minutes). Once an alert is sent, duplicate down notifications are throttled until cooldown expires.
2. **Escalation Delay**: Delays secondary notifications (e.g. SMS / Teams) until an incident has remained unacknowledged for $N$ minutes.
