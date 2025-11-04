<?php

namespace App\Repositories;

use App\Models\Employee;
use App\Repositories\Contracts\EmployeeRepositoryInterface;

/**
 * Employee repository implementation
 * 
 * @author Gin<gin_vn@haldata.net>
 * @lastupdate Gin<gin_vn@haldata.net>
 */
class EmployeeRepository extends BaseRepository implements EmployeeRepositoryInterface
{
    /**
     * EmployeeRepository constructor
     */
    public function __construct()
    {
        parent::__construct(new Employee());
    }

    /**
     * Find employee by employee code
     *
     * @param string $employeeCode
     * @return \App\Models\Employee|null
     */
    public function findByEmployeeCode(string $employeeCode): ?\App\Models\Employee
    {
        return $this->model->where('employee_code', $employeeCode)->first();
    }

    /**
     * Get active employees
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveEmployees(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->where('status', 'active')->get();
    }

    /**
     * Get employees by department
     *
     * @param string $department
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findByDepartment(string $department): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->where('department', $department)->get();
    }
}
