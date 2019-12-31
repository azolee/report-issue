<?php

namespace App\Http\Controllers;

use App\Report;
use App\Http\Resources\ReportResource;
use App\Http\Requests\{
    DeleteReportRequest, ReportRequest, ShowReport
};
use App\Services\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index(Request $request) {
        $reports = Report::with(['user', 'photos'])->forUser()->byStatus()->paginate(
            $request->has('per_page') ? $request->per_page : 25
        );
        return ReportResource::collection($reports);
    }

    public function store(ReportRequest $request) {
        $report = $this->reportService->create( $request );
        return new ReportResource($report);
    }

    public function show(Request $request, Report $report) {
        return new ReportResource($report);
    }

    public function update(ReportRequest $request, Report $report) {
        $report = $this->reportService->update($request, $report);
        return new ReportResource($report);
    }

    public function destroy(DeleteReportRequest $request, Report $report) {
        $this->reportService->delete($report);
        return response()->json(null, 204);
    }
    public function updateStatus(Request $request, Report $report, $status) {
        $report = $this->reportService->updateStatus($request, $report, $status);
        if($report) {
            return new ReportResource($report);
        }
        return abort(400);
    }
}
