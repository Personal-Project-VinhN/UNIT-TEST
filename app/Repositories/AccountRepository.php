<?php

namespace App\Repositories;

use App\Models\Account;
use App\Repositories\Contracts\AccountRepositoryInterface;

/**
 * Account repository implementation
 * 
 * @author Gin<gin_vn@haldata.net>
 * @lastupdate Gin<gin_vn@haldata.net>
 */
class AccountRepository extends BaseRepository implements AccountRepositoryInterface
{
    /**
     * AccountRepository constructor
     */
    public function __construct()
    {
        parent::__construct(new Account());
    }

    /**
     * Get active accounts
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveAccounts(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->where('is_active', true)->get();
    }

    /**
     * Get accounts by type
     *
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findByType(string $type): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->where('account_type', $type)->get();
    }

    /**
     * Update account balance
     *
     * @param int $accountId
     * @param float $amount
     * @return bool
     */
    public function updateBalance(int $accountId, float $amount): bool
    {
        $account = $this->find($accountId);
        
        if (!$account) {
            return false;
        }

        $account->balance += $amount;
        return $account->save();
    }
}
