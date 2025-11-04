<?php

namespace App\Repositories\Contracts;

/**
 * Employee repository interface
 * 
 * @author Gin<gin_vn@haldata.net>
 * @lastupdate Gin<gin_vn@haldata.net>
 */
interface EmployeeRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Find employee by employee code
     *
     * @param string $employeeCode
     * @return \App\Models\Employee|null
     */
    public function findByEmployeeCode(string $employeeCode): ?\App\Models\Employee;

    /**
     * Get active employees
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveEmployees(): \Illuminate\Database\Eloquent\Collection;

    /**
     * Get employees by department
     *
     * @param string $department
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findByDepartment(string $department): \Illuminate\Database\Eloquent\Collection;
}
