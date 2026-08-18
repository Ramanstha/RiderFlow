<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ReportExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Report\ReportFilterRequest;
use App\Models\Company;
use App\Models\Rider;
use App\Services\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Closure;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function __construct(protected ReportService $reports) {}

    public function index(): View
    {
        $this->authorize('reports.view');

        return view('admin.reports.index');
    }

    public function attendance(ReportFilterRequest $request): View
    {
        $this->authorize('reports.view');

        $summary = $this->reports->attendanceSummary($request->validated());
        $filters = $this->filterOptions();

        return view('admin.reports.attendance', compact('summary', 'filters'));
    }

    public function parcels(ReportFilterRequest $request): View
    {
        $this->authorize('reports.view');

        $summary = $this->reports->parcelSummary($request->validated());
        $filters = $this->filterOptions();

        return view('admin.reports.parcels', compact('summary', 'filters'));
    }

    public function performance(ReportFilterRequest $request): View
    {
        $this->authorize('reports.view');

        $summary = $this->reports->performanceSummary($request->validated());
        $filters = $this->filterOptions();

        return view('admin.reports.performance', compact('summary', 'filters'));
    }

    public function exportAttendanceCsv(ReportFilterRequest $request): StreamedResponse
    {
        $this->authorize('reports.view');

        return $this->streamCsv($this->attendanceSpec($request));
    }

    public function exportParcelsCsv(ReportFilterRequest $request): StreamedResponse
    {
        $this->authorize('reports.view');

        return $this->streamCsv($this->parcelsSpec($request));
    }

    public function exportPerformanceCsv(ReportFilterRequest $request): StreamedResponse
    {
        $this->authorize('reports.view');

        return $this->streamCsv($this->performanceSpec($request));
    }

    public function exportAttendanceExcel(ReportFilterRequest $request): BinaryFileResponse
    {
        $this->authorize('reports.view');

        return $this->downloadExcel($this->attendanceSpec($request));
    }

    public function exportParcelsExcel(ReportFilterRequest $request): BinaryFileResponse
    {
        $this->authorize('reports.view');

        return $this->downloadExcel($this->parcelsSpec($request));
    }

    public function exportPerformanceExcel(ReportFilterRequest $request): BinaryFileResponse
    {
        $this->authorize('reports.view');

        return $this->downloadExcel($this->performanceSpec($request));
    }

    public function exportAttendancePdf(ReportFilterRequest $request): HttpResponse
    {
        $this->authorize('reports.view');

        return $this->downloadPdf($this->attendanceSpec($request));
    }

    public function exportParcelsPdf(ReportFilterRequest $request): HttpResponse
    {
        $this->authorize('reports.view');

        return $this->downloadPdf($this->parcelsSpec($request));
    }

    public function exportPerformancePdf(ReportFilterRequest $request): HttpResponse
    {
        $this->authorize('reports.view');

        return $this->downloadPdf($this->performanceSpec($request));
    }

    protected function attendanceSpec(ReportFilterRequest $request): array
    {
        return [
            'title' => 'Attendance Report',
            'filename' => 'attendance-report',
            'headings' => ['Rider', 'Company', 'Total Days', 'Present Days', 'Absent Days', 'Attendance Rate (%)'],
            'rows' => $this->reports->attendanceSummary($request->validated()),
            'mapRow' => fn ($row) => [
                $row['rider_name'], $row['company_name'], $row['total_days'], $row['present_days'],
                $row['absent_days'], $row['attendance_rate'],
            ],
        ];
    }

    protected function parcelsSpec(ReportFilterRequest $request): array
    {
        return [
            'title' => 'Parcel Report',
            'filename' => 'parcel-report',
            'headings' => ['Rider', 'Company', 'Assigned', 'Delivered', 'Success Rate (%)'],
            'rows' => $this->reports->parcelSummary($request->validated()),
            'mapRow' => fn ($row) => [
                $row['rider_name'], $row['company_name'], $row['assigned'], $row['delivered'],
                $row['success_rate'],
            ],
        ];
    }

    protected function performanceSpec(ReportFilterRequest $request): array
    {
        return [
            'title' => 'Performance Report',
            'filename' => 'performance-report',
            'headings' => ['Rider', 'Company', 'Average Score (%)', 'Deliveries Completed', 'Deliveries Failed'],
            'rows' => $this->reports->performanceSummary($request->validated()),
            'mapRow' => fn ($row) => [
                $row['rider_name'], $row['company_name'], $row['average_score'],
                $row['total_deliveries_completed'], $row['total_deliveries_failed'],
            ],
        ];
    }

    protected function filterOptions(): array
    {
        return [
            'riders' => Rider::query()->orderBy('name')->get(),
            'companies' => Company::query()->orderBy('name')->get(),
        ];
    }

    protected function streamCsv(array $spec): StreamedResponse
    {
        /** @var Collection $rows */
        $rows = $spec['rows'];
        /** @var Closure $mapRow */
        $mapRow = $spec['mapRow'];

        return Response::streamDownload(function () use ($spec, $rows, $mapRow) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $spec['headings']);

            foreach ($rows as $row) {
                fputcsv($handle, $mapRow($row));
            }

            fclose($handle);
        }, "{$spec['filename']}.csv", ['Content-Type' => 'text/csv']);
    }

    protected function downloadExcel(array $spec): BinaryFileResponse
    {
        $export = new ReportExport($spec['rows'], $spec['headings'], $spec['mapRow']);

        return Excel::download($export, "{$spec['filename']}.xlsx");
    }

    protected function downloadPdf(array $spec): HttpResponse
    {
        $pdf = Pdf::loadView('exports.report-pdf', [
            'title' => $spec['title'],
            'headings' => $spec['headings'],
            'rows' => $spec['rows'],
            'mapRow' => $spec['mapRow'],
        ])->setPaper('a4', 'landscape');

        return $pdf->download("{$spec['filename']}.pdf");
    }
}
