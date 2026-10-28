<?php

namespace App\Http\Controllers;

use App\Domain\Alerts\Models\NotificationChannel;
use App\Domain\Billing\Models\SubscriptionPlan;
use App\Domain\CMS\Models\CmsPage;
use App\Domain\CMS\Models\ContactRequest;
use App\Domain\CMS\Models\DemoRequest;
use App\Domain\CMS\Models\Faq;
use App\Domain\CMS\Models\Testimonial;
use App\Domain\Monitoring\Models\Monitor;
use App\Domain\Monitoring\Models\MonitorLocation;
use App\Domain\StatusPages\Models\StatusPage;
use App\Domain\StatusPages\Models\StatusPageSubscriber;
use App\Domain\Tenancy\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PublicWebsiteController extends Controller
{
    public function home()
    {
        $plans = SubscriptionPlan::where('is_active', true)->orderBy('order_index')->get();
        $faqs = Faq::where('is_published', true)->orderBy('order_index')->limit(6)->get();
        $testimonials = Testimonial::where('is_featured', true)->limit(3)->get();
        $locations = MonitorLocation::where('status', 'active')->get();

        return view('public.home', compact('plans', 'faqs', 'testimonials', 'locations'));
    }

    public function pricing()
    {
        $plans = SubscriptionPlan::where('is_active', true)->orderBy('order_index')->get();

        return view('public.pricing', compact('plans'));
    }

    public function demo()
    {
        $demoTenants = Tenant::whereIn('slug', ['nimbuscloud', 'shopvista', 'pixelforge', 'atlas'])->get();
        $sampleMonitors = Monitor::where('is_active', true)->limit(6)->get();
        $locations = MonitorLocation::all();

        return view('public.demo', compact('demoTenants', 'sampleMonitors', 'locations'));
    }

    public function statusPage(string $slug)
    {
        $page = StatusPage::where('slug', $slug)
            ->where('is_active', true)
            ->with(['components.monitor', 'tenant'])
            ->firstOrFail();

        return view('public.status-page', compact('page'));
    }

    public function subscribeStatusPage(Request $request, string $slug)
    {
        $page = StatusPage::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'destination' => 'required|string',
            'subscription_type' => 'required|in:email,webhook',
        ]);

        StatusPageSubscriber::updateOrCreate(
            [
                'status_page_id' => $page->id,
                'destination' => $validated['destination'],
                'subscription_type' => $validated['subscription_type'],
            ],
            [
                'is_verified' => true,
                'subscribed_at' => now(),
            ]
        );

        return back()->with('success', 'You have been successfully subscribed to incident and maintenance updates.');
    }

    public function page(string $slug)
    {
        $page = CmsPage::where('slug', $slug)->where('is_published', true)->first();

        return view('public.page', [
            'slug' => $slug,
            'title' => $page?->title ?? ucwords(str_replace(['-', '/'], ' ', $slug)),
            'content' => $page?->content ?? null,
            'summary' => $page?->summary ?? 'Enterprise observability, reliable synthetic monitoring, and incident escalation platform.',
        ]);
    }

    public function contactSubmit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'company' => 'nullable|string|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        ContactRequest::create($validated);

        return back()->with('success', 'Thank you! Your message has been received. Our team will contact you shortly.');
    }

    public function demoRequestSubmit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'company' => 'required|string|max:255',
            'team_size' => 'nullable|string',
            'notes' => 'nullable|string|max:1000',
        ]);

        DemoRequest::create($validated);

        return back()->with('success', 'Demo request submitted successfully. A solutions engineer will reach out to schedule your walk-through.');
    }

    public function onboarding()
    {
        $plans = SubscriptionPlan::where('is_active', true)->get();

        return view('public.onboarding', compact('plans'));
    }

    public function onboardingSubmit(Request $request)
    {
        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'business_mode' => 'required|string',
            'monitor_name' => 'required|string|max:255',
            'target_url' => 'required|string|max:500',
            'plan_id' => 'required|exists:subscription_plans,id',
        ]);

        $user = User::create([
            'name' => $validated['business_name'].' Admin',
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'owner',
            'is_active' => true,
        ]);

        $tenant = Tenant::create([
            'name' => $validated['business_name'],
            'business_mode' => $validated['business_mode'],
            'email' => $validated['email'],
            'subscription_plan_id' => $validated['plan_id'],
            'subscription_status' => 'trialing',
        ]);

        $user->tenants()->attach($tenant->id, ['role' => 'owner', 'is_owner' => true]);
        $user->update(['current_tenant_id' => $tenant->id]);

        // Create initial monitor
        Monitor::create([
            'tenant_id' => $tenant->id,
            'name' => $validated['monitor_name'],
            'target' => $validated['target_url'],
            'monitor_type' => 'https',
            'status' => 'healthy',
            'check_interval_seconds' => 60,
            'next_check_due_at' => now(),
        ]);

        // Default notification channel
        NotificationChannel::create([
            'tenant_id' => $tenant->id,
            'name' => 'Primary Email Alerts',
            'channel_type' => 'email',
            'destination' => $validated['email'],
            'is_active' => true,
        ]);

        auth()->login($user);

        return redirect('/app')->with('success', 'Workspace and initial monitor setup completed successfully!');
    }
}
