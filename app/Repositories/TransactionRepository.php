<?php

namespace App\Repositories;

use App\Models\Transaction;
use App\Repositories\Contracts\TransactionRepositoryInterface;

/**
 * Transaction repository implementation
 * 
 * @author Gin<gin_vn@haldata.net>
 * @lastupdate Gin<gin_vn@haldata.net>
 */
class TransactionRepository extends BaseRepository implements TransactionRepositoryInterface
{
    /**
     * TransactionRepository constructor
     */
    public function __construct()
    {
        parent::__construct(new Transaction());
    }

    /**
     * Get transactions by account ID
     *
     * @param int $accountId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findByAccountId(int $accountId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->where('account_id', $accountId)->get();
    }

    /**
     * Get transactions by type
     *
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findByType(string $type): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->where('type', $type)->get();
    }

    /**
     * Get transactions by date range
     *
     * @param string $startDate
     * @param string $endDate
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findByDateRange(string $startDate, string $endDate): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->whereBetween('transaction_date', [$startDate, $endDate])->get();
    }

    /**
     * Get transactions by category ID
     *
     * @param int $categoryId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findByCategoryId(int $categoryId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->where('category_id', $categoryId)->get();
    }
}
