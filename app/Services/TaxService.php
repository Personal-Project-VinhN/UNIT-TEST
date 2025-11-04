<?php

namespace App\Services;

/**
 * Tax service for calculating taxes for individuals and businesses
 * 
 * @author Gin<gin_vn@haldata.net>
 * @lastupdate Gin<gin_vn@haldata.net>
 */
class TaxService
{
    // Personal income tax brackets (Vietnam 2024)
    const PERSONAL_TAX_BRACKETS = [
        ['min' => 0, 'max' => 5000000, 'rate' => 0.05],      // 5% for 0-5M
        ['min' => 5000000, 'max' => 10000000, 'rate' => 0.10], // 10% for 5-10M
        ['min' => 10000000, 'max' => 18000000, 'rate' => 0.15], // 15% for 10-18M
        ['min' => 18000000, 'max' => 32000000, 'rate' => 0.20], // 20% for 18-32M
        ['min' => 32000000, 'max' => 52000000, 'rate' => 0.25], // 25% for 32-52M
        ['min' => 52000000, 'max' => 80000000, 'rate' => 0.30], // 30% for 52-80M
        ['min' => 80000000, 'max' => PHP_FLOAT_MAX, 'rate' => 0.35], // 35% for >80M
    ];

    // Personal deduction (Vietnam 2024)
    const PERSONAL_DEDUCTION = 11000000; // 11M per month
    const DEPENDENT_DEDUCTION = 4400000; // 4.4M per dependent per month

    // Corporate tax rate (Vietnam 2024)
    const CORPORATE_TAX_RATE = 0.20; // 20% for standard businesses
    const PREFERRED_CORPORATE_TAX_RATE = 0.15; // 15% for preferred sectors
    const SMALL_BUSINESS_TAX_RATE = 0.17; // 17% for small businesses

    // VAT rates (Vietnam)
    const VAT_RATE_ZERO = 0.00;   // 0% for exports
    const VAT_RATE_REDUCED = 0.05; // 5% for essential goods
    const VAT_RATE_STANDARD = 0.10; // 10% standard rate

    /**
     * Calculate personal income tax
     *
     * @param float $taxableIncome Taxable income (after deductions)
     * @param int $dependents Number of dependents
     * @return float
     */
    public function calculatePersonalIncomeTax(float $taxableIncome, int $dependents = 0): float
    {
        // Calculate total deductions
        $totalDeduction = self::PERSONAL_DEDUCTION + ($dependents * self::DEPENDENT_DEDUCTION);
        
        // Calculate taxable amount
        $taxableAmount = max(0, $taxableIncome - $totalDeduction);
        
        if ($taxableAmount <= 0) {
            return 0;
        }

        $totalTax = 0;
        $remainingAmount = $taxableAmount;

        foreach (self::PERSONAL_TAX_BRACKETS as $bracket) {
            if ($remainingAmount <= 0) {
                break;
            }

            $bracketMin = $bracket['min'];
            $bracketMax = $bracket['max'];
            $rate = $bracket['rate'];

            if ($taxableAmount > $bracketMin) {
                $taxableInBracket = min($remainingAmount, $bracketMax - $bracketMin);
                $totalTax += $taxableInBracket * $rate;
                $remainingAmount -= $taxableInBracket;
            }
        }

        return round($totalTax, 2);
    }

    /**
     * Calculate personal income tax from gross salary
     *
     * @param float $grossSalary
     * @param int $dependents
     * @return float
     */
    public function calculatePersonalIncomeTaxFromGross(float $grossSalary, int $dependents = 0): float
    {
        return $this->calculatePersonalIncomeTax($grossSalary, $dependents);
    }

    /**
     * Calculate corporate income tax
     *
     * @param float $taxableIncome
     * @param string $businessType 'standard', 'preferred', or 'small'
     * @return float
     */
    public function calculateCorporateIncomeTax(float $taxableIncome, string $businessType = 'standard'): float
    {
        if ($taxableIncome <= 0) {
            return 0;
        }

        $rate = match ($businessType) {
            'preferred' => self::PREFERRED_CORPORATE_TAX_RATE,
            'small' => self::SMALL_BUSINESS_TAX_RATE,
            default => self::CORPORATE_TAX_RATE,
        };

        return round($taxableIncome * $rate, 2);
    }

    /**
     * Calculate VAT
     *
     * @param float $amount
     * @param string $vatType 'zero', 'reduced', or 'standard'
     * @param bool $includeVat Whether amount includes VAT or not
     * @return array ['amount' => float, 'vat' => float, 'total' => float]
     */
    public function calculateVAT(float $amount, string $vatType = 'standard', bool $includeVat = false): array
    {
        $rate = match ($vatType) {
            'zero' => self::VAT_RATE_ZERO,
            'reduced' => self::VAT_RATE_REDUCED,
            default => self::VAT_RATE_STANDARD,
        };

        if ($includeVat) {
            // Amount includes VAT, calculate base amount
            $baseAmount = $amount / (1 + $rate);
            $vat = $amount - $baseAmount;
        } else {
            // Amount excludes VAT
            $baseAmount = $amount;
            $vat = $amount * $rate;
        }

        return [
            'amount' => round($baseAmount, 2),
            'vat' => round($vat, 2),
            'total' => round($baseAmount + $vat, 2),
        ];
    }

    /**
     * Calculate total tax for business transaction
     *
     * @param float $revenue
     * @param float $expenses
     * @param string $businessType
     * @param string $vatType
     * @return array
     */
    public function calculateBusinessTax(
        float $revenue,
        float $expenses,
        string $businessType = 'standard',
        string $vatType = 'standard'
    ): array {
        // Calculate VAT on revenue
        $vatDetails = $this->calculateVAT($revenue, $vatType, false);
        
        // Calculate taxable income (revenue - expenses)
        $taxableIncome = $revenue - $expenses;
        
        // Calculate corporate income tax
        $corporateTax = $this->calculateCorporateIncomeTax($taxableIncome, $businessType);

        return [
            'revenue' => $revenue,
            'expenses' => $expenses,
            'taxable_income' => $taxableIncome,
            'vat' => $vatDetails['vat'],
            'corporate_tax' => $corporateTax,
            'total_tax' => $vatDetails['vat'] + $corporateTax,
            'net_profit' => $taxableIncome - $corporateTax,
        ];
    }
}
