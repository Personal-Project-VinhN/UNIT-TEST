<?php

namespace Tests\Unit\Services;

use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use App\Repositories\Contracts\AccountRepositoryInterface;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use App\Services\TransactionService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

/**
 * Unit tests for TransactionService
 * 
 * @author Gin<gin_vn@haldata.net>
 * @lastupdate Gin<gin_vn@haldata.net>
 */
class TransactionServiceTest extends TestCase
{
    protected TransactionService $transactionService;
    protected $transactionRepository;
    protected $accountRepository;

    /**
     * Setup test environment
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Mock repositories
        $this->transactionRepository = Mockery::mock(TransactionRepositoryInterface::class);
        $this->accountRepository = Mockery::mock(AccountRepositoryInterface::class);

        // Create service instance with mocked repositories
        $this->transactionService = new TransactionService(
            $this->transactionRepository,
            $this->accountRepository
        );
    }

    /**
     * Test creating a valid revenue transaction
     */
    public function test_create_transaction_success(): void
    {
        $mockData = require __DIR__ . '/../../../mockData/transactions.php';
        $transactionData = $mockData['valid_revenue_transaction'];

        $account = new Account();
        $account->id = $transactionData['account_id'];
        $account->balance = 100000000;

        $transaction = new Transaction();
        $transaction->id = 1;
        $transaction->fill($transactionData);
        $transaction->account_id = $account->id;

        // Mock account repository
        $this->accountRepository
            ->shouldReceive('find')
            ->with($transactionData['account_id'])
            ->once()
            ->andReturn($account);

        // Mock transaction repository
        $this->transactionRepository
            ->shouldReceive('create')
            ->with($transactionData)
            ->once()
            ->andReturn($transaction);

        // Mock account balance update
        $this->accountRepository
            ->shouldReceive('updateBalance')
            ->with($account->id, $transactionData['amount'])
            ->once()
            ->andReturn(true);

        $result = $this->transactionService->createTransaction($transactionData);

        $this->assertInstanceOf(Transaction::class, $result);
        $this->assertEquals($transactionData['amount'], $result->amount);
    }

    /**
     * Test creating transaction with non-existent account
     */
    public function test_create_transaction_account_not_found(): void
    {
        $mockData = require __DIR__ . '/../../../mockData/transactions.php';
        $transactionData = $mockData['valid_revenue_transaction'];

        // Mock account repository to return null
        $this->accountRepository
            ->shouldReceive('find')
            ->with($transactionData['account_id'])
            ->once()
            ->andReturn(null);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Account not found');

        $this->transactionService->createTransaction($transactionData);
    }

    /**
     * Test calculating total revenue for date range
     */
    public function test_get_total_revenue(): void
    {
        $mockData = require __DIR__ . '/../../../mockData/transactions.php';
        $dateRange = $mockData['date_range_test'];

        $transactions = new Collection(
            array_map(function ($data) {
                $transaction = new Transaction();
                $transaction->fill($data);
                return $transaction;
            }, $mockData['multiple_revenue_transactions'])
        );

        // Mock transaction repository
        $this->transactionRepository
            ->shouldReceive('findByDateRange')
            ->with($dateRange['start_date'], $dateRange['end_date'])
            ->once()
            ->andReturn($transactions);

        $totalRevenue = $this->transactionService->getTotalRevenue(
            $dateRange['start_date'],
            $dateRange['end_date']
        );

        $expectedTotal = 60000000; // 30000000 + 20000000 + 10000000
        $this->assertEquals($expectedTotal, $totalRevenue);
    }

    /**
     * Test calculating total expense for date range
     */
    public function test_get_total_expense(): void
    {
        $mockData = require __DIR__ . '/../../../mockData/transactions.php';
        $dateRange = $mockData['date_range_test'];

        $transactions = new Collection(
            array_map(function ($data) {
                $transaction = new Transaction();
                $transaction->fill($data);
                return $transaction;
            }, $mockData['multiple_expense_transactions'])
        );

        // Mock transaction repository
        $this->transactionRepository
            ->shouldReceive('findByDateRange')
            ->with($dateRange['start_date'], $dateRange['end_date'])
            ->once()
            ->andReturn($transactions);

        $totalExpense = $this->transactionService->getTotalExpense(
            $dateRange['start_date'],
            $dateRange['end_date']
        );

        $expectedTotal = 23000000; // 15000000 + 5000000 + 3000000
        $this->assertEquals($expectedTotal, $totalExpense);
    }

    /**
     * Test calculating profit for date range
     */
    public function test_get_profit(): void
    {
        $mockData = require __DIR__ . '/../../../mockData/transactions.php';
        $dateRange = $mockData['date_range_test'];

        $allTransactionsData = array_merge(
            $mockData['multiple_revenue_transactions'],
            $mockData['multiple_expense_transactions']
        );

        $allTransactions = new Collection(
            array_map(function ($data) {
                $transaction = new Transaction();
                $transaction->fill($data);
                return $transaction;
            }, $allTransactionsData)
        );

        // Mock transaction repository for revenue
        $this->transactionRepository
            ->shouldReceive('findByDateRange')
            ->with($dateRange['start_date'], $dateRange['end_date'])
            ->twice()
            ->andReturn($allTransactions);

        $profit = $this->transactionService->getProfit(
            $dateRange['start_date'],
            $dateRange['end_date']
        );

        $expectedProfit = 37000000; // 60000000 - 23000000
        $this->assertEquals($expectedProfit, $profit);
    }

    /**
     * Test updating transaction that does not exist
     */
    public function test_update_transaction_not_found(): void
    {
        $mockData = require __DIR__ . '/../../../mockData/transactions.php';
        $updateData = $mockData['transaction_for_update'];
        $nonExistentId = 999;

        // Mock repository to return null (transaction not found)
        $this->transactionRepository
            ->shouldReceive('find')
            ->with($nonExistentId)
            ->once()
            ->andReturn(null);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Transaction not found');

        $this->transactionService->updateTransaction($nonExistentId, $updateData);
    }

    /**
     * Test deleting transaction that does not exist
     */
    public function test_delete_transaction_not_found(): void
    {
        $nonExistentId = 999;

        // Mock repository to return null (transaction not found)
        $this->transactionRepository
            ->shouldReceive('find')
            ->with($nonExistentId)
            ->once()
            ->andReturn(null);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Transaction not found');

        $this->transactionService->deleteTransaction($nonExistentId);
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
