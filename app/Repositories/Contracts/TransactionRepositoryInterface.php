<?php

namespace App\Repositories\Contracts;

/**
 * Transaction repository interface
 * 
 * @author Gin<gin_vn@haldata.net>
 * @lastupdate Gin<gin_vn@haldata.net>
 */
interface TransactionRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get transactions by account ID
     *
     * @param int $accountId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findByAccountId(int $accountId): \Illuminate\Database\Eloquent\Collection;

    /**
     * Get transactions by type
     *
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findByType(string $type): \Illuminate\Database\Eloquent\Collection;

    /**
     * Get transactions by date range
     *
     * @param string $startDate
     * @param string $endDate
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findByDateRange(string $startDate, string $endDate): \Illuminate\Database\Eloquent\Collection;

    /**
     * Get transactions by category ID
     *
     * @param int $categoryId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findByCategoryId(int $categoryId): \Illuminate\Database\Eloquent\Collection;
}
