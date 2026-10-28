<?php

namespace App\Filament\App\Pages;

use App\Domain\Reports\Services\ReportGenerator;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Symfony\Component\HttpFoundation\StreamedResponse;
use UnitEnum;

class ReportsPage extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentArrowDown;

    protected static string|UnitEnum|null $navigationGroup = 'Analytics';

    protected static ?string $navigationLabel = 'Reliability Reports';

    protected static ?string $title = 'Reliability & Uptime Reports';

    protected static ?int $navigationSort = 4;

    protected string $view = 'filament.app.pages.reports';

    public string $period = '30d';

    public string $locale = 'en';

    public function downloadPdf(ReportGenerator $generator)
    {
        $tenant = auth()->user()?->currentTenant();
        if (! $tenant) {
            Notification::make()->title('No active organization found')->danger()->send();

            return null;
        }

        try {
            $pdf = $generator->generateUptimePdf($tenant, $this->period, $this->locale);

            return $pdf->download("uptime-report-{$tenant->slug}-{$this->period}.pdf");
        } catch (\Throwable $e) {
            // Fallback: stream HTML report directly
            return response($generator->generateUptimeHtml($tenant, $this->period, $this->locale))
                ->header('Content-Type', 'text/html');
        }
    }

    public function downloadCsv(ReportGenerator $generator): StreamedResponse
    {
        $tenant = auth()->user()?->currentTenant();
        $csv = $generator->exportMonitorsCsv($tenant);

        return response()->streamDownload(function () use ($csv) {
            echo $csv;
        }, "monitors-{$tenant->slug}.csv", [
            'Content-Type' => 'text/csv',
        ]);
    }
}
