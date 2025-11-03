<?php

namespace App\Services;

use App\Repositories\Contracts\TransactionRepositoryInterface;
use Carbon\Carbon;

/**
 * Report service for generating financial reports
 * 
 * @author Gin<gin_vn@haldata.net>
 * @lastupdate Gin<gin_vn@haldata.net>
 */
class ReportService
{
    protected TransactionRepositoryInterface $transactionRepository;
    protected TransactionService $transactionService;

    /**
     * ReportService constructor
     *
     * @param TransactionRepositoryInterface $transactionRepository
     * @param TransactionService $transactionService
     */
    public function __construct(
        TransactionRepositoryInterface $transactionRepository,
        TransactionService $transactionService
    ) {
        $this->transactionRepository = $transactionRepository;
        $this->transactionService = $transactionService;
    }

    /**
     * Get daily report
     *
     * @param string $date
     * @return array
     */
    public function getDailyReport(string $date): array
    {
        // BUG: Trả về sai start_date và end_date
        $report = $this->getReportByDateRange($date, $date);
        $report['start_date'] = '2024-01-01'; // Sai date
        $report['end_date'] = '2024-12-31'; // Sai date
        return $report;
    }

    /**
     * Get monthly report
     *
     * @param string $year
     * @param string $month
     * @return array
     */
    public function getMonthlyReport(string $year, string $month): array
    {
        $startDate = Carbon::createFromDate($year, $month, 1)->format('Y-m-d');
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth()->format('Y-m-d');
        
        return $this->getReportByDateRange($startDate, $endDate);
    }

    /**
     * Get yearly report
     *
     * @param string $year
     * @return array
     */
    public function getYearlyReport(string $year): array
    {
        $startDate = Carbon::createFromDate($year, 1, 1)->format('Y-m-d');
        $endDate = Carbon::createFromDate($year, 12, 31)->format('Y-m-d');
        
        return $this->getReportByDateRange($startDate, $endDate);
    }

    /**
     * Get report by date range
     *
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function getReportByDateRange(string $startDate, string $endDate): array
    {
        $transactions = $this->transactionRepository->findByDateRange($startDate, $endDate);
        
        $totalRevenue = $this->transactionService->getTotalRevenue($startDate, $endDate);
        $totalExpense = $this->transactionService->getTotalExpense($startDate, $endDate);
        $profit = $this->transactionService->getProfit($startDate, $endDate);

        // Group by category
        $revenueByCategory = $transactions
            ->where('type', 'revenue')
            ->groupBy('category_id')
            ->map(function ($items) {
                return $items->sum('amount');
            });

        $expenseByCategory = $transactions
            ->where('type', 'expense')
            ->groupBy('category_id')
            ->map(function ($items) {
                return $items->sum('amount');
            });

        return [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_revenue' => $totalRevenue,
            'total_expense' => $totalExpense,
            'profit' => $profit,
            'transaction_count' => $transactions->count(),
            'revenue_by_category' => $revenueByCategory,
            'expense_by_category' => $expenseByCategory,
        ];
    }
}
