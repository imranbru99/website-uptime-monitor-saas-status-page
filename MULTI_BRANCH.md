# Website Uptime Monitor — Multi-Branch Enterprise Hierarchy

**Developer:** Imran Ahmed  
**Email:** me@imrandev.bd  
**Official Website:** [https://imrandev.bd](https://imrandev.bd)  
**Target:** Global Enterprises, Regional MSPs & Holding Companies  

---

## 1. Enterprise Organizational Hierarchy

Website Uptime Monitor provides a 4-tier structural hierarchy designed for large distributed enterprises:

```
+-------------------------------------------------------------+
|                  Tenant (Parent Organization)               |
|            e.g. NimbusCloud Enterprise Global               |
+-------------------------------------------------------------+
                              |
       +----------------------+----------------------+
       |                                             |
+--------------------------+             +--------------------------+
| Branch: US East (US-VA)  |             | Branch: EU West (EU-DE)  |
+--------------------------+             +--------------------------+
       |                                             |
+--------------------------+             +--------------------------+
| Department: SRE & Cloud  |             | Department: Cloud Ops    |
+--------------------------+             +--------------------------+
       |                                             |
+--------------------------+             +--------------------------+
| Team: Alpha SRE Responders|            | Team: European On-Call   |
+--------------------------+             +--------------------------+
```

---

## 2. Branch Scoping & Delegation

### 2.1 Regional Isolation
- Enterprise customers can partition infrastructure into distinct regional or departmental branches.
- Monitors assigned to a branch (`branch_id`) are visible to that branch's operators while remaining aggregated at the parent tenant level.
- The dedicated `/branch` Filament panel provides branch managers with a focused command console without clutter from other business divisions.

### 2.2 Regional Module Overrides
In the 60-Module architecture, modules marked `allow_branch_override = true` can be enabled or disabled independently per branch:
- *Example*: The EU Branch can activate strict GDPR audit logging and regional notification channels while the US branch utilizes different on-call rotation policies.

### 2.3 Team-Based Alert Routing
- Incidents detected on branch monitors route automatically to the branch's designated on-call schedules and response teams, preventing cross-region alert spam.

