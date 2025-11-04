<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\SalaryPayment;
use App\Repositories\Contracts\AccountRepositoryInterface;
use App\Repositories\Contracts\EmployeeRepositoryInterface;
use App\Repositories\Contracts\SalaryPaymentRepositoryInterface;
use App\Services\TaxService;

/**
 * Salary service for handling salary payment operations
 * 
 * @author Gin<gin_vn@haldata.net>
 * @lastupdate Gin<gin_vn@haldata.net>
 */
class SalaryService
{
    protected EmployeeRepositoryInterface $employeeRepository;
    protected SalaryPaymentRepositoryInterface $salaryPaymentRepository;
    protected AccountRepositoryInterface $accountRepository;
    protected TaxService $taxService;

    // Insurance rates (Vietnam standard rates)
    const SOCIAL_INSURANCE_RATE = 0.08; // 8% of base salary
    const HEALTH_INSURANCE_RATE = 0.015; // 1.5% of base salary
    const UNEMPLOYMENT_INSURANCE_RATE = 0.01; // 1% of base salary

    /**
     * SalaryService constructor
     *
     * @param EmployeeRepositoryInterface $employeeRepository
     * @param SalaryPaymentRepositoryInterface $salaryPaymentRepository
     * @param AccountRepositoryInterface $accountRepository
     * @param TaxService $taxService
     */
    public function __construct(
        EmployeeRepositoryInterface $employeeRepository,
        SalaryPaymentRepositoryInterface $salaryPaymentRepository,
        AccountRepositoryInterface $accountRepository,
        TaxService $taxService
    ) {
        $this->employeeRepository = $employeeRepository;
        $this->salaryPaymentRepository = $salaryPaymentRepository;
        $this->accountRepository = $accountRepository;
        $this->taxService = $taxService;
    }

    /**
     * Calculate gross salary
     *
     * @param float $baseSalary
     * @param float $overtimeHours
     * @param float $overtimeRate
     * @param float $bonus
     * @param float $allowance
     * @return float
     */
    public function calculateGrossSalary(
        float $baseSalary,
        float $overtimeHours = 0,
        float $overtimeRate = 0,
        float $bonus = 0,
        float $allowance = 0
    ): float {
        $overtimeAmount = $overtimeHours * $overtimeRate;
        return $baseSalary + $overtimeAmount + $bonus + $allowance;
    }

    /**
     * Calculate insurance deductions
     *
     * @param float $baseSalary
     * @return array
     */
    public function calculateInsuranceDeductions(float $baseSalary): array
    {
        return [
            'social_insurance' => $baseSalary * self::SOCIAL_INSURANCE_RATE,
            'health_insurance' => $baseSalary * self::HEALTH_INSURANCE_RATE,
            'unemployment_insurance' => $baseSalary * self::UNEMPLOYMENT_INSURANCE_RATE,
        ];
    }

    /**
     * Calculate net salary
     *
     * @param float $grossSalary
     * @param float $incomeTax
     * @param float $socialInsurance
     * @param float $healthInsurance
     * @param float $unemploymentInsurance
     * @param float $deduction
     * @return float
     */
    public function calculateNetSalary(
        float $grossSalary,
        float $incomeTax,
        float $socialInsurance,
        float $healthInsurance,
        float $unemploymentInsurance,
        float $deduction = 0
    ): float {
        $totalDeductions = $incomeTax + $socialInsurance + $healthInsurance + $unemploymentInsurance + $deduction;
        return $grossSalary - $totalDeductions;
    }

    /**
     * Process salary payment for employee
     *
     * @param array $data
     * @return SalaryPayment
     * @throws \Exception
     */
    public function processSalaryPayment(array $data): SalaryPayment
    {
        // Validate employee exists
        $employee = $this->employeeRepository->find($data['employee_id']);
        if (!$employee) {
            throw new \Exception('Employee not found');
        }

        // Validate account exists
        $account = $this->accountRepository->find($data['account_id']);
        if (!$account) {
            throw new \Exception('Account not found');
        }

        // Calculate gross salary
        $grossSalary = $this->calculateGrossSalary(
            $data['base_salary'],
            $data['overtime_hours'] ?? 0,
            $data['overtime_rate'] ?? 0,
            $data['bonus'] ?? 0,
            $data['allowance'] ?? 0
        );

        // Calculate insurance deductions
        $insuranceDeductions = $this->calculateInsuranceDeductions($data['base_salary']);

        // Calculate income tax (personal income tax)
        $incomeTax = $this->taxService->calculatePersonalIncomeTax($grossSalary);

        // Calculate net salary
        $netSalary = $this->calculateNetSalary(
            $grossSalary,
            $incomeTax,
            $insuranceDeductions['social_insurance'],
            $insuranceDeductions['health_insurance'],
            $insuranceDeductions['unemployment_insurance'],
            $data['deduction'] ?? 0
        );

        // Prepare payment data
        $paymentData = [
            'employee_id' => $data['employee_id'],
            'account_id' => $data['account_id'],
            'payment_month' => $data['payment_month'],
            'payment_year' => $data['payment_year'],
            'base_salary' => $data['base_salary'],
            'overtime_hours' => $data['overtime_hours'] ?? 0,
            'overtime_rate' => $data['overtime_rate'] ?? 0,
            'bonus' => $data['bonus'] ?? 0,
            'allowance' => $data['allowance'] ?? 0,
            'deduction' => $data['deduction'] ?? 0,
            'gross_salary' => $grossSalary,
            'income_tax' => $incomeTax,
            'social_insurance' => $insuranceDeductions['social_insurance'],
            'health_insurance' => $insuranceDeductions['health_insurance'],
            'unemployment_insurance' => $insuranceDeductions['unemployment_insurance'],
            'net_salary' => $netSalary,
            'payment_date' => $data['payment_date'],
            'payment_method' => $data['payment_method'] ?? 'bank_transfer',
            'reference_number' => $data['reference_number'] ?? null,
            'notes' => $data['notes'] ?? null,
        ];

        // Create salary payment record
        $salaryPayment = $this->salaryPaymentRepository->create($paymentData);

        // Update account balance (deduct net salary)
        $this->accountRepository->updateBalance($account->id, -$netSalary);

        return $salaryPayment;
    }

    /**
     * Get salary payment by ID
     *
     * @param int $id
     * @return SalaryPayment|null
     */
    public function getSalaryPayment(int $id): ?SalaryPayment
    {
        return $this->salaryPaymentRepository->find($id);
    }

    /**
     * Get salary payments for employee
     *
     * @param int $employeeId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getEmployeeSalaryPayments(int $employeeId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->salaryPaymentRepository->findByEmployeeId($employeeId);
    }

    /**
     * Get salary payments for month and year
     *
     * @param int $month
     * @param int $year
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSalaryPaymentsByMonth(int $month, int $year): \Illuminate\Database\Eloquent\Collection
    {
        return $this->salaryPaymentRepository->findByMonthAndYear($month, $year);
    }

    /**
     * Get total salary cost for date range
     *
     * @param string $startDate
     * @param string $endDate
     * @return float
     */
    public function getTotalSalaryCost(string $startDate, string $endDate): float
    {
        $payments = $this->salaryPaymentRepository->findByDateRange($startDate, $endDate);
        return $payments->sum('gross_salary');
    }
}
