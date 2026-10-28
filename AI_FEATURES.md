# Website Uptime Monitor — AI Features & Operations Center

**Developer:** Imran Ahmed  
**Email:** me@imrandev.bd  
**Official Website:** [https://imrandev.bd](https://imrandev.bd)  
**Package Integration:** `imrandevbd/laravel-ai-hub` (v1.7.1)  

---

## 1. AI Integration Architecture

The platform integrates `imrandevbd/laravel-ai-hub` to provide AI-assisted operational intelligence across monitoring diagnostics, incident response, and public communication.

```
+-------------------------------------------------------------+
|              App Domain: ArtificialIntelligence             |
|                  App\Domain\ArtificialIntelligence          |
+-------------------------------------------------------------+
                              |
                 AiOperationsCenter Service
                              |
       +----------------------+----------------------+
       |                                             |
[ Laravel AI Hub Driver ]                 [ Built-in Fallback ]
(OpenAI, Anthropic, Gemini)               (Deterministic Diagnostics)
```

If an external AI API key is not configured or an AI provider experiences rate limits, the service falls back automatically to structured, deterministic heuristics so incident workflows are never blocked.

---

## 2. Key AI Capabilities

### 2.1 Automated Incident Summaries
- **Method:** `AiOperationsCenter::generateIncidentSummary(Incident $incident)`
- Evaluates monitor type, error codes, failed probe regions, and recent timeline updates.
- Produces an executive-level incident summary suitable for engineering leadership and postmortems.

### 2.2 Root Cause Analysis (RCA) Assistant
- **Method:** `AiOperationsCenter::suggestRootCauses(Incident $incident)`
- Analyzes telemetry patterns:
  - High latency + HTTP 504 -> Upstream reverse proxy timeout / overloaded application server.
  - Connection refused + TCP port 5432 -> Database daemon crash or max connections reached.
  - SSL expiration -> Certificate renewal failure or ACME challenge failure.
- Returns actionable diagnostic steps for the responding on-call engineer.

### 2.3 Public Status Update Copilot
- **Method:** `AiOperationsCenter::draftPublicStatusUpdate(Incident $incident, string $stage)`
- Generates clear, non-technical, empathetic status update drafts for public status page subscribers across stages: `investigating`, `identified`, `monitoring`, and `resolved`.

### 2.4 Interactive AI Assistant Dashboard Page
- Accessible inside the Business Owner Panel at `/app/ai-assistant-page`.
- Provides an interactive AI console to query system health, analyze latency anomalies, and review draft incident statements before publishing.

