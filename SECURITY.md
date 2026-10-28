# Website Uptime Monitor — Security & Compliance Architecture

**Developer:** Imran Ahmed  
**Email:** me@imrandev.bd  
**Official Website:** [https://imrandev.bd](https://imrandev.bd)  
**Security Posture:** Enterprise Grade, Defense-in-Depth  

---

## 1. Server-Side Request Forgery (SSRF) Defense

Because synthetic monitoring platforms accept user-provided target URLs and hostnames, SSRF is the highest-risk attack vector. The `App\Domain\Monitoring\Services\SSRFValidator` enforces strict defense-in-depth before any socket or HTTP connection is initiated:

### 1.1 Prohibited IP Address Ranges
- **Loopback Addresses**: `127.0.0.0/8`, `::1` (blocks probe from contacting localhost services).
- **Private Networks (RFC 1918)**:
  - `10.0.0.0/8`
  - `172.16.0.0/12`
  - `192.168.0.0/16`
- **Link-Local & Cloud Metadata Addresses (RFC 3927)**:
  - `169.254.0.0/16` (blocks AWS, Google Cloud, Azure, and DigitalOcean instance metadata services: `http://169.254.169.254/`).
- **Broadcast & Multicast**:
  - `0.0.0.0/8`, `224.0.0.0/4`, `240.0.0.0/4`.

### 1.2 Dangerous Scheme Blocking
Only sanctioned schemes are allowed: `http://`, `https://`, `tcp://`, and naked hostnames. Dangerous protocols are strictly rejected before DNS resolution:
- `file://` (blocks local file reading like `/etc/passwd`)
- `phar://` (blocks PHP deserialization exploits)
- `gopher://` (blocks raw TCP protocol smuggling to internal Redis/SMTP)
- `dict://`, `ldap://`, `ftp://`

### 1.3 DNS Rebinding Protection
The validator resolves the hostname's A/AAAA IP address at probe execution time and verifies the target IP against the blocklist, ensuring DNS rebinding attacks cannot bypass initial form validation.

---

## 2. Multi-Tenant Data Isolation

Row-level tenant segregation is enforced through architecture:
1. **TenantScope**: Every model extending `BelongsToTenant` receives a global query scope appending `WHERE tenant_id = ?`.
2. **Implicit Scoping**: Inserting records via relationships automatically sets `tenant_id`.
3. **Foreign Key Integrity**: All tenant-owned tables feature `FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE`.

---

## 3. Credential & Secret Encryption

1. **Encrypted Storage**: Sensitive fields (webhook secrets, custom HTTP authorization tokens, Basic Auth passwords) are encrypted in the database using AES-256-CBC via Laravel's encrypter:
   ```php
   protected $casts = [
       'auth_credentials' => 'encrypted:array',
       'secret_token' => 'encrypted',
   ];
   ```
2. **Redacted Serialization**: All secret attributes are registered in Eloquent `$hidden` arrays to prevent leakage into API JSON responses or audit logs.

---

## 4. Webhook Authentication & Integrity

Outbound webhook alerts dispatched to user endpoints carry cryptographic proof of authenticity:
- **Header:** `X-Uptime-Signature: sha256=<HASH>`
- **Algorithm:** HMAC-SHA256
- **Payload:** Raw JSON string of the notification payload.
- **Verification Example:**
  ```php
  $computed = hash_hmac('sha256', $rawPayload, $webhookSecret);
  if (!hash_equals($computed, $receivedSignature)) {
      abort(401, 'Invalid signature.');
  }
  ```

---

## 5. Authentication & Session Security

- **Session Security**: `HttpOnly`, `SameSite=Lax`, and `Secure` cookie attributes are enforced in production.
- **API Tokens**: Laravel Sanctum personal access tokens use SHA-256 hashes in the database. Raw plaintext tokens are presented only once at creation time.
- **Rate Limiting**:
  - `POST /api/v1/auth/token`: Limited to 5 attempts per minute per IP to prevent brute-force attacks.
  - `/api/v1/*`: 60 requests per minute per authenticated token.
  - Status Page Subscription: 10 requests per minute per IP to mitigate spam.

---

## 6. Audit Logging & Compliance

All critical operational events are immutably logged to `audit_logs`:
- Monitor creation, modification, and deletion.
- Alert rule alterations and channel destinations.
- User permission adjustments and invitations.
- Status page custom domain mappings.
- Module activation and deactivation events.

