<?php

namespace Tests\Unit\Services;

use App\Models\Account;
use App\Repositories\Contracts\AccountRepositoryInterface;
use App\Services\AccountService;
use Illuminate\Database\Eloquent\Collection;
use Mockery;
use Tests\TestCase;

/**
 * Unit tests for AccountService
 * 
 * @author Gin<gin_vn@haldata.net>
 * @lastupdate Gin<gin_vn@haldata.net>
 */
class AccountServiceTest extends TestCase
{
    protected AccountService $accountService;
    protected $accountRepository;

    /**
     * Setup test environment
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Mock repository
        $this->accountRepository = Mockery::mock(AccountRepositoryInterface::class);

        // Create service instance with mocked repository
        $this->accountService = new AccountService($this->accountRepository);
    }

    /**
     * Test getting all accounts
     */
    public function test_get_all_accounts(): void
    {
        $mockData = require __DIR__ . '/../../../mockData/accounts.php';

        $accounts = new Collection(
            array_map(function ($data) {
                $account = new Account();
                $account->fill($data);
                return $account;
            }, $mockData['multiple_accounts'])
        );

        $this->accountRepository
            ->shouldReceive('all')
            ->once()
            ->andReturn($accounts);

        $result = $this->accountService->getAllAccounts();

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(3, $result);
    }

    /**
     * Test getting active accounts
     */
    public function test_get_active_accounts(): void
    {
        $mockData = require __DIR__ . '/../../../mockData/accounts.php';

        $activeAccountsData = array_filter(
            $mockData['multiple_accounts'],
            fn($data) => ($data['is_active'] ?? true)
        );

        $accounts = new Collection(
            array_map(function ($data) {
                $account = new Account();
                $account->fill($data);
                return $account;
            }, $activeAccountsData)
        );

        $this->accountRepository
            ->shouldReceive('getActiveAccounts')
            ->once()
            ->andReturn($accounts);

        $result = $this->accountService->getActiveAccounts();

        $this->assertInstanceOf(Collection::class, $result);
        $result->each(function ($account) {
            $this->assertTrue($account->is_active);
        });
    }

    /**
     * Test creating a new account
     */
    public function test_create_account(): void
    {
        $mockData = require __DIR__ . '/../../../mockData/accounts.php';
        $accountData = $mockData['valid_bank_account'];

        $account = new Account();
        $account->id = 1;
        $account->fill($accountData);

        $this->accountRepository
            ->shouldReceive('create')
            ->with($accountData)
            ->once()
            ->andReturn($account);

        $result = $this->accountService->createAccount($accountData);

        $this->assertInstanceOf(Account::class, $result);
        $this->assertEquals($accountData['name'], $result->name);
        $this->assertEquals($accountData['account_type'], $result->account_type);
    }

    /**
     * Test calculating total balance
     */
    public function test_get_total_balance(): void
    {
        $mockData = require __DIR__ . '/../../../mockData/accounts.php';

        $activeAccountsData = array_filter(
            $mockData['multiple_accounts'],
            fn($data) => ($data['is_active'] ?? true)
        );

        $accounts = new Collection(
            array_map(function ($data) {
                $account = new Account();
                $account->fill($data);
                $account->balance = $data['balance'];
                $account->is_active = $data['is_active'] ?? true;
                return $account;
            }, $activeAccountsData)
        );

        $this->accountRepository
            ->shouldReceive('getActiveAccounts')
            ->once()
            ->andReturn($accounts);

        $totalBalance = $this->accountService->getTotalBalance();

        $expectedTotal = 105000000; // 100000000 + 5000000 + 0
        $this->assertEquals($expectedTotal, $totalBalance);
    }

    /**
     * Test updating account that does not exist
     */
    public function test_update_account_not_found(): void
    {
        $mockData = require __DIR__ . '/../../../mockData/accounts.php';
        $updateData = ['name' => 'Updated Account'];
        $nonExistentId = 999;

        // Mock repository to return false (account not found or update failed)
        $this->accountRepository
            ->shouldReceive('update')
            ->with($nonExistentId, $updateData)
            ->once()
            ->andReturn(false);

        $result = $this->accountService->updateAccount($nonExistentId, $updateData);

        $this->assertFalse($result);
    }

    /**
     * Test deleting account that does not exist
     */
    public function test_delete_account_not_found(): void
    {
        $nonExistentId = 999;

        // Mock repository to return false (account not found or delete failed)
        $this->accountRepository
            ->shouldReceive('delete')
            ->with($nonExistentId)
            ->once()
            ->andReturn(false);

        $result = $this->accountService->deleteAccount($nonExistentId);

        $this->assertFalse($result);
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
