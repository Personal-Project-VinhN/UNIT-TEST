<?php

namespace Tests\Unit\Services;

use App\Models\Account;
use App\Models\Employee;
use App\Models\SalaryPayment;
use App\Repositories\Contracts\AccountRepositoryInterface;
use App\Repositories\Contracts\EmployeeRepositoryInterface;
use App\Repositories\Contracts\SalaryPaymentRepositoryInterface;
use App\Services\SalaryService;
use App\Services\TaxService;
use Illuminate\Database\Eloquent\Collection;
use Mockery;
use Tests\TestCase;

/**
 * Unit tests for SalaryService
 * 
 * @author Gin<gin_vn@haldata.net>
 * @lastupdate Gin<gin_vn@haldata.net>
 */
class SalaryServiceTest extends TestCase
{
    protected SalaryService $salaryService;
    protected $employeeRepository;
    protected $salaryPaymentRepository;
    protected $accountRepository;
    protected $taxService;

    /**
     * Setup test environment
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Mock repositories
        $this->employeeRepository = Mockery::mock(EmployeeRepositoryInterface::class);
        $this->salaryPaymentRepository = Mockery::mock(SalaryPaymentRepositoryInterface::class);
        $this->accountRepository = Mockery::mock(AccountRepositoryInterface::class);
        $this->taxService = Mockery::mock(TaxService::class);

        // Create service instance with mocked repositories
        $this->salaryService = new SalaryService(
            $this->employeeRepository,
            $this->salaryPaymentRepository,
            $this->accountRepository,
            $this->taxService
        );
    }

    /**
     * Test calculating gross salary
     */
    public function test_calculate_gross_salary(): void
    {
        $baseSalary = 10000000;
        $overtimeHours = 10;
        $overtimeRate = 50000;
        $bonus = 2000000;
        $allowance = 1000000;

        $grossSalary = $this->salaryService->calculateGrossSalary(
            $baseSalary,
            $overtimeHours,
            $overtimeRate,
            $bonus,
            $allowance
        );

        $expected = $baseSalary + ($overtimeHours * $overtimeRate) + $bonus + $allowance;
        $this->assertEquals($expected, $grossSalary);
    }

    /**
     * Test calculating insurance deductions
     */
    public function test_calculate_insurance_deductions(): void
    {
        $baseSalary = 10000000;

        $deductions = $this->salaryService->calculateInsuranceDeductions($baseSalary);

        $this->assertEquals(800000, $deductions['social_insurance']); // 8%
        $this->assertEquals(150000, $deductions['health_insurance']); // 1.5%
        $this->assertEquals(100000, $deductions['unemployment_insurance']); // 1%
    }

    /**
     * Test calculating net salary
     */
    public function test_calculate_net_salary(): void
    {
        $grossSalary = 15000000;
        $incomeTax = 500000;
        $socialInsurance = 800000;
        $healthInsurance = 150000;
        $unemploymentInsurance = 100000;
        $deduction = 200000;

        $netSalary = $this->salaryService->calculateNetSalary(
            $grossSalary,
            $incomeTax,
            $socialInsurance,
            $healthInsurance,
            $unemploymentInsurance,
            $deduction
        );

        $expected = $grossSalary - ($incomeTax + $socialInsurance + $healthInsurance + $unemploymentInsurance + $deduction);
        $this->assertEquals($expected, $netSalary);
    }

