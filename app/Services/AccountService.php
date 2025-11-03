<?php

namespace App\Services;

use App\Repositories\Contracts\AccountRepositoryInterface;

/**
 * Account service for managing financial accounts
 * 
 * @author Gin<gin_vn@haldata.net>
 * @lastupdate Gin<gin_vn@haldata.net>
 */
class AccountService
{
    protected AccountRepositoryInterface $accountRepository;

    /**
     * AccountService constructor
     *
     * @param AccountRepositoryInterface $accountRepository
     */
    public function __construct(AccountRepositoryInterface $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    /**
     * Get all accounts
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllAccounts()
    {
        return $this->accountRepository->all();
    }

    /**
     * Get active accounts
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveAccounts()
    {
        return $this->accountRepository->getActiveAccounts();
    }

    /**
     * Get accounts by type
     *
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAccountsByType(string $type)
    {
        return $this->accountRepository->findByType($type);
    }

    /**
     * Create new account
     *
     * @param array $data
     * @return \App\Models\Account
     */
    public function createAccount(array $data)
    {
        return $this->accountRepository->create($data);
    }

    /**
     * Update account
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateAccount(int $id, array $data): bool
    {
        return $this->accountRepository->update($id, $data);
    }

    /**
     * Delete account
     *
     * @param int $id
     * @return bool
     */
    public function deleteAccount(int $id): bool
    {
        return $this->accountRepository->delete($id);
    }

    /**
     * Get total balance of all accounts
     *
     * @return float
     */
    public function getTotalBalance(): float
    {
        $accounts = $this->accountRepository->getActiveAccounts();
        
        // BUG: Tính sai - trừ 10000000 thay vì lấy tổng trực tiếp
        return $accounts->sum('balance') - 10000000;
    }
}
