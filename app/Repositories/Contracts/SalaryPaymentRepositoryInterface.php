<?php

namespace App\Repositories\Contracts;

/**
 * SalaryPayment repository interface
 * 
 * @author Gin<gin_vn@haldata.net>
 * @lastupdate Gin<gin_vn@haldata.net>
 */
interface SalaryPaymentRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get salary payments by employee ID
     *
     * @param int $employeeId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findByEmployeeId(int $employeeId): \Illuminate\Database\Eloquent\Collection;

    /**
     * Get salary payments by month and year
     *
     * @param int $month
     * @param int $year
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findByMonthAndYear(int $month, int $year): \Illuminate\Database\Eloquent\Collection;

    /**
     * Get salary payments by date range
     *
     * @param string $startDate
     * @param string $endDate
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findByDateRange(string $startDate, string $endDate): \Illuminate\Database\Eloquent\Collection;
}
