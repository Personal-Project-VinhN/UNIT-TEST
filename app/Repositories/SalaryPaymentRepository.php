<?php

namespace App\Repositories;

use App\Models\SalaryPayment;
use App\Repositories\Contracts\SalaryPaymentRepositoryInterface;

/**
 * SalaryPayment repository implementation
 * 
 * @author Gin<gin_vn@haldata.net>
 * @lastupdate Gin<gin_vn@haldata.net>
 */
class SalaryPaymentRepository extends BaseRepository implements SalaryPaymentRepositoryInterface
{
    /**
     * SalaryPaymentRepository constructor
     */
    public function __construct()
    {
        parent::__construct(new SalaryPayment());
    }

    /**
     * Get salary payments by employee ID
     *
     * @param int $employeeId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findByEmployeeId(int $employeeId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->where('employee_id', $employeeId)->get();
    }

    /**
     * Get salary payments by month and year
     *
     * @param int $month
     * @param int $year
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findByMonthAndYear(int $month, int $year): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model
            ->where('payment_month', $month)
            ->where('payment_year', $year)
            ->get();
    }

    /**
     * Get salary payments by date range
     *
     * @param string $startDate
     * @param string $endDate
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findByDateRange(string $startDate, string $endDate): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->whereBetween('payment_date', [$startDate, $endDate])->get();
    }
}
