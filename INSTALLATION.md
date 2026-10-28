# Installation & Setup Guide

**Developer:** Imran Ahmed  
**Email:** me@imrandev.bd  
**Official Website:** [https://imrandev.bd](https://imrandev.bd)  
**Product:** Website Uptime Monitor — Enterprise SaaS Platform  

---

## 1. System Prerequisites

Before installing the platform, ensure your environment meets the minimum requirements:

- **PHP**: 8.3 or 8.4+ (CLI & FPM)
- **Required PHP Extensions**: `pdo`, `pdo_pgsql` (or `pdo_mysql` / `pdo_sqlite`), `redis`, `bcmath`, `ctype`, `curl`, `dom`, `fileinfo`, `filter`, `gd`, `hash`, `intl`, `json`, `mbstring`, `openssl`, `pcntl`, `pcre`, `session`, `tokenizer`, `xml`, `zip`
- **Composer**: 2.8+
- **Node.js**: 20.x or 22.x+ & NPM
- **Database**: PostgreSQL 15+ (preferred) or MySQL 8.0+ / SQLite 3.35+
- **Queue / Cache**: Redis 6.2+
- **Web Server**: Nginx or Caddy

---

## 2. Bare-Metal Local Installation

### Step 1: Clone Repository
```bash
git clone https://github.com/imrandevbd/website-uptime-monitor.git
cd website-uptime-monitor
```

### Step 2: Configure Git Author Identity (Mandatory)
```bash
git config user.name "Imran Ahmed"
git config user.email "me@imrandev.bd"
```

### Step 3: Install Composer Dependencies
```bash
composer install --prefer-dist --no-interaction
```

### Step 4: Environment Configuration
Copy the example environment file and generate the application encryption key:
```bash
cp .env.example .env
php artisan key:generate
```

Configure your `.env` database settings:
```ini
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=uptime_monitor
DB_USERNAME=postgres
DB_PASSWORD=your_secret_password
```

### Step 5: Run Installation Command
Execute the automated installer to run migrations, initialize the 60 core modules, seed global probe locations, and create the default superadmin account:
```bash
php artisan uptime:install
```

Default SuperAdmin Credentials:
- **URL**: `http://localhost:8000/platform`
- **Email**: `superadmin@uptime.test`
- **Password**: `password`

### Step 6: Seed Enterprise Demonstration Data (Optional)
Populate the database with 4 multi-tenant organizations (NimbusCloud, ShopVista, PixelForge, Atlas Financial), 100+ monitors, check history, active incidents, alert rules, and public status pages:
```bash
php artisan uptime:demo-seed
```

### Step 7: Build Frontend Assets
```bash
npm install
npm run build
```

### Step 8: Start the Services
Open separate terminal sessions or use a process runner:

```bash
# 1. Start Web Server
php artisan serve --port=8000

# 2. Start Queue Worker
php artisan queue:work --sleep=2 --tries=3

# 3. Start Monitoring Scheduler
php artisan schedule:work
```

---

## 3. Docker Installation (One-Click)

The repository provides a complete `Dockerfile` and `docker-compose.yml` for instant containerized deployment with PostgreSQL 16, Redis 7, and Mailpit:

```bash
# 1. Clone repository
git clone https://github.com/imrandevbd/website-uptime-monitor.git
cd website-uptime-monitor

# 2. Configure environment
cp .env.example .env

# 3. Start Docker stack
docker compose up -d --build

# 4. Initialize Database & Seed Demo Data inside container
docker compose exec app php artisan uptime:install
docker compose exec app php artisan uptime:demo-seed

# 5. Verify system diagnostics
docker compose exec app php artisan uptime:diagnose
```

### Access URLs:
- **Public Website & Status Pages**: `http://localhost:8000`
- **Business Owner Dashboard**: `http://localhost:8000/app`
- **Platform SuperAdmin Panel**: `http://localhost:8000/platform`
- **Branch Operations Console**: `http://localhost:8000/branch`
- **Client Agency Portal**: `http://localhost:8000/client`
- **Support & Operations**: `http://localhost:8000/operations`
- **Mailpit Email Testing Web UI**: `http://localhost:8025`

---

## 4. Verifying System Health

Run the diagnostic suite to ensure database connectivity, tenant health, probe mesh readiness, and SSRF security defense:
```bash
php artisan uptime:diagnose
```

Inspect worker queues and probe nodes:
```bash
php artisan uptime:worker-status
```
