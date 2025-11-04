<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * SalaryPayment model for salary payment records
 * 
 * @author Gin<gin_vn@haldata.net>
 * @lastupdate Gin<gin_vn@haldata.net>
 */
class SalaryPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'account_id',
        'payment_month',
        'payment_year',
        'base_salary',
        'overtime_hours',
        'overtime_rate',
        'bonus',
        'allowance',
        'deduction',
        'gross_salary',
        'income_tax',
        'social_insurance',
        'health_insurance',
        'unemployment_insurance',
        'net_salary',
        'payment_date',
        'payment_method', // 'bank_transfer', 'cash', 'check'
        'reference_number',
        'notes',
    ];

    protected $casts = [
        'base_salary' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
        'overtime_rate' => 'decimal:2',
        'bonus' => 'decimal:2',
        'allowance' => 'decimal:2',
        'deduction' => 'decimal:2',
        'gross_salary' => 'decimal:2',
        'income_tax' => 'decimal:2',
        'social_insurance' => 'decimal:2',
        'health_insurance' => 'decimal:2',
        'unemployment_insurance' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'payment_date' => 'date',
    ];

    /**
     * Get employee that owns this salary payment
     *
     * @return BelongsTo
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get account used for this payment
     *
     * @return BelongsTo
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
