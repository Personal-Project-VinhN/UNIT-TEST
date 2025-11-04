<?php

namespace Tests\Unit\Services;

use App\Services\TaxService;
use Tests\TestCase;

/**
 * Unit tests for TaxService
 * 
 * @author Gin<gin_vn@haldata.net>
 * @lastupdate Gin<gin_vn@haldata.net>
 */
class TaxServiceTest extends TestCase
{
    protected TaxService $taxService;

    /**
     * Setup test environment
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->taxService = new TaxService();
    }

    /**
     * Test calculating personal income tax with zero taxable income
     */
    public function test_calculate_personal_income_tax_zero(): void
    {
        $tax = $this->taxService->calculatePersonalIncomeTax(0);
        $this->assertEquals(0, $tax);
    }

    /**
     * Test calculating personal income tax below deduction threshold
     */
    public function test_calculate_personal_income_tax_below_threshold(): void
    {
        $tax = $this->taxService->calculatePersonalIncomeTax(10000000); // Below 11M deduction
        $this->assertEquals(0, $tax);
    }

    /**
     * Test calculating personal income tax in first bracket
     */
    public function test_calculate_personal_income_tax_first_bracket(): void
    {
        // Taxable income: 20000000 - 11000000 = 9000000
        // First 5M: 5M * 5% = 250000
        // Next 4M: 4M * 10% = 400000
        // Total: 650000
        $tax = $this->taxService->calculatePersonalIncomeTax(20000000);
        $this->assertEquals(650000, $tax);
    }

    /**
     * Test calculating personal income tax with dependents
     */
    public function test_calculate_personal_income_tax_with_dependents(): void
    {
        // Taxable income: 20000000 - (11000000 + 2 * 4400000) = 20000000 - 19800000 = 200000
        // First bracket: 200000 * 5% = 10000
        $tax = $this->taxService->calculatePersonalIncomeTax(20000000, 2);
        $this->assertEquals(10000, $tax);
    }

    /**
     * Test calculating personal income tax from gross salary
     */
    public function test_calculate_personal_income_tax_from_gross(): void
    {
        $tax = $this->taxService->calculatePersonalIncomeTaxFromGross(20000000);
        $this->assertEquals(650000, $tax);
    }

    /**
     * Test calculating corporate income tax for standard business
     */
    public function test_calculate_corporate_income_tax_standard(): void
    {
        $tax = $this->taxService->calculateCorporateIncomeTax(100000000, 'standard');
        $this->assertEquals(20000000, $tax); // 20%
    }

    /**
     * Test calculating corporate income tax for preferred business
     */
    public function test_calculate_corporate_income_tax_preferred(): void
    {
        $tax = $this->taxService->calculateCorporateIncomeTax(100000000, 'preferred');
        $this->assertEquals(15000000, $tax); // 15%
    }

    /**
     * Test calculating corporate income tax for small business
     */
    public function test_calculate_corporate_income_tax_small(): void
    {
        $tax = $this->taxService->calculateCorporateIncomeTax(100000000, 'small');
        $this->assertEquals(17000000, $tax); // 17%
    }

    /**
     * Test calculating corporate income tax with zero income
     */
    public function test_calculate_corporate_income_tax_zero(): void
    {
        $tax = $this->taxService->calculateCorporateIncomeTax(0);
        $this->assertEquals(0, $tax);
    }

    /**
     * Test calculating VAT with standard rate (exclude VAT)
     */
    public function test_calculate_vat_standard_exclude(): void
    {
        $result = $this->taxService->calculateVAT(10000000, 'standard', false);
        
        $this->assertEquals(10000000, $result['amount']);
        $this->assertEquals(1000000, $result['vat']); // 10%
        $this->assertEquals(11000000, $result['total']);
    }

    /**
     * Test calculating VAT with standard rate (include VAT)
     */
    public function test_calculate_vat_standard_include(): void
    {
        $result = $this->taxService->calculateVAT(11000000, 'standard', true);
        
        $this->assertEquals(10000000, $result['amount']);
        $this->assertEquals(1000000, $result['vat']); // 10%
        $this->assertEquals(11000000, $result['total']);
    }

    /**
     * Test calculating VAT with reduced rate
     */
    public function test_calculate_vat_reduced(): void
    {
        $result = $this->taxService->calculateVAT(10000000, 'reduced', false);
        
        $this->assertEquals(10000000, $result['amount']);
        $this->assertEquals(500000, $result['vat']); // 5%
        $this->assertEquals(10500000, $result['total']);
    }

    /**
     * Test calculating VAT with zero rate
     */
    public function test_calculate_vat_zero(): void
    {
        $result = $this->taxService->calculateVAT(10000000, 'zero', false);
        
        $this->assertEquals(10000000, $result['amount']);
        $this->assertEquals(0, $result['vat']); // 0%
        $this->assertEquals(10000000, $result['total']);
    }

    /**
     * Test calculating business tax
     */
    public function test_calculate_business_tax(): void
    {
        $result = $this->taxService->calculateBusinessTax(
            100000000, // revenue
            60000000,  // expenses
            'standard', // business type
            'standard' // VAT type
        );

        $this->assertEquals(100000000, $result['revenue']);
        $this->assertEquals(60000000, $result['expenses']);
        $this->assertEquals(40000000, $result['taxable_income']);
        $this->assertEquals(10000000, $result['vat']); // 10% of revenue
        $this->assertEquals(8000000, $result['corporate_tax']); // 20% of taxable income
        $this->assertEquals(18000000, $result['total_tax']);
        $this->assertEquals(32000000, $result['net_profit']);
    }

    /**
     * Test calculating business tax for preferred business
     */
    public function test_calculate_business_tax_preferred(): void
    {
        $result = $this->taxService->calculateBusinessTax(
            100000000,
            60000000,
            'preferred',
            'standard'
        );

        $this->assertEquals(6000000, $result['corporate_tax']); // 15% of taxable income
        $this->assertEquals(16000000, $result['total_tax']);
    }
}
