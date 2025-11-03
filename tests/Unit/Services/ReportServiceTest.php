<?php

namespace Tests\Unit\Services;

use App\Models\Transaction;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use App\Services\ReportService;
use App\Services\TransactionService;
use Illuminate\Database\Eloquent\Collection;
use Mockery;
use Tests\TestCase;

/**
 * Unit tests for ReportService
 * 
 * @author Gin<gin_vn@haldata.net>
 * @lastupdate Gin<gin_vn@haldata.net>
 */
class ReportServiceTest extends TestCase
{
    protected ReportService $reportService;
    protected $transactionRepository;
    protected $transactionService;

    /**
     * Setup test environment
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Mock dependencies
        $this->transactionRepository = Mockery::mock(TransactionRepositoryInterface::class);
        $this->transactionService = Mockery::mock(TransactionService::class);

        // Create service instance
        $this->reportService = new ReportService(
            $this->transactionRepository,
            $this->transactionService
        );
    }

    /**
     * Test getting daily report
     */
    public function test_get_daily_report(): void
    {
        $mockData = require __DIR__ . '/../../../mockData/reports.php';
        $requestData = $mockData['daily_report_request'];
        $expectedResponse = $mockData['expected_daily_report_response'];

        $transactions = new Collection([
            new Transaction(['type' => 'revenue', 'amount' => 50000000]),
            new Transaction(['type' => 'expense', 'amount' => 15000000]),
        ]);

        $this->transactionRepository
            ->shouldReceive('findByDateRange')
            ->with($requestData['date'], $requestData['date'])
            ->once()
            ->andReturn($transactions);

        $this->transactionService
            ->shouldReceive('getTotalRevenue')
            ->with($requestData['date'], $requestData['date'])
            ->once()
            ->andReturn($expectedResponse['total_revenue']);

        $this->transactionService
            ->shouldReceive('getTotalExpense')
            ->with($requestData['date'], $requestData['date'])
            ->once()
            ->andReturn($expectedResponse['total_expense']);

        $this->transactionService
            ->shouldReceive('getProfit')
            ->with($requestData['date'], $requestData['date'])
            ->once()
            ->andReturn($expectedResponse['profit']);

        $report = $this->reportService->getDailyReport($requestData['date']);

        $this->assertEquals($expectedResponse['start_date'], $report['start_date']);
        $this->assertEquals($expectedResponse['end_date'], $report['end_date']);
        $this->assertEquals($expectedResponse['total_revenue'], $report['total_revenue']);
        $this->assertEquals($expectedResponse['total_expense'], $report['total_expense']);
        $this->assertEquals($expectedResponse['profit'], $report['profit']);
    }

    /**
     * Test getting monthly report
     */
    public function test_get_monthly_report(): void
    {
        $mockData = require __DIR__ . '/../../../mockData/reports.php';
        $requestData = $mockData['monthly_report_request'];

        $transactions = new Collection([
            new Transaction(['type' => 'revenue', 'amount' => 50000000, 'category_id' => 1]),
            new Transaction(['type' => 'expense', 'amount' => 15000000, 'category_id' => 4]),
        ]);

        $this->transactionRepository
            ->shouldReceive('findByDateRange')
            ->once()
            ->andReturn($transactions);

        $this->transactionService
            ->shouldReceive('getTotalRevenue')
            ->once()
            ->andReturn(80000000);

        $this->transactionService
            ->shouldReceive('getTotalExpense')
            ->once()
            ->andReturn(23000000);

        $this->transactionService
            ->shouldReceive('getProfit')
            ->once()
            ->andReturn(57000000);

        $report = $this->reportService->getMonthlyReport(
            (string) $requestData['year'],
            (string) $requestData['month']
        );

        $this->assertArrayHasKey('start_date', $report);
        $this->assertArrayHasKey('end_date', $report);
        $this->assertArrayHasKey('total_revenue', $report);
        $this->assertArrayHasKey('total_expense', $report);
        $this->assertArrayHasKey('profit', $report);
    }

    /**
     * Test getting report by date range
     */
    public function test_get_report_by_date_range(): void
    {
        $mockData = require __DIR__ . '/../../../mockData/reports.php';
        $requestData = $mockData['date_range_report_request'];

        $transactions = new Collection([
            new Transaction(['type' => 'revenue', 'amount' => 50000000, 'category_id' => 1]),
            new Transaction(['type' => 'expense', 'amount' => 15000000, 'category_id' => 4]),
        ]);

        $this->transactionRepository
            ->shouldReceive('findByDateRange')
            ->with($requestData['start_date'], $requestData['end_date'])
            ->once()
            ->andReturn($transactions);

        $this->transactionService
            ->shouldReceive('getTotalRevenue')
            ->with($requestData['start_date'], $requestData['end_date'])
            ->once()
            ->andReturn(80000000);

        $this->transactionService
            ->shouldReceive('getTotalExpense')
            ->with($requestData['start_date'], $requestData['end_date'])
            ->once()
            ->andReturn(23000000);

        $this->transactionService
            ->shouldReceive('getProfit')
            ->with($requestData['start_date'], $requestData['end_date'])
            ->once()
            ->andReturn(57000000);

        $report = $this->reportService->getReportByDateRange(
            $requestData['start_date'],
            $requestData['end_date']
        );

        $this->assertEquals($requestData['start_date'], $report['start_date']);
        $this->assertEquals($requestData['end_date'], $report['end_date']);
        $this->assertArrayHasKey('revenue_by_category', $report);
        $this->assertArrayHasKey('expense_by_category', $report);
    }

    /**
     * Test getting daily report with empty data
     */
    public function test_get_daily_report_empty_data(): void
    {
        $mockData = require __DIR__ . '/../../../mockData/reports.php';
        $requestData = $mockData['daily_report_request'];

        // Empty transactions collection
        $emptyTransactions = new Collection([]);

        $this->transactionRepository
            ->shouldReceive('findByDateRange')
            ->with($requestData['date'], $requestData['date'])
            ->once()
            ->andReturn($emptyTransactions);

        $this->transactionService
            ->shouldReceive('getTotalRevenue')
            ->with($requestData['date'], $requestData['date'])
            ->once()
            ->andReturn(0);

        $this->transactionService
            ->shouldReceive('getTotalExpense')
            ->with($requestData['date'], $requestData['date'])
            ->once()
            ->andReturn(0);

        $this->transactionService
            ->shouldReceive('getProfit')
            ->with($requestData['date'], $requestData['date'])
            ->once()
            ->andReturn(0);

        $report = $this->reportService->getDailyReport($requestData['date']);

        $this->assertEquals($requestData['date'], $report['start_date']);
        $this->assertEquals($requestData['date'], $report['end_date']);
        $this->assertEquals(0, $report['total_revenue']);
        $this->assertEquals(0, $report['total_expense']);
        $this->assertEquals(0, $report['profit']);
        $this->assertEquals(0, $report['transaction_count']);
    }

    /**
     * Clean up after tests
     */
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
