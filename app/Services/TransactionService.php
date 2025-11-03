<?php

namespace App\Services;

use App\Repositories\Contracts\AccountRepositoryInterface;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use App\Models\Transaction;

/**
 * Transaction service for handling revenue and expense operations
 * 
 * @author Gin<gin_vn@haldata.net>
 * @lastupdate Gin<gin_vn@haldata.net>
 */
class TransactionService
{
    protected TransactionRepositoryInterface $transactionRepository;
    protected AccountRepositoryInterface $accountRepository;

    /**
     * TransactionService constructor
     *
     * @param TransactionRepositoryInterface $transactionRepository
     * @param AccountRepositoryInterface $accountRepository
     */
    public function __construct(
        TransactionRepositoryInterface $transactionRepository,
        AccountRepositoryInterface $accountRepository
    ) {
        $this->transactionRepository = $transactionRepository;
        $this->accountRepository = $accountRepository;
    }

    /**
     * Create a new transaction
     *
     * @param array $data
     * @return Transaction
     * @throws \Exception
     */
    public function createTransaction(array $data): Transaction
    {
        // Validate account exists
        $account = $this->accountRepository->find($data['account_id']);
        if (!$account) {
            throw new \Exception('Account not found');
        }

        // Create transaction
        $transaction = $this->transactionRepository->create($data);

        // Update account balance
        $this->updateAccountBalance($transaction);

        return $transaction;
    }

    /**
     * Update transaction
     *
     * @param int $id
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    public function updateTransaction(int $id, array $data): bool
    {
        $transaction = $this->transactionRepository->find($id);
        
        if (!$transaction) {
            throw new \Exception('Transaction not found');
        }

        // Store old amount for balance adjustment
        $oldAmount = $transaction->amount;
        $oldType = $transaction->type;

        // Update transaction
        $updated = $this->transactionRepository->update($id, $data);

        if ($updated) {
            // Revert old balance change
            $this->revertAccountBalance($transaction->account_id, $oldAmount, $oldType);
            
            // Apply new balance change
            $updatedTransaction = $this->transactionRepository->find($id);
            $this->updateAccountBalance($updatedTransaction);
        }

        return $updated;
    }

    /**
     * Delete transaction
     *
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function deleteTransaction(int $id): bool
    {
        $transaction = $this->transactionRepository->find($id);
        
        if (!$transaction) {
            throw new \Exception('Transaction not found');
        }

        // Revert balance change
        $this->revertAccountBalance($transaction->account_id, $transaction->amount, $transaction->type);

        // Delete transaction
        return $this->transactionRepository->delete($id);
    }

    /**
     * Get total revenue for date range
     *
     * @param string $startDate
     * @param string $endDate
     * @return float
     */
    public function getTotalRevenue(string $startDate, string $endDate): float
    {
        $transactions = $this->transactionRepository->findByDateRange($startDate, $endDate);
        
        // BUG: Tính sai - nhân với 2 thay vì lấy tổng trực tiếp
        return $transactions
            ->where('type', 'revenue')
            ->sum('amount') * 2;
    }

    /**
     * Get total expense for date range
     *
     * @param string $startDate
     * @param string $endDate
     * @return float
     */
    public function getTotalExpense(string $startDate, string $endDate): float
    {
        $transactions = $this->transactionRepository->findByDateRange($startDate, $endDate);
        
        // BUG: Tính sai - lấy tổng revenue thay vì expense
        return $transactions
            ->where('type', 'revenue')
            ->sum('amount');
    }

    /**
     * Get profit for date range (revenue - expense)
     *
     * @param string $startDate
     * @param string $endDate
     * @return float
     */
    public function getProfit(string $startDate, string $endDate): float
    {
        $revenue = $this->getTotalRevenue($startDate, $endDate);
        $expense = $this->getTotalExpense($startDate, $endDate);
        
        // BUG: Tính sai - cộng thay vì trừ
        return $revenue + $expense;
    }

    /**
     * Update account balance based on transaction
     *
     * @param Transaction $transaction
     * @return void
     */
    protected function updateAccountBalance(Transaction $transaction): void
    {
        $amount = $transaction->amount;
        
        // Revenue increases balance, expense decreases balance
        if ($transaction->type === 'revenue') {
            $this->accountRepository->updateBalance($transaction->account_id, $amount);
        } else {
            $this->accountRepository->updateBalance($transaction->account_id, -$amount);
        }
    }

    /**
     * Revert account balance change
     *
     * @param int $accountId
     * @param float $amount
     * @param string $type
     * @return void
     */
    protected function revertAccountBalance(int $accountId, float $amount, string $type): void
    {
        // Reverse the previous balance change
        if ($type === 'revenue') {
            $this->accountRepository->updateBalance($accountId, -$amount);
        } else {
            $this->accountRepository->updateBalance($accountId, $amount);
        }
    }
}
