# Production Deployment Guide

**Developer:** Imran Ahmed  
**Email:** me@imrandev.bd  
**Official Website:** [https://imrandev.bd](https://imrandev.bd)  
**Target:** High-Availability Linux Enterprise Deployment  

---

## 1. Production Architecture Overview

For production SaaS workloads, separate the web frontend, background workers, probe nodes, and database tiers:

```
[ Internet ] 
     |
     v (HTTPS / TLS Termination)
[ Load Balancer / Cloudflare / Nginx Reverse Proxy ]
     |
     +---> [ App Cluster: Nginx + PHP-FPM / Octane ]
     |
     +---> [ Redis Cluster: Queue, Cache, Rate Limits, Session ]
     |
     +---> [ Managed PostgreSQL 16: Primary + Read Replicas ]
     |
     +---> [ Background Workers: Supervisor Queue Consumers ]
     |
     +---> [ Scheduler Worker: artisan schedule:run ]
     |
     +---> [ Global Probe Nodes (Frankfurt, London, Tokyo, Singapore, etc.) ]
```

---

## 2. Server Configuration

### 2.1 Nginx Web Server (`/etc/nginx/sites-available/uptime-monitor.conf`)

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name uptime.yourdomain.com *.uptime.yourdomain.com;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name uptime.yourdomain.com *.uptime.yourdomain.com;
    root /var/www/uptime-monitor/public;

    ssl_certificate /etc/letsencrypt/live/uptime.yourdomain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/uptime.yourdomain.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;

    index index.php index.html;
    charset utf-8;
    client_max_body_size 64M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
        fastcgi_buffer_size 128k;
        fastcgi_buffers 4 256k;
        fastcgi_busy_buffers_size 256k;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

---

## 3. Background Workers (Supervisor)

Create `/etc/supervisor/conf.d/uptime-workers.conf`:

```ini
[program:uptime-worker-checks]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/uptime-monitor/artisan queue:work redis --queue=checks,default --sleep=1 --tries=3 --timeout=60 --max-time=3600
autostart=true
autorestart=true
user=www-data
numprocs=8
redirect_stderr=true
stdout_logfile=/var/log/supervisor/uptime-worker-checks.log

[program:uptime-worker-notifications]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/uptime-monitor/artisan queue:work redis --queue=notifications,high --sleep=1 --tries=3 --timeout=30 --max-time=3600
autostart=true
autorestart=true
user=www-data
numprocs=4
redirect_stderr=true
stdout_logfile=/var/log/supervisor/uptime-worker-notifications.log
```

---

## 4. Cron Scheduler (`/etc/cron.d/uptime-monitor`)

Ensure the Laravel scheduler runs every single minute to dispatch monitoring checks and SLA calculations:

```cron
* * * * * www-data php /var/www/uptime-monitor/artisan schedule:run >> /dev/null 2>&1
```

---

## 5. Deployment Script (`deploy.sh`)

Use the following automated deployment script in your CI/CD runner:

```bash
#!/usr/bin/env bash
set -e

echo "Deploying Website Uptime Monitor to Production..."

cd /var/www/uptime-monitor

# 1. Enter maintenance mode
php artisan down --retry=60

# 2. Fetch latest code
git pull origin main

# 3. Install production dependencies
composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction

# 4. Compile frontend assets
npm ci
npm run build

# 5. Database migrations & module registry sync
php artisan migrate --force
php artisan uptime:install --only-sync-modules

# 6. Optimize config & routes
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 7. Restart background queues
php artisan queue:restart
supervisorctl reread
supervisorctl update
supervisorctl restart all

# 8. Exit maintenance mode
php artisan up

# 9. Run automated diagnostics
php artisan uptime:diagnose

echo "Deployment completed successfully!"
```

---

## 6. Data Retention & Maintenance

Run the automated data retention pruning command periodically to prevent unbounded database growth while preserving SLA history:

```bash
# Prune check telemetry older than 90 days (configurable)
php artisan uptime:retention --days=90
```
This prunes raw check samples while aggregated uptime, incident timelines, and SLA compliance metrics remain intact permanently.
