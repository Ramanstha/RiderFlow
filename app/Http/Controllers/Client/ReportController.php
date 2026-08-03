<?php

namespace App\Http\Controllers\Client;

use App\Exports\ReportExport;
use App\Http\Requests\Report\ReportFilterRequest;
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

/**
 * The same reports the admin panel offers, pinned to the signed-in company.
 * Filters run through scopedFilters(), so a client cannot widen the scope by
 * passing another company_id or a rider that is not on their roster.
 */
class ReportController extends PortalController
{
    public function __construct(protected ReportService $reports) {}

    public function index(): View
    {
        return view('client.reports.index');
    }

    public function attendance(ReportFilterRequest $request): View
    {
        return view('client.reports.attendance', [
            'summary' => $this->reports->attendanceSummary($this->scopedFilters($request->validated())),
            'riders' => $this->riderOptions(),
        ]);
    }

    public function parcels(ReportFilterRequest $request): View
    {
        return view('client.reports.parcels', [
            'summary' => $this->reports->parcelSummary($this->scopedFilters($request->validated())),
            'riders' => $this->riderOptions(),
        ]);
    }

    public function performance(ReportFilterRequest $request): View
    {
        return view('client.reports.performance', [
            'summary' => $this->reports->performanceSummary($this->scopedFilters($request->validated())),
            'riders' => $this->riderOptions(),
        ]);
    }

    public function exportAttendanceCsv(ReportFilterRequest $request): StreamedResponse
    {
        return $this->streamCsv($this->attendanceSpec($request));
    }

    public function exportParcelsCsv(ReportFilterRequest $request): StreamedResponse
    {
        return $this->streamCsv($this->parcelsSpec($request));
    }

    public function exportPerformanceCsv(ReportFilterRequest $request): StreamedResponse
    {
        return $this->streamCsv($this->performanceSpec($request));
    }

    public function exportAttendanceExcel(ReportFilterRequest $request): BinaryFileResponse
    {
        return $this->downloadExcel($this->attendanceSpec($request));
    }

    public function exportParcelsExcel(ReportFilterRequest $request): BinaryFileResponse
    {
        return $this->downloadExcel($this->parcelsSpec($request));
    }

    public function exportPerformanceExcel(ReportFilterRequest $request): BinaryFileResponse
    {
        return $this->downloadExcel($this->performanceSpec($request));
    }

    public function exportAttendancePdf(ReportFilterRequest $request): HttpResponse
    {
        return $this->downloadPdf($this->attendanceSpec($request));
    }

    public function exportParcelsPdf(ReportFilterRequest $request): HttpResponse
    {
        return $this->downloadPdf($this->parcelsSpec($request));
    }

    public function exportPerformancePdf(ReportFilterRequest $request): HttpResponse
    {
        return $this->downloadPdf($this->performanceSpec($request));
    }

    protected function attendanceSpec(ReportFilterRequest $request): array
    {
        return [
            'title' => $this->company()->name.' — Attendance Report',
            'filename' => 'attendance-report',
            'headings' => ['Rider', 'Total Days', 'Present Days', 'Absent Days', 'Leave Days', 'Attendance Rate (%)'],
            'rows' => $this->reports->attendanceSummary($this->scopedFilters($request->validated())),
            'mapRow' => fn ($row) => [
                $row['rider_name'], $row['total_days'], $row['present_days'],
                $row['absent_days'], $row['leave_days'], $row['attendance_rate'],
            ],
        ];
    }

    protected function parcelsSpec(ReportFilterRequest $request): array
    {
        return [
            'title' => $this->company()->name.' — Parcel Report',
            'filename' => 'parcel-report',
            'headings' => ['Rider', 'Assigned', 'Delivered', 'Failed', 'Returned', 'Success Rate (%)'],
            'rows' => $this->reports->parcelSummary($this->scopedFilters($request->validated())),
            'mapRow' => fn ($row) => [
                $row['rider_name'], $row['assigned'], $row['delivered'],
                $row['failed'], $row['returned'], $row['success_rate'],
            ],
        ];
    }

    protected function performanceSpec(ReportFilterRequest $request): array
    {
        return [
            'title' => $this->company()->name.' — Performance Report',
            'filename' => 'performance-report',
            'headings' => ['Rider', 'Average Score (%)', 'Deliveries Completed', 'Deliveries Failed'],
            'rows' => $this->reports->performanceSummary($this->scopedFilters($request->validated())),
            'mapRow' => fn ($row) => [
                $row['rider_name'], $row['average_score'],
                $row['total_deliveries_completed'], $row['total_deliveries_failed'],
            ],
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, \App\Models\Rider>
     */
    protected function riderOptions()
    {
        return $this->company()->riders()->orderBy('name')->get(['id', 'name']);
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
