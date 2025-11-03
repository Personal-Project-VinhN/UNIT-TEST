<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Report controller for generating financial reports
 * 
 * @author Gin<gin_vn@haldata.net>
 * @lastupdate Gin<gin_vn@haldata.net>
 */
class ReportController extends Controller
{
    protected ReportService $reportService;

    /**
     * ReportController constructor
     *
     * @param ReportService $reportService
     */
    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * Get daily report
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function daily(Request $request): JsonResponse
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        $report = $this->reportService->getDailyReport($request->date);
        return response()->json($report);
    }

    /**
     * Get monthly report
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function monthly(Request $request): JsonResponse
    {
        $request->validate([
            'year' => 'required|integer|min:2000|max:2100',
            'month' => 'required|integer|min:1|max:12',
        ]);

        $report = $this->reportService->getMonthlyReport(
            (string) $request->year,
            (string) $request->month
        );
        return response()->json($report);
    }

    /**
     * Get yearly report
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function yearly(Request $request): JsonResponse
    {
        $request->validate([
            'year' => 'required|integer|min:2000|max:2100',
        ]);

        $report = $this->reportService->getYearlyReport((string) $request->year);
        return response()->json($report);
    }

    /**
     * Get report by date range
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function dateRange(Request $request): JsonResponse
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $report = $this->reportService->getReportByDateRange(
            $request->start_date,
            $request->end_date
        );
        return response()->json($report);
    }
}
