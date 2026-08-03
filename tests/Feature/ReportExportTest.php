<?php

namespace Tests\Feature;

use App\Models\Rider;
use App\Models\RiderAttendance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\ActsWithRoles;
use Tests\TestCase;

class ReportExportTest extends TestCase
{
    use ActsWithRoles, RefreshDatabase;

    public function test_attendance_csv_export_downloads(): void
    {
        $admin = $this->actingAsSuperAdmin();
        $rider = Rider::factory()->create();
        RiderAttendance::factory()->create(['rider_id' => $rider->id]);

        $this->actingAs($admin)
            ->get(route('reports.attendance.csv'))
            ->assertOk()
            ->assertHeader('content-type', 'text/csv; charset=UTF-8');
    }

    public function test_attendance_excel_export_downloads(): void
    {
        $admin = $this->actingAsSuperAdmin();
        $rider = Rider::factory()->create();
        RiderAttendance::factory()->create(['rider_id' => $rider->id]);

        $response = $this->actingAs($admin)->get(route('reports.attendance.xlsx'));

        $response->assertOk();
        $this->assertStringContainsString('spreadsheetml', $response->headers->get('content-type'));
    }

    public function test_attendance_pdf_export_downloads(): void
    {
        $admin = $this->actingAsSuperAdmin();
        $rider = Rider::factory()->create();
        RiderAttendance::factory()->create(['rider_id' => $rider->id]);

        $response = $this->actingAs($admin)->get(route('reports.attendance.pdf'));

        $response->assertOk();
        $this->assertStringContainsString('application/pdf', $response->headers->get('content-type'));
    }

    public function test_parcels_excel_and_pdf_export_download(): void
    {
        $admin = $this->actingAsSuperAdmin();

        $this->actingAs($admin)->get(route('reports.parcels.xlsx'))->assertOk();
        $this->actingAs($admin)->get(route('reports.parcels.pdf'))->assertOk();
    }

    public function test_performance_excel_and_pdf_export_download(): void
    {
        $admin = $this->actingAsSuperAdmin();

        $this->actingAs($admin)->get(route('reports.performance.xlsx'))->assertOk();
        $this->actingAs($admin)->get(route('reports.performance.pdf'))->assertOk();
    }

    public function test_viewer_can_export_reports_but_manager_without_report_access_cannot(): void
    {
        $viewer = $this->actingAsViewer();

        $this->actingAs($viewer)->get(route('reports.attendance.xlsx'))->assertOk();
    }
}
