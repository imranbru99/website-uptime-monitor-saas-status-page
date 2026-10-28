<?php

use App\Http\Controllers\PublicWebsiteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public SaaS & Marketing Routes - Website Uptime Monitor
|--------------------------------------------------------------------------
| Author: Imran Ahmed <me@imrandev.bd>
| Website: https://imrandev.bd
*/

Route::get('/', [PublicWebsiteController::class, 'home'])->name('home');
Route::get('/pricing', [PublicWebsiteController::class, 'pricing'])->name('pricing');
Route::get('/demo', [PublicWebsiteController::class, 'demo'])->name('demo');

// Status Pages
Route::get('/status/{slug}', [PublicWebsiteController::class, 'statusPage'])->name('status-page');
Route::post('/status/{slug}/subscribe', [PublicWebsiteController::class, 'subscribeStatusPage'])->name('status-page.subscribe');

// Onboarding Wizard
Route::get('/onboarding', [PublicWebsiteController::class, 'onboarding'])->name('onboarding');
Route::post('/onboarding', [PublicWebsiteController::class, 'onboardingSubmit'])->name('onboarding.submit');

// Contact & Demo Requests
Route::post('/contact', [PublicWebsiteController::class, 'contactSubmit'])->name('contact.submit');
Route::post('/request-demo', [PublicWebsiteController::class, 'demoRequestSubmit'])->name('request-demo.submit');

// 46+ Public CMS, Solutions & Feature Pages
$publicPages = [
    'features',
    'features/website-monitoring',
    'features/api-monitoring',
    'features/server-monitoring',
    'features/ssl-monitoring',
    'features/domain-monitoring',
    'features/cron-monitoring',
    'features/global-monitoring',
    'features/incident-management',
    'features/alerting',
    'features/public-status-pages',
    'features/private-status-pages',
    'features/ai-monitoring',
    'features/sla-management',
    'features/analytics-reports',
    'features/monitoring-automation',
    'solutions/agency',
    'solutions/enterprise',
    'solutions/multi-branch',
    'integrations',
    'pricing/compare',
    'customers',
    'about',
    'contact',
    'request-demo',
    'help',
    'docs',
    'docs/api',
    'blog',
    'faq',
    'security',
    'privacy',
    'terms',
    'cookies',
    'status',
    'changelog',
    'roadmap',
    'careers',
    'partners',
];

foreach ($publicPages as $pageSlug) {
    Route::get('/'.$pageSlug, function () use ($pageSlug) {
        return app(PublicWebsiteController::class)->page($pageSlug);
    });
}