    /**
     * Test processing salary payment successfully
     */
    public function test_process_salary_payment_success(): void
    {
        $employee = new Employee();
        $employee->id = 1;
        $employee->base_salary = 10000000;

        $account = new Account();
        $account->id = 1;
        $account->balance = 100000000;

        $paymentData = [
            'employee_id' => 1,
            'account_id' => 1,
            'payment_month' => 11,
            'payment_year' => 2024,
            'base_salary' => 10000000,
            'overtime_hours' => 10,
            'overtime_rate' => 50000,
            'bonus' => 2000000,
            'allowance' => 1000000,
            'deduction' => 0,
            'payment_date' => '2024-11-30',
            'payment_method' => 'bank_transfer',
        ];

        $salaryPayment = new SalaryPayment();
        $salaryPayment->id = 1;
        $salaryPayment->fill($paymentData);
        $salaryPayment->gross_salary = 13500000; // 10000000 + 500000 + 2000000 + 1000000
        $salaryPayment->income_tax = 0;
        $salaryPayment->social_insurance = 800000;
        $salaryPayment->health_insurance = 150000;
        $salaryPayment->unemployment_insurance = 100000;
        $salaryPayment->net_salary = 12450000;

        // Mock employee repository
        $this->employeeRepository
            ->shouldReceive('find')
            ->with(1)
            ->once()
            ->andReturn($employee);

        // Mock account repository
        $this->accountRepository
            ->shouldReceive('find')
            ->with(1)
            ->once()
            ->andReturn($account);

        // Mock tax service
        $this->taxService
            ->shouldReceive('calculatePersonalIncomeTax')
            ->once()
            ->andReturn(0);

        // Mock salary payment repository
        $this->salaryPaymentRepository
            ->shouldReceive('create')
            ->once()
            ->andReturn($salaryPayment);

        // Mock account balance update
        $this->accountRepository
            ->shouldReceive('updateBalance')
            ->with(1, -12450000)
            ->once()
            ->andReturn(true);

        $result = $this->salaryService->processSalaryPayment($paymentData);

        $this->assertInstanceOf(SalaryPayment::class, $result);
        $this->assertEquals(1, $result->id);
    }

    /**
     * Test processing salary payment with employee not found
     */
    public function test_process_salary_payment_employee_not_found(): void
    {
        $paymentData = [
            'employee_id' => 999,
            'account_id' => 1,
            'payment_month' => 11,
            'payment_year' => 2024,
            'base_salary' => 10000000,
            'payment_date' => '2024-11-30',
        ];

        $this->employeeRepository
            ->shouldReceive('find')
            ->with(999)
            ->once()
            ->andReturn(null);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Employee not found');

        $this->salaryService->processSalaryPayment($paymentData);
    }

    /**
     * Test processing salary payment with account not found
     */
    public function test_process_salary_payment_account_not_found(): void
    {
        $employee = new Employee();
        $employee->id = 1;

        $paymentData = [
            'employee_id' => 1,
            'account_id' => 999,
            'payment_month' => 11,
            'payment_year' => 2024,
            'base_salary' => 10000000,
            'payment_date' => '2024-11-30',
        ];

        $this->employeeRepository
            ->shouldReceive('find')
            ->with(1)
            ->once()
            ->andReturn($employee);

        $this->accountRepository
            ->shouldReceive('find')
            ->with(999)
            ->once()
            ->andReturn(null);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Account not found');

        $this->salaryService->processSalaryPayment($paymentData);
    }

    /**
     * Test getting salary payments for employee
     */
    public function test_get_employee_salary_payments(): void
    {
        $payments = new Collection([
            new SalaryPayment(['id' => 1, 'employee_id' => 1]),
            new SalaryPayment(['id' => 2, 'employee_id' => 1]),
        ]);

        $this->salaryPaymentRepository
            ->shouldReceive('findByEmployeeId')
            ->with(1)
            ->once()
            ->andReturn($payments);

        $result = $this->salaryService->getEmployeeSalaryPayments(1);

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(2, $result);
    }

    /**
     * Test getting total salary cost for date range
     */
    public function test_get_total_salary_cost(): void
    {
        $payment1 = new SalaryPayment();
        $payment1->gross_salary = 10000000;

        $payment2 = new SalaryPayment();
        $payment2->gross_salary = 15000000;

        $payments = new Collection([$payment1, $payment2]);

        $this->salaryPaymentRepository
            ->shouldReceive('findByDateRange')
            ->with('2024-11-01', '2024-11-30')
            ->once()
            ->andReturn($payments);

        $total = $this->salaryService->getTotalSalaryCost('2024-11-01', '2024-11-30');

        $this->assertEquals(25000000, $total);
    }

    /**
     * Clean up after tests
     */
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
