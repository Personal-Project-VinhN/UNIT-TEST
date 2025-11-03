<?php

namespace App\Repositories\Contracts;

/**
 * Account repository interface
 * 
 * @author Gin<gin_vn@haldata.net>
 * @lastupdate Gin<gin_vn@haldata.net>
 */
interface AccountRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get active accounts
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveAccounts(): \Illuminate\Database\Eloquent\Collection;

    /**
     * Get accounts by type
     *
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findByType(string $type): \Illuminate\Database\Eloquent\Collection;

    /**
     * Update account balance
     *
     * @param int $accountId
     * @param float $amount
     * @return bool
     */
    public function updateBalance(int $accountId, float $amount): bool;
}
